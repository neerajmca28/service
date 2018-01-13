<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

if($_SESSION['BranchId'] == 1)
{

	$telecaller = select_query("select * from internalsoftware.telecaller_users where login_name='".$_SESSION['username']."' and `status`='1'  
								and branch_id='".$_SESSION['BranchId']."'");
	
	$assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
					branch_id='".$_SESSION['BranchId']."' and sys_active='1' and telecaller_id='".$telecaller[0]['id']."' AND Userid NOT IN(1,2143)
					order by client_type"); 

}else {
	
	$assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
					branch_id='".$_SESSION['BranchId']."' and sys_active='1' AND Userid NOT IN(1,2143) order by client_type"); 

}

for($i=0;$i<count($assign_client);$i++)
    {
        $client_id.= $assign_client[$i]['Userid'].",";
    }
	
    $user = substr($client_id,0,strlen($client_id)-1);

?>

<div class="top-bar">
  <div align="center">
    <form name="myformlisting" method="post" action="">
      <select name="Showrequest" id="Showrequest" onChange="form.submit();" >
        <option value="0" <? if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>
        <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending</option>
        <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Closed</option>
        <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
      </select>
    </form>
  </div>
  <h1>New Device Addition List</h1>
</div>
<div class="top-bar">
  <div style="float:right";><font style="color:#68C5CA;font-weight:bold;">Blue:</font> Back from support</div>
  <br/>
  <div style="float:right";><font style="color:#F2F5A9;font-weight:bold;">Yellow:</font> Back from Admin</div>
  <br/>
  <div style="float:right";><font style="color:#99FF66;font-weight:bold;">Green:</font> Completed your requsest.</div>
</div>
<div class="table">
  <?php

 if($_POST["Showrequest"]==1)
 {
	  $WhereQuery=" where user_id IN($user) and approve_status=1 and final_status=1 ";
 }
 else if($_POST["Showrequest"]==2)
 {
	 $WhereQuery=" where user_id IN($user)";
 }
 else
 {
	 $WhereQuery=" where user_id IN($user) and (approve_status=0 or approve_status=1) and final_status!=1 ";
 }
 
$query = select_query("SELECT * FROM new_device_addition  ". $WhereQuery." order by date DESC ");

?>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      <tr>
        <th>SL.No</th>
        <th>Date</th>
        <th>Sales Manager</th>
        <th>Client</th>
        <th>Company Name</th>
        <th>Vehicles No</th>
        <th>IMEI</th>
        <th>Model</th>
        <th>Device Type</th>
        <th>Billing</th>
        <th>Status</th>
        <th>View Detail</th>
      </tr>
    </thead>
    <tbody>
      <?php 

for($i=0;$i<count($query);$i++)
{
?>
      <tr align="center" <? if( $query[$i]["support_comment"]!="" && $query[$i]["final_status"]!=1 ){ echo 'style="background-color:#68C5CA"';} elseif($query[$i]["final_status"]==1){ echo 'style="background-color:#99FF66"';}elseif($query[$i]["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';}elseif( $query[$i]["admin_comment"]!="" && $query[$i]["final_status"]!=1 && $query[$i]["service_comment"]==""){ echo 'style="background-color:#F2F5A9"';}?> >
        <td><?php echo $i+1;?></td>
        <td><?php echo $query[$i]["date"];?></td>
        <td><?php echo $query[$i]["sales_manager"];?></td>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$query[$i]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <td><?php echo $rowuser[0]["sys_username"];?></td>
        <td><?php echo $query[$i]["client"];?></td>
        <td><?php echo $query[$i]["vehicle_no"];?></td>
        <td><?php echo $query[$i]["device_imei"];?></td>
        <td><?php echo $query[$i]["device_model"];?></td>
        <td><?php echo $query[$i]["device_type"];?></td>
        <? /*if($query[$i]["device_type"]=='New'){
    $billing_status=$query[$i]["billing"];
    }
    else{
    $billing_status=$query[$i]["billing_if_old_device"];
    }*/
        ?>
        <td><?php echo $query[$i]["billing"]; ?></td>
       <!-- <td><?php echo $query[$i]["billing_if_no_reason"];?></td>-->
        <td><?  if($query[$i]["new_device_status"]==2 || ($query[$i]["support_comment"]!="" && $query[$i]["service_comment"]=="")){echo "Reply Pending at Request Side";}
    elseif($query[$i]["new_device_status"]==1 && $query[$i]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($query[$i]["final_status"]==1){echo "Process Done";}?></td>
        <td><a href="#" onClick="Show_record(<?php echo $query[$i]["id"];?>,'new_device_addition','popup1'); " class="topopup">View Detail</a></td>
      </tr>
      <?php }?>
  </table>
  <div id="toPopup">
    <div class="close">close</div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup1"> <!--your content start--> 
      
    </div>
    <!--your content end--> 
    
  </div>
  <!--toPopup end-->
  
  <div class="loader"></div>
  <div id="backgroundPopup"></div>
</div>
<?php
include("../include/footer.php"); ?>
