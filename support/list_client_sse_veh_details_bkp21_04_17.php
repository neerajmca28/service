<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/
 
?> 


<div class="top-bar">
                    
        <h1>Client Vehicle and SSE details</h1>
  </div>
    <div class="top-bar">
        <div style="float:right";><a href="reportfiles/client_vehicle_sse_details.xls">Create Excel</a><br/></div>
        <br/>
    </div>    
    <div class="table">
<?php
 
if($_SESSION['username']=="dservicehead")
{
    $query = select_query_live_con("select * from matrix.users where users.sys_parent_user=1 and sys_active=true");
}
else
{
    $query = select_query_live_con("select * from matrix.users where users.sys_parent_user=1 and sys_active=true and branch_id=1");
}
?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>SL.No</th>
            <th>User Name</th>
            <th>Contact Number</th>
            <th>Contact Name</th>
            <th>Company Name</th>
            <th>Address</th>
            <th>Type of Vehicle</th>
            <th>Total Vehicle</th>
            <th>Creation Date</th>
            <th>Branch</th>
            <th>SSE</th>
        </tr>
    </thead>
    <tbody>

<?php 
                    
        $excel_data.= '<table cellspacing="5" cellpadding="5" border="1">
                        <thead>
                                <tr><td colspan="9" align="center"><strong>Client Vehicle and SSE Details</strong></td></tr>
                                <tr><td colspan="9"></td></tr>
                                <tr>
                                        <th width="15%">User Name</th>
                                        <th width="15%">Contact Number</th>
                                        <th width="15%">Contact Name</th>
                                        <th width="15%">Company Name</th>
                                        <th width="15%">Type of Vehicle</th>
                                        <th width="15%">Total Vehicle</th>
                                        <th width="10%">Creation Date</th>
                                        <th width="10%">Branch</th>
                                        <th width="10%">SSE</th>
                                </tr>
                     </thead>
                     <tbody>';
                     

//echo mysql_num_rows($query);
//while($row=mysql_fetch_array($query))
for($i=0;$i<count($query);$i++)
{ 
    $user_id = $query[$i]["id"];
    
    
    $group_user_id = select_query_live_con("SELECT sys_group_id,name FROM matrix.group_users left join matrix.group on group_users.sys_group_id=`group`.id  where group_users.sys_user_id='".$user_id."'");
    $group_id =  $group_user_id[0]['sys_group_id'];
    $company_name =  $group_user_id[0]['name'];

    /*$active_query = select_query_live_con("SELECT sys_service_id,sys_added_date FROM matrix.group_services WHERE active=1 AND sys_group_id='".$group_id."'");
    $total_vehicle = count($active_query);
    if($total_vehicle>=0)
    {*/
        if($query[$i]["default_vehicle_type"]==1){ $vehicle = "Truck";} 
        elseif($query[$i]["default_vehicle_type"]==0){ $vehicle = "Taxi";}
        
        if($query[$i]["branch_id"]==1){ $branch = "Delhi";}
        elseif($query[$i]["branch_id"]==2){ $branch = "Mumbai";}
        elseif($query[$i]["branch_id"]==3){ $branch = "Jaipur";}
        elseif($query[$i]["branch_id"]==4){ $branch = "Sonipath";}
        elseif($query[$i]["branch_id"]==6){ $branch = "Ahmedabad";}
        elseif($query[$i]["branch_id"]==7){ $branch = "kolkata";}
    
?>

<tr align="center">

    <td><?php echo $i+1;?></td>
    <td><?php echo $query[$i]["sys_username"];?></td>
     <td><?php echo $query[$i]["mobile_number"];?></td>
      <td><?php echo $query[$i]["fullname"];?></td>
    <td><?php echo $company_name;?></td>
    <td><?php echo wordwrap($query[$i]["address"],30, "<br />");?></td>
    <td><?php echo $vehicle;?></td>
    <td><?php echo $total_vehicle;?></td>
    <td><?php echo $query[$i]["sys_added_date"];?></td>
    <td><?php echo $branch;?></td>
    <td><?php echo $query[$i]["telecaller"];?></td>

</tr> 
<?php 
    
    
    $excel_data.="<tr>
                         <td>".$query[$i]["sys_username"]."</td>
                         <td>".$query[$i]["mobile_number"]."</td>
                         <td>".$query[$i]["fullname"]."</td>
                         <td>".$company_name."</td>
                         <td>".$vehicle."</td>
                         <td>".$total_vehicle."</td>
                         <td>".$query[$i]["sys_added_date"]."</td>
                         <td>".$branch."</td>
                         <td>".$query[$i]["telecaller"]."</td>
                 </tr>";
    //}
    
}
    
    $excel_data.='</tbody></table>';
?>
</table>
     
   <div id="toPopup"> 
        
        <div class="close">close</div>
           <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
        <div id="popup1"> <!--your content start-->
            

 
        </div> <!--your content end-->
    
    </div> <!--toPopup end-->
    
    <div class="loader"></div>
       <div id="backgroundPopup"></div>
</div>
 
<?php

unlink(__DOCUMENT_ROOT.'/support/reportfiles/client_vehicle_sse_details.xls');
$filepointer=fopen(__DOCUMENT_ROOT.'/support/reportfiles/client_vehicle_sse_details.xls','w');
fwrite($filepointer,$excel_data);
fclose($filepointer);



include("../include/footer.inc.php"); ?>