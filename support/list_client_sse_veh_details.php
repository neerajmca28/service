<?php
session_start();
ini_set('max_execution_time', 200);
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
 
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
    $query = select_query("select ad.id, ad.Userid, ad.UserName, ad.sys_parent_user,ad.sys_active, ad.company, ad.address,
                            ad.mobile_number, ad.potential, ad.current_vehicle, ad.Branch_id, ad.Date, cc.category_type, tu.name
                            from internalsoftware.addclient as ad left join client_category as cc
                            on ad.client_type=cc.id left join telecaller_users as tu on ad.telecaller_id=tu.id
                            where ad.sys_parent_user=1 and ad.sys_active='1' order by ad.Date desc");
}
else
{
    $query = select_query("select ad.id, ad.Userid, ad.UserName, ad.sys_parent_user,ad.sys_active, ad.company, ad.address,
                            ad.mobile_number, ad.potential, ad.current_vehicle, ad.Branch_id, ad.Date, cc.category_type, tu.name
                            from internalsoftware.addclient as ad left join client_category as cc
                            on ad.client_type=cc.id left join telecaller_users as tu on ad.telecaller_id=tu.id
                            where ad.sys_parent_user=1 and ad.sys_active='1' and ad.Branch_id='".$_SESSION['BranchId']."' order by ad.Date desc");
}
?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>SL.No</th>
            <th>User Name</th>
            <th>Company Name</th>
            <th>Address</th>
            <th>Creation Date</th>
            <th>Contact Number</th>
            <th>Potential</th>
            <th>Current Vehicle</th>
            <th>Branch</th>
            <th>SSE</th>
            <th>Category</th>
            <?php if($_SESSION['username']=="dservicehead"){?>
            <th>Add SSE</th> 
            <?php } ?>
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
                                        <th width="15%">Company Name</th>
                                        <th width="10%">Contact Number</th>
                                        <th width="10%">Potential</th>
                                        <th width="15%">Current Vehicle</th>
                                        <th width="10%">Creation Date</th>
                                        <th width="10%">Branch</th>
                                        <th width="10%">SSE</th>
                                        <th width="10%">Category</th>
                                </tr>
                     </thead>
                     <tbody>';
                     

//echo mysql_num_rows($query);

for($i=0;$i<count($query);$i++)
{   
        
        if($query[$i]["Branch_id"]==1){ $branch = "Delhi";}
        elseif($query[$i]["Branch_id"]==2){ $branch = "Mumbai";}
        elseif($query[$i]["Branch_id"]==3){ $branch = "Jaipur";}
        elseif($query[$i]["Branch_id"]==4){ $branch = "Sonipath";}
        elseif($query[$i]["Branch_id"]==6){ $branch = "Ahmedabad";}
        elseif($query[$i]["Branch_id"]==7){ $branch = "kolkata";}
    
?>

<tr align="center">

    <td><?php echo $i+1;?></td>
    <td><?php echo $query[$i]["UserName"];?></td>
    <td><?php echo $query[$i]["company"];?></td>
    <td><?php echo wordwrap($query[$i]["address"],30, "<br />");?></td>
    <td><?php echo $query[$i]["Date"];?></td>
    <td><?php echo $query[$i]["mobile_number"];?></td>
    <td><?php echo $query[$i]["potential"];?></td>
    <td><?php echo $query[$i]["current_vehicle"];?></td>
    <td><?php echo $branch;?></td>
    <td><?php echo $query[$i]["name"];?></td>
    <td><?php echo $query[$i]["category_type"];?></td>
    <?php if($_SESSION['username']=="dservicehead"){?>
    <td><a href="telecaller-iframe.php?id=<?= $query[$i]["Userid"]?>&req_id=25&height=220&width=330" class="thickbox" >Add SSE</a>  </td>
   <?php } ?>

</tr> 
<?php 
    
    
    $excel_data.="<tr>
                         <td>".$query[$i]["UserName"]."</td>
                         <td>".$query[$i]["company"]."</td>
                         <td>".$query[$i]["mobile_number"]."</td>
                         <td>".$query[$i]["potential"]."</td>
                         <td>".$query[$i]["current_vehicle"]."</td>
                         <td>".$query[$i]["Date"]."</td>
                         <td>".$branch."</td>
                         <td>".$query[$i]["name"]."</td>
                         <td>".$query[$i]["category_type"]."</td>
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