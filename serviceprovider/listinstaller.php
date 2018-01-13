<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

	/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
	include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");  
	include($_SERVER['DOCUMENT_ROOT']."/service/sqlconnection.php");*/
	
/*select installer.inst_name,name,user_id,company_name,job_type from installer left join services  on
 installer.inst_id=services.inst_id and installer.branch_id=1
 where installer.inst_id =29
 
 select installer.inst_name from installer left join installation  on
 installer.inst_id= installation.inst_id and installer.branch_id=1
 where installer.inst_id =29*/
 
 
?>

<div class="top-bar">
  <h1>Installer List</h1>
</div>
<div class="table">
  <?  
	 
	$result = mssql_query("select device.device_id,device.device_imei,device.itgc_id,sim.sim_no,device.dispatch_date,installerid from ChallanDetail
left join device on ChallanDetail.deviceid=device.device_id
left join sim on device.sim_id=sim.sim_id
 where  device.device_status=64 and ChallanDetail.branchid=1");
	/*select installer.inst_name,name,user_id,company_name,job_type from installer left join services  on
 installer.inst_id=services.inst_id and installer.branch_id=1
 where installer.inst_id =29*/
  

	?>
  <div style="float:right"><a href="installations.php?mode=new">New</a> | <a href="installations.php?mode=close">Closed</a></div>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      <tr>
        <th>Sl. No</th>
        <th>Device Id </th>
        <th>Device IMEI </th>
        <th  ><font color="#0E2C3C"><b>Itgc ID </b></font></th>
        <th  ><font color="#0E2C3C"><b>Mobile Num </b></font></th>
        <th  ><font color="#0E2C3C"><b>Dispatch Date</b></font></th>
        <th ><font color="#0E2C3C"><b>Installer Name</b></font></th>
      </tr>
    </thead>
    <tbody>
      <?php 
	$i=1;
   while($row = mssql_fetch_array($result))
{ 
    ?>
      <tr align="Center">
        <td><?php echo $i ?></td>
        <td>&nbsp;<?php echo $row["device_id"];?></td>
        <td>&nbsp;<?php echo $row["device_imei"]?></td>
        <td  align="center"><?php echo $row["itgc_id"]?></td>
        <td  align="center">&nbsp;<?php echo $row["sim_no"]?></td>
        <td  align="center">&nbsp;<?php echo $row["dispatch_date"]?></td>
        <td   align="center">&nbsp;<?php echo $row["installerid"]?></td>
      </tr>
      <?php  
    $i++;}
	 
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
