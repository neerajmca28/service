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
if($_POST["Installer_name"])
{
     $mode="";
    $Inst_id=$_POST["Installer_name"];
}
?>
 
<div class="top-bar">

<h1>Installer Report <?=$mode;?></h1>

</div>
 
 

                       
           
                <div class="table">
      <?php
     $query=mysql_query("select * from installer where branch_id=".$_SESSION['BranchId']." order by inst_name asc");         
 ?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
       <th>SL.No</th>
        <th>Installer Name </th>
        <th>Current Job </th>
        <th>Pending Job 1 </th>
        <th>Pending Job 2 </th>
        <th>Pending Job 3 </th>
        <th>Pending Job 4 </th>
        <th>Pending Job 5 </th>
        <th>Pending Job 6 </th>
        <th>Pending Job 7 </th>
        <th>Pending Job 8 </th>
        <th>Pending Job 9 </th>
 
        </tr>
    </thead>
    <tbody>

 
   
    <?php
$i=1;
while($data=mysql_fetch_array($query))
 {
 $CurrentJob="";
    $Inst_id=$data['inst_id'];
    $CurrentJobService = mysql_query("select installer.inst_id as id,installer.inst_name as instname,services.id as jobid,services.company_name as company_name,services.veh_reg as veh_reg,services.service_status, services.request_by,services.Zone_area as Zone_area,services.job_type as job_type, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id where services.job_type=1 and   installer.inst_id=".$Inst_id." order by services.req_date desc limit 1");

    $CurrentJobServiceNUM=mysql_num_rows($CurrentJobService);
    if($CurrentJobServiceNUM>0)
    {
    $status="";
        $CurrentJobServicefetch=mysql_fetch_array($CurrentJobService);
        if($CurrentJobServicefetch["service_status"]=="3"  || $CurrentJobServicefetch["service_status"]=="4")
        {
    $status="Back to Service";
        }
        else if($CurrentJobServicefetch["service_status"]=="5" || $CurrentJobServicefetch["service_status"]=="6")
        {
            $status="Closed Service";
        }
        else if($CurrentJobServicefetch["service_status"]=="2")
        {
            $status="Assigned Service";
        }
        $CurrentJob="Current Service At ".$CurrentJobServicefetch["company_name"]."<br/>Loc: ".$CurrentJobServicefetch["re_city_spr_1"]." ".$CurrentJobServicefetch["location"]." <br/>Zone: ".$CurrentJobServicefetch["zone"]." <br/>Job Status: ".$status;
    }


    $CurrentJobInstallation = mysql_query("select installer.inst_id as id,installer.inst_name as instname,installation.id as jobid,installation.company_name as company_name,installation.veh_reg as veh_reg,installation.installation_status ,installation.request_by,installation.Zone_area as Zone_area,installation.job_type as job_type, re_city_spr_1.name as area,installation.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join installation on installer.inst_id=installation.inst_id  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id where  installation.job_type=1 and installer.inst_id=".$Inst_id."  order by installation.req_date desc limit 1");

     
    $CurrentJobInstallationNUM=mysql_num_rows($CurrentJobInstallation);
    if($CurrentJobInstallationNUM>0)
    {
$status="";
        $CurrentJobInstallationfetch=mysql_fetch_array($CurrentJobInstallation);
        if($CurrentJobInstallationfetch["installation_status"]=="3"  || $CurrentJobInstallationfetch["installation_status"]=="4")
        {
    $status="Back to Installation";
        }
        else if($CurrentJobInstallationfetch["installation_status"]=="5" || $CurrentJobInstallationfetch["installation_status"]=="6")
        {
            $status="Closed Installation";
        }
        else if($CurrentJobInstallationfetch["installation_status"]=="2")
        {
            $status="Assigned Installation";
        }
        $CurrentJob="Current Installation At ".$CurrentJobInstallationfetch["company_name"]."<br/>Loc: ".$CurrentJobInstallationfetch["re_city_spr_1"]." ".$CurrentJobInstallationfetch["location"]." <br/>Zone: ".$CurrentJobInstallationfetch["zone"]." <br/>Job Status: ".$status;
    }


    ?>
     
    <tr align="Center">
     <td><?php  echo $i?></td>
       
      
       <td>&nbsp;<?php echo $data['inst_name'];?></td>
      
       <td>&nbsp;<?php echo $CurrentJob;?></td>

       <? $pendingJobService = mysql_query("select installer.inst_id as id,installer.inst_name as instname,services.id as jobid,services.company_name as company_name,services.veh_reg as veh_reg,services.service_status, services.request_by,services.Zone_area as Zone_area,services.job_type as job_type, re_city_spr_1.name as area,services.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join services on installer.inst_id=services.inst_id  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id where services.job_type=2 and   installer.inst_id=".$Inst_id);

    $pendingJobServiceNUM=mysql_num_rows($pendingJobService);
    if($pendingJobServiceNUM>0)
    {
        while($pendingJobServicefetch=mysql_fetch_array($pendingJobService))
        {
            $status="";
            if($pendingJobServicefetch["service_status"]=="3"  || $pendingJobServicefetch["service_status"]=="4")
        {
    $status="Back to Service";
        }
        else if($pendingJobServicefetch["service_status"]=="5" || $pendingJobServicefetch["service_status"]=="6")
        {
            $status="Closed Service";
        }
        else if($pendingJobServicefetch["service_status"]=="2")
        {
            $status="Assigned Service";
        }
        echo $pendingJob=" <td>&nbsp;pending Service At ".$pendingJobServicefetch["company_name"]."<br/>Loc: ".$pendingJobServicefetch["re_city_spr_1"]." ".$pendingJobServicefetch["location"]."<br/> Zone: ".$pendingJobServicefetch["zone"]." <br/>Job Status: ".$status."</td>";
        }
    }


    $pendingJobInstallation = mysql_query("select installer.inst_id as id,installer.inst_name as instname,installation.id as jobid,installation.company_name as company_name,installation.veh_reg as veh_reg,installation.installation_status , installation.request_by,installation.Zone_area as Zone_area,installation.job_type as job_type, re_city_spr_1.name as area,installation.location as location,re_city_spr_1.id_region,zone.name as zone from installer  left join installation on installer.inst_id=installation.inst_id  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  left join zone on re_city_spr_1.id_region=zone.id where  installation.job_type=2 and installer.inst_id=".$Inst_id);

     
    $pendingJobInstallationNUM=mysql_num_rows($pendingJobInstallation);
    if($pendingJobInstallationNUM>0)
    {
        while($pendingJobInstallationfetch=mysql_fetch_array($pendingJobInstallation))
            $status="";
        {if($pendingJobInstallationfetch["service_status"]=="3"  || $pendingJobInstallationfetch["service_status"]=="4")
        {
    $status="Back to Service";
        }
        else if($pendingJobInstallationfetch["service_status"]=="5" || $pendingJobInstallationfetch["service_status"]=="6")
        {
            $status="Closed Service";
        }
        else if($pendingJobInstallationfetch["service_status"]=="2")
        {
            $status="Assigned Service";
        }
        echo $pendingJob="<td>&nbsp;pending Installation At ".$pendingJobInstallationfetch["company_name"]."<br/>Loc: ".$pendingJobInstallationfetch["re_city_spr_1"]." ".$pendingJobInstallationfetch["location"]." <br/>Zone: ".$pendingJobInstallationfetch["zone"]."<br/>Job Status: ".$status."</td>";
        }
    }
      $NumCount=$pendingJobInstallationNUM+$pendingJobServiceNUM;

    if($NumCount<=5)
    {
         
      for($j=0;$j<=(5-$NumCount);$j++)
        {
            echo $pendingJob="<td>&nbsp;--</td>";
        } 
    }
    ?>
   
    </tr> <?php $i++; }?>

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
include("../include/footer.inc.php");

?>