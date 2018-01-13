<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu_kpm.php');

$userId = '77727';
$groupId = '7781';
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
    <a href="new_installation.php" class="button">ADD NEW</a> 
  <h1>New Device Addition List</h1>
</div>
<div class="top-bar">
  <!--<div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font> Approved</div>
  <br/>
  <div style="float:right";><font style="color:#68C5CA;font-weight:bold;">Blue:</font> Back from support</div>
  <br/>
  <div style="float:right";><font style="color:#F2F5A9;font-weight:bold;">Yellow:</font> Back from Admin</div>
  <br/>
  <div style="float:right";><font style="color:#CFBF7E;font-weight:bold;">Brown:</font>Admin forward request</div>
  <br/>
  <div style="float:right";><font style="color:#99FF66;font-weight:bold;">Green:</font> Completed your requsest.</div>-->
</div>
<div class="table">
  <?php
 
 if($_POST["Showrequest"]==1)
 {
	  $WhereQuery=" where approve_status!=1 and final_status=1 and (acc_manager='".$_SESSION['user_name']."' or forward_req_user='".$_SESSION["user_name"]."')";
 }
 else if($_POST["Showrequest"]==2)
 {
	 $WhereQuery=" where (acc_manager='".$_SESSION['user_name']."' or forward_req_user='".$_SESSION["user_name"]."') ";
 }
 else
 { 
	 $WhereQuery=" where final_status!=1 and (service_comment is null or new_device_status=2) and (acc_manager='".$_SESSION['user_name']."' or (forward_req_user='".$_SESSION["user_name"]."' and (forward_back_comment is null or forward_back_comment='')))";
	   
 }

$query = select_query("SELECT * FROM new_device_addition  ". $WhereQuery."   order by date DESC ");
 

?>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      <tr>
        <!--<th>SL.No</th>-->
        <th>Date</th>
        <th>Customer Name</th>
        <th>Truck No</th>
        <th>Device Imei</th>
        <th>Destination</th>
        <th>Ware House</th>
        <th>Transport Name</th>
        <th>Transport Mob No</th>
        <th>LR Number</th>
        <th>Driver Mob No</th>
        <th>Trip</th>
        <th>Rate</th>
        <th>Edit</th> 
      </tr>
    </thead>
    <tbody>
      <?php 
	for($i=0;$i<count($query);$i++)
	{
	?>
      <tr align="center">
        <!--<td><?php echo $i+1;?></td>-->
        <td><?php echo $query[$i]["date"];?></td>
        <td><?php echo $query[$i]["sales_manager"];?></td>
        <td><?php echo $rowuser[$i]["sys_username"];?></td>
        <td><?php echo $query[$i]["vehicle_no"];?></td>
        <td><?php echo $query[$i]["vehicle_no"];?></td>
        <td><?php echo $query[$i]["device_imei"];?></td>
        <td><?php echo $query[$i]["device_type"];?></td>
        <td><?php echo $query[$i]["inst_name"];?></td>
        <td><?php echo $query[$i]["dimts"];?></td>
        <td><?php echo $query[$i]["date"];?></td>
        <td><?php echo $query[$i]["sales_manager"];?></td>
        <td><?php echo $rowuser[$i]["sys_username"];?></td>
        <td><a href="new_installation.php?id=<?=$query[$i]['id'];?>&action=edit<? echo $pg;?>">Edit</a> </td>
        
        
      </tr>
      
<?php  }?>

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
