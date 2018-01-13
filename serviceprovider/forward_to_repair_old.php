<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");  */
?>

<div class="top-bar">
  <h1>Service List</h1>
</div>
<div class="top-bar">
  <div style="float:right";><font style="color:#ADFF2F;font-weight:bold;">GreenYellow:</font> Urgent Service</div>
  <br/>
  <div style="float:right";><font style="color:#CCCCCC;font-weight:bold;">Grey:</font> Closed Service</div>
</div>
<div class="table">
  <?php
 
	$rs = select_query("SELECT * FROM services  where (service_status='11' and ((fwd_tech_rm_id is NOT NULL and fwd_reason is NOT NULL) or (fwd_repair_id is NOT NULL or fwd_serv_to_repair is NOT NULL))) and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc");


?>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      <tr>
        <th>Sl. No</th>
        <th>Request By </th>
        <th>Request Date </th>
        <th><font color="#0E2C3C"><b>Client Name </b></font></th>
        <th><font color="#0E2C3C"><b>Vehicle No <br/>
          (IP Box/ Required)</b></font></th>
        <th><font color="#0E2C3C"><b>Device IMEI</b></font></th>
        <th><font color="#0E2C3C"><b>Notworking </b></font></th>
        <th><font color="#0E2C3C"><b>Location</b></font></th>
        <th><font color="#0E2C3C"><b>Available Time</b></font></th>
        <th><font color="#0E2C3C"><b>Client Contact No.</b></font></th>
        <th><font color="#0E2C3C"><b>Installer Name</b></font></th>
        <th><font color="#0E2C3C"><b>Comment</b></font></th>
        <th>View Detail</th>
      </tr>
    </thead>
    <tbody>
      <?php 
	
    //while ($row = mysql_fetch_array($rs)) 
	for($i=0;$i<count($rs);$i++)
	{
		if($rs[$i]['IP_Box']=="") $ip_box="Not Required";  else $ip_box="$rs[$i][IP_Box]"; 
	
    ?>
      
      <!-- <tr align="Center" <? if(($rs[$i]['reason'] && $rs[$i]['time']) ||  $rs[$i]['back_reason']) { ?> style="background:#CCCCCC" <? }?>> -->
      <tr align="Center"   <? if($rs[$i]['service_status']==5 or $rs[$i]['service_status']==6 )  {  ?> style="background:#CCCCCC;" <? }
	else  if($rs[$i]['required']=='urgent'){ ?>style="background:#ADFF2F" <? } ?>>
        <td><?php echo $i+1; ?></td>
        <td>&nbsp;<?php echo $rs[$i]['request_by'];?></td>
        <td>&nbsp;<?php echo $rs[$i]['req_date'];?></td>
        <td>&nbsp;<?php echo $rs[$i]['name'];?></td>
        <td>&nbsp;<?php echo $rs[$i]['veh_reg']." <br/><br/>(".$ip_box.")";?></span></td>
        <td>&nbsp;<?php echo  $rs[$i]['device_imei'] ?></td>
        <td>&nbsp;<?php echo  $rs[$i]['Notwoking'] ?></td>
        <?php if($rs[$i]['location']!=""){?>
        <td>&nbsp;<?php echo $rs[$i]['location'];?></td>
        <?php }else{ $city= select_query("select * from tbl_city_name where branch_id='".$rs[$i]['inter_branch']."'");?>
        <td>&nbsp;<?php echo $city[0]['city'];?></td>
        <?php }?>
        <td>&nbsp;<?php echo $rs[$i]['atime'] ;?></td>
        <td  style="font-size:12px">&nbsp;<?php echo $rs[$i]['cnumber'] ;?></td>
        <td  style="font-size:12px">&nbsp;<strong><?php echo $rs[$i]['inst_name'];
		if($rs[$i]['job_type']==2)
		{
			echo "<br/><font color='red'>(pending Job)</font>";
		}
		else
		{
			echo "<br/>(Ongoing Job)";
		}
		?> </strong></td>
        <td><?php if($rs[$i]['fwd_reason'] != "" && $rs[$i]['fwd_tech_rm_id'] != "" && $rs[$i]['fwd_repair_id'] == "") { 
					echo $rs[$i]['fwd_datetime'].' - '.$rs[$i]['fwd_reason'];
				} else if($rs[$i]['fwd_serv_to_repair'] != "" && $rs[$i]['fwd_repair_id'] == "") {
					echo $rs[$i]['fwd_serv_to_repair']; 
				} else if($rs[$i]['fwd_serv_to_repair'] != "" && $rs[$i]['fwd_repair_id'] != "") {
					echo $rs[$i]['fwd_repair_date'].' - '.$rs[$i]['fwd_serv_to_repair']; 
				}?>
        </td>
        <td><a href="#" onclick="Show_record(<?php echo $rs[$i]["id"];?>,'services','popup1'); " class="topopup">View Detail</a></td>
      </tr>
      <?php  
    }
	 
    ?>
  </table>
  <div id="toPopup">
    <div class="close">close</div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup1" style ="height:100%;width:100%"> <!--your content start--> 
      
    </div>
    <!--your content end--> 
    
  </div>
  <!--toPopup end-->
  
  <div class="loader"></div>
  <div id="backgroundPopup"></div>
</div>
<?
include("../include/footer.inc.php");

?>
