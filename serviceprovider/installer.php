<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");

include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/

 

$mode=$_GET['mode'];

if($mode=='')

{

	$mode="Service";

}





               

  if($_POST["submit"])

 {



  if($_POST["Installer_name"])

{

	  

	  if($mode=="Service")

{

	

 

	$rs = mysql_query("select installer.inst_id as id,installer.inst_name as instname,services.id as jobid,services.company_name as company_name,services.veh_reg as veh_reg,services.reason as problem_in_service,services.problem as problem, services.request_by,services.Zone_area as Zone_area,services.req_date as req_date,services.time as close_date, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id where services.job_type=1 and req_date>='". $_POST["FromDate"]."' and req_date<='" . $_POST["ToDate"]. " 23:59:59". "' and installer.branch_id=".$_SESSION['BranchId']. " and installer.inst_id='" . $_POST["Installer_name"]. "'");

}

 if($mode=="Installation")

{

	 

	$rs = mysql_query("select installer.inst_id as id,installer.inst_name as instname,installation.id as jobid,installation.req_date as req_date,installation.company_name as company_name,installation.veh_reg as veh_reg, installation.request_by,installation.Zone_area as Zone_area,installation.job_type as job_type,installation.rtime as close_date, re_city_spr_1.name as area,installation.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join installation on installer.inst_id=installation.inst_id  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id where  installation.job_type=1 and req_date>='". $_POST["FromDate"]."' and req_date<='" . $_POST["ToDate"]. " 23:59:59". "' and installer.branch_id=".$_SESSION['BranchId']." and installer.inst_id='" . $_POST["Installer_name"]. "'");

}

	  

//$rs = mysql_query("select installer.inst_id as id,installer.inst_name as instname,services.id as jobid,services.company_name as company_name,services.veh_reg as veh_reg,services.reason as problem_in_service,services.problem as problem, services.request_by,services.Zone_area as Zone_area,services.job_type as job_type, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id where services.job_type=1 and req_date>='". $_POST["FromDate"]."' and req_date<='" . $_POST["ToDate"]. " 23:59:59". "' and installer.branch_id=".$_SESSION['BranchId']. " and installer.inst_id='" . $_POST["Installer_name"]. "'");

}

}









?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>

var j = jQuery.noConflict();

j(function() 

{

j( "#FromDate" ).datepicker({ dateFormat: "yy-mm-dd" });



j( "#ToDate" ).datepicker({ dateFormat: "yy-mm-dd" });



});



</script>

<div class="top-bar">
  <h1>Installer Report
    <?= $mode;?>
  </h1>
</div>
<div class="top-bar">
  <div style="float:right";><font style="color:#F6F;font-weight:bold;">Purple:</font> Urgent Service</div>
  <br/>
  <div style="float:right";><font style="color:#CCCCCC;font-weight:bold;">Grey:</font> Closed Service</div>
</div>
<div style="float:right"><a href="installer.php?mode=Service"> Service</a> | <a href="installer.php?mode=Installation"> Installation</a></div>
<div class="top-bar">
  <div align="center">
    <form name="myformlisting" method="post" action="" onchange="myformlisting.submit();">
      <table cellspacing="5" cellpadding="5" align="left">
        <tr>
          <td >Installer Name</td>
          <td><select name="Installer_name" id="Installer_name" style="width:150px">
              <option value="">Select Name</option>
              <?

$query=mysql_query("select * from installer where branch_id=".$_SESSION['BranchId']." order by inst_name asc");

while($data=mysql_fetch_array($query)) {

 ?>
              <option value="<?=$data['inst_id']?>" <? if($_POST['Installer_name']==$data['inst_id']) {?> selected="selected" <? } ?> >
              <?=$data['inst_name']?>
              </option>
              <? } ?>
            </select></td>
          <td >From Date</td>
          <td><input type="text" name="FromDate" id="FromDate" value="<?echo  $_POST["FromDate"]?>"/></td>
          <td>To Date</td>
          <td><input type="text" name="ToDate" id="ToDate"  value="<?echo  $_POST["ToDate"]?>" /></td>
          <td align="center"><input type="submit" name="submit" value="submit"  /></td>
        </tr>
      </table>
    </form>
  </div>
  <div class="table">
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
      <thead>
        <tr>
          <th>Sl. No</th>
          <th>Installer Name </th>
          <th>Request  By </th>
          <th  ><font color="#0E2C3C"><b>Request Date</b></font></th>
          
          <!-- <th  ><font color="#0E2C3C"><b>Close Date </b></font></th> -->
          
          <th  ><font color="#0E2C3C"><b>Client Name </b></font></th>
          <th  ><font color="#0E2C3C"><b>Vehicle No </font></th>
          <th  ><font color="#0E2C3C"><b>Problem in Service </font></th>
          <th  ><font color="#0E2C3C"><b>City</b></font></th>
          <th  ><font color="#0E2C3C"><b>Location</b></font></th>
          <th  ><font color="#0E2C3C"><b>Closed Date</b></font></th>
        </tr>
      </thead>
      <tbody>
        <?php 

	$i=1;

	 

    while ($row = mysql_fetch_array($rs)) { 

    ?>
        <tr align="Center"   <? if($row['service_status']==5 or $row['service_status']==6 )  {  ?> style="background:#CCCCCC;" <? }

	else  if($row['required']=='urgent'){ ?>style="background:#F6F" <? }?> >
          <td><?php echo $i ?></td>
          <td>&nbsp;<?php echo $row['instname'];?></td>
          <td>&nbsp;<?php echo $row['request_by'];?></td>
          <td>&nbsp;<?php echo $row['req_date'];?></td>
          
          <!-- <td >&nbsp;<?php echo $row['close_date'];?></td> -->
          
          <td >&nbsp;<?php echo $row['company_name'];?></td>
          <td >&nbsp;<?php echo $row['veh_reg'];?></td>
          <td >&nbsp;<?php echo $row['problem_in_service'] ;?></td>
          
          <!--<td  >&nbsp;<?php //echo  $row['zone'] ?></td>-->
          
          <td  >&nbsp;<?php echo $row['area'];?></td>
          <td  >&nbsp;<?php echo $row['location'] ;?></td>
          <td >&nbsp;<?php echo $row['close_date'];?></td>
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
