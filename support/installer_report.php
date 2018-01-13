<?php 
session_start();
ini_set('max_execution_time', 200);
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
//include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

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

$Installer_query = select_query("select * from internalsoftware.installer where is_delete=1 and branch_id='".$_SESSION['BranchId']."' 
order by inst_name asc");
 
 //echo "<pre>";print_r($Installer_query);die;  
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
     
   //while($row=mysql_fetch_array($Installer_query))
   for($ui=0;$ui<count($Installer_query);$ui++)
    {
          $Inst_id = $Installer_query[$ui]['inst_id'];
        
         $stock_in_beg = select_query_inventory("select count(device.device_imei) as Number , ChallanDetail.InstallerID from inventory.device left join inventory.ChallanDetail on ChallanDetail.deviceid=device.device_id where device_status in (63,64,96) and recd_date >='2014-01-01 00:00:00' and recd_date <='".date("Y-m-d")." 23:59:59' and currentrecord=1  and InstallerID=".$Inst_id." group by ChallanDetail.InstallerID");
         //$stock_data = mssql_fetch_array($stock_in_beg);
         $installer_stock = $stock_in_beg[0]['Number'];
        
        $assigned_count_inst = select_query("SELECT COUNT(id) AS total_inst FROM installation WHERE job_type IN (1,2) AND installation.inst_id=".$Inst_id." AND `time`>='".date("Y-m-d")." 00:00:00' AND `time`<='".date("Y-m-d")." 23:59:59'");
        
        $assigned_count_ser = select_query("SELECT COUNT(id) AS total_ser FROM services WHERE job_type IN (1,2) AND services.inst_id=".$Inst_id." AND `atime`>='".date("Y-m-d")." 00:00:00' AND `atime`<='".date("Y-m-d")." 23:59:59'");
      
       $assigned_job = $assigned_count_inst[0]['total_inst'] + $assigned_count_ser[0]['total_ser'];
       $total_assigned_job[] = $assigned_job;
       
        $close_count_inst = select_query("SELECT COUNT(id) AS total_inst FROM installation WHERE job_type IN (1,2) AND installation.inst_id=".$Inst_id."  AND installation_status IN ('3','4','5','6','15') AND `time`>='".date("Y-m-d")." 00:00:00' AND `time`<='".date("Y-m-d")." 23:59:59'");
        
        $close_count_ser = select_query("SELECT COUNT(id) AS total_ser FROM services WHERE job_type IN (1,2) AND services.inst_id=".$Inst_id." AND service_status IN ('3','4','5','6') AND `atime`>='".date("Y-m-d")." 00:00:00' AND `atime`<='".date("Y-m-d")." 23:59:59'");
      
       $closed_job = $close_count_inst[0]['total_inst'] + $close_count_ser[0]['total_ser'];
       $total_closed_job[] = $closed_job;
    
    
        if($_POST["submit"] == "Today")
        {

                  $current_assigned_count_inst = select_query("SELECT COUNT(id) AS total_inst FROM installation WHERE job_type IN (1,2) AND installation.inst_id=".$Inst_id." AND `time`>='".date("Y-m-d")." 00:00:00' AND `time`<='".date("Y-m-d")." 23:59:59'");
            
                  $current_assigned_count_ser = select_query("SELECT COUNT(id) AS total_ser FROM services WHERE job_type IN (1,2) AND services.inst_id=".$Inst_id." AND `atime`>='".date("Y-m-d")." 00:00:00' AND `atime`<='".date("Y-m-d")." 23:59:59'");
                  
                  $current_assigned_job = $current_assigned_count_inst[0]['total_inst'] + $current_assigned_count_ser[0]['total_ser'];
                  
                  $current_close_count_inst = select_query("SELECT COUNT(id) AS total_inst FROM installation WHERE job_type IN (1,2) AND installation.inst_id=".$Inst_id."  AND installation_status IN ('3','4','5','6','15') AND `time`>='".date("Y-m-d")." 00:00:00' AND `time`<='".date("Y-m-d")." 23:59:59'");
                  
                  $current_close_count_ser = select_query("SELECT COUNT(id) AS total_ser FROM services WHERE job_type IN (1,2) AND services.inst_id=".$Inst_id." AND service_status IN ('3','4','5','6') AND `atime`>='".date("Y-m-d")." 00:00:00' AND `atime`<='".date("Y-m-d")." 23:59:59'");
                  
                  $current_closed_job = $current_close_count_inst[0]['total_inst'] + $current_close_count_ser[0]['total_ser'];

                  $CurrentJobService = select_query("select installer.inst_id,installer.inst_name,services.id as id,services.company_name as company_name,services.user_id as user_id,services.service_status,services.request_by,services.Zone_area as Zone_area,services.job_type as job_type, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id left join zone on re_city_spr_1.id_region=zone.id WHERE services.inst_id=".$Inst_id." AND services.atime>='".date("Y-m-d")." 00:00:00' AND services.atime<='".date("Y-m-d")." 23:59:59' ORDER BY services.atime ASC");          
              
                  $company="";$location="";$total=0;$current_installer_name="";$current_service_status="";
                  
                  //while($yest_row_service = mysql_fetch_array($YestJobService))
                  for($yjs=0;$yjs<count($CurrentJobService);$yjs++)
                  {
                      
                      if($CurrentJobService[$yjs]['company_name'] == "")
                      {
                        $user_name_ser = select_query("SELECT UserName FROM internalsoftware.users WHERE Userid='".$CurrentJobService[$yjs]["user_id"]."'");
                        $company.= $user_name_ser[0]['UserName']."~</br>";  
                      }
                      else
                      {
                          $company.= $CurrentJobService[$yjs]['company_name']."~</br>";
                      }
                      if($CurrentJobService[$yjs]['location']!=""){
                        $location.= $CurrentJobService[$yjs]['location']."~</br>";
                      }else{
                          $location.= $CurrentJobService[$yjs]['area']."~</br>";
                      }
                      
                      $current_installer_name = $CurrentJobService[$yjs]['inst_name'];
                      
                    if($CurrentJobService[$yjs]["service_status"]=="3"  || $CurrentJobService[$yjs]["service_status"]=="4"){$current_service_status.="Back -S"."</br>";}
                    else if($CurrentJobService[$yjs]["service_status"]=="5"){$current_service_status.="Closed -S"."</br>";}
                    else if($CurrentJobService[$yjs]["service_status"]=="6"){$current_service_status.="BackClosed -S"."</br>";}
                    else if($CurrentJobService[$yjs]["service_status"]=="2" && $CurrentJobService[$yjs]["job_type"]=="1"){$current_service_status.="Ongoing -S"."</br>";}
                    else if($CurrentJobService[$yjs]["service_status"]=="2" && $CurrentJobService[$yjs]["job_type"]=="2"){$current_service_status.="pending -S"."</br>";}
                    else if($CurrentJobService[$yjs]["service_status"]=="1"){$current_service_status.="Not Assigned -S"."</br>";}
                  }



                      $CurrentJobInstallation = select_query("select installer.inst_id,installer.inst_name,installation.id as id,installation.company_name as company_name,installation.user_id as user_id,installation.installation_status ,installation.installation_made,installation.Zone_area as Zone_area,installation.job_type as job_type, re_city_spr_1.name as area,installation.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join installation on installer.inst_id=installation.inst_id  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id WHERE installation.inst_id=".$Inst_id." AND installation.time>='".date("Y-m-d")." 00:00:00' AND installation.time<='".date("Y-m-d")." 23:59:59' ORDER BY installation.time ASC");
                      
                  $company2="";$location2="";    $total_inst="";$current_installer_name2="";$current_inst_status="";
                  
                 //while($yest_row_installation = mysql_fetch_array($YestJobInstallation))
                 for($yji=0;$yji<count($CurrentJobInstallation);$yji++)
                  {
                      
                      if($CurrentJobInstallation[$yji]['company_name'] == "")
                      {
                        $user_name_inst = select_query("SELECT UserName FROM internalsoftware.users WHERE Userid='".$CurrentJobInstallation[$yji]["user_id"]."'");
                        $company2.= $user_name_inst[0]['UserName']."~</br>";  
                      }
                      else
                      {
                        $company2.= $CurrentJobInstallation[$yji]['company_name']."~</br>";
                      }
                      if($CurrentJobInstallation[$yji]['location']!=""){    
                        $location2.= $CurrentJobInstallation[$yji]['location']."~</br>";
                      }else{
                         $location2.= $CurrentJobInstallation[$yji]['area']."~</br>";
                      }
                      
                      $total_inst.= $CurrentJobInstallation[$yji]['installation_made']."~</br>";
                      $current_installer_name2 = $CurrentJobInstallation[$yji]['inst_name'];
                      
                    if($CurrentJobInstallation[$yji]["installation_status"]=="3"  || $CurrentJobInstallation[$yji]["installation_status"]=="4"){$current_inst_status.="Back -I"."</br>";}
                    else if($CurrentJobInstallation[$yji]["installation_status"]=="15"){$current_inst_status.="SemiClosed -I"."</br>";}
                    else if($CurrentJobInstallation[$yji]["installation_status"]=="5"){$current_inst_status.="Closed -I"."</br>";}
                    else if($CurrentJobInstallation[$yji]["installation_status"]=="6"){$current_inst_status.="BackClosed -I"."</br>";}
                    else if($CurrentJobInstallation[$yji]["installation_status"]=="2" && $CurrentJobInstallation[$yji]["job_type"]=="1"){$current_inst_status.="Ongoing -I"."</br>";}
                    else if($CurrentJobInstallation[$yji]["installation_status"]=="2" && $CurrentJobInstallation[$yji]["job_type"]=="2"){$current_inst_status.="Pending -I"."</br>";}
                    else if($CurrentJobInstallation[$yji]["installation_status"]=="1"){$current_inst_status.="Not Assigned -I"."</br>";}
                  }

                  $currentservice = select_query("select installer.inst_id,installer.inst_name,services.id as id,services.company_name as company_name,services.user_id as user_id,services.service_status,services.request_by,services.Zone_area as Zone_area,services.job_type as job_type, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id left join zone on re_city_spr_1.id_region=zone.id WHERE services.job_type=1 AND services.inst_id=".$Inst_id." AND services.service_status=2 AND services.atime>='".date("Y-m-d")." 00:00:00' AND services.atime<='".date("Y-m-d")." 23:59:59' ORDER BY services.req_date DESC LIMIT 1");    
                
                $countservice = count($currentservice);
                                
                  $CurrentInstallation = select_query("select installer.inst_id,installer.inst_name,installation.id as id,installation.company_name as company_name,installation.user_id as user_id,installation.installation_status ,installation.request_by,installation.Zone_area as Zone_area,installation.job_type as job_type, re_city_spr_1.name as area,installation.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join installation on installer.inst_id=installation.inst_id  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id WHERE installation.job_type=1 AND installation.inst_id=".$Inst_id." AND installation.installation_status=2 AND installation.time>='".date("Y-m-d")." 00:00:00' AND installation.time<='".date("Y-m-d")." 23:59:59' ORDER BY installation.req_date DESC LIMIT 1");
                
                $CountInstallation = count($CurrentInstallation);
                
                if($countservice == 0 && $CountInstallation == 0)
                {
                    $CurrentJobService = select_query("select installer.inst_id,installer.inst_name,services.id as id,services.company_name as company_name,services.user_id as user_id,services.service_status,services.request_by,services.Zone_area as Zone_area,services.job_type as job_type, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id left join zone on re_city_spr_1.id_region=zone.id WHERE services.job_type=1 AND services.inst_id=".$Inst_id." AND services.service_status NOT IN(1,2) AND services.atime>='".date("Y-m-d")." 00:00:00' AND services.atime<='".date("Y-m-d")." 23:59:59' ORDER BY services.req_date DESC LIMIT 1");          
                }
                
                //$CurrentJobService = mysql_fetch_array($currentservice);
                if($CurrentJobService[0]["service_status"]=="3"  || $CurrentJobService[0]["service_status"]=="4"){$service_status="Back -S";}
                else if($CurrentJobService[0]["service_status"]=="5"){$service_status="Closed -S";}
                else if($CurrentJobService[0]["service_status"]=="6"){$service_status="BackClosed -S";}
                else if($CurrentJobService[0]["service_status"]=="2" && $CurrentJobService[0]["job_type"]=="1"){$service_status="Ongoing -S";}
                else if($CurrentJobService[0]["service_status"]=="2" && $CurrentJobService[0]["job_type"]=="2"){$service_status="pending -S";}
                else if($CurrentJobService[0]["service_status"]=="1"){$inst_status="Not Assigned -S";}
         
                
                if($CountInstallation == 0 && $countservice == 0)
                {
                    $CurrentJobInstallation = select_query("select installer.inst_id,installer.inst_name,installation.id as id,installation.company_name as company_name,installation.user_id as user_id,installation.installation_status ,installation.request_by,installation.Zone_area as Zone_area,installation.job_type as job_type, re_city_spr_1.name as area,installation.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join installation on installer.inst_id=installation.inst_id  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id WHERE installation.job_type=1 AND installation.inst_id=".$Inst_id." AND installation.installation_status NOT IN(1,2) AND installation.time>='".date("Y-m-d")." 00:00:00' AND installation.time<='".date("Y-m-d")." 23:59:59' ORDER BY installation.req_date DESC LIMIT 1");
                }
                
                //$CurrentJobInstallation = mysql_fetch_array($CurrentInstallation);
                if($CurrentJobInstallation[0]["installation_status"]=="3"  || $CurrentJobInstallation[0]["installation_status"]=="4"){$inst_status="Back -I";}
                else if($CurrentJobInstallation[0]["installation_status"]=="15"){$inst_status="Closed -I";}
                else if($CurrentJobInstallation[0]["installation_status"]=="5"){$inst_status="Closed -I";}
                else if($CurrentJobInstallation[0]["installation_status"]=="6"){$inst_status="BackClosed -I";}
                else if($CurrentJobInstallation[0]["installation_status"]=="2" && $CurrentJobInstallation[0]["job_type"]=="1"){$inst_status="Ongoing -I";}
                else if($CurrentJobInstallation[0]["installation_status"]=="2" && $CurrentJobInstallation[0]["job_type"]=="2"){$inst_status="pending -I";}
                else if($CurrentJobInstallation[0]["installation_status"]=="1"){$inst_status="Not Assigned -I";}
        
              
        if($CurrentJobService[0]['inst_name'] != "" || $CurrentJobInstallation[0]['inst_name'] != "" )
        {      
            if($CurrentJobInstallation[0]["user_id"] != "")
            {
                if($CurrentJobInstallation[0]["location"] != ""){$currentlocation = $CurrentJobInstallation[0]["location"];}else{$currentlocation = $CurrentJobInstallation[0]["area"];}
                
                if($CurrentJobInstallation[0]["company_name"] == ''){
                    
                    $user_name_inst = select_query("SELECT UserName FROM internalsoftware.users WHERE Userid='".$CurrentJobInstallation[0]["user_id"]."'");
                    $CurrentComapny = $user_name_inst[0]['UserName'];
                    
                    }else{$CurrentComapny = $CurrentJobInstallation[0]["company_name"];}
                
                $excel_data.="<tr><td width='10%'>".$inst_status."</td><td width='10%'>".$CurrentJobInstallation[0]['inst_name']."</td><td width='30%'>".$currentlocation."</td><td width='20%'>".$CurrentComapny."</td><td width='10%'>".$assigned_job."</td><td width='10%'>".$closed_job."</td><td width='10%'>".$installer_stock."</td></tr>";
    ?>  
        <tr align="Center" >
            <td><?php echo $inst_status; ?></td>
            <td>&nbsp;<?php echo $CurrentJobInstallation[0]['inst_name'];?></td>
            <td>&nbsp;<?php echo $currentlocation;?></td>
            <td>&nbsp;<?php echo $CurrentComapny;?></td>    
            <td>&nbsp;<?php echo $assigned_job;?></td>
            <td>&nbsp;<?php echo $closed_job;?></td>
            <td>&nbsp;<a href="#" onclick="Show_record(<?php echo $Inst_id;?>,'Installer_Stock','popup1'); " class="topopup"><?php echo $installer_stock;?></a></td>
        </tr>
        <?php } else { 
        
                if($CurrentJobService[0]["location"] != ""){$current_location = $CurrentJobService[0]["location"];}else{$current_location = $CurrentJobService[0]["area"];}
                
                if($CurrentJobService[0]["company_name"] == ''){
                    
                    $user_name_ser = select_query("SELECT UserName FROM internalsoftware.users WHERE Userid='".$CurrentJobService[0]["user_id"]."'");
                    $Current_Comapny = $user_name_ser[0]['UserName'];}else{$Current_Comapny = $CurrentJobService[0]["company_name"];}
                
                $excel_data.="<tr><td width='10%'>".$service_status."</td><td width='10%'>".$CurrentJobService[0]['inst_name']."</td><td width='30%'>".$current_location."</td><td width='20%'>".$Current_Comapny."</td><td width='10%'>".$assigned_job."</td><td width='10%'>".$closed_job."</td><td width='10%'>".$installer_stock."</td></tr>";
        
        ?>
        <tr align="Center" >
            <td><?php echo $service_status; ?></td>
            <td>&nbsp;<?php echo $CurrentJobService[0]['inst_name'];?></td>
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
                  $CurrentJobService = select_query("select installer.inst_id,installer.inst_name,services.id as id,services.company_name as company_name,services.user_id as user_id,services.service_status,services.request_by,services.Zone_area as Zone_area,services.job_type as job_type, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id left join zone on re_city_spr_1.id_region=zone.id WHERE services.inst_id=".$Inst_id." AND services.atime>='".date("Y-m-d")." 00:00:00' AND services.atime<='".date("Y-m-d")." 23:59:59' ORDER BY services.atime ASC");          
                
              $company="";$location=""; $total=0;$installer_name="";$service_status="";
              
              //while($row_service = mysql_fetch_array($CurrentJobService))
              for($td=0;$td<count($CurrentJobService);$td++)
              {                  
                  if($CurrentJobService[$td]['company_name'] == "")
                  {
                    $user_name_ser = select_query("SELECT UserName FROM internalsoftware.users WHERE Userid='".$CurrentJobService[$td]["user_id"]."'");
                    $company.= $user_name_ser[0]['sys_username']."~</br>";  
                  }
                  else
                  {
                      $company.= $CurrentJobService[$td]['company_name']."~</br>";
                  }
                  
                  if($CurrentJobService[$td]['location']!=""){
                      $location.= $CurrentJobService[$td]['location']."~</br>";
                  } else {
                      $location.= $CurrentJobService[$td]['area']."~</br>";
                  }
                  
                  $installer_name = $CurrentJobService[$td]['inst_name'];
                  
                  if($CurrentJobService[$td]["service_status"]=="3"  || $CurrentJobService[$td]["service_status"]=="4"){$service_status.="Back -S"."</br>";}
                  else if($CurrentJobService[$td]["service_status"]=="5"){$service_status.="Closed -S"."</br>";}
                  else if($CurrentJobService[$td]["service_status"]=="6"){$service_status.="BackClosed -S"."</br>";}
                  else if($CurrentJobService[$td]["service_status"]=="2" && $CurrentJobService[$td]["job_type"]=="1"){$service_status.="Ongoing -S"."</br>";}
                  else if($CurrentJobService[$td]["service_status"]=="2" && $CurrentJobService[$td]["job_type"]=="2"){$service_status.="pending -S"."</br>";}
                  else if($CurrentJobService[$td]["service_status"]=="1"){$service_status.="Not Assigned -S"."</br>";}
                  
              }
              
                  $CurrentJobInstallation = select_query("select installer.inst_id,installer.inst_name,installation.id as id,installation.company_name as company_name,installation.user_id as user_id,installation.installation_status ,installation.installation_made,installation.Zone_area as Zone_area,installation.job_type as job_type, re_city_spr_1.name as area,installation.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join installation on installer.inst_id=installation.inst_id  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id WHERE installation.inst_id=".$Inst_id." AND installation.time>='".date("Y-m-d")." 00:00:00' AND installation.time<='".date("Y-m-d")." 23:59:59' ORDER BY installation.time ASC");
              
              $company2="";$location2="";    $total_inst="";$installer_name2="";$inst_status= "";
              
             //while($row_installation = mysql_fetch_array($CurrentJobInstallation))
             for($tdi=0;$tdi<count($CurrentJobInstallation);$tdi++)
              {
                  if($CurrentJobInstallation[$tdi]['company_name'] == "")
                  {
                    $user_name_inst = select_query("SELECT UserName FROM internalsoftware.users WHERE Userid='".$CurrentJobInstallation[$tdi]["user_id"]."'");
                    $company2.= $user_name_inst[0]['UserName']."~</br>";  
                  }
                  else
                  {
                    $company2.= $CurrentJobInstallation[$tdi]['company_name']."~</br>";
                  }
                  if($CurrentJobInstallation[$tdi]['location']!=""){
                      $location2.= $CurrentJobInstallation[$tdi]['location']."~</br>";
                  }else {
                      $location2.= $CurrentJobInstallation[$tdi]['area']."~</br>";
                  }
                  
                  $total_inst.= $CurrentJobInstallation[$tdi]['installation_made']."~</br>";
                  $installer_name2 = $CurrentJobInstallation[$tdi]['inst_name'];
                  
                if($CurrentJobInstallation[$tdi]["installation_status"]=="3"  || $CurrentJobInstallation[$tdi]["installation_status"]=="4"){$inst_status.="Back -I"."</br>";}
                else if($CurrentJobInstallation[$tdi]["installation_status"]=="15"){$inst_status.="Closed -I"."</br>";}
                else if($CurrentJobInstallation[$tdi]["installation_status"]=="5"){$inst_status.="Closed -I"."</br>";}
                else if($CurrentJobInstallation[$tdi]["installation_status"]=="6"){$inst_status.="BackClosed -I"."</br>";}
                else if($CurrentJobInstallation[$tdi]["installation_status"]=="2" && $CurrentJobInstallation[$tdi]["job_type"]=="1"){$inst_status.="Ongoing -I"."</br>";}
                else if($CurrentJobInstallation[$tdi]["installation_status"]=="2" && $CurrentJobInstallation[$tdi]["job_type"]=="2"){$inst_status.="Pending -I"."</br>";}
                else if($CurrentJobInstallation[$tdi]["installation_status"]=="1"){$inst_status.="Not Assigned -I"."</br>";}
                              
            }
      
    ?>  

<?php  if($installer_name != "" || $installer_name2 != "" ){
    
        $excel_data.="<tr><td width='10%'>".$service_status.''.$inst_status."</td><td width='10%'>".$Installer_query[$ui]['inst_name']."</td><td width='30%'>".$location.''.$location2."</td><td width='20%'>".$company.''.$company2."</td><td width='10%'>".$assigned_job."</td><td width='10%'>".$closed_job."</td><td width='10%'>".$installer_stock."</td></tr>";

    ?>
        <tr align="Center" >
            <td><?php  echo $service_status."".$inst_status;?></td>
               <td>&nbsp;<?php echo $Installer_query[$ui]['inst_name'];?></td>
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
                
            $yest_assigned_count_inst = select_query("SELECT COUNT(id) AS total_inst FROM installation WHERE job_type IN (1,2) AND installation.inst_id=".$Inst_id." AND `time`>='".date("Y-m-d",strtotime('-1 days'))." 00:00:00' AND `time`<='".date("Y-m-d",strtotime('-1 days'))." 23:59:59'");
            
            $yest_assigned_count_ser = select_query("SELECT COUNT(id) AS total_ser FROM services WHERE job_type IN (1,2) AND services.inst_id=".$Inst_id." AND `atime`>='".date("Y-m-d",strtotime('-1 days'))." 00:00:00' AND `atime`<='".date("Y-m-d",strtotime('-1 days'))." 23:59:59'");
            
            $yest_assigned_job = $yest_assigned_count_inst[0]['total_inst'] + $yest_assigned_count_ser[0]['total_ser'];
            
            $yest_close_count_inst = select_query("SELECT COUNT(id) AS total_inst FROM installation WHERE job_type IN (1,2) AND installation.inst_id=".$Inst_id."  AND installation_status IN ('3','4','5','6','15') AND `time`>='".date("Y-m-d",strtotime('-1 days'))." 00:00:00' AND `time`<='".date("Y-m-d",strtotime('-1 days'))." 23:59:59'");
            
            $yest_close_count_ser = select_query("SELECT COUNT(id) AS total_ser FROM services WHERE job_type IN (1,2) AND services.inst_id=".$Inst_id." AND service_status IN ('3','4','5','6') AND `atime`>='".date("Y-m-d",strtotime('-1 days'))." 00:00:00' AND `atime`<='".date("Y-m-d",strtotime('-1 days'))." 23:59:59'");
            
            $yest_closed_job = $yest_close_count_inst[0]['total_inst'] + $yest_close_count_ser[0]['total_ser'];
                
            $YestJobService = select_query("select installer.inst_id,installer.inst_name,services.id as id,services.company_name as company_name,services.user_id as user_id,services.service_status,services.request_by,services.Zone_area as Zone_area,services.job_type as job_type, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id left join zone on re_city_spr_1.id_region=zone.id WHERE services.inst_id=".$Inst_id." AND services.atime>='".date("Y-m-d",strtotime('-1 days'))." 00:00:00' AND services.atime<='".date("Y-m-d",strtotime('-100 days'))." 23:59:59' ORDER BY services.atime ASC");          
              
              $company="";$location="";$total=0;$yest_installer_name="";$yest_service_status="";
              
              //while($yest_row_service = mysql_fetch_array($YestJobService))
              for($yjs=0;$yjs<count($YestJobService);$yjs++)
              {
                  
                  if($YestJobService[$yjs]['company_name'] == "")
                  {
                    $user_name_ser = select_query("SELECT UserName FROM internalsoftware.users WHERE Userid='".$YestJobService[$yjs]["user_id"]."'");
                    $company.= $user_name_ser[0]['UserName']."~</br>";  
                  }
                  else
                  {
                      $company.= $YestJobService[$yjs]['company_name']."~</br>";
                  }
                  if($YestJobService[$yjs]['location']!=""){
                    $location.= $YestJobService[$yjs]['location']."~</br>";
                  }else{
                      $location.= $YestJobService[$yjs]['area']."~</br>";
                  }
                  
                  $yest_installer_name = $YestJobService[$yjs]['inst_name'];
                  
                if($YestJobService[$yjs]["service_status"]=="3"  || $YestJobService[$yjs]["service_status"]=="4"){$yest_service_status.="Back -S"."</br>";}
                else if($YestJobService[$yjs]["service_status"]=="5"){$yest_service_status.="Closed -S"."</br>";}
                else if($YestJobService[$yjs]["service_status"]=="6"){$yest_service_status.="BackClosed -S"."</br>";}
                else if($YestJobService[$yjs]["service_status"]=="2" && $YestJobService[$yjs]["job_type"]=="1"){$yest_service_status.="Ongoing -S"."</br>";}
                else if($YestJobService[$yjs]["service_status"]=="2" && $YestJobService[$yjs]["job_type"]=="2"){$yest_service_status.="pending -S"."</br>";}
                else if($YestJobService[$yjs]["service_status"]=="1"){$yest_service_status.="Not Assigned -S"."</br>";}
              }



                  $YestJobInstallation = select_query("select installer.inst_id,installer.inst_name,installation.id as id,installation.company_name as company_name,installation.user_id as user_id,installation.installation_status ,installation.installation_made,installation.Zone_area as Zone_area,installation.job_type as job_type, re_city_spr_1.name as area,installation.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join installation on installer.inst_id=installation.inst_id  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id WHERE installation.inst_id=".$Inst_id." AND installation.time>='".date("Y-m-d",strtotime('-100 days'))." 00:00:00' AND installation.time<='".date("Y-m-d",strtotime('-1 days'))." 23:59:59' ORDER BY installation.time ASC");
                  
              $company2="";$location2="";    $total_inst="";$yest_installer_name2="";$yest_inst_status="";
              
             //while($yest_row_installation = mysql_fetch_array($YestJobInstallation))
             for($yji=0;$yji<count($YestJobInstallation);$yji++)
              {
                  
                  if($YestJobInstallation[$yji]['company_name'] == "")
                  {
                    $user_name_inst = select_query("SELECT UserName FROM internalsoftware.users WHERE Userid='".$YestJobInstallation[$yji]["user_id"]."'");
                    $company2.= $user_name_inst[0]['UserName']."~</br>";  
                  }
                  else
                  {
                    $company2.= $YestJobInstallation[$yji]['company_name']."~</br>";
                  }
                  if($YestJobInstallation[$yji]['location']!=""){    
                    $location2.= $YestJobInstallation[$yji]['location']."~</br>";
                  }else{
                     $location2.= $YestJobInstallation[$yji]['area']."~</br>";
                  }
                  
                  $total_inst.= $YestJobInstallation[$yji]['installation_made']."~</br>";
                  $yest_installer_name2 = $YestJobInstallation[$yji]['inst_name'];
                  
                if($YestJobInstallation[$yji]["installation_status"]=="3"  || $YestJobInstallation[$yji]["installation_status"]=="4"){$yest_inst_status.="Back -I"."</br>";}
                else if($YestJobInstallation[$yji]["installation_status"]=="15"){$yest_inst_status.="SemiClosed -I"."</br>";}
                else if($YestJobInstallation[$yji]["installation_status"]=="5"){$yest_inst_status.="Closed -I"."</br>";}
                else if($YestJobInstallation[$yji]["installation_status"]=="6"){$yest_inst_status.="BackClosed -I"."</br>";}
                else if($YestJobInstallation[$yji]["installation_status"]=="2" && $YestJobInstallation[$yji]["job_type"]=="1"){$yest_inst_status.="Ongoing -I"."</br>";}
                else if($YestJobInstallation[$yji]["installation_status"]=="2" && $YestJobInstallation[$yji]["job_type"]=="2"){$yest_inst_status.="Pending -I"."</br>";}
                else if($YestJobInstallation[$yji]["installation_status"]=="1"){$yest_inst_status.="Not Assigned -I"."</br>";}
              }
      
    ?>  

<?php  if($yest_installer_name != "" || $yest_installer_name2 != "" ){
    
        $excel_data.="<tr><td width='10%'>".$yest_service_status.''.$yest_inst_status."</td><td width='10%'>".$Installer_query[$ui]['inst_name']."</td><td width='30%'>".$location.''.$location2."</td><td width='20%'>".$company.''.$company2."</td><td width='10%'>".$yest_assigned_job."</td><td width='10%'>".$yest_closed_job."</td><td width='10%'>".$installer_stock."</td></tr>";
    ?>
        <tr align="Center" >
            <td><?php  echo $yest_service_status."".$yest_inst_status;?></td>
               <td>&nbsp;<?php echo $Installer_query[$ui]['inst_name'];?></td>
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