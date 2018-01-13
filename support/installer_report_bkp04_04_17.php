<?php 
session_start();
ini_set('max_execution_time', 100);
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/ 
//include($_SERVER['DOCUMENT_ROOT']."/service/sqlconnection.php");

$details = "";
if($_POST["submit"])
{
	$details.= $_POST["submit"];
}
else
{
	$details.= $_GET['details'];
}
$query=mysql_query("select * from installer where is_delete=1 and branch_id='".$_SESSION['BranchId']."' order by inst_name asc");
 
 print_r($total_assigned_job);  
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
    
    <h1>Installer Report: <?php echo $details;?> </h1>
      
</div>

			<!--<div style="float:center">
					  <div align="center"><br/>
	 	                No. of Job Assigned = <?=$total_assigned_job?> || No. of Job Close = <?=$total_closed_job?>
                        <br/>
                      </div>
  				</div>-->


<?php if($details != ""){?>
    <div class="top-bar">
        <div style="float:right";><a href="reportfiles/installer_excel.xls">Create Excel</a><br/></div>
        <br/>
        <div style="float:right";><a href="installer_report.php?details=today_details">Today Details</a><br/></div>
    </div>
<?php } ?>
<div class="top-bar">
<form name="myForm" action=""   method="post">

<table cellspacing="5" cellpadding="5">

    <tr>
    	<!--<td>
            <input type="radio" name="job" id="service" value="Service" <? if($_POST["job"]=="Service") echo "checked"?> />Service 
            <input type="radio" name="job" id="installation" value="Installation" <? if($_POST["job"]=="Installation") echo "checked"?>/>Installation
        </td>-->
        <!--<td><input type="submit" name="submit" value="Current"  /></td>-->
        <td><input type="submit" name="submit" value="Today"  /></td>
        <td><input type="submit" name="submit" value="Yesterday"  /></td>
    </tr>
 
</table>
</form>
</div>
<div class="table">
   
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
      <tr>
            <th>S/I Status</th>
            <th>Installer Name </th>
            <th>Location</th>
            <th>Client </th>
            <th>No. of Job Assigned</th>
            <th>No. of Job Close</th>
            <th>Stock in Bag</th> 
		</tr>
	</thead>
	<tbody>
   
	<?php 

	$excel_data.='<table cellpadding="5" cellspacing="5" border="1"><thead><tr><td colspan="7" align="center"><strong>Installer Report: '.$_POST["submit"].'</strong></td></tr><tr><td colspan="7"></td></tr><tr><th width="10%">S/I Status</th><th width="10%">Installer Name</th><th width="30%">Location</th><th width="20%">Client</th><th width="10%">No. of Job Assigned</th><th width="10%">No. of Job Close</th><th width="10%">Stock in Beg</th></tr></thead><tbody>';
	 
   while($row=mysql_fetch_array($query))
	{
	 	 $Inst_id=$row['inst_id'];
		 
		 $stock_in_beg = mssql_query("select count(device.device_imei) as Number , ChallanDetail.InstallerID from device left join ChallanDetail on ChallanDetail.deviceid=device.device_id where device_status in (63,64,96) and recd_date >='2014-01-01 00:00:00' and recd_date <='".date("Y-m-d")." 23:59:59' and currentrecord=1  and InstallerID=".$Inst_id." group by ChallanDetail.InstallerID");
		 $stock_data = mssql_fetch_array($stock_in_beg);
		 $installer_stock = $stock_data['Number'];
		
		$assigned_count_inst = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total_inst FROM installation WHERE job_type IN (1,2) AND installation.inst_id=".$Inst_id." AND `time`>='".date("Y-m-d")." 00:00:00' AND `time`<='".date("Y-m-d")." 23:59:59'"));
		
		$assigned_count_ser = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total_ser FROM services WHERE job_type IN (1,2) AND services.inst_id=".$Inst_id." AND `atime`>='".date("Y-m-d")." 00:00:00' AND `atime`<='".date("Y-m-d")." 23:59:59'"));
	  
	   $assigned_job = $assigned_count_inst['total_inst'] + $assigned_count_ser['total_ser'];
	   $total_assigned_job[] = $assigned_job;
	   
		$close_count_inst = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total_inst FROM installation WHERE job_type IN (1,2) AND installation.inst_id=".$Inst_id."  AND installation_status IN ('3','4','5','6','15') AND `time`>='".date("Y-m-d")." 00:00:00' AND `time`<='".date("Y-m-d")." 23:59:59'"));
		
		$close_count_ser = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total_ser FROM services WHERE job_type IN (1,2) AND services.inst_id=".$Inst_id." AND service_status IN ('3','4','5','6') AND `atime`>='".date("Y-m-d")." 00:00:00' AND `atime`<='".date("Y-m-d")." 23:59:59'"));
	  
	   $closed_job = $close_count_inst['total_inst'] + $close_count_ser['total_ser'];
	   $total_closed_job[] = $closed_job;
	
	
		if($_POST["submit"] == "Today")
		{ 
		  		$currentservice = mysql_query("select installer.inst_id,installer.inst_name,services.id as id,services.company_name as company_name,services.user_id as user_id,services.service_status,services.request_by,services.Zone_area as Zone_area,services.job_type as job_type, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id left join zone on re_city_spr_1.id_region=zone.id WHERE services.job_type=1 AND services.inst_id=".$Inst_id." AND services.service_status=2 AND services.atime>='".date("Y-m-d")." 00:00:00' AND services.atime<='".date("Y-m-d")." 23:59:59' ORDER BY services.req_date DESC LIMIT 1");	
				
				$countservice = mysql_num_rows($currentservice);
								
		  		$CurrentInstallation = mysql_query("select installer.inst_id,installer.inst_name,installation.id as id,installation.company_name as company_name,installation.user_id as user_id,installation.installation_status ,installation.request_by,installation.Zone_area as Zone_area,installation.job_type as job_type, re_city_spr_1.name as area,installation.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join installation on installer.inst_id=installation.inst_id  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id WHERE installation.job_type=1 AND installation.inst_id=".$Inst_id." AND installation.installation_status=2 AND installation.time>='".date("Y-m-d")." 00:00:00' AND installation.time<='".date("Y-m-d")." 23:59:59' ORDER BY installation.req_date DESC LIMIT 1");
				
				$CountInstallation = mysql_num_rows($CurrentInstallation);
				
				if($countservice == 0 && $CountInstallation == 0)
				{
					$currentservice = mysql_query("select installer.inst_id,installer.inst_name,services.id as id,services.company_name as company_name,services.user_id as user_id,services.service_status,services.request_by,services.Zone_area as Zone_area,services.job_type as job_type, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id left join zone on re_city_spr_1.id_region=zone.id WHERE services.job_type=1 AND services.inst_id=".$Inst_id." AND services.service_status NOT IN(1,2) AND services.atime>='".date("Y-m-d")." 00:00:00' AND services.atime<='".date("Y-m-d")." 23:59:59' ORDER BY services.req_date DESC LIMIT 1");		  
				}
				
				$CurrentJobService = mysql_fetch_array($currentservice);
				if($CurrentJobService["service_status"]=="3"  || $CurrentJobService["service_status"]=="4"){$service_status="Back -S";}
				else if($CurrentJobService["service_status"]=="5"){$service_status="Closed -S";}
				else if($CurrentJobService["service_status"]=="6"){$service_status="BackClosed -S";}
				else if($CurrentJobService["service_status"]=="2" && $CurrentJobService["job_type"]=="1"){$service_status="Ongoing -S";}
				else if($CurrentJobService["service_status"]=="2" && $CurrentJobService["job_type"]=="2"){$service_status="pending -S";}
				else if($CurrentJobService["service_status"]=="1"){$inst_status="Not Assigned -S";}
		 
				
				if($CountInstallation == 0 && $countservice == 0)
				{
					$CurrentInstallation = mysql_query("select installer.inst_id,installer.inst_name,installation.id as id,installation.company_name as company_name,installation.user_id as user_id,installation.installation_status ,installation.request_by,installation.Zone_area as Zone_area,installation.job_type as job_type, re_city_spr_1.name as area,installation.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join installation on installer.inst_id=installation.inst_id  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id WHERE installation.job_type=1 AND installation.inst_id=".$Inst_id." AND installation.installation_status NOT IN(1,2) AND installation.time>='".date("Y-m-d")." 00:00:00' AND installation.time<='".date("Y-m-d")." 23:59:59' ORDER BY installation.req_date DESC LIMIT 1");
				}
				
				$CurrentJobInstallation = mysql_fetch_array($CurrentInstallation);
				if($CurrentJobInstallation["installation_status"]=="3"  || $CurrentJobInstallation["installation_status"]=="4"){$inst_status="Back -I";}
				else if($CurrentJobInstallation["installation_status"]=="15"){$inst_status="Closed -I";}
				else if($CurrentJobInstallation["installation_status"]=="5"){$inst_status="Closed -I";}
				else if($CurrentJobInstallation["installation_status"]=="6"){$inst_status="BackClosed -I";}
				else if($CurrentJobInstallation["installation_status"]=="2" && $CurrentJobInstallation["job_type"]=="1"){$inst_status="Ongoing -I";}
				else if($CurrentJobInstallation["installation_status"]=="2" && $CurrentJobInstallation["job_type"]=="2"){$inst_status="pending -I";}
				else if($CurrentJobInstallation["installation_status"]=="1"){$inst_status="Not Assigned -I";}
		
			  
		if($CurrentJobService['inst_name'] != "" || $CurrentJobInstallation['inst_name'] != "" )
		{	  
			if($CurrentJobInstallation["user_id"] != "")
			{
				if($CurrentJobInstallation["location"] != ""){$currentlocation = $CurrentJobInstallation["location"];}else{$currentlocation = $CurrentJobInstallation["area"];}
				if($CurrentJobInstallation["company_name"] == ''){
					$user_name_inst = mysql_fetch_array(mysql_query("SELECT sys_username FROM matrix.users WHERE id='".$CurrentJobInstallation["user_id"]."'"));
					$CurrentComapny = $user_name_inst['sys_username'];}else{$CurrentComapny = $CurrentJobInstallation["company_name"];}
				
				$excel_data.="<tr><td width='10%'>".$inst_status."</td><td width='10%'>".$CurrentJobInstallation['inst_name']."</td><td width='30%'>".$currentlocation."</td><td width='20%'>".$CurrentComapny."</td><td width='10%'>".$assigned_job."</td><td width='10%'>".$closed_job."</td><td width='10%'>".$installer_stock."</td></tr>";
    ?>  
        <tr align="Center" >
            <td><?php echo $inst_status; ?></td>
            <td>&nbsp;<?php echo $CurrentJobInstallation['inst_name'];?></td>
            <td>&nbsp;<?php echo $currentlocation;?></td>
            <td>&nbsp;<?php echo $CurrentComapny;?></td>	
            <td>&nbsp;<?php echo $assigned_job;?></td>
            <td>&nbsp;<?php echo $closed_job;?></td>
            <td>&nbsp;<a href="#" onclick="Show_record(<?php echo $Inst_id;?>,'Installer_Stock','popup1'); " class="topopup"><?php echo $installer_stock;?></a></td>
        </tr>
        <?php } else { 
		
				if($CurrentJobService["location"] != ""){$current_location = $CurrentJobService["location"];}else{$current_location = $CurrentJobService["area"];}
				if($CurrentJobService["company_name"] == ''){
					$user_name_ser = mysql_fetch_array(mysql_query("SELECT sys_username FROM matrix.users WHERE id='".$CurrentJobService["user_id"]."'"));
					$Current_Comapny = $user_name_ser['sys_username'];}else{$Current_Comapny = $CurrentJobService["company_name"];}
				
				$excel_data.="<tr><td width='10%'>".$service_status."</td><td width='10%'>".$CurrentJobService['inst_name']."</td><td width='30%'>".$current_location."</td><td width='20%'>".$Current_Comapny."</td><td width='10%'>".$assigned_job."</td><td width='10%'>".$closed_job."</td><td width='10%'>".$installer_stock."</td></tr>";
		
		?>
        <tr align="Center" >
            <td><?php echo $service_status; ?></td>
            <td>&nbsp;<?php echo $CurrentJobService['inst_name'];?></td>
            <td>&nbsp;<?php echo $current_location;?></td>
            <td>&nbsp;<?php echo $Current_Comapny;?></td>	
            <td>&nbsp;<?php echo $assigned_job; ?></td>
            <td>&nbsp;<?php echo $closed_job;?></td>
            <td>&nbsp;<a href="#" onclick="Show_record(<?php echo $Inst_id;?>,'Installer_Stock','popup1'); " class="topopup"><?php echo $installer_stock;?></a></td>
        </tr>
	<?php } 
	  }
	}
	  
	  
	  if($details == "today_details")
 	  	{ 
		  		$CurrentJobService = mysql_query("select installer.inst_id,installer.inst_name,services.id as id,services.company_name as company_name,services.user_id as user_id,services.service_status,services.request_by,services.Zone_area as Zone_area,services.job_type as job_type, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id left join zone on re_city_spr_1.id_region=zone.id WHERE services.inst_id=".$Inst_id." AND services.atime>='".date("Y-m-d")." 00:00:00' AND services.atime<='".date("Y-m-d")." 23:59:59' ORDER BY services.atime ASC");		  
			    
			  $company="";$location=""; $total=0;$installer_name="";$service_status="";
			  
			  while($row_service = mysql_fetch_array($CurrentJobService))
			  {				  
				  if($row_service['company_name'] == "")
				  {
					$user_name_ser = mysql_fetch_array(mysql_query("SELECT sys_username FROM matrix.users WHERE id='".$row_service["user_id"]."'"));
					$company.= $user_name_ser['sys_username']."~</br>";  
				  }
				  else
				  {
				  	$company.= $row_service['company_name']."~</br>";
				  }
				  
				  if($row_service['location']!=""){
				  	$location.= $row_service['location']."~</br>";
				  } else {
					  $location.= $row_service['area']."~</br>";
				  }
				  
				  $installer_name = $row_service['inst_name'];
				  
				  if($row_service["service_status"]=="3"  || $row_service["service_status"]=="4"){$service_status.="Back -S"."</br>";}
				  else if($row_service["service_status"]=="5"){$service_status.="Closed -S"."</br>";}
				  else if($row_service["service_status"]=="6"){$service_status.="BackClosed -S"."</br>";}
				  else if($row_service["service_status"]=="2" && $row_service["job_type"]=="1"){$service_status.="Ongoing -S"."</br>";}
				  else if($row_service["service_status"]=="2" && $row_service["job_type"]=="2"){$service_status.="pending -S"."</br>";}
				  else if($row_service["service_status"]=="1"){$service_status.="Not Assigned -S"."</br>";}
				  
			  }
			  
		  		$CurrentJobInstallation = mysql_query("select installer.inst_id,installer.inst_name,installation.id as id,installation.company_name as company_name,installation.user_id as user_id,installation.installation_status ,installation.installation_made,installation.Zone_area as Zone_area,installation.job_type as job_type, re_city_spr_1.name as area,installation.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join installation on installer.inst_id=installation.inst_id  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id WHERE installation.inst_id=".$Inst_id." AND installation.time>='".date("Y-m-d")." 00:00:00' AND installation.time<='".date("Y-m-d")." 23:59:59' ORDER BY installation.time ASC");
			  
			  $company2="";$location2="";	$total_inst="";$installer_name2="";$inst_status= "";
			  
			 while($row_installation = mysql_fetch_array($CurrentJobInstallation))
			  {
				  if($row_installation['company_name'] == "")
				  {
				    $user_name_inst = mysql_fetch_array(mysql_query("SELECT sys_username FROM matrix.users WHERE id='".$row_installation["user_id"]."'"));
					$company2.= $user_name_inst['sys_username']."~</br>";  
				  }
				  else
				  {
				    $company2.= $row_installation['company_name']."~</br>";
				  }
				  if($row_installation['location']!=""){
				 	 $location2.= $row_installation['location']."~</br>";
				  }else {
					  $location2.= $row_installation['area']."~</br>";
				  }
				  
				  $total_inst.= $row_installation['installation_made']."~</br>";
				  $installer_name2 = $row_installation['inst_name'];
				  
				if($row_installation["installation_status"]=="3"  || $row_installation["installation_status"]=="4"){$inst_status.="Back -I"."</br>";}
				else if($row_installation["installation_status"]=="15"){$inst_status.="Closed -I"."</br>";}
				else if($row_installation["installation_status"]=="5"){$inst_status.="Closed -I"."</br>";}
				else if($row_installation["installation_status"]=="6"){$inst_status.="BackClosed -I"."</br>";}
				else if($row_installation["installation_status"]=="2" && $row_installation["job_type"]=="1"){$inst_status.="Ongoing -I"."</br>";}
				else if($row_installation["installation_status"]=="2" && $row_installation["job_type"]=="2"){$inst_status.="Pending -I"."</br>";}
				else if($row_installation["installation_status"]=="1"){$inst_status.="Not Assigned -I"."</br>";}
							  
		    }
	  
    ?>  

<?php  if($installer_name != "" || $installer_name2 != "" ){
	
		$excel_data.="<tr><td width='10%'>".$service_status.''.$inst_status."</td><td width='10%'>".$row['inst_name']."</td><td width='30%'>".$location.''.$location2."</td><td width='20%'>".$company.''.$company2."</td><td width='10%'>".$assigned_job."</td><td width='10%'>".$closed_job."</td><td width='10%'>".$installer_stock."</td></tr>";

	?>
        <tr align="Center" >
            <td><?php  echo $service_status."".$inst_status;?></td>
       		<td>&nbsp;<?php echo $row['inst_name'];?></td>
            <td>&nbsp;<?php echo $location."".$location2;?></td>	
            <td>&nbsp;<?php echo $company."".$company2;?></td>
            <td>&nbsp;<?php echo $assigned_job;?></td>
            <td>&nbsp;<?php echo $closed_job;?></td>
            <td>&nbsp;<a href="#" onclick="Show_record(<?php echo $Inst_id;?>,'Installer_Stock','popup1'); " class="topopup"><?php echo $installer_stock;?></a></td>
         </tr>
    <?php }?>        
	  
<?php }
		
	  if($_POST["submit"] == "Yesterday")
 	  { 
				
			$yest_assigned_count_inst = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total_inst FROM installation WHERE job_type IN (1,2) AND installation.inst_id=".$Inst_id." AND `time`>='".date("Y-m-d",strtotime('-1 days'))." 00:00:00' AND `time`<='".date("Y-m-d",strtotime('-1 days'))." 23:59:59'"));
			
			$yest_assigned_count_ser = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total_ser FROM services WHERE job_type IN (1,2) AND services.inst_id=".$Inst_id." AND `atime`>='".date("Y-m-d",strtotime('-1 days'))." 00:00:00' AND `atime`<='".date("Y-m-d",strtotime('-1 days'))." 23:59:59'"));
			
			$yest_assigned_job = $yest_assigned_count_inst['total_inst'] + $yest_assigned_count_ser['total_ser'];
			
			$yest_close_count_inst = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total_inst FROM installation WHERE job_type IN (1,2) AND installation.inst_id=".$Inst_id."  AND installation_status IN ('3','4','5','6','15') AND `time`>='".date("Y-m-d",strtotime('-1 days'))." 00:00:00' AND `time`<='".date("Y-m-d",strtotime('-1 days'))." 23:59:59'"));
			
			$yest_close_count_ser = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total_ser FROM services WHERE job_type IN (1,2) AND services.inst_id=".$Inst_id." AND service_status IN ('3','4','5','6') AND `atime`>='".date("Y-m-d",strtotime('-1 days'))." 00:00:00' AND `atime`<='".date("Y-m-d",strtotime('-1 days'))." 23:59:59'"));
			
			$yest_closed_job = $yest_close_count_inst['total_inst'] + $yest_close_count_ser['total_ser'];
				
			$YestJobService = mysql_query("select installer.inst_id,installer.inst_name,services.id as id,services.company_name as company_name,services.user_id as user_id,services.service_status,services.request_by,services.Zone_area as Zone_area,services.job_type as job_type, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id left join zone on re_city_spr_1.id_region=zone.id WHERE services.inst_id=".$Inst_id." AND services.atime>='".date("Y-m-d",strtotime('-1 days'))." 00:00:00' AND services.atime<='".date("Y-m-d",strtotime('-1 days'))." 23:59:59' ORDER BY services.atime ASC");		  
			  
			  $company="";$location="";$total=0;$yest_installer_name="";$yest_service_status="";
			  
			  while($yest_row_service = mysql_fetch_array($YestJobService))
			  {
				  
				  if($yest_row_service['company_name'] == "")
				  {
					$user_name_ser = mysql_fetch_array(mysql_query("SELECT sys_username FROM matrix.users WHERE id='".$yest_row_service["user_id"]."'"));
					$company.= $user_name_ser['sys_username']."~</br>";  
				  }
				  else
				  {
				  	$company.= $yest_row_service['company_name']."~</br>";
				  }
				  if($yest_row_service['location']!=""){
					$location.= $yest_row_service['location']."~</br>";
				  }else{
					  $location.= $yest_row_service['area']."~</br>";
				  }
				  
				  $yest_installer_name = $yest_row_service['inst_name'];
				  
				if($yest_row_service["service_status"]=="3"  || $yest_row_service["service_status"]=="4"){$yest_service_status.="Back -S"."</br>";}
				else if($yest_row_service["service_status"]=="5"){$yest_service_status.="Closed -S"."</br>";}
				else if($yest_row_service["service_status"]=="6"){$yest_service_status.="BackClosed -S"."</br>";}
				else if($yest_row_service["service_status"]=="2" && $yest_row_service["job_type"]=="1"){$yest_service_status.="Ongoing -S"."</br>";}
				else if($yest_row_service["service_status"]=="2" && $yest_row_service["job_type"]=="2"){$yest_service_status.="pending -S"."</br>";}
				else if($yest_row_service["service_status"]=="1"){$yest_service_status.="Not Assigned -S"."</br>";}
			  }



		  		$YestJobInstallation = mysql_query("select installer.inst_id,installer.inst_name,installation.id as id,installation.company_name as company_name,installation.user_id as user_id,installation.installation_status ,installation.installation_made,installation.Zone_area as Zone_area,installation.job_type as job_type, re_city_spr_1.name as area,installation.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join installation on installer.inst_id=installation.inst_id  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id WHERE installation.inst_id=".$Inst_id." AND installation.time>='".date("Y-m-d",strtotime('-1 days'))." 00:00:00' AND installation.time<='".date("Y-m-d",strtotime('-1 days'))." 23:59:59' ORDER BY installation.time ASC");
			  	
			  $company2="";$location2="";	$total_inst="";$yest_installer_name2="";$yest_inst_status="";
			  
			 while($yest_row_installation = mysql_fetch_array($YestJobInstallation))
			  {
				  
				  if($yest_row_installation['company_name'] == "")
				  {
					$user_name_inst = mysql_fetch_array(mysql_query("SELECT sys_username FROM matrix.users WHERE id='".$yest_row_installation["user_id"]."'"));
					$company2.= $user_name_inst['sys_username']."~</br>";  
				  }
				  else
				  {
				    $company2.= $yest_row_installation['company_name']."~</br>";
				  }
				  if($yest_row_installation['location']!=""){	
					$location2.= $yest_row_installation['location']."~</br>";
				  }else{
					 $location2.= $yest_row_installation['area']."~</br>";
				  }
				  
				  $total_inst.= $yest_row_installation['installation_made']."~</br>";
				  $yest_installer_name2 = $yest_row_installation['inst_name'];
				  
				if($yest_row_installation["installation_status"]=="3"  || $yest_row_installation["installation_status"]=="4"){$yest_inst_status.="Back -I"."</br>";}
				else if($yest_row_installation["installation_status"]=="15"){$yest_inst_status.="SemiClosed -I"."</br>";}
				else if($yest_row_installation["installation_status"]=="5"){$yest_inst_status.="Closed -I"."</br>";}
				else if($yest_row_installation["installation_status"]=="6"){$yest_inst_status.="BackClosed -I"."</br>";}
				else if($yest_row_installation["installation_status"]=="2" && $yest_row_installation["job_type"]=="1"){$yest_inst_status.="Ongoing -I"."</br>";}
				else if($yest_row_installation["installation_status"]=="2" && $yest_row_installation["job_type"]=="2"){$yest_inst_status.="Pending -I"."</br>";}
				else if($yest_row_installation["installation_status"]=="1"){$yest_inst_status.="Not Assigned -I"."</br>";}
			  }
	  
    ?>  

<?php  if($yest_installer_name != "" || $yest_installer_name2 != "" ){
	
		$excel_data.="<tr><td width='10%'>".$yest_service_status.''.$yest_inst_status."</td><td width='10%'>".$row['inst_name']."</td><td width='30%'>".$location.''.$location2."</td><td width='20%'>".$company.''.$company2."</td><td width='10%'>".$yest_assigned_job."</td><td width='10%'>".$yest_closed_job."</td><td width='10%'>".$installer_stock."</td></tr>";
	?>
        <tr align="Center" >
            <td><?php  echo $yest_service_status."".$yest_inst_status;?></td>
       		<td>&nbsp;<?php echo $row['inst_name'];?></td>
            <td>&nbsp;<?php echo $location."".$location2;?></td>	
            <td>&nbsp;<?php echo $company."".$company2;?></td>
            <td>&nbsp;<?php echo $yest_assigned_job;?></td>
            <td>&nbsp;<?php echo $yest_closed_job;?></td>
            <td>&nbsp;<a href="#" onclick="Show_record(<?php echo $Inst_id;?>,'Installer_Stock','popup1'); " class="topopup"><?php echo $installer_stock;?></a></td>
         </tr>
        <?php }
	  }
	}
	$excel_data.='</tbody></table>';	 
    ?>
</table>
     
   <div id="toPopup"> 
    	
        <div class="close">close</div>
       	<span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
		<div id="popup1" style ="height:100%;width:100%"> <!--your content start-->
            

 
        </div> <!--your content end-->
    
    </div> <!--toPopup end-->
    
	<div class="loader"></div>
   	<div id="backgroundPopup"></div>
</div>
 
 
 
<?
unlink(__DOCUMENT_ROOT.'/support/reportfiles/installer_excel.xls');
$filepointer=fopen(__DOCUMENT_ROOT.'/support/reportfiles/installer_excel.xls','w');
fwrite($filepointer,$excel_data);
fclose($filepointer);

include("../include/footer.inc.php");

?>

 