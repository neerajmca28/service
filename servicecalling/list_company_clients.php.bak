<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/private/master.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); 
include($_SERVER['DOCUMENT_ROOT']."/service/private/master.php");*/

$masterObj = new master();

?>
 
 <div class="top-bar">
              <div align="center">
 <form name="myformlisting" method="post" action="">
    <select name="Showrequest" id="Showrequest" onChange="form.submit();" >
        <option value="" <? if($_POST['Showrequest']==''){ echo "Selected"; }?>>All</option>
        <option value="1" <? if($_POST['Showrequest']==1){ echo "Selected"; }?>>0-10</option>
        <option value="2" <? if($_POST['Showrequest']==2){ echo "Selected" ;}?>>11-50</option>
        <option value="3" <? if($_POST['Showrequest']==3){ echo "Selected"; }?>>51-100</option>
        <option value="4" <? if($_POST['Showrequest']==4){ echo "Selected"; }?>>101-200</option>
        <option value="5" <? if($_POST['Showrequest']==5){ echo "Selected"; }?>>200 Above</option>
    
    </select>
</form>
        </div>       
<div align="center">
</div>
    
    <h1>Client List</h1>
      
</div>
<div class="table">
<?php

  $query1 = select_query("SELECT * FROM internalsoftware.users where branch_id=1 and (telecaller='".$_SESSION['username']."' or ((temp_telecaller='".$_SESSION['username']."') and (temp_from_date>='".date("Y-m-d")."' or temp_to_date >='".date("Y-m-d")."'))) order by sys_username"); 
  
   //while($row=mysql_fetch_array($query1))
   for($i=0;$i<count($query1);$i++)
    {
        $client_id.= $query1[$i]['id'].",";
    }
    $client_rslt=substr($client_id,0,strlen($client_id)-1);
    
	 /*$row_data = select_query_live("SELECT group_users.sys_user_id,COUNT(*) AS veh_total FROM matrix.group_users LEFT JOIN matrix.group_services ON 
	group_users.sys_group_id=group_services.sys_group_id WHERE sys_user_id IN ($client_rslt) GROUP BY sys_user_id");*/

	$row_data = $masterObj->getUserVehicleVal($client_rslt);
	//echo "<pre>";print_r($row_data);die;
	
//while($row_data=mysql_fetch_array($total_veh))
for($j=0;$j<count($row_data);$j++)
{
    if($row_data[$j]['veh_total']>=0 && $row_data[$j]['veh_total']<=10)
    {
        $user_id.= $row_data[$j]['sys_user_id'].",";
    }
    elseif($row_data[$j]['veh_total']>=11 && $row_data[$j]['veh_total']<=50)
    {
        $user_id11.= $row_data[$j]['sys_user_id'].",";
    }
    elseif($row_data[$j]['veh_total']>=51 && $row_data[$j]['veh_total']<=100)
    {
        $user_id51.= $row_data[$j]['sys_user_id'].",";
    }
    elseif($row_data[$j]['veh_total']>=101 && $row_data[$j]['veh_total']<=200)
    {
        $user_id101.= $row_data[$j]['sys_user_id'].",";
    }
    elseif($row_data[$j]['veh_total']>=201)
    {
        $user_id201.= $row_data[$j]['sys_user_id'].",";
    }
}

$user_id_rslt=substr($user_id,0,strlen($user_id)-1);

$user_id_rslt11=substr($user_id11,0,strlen($user_id11)-1);

$user_id_rslt51=substr($user_id51,0,strlen($user_id51)-1);

$user_id_rslt101=substr($user_id101,0,strlen($user_id101)-1);

$user_id_rslt201=substr($user_id201,0,strlen($user_id201)-1);


if($_POST["Showrequest"]==1)
 {
    $query = select_query("SELECT * FROM users where id in ($user_id_rslt) order by sys_username");         
 }
 else if($_POST["Showrequest"]==2)
 {
     $query = select_query("SELECT * FROM internalsoftware.users where id in ($user_id_rslt11) order by sys_username");
 }
 else if($_POST["Showrequest"]==3)
 {
     $query = select_query("SELECT * FROM internalsoftware.users where id in ($user_id_rslt51) order by sys_username");
 }
 else if($_POST["Showrequest"]==4)
 {
     $query = select_query("SELECT * FROM internalsoftware.users where id in ($user_id_rslt101) order by sys_username");
 }
 else if($_POST["Showrequest"]==5)
 {
     $query = select_query("SELECT * FROM internalsoftware.users where id in ($user_id_rslt201) order by sys_username");
 }
 else
 { 
    $WhereQuery = "where branch_id=1 and (telecaller='".$_SESSION['username']."' or ((temp_telecaller='".$_SESSION['username']."') and (temp_from_date>='".date("Y-m-d")."' or temp_to_date >='".date("Y-m-d")."')))";
    
    $query = select_query("SELECT * FROM internalsoftware.users ". $WhereQuery." order by sys_username");   

 }
 

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>SL No</th>
            <th>Users</th>
            <th>Company Name</th>
            <th>Phone No</th>
            <!--<th>Assign To</th>
            <th>Assign From - To</th>
            <th>Edit</th>-->
        </tr>
    </thead>
    <tbody>
 
<?php 

//while($row=mysql_fetch_array($query))
for($k=0;$k<count($query);$k++)
{
    
?>

<tr align="center">
 
  <td><?php echo $k+1; ?></td>
  <td><?php echo $query[$k]["sys_username"];?></td>
  <td><?php echo $query[$k]["company"];?></td>
  <td><?php echo $query[$k]["mobile_number"];?></td>
  <!--<td><?php echo $query[$k]["temp_telecaller"];?></td>
  <td><?php if($query[$k]["temp_telecaller"]!=""){echo $query[$k]["temp_from_date"]." - ".$query[$k]["temp_to_date"];}else{}?></td>
  <td><a href="client_assign.php?id=<?=$query[$k]['id'];?>&action=edit<? echo $pg;?>">Edit</a></td>-->
  
</tr> 
<?php }?>
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
include("../include/footer.php"); ?>



 