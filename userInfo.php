<?php
error_reporting(0);
ob_start();
session_start();
include("connection.php");
//include_once(__DOCUMENT_ROOT.'/sqlconnection.php');
include_once(__DOCUMENT_ROOT.'/private/master.php');

/*include($_SERVER["DOCUMENT_ROOT"]."/service/connection.php");
//include($_SERVER["DOCUMENT_ROOT"]."/service/sqlconnection.php");
include($_SERVER['DOCUMENT_ROOT']."/service/private/master.php");*/

$masterObj = new master();

$q=$_GET["user_id"];
$inst_id=$_GET["inst_id"];

$veh_reg=$_GET["veh_reg"];
$row_id=$_GET["row_id"];
$comment=$_GET["comment"];


if(isset($_GET['action']) && $_GET['action']=='salespersonname')
{
  $sql="SELECT name AS 'sales_person_name' FROM addclient ac LEFT JOIN sales_person sp ON ac.sales_person_id = sp.id  WHERE ac.Userid=".$q;
  //echo $sql; die;

  $row=select_query($sql);

  echo $row[0]["sales_person_name"];
}

if(isset($_GET['action']) && $_GET['action']=='onlineCrackRND')
{
     $inst_req_id=$_GET["RowId"];
  
    //echo "update internalsoftware.installation set installation_status=11 where inst_req_id='".$inst_req_id."' "; die;
      $UpdateOnlineCrackStatus=mysql_query("update internalsoftware.installation set installation_status=11 where id='".$inst_req_id."' ");
    //if(mysql_query($Updateapprovestatus))
    echo "Successfully Sent to R&D";
  
 }
 if(isset($_GET['action']) && $_GET['action']=='debugComment')
{
    $Comment_by=$_SESSION['username'];
  
    //$Updateapprovestatus="update device_lost set account_comment='".addslashes($comment)."' where id=".$row_id;
      $Updateapprovestatus="insert into matrix.comment(service_id,comment,comment_by) values('".$row_id."','".addslashes($comment)."','".$Comment_by."')";
    //if(mysql_query($Updateapprovestatus))
    echo "Comment Added Successfully";
  
 }

/**************** Old Inventory function Start ******************/

if(isset($_GET['action']) && $_GET['action']=='DetailInstalltion_old')
{
 

   $InstallerId=$_GET["InstallerId"];
    $msg='<table>';
for($N=1;$N<=$_GET["RowId"];$N++)
    {
    $msg.=" <tr><td><input type='text' name='Veh_name$N' required></td><td>";
  
        $result = mssql_query("select device.device_id,device.device_imei,device.itgc_id,sim.sim_no,device.dispatch_date,installerid from ChallanDetail
left join device on ChallanDetail.deviceid=device.device_id
left join sim on device.sim_id=sim.sim_id
 where  device.device_status=64 and ChallanDetail.branchid=".$_SESSION['BranchId']." and  ChallanDetail.CurrentRecord=1 and ChallanDetail.installerid=".$InstallerId);

/*$result = mysql_query("select device.device_id,device.device_imei,device.itgc_id,sim.sim_no,device.dispatch_date,installerid from ChallanDetail
left join device on ChallanDetail.deviceid=device.device_id
left join sim on device.sim_id=sim.sim_id
 where  device.device_status=64 and ChallanDetail.branchid=".$_SESSION['BranchId']." and  ChallanDetail.CurrentRecord=1 and ChallanDetail.installerid=".  $InstallerId);*/

$msg.=" <select  name='DeviceIMEI$N' required>
<option value=''>Select imei</option><option value='01234'>01234</option>";
 
while($row = mssql_fetch_array($result))
//while($row = mysql_fetch_array($result))
  {
    
  $msg .="<option value=".$row['device_imei'].">".$row['device_imei']."</option>";
 
  }
 
  $msg .="</select></td><td>
              <select name='machine$N' required>
                  <option value=''>Machine</option>
                  <option value='Car'>Car</option>
                  <option value='BUS'>BUS</option>
                  <option value='Truck'>Truck</option>
                  <option value='Bike'>Bike</option>
                  <option value='Trailer'>Trailer</option>
                  <option value='Tempo'>Tempo</option>
                  <option value='Jeep'>Jeep</option>                  
                  <option value='Drilto'>Drilto</option>
                  <option value='Hynlma'>Hynlma</option>
                  <option value='Ditchwitch'>Ditchwitch</option>
                  <option value='Vermeer'>Vermeer</option>
                  <option value='LCV'>LCV</option>
             </select></td><td>
        </select></td><td>
              <select name='ac$N' required>
                  <option value=''>AC</option>
                <option value='on'>YES</option>
                <option value='off'>NO</option>
            </select></td><td>
        <select name='immobilizer$N' required>
                  <option value=''>Immobilizer</option>
                <option value='Yes'>YES</option>
                <option value='No'>NO</option>
            </select></td><td>
        <select name='immobilizer_type$N' >
                  <option value=''>Type</option>
                <option value='12V'>12 V</option>
                <option value='24V'>24 V</option>
            </select></td></tr>";
 
      }
    $msg.='</table>';
    echo $msg;
}

if(isset($_GET['action']) && $_GET['action']=='ReInstalltion_old')
{
 
   $UserId=$_GET["UserId"];
    $msg='<table>';
for($N=1;$N<=$_GET["RowId"];$N++)
    {
    $msg.=" <tr><td><input type='text' name='Veh_name$N' required></td><td>";
  
     /*$result = mysql_query("SELECT sys_service_id FROM matrix.tbl_history_devices WHERE sys_group_id=(SELECT sys_group_id FROM matrix.group_users where sys_user_id='".$UserId."') AND remove_date is not null",$dblink);*/
   
    $deactive_query = select_query_live_con("SELECT sys_service_id FROM matrix.group_services WHERE active=0 AND sys_group_id=(SELECT sys_group_id FROM matrix.group_users where sys_user_id='".$UserId."')");
   
    $veh_id_get = "";
    //while($vehicle_id_row = mysql_fetch_array($deactive_query))
    for($ser=0;$ser<count($deactive_query);$ser++)
    {
        $veh_id_get.= $deactive_query[$ser]['sys_service_id']."','";
    }
    $veh_id_data=substr($veh_id_get,0,strlen($veh_id_get)-3);
   
    $device_get_query = select_query_live_con("SELECT id,sys_device_id FROM matrix.services WHERE id IN ('".$veh_id_data."')");
   
    $sys_device_id = "";
    //while($device_get_row = mysql_fetch_array($device_get_query))
    for($de=0;$de<count($device_get_query);$de++)
    {
        $sys_device_id.= $device_get_query[$de]['sys_device_id']."','";
    }
    $sys_device_id_data=substr($sys_device_id,0,strlen($sys_device_id)-3);
   
    $result = select_query_live_con("SELECT device_imei FROM matrix.device_mapping WHERE device_id IN ('".$sys_device_id_data."')");
   
    /*$imei_no_row = "";
    while($imei_get_row = mysql_fetch_array($imei_query))
    {
        $no_of_imei = str_replace("_","",$imei_get_row['device_imei']);
        $imei_no_row.= $no_of_imei."','";
    }
    $imei_no_data=substr($imei_no_row,0,strlen($imei_no_row)-3);
   
     $result = mysql_query("SELECT imei FROM internalsoftware.deletion where user_id='".$UserId."' AND imei in ('".$imei_no_data."') AND final_status=1 AND ((device_status IN('Sold Vehicle','Vehicle Stand For Long Time','Stop GPS') and vehicle_location IN('gtrack office','client office')) or (device_status IN('Device Lost','Device Dead') and vehicle_location IN('gtrack office')) or  (vehicle_location IN('gtrack office','client office') and device_status is null)) order by id DESC");*/
   

$msg.=" <select  name='DeviceIMEI$N' required>
    <option value=''>Select imei</option>";
 
  //while($row = mysql_fetch_array($result))
  for($ime=0;$ime<count($result);$ime++)
  {
     /*$veh_id = $row['sys_service_id'];
        
     $device_query = mysql_fetch_array(mysql_query("SELECT device_imei FROM matrix.device_mapping WHERE device_id=(SELECT sys_device_id FROM matrix.services WHERE id='".$veh_id."')",$dblink));*/
     
     $device_imei = str_replace("_","",$result[$ime]['device_imei']);
    if($device_imei !='')
    {
           $msg .="<option value=".$device_imei.">".$device_imei."</option>";
    }
   
  }
 
  $msg .="</select></td><td>
              <select name='ac$N' required>
                  <option value=''>AC</option>
                <option value='on'>YES</option>
                <option value='off'>NO</option>
            </select></td><td>
        <select name='immobilizer$N' required>
                  <option value=''>Immobilizer</option>
                <option value='Yes'>YES</option>
                <option value='No'>NO</option>
            </select></td><td>
        <select name='immobilizer_type$N' >
                  <option value=''>Type</option>
                <option value='12V'>12 V</option>
                <option value='24V'>24 V</option>
            </select></td></tr>";
 
      }
    $msg.='</table>';
    echo $msg;
}

if(isset($_GET['action']) && $_GET['action']=='sim_change_dropdown_old')
{

   $InstallerId=$_GET["InstallerId"];
  
   $sim_no = mssql_query("select sim.sim_no,sim.phone_no,sim.sim_id,installerid from SimChallanDetail left join sim on SimChallanDetail.sim_id=sim.sim_id
 where  sim.Sim_Status=90 and SimChallanDetail.branchid='".$_SESSION['BranchId']."' and SimChallanDetail.installerid='".$InstallerId."' and
sim.flag=1 AND sim.active_status=1 and sim.is_testsim=0 and SimChallanDetail.CurrentRecord=1");

$sim_count = mssql_num_rows($sim_no);
$msg.=" <table><tr><td>
        <select  name='sim_no' id='sim_no'>
        <option value=''>Select Sim No</option>";
 
while($sim_row = mssql_fetch_array($sim_no))
//while($row = mysql_fetch_array($result))
  {
    
  $msg .="<option value=".$sim_row['phone_no'].">".$sim_row['phone_no']."</option>";
 
 
  }
 
  $msg .="</select></td></tr></table>";
 
echo $msg;
}

if(isset($_GET['action']) && $_GET['action']=='replace_FFC_device_old')
{

   $DeviceModel=$_GET["DeviceModel"];
   $device_warranty_date=$_GET["sys_added_date"];
    if($DeviceModel == 150)
    {
           $device_type = '79,69,86,95,93,0';
          
         $replace_other=mssql_query("SELECT DISTINCT(device.device_imei),item_name,device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty_date."') AS age
 FROM device left join item_master on item_master.item_id=device.device_type WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty_date."') >=0 AND active_status=1 and device_status='69' and device_type in(select item_id from item_master where parent_id not in($device_type)) and dispatch_branch='".$_SESSION['BranchId']."' order by age asc");
    }
    else
    {
           $replace_other=mssql_query("SELECT DISTINCT(device.device_imei),item_name,device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty_date."') AS age
 FROM device left join item_master on item_master.item_id=device.device_type WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty_date."') >=0 AND active_status=1 and device_status='69' and device_type in(select item_id from item_master where parent_id='".$DeviceModel."') and dispatch_branch='".$_SESSION['BranchId']."' order by age asc");
 
            
    }
$replace_other_count = mssql_num_rows($replace_other);

$msg.=" <table><tr><td align='right'>Replace with other Model :</td><td>
        <select  name='replace_withother' id='replace_withother'>
        <option value=''>-- Select One --</option>";
 
    while($data1=mssql_fetch_assoc($replace_other))
      {
        
      $msg .="<option value=".$data1['device_imei'].'-'.$data1['age'].'-'.$data1['item_name'].">".$data1['device_imei'].' ('.$data1['item_name'].' - '.$data1['age'].'Month)'."</option>";
     
    
      }
    
      $msg .="</select></td></tr></table>";
    
    echo $msg;
}

/**************** Old Inventory function close ******************/


/**************** New Inventory function Start ******************/

if(isset($_GET['action']) && $_GET['action']=='DetailInstalltion')
{
 

   $InstallerId=$_GET["InstallerId"];
    $msg='<table>';
    for($N=1;$N<=$_GET["RowId"];$N++)
    {
       
         $msg.=" <tr><td><input type='text' name='Veh_name$N' required></td><td>";
  // echo "select device.device_id, device.device_imei, device.itgc_id, sim.sim_no, device.dispatch_date,
  //                   installerid from inventory.ChallanDetail left join inventory.device on ChallanDetail.deviceid=device.device_id left join inventory.sim on
  //                   device.sim_id=sim.sim_id where  device.device_status=64 and ChallanDetail.branchid='".$_SESSION['BranchId']."' and  
  //                   ChallanDetail.CurrentRecord=1 and ChallanDetail.installerid='".$InstallerId."' "; die;
        $assign_device = select_query_inventory("select device.device_id, device.device_imei, device.itgc_id, sim.sim_no, device.dispatch_date,
                    installerid from inventory.challandetail left join inventory.device on challandetail.deviceid=device.device_id left join inventory.sim on
                    device.sim_id=sim.sim_id where  device.device_status=64 and challandetail.branchid='".$_SESSION['BranchId']."' and  
                    challandetail.CurrentRecord=1 and challandetail.installerid='".$InstallerId."' ");


        $msg.=" <select  name='DeviceIMEI$N' required>
                    <option value=''>Select imei</option>";

        for($aid=0;$aid<count($assign_device);$aid++)
        {
        
              $msg .="<option value=".$assign_device[$aid]['device_imei'].">".$assign_device[$aid]['device_imei']."</option>";
     
        }
 
      $msg .="</select></td><td>
                  <select name='machine$N' required>
                      <option value=''>Machine</option>
                      <option value='Car'>Car</option>
                      <option value='BUS'>BUS</option>
                      <option value='Truck'>Truck</option>
                      <option value='Bike'>Bike</option>
                      <option value='Trailer'>Trailer</option>
                      <option value='Tempo'>Tempo</option>
                      <option value='Jeep'>Jeep</option>                  
                      <option value='Drilto'>Drilto</option>
                      <option value='Hynlma'>Hynlma</option>
                      <option value='Ditchwitch'>Ditchwitch</option>
                      <option value='Vermeer'>Vermeer</option>
                      <option value='LCV'>LCV</option>
                 </select></td><td>
            </select></td><td>
                  <select name='ac$N' required>
                      <option value=''>AC</option>
                    <option value='on'>YES</option>
                    <option value='off'>NO</option>
                </select></td><td>
            <select name='immobilizer$N' required>
                      <option value=''>Immobilizer</option>
                    <option value='Yes'>YES</option>
                    <option value='No'>NO</option>
                </select></td><td>
            <select name='immobilizer_type$N' >
                      <option value=''>Type</option>
                    <option value='12V'>12 V</option>
                    <option value='24V'>24 V</option>
                </select></td></tr>";
 
      }
    $msg.='</table>';
    echo $msg;
}

if(isset($_GET['action']) && $_GET['action']=='ReInstalltion')
{
 
    $UserId=$_GET["UserId"];
    $msg='<table>';
    for($N=1;$N<=$_GET["RowId"];$N++)
    {
        $msg.=" <tr><td><input type='text' name='Veh_name$N' required></td><td>";
  
       
        $deactive_query = select_query_live_con("SELECT sys_service_id FROM matrix.group_services WHERE active=0 AND
                            sys_group_id=(SELECT sys_group_id FROM matrix.group_users where sys_user_id='".$UserId."')");
       
        $veh_id_get = "";
            
        for($ser=0;$ser<count($deactive_query);$ser++)
        {
            $veh_id_get.= $deactive_query[$ser]['sys_service_id']."','";
        }
        
        $veh_id_data=substr($veh_id_get,0,strlen($veh_id_get)-3);
       
        $device_get_query = select_query_live_con("SELECT id,sys_device_id FROM matrix.services WHERE id IN ('".$veh_id_data."')");
       
        $sys_device_id = "";
        for($de=0;$de<count($device_get_query);$de++)
        {
            $sys_device_id.= $device_get_query[$de]['sys_device_id']."','";
        }
        $sys_device_id_data=substr($sys_device_id,0,strlen($sys_device_id)-3);
       
        $result = select_query_live_con("SELECT device_imei FROM matrix.device_mapping WHERE device_id IN ('".$sys_device_id_data."')");
   

        $msg.=" <select  name='DeviceIMEI$N' required>
            <option value=''>Select imei</option>";
         
          for($ime=0;$ime<count($result);$ime++)
          {
     
            $device_imei = str_replace("_","",$result[$ime]['device_imei']);
            if($device_imei !='')
            {
                $msg .="<option value=".$device_imei.">".$device_imei."</option>";
            }
   
          }
 
      $msg .="</select></td><td>
                  <select name='ac$N' required>
                      <option value=''>AC</option>
                    <option value='on'>YES</option>
                    <option value='off'>NO</option>
                </select></td><td>
            <select name='immobilizer$N' required>
                      <option value=''>Immobilizer</option>
                    <option value='Yes'>YES</option>
                    <option value='No'>NO</option>
                </select></td><td>
            <select name='immobilizer_type$N' >
                      <option value=''>Type</option>
                    <option value='12V'>12 V</option>
                    <option value='24V'>24 V</option>
                </select></td></tr>";
 
      }
    $msg.='</table>';
    echo $msg;
}

if(isset($_GET['action']) && $_GET['action']=='sim_change_dropdown')
{

   $InstallerId=$_GET["InstallerId"];

   $sim_no = select_query_inventory("select sim.sim_no, sim.phone_no, sim.sim_id, installerid from inventory.simchallandetail left join inventory.sim on
                         simchallandetail.sim_id=sim.sim_id where  sim.Sim_Status=90 and simchallandetail.branchid='".$_SESSION['BranchId']."'
                    and simchallandetail.installerid='".$InstallerId."' and sim.flag=1 AND sim.active_status=1 and sim.is_testsim=0 and
                    simchallandetail.CurrentRecord=1");

    $msg.=" <table><tr><td>
            <select  name='sim_no' id='sim_no'>
            <option value=''>Select Sim No</option>";
 
    for($sn=0;$sn<count($sim_no);$sn++)
      {
        
      $msg .="<option value=".$sim_no[$sn]['phone_no'].">".$sim_no[$sn]['phone_no']."</option>";
     
     
      }
     
      $msg .="</select></td></tr></table>";
     
    echo $msg;
}


if(isset($_GET['action']) && $_GET['action']=='replace_FFC_device')
{

   $DeviceModel=$_GET["DeviceModel"];
   $device_warranty_date=$_GET["sys_added_date"];
    if($DeviceModel == 150)
    {
         $device_type = '79,69,86,95,93,0';
          
         $replace_other = select_query_inventory("SELECT DISTINCT(device.device_imei), item_name,device.recd_date,
                 TIMESTAMPDIFF(MONTH,device.recd_date,'".$device_warranty_date."') AS age FROM inventory.device left join inventory.item_master on
                item_master.item_id=device.device_type WHERE is_ffc=1 AND TIMESTAMPDIFF(MONTH,device.recd_date,'".$device_warranty_date."') >=0
                AND active_status=1 and device_status='69' and device_type in(select item_id from inventory.item_master where parent_id
                not in($device_type)) and dispatch_branch='".$_SESSION['BranchId']."' order by age asc");
    }
    else
    {
           $replace_other = select_query_inventory("SELECT DISTINCT(device.device_imei), item_name, device.recd_date,
                   TIMESTAMPDIFF(MONTH,device.recd_date,'".$device_warranty_date."') AS age FROM inventory.device left join inventory.item_master on
                item_master.item_id=device.device_type WHERE is_ffc=1 AND TIMESTAMPDIFF(MONTH,device.recd_date,'".$device_warranty_date."') >=0
                AND active_status=1 and device_status='69' and device_type in(select item_id from inventory.item_master where
                parent_id='".$DeviceModel."') and dispatch_branch='".$_SESSION['BranchId']."' order by age asc");
 
    }
    
    
    $msg.=" <table><tr><td align='right'>Replace with other Model :</td><td>
            <select  name='replace_withother' id='replace_withother'>
            <option value=''>-- Select One --</option>";
 
    for($ffc=0;$ffc<count($replace_other);$ffc++)
      {
        
      $msg .="<option value=".$replace_other[$ffc]['device_imei'].'-'.$replace_other[$ffc]['age'].'-'.$replace_other[$ffc]['item_name'].">".$replace_other[$ffc]['device_imei'].' ('.$replace_other[$ffc]['item_name'].' - '.$replace_other[$ffc]['age'].'Month)'."</option>";
     
    
      }
    
      $msg .="</select></td></tr></table>";
    
    echo $msg;
    
}

/**************** New Inventory function Close ******************/


if(isset($_GET['action']) && $_GET['action']=='DetailClient')
{

   $branch_id=$_GET["RowId"];
   
   if($branch_id != "")
   {
       $query = select_query("select * from internalsoftware.installer where branch_id='".$branch_id."' and is_delete=1 order by inst_name asc");
      
       $main_user_data = select_query("SELECT Userid AS user_id,UserName AS name FROM internalsoftware.addclient WHERE Branch_id='".$branch_id."'");
           
        $msg.=" <table><tr><td >Installer Name</td><td>
                <select  name='Installer_name' id='Installer_name'>
                <option value=''>-- Select Name --</option>";
         
         for($inst=0;$inst<count($query);$inst++)
         {
             
          $msg .="<option value=".$query[$inst]['inst_id'].">".$query[$inst]['inst_name']."</option>";
          
          }
          $msg .="</select></td><td >Client Name</td><td>
                <select  name='main_user_id' id='main_user_id'>
                <option value=''>-- Select Client --</option>";
       
         for($cl=0;$cl<count($main_user_data);$cl++)
           {
              
          $msg .="<option value=".$main_user_data[$cl]['user_id'].">".$main_user_data[$cl]['name']."</option>";
          
          }
       
          $msg .="</select></td></tr></table>";
         
        echo $msg;
   }
}

if(isset($_GET['action']) && $_GET['action']=='DetailVehicle')
{
    $user_Id=$_GET["user_Id"];
    $msg='<table>';
for($N=1;$N<=$_GET["RowId"];$N++)
    {
      $msg .=" <tr><td>";
     
     /*$result="select services.id,veh_reg from matrix.services where services.id in (select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (select sys_group_id from matrix.group_users where sys_user_id=(".$user_Id.")))";

$data=mysql_query($result,$dblink);*/
    
    $vehicledata = $masterObj->getVehicleDetail($user_Id);

$msg.=' <select  name="veh_reg$N" id="veh_reg_replce" onchange="getdeviceImei(this.value,\'replaceDeviceIMEI'.$N.'\')">
<option value="0">Select Vehicle No</option>';

//while($row = mysql_fetch_array($data))
  for($veh=0;$veh<count($vehicledata);$veh++)
  {
  $msg .="<option value=".$vehicledata[$veh]['veh_reg'].">".$vehicledata[$veh]['veh_reg']."</option>";
  }
  $msg .="</select></td>";
  $msg .="<td><input type='text' name='replaceDeviceIMEI[]' id='replaceDeviceIMEI$N' value=".$row['replaceDeviceIMEI']."></td></tr>";
    }
    $msg.='</table>';
    echo $msg;
}


if(isset($_GET['action']) && $_GET['action']=='getdata')
{
 
   /*$result="select services.id as id,services.id,veh_reg from matrix.services
where services.id in
(select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (
select sys_group_id from matrix.group_users where sys_user_id=(".$q.")))";
$data=mysql_query($result,$dblink);*/

    $vehicledata = $masterObj->getVehicleDetail($q);

$msg=' <select name="veh_reg" id="veh_reg" onchange="getdeviceImei(this.value,\'TxtDeviceIMEI\');getInstaltiondate(this.value,\'date_of_install\');getdevicemobile(this.value,\'Devicemobile\');getNotwokingdate(this.value,\'Notwoking\')">
<option value="0">Select Vehicle No</option>';

//while($row = mysql_fetch_array($data))
 for($veh=0;$veh<count($vehicledata);$veh++)
  {
    if($veh%3==0) {
        $msg .="</tr><tr>";
    }
  $msg .="<option value='".$vehicledata[$veh]['veh_reg']."'>".$vehicledata[$veh]['veh_reg']."</option>";
 
  }
 
 
  $msg .="</select>";
 
  echo $msg;
}


if(isset($_GET['action']) && $_GET['action']=='getdatareplce')
{
 
  /*$result="select services.id as id,services.id,veh_reg from matrix.services
where services.id in
(select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (
select sys_group_id from matrix.group_users where sys_user_id=(".$q.")))";
$data=mysql_query($result,$dblink);*/
    
    $vehicledata = $masterObj->getVehicleDetail($q);

$msg=' <select name="veh_reg_replce" id="veh_reg_replce" onchange="getdeviceImei(this.value,\'replaceDeviceIMEI\');getInstaltiondate(this.value,\'replacedate_of_install\');getInstaltiondate(this.value,\'replacedate_of_install\');getdevicemobile(this.value,\'replaceDevicemobile\');Notwoking(this.value,\'Notwoking\')">
<option value="0">Select Vehicle No</option>';

//while($row = mysql_fetch_array($data))
  for($veh=0;$veh<count($vehicledata);$veh++)
  {
    if($veh%3==0) {
        $msg .="</tr><tr>";
    }
  $msg .="<option value=".$vehicledata[$veh]['veh_reg'].">".$vehicledata[$veh]['veh_reg']."</option>";

  }
 
 
  $msg .="</select>";
 
  echo $msg;
}
 


if(isset($_GET['action']) && $_GET['action']=='total')
    {
        
        $vehicledata = $masterObj->getVehicleDetail($q);
        
        echo count($vehicledata);
        
/*$result="select services.id as id,services.id,veh_reg from matrix.services
where services.id in
(select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (
select sys_group_id from matrix.group_users where sys_user_id=(".$q.")))";
                                                              
$data=mysql_query($result,$dblink);
        echo mysql_num_rows($data);*/
    }

if(isset($_GET['action']) && $_GET['action']=='companyname')
    {
        
        /*$sql="select `group`.name as company from matrix.group_users left join matrix.`group` on group_users.sys_group_id=`group`.id where group_users.sys_user_id=".$q;
        $row=select_query_live($sql);*/
        
        $commpany = $masterObj->getCompanyName($q);
        echo $commpany[0]["company"];
        
   }

if(isset($_GET['action']) && $_GET['action']=='installermobile')
{
  
    $sql="select installer_mobile as installer_mobile from installer where inst_id=".$inst_id;

    $row=select_query($sql);

    echo $row[0]["installer_mobile"];
}


if(isset($_GET['action']) && $_GET['action']=='creationdate')
    {
        
        /*$sql="select * from matrix.users where id=".$q;
        $row=select_query_live($sql);*/
        
        $add_date = $masterObj->getCreationDate($q);
        
        echo date("d-M-Y",strtotime($add_date[0]["sys_added_date"]));
    }

if(isset($_GET['action']) && $_GET['action']=='forwardtoRepairComment')
{
     
    $Updateapprovestatus="update services set service_status='11', fwd_serv_to_repair='".date("Y-m-d H:i:s")." - ".$comment."' where id=".$row_id;
     
     mysql_query($Updateapprovestatus,$dblink2);
  
 }
 
if(isset($_GET['action']) && $_GET['action']=='forward_Repair_Install_Comment')
{
     
    $Updateapprovestatus="update installation set installation_status='11', fwd_install_to_repair='".date("Y-m-d H:i:s")." - ".$comment."' where id=".$row_id;
     
     mysql_query($Updateapprovestatus,$dblink2);
  
 }

if(isset($_GET['action']) && $_GET['action']=='deviceImei')
    {
        
    /*$sql1="select imei from matrix.devices where id in (select sys_device_id from matrix.services where veh_reg='".$veh_reg."') limit 1";
    $row=select_query_live($sql1);*/
    
    $veh_reg = str_replace(","," ",$veh_reg);
    
    $imeino = $masterObj->getDeviceImei($veh_reg);
    
     echo $imeino[0]["imei"];
    }
  
  
if(isset($_GET['action']) && $_GET['action']=='deviceMobile')
    {
        
    /*$sql1="select mobile_no from matrix.mobile_simcards where id in ( select sys_simcard from matrix.devices where id in (select sys_device_id from matrix.services where veh_reg='".$veh_reg."'))";
    $row=select_query_live($sql1);*/
    
    $phoneno = $masterObj->getDeviceMobile($veh_reg);
    
     echo $phoneno[0]["mobile_no"];
    }



if(isset($_GET['action']) && $_GET['action']=='Instaltiondate')
    {
        
        /*$sql="select sys_created from matrix.services where veh_reg='".$veh_reg."' limit 1";
        $row=select_query_live($sql);*/
        
        $inst_date = $masterObj->getDeviceInstaltiondate($veh_reg);
        
        echo date("d-M-Y",strtotime($inst_date[0]["sys_created"]));
    }
  
if(isset($_GET['action']) && $_GET['action']=='Notwokingdate')
    {
        
        /*$sql="select  ADDDATE(gps_time, INTERVAL -330 MINUTE) as notworkingDate from matrix.latest_telemetry where sys_service_id in (select id from matrix.services where veh_reg='".$veh_reg."' )";
        $row=select_query_live($sql);*/
        
        $notworking = $masterObj->getDeviceNotwokingdate($veh_reg);
        
        echo date("d-M-Y H:i:s",strtotime($notworking[0]["notworkingDate"]));
    }
  


    
 
    if(isset($_GET['action']) && $_GET['action']=='getrowsValue')
    {
        ?>
<style type="text/css">
#databox {
    width:840px;
    height:650px;
    margin: 30px auto auto;
    border:1px solid #bfc0c1;
    font-family:Arial, Helvetica, sans-serif;
    font-size:13px;
    font-weight:normal;
    color:#3f4041;
}
.heading {
    font-family:Arial, Helvetica, sans-serif;
    font-size:30px;
    font-weight:700;
    word-spacing:5px;
    text-align:center;
    color:#3E3E3E;
    background-color:#ECEFE7;
    margin-bottom:10px;
}
.dataleft {
    float:left;
    width:400px;
    height:400px;
    margin-left:10px;
    border-right:1px solid #bfc0c1;
}
.dataright {
    float:right;
    width:400px;
    height:400px;
    margin-left:19px;
}
td {
    padding-right:20px;
    padding-left:20px;
}

</style>
<?
        
            $RowId=$_GET["RowId"];
            $tablename=$_GET["tablename"];
          
    


if($tablename=="installation")
{
      //echo 'tt'; die;
      $query = "select * from  installation left join re_city_spr_1 on installation.Zone_area =re_city_spr_1.id where installation.id=".$RowId;
     // echo $query; die;

    $row=select_query($query);
   // echo '<pre>'; print_r($row);die;
    
    if($row[0]['fwd_tech_rm_id'] != "" && $row[0]['fwd_repair_id'] == ""){
        $support_query = select_query("select user_name from request_forward_list where user_id='".$row[0]['fwd_tech_rm_id']."'");
        $forward_name = $support_query[0]['user_name'];
    } else if($row[0]['fwd_repair_id'] != "") {
        $support_query = select_query("select user_name from request_forward_list where user_id='".$row[0]['fwd_repair_id']."'");
        $forward_name = $support_query[0]['user_name'];
    } else { $forward_name = '';}

     $toolk=explode('#',$row[0]['accessories_tollkit']);
     $tools=array();
     $accessory_data=array();
    // echo '<pre>'; print_r($toolk);die;
    ?>
<div id="databox">
  <div class="heading">Installation</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td> Date: </td>
          <td><?echo $row[0]["req_date"];?></td>
        </tr>
        <tr>
          <td>Request By: </td>
          <td><?echo $row[0]["request_by"];?></td>
        </tr>
        <?
$sales=mysql_fetch_array(mysql_query("select name from sales_person where id='".$row[0]['sales_person']."'"));
?>
        <tr>
          <td>Sales Person </td>
          <td><?echo $sales['name'];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["company_name"];?></td>
        </tr>
        <tr>
          <td>No. Of Vehicles: </td>
          <td><?echo $row[0]["no_of_vehicals"];?></td>
        </tr>
        <tr>
          <td>Approve Installation: </td>
          <td><?echo $row[0]["installation_approve"];?></td>
        </tr>
        <tr>
          <td>Area: </td>
          <td><?echo $row[0]["name"];?></td>
        </tr>
        
        <?php if($row[0]['location']!=""){?>
        <tr>
          <td>Location: </td>
          <td><?echo $row[0]["location"];?></td>
        </tr>
        <?php }else{ $city= select_query("select * FROM internalsoftware.tbl_city_name where branch_id='".$row[0]['inter_branch']."'");?>
        <tr>
          <td>Location: </td>
          <td><?echo $city[0]["city"];?></td>
        </tr>
        <?php }?>
        <tr>
        <!-- <tr>
          <td>Model:</td>
          <td><?echo $row[0]["model"];?></td>
        </tr> -->
         <tr>
        <?php 
        //$sqlDevice=select_query("SELECT device_type FROM device_type where id='".$row[0]["device_type"]."' ");   
         $sqlDevice=select_query("SELECT item_name FROM item_master where item_id='".$row[0]["device_type"]."'");
         ?>
          <td>Device Type:</td>
          <td><?echo $sqlDevice[0]["item_name"];?></td>
        </tr>
        <tr>
        <?php 
        //$sqlModel=select_query("SELECT device_model FROM device_model where id='".$row[0]["model"]."' ");   
         $sqlModel=select_query("select im.* from installation_request ir left join item_master im on ir.model=im.item_id where ir.model='".$row[0]["model"]."'");
         ?>
          <td>Model:</td>
          <td><?echo $sqlModel[0]["item_name"];?></td>
        </tr>

        <tr>
          <td>Available Time Status: </td>
          <td><?echo $row[0]["atime_status"];?></td>
        </tr>
        <tr>
          <td>Time: </td>
          <td><?echo $row[0]["time"];?></td>
        </tr>
        <tr>
          <td>To Time: </td>
          <td><?echo $row[0]["totime"];?></td>
        </tr>
        
       <tr>
          <td>Vehicle Type: </td>
          <td><?echo $row[0]["veh_type"];?></td>
        </tr>
        <tr>
          <td>Trailer Type </td>
          <td><?echo $row[0]["TrailerType"];?></td>
        </tr>
        <tr>
          <td>Machine Type</td>
          <td><?echo $row[0]["MachineType"];?></td>
        </tr>
        <tr>
          <td>AC/Non AC </td>
          <td><?echo $row[0]["actype"];?></td>
        </tr>
        <tr>
          <td>Delux/Non Delux</td>
          <td><?echo $row[0]["standard"];?></td>
        </tr>
        <tr>
          <td>Truck Type</td>
          <td><?echo $row[0]["TruckType"];?></td>
        </tr>
         <tr>
          <td>Billing</td>
          <td><?echo $row[0]["billing"];?></td>
        </tr>
      
       
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Job: </td>
          <td><?echo $row[0]["instal_reinstall"];?></td>
        </tr>
        <?php  for($v=0;$v<count($toolk);$v++)
        {
        //$tools[]=$toolk[$v]; 
         // echo "SELECT items AS `access_name` FROM toolkit_access where id='".$toolk[$v]."' ORDER BY `access_name` asc"; die;
         $accessory_data=select_query("SELECT items AS `access_name` FROM toolkit_access where id='".$toolk[$v]."' ORDER BY `access_name` asc");
         if($accessory_data!="")
         {?>
          <tr>
            <td><?php echo $accessory_data[0]['access_name'];?> </td>
           <td>Yes</td>
          <tr>
         <?php }
         
       }
    // echo $accessories_tollkits; die;
     ?>
     <tr>
          <td>Contact No.:</td>
          <td><?echo $row[0]["contact_number"];?></td>
        </tr>
        <tr>
          <td>Contact Person: </td>
          <td><?echo $row[0]["contact_person"];?></td>
        </tr>
          <tr>
          <td>Alternate Contact No.:</td>
          <td><?echo $row[0]["alt_cont_number"];?></td>
        </tr>
        <tr>
          <td>Alternate Contact Person: </td>
          <td><?echo $row[0]["alt_cont_person"];?></td>
        </tr>

        <tr>
          <td>Installation Made: </td>
          <td><?echo $row[0]["installation_made"];?></td>
        </tr>
        <tr>
          <td>Installer Name: </td>
          <td><?echo $row[0]["inst_name"];?></td>
        </tr>
        <tr>
          <td>Installer Current Location: </td>
          <td><?echo $row[0]["inst_cur_location"];?></td>
        </tr>
        <tr>
          <td>Installation Done At: </td>
          <td><?echo $row[0]["rtime"];?></td>
        </tr>
        <tr>
          <td>Reason To Back Services:</td>
          <td><?echo $row[0]["back_reason"];?></td>
        </tr>
        <tr>
          <td>Forward to <strong>
            <?=$forward_name;?>
            </strong> :</td>
          <td><? if($row[0]['fwd_reason'] != "" && $row[0]['fwd_tech_rm_id'] != "" && $row[0]['fwd_repair_id'] == "") {
            echo $row[0]['fwd_datetime'].' - '.$row[0]['fwd_reason'];
        } else if($row[0]['fwd_install_to_repair'] != "" && $row[0]['fwd_repair_id'] == "") {
            echo $row[0]['fwd_install_to_repair']; 
        } else if($row[0]['fwd_install_to_repair'] != "" && $row[0]['fwd_repair_id'] != "") {
            echo $row[0]['fwd_repair_date'].' - '.$row[0]['fwd_install_to_repair']; 
        }
    ?></td>
        </tr>
        <tr>
          <td>Reply Comment:</td>
          <td><? if(($row[0]['fwd_tech_rm_id'] != "" || $row[0]['fwd_repair_id'] == "") && $row[0]["fwd_repair_to_install"] != ""){
             echo $row[0]['fwd_done_time'].' - '.$row[0]["fwd_repair_to_install"];
        } else {
            echo $row[0]["fwd_repair_to_install"];
        }
     ?></td>
        </tr>
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["installation_status"]==7 && ($row[0]["admin_comment"]!="" || $row[0]["sales_comment"]=="")){echo "Reply Pending at Request Side";}
    elseif($row[0]["installation_status"]==7 && $row[0]["admin_comment"]=="" ){echo "Pending Saleslogin for Information";}
    elseif($row[0]["approve_status"]==0 && $row[0]["installation_status"]==8 ){echo "Pending Admin Approval";}
    elseif($row[0]["installation_status"]==9 && $row[0]["approve_status"]==1 ){echo "Pending Confirmation at Request Person";}
    elseif($row[0]["installation_status"]==1 ){echo "Pending Dispatch Team";}
    elseif($row[0]["installation_status"]==2 ){echo "Assign To Installer";}
    elseif($row[0]["installation_status"]==11 ){echo "Request Forward to ".$forward_name;}
    elseif($row[0]["installation_status"]==3 ){echo "Back Installation";}
    elseif($row[0]["installation_status"]==15 ){echo "Pending Remaining Installation";}
    elseif($row[0]["installation_status"]==5 || $row[0]["installation_status"]==6){echo "Installation Close";}?>
            </strong></td>
        </tr>
        <?php if($_SESSION['BranchId']==1){?>
        <tr>
          <td>Sales Comment</td>
          <td><?echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Approval</td>
          <td><?if($row[0]["approve_status"]==1) echo "Approved";?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
    if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
    {
        echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
    }
    else
    {
        echo "";
    }
  
    ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<? }

elseIf($tablename=="installation_request")
        {
   
    $query = "select * FROM internalsoftware.installation_request left join internalsoftware.re_city_spr_1 on installation_request.Zone_area =re_city_spr_1.id where installation_request.id=".$RowId;
    $row=select_query($query);
  //echo '<pre>'; print_r($row);die;
    //echo $row[0]['accessories_tollkit']; die;
   $toolk=explode('#',$row[0]['accessories_tollkit']);
   $tools=array();
   $accessory_data=array();

    ?>
<div id="databox">
  <div class="heading">Installation Request</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td> Date: </td>
          <td><?echo $row[0]["req_date"];?></td>
        </tr>
        <tr>
          <td>Request By: </td>
          <td><?echo $row[0]["request_by"];?></td>
        </tr>
        <? 
    $sales=select_query("select name FROM internalsoftware.sales_person where id='".$row[0]['sales_person']."' ");
    ?>
        <tr>
          <td>Sales Person </td>
          <td><?echo $sales[0]['name'];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["company_name"];?></td>
        </tr>
        <tr>
          <td>No. Of Vehicles: </td>
          <td><?echo $row[0]["no_of_vehicals"];?></td>
        </tr>
        <tr>
          <td>Approve Installation: </td>
          <td><?echo $row[0]["installation_approve"];?></td>
        </tr>
        <tr>
          <td>Area: </td>
          <td><?echo $row[0]["name"];?></td>
        </tr>
        <?php if($row[0]['location']!=""){?>
        <tr>
          <td>Location: </td>
          <td><?echo $row[0]["location"];?></td>
        </tr>
        <?php }else{ $city= select_query("select * FROM internalsoftware.tbl_city_name where branch_id='".$row[0]['inter_branch']."'");?>
        <tr>
          <td>Location: </td>
          <td><?echo $city[0]["city"];?></td>
        </tr>
        <?php }?>
        <tr>
        <?php 
        //$sqlDevice=select_query("SELECT device_type FROM device_type where id='".$row[0]["device_type"]."' ");
        
        $sqlDevice=select_query("SELECT item_name FROM item_master where item_id='".$row[0]["device_type"]."'");
           ?>
          <td>Device Type:</td>
          <td><?echo $sqlDevice[0]["item_name"];?></td>
        </tr>
        <tr>
        <?php 
        //$sqlModel=select_query("SELECT device_model FROM device_model where id='".$row[0]["model"]."' ");  
         $sqlModel=select_query("select im.* from installation_request ir left join item_master im on ir.model=im.item_id where ir.model='".$row[0]["model"]."'");
          ?>
          <td>Model:</td>
          <td><?echo $sqlModel[0]["item_name"];?></td>
        </tr>
        <tr>
          <td>Available Time Status: </td>
          <td><?echo $row[0]["atime_status"];?></td>
        </tr>
        <tr>
          <td>Time: </td>
          <td><?echo $row[0]["time"];?></td>
        </tr>
        <tr>
          <td>To Time: </td>
          <td><?echo $row[0]["totime"];?></td>
        </tr>
        <tr>
          <td>Contact No.:</td>
          <td><?echo $row[0]["contact_number"];?></td>
        </tr>
        <tr>
          <td>Contact Person: </td>
          <td><?echo $row[0]["contact_person"];?></td>
        </tr>
        <tr>
          <td>Alternative Contact No.</td>
          <td><?echo $row[0]["alter_contact_no"];?></td>
        </tr>
        <tr>
          <td>Designation</td>
          <td><?echo $row[0]["designation"];?></td>
        </tr>
          <tr>
          <td>Vehicle Type: </td>
          <td><?echo $row[0]["veh_type"];?></td>
        </tr>
        <tr>
          <td>Trailer Type </td>
          <td><?echo $row[0]["TrailerType"];?></td>
        </tr>
        <tr>
          <td>Machine Type</td>
          <td><?echo $row[0]["MachineType"];?></td>
        </tr>
        <tr>
          <td>AC/Non AC </td>
          <td><?echo $row[0]["actype"];?></td>
        </tr>
        <tr>
          <td>Delux/Non Delux</td>
          <td><?echo $row[0]["standard"];?></td>
        </tr>
        <tr>
          <td>Truck Type</td>
          <td><?echo $row[0]["TruckType"];?></td>
        </tr>
         <tr>
          <td>Billing</td>
          <td><?echo $row[0]["billing"];?></td>
        </tr></tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Job: </td>
          <td><?echo $row[0]["instal_reinstall"];?></td>
        </tr>
     <?php  for($v=0;$v<count($toolk);$v++)
      {
        //$tools[]=$toolk[$v]; 
         $accessory_data=select_query("SELECT items AS `access_name` FROM toolkit_access where id='".$toolk[$v]."' ORDER BY `access_name` asc");
         if($accessory_data!="")
         {?>
          <tr>
            <td><?php echo $accessory_data[0]['access_name'];?> </td>
           <td>Yes</td>
          <tr>
         <?php }
         
      }
    // echo $accessories_tollkits; die;
     ?>

       
        <tr>
          <td>Contact Person: </td>
          <td><?echo $row[0]["contact_person"];?></td>
        </tr>
        <tr>
          <td>Contact No.: </td>
          <td><?echo $row[0]["contact_number"];?></td>
        </tr>
        <tr>
        <tr>
          <td>Designation.: </td>
          <td><?echo $row[0]["designation"];?></td>
        </tr>

         <tr>
          <td>Alternative Contact Person.: </td>
          <td><?echo $row[0]["alt_cont_person"];?></td>
        </tr>
        <tr>
          <td>Alternative Contact No.: </td>
          <td><?echo $row[0]["alter_contact_no"];?></td>
        </tr>
          <tr>
          <td>Designation.: </td>
          <td><?echo $row[0]["alt_designation"];?></td>
        </tr>
        <tr>
          <td>Installation Made: </td>
          <td><?echo $row[0]["installation_made"];?></td>
        </tr>
        <tr>
          <td>Installer Name: </td>
          <td><?echo $row[0]["inst_name"];?></td>
        </tr>
        <tr>
          <td>Installer Current Location: </td>
          <td><?echo $row[0]["inst_cur_location"];?></td>
        </tr>
        <!--<tr><td>Change Installer Name: </td><td><?echo $row[0]["inst_cur_location"];?></td></tr>  
-->
        <tr>
          <td>Installation Done At: </td>
          <td><?echo $row[0]["rtime"];?></td>
        </tr>
        <tr>
          <td>Reason To Back Services:</td>
          <td><?echo $row[0]["back_reason"];?></td>
        </tr>
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["installation_status"]==7 && ($row[0]["admin_comment"]!="" || $row[0]["sales_comment"]=="")){echo "Reply Pending at Request Side";}
        elseif($row[0]["installation_status"]==7 && $row[0]["admin_comment"]=="" ){echo "Pending Saleslogin for Information";}
        elseif($row[0]["approve_status"]==0 && $row[0]["installation_status"]==8 ){echo "Pending Admin Approval";}
        elseif($row[0]["installation_status"]==9 && $row[0]["approve_status"]==1 ){echo "Pending Confirmation at Request Person";}
        elseif($row[0]["installation_status"]==1 ){echo "Pending Dispatch Team";}
        elseif($row[0]["installation_status"]==2 ){echo "Assign To Installer";}
        elseif($row[0]["installation_status"]==11 ){echo "Request Forward to Repair Team";}
        elseif($row[0]["installation_status"]==3 ){echo "Back Installation";}
        elseif($row[0]["installation_status"]==15 ){echo "Pending Remaining Installation";}
        elseif($row[0]["installation_status"]==5 || $row[0]["installation_status"]==6){echo "Installation Close";}?>
            </strong></td>
        </tr>
        <?php if($_SESSION['BranchId']==1 || $row[0]["inter_branch"]==1){?>
        <tr>
          <td>Sales Comment</td>
          <td><?echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td>Approval Date</td>
          <td><?
    if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
    {
    echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
    }
    else
    {
        echo "";
    }
   
    ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<? }
else If($tablename=="installation_history_tbl")
        {
  
    $query = "select * from  installation_history_tbl left join re_city_spr_1 on installation_history_tbl.Zone_area =re_city_spr_1.id where installation_history_tbl.id=".$RowId;
     $row=select_query($query);
    ?>
<div id="databox">
  <div class="heading">Back Installation</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td> Date: </td>
          <td><?echo $row[0]["req_date"];?></td>
        </tr>
        <tr>
          <td>Request By: </td>
          <td><?echo $row[0]["request_by"];?></td>
        </tr>
        <?
$sales=mysql_fetch_array(mysql_query("select name from sales_person where id='".$row[0]['sales_person']."'"));
?>
        <tr>
          <td>Sales Person </td>
          <td><?echo $sales['name'];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["company_name"];?></td>
        </tr>
        <tr>
          <td>No. Of Vehicles: </td>
          <td><?echo $row[0]["no_of_vehicals"];?></td>
        </tr>
        <tr>
          <td>Approve Installation: </td>
          <td><?echo $row[0]["installation_approve"];?></td>
        </tr>
        <tr>
          <td>Area: </td>
          <td><?echo $row[0]["name"];?></td>
        </tr>
        
        <!--<tr><td>Location: </td><td><?echo $row[0]["location"];?></td></tr>-->
        <?php if($row[0]['location']!=""){?>
        <tr>
          <td>Location: </td>
          <td><?echo $row[0]["location"];?></td>
        </tr>
        <?php }else{ $city= mysql_fetch_array(mysql_query("select * from tbl_city_name where branch_id='".$row[0]['inter_branch']."'"));?>
        <tr>
          <td>Location: </td>
          <td><?echo $city["city"];?></td>
        </tr>
        <?php }?>
        <tr>
          <td>Model:</td>
          <td><?echo $row[0]["model"];?></td>
        </tr>
        <tr>
          <td>Available Time Status: </td>
          <td><?echo $row[0]["atime_status"];?></td>
        </tr>
        <tr>
          <td>Time: </td>
          <td><?echo $row[0]["time"];?></td>
        </tr>
        <tr>
          <td>To Time: </td>
          <td><?echo $row[0]["totime"];?></td>
        </tr>
        <tr>
          <td>Contact No.:</td>
          <td><?echo $row[0]["contact_number"];?></td>
        </tr>
        <tr>
          <td>Contact Person: </td>
          <td><?echo $row[0]["contact_person"];?></td>
        </tr>
        <tr>
          <td>DIMTS: </td>
          <td><?echo $row[0]["dimts"];?></td>
        </tr>
        <tr>
          <td>Demo: </td>
          <td><?echo $row[0]["demo"];?></td>
        </tr>
        <tr>
          <td>Vehicle Type: </td>
          <td><?echo $row[0]["veh_type"];?></td>
        </tr>
        <tr>
          <td>Immobilizer: </td>
          <td><?echo $row[0]["immobilizer_type"];?></td>
        </tr>
        <tr>
          <td>Comment: </td>
          <td><?echo $row[0]["comment"];?></td>
        </tr>
        <tr>
          <td>Payment: </td>
          <td><?echo $row[0]["payment_req"];?></td>
        <tr>
          <td>Device Type: </td>
          <td><?echo $row[0]["device_type"];?></td>
        </tr>
        <tr>
          <td>Fuel Sensor: </td>
          <td><?echo $row[0]["fuel_sensor"];?></td>
        <tr>
          <td>Bonnet Sensor: </td>
          <td><?echo $row[0]["bonnet_sensor"];?></td>
        <tr>
          <td>RFID Reader: </td>
          <td><?echo $row[0]["rfid_reader"];?></td>
        <tr>
          <td>Speed Alarm: </td>
          <td><?echo $row[0]["speed_alarm"];?></td>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Job: </td>
          <td><?echo $row[0]["instal_reinstall"];?></td>
        </tr>
        <tr>
          <td>Amount: </td>
          <td><?echo $row[0]["amount"];?></td>
        <tr>
          <td>Payment Mode: </td>
          <td><?echo $row[0]["pay_mode"];?></td>
        <tr>
          <td>Required.:</td>
          <td><?echo $row[0]["required"];?></td>
        </tr>
        <tr>
          <td>IP Box.: </td>
          <td><?echo $row[0]["IP_Box"];?></td>
        </tr>
        <tr>
          <td>Door lock/unlock circuit: </td>
          <td><?echo $row[0]["door_lock_unlock"];?></td>
        <tr>
          <td>Temperature Sensor: </td>
          <td><?echo $row[0]["temperature_sensor"];?></td>
        <tr>
          <td>Duty Box: </td>
          <td><?echo $row[0]["duty_box"];?></td>
        <tr>
          <td>Panic Button: </td>
          <td><?echo $row[0]["panic_button"];?></td>
        </tr>
        <tr>
          <td>Contact Person No.: </td>
          <td><?echo $row[0]["contact_person_no"];?></td>
        </tr>
        <tr>
          <td>Installation Made: </td>
          <td><?echo $row[0]["installation_made"];?></td>
        </tr>
        <tr>
          <td>Installer Name: </td>
          <td><?echo $row[0]["inst_name"];?></td>
        </tr>
        <tr>
          <td>Installer Current Location: </td>
          <td><?echo $row[0]["inst_cur_location"];?></td>
        </tr>
        <!--<tr><td>Change Installer Name: </td><td><?echo $row[0]["inst_cur_location"];?></td></tr>
-->
        <tr>
          <td>Installation Done At: </td>
          <td><?echo $row[0]["rtime"];?></td>
        </tr>
        <tr>
          <td>Reason To Back Services:</td>
          <td><?echo $row[0]["back_reason"];?></td>
        </tr>
        <tr>
          <td>Forward to Repair :</td>
          <td><?echo $row[0]["fwd_install_to_repair"];?></td>
        </tr>
        <tr>
          <td>Repair Comment:</td>
          <td><?echo $row[0]["fwd_repair_to_install"];?></td>
        </tr>
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["installation_status"]==7 && ($row[0]["admin_comment"]!="" || $row[0]["sales_comment"]=="")){echo "Reply Pending at Request Side";}
    elseif($row[0]["installation_status"]==7 && $row[0]["admin_comment"]=="" ){echo "Pending Saleslogin for Information";}
    elseif($row[0]["approve_status"]==0 && $row[0]["installation_status"]==8 ){echo "Pending Admin Approval";}
    elseif($row[0]["installation_status"]==9 && $row[0]["approve_status"]==1 ){echo "Pending Confirmation at Request Person";}
    elseif($row[0]["installation_status"]==1 ){echo "Pending Dispatch Team";}
    elseif($row[0]["installation_status"]==2 ){echo "Assign To Installer";}
    elseif($row[0]["installation_status"]==11 ){echo "Request Forward to Repair Team";}
    elseif($row[0]["installation_status"]==3 ){echo "Back Installation";}
    elseif($row[0]["installation_status"]==15 ){echo "Pending Remaining Installation";}
    elseif($row[0]["installation_status"]==5 || $row[0]["installation_status"]==6){echo "Installation Close";}?>
            </strong></td>
        </tr>
        <?php if($_SESSION['BranchId']==1){?>
        <tr>
          <td>Sales Comment</td>
          <td><?echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Approval</td>
          <td><?if($row[0]["approve_status"]==1) echo "Approved";?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
    if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
    {
    echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
    }
    else
    {
        echo "";
    }
  
    ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<? }

    else If($tablename=="services")
        {
          $query = "SELECT * FROM services left join re_city_spr_1 on services.Zone_area =re_city_spr_1.id  where services.id=".$RowId;
            $row=select_query($query);
            
            if($row[0]['fwd_tech_rm_id'] != "" && $row[0]['fwd_repair_id'] == ""){
                $support_query = select_query("select user_name from request_forward_list where user_id='".$row[0]['fwd_tech_rm_id']."'");
                $forward_name = $support_query[0]['user_name'];
            } else if($row[0]['fwd_repair_id'] != "") {
                $support_query = select_query("select user_name from request_forward_list where user_id='".$row[0]['fwd_repair_id']."'");
                $forward_name = $support_query[0]['user_name'];
            } else { $forward_name = '';}
            
    ?>
<div id="databox">
  <div class="heading">Service</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td> Date </td>
          <td><?echo $row[0]["req_date"];?></td>
        </tr>
        <tr>
          <td>Request By </td>
          <td><?echo $row[0]["request_by"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["company_name"];?></td>
        </tr>
        <tr>
          <td>Registration No </td>
          <td><?echo $row[0]["veh_reg"];?></td>
        </tr>
        <tr>
       <!--    <td>Device Type: </td>
          <td><?echo $row[0]["device_model"];?></td>
        </tr>
        <tr>
          <td>Device Model: </td>
          <td><?echo $row[0]["device_model"];?></td>
        </tr> -->
          <tr>
        <?php 
        //$sqlDevice=select_query("SELECT device_type FROM device_type where id='".$row[0]["device_type"]."' ");  
         $sqlDevice=select_query("SELECT item_name FROM item_master where item_id='".$row[0]["device_type"]."'");
          ?>
          <td>Device Type:</td>
          <td><?echo $sqlDevice[0]["item_name"];?></td>
        </tr>
        <tr>
        <?php
        // $sqlModel=select_query("SELECT device_model FROM device_model where id='".$row[0]["model"]."' ");   
        $sqlModel=select_query("select im.* from installation_request ir left join item_master im on ir.model=im.item_id where ir.model='".$row[0]["device_model"]."'");
        ?>
          <td>Model:</td>
          <td><?echo $sqlModel[0]["item_name"];?></td>
        </tr>


        <tr>
          <td>Device IMEI: </td>
          <td><?echo $row[0]["device_imei"];?></td>
        </tr>
        <tr>
          <td>Date Of Installation: </td>
          <td><?echo $row[0]["date_of_installation"];?></td>
        </tr>
        <tr>
          <td>Not working:</td>
          <td><?echo $row[0]["Notwoking"];?></td>
        </tr>
        <tr>
          <td>Area: </td>
          <td><?echo $row[0]["name"];?></td>
        </tr>
        <?php if($row[0]['location']!=""){?>
        <tr>
          <td>Location: </td>
          <td><?echo $row[0]["location"];?></td>
        </tr>
        <?php }else{ $city= mysql_fetch_array(mysql_query("select * from tbl_city_name where branch_id='".$row[0]['inter_branch']."'",$dblink2));?>
        <tr>
          <td>Location: </td>
          <td><?echo $city["city"];?></td>
        </tr>
        <?php }?>
        <tr>
          <td>Available Time Status: </td>
          <td><?echo $row[0]["atime_status"];?></td>
        </tr>
        <tr>
          <td>Available Time: </td>
          <td><?echo $row[0]["atime"];?></td>
        </tr>
        <tr>
          <td>To Available Time: </td>
          <td><?echo $row[0]["atimeto"];?></td>
        </tr>
        <tr>
          <td>Person Name: </td>
          <td><?echo $row[0]["pname"];?></td>
        </tr>
        <tr>
          <td>Contact No: </td>
          <td><?echo $row[0]["cnumber"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Job: </td>
          <td><?echo $row[0]["service_reinstall"];?></td>
        </tr>
        <tr>
          <td>Required: </td>
          <td><?echo $row[0]["required"];?></td>
        </tr>
        <tr>
          <td>IP Box: </td>
          <td><?echo $row[0]["IP_Box"];?></td>
        </tr>
        <tr>
          <td>Comment</td>
          <td><?echo $row[0]["comment"];?></td>
        </tr>
        <tr>
          <td>Reason To Back Services:</td>
          <td><?echo $row[0]["back_reason"];?></td>
        </tr>
        <tr>
          <td>Installer Name: </td>
          <td><?echo $row[0]["inst_name"];?></td>
        </tr>
        <tr>
          <td>Installer Current Location: </td>
          <td><?echo $row[0]["inst_cur_location"];?></td>
        </tr>
        <!--<tr><td>Change Installer Name: </td><td><?echo $row[0]["inst_cur_location"];?></td></tr>
-->
        <tr>
          <td>Problem:</td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td>Problem Due to:</td>
          <td><?echo $row[0]["problem_in_service"];?></td>
        </tr>
        <tr>
          <td>Reason:</td>
          <td><?echo $row[0]["problem"];?></td>
        </tr>
        <tr>
          <td>Antenna Billing:</td>
          <td><?echo $row[0]["ant_billing_amt"];?></td>
        </tr>
        <tr>
          <td>Billing Reason:</td>
          <td><?echo $row[0]["ant_billing_reason"];?></td>
        </tr>
        <!--<tr><td>Forward to Repair :</td><td><?echo $row[0]["fwd_serv_to_repair"];?></td></tr>
    <tr><td>Repair Reason:</td><td><?echo $row[0]["fwd_repair_to_serv"];?></td></tr>-->
        
        <tr>
          <td>Forward to <strong>
            <?=$forward_name;?>
            </strong> :</td>
          <td><? if($row[0]['fwd_reason'] != "" && $row[0]['fwd_tech_rm_id'] != "" && $row[0]['fwd_repair_id'] == "") {
            echo $row[0]['fwd_datetime'].' - '.$row[0]['fwd_reason'];
        } else if($row[0]['fwd_serv_to_repair'] != "" && $row[0]['fwd_repair_id'] == "") {
            echo $row[0]['fwd_serv_to_repair'];
        } else if($row[0]['fwd_serv_to_repair'] != "" && $row[0]['fwd_repair_id'] != "") {
            echo $row[0]['fwd_repair_date'].' - '.$row[0]['fwd_serv_to_repair'];
        }
    ?></td>
        </tr>
        <tr>
          <td>Reply Comment:</td>
          <td><? if(($row[0]['fwd_tech_rm_id'] != "" || $row[0]['fwd_repair_id'] == "") && $row[0]["fwd_repair_to_serv"] != ''){
             echo $row[0]['fwd_done_time'].' - '.$row[0]["fwd_repair_to_serv"];
        } else {
            echo $row[0]["fwd_repair_to_serv"];
        }
     ?></td>
        </tr>
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <? if($row[0]["service_status"]==1 ){echo "Pending Dispatch Team";}
                elseif($row[0]["service_status"]==2 ){echo "Assign To Installer";}
                elseif($row[0]["service_status"]==11 ){echo "Request Forward to ".$forward_name;}
                elseif($row[0]["service_status"]==3 ){echo "Back Installation";}
                elseif($row[0]["service_status"]==5 || $row[0]["service_status"]==6){echo "Service Close";}?>
            </strong></td>
        </tr>
        <tr>
        <td>Service Done At: </td>
          <td><?echo $row[0]["time"];?></td>
        </tr>
    </table>
  </div>
</div>
<? }
  else If($tablename=="services_crack")
  {
           $query = "SELECT * FROM services_crack left join re_city_spr_1 on services_crack.Zone_area =re_city_spr_1.id  where services_crack.id=".$RowId; 
            $row=select_query($query);
            //echo '<pre>'; print_r($query); die;
            
            if($row[0]['fwd_tech_rm_id'] != "" && $row[0]['fwd_repair_id'] == ""){
                $support_query = select_query("select user_name from request_forward_list where user_id='".$row[0]['fwd_tech_rm_id']."'");
                $forward_name = $support_query[0]['user_name'];
            } 
            else if($row[0]['fwd_repair_id'] != "") {
                $support_query = select_query("select user_name from request_forward_list where user_id='".$row[0]['fwd_repair_id']."'");
                $forward_name = $support_query[0]['user_name'];
            }
             else { $forward_name = '';}
            
    ?>
<div id="databox">
  <div class="heading">Service Request</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td> Date </td>
          <td><?echo $row[0]["req_date"];?></td>
        </tr>
        <tr>
          <td>Request By </td>
          <td><?echo $row[0]["request_by"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["company_name"];?></td>
        </tr>
        <tr>
          <td>Registration No </td>
          <td><?echo $row[0]["veh_reg"];?></td>
        </tr>
        <tr>
          <td>Device Model: </td>
          <td><?echo $row[0]["device_model"];?></td>
        </tr>
        <tr>
          <td>Device IMEI: </td>
          <td><?echo $row[0]["device_imei"];?></td>
        </tr>
        <tr>
          <td>Date Of Installation: </td>
          <td><?echo $row[0]["date_of_installation"];?></td>
        </tr>
        <tr>
          <td>Not working:</td>
          <td><?echo $row[0]["Notwoking"];?></td>
        </tr>
        <tr>
          <td>Area: </td>
          <td><?echo $row[0]["name"];?></td>
        </tr>
        <?php if($row[0]['location']!=""){?>
        <tr>
          <td>Location: </td>
          <td><?echo $row[0]["location"];?></td>
        </tr>
        <?php }else{ $city= mysql_fetch_array(mysql_query("select * from tbl_city_name where branch_id='".$row[0]['inter_branch']."'",$dblink2));?>
        <tr>
          <td>Location: </td>
          <td><?echo $city["city"];?></td>
        </tr>
        <?php }?>
        <tr>
          <td>Available Time Status: </td>
          <td><?echo $row[0]["atime_status"];?></td>
        </tr>
        <tr>
          <td>Available Time: </td>
          <td><?echo $row[0]["atime"];?></td>
        </tr>
        <tr>
          <td>To Available Time: </td>
          <td><?echo $row[0]["atimeto"];?></td>
        </tr>
        <tr>
          <td>Person Name: </td>
          <td><?echo $row[0]["pname"];?></td>
        </tr>
        <tr>
          <td>Contact No: </td>
          <td><?echo $row[0]["cnumber"];?></td>
        </tr>
        <tr>
          <td>Designation: </td>
          <td><?echo $row[0]["designation"];?></td>
        </tr>
        <tr>
          <td>Alternative Contact</td>
          <td><?echo $row[0]["alt_designation"];?></td>
        </tr>
        <tr>
          <td>AlternativePerson: </td>
          <td><?echo $row[0]["alt_cont_person"];?></td>
        </tr>
        <tr>
          <td>Alternative Designation: </td>
          <td><?echo $row[0]["alter_contact_no"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Job: </td>
          <td><?echo $row[0]["service_reinstall"];?></td>
        </tr>
        <tr>
          <td>Required: </td>
          <td><?echo $row[0]["required"];?></td>
        </tr>
        <tr>
          <td>IP Box: </td>
          <td><?echo $row[0]["IP_Box"];?></td>
        </tr>
        <tr>
          <td>Comment</td>
          <td><?echo $row[0]["comment"];?></td>
        </tr>
        <tr>
          <td>Reason To Back Services:</td>
          <td><?echo $row[0]["back_reason"];?></td>
        </tr>
        <tr>
          <td>Installer Name: </td>
          <td><?echo $row[0]["inst_name"];?></td>
        </tr>
        <tr>
          <td>Installer Current Location: </td>
          <td><?echo $row[0]["inst_cur_location"];?></td>
        </tr>
        <!--<tr><td>Change Installer Name: </td><td><?echo $row[0]["inst_cur_location"];?></td></tr>
-->
        <tr>
          <td>Problem:</td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td>Problem Due to:</td>
          <td><?echo $row[0]["problem_in_service"];?></td>
        </tr>
        <tr>
          <td>Reason:</td>
          <td><?echo $row[0]["problem"];?></td>
        </tr>
        <tr>
          <td>Antenna Billing:</td>
          <td><?echo $row[0]["ant_billing_amt"];?></td>
        </tr>
        <tr>
          <td>Billing Reason:</td>
          <td><?echo $row[0]["ant_billing_reason"];?></td>
        </tr>
        <!--<tr><td>Forward to Repair :</td><td><?echo $row[0]["fwd_serv_to_repair"];?></td></tr>
    <tr><td>Repair Reason:</td><td><?echo $row[0]["fwd_repair_to_serv"];?></td></tr>-->
        
        <tr>
          <td>Forward to <strong>
            <?=$forward_name;?>
            </strong> :</td>
          <td><? if($row[0]['fwd_reason'] != "" && $row[0]['fwd_tech_rm_id'] != "" && $row[0]['fwd_repair_id'] == "") {
            echo $row[0]['fwd_datetime'].' - '.$row[0]['fwd_reason'];
        } else if($row[0]['fwd_serv_to_repair'] != "" && $row[0]['fwd_repair_id'] == "") {
            echo $row[0]['fwd_serv_to_repair'];
        } else if($row[0]['fwd_serv_to_repair'] != "" && $row[0]['fwd_repair_id'] != "") {
            echo $row[0]['fwd_repair_date'].' - '.$row[0]['fwd_serv_to_repair'];
        }
    ?></td>
        </tr>
        <tr>
          <td>Reply Comment:</td>
          <td><? if(($row[0]['fwd_tech_rm_id'] != "" || $row[0]['fwd_repair_id'] == "") && $row[0]["fwd_repair_to_serv"] != ''){
             echo $row[0]['fwd_done_time'].' - '.$row[0]["fwd_repair_to_serv"];
        } else {
            echo $row[0]["fwd_repair_to_serv"];
        }
     ?></td>
        </tr>
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <? if($row[0]["service_status"]==1 ){echo "Pending Dispatch Team";}
                elseif($row[0]["service_status"]==2 ){echo "Assign To Installer";}
                elseif($row[0]["service_status"]==11 ){echo "Request Forward to ".$forward_name;}
                elseif($row[0]["service_status"]==3 ){echo "Back Crack Service";}
                elseif($row[0]["service_status"]==5 || $row[0]["service_status"]==6){echo "Service Close";}?>
            </strong></td>
        </tr>
        <tr>
        <td>Service Done At: </td>
          <td><?echo $row[0]["time"];?></td>
        </tr>
    </table>
  </div>
</div>

    <? }
else If($tablename=="services_history_tbl")
        {
          $query = "SELECT * FROM services_history_tbl left join re_city_spr_1 on services_history_tbl.Zone_area =re_city_spr_1.id  where services_history_tbl.id=".$RowId;
          $row=select_query($query);
          
            if($row[0]['fwd_tech_rm_id'] != "" && $row[0]['fwd_repair_id'] == ""){
                $support_query = select_query("select user_name from request_forward_list where user_id='".$row[0]['fwd_tech_rm_id']."'");
                $forward_name = $support_query[0]['user_name'];
            } else if($row[0]['fwd_repair_id'] != "") {
                $support_query = select_query("select user_name from request_forward_list where user_id='".$row[0]['fwd_repair_id']."'");
                $forward_name = $support_query[0]['user_name'];
            } else { $forward_name = '';}

    ?>
<div id="databox">
  <div class="heading">Back Service</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td> Date </td>
          <td><?echo $row[0]["req_date"];?></td>
        </tr>
        <tr>
          <td>Request By </td>
          <td><?echo $row[0]["request_by"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["company_name"];?></td>
        </tr>
        <tr>
          <td>Registration No </td>
          <td><?echo $row[0]["veh_reg"];?></td>
        </tr>
        <tr>
          <td>Device Model: </td>
          <td><?echo $row[0]["device_model"];?></td>
        </tr>
        <tr>
          <td>Device IMEI: </td>
          <td><?echo $row[0]["device_imei"];?></td>
        </tr>
        <tr>
          <td>Date Of Installation: </td>
          <td><?echo $row[0]["date_of_installation"];?></td>
        </tr>
        <tr>
          <td>Not working:</td>
          <td><?echo $row[0]["Notwoking"];?></td>
        </tr>
        <tr>
          <td>Area: </td>
          <td><?echo $row[0]["name"];?></td>
        </tr>
        <?php if($row[0]['location']!=""){?>
        <tr>
          <td>Location: </td>
          <td><?echo $row[0]["location"];?></td>
        </tr>
        <?php }else{ $city= select_query("select * from tbl_city_name where branch_id='".$row[0]['inter_branch']."'");?>
        <tr>
          <td>Location: </td>
          <td><?echo $city[0]["city"];?></td>
        </tr>
        <?php }?>
        <tr>
          <td>Available Time Status: </td>
          <td><?echo $row[0]["atime_status"];?></td>
        </tr>
        <tr>
          <td>Available Time: </td>
          <td><?echo $row[0]["atime"];?></td>
        </tr>
        <tr>
          <td>To Available Time: </td>
          <td><?echo $row[0]["atimeto"];?></td>
        </tr>
        <tr>
          <td>Person Name: </td>
          <td><?echo $row[0]["pname"];?></td>
        </tr>
        <tr>
          <td>Contact No: </td>
          <td><?echo $row[0]["cnumber"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Job: </td>
          <td><?echo $row[0]["service_reinstall"];?></td>
        </tr>
        <tr>
          <td>Required: </td>
          <td><?echo $row[0]["required"];?></td>
        </tr>
        <tr>
          <td>IP Box: </td>
          <td><?echo $row[0]["IP_Box"];?></td>
        </tr>
        <tr>
          <td>Comment</td>
          <td><?echo $row[0]["comment"];?></td>
        </tr>
        <tr>
          <td>Reason To Back Services:</td>
          <td><?echo $row[0]["back_reason"];?></td>
        </tr>
        <tr>
          <td>Installer Name: </td>
          <td><?echo $row[0]["inst_name"];?></td>
        </tr>
        <tr>
          <td>Installer Current Location: </td>
          <td><?echo $row[0]["inst_cur_location"];?></td>
        </tr>
        <!--<tr><td>Change Installer Name: </td><td><?echo $row[0]["inst_cur_location"];?></td></tr>
-->
        <tr>
          <td>Problem:</td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td>Problem Due to:</td>
          <td><?echo $row[0]["problem_in_service"];?></td>
        </tr>
        <tr>
          <td>Reason:</td>
          <td><?echo $row[0]["problem"];?></td>
        </tr>
        <tr>
          <td>Antenna Billing:</td>
          <td><?echo $row[0]["ant_billing_amt"];?></td>
        </tr>
        <tr>
          <td>Billing Reason:</td>
          <td><?echo $row[0]["ant_billing_reason"];?></td>
        </tr>
        <tr>
          <td>Forward to <strong>
            <?=$forward_name;?>
            </strong> :</td>
          <td><? if($row[0]['fwd_reason'] != "" && $row[0]['fwd_tech_rm_id'] != "" && $row[0]['fwd_repair_id'] == "") {
            echo $row[0]['fwd_datetime'].' - '.$row[0]['fwd_reason'];
        } else if($row[0]['fwd_serv_to_repair'] != "" && $row[0]['fwd_repair_id'] == "") {
            echo $row[0]['fwd_serv_to_repair'];
        } else if($row[0]['fwd_serv_to_repair'] != "" && $row[0]['fwd_repair_id'] != "") {
            echo $row[0]['fwd_repair_date'].' - '.$row[0]['fwd_serv_to_repair'];
        }
    ?></td>
        </tr>
        <tr>
          <td>Reply Comment:</td>
          <td><? if(($row[0]['fwd_tech_rm_id'] != "" || $row[0]['fwd_repair_id'] == "") && $row[0]["fwd_repair_to_serv"] != ''){
             echo $row[0]['fwd_done_time'].' - '.$row[0]["fwd_repair_to_serv"];
        } else {
            echo $row[0]["fwd_repair_to_serv"];
        }
     ?></td>
        </tr>
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <? if($row[0]["service_status"]==1 ){echo "Pending Dispatch Team";}
                elseif($row[0]["service_status"]==2 ){echo "Assign To Installer";}
                elseif($row[0]["service_status"]==11 ){echo "Request Forward to ".$forward_name;}
                elseif($row[0]["service_status"]==3 ){echo "Back Installation";}
                elseif($row[0]["service_status"]==5 || $row[0]["service_status"]==6){echo "Service Close";}?>
            </strong></td>
        </tr>
         <tr>
          <td>Service Done At: </td>
          <td><?echo $row[0]["time"];?></td>
        </tr>
    </table>
  </div>
</div>
<? }
   
        else if($tablename=="comment")
        {
        //"select * from comment where service_id='".$service_id."' order by date desc"
        
    ?>
<div >
  <div style=" padding-left: 50px;">
    <h1>Comment</h1>
  </div>
  <div class="table">
    <table cellspacing="2" cellpadding="2" style=" padding-left: 100px;width: 500px;">
      <tr>
        <td><?
$offset = ($_GET['page'] - 1) * 10;
$queryString = "select * from matrix.comment where service_id='".$RowId."' order by date desc limit 10 OFFSET ".$offset.";";
// echo $queryString;
$data=select_query_live_con($queryString);
$allRows=select_query_live_con("select * from matrix.comment where service_id='".$RowId."' order by date desc");

if(count($data)>0)
{
echo '<table cellspacing="0" cellpadding="0" border="1" width="100%" >
    
        <tr  ><th>Date</th><th>Comment By</th><th>Comment</th></tr>';
for($c=0;$c<count($data);$c++)
    {

 echo '<tr ><td>'. $data[$c]["date"]. '</td><td>'. $data[$c]["comment_by"]. '</td><td>'. $data[$c]["comment"]. '</td></tr>';
    /*echo '<div>'. $data[$c]["date"]. '<div>';
    echo '<br/>';
    echo '<div>Comment By--'. $data[$c]["comment_by"]. '<div>';
    echo '<br/>';
    echo '<div>'. $data[$c]["comment"]. '<div>';
    //echo '<div align="right"><a href="?d=true&id='.$data[$c]["id"].'" >Remove </a></div>';

    echo '<hr>&nbsp;</hr>';*/
    }
    echo '</table>';

 }
 else
    {
     echo '<div> No Comments<div>';

    echo '<hr>&nbsp;</hr>';
    }
 ?></td>
      </tr>
    </table>
<?php
    $totalRows = count($allRows);
    $rowsPerPage = 10;
    $totalPages = $totalRows / $rowsPerPage;
    $pageCounter = 1;
    $allPaginationLink = '';

    // $last = ceil($totalPages);
    // //echo "###";
    // $start      = ( ( $pageCounter -  $rowsPerPage ) > 0 ) ? $pageCounter -  $rowsPerPage : 1;
    // // echo "###";
    // $end        = ( ( $pageCounter +  $rowsPerPage ) < $last ) ? $pageCounter +  $rowsPerPage : $last;

    // $html       = '<ul class="' . $list_class . '">';
 
    // $class      = ($rowsPerPage == 1 ) ? "disabled" : "";
    // $html       .= '<li class="' . $class . '"><a href="?limit=' .$rowsPerPage  . '&page=' . ( $rowsPerPage - 1 ) . '">&laquo;</a></li>';
 
    // if ( $start > 1 ) {
    //     $html   .= '<li><a href="?limit=' . $rowsPerPage  . '&page=1">1</a></li>';
    //     $html   .= '<li class="disabled"><span>...</span></li>';
    // }
 
    // for ( $i = $start ; $i <= $end; $i++ ) {
    //     $class  = ( $rowsPerPage == $i ) ? "active" : "";
    //     $html   .= '<li class="' . $class . '"><a href="?limit=' . $rowsPerPage  . '&page=' . $i . '">' . $i . '</a></li>';
    // }
 
    // if ( $end < $last ) {
    //     $html   .= '<li class="disabled"><span>...</span></li>';
    //     $html   .= '<li><a href="?limit=' . $rowsPerPage  . '&page=' . $last . '">' . $last . '</a></li>';
    // }
 
    // $class      = ( $rowsPerPage == $last ) ? "disabled" : "";
    // $html       .= '<li class="' . $class . '"><a href="?limit=' . $rowsPerPage  . '&page=' . ( $rowsPerPage + 1 ) . '">&raquo;</a></li>';
 
    // $html       .= '</ul>';

    // echo $html;

    if(ceil($totalPages) > 1){
      $current = $_GET['page'] == $pageCounter ? 'style="text-decoration: underline;"' : '';
      if($pageCounter == 1){
          $allPaginationLink .= '<a href="javascript:void(0)" '.$current.' onclick="Show_record_pagination(\'userInfo.php?action=getrowsValue&page='.$pageCounter.'&RowId='.$RowId.'&tablename='.$tablename.'\')">&laquo;</a>&emsp;';
        }
   
    while($pageCounter <= $totalPages) { 

        $current = $_GET['page'] == $pageCounter ? 'style="text-decoration: underline;"' : '';
          if($pageCounter == 1){}else{        
          $allPaginationLink .= '<a href="javascript:void(0)" '.$current.' onclick="Show_record_pagination(\'userInfo.php?action=getrowsValue&page='.$pageCounter.'&RowId='.$RowId.'&tablename='.$tablename.'\')">'.$pageCounter.'</a> &emsp;';
          } 
          $pageCounter++;
    }
     $current = $_GET['page'] == $totalPages ? 'style="text-decoration: underline;"' : '';      
       $allPaginationLink .= '<a href="javascript:void(0)" '.$current.' onclick="Show_record_pagination(\'userInfo.php?action=getrowsValue&page='.$totalPages.'&RowId='.$RowId.'&tablename='.$tablename.'\')">&raquo;</a> &emsp;';
     
       
    echo $allPaginationLink;
  }
?>

  </div>
</div>
<? }
   
   
    else If($tablename=="Installer_Stock")
        {
       
    ?>
<div >
  <div style=" padding-left: 50px;">
    <h1>Stock In Bag</h1>
  </div>
  <div class="table">
    <table cellspacing="2" cellpadding="2" style=" padding-left: 100px;width: 500px;">
      <tr>
        <td><?

$stock_in_beg = select_query_inventory("select device.device_imei, item_name,is_ffc,is_repaired from inventory.device left join inventory.item_master on item_master.item_id=device.device_type left join inventory.ChallanDetail on ChallanDetail.deviceid=device.device_id where device.device_status in (63,64,96) and recd_date >='2014-01-01 00:00:00' and recd_date <='".date("Y-m-d")." 23:59:59' and currentrecord=1  and InstallerID='".$RowId."'");
    
/*$stock_in_beg = mssql_query("select device.device_imei, item_name,is_ffc,is_repaired from device left join item_master on item_master.item_id=device.device_type left join ChallanDetail on ChallanDetail.deviceid=device.device_id where device.device_status in (63,64,96) and recd_date >='2014-01-01 00:00:00' and recd_date <='".date("Y-m-d")." 23:59:59' and currentrecord=1  and InstallerID='".$RowId."'");
 
while( $stock_data = mssql_fetch_array($stock_in_beg))
 {
     $data[] = $stock_data;
 }*/
 
if(count($stock_in_beg)>0)
{
    echo '<table cellspacing="0" cellpadding="0" border="1" width="100%" >
         
            <tr><th>IMEI</th><th>Model</th><th>Device Type</th></tr>';
    for($d=0;$d<count($stock_in_beg);$d++)
    {
            $ffc_device = $stock_in_beg[$d]["is_ffc"];
            $repaired_device = $stock_in_beg[$d]["is_repaired"];
           
            if($ffc_device==1 && $repaired_device==0){$device_status = "FFC Device";}
            else if($repaired_device==1 && $ffc_device==0){$device_status = "Repaired Device";}
            else if($ffc_device==1 && $repaired_device==1){$device_status = "FFC/Repaired Device";}
            else {$device_status = "New Device";}
           
         echo '<tr ><td>'.$stock_in_beg[$d]["device_imei"]. '</td><td>'.$stock_in_beg[$d]["item_name"].'</td><td>'.$device_status.'</td></tr>';
    }
    echo '</table>';

 }
 else
    {
     echo '<div> Empty Bag<div>';

    echo '<hr>&nbsp;</hr>';
    }
 ?></td>
      </tr>
    </table>
  </div>
</div>
<? }
    
    else If($tablename == 'Showvehicleonmap')
    {
        
        $latitude = $_GET["latitude"];
        $longitude = $_GET["longitude"];
        
    ?>
    
    
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=AIzaSyDdKcx6Qx6Dr4vtlkltAdyhFvNySaq8dXY" type="text/javascript"></script>

<!--<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDdKcx6Qx6Dr4vtlkltAdyhFvNySaq8dXY&language=de"></script>-->

<script type="text/javascript">

function Vehicle_Map(action,latitude,longitude)
{
    //alert(longitude);
    if (GBrowserIsCompatible()) {

      function createMarker(point,html) {
        var marker = new GMarker(point);
        GEvent.addListener(marker, "click", function() {
//          marker.openInfoWindowHtml(html);
        });
        return marker;
      }

      // Display the map, with some controls and set the initial location
      var map = new GMap2(document.getElementById("map"));
      map.addControl(new GLargeMapControl());
      map.addControl(new GMapTypeControl());
      map.setCenter(new GLatLng(latitude,longitude),16);

      // Set up three markers with info windows

      var point = new GLatLng(latitude,longitude);
      var marker = createMarker(point,'')
      map.addOverlay(marker);


    }

    // display a warning if the browser was not compatible
    else {
      alert("Sorry, the Google Maps API is not compatible with this browser");
    }


}

Vehicle_Map('Showvehicleonmap',<?=$latitude?>,'<?=$longitude?>');

</script>

<div>
  <div style=" padding-left: 50px;">
    <h1>View Map</h1>
  </div>
  
  <div class="table">
    <div class="row" id="innermap">
        <div id="map" style="height:200px;width:300px;"></div>

      </div>
  </div>
</div>
<?

}
else If($tablename=="deletion")
        {
        $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);



    ?>
<div id="databox">
  <div class="heading">Deletion Vehicle</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>date</td>
          <td><?echo $row[0]["date"];?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?echo $row[0]["sales_manager"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["client"];?></td>
        </tr>
        <tr>
          <td>Registration No </td>
          <td><?echo $row[0]["reg_no"];?></td>
        </tr>
        <tr>
          <td>Device Model </td>
          <td><?echo $row[0]["device_model"];?></td>
        </tr>
        <tr>
          <td>Device IMEI </td>
          <td><?echo $row[0]["imei"];?></td>
        </tr>
        <tr>
          <td>Device Mobile Number </td>
          <td><?echo $row[0]["device_sim_no"];?></td>
        </tr>
        <tr>
          <td>Date Of Installation </td>
          <td><?echo $row[0]["date_of_installation"];?></td>
        </tr>
        <tr>
          <td>Present Status of device</td>
          <td>----------------------</td>
        </tr>
        <tr>
          <td>Device Status</td>
          <td><?echo $row[0]["device_status"];?></td>
        </tr>
        <tr>
          <td>Device Location </td>
          <td><?echo $row[0]["vehicle_location"];?></td>
        </tr>
        <tr>
          <td>Contact person </td>
          <td><?echo $row[0]["Contact_person"];?></td>
        </tr>
        <tr>
          <td>Deactivation of SIM </td>
          <td><?echo $row[0]["deactivation_of_sim"];?></td>
        </tr>
        <tr>
          <td>Deletion date </td>
          <td><?echo $row[0]["deletion_date"];?></td>
        </tr>
        <tr>
          <td>Reason</td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
        <!--<tr><td colspan="2">-------------------------------------------</td> </tr>-->
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["delete_veh_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["service_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["vehicle_location"]=="gtrack office" && $row[0]["stock_comment"]==""){echo "Pending at Stock";}
    elseif($row[0]["account_comment"]=="" && $row[0]["odd_paid_unpaid"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["odd_paid_unpaid"]!="") && $row[0]["final_status"]==0 && $row[0]["delete_veh_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["delete_veh_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Old Device Paid or Not</td>
          <td><?echo $row[0]["odd_paid_unpaid"];?></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Stock Comment</td>
          <td><?echo $row[0]["stock_comment"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<? }
 else If($tablename=="vehicle_no_change")
        {
         $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

 
 
    ?>
<div id="databox">
  <div class="heading">View Vehicle Change</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?echo $row[0]["date"];?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager </td>
          <td><?echo $row[0]["sales_manager"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["client"];?></td>
        </tr>
        <tr>
          <td>Registration No</td>
          <td><?echo $row[0]["old_reg_no"];?></td>
        </tr>
        <tr>
          <td>New Registration No </td>
          <td><?echo $row[0]["new_reg_no"];?></td>
        </tr>
        <tr>
          <td>Billing</td>
          <td><?echo $row[0]["billing"];?></td>
        </tr>
        <tr>
          <td>Billing Reason</td>
          <td><?echo $row[0]["billing_reason"];?></td>
        </tr>
        <tr>
          <td>Date </td>
          <td><?echo $row[0]["numberchange_date"];?></td>
        </tr>
        <tr>
          <td>Vehicle No Change Reason </td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td colspan="2">-------------------------------------------</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["reason"]=='Temperory no to Permanent no' || $row[0]["reason"]=='Personal no to Commercial no' || $row[0]["reason"]=='Commercial no to Personal no' || $row[0]["reason"]=='For Warranty Renuwal Purpose')
    {
        if($row[0]["vehicle_status"]==2 || ($row[0]["support_comment"]!="" && $row[0]["service_comment"]==""))
        {echo "Reply Pending at Request Side";}
        elseif($row[0]["vehicle_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
        elseif($row[0]["final_status"]==1){echo "Process Done";}
    }
    else{
        if($row[0]["vehicle_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["service_comment"]==""))
        {echo "Reply Pending at Request Side";}
        elseif($row[0]["new_reg_no"]=="" && $row[0]["reason"]=="" && $row[0]["approve_status"]==0){echo "Request Not Completely Generate.";}
        elseif($row[0]["account_comment"]=="" && $row[0]["payment_status"]=="" && $row[0]["reason"]!="" && $row[0]["approve_status"]==0){echo "Pending at Accounts";}
        elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["vehicle_status"]==1)   
        {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
        elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["payment_status"]!="") && $row[0]["final_status"]==0 && $row[0]["vehicle_status"]==1)
        {echo "Pending Admin Approval";}
        elseif($row[0]["approve_status"]==1 && $row[0]["vehicle_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
        elseif($row[0]["final_status"]==1){echo "Process Done";}
    } ?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Payment Pending </td>
          <td><?echo $row[0]["payment_status"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
        <?php if($row[0]["close_comment"]!=""){?>
        <tr>
          <td>Duplicate Close Reason</td>
          <td><?echo $row[0]["close_comment"];?></td>
        </tr>
        <?php } ?>
        <tr>
          <td>Closed Date</td>
          <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<? }
    else If($tablename=="sim_change")
        {
        $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

    ?>
<div id="databox">
  <div class="heading">View Mobile Number Change</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?echo $row[0]["date"];?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?echo $row[0]["sales_manager"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["client"];?></td>
        </tr>
        <tr>
          <td>Registration No</td>
          <td><?echo $row[0]["reg_no"];?></td>
        </tr>
        <tr>
          <td>Old Mobile Number </td>
          <td><?echo $row[0]["old_sim"];?></td>
        </tr>
        <tr>
          <td>New Mobile Number </td>
          <td><?echo $row[0]["new_sim"];?></td>
        </tr>
        <tr>
          <td>Sim Change Date </td>
          <td><?echo $row[0]["sim_change_date"];?></td>
        </tr>
        <tr>
          <td>Reason </td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td colspan="2">-------------------------------------------</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Support Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?     if($row[0]["sim_change_status"]==2 || ($row[0]["support_comment"]!="" && $row[0]["service_comment"]==""))
        {echo "Reply Pending at Request Side";}
        elseif($row[0]["sim_change_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
        elseif($row[0]["final_status"]==1){echo "Process Done";}
     ?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}
?></td>
        </tr>
        <?php if($row[0]["close_comment"]!=""){?>
        <tr>
          <td>Duplicate Close Reason</td>
          <td><?echo $row[0]["close_comment"];?></td>
        </tr>
        <?php } ?>
        <tr>
          <td>Closed Date</td>
          <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<? }
    
 elseIf($tablename=="installer")
  {

    $query = "SELECT * FROM ".$tablename." where inst_id=".$RowId;
   $row=select_query($query);

 ?>
<div id="databox">
  <div class="heading">View Installer Contact Info</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Installer Name</td>
          <td><?echo $row[0]["inst_name"];?></td>
        </tr>
        <tr>
          <td>Address</td>
          <td><?echo $row[0]["address"];?></td>
        </tr>
        <tr>
          <td>Specialist</td>
          <td><?echo $row[0]["specialist"];?></td>
        </tr>
        <tr>
          <td>Tool Kit</td>
          <td><?echo $row[0]["toolkit"];?></td>
        </tr>
        <tr>
          <td>Work Status</td>
          <td><?echo $row[0]["work_status"];?></td>
        </tr>
        <tr>
          <td>Mobile No. </td>
          <td><?echo $row[0]["installer_mobile"];?></td>
        </tr>
        <? $sql="select * from gtrac_branch where id=".$row[0]["branch_id"];
 $rowuser=select_query($sql);
 ?>
        <tr>
          <td>Branch Name</td>
          <td><?echo $rowuser[0]["branch_name"];?></td>
        </tr>
        <tr>
          <td>Status</td>
          <td><?echo $row[0]["status"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<? }
elseIf($tablename=="new_account_creation")
        {
    $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
    ?>
<div id="databox">
  <div class="heading">New account creation</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td><strong>Date</strong></td>
          <td><?echo $row[0]["date"];?></td>
        </tr>
        <? /* if($row[0]["account_manager"]=='saleslogin') {
$account_manager=$row[0]["sales_manager"];
}
else {
$account_manager=$row[0]["account_manager"];
}*/

?>
        <tr>
          <td>Request By</td>
          <td><?echo $row[0]["account_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?echo $row[0]["sales_manager"];?></td>
        </tr>
        <tr>
          <td><strong>Company</strong></td>
          <td><?echo $row[0]["company"];?></td>
        </tr>
        <tr>
          <td><strong>Potential</strong></td>
          <td><?echo $row[0]["potential"];?></td>
        </tr>
        <tr>
          <td><strong>Contact Person</strong></td>
          <td><?echo $row[0]["contact_person"];?></td>
        </tr>
        <tr>
          <td><strong>Contact Number</strong></td>
          <td><?echo $row[0]["contact_number"];?></td>
        </tr>
        <tr>
          <td><strong>Billing Name</strong></td>
          <td><?echo $row[0]["billing_name"];?></td>
        </tr>
        <tr>
          <td><strong>Billing Address</strong></td>
          <td><?echo $row[0]["billing_address"];?></td>
        </tr>
        <tr>
          <td><strong>Type of Organisation</strong></td>
          <td><?echo $row[0]["type_of_org"];?></td>
        </tr>
        <tr>
          <td><strong>PAN No.</strong></td>
          <td><?echo $row[0]["pan_no"];?></td>
        </tr>
        <tr>
          <td><strong> Copy of Pan Card</strong></td>
          <td><?echo $row[0]["pan_card"];?></td>
        </tr>
        <tr>
          <td><strong>Copy of Address Proof</strong></td>
          <td><?echo $row[0]["address_proof"];?></td>
        </tr>
        <tr>
          <td><strong>mode of payment</strong></td>
          <td><?echo $row[0]["mode_of_payment"];?></td>
        </tr>
        <tr>
          <td><strong>Demo Period</strong></td>
          <td><?echo $row[0]["demo_time"];?></td>
        </tr>
        <tr>
          <td><strong>Device Price</strong></td>
          <td><?echo $row[0]["device_price"];?></td>
        </tr>
        <tr>
          <td><strong>Vat(5%)</strong></td>
          <td><?echo $row[0]["device_price_vat"];?></td>
        </tr>
        <tr>
          <td><strong>Total </strong></td>
          <td><?echo $row[0]["device_price_total"];?></td>
        </tr>
        <tr>
          <td><strong>Rent </strong></td>
          <td><?echo $row[0]["device_rent_Price"];?></td>
        </tr>
        <tr>
          <td><strong>Service Tex(12.36%)</strong></td>
          <td><?echo $row[0]["device_rent_service_tax"];?></td>
        </tr>
        <tr>
          <td><strong>Total Rent</strong></td>
          <td><?echo $row[0]["DTotalREnt"];?></td>
        </tr>
        <tr>
          <td><strong>Dimts Fee status </strong></td>
          <td><?echo $row[0]["dimts_fee"];?></td>
        </tr>
        <tr>
          <td><strong>Rent Status</strong></td>
          <td><?echo $row[0]["rent_status"];?></td>
        </tr>
        <tr>
          <td><strong>Rent Month</strong></td>
          <td><?echo $row[0]["rent_month"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Dimts</td>
          <td><?echo $row[0]["dimts"];?></td>
        </tr>
        <tr>
          <td><strong>Vehicle type</strong></td>
          <td><?echo $row[0]["vehicle_type"];?></td>
        </tr>
        <tr>
          <td><strong>Immobilizer (Y/N)</strong></td>
          <td><?echo $row[0]["immobilizer"];?></td>
        </tr>
        <tr>
          <td><strong>AC (ON/OFF)</strong></td>
          <td><?echo $row[0]["ac_on_off"];?></td>
        </tr>
        <tr>
          <td><strong>E_mail ID</strong></td>
          <td><?echo $row[0]["email_id"];?></td>
        </tr>
        <tr>
          <td><strong>User Name</strong></td>
          <td><?echo $row[0]["user_name"];?></td>
        </tr>
        <tr>
          <td><strong>Password</strong></td>
          <td><?echo $row[0]["user_password"];?></td>
        </tr>
        <tr>
          <td colspan="2">-------------------------------------------</td>
        </tr>
        
        <!-- <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["acc_creation_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["approve_status"]==0 && $row[0]["final_status"]==0 && $row[0]["acc_creation_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["acc_creation_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td><strong>Account Comment</strong></td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td><strong>Sales Comment</strong></td>
          <td><?echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td><strong>Support Comment</strong></td>
          <td><?echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td><strong>Admin Comment</strong></td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td><strong>Req Forwarded to</strong></td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td><strong>Forward Comment</strong></td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td><strong>F/W Request Back Comment</strong></td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td><strong>Approval Date</strong></td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
        <tr>
          <td><strong>Closed Date</strong></td>
          <td><?

if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<? }

    elseIf($tablename=="new_device_addition")
        {

          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

    ?>
<div id="databox">
  <div class="heading">View New device Addition</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tr>
        <td>Date</td>
        <td><?echo $row[0]["date"];?></td>
      </tr>
      <tr>
        <td>Request By</td>
        <td><?echo $row[0]["acc_manager"];?></td>
      </tr>
      <tr>
        <td>Account Manager</td>
        <td><?echo $row[0]["sales_manager"];?></td>
      </tr>
      <tr>
        <td>Company</td>
        <td><?echo $row[0]["client"];?></td>
      </tr>
      <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
      <tr>
        <td>Client User Name </td>
        <td><?echo $rowuser[0]["sys_username"];?></td>
      </tr>
      <tr>
        <td>Vehicle Name</td>
        <td><?echo $row[0]["vehicle_no"];?></td>
      </tr>
      <tr>
        <td>Device Type </td>
        <td><?echo $row[0]["device_type"];?></td>
      </tr>
      <tr>
        <td>OLD Company Name </td>
        <td><?echo $row[0]["old_device_client"];?></td>
      </tr>
      <tr>
        <td>OLD Registration No </td>
        <td><?echo $row[0]["old_vehicle_name"];?></td>
      </tr>
      <tr>
        <td>Device Model </td>
        <td><?echo $row[0]["device_model"];?></td>
      </tr>
      <tr>
        <td>Device IMEI </td>
        <td><?echo $row[0]["device_imei"];?></td>
      </tr>
      <?
if($row[0]["device_type"]=='New') {
$Deviceid=$row[0]["device_id"];
}
else if($row[0]["device_type"]=='Old') {
$Deviceid=$row[0]["old_device_id"];
}
?>
      <tr>
        <td>Device ID </td>
        <td><?echo $Deviceid;?></td>
      </tr>
      <tr>
        <td>Device Mobile Number </td>
        <td><?echo $row[0]["device_sim_num"];?></td>
      </tr>
      <tr>
        <td>OLD Date Of Installation </td>
        <td><?echo $row[0]["olddate_of_installation"];?></td>
      </tr>
      <? if($row[0]["device_type"]=='New'){
$biliing_status=$row[0]["billing"];
}
else{
$biliing_status=$row[0]["billing_if_old_device"];
}
?>
      <tr>
        <td>Billing</td>
        <td><?echo $biliing_status;?></td>
      </tr>
      <tr>
        <td>Billing Reason</td>
        <td><?echo $row[0]["billing_if_no_reason"];?></td>
      </tr>
      <tr>
        <td>Installer</td>
        <td><?echo $row[0]["inst_name"];?></td>
      </tr>
      <tr>
        <td>Dimts</td>
        <td><?echo $row[0]["dimts"];?></td>
      </tr>
      <tr>
        <td>Immobilizer </td>
        <td><?echo $row[0]["immobilizer"];?></td>
      </tr>
      <tr>
        <td>AC </td>
        <td><?echo $row[0]["ac"];?></td>
      </tr>
      <tr>
        <td>Date Of Installation </td>
        <td><?echo $row[0]["date_of_installation"];?></td>
      </tr>
      <tr>
        <td>Reason</td>
        <td><?echo $row[0]["reason"];?></td>
      </tr>
      <tr>
        <td colspan="2">-------------------------------------------</td>
      </tr>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Account Comment</td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td><strong>Process Pending</strong></td>
          <td><strong>
            <?  if($row[0]["new_device_status"]==2 || ($row[0]["support_comment"]!="" && $row[0]["service_comment"]=="")){echo "Reply Pending at Request Side";}
    elseif($row[0]["new_device_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
    </table>
  </div>
</div>
<? }
elseif($tablename=="device_change")
        {
    $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
    ?>
<div id="databox">
  <div class="heading"> View Device Change</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tr>
        <td>Date</td>
        <td><?echo $row[0]["date"];?></td>
      </tr>
      <tr>
        <td>Request By</td>
        <td><?echo $row[0]["acc_manager"];?></td>
      </tr>
      <tr>
        <td>Account Manager</td>
        <td><?echo $row[0]["sales_manager"];?></td>
      </tr>
      <tr>
        <td>Company</td>
        <td><?echo $row[0]["client"];?></td>
      </tr>
      <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
      <tr>
        <td>Client User Name </td>
        <td><?echo $rowuser[0]["sys_username"];?></td>
      </tr>
      <tr>
        <td>Device Model</td>
        <td><?echo $row[0]["device_model"];?></td>
      </tr>
      <tr>
        <td>Device IMEI</td>
        <td><?echo $row[0]["device_imei"];?></td>
      </tr>
      <tr>
        <td>Veh Num</td>
        <td><?echo $row[0]["reg_no"];?></td>
      </tr>
      <tr>
        <td>Mobile Number</td>
        <td><?echo $row[0]["mobile_no"];?></td>
      </tr>
      <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["rdd_username"];
    $rowuser_old=select_query($sql);
    ?>
      <tr>
        <td><strong>Replaced Device Details</strong></td>
        <td>---------------------------</td>
      </tr>
      <tr>
        <td>Client User</td>
        <td><?echo $rowuser_old[0]["sys_username"];?></td>
      </tr>
      <tr>
        <td>Client Name</td>
        <td><?echo $row[0]["rdd_companyname"];?></td>
      </tr>
      <tr>
        <td>Device Type</td>
        <td><?echo $row[0]["rdd_device_type"];?></td>
      </tr>
      <tr>
        <td>Device Model</td>
        <td><?echo $row[0]["rdd_device_model"];?></td>
      </tr>
      <tr>
        <td>Vehicle No</td>
        <td><?echo $row[0]["rdd_reg_no"];?></td>
      </tr>
      <tr>
        <td>IMEI</td>
        <td><?echo $row[0]["rdd_device_imei"];?></td>
      </tr>
      <tr>
        <td>Device ID</td>
        <td><?echo $row[0]["rdd_device_id"];?></td>
      </tr>
      <tr>
        <td>Mobile Number</td>
        <td><?echo $row[0]["rdd_device_mobile_num"];?></td>
      </tr>
      <tr>
        <td>Date of installation </td>
        <td><?echo $row[0]["rdd_date_replace"];?></td>
      </tr>
      <tr>
        <td>Replace Date</td>
        <td><?echo $row[0]["rdd_date"];?></td>
      </tr>
      <tr>
        <td>Billing</td>
        <td><?echo $row[0]["billing"];?></td>
      </tr>
      <tr>
        <td>Billing Reason</td>
        <td><?echo $row[0]["billing_reason"];?></td>
      </tr>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tr>
        <td><strong>Process Pending </strong></td>
        <td><strong>
          <?  if(($row[0]["device_change_status"]==2 && $row[0]["rdd_device_type"]!="New") || (($row[0]["support_comment"]!="" || ($row[0]["admin_comment"]!="" && $row[0]["rdd_device_type"]!="New")) && $row[0]["service_comment"]=="")){echo "Reply Pending at Request Side";}            
    elseif($row[0]["rdd_device_imei"]=="" && $row[0]["rdd_reason"]=="" && $row[0]["approve_status"]==0){echo "Request Not Completely Generate.";}
    elseif($row[0]["account_comment"]=="" && $row[0]["pay_status"]=="" && $row[0]["rdd_reason"]!="" && $row[0]["approve_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["rdd_device_type"]=="New" && ($row[0]["service_support_com"]=='' || $row[0]["device_change_status"]==2) && $row[0]["approve_status"]==0){echo "Pending at Delhi Service Support Login";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["device_change_status"]==1)    
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["pay_status"]!="") && $row[0]["final_status"]==0 && $row[0]["device_change_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["device_change_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
          </strong></td>
      </tr>
      <tr>
        <td>Reason</td>
        <td><?echo $row[0]["rdd_reason"];?></td>
      </tr>
      <!-- <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
      <tr>
        <td>Account Comment</td>
        <td><?echo $row[0]["account_comment"];?></td>
      </tr>
      <tr>
        <td>Service Comment</td>
        <td><?echo $row[0]["service_comment"];?></td>
      </tr>
      <tr>
        <td>Support Comment</td>
        <td><?echo $row[0]["support_comment"];?></td>
      </tr>
      <tr>
        <td>Service Support Comment</td>
        <td><?echo $row[0]["service_support_com"];?></td>
      </tr>
      <tr>
        <td>Admin Comment</td>
        <td><?echo $row[0]["admin_comment"];?></td>
      </tr>
      <tr>
        <td>Req Forwarded to</td>
        <td><?echo $row[0]["forward_req_user"];?></td>
      </tr>
      <tr>
        <td>Forward Comment</td>
        <td><?echo $row[0]["forward_comment"];?></td>
      </tr>
      <tr>
        <td>F/W Request Back Comment</td>
        <td><?echo $row[0]["forward_back_comment"];?></td>
      </tr>
      <tr>
        <td>Approval Date</td>
        <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}

?></td>
      </tr>
      <?php if($row[0]["close_comment"]!=""){?>
      <tr>
        <td>Duplicate Close Reason</td>
        <td><?echo $row[0]["close_comment"];?></td>
      </tr>
      <?php } ?>
      <tr>
        <td>Closed Date</td>
        <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
      </tr>
    </table>
  </div>
</div>
<? }
else If($tablename=="no_bills")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
          
    ?>
<div id="databox">
  <div class="heading">No Bills</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?echo $row[0]["sales_manager"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["client"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["company_name"];?></td>
        </tr>
        <!--<tr><td>Vehicle Num</td><td><?echo $row[0]["reg_no"];?></td></tr>-->
        
        <tr>
          <td>Vehicle Num </td>
          <td><?php $vechile_no = explode(",",$row[0]["reg_no"]);
for($i=0;$i<=count($vechile_no);$i++){ if($i%3!=0){ echo $vechile_no[$i].", ";}else { echo "<br/>".$vechile_no[$i].", ";} }?></td>
        </tr>
        <tr>
          <td>No Bill For </td>
          <td><?echo $row[0]["rent_device"];?></td>
        </tr>
        <tr>
          <td>Reason </td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td>Provision Bill </td>
          <td><?echo $row[0]["provision_bill"];?></td>
        </tr>
        <tr>
          <td>Duration for Provision Bill </td>
          <td><?echo $row[0]["duration"];?></td>
        </tr>
        <tr>
          <td>Issue for No Bill</td>
          <td><?echo $row[0]["no_bill_issue"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["no_bill_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}   
    elseif($row[0]["no_bill_issue"]=="Software Issue" && ($row[0]["support_comment"]=="" || $row[0]["no_bill_status"]==1) && $row[0]["software_comment"]=="")
    {echo "Pending at Tech Support Team";}   
    elseif(($row[0]["no_bill_issue"]=="Service Issue" || $row[0]["no_bill_issue"]=="Client Side Issue") && $row[0]["no_bill_status"]==1 && $row[0]["approve_status"]!=1 && $row[0]["service_comment"]=="")    {echo "Pending at Service Team";}   
    elseif($row[0]["no_bill_status"]==1 && $row[0]["account_comment"]=="" && $row[0]["total_pending"]=="" && $row[0]["approve_status"]==0 ){echo "Pending at Accounts";}    
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["no_bill_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}   
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["no_bill_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["final_status"]==0){echo "Pending at Account For No Bill";}   
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?echo $row[0]["total_pending"];?></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Software Comment</td>
          <td><?echo $row[0]["software_comment"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
        <tr>
          <td>Closed By</td>
          <td><?echo $row[0]["req_close_by"];?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<? }
    
 
    else If($tablename=="discount_details")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
          $row=select_query($query);
          
          $forward_dis_query = select_query("SELECT * FROM forward_req_history where table_name='$tablename' and table_row_id=".$RowId);
          $total_dis_query = count($forward_dis_query);
          //echo "<pre>"; print_r($forward_dis_query);die;
          
    ?>
<div id="databox">
  <div class="heading">Discount</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?echo $row[0]["date"];?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?echo $row[0]["sales_manager"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["client"];?></td>
        </tr>
        <!--<tr><td>Vehicle    for discount</td><td><?echo $row[0]["reg_no"];?></td></tr>-->
        <tr>
          <td>Vehicle    for discount </td>
          <td><?php $vechile_no = explode(",",$row[0]["reg_no"]);
for($i=0;$i<=count($vechile_no);$i++){ if($i%3!=0){ echo $vechile_no[$i].", ";}else { echo "<br/>".$vechile_no[$i].", ";} }?></td>
        </tr>
        <tr>
          <td>Discount For</td>
          <td><?echo $row[0]["rent_device"];?></td>
        </tr>
        <tr>
          <td>Month</td>
          <td><?echo $row[0]["mon_of_dis_in_case_of_rent"];?></td>
        </tr>
        <tr>
          <td>Discount Amount </td>
          <td><?echo $row[0]["dis_amt"];?></td>
        </tr>
        <tr>
          <td>After Discount </td>
          <td><?echo $row[0]["amt_rec_after_dis"];?></td>
        </tr>
        <tr>
          <td>Before Discount </td>
          <td><?echo $row[0]["amt_before_dis"];?></td>
        </tr>
        <tr>
          <td>Reason </td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td>Service Action</td>
          <td><?echo $row[0]["service_action"];?></td>
        </tr>
        <!--<tr><td colspan="2">-------------------------------------------</td> </tr>-->
        
        <?php if($total_dis_query>0){?>
        <tr>
          <td colspan="2">-------------------------------------------</td>
        </tr>
        <tr>
          <td colspan="2"><table cellspacing="2" cellpadding="2">
              <tbody>
                <tr>
                  <td align="left">Req Forwarded to</td>
                  <td>Forward Comment</td>
                  <td>F/W Request Back Comment</td>
                </tr>
                <?php for($dis=0;$dis<count($forward_dis_query);$dis++){ ?>
                <tr>
                  <td><?php echo $forward_dis_query[$dis]["forward_req_user"];?></td>
                  <td><?php echo $forward_dis_query[$dis]["forward_comment"];?></td>
                  <td><?php echo $forward_dis_query[$dis]["forward_back_comment"];?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["discount_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["discount_issue"]=="Software Issue" && $row[0]["approve_status"]==0 && $row[0]["software_comment"]=="" && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["discount_status"]==1){echo "Pending at Tech Support Login (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["discount_issue"]=="Software Issue" && $row[0]["discount_status"]==1 && $row[0]["approve_status"]!=1 && $row[0]["software_comment"]=="")
    {echo "Pending at Tech Support Login";}
    elseif($row[0]["discount_issue"]=="Repair Cost Issue" && $row[0]["discount_status"]==1 && $row[0]["approve_status"]!=1 && $row[0]["repair_comment"]=="")
    {echo "Pending at Repair Login";}
    elseif($row[0]["discount_issue"]=="Service Issue" && $row[0]["discount_status"]==1 && $row[0]["approve_status"]!=1 && $row[0]["service_comment"]=="")
    {echo "Pending at Service Support Login";}   
    elseif($row[0]["discount_status"]==1 && $row[0]["account_comment"]=="" && $row[0]["total_pending"]=="" && $row[0]["approve_status"]!=1){echo "Pending at Account Login";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["discount_status"]==1)    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}   
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["discount_status"]==1)
    {echo "Pending Admin Approval";}       
    elseif($row[0]["approve_status"]==1 && $row[0]["final_status"]==0){echo "Pending at Account For Discounting";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?echo $row[0]["total_pending"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Software Comment</td>
          <td><?echo $row[0]["software_comment"];?></td>
        </tr>
        <tr>
          <td>Repair Comment</td>
          <td><?echo $row[0]["repair_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<? }
    else If($tablename=="deactivation_of_account")
        {
             $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
            
             $query1 = select_query("SELECT imei_of_removed_devices,other_imei_removed,client,device_location FROM stock_deactivation_of_account where deactivate_acc_id=".$RowId);

  ?>
<div id="databox">
  <div class="heading">Deactivation Of Account</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?echo $row[0]["date"];?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?echo $row[0]["sales_manager"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["company"];?></td>
        </tr>
        <tr>
          <td>Total No Of Vehicle </td>
          <td><?echo $row[0]["total_no_of_vehicles"];?></td>
        </tr>
        <tr>
          <td>Device Removed Status</td>
          <td><?echo $row[0]["device_remove_status"];?></td>
        </tr>
        <tr>
          <td>No of Removed Device</td>
          <td><?echo $row[0]["no_of_removed_devices"];?></td>
        </tr>
        <tr>
          <td>Deactivate </td>
          <td><?echo $row[0]["deactivate_temp"];?></td>
        </tr>
        <tr>
          <td>Reason</td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td colspan="2">-------------------------------------------</td>
        </tr>
        <tr>
          <td colspan="2"><table cellspacing="2" cellpadding="2">
              <tbody>
                <tr>
                  <td align="left">IMEI No.</td>
                  <td>Client Name</td>
                  <td>Device Location</td>
                </tr>
                <?php for($stc=0;$stc<count($query1);$stc++){?>
                <tr>
                  <?php if($query1[$stc]["imei_of_removed_devices"]!=""){ ?>
                  <td><?php echo $query1[$stc]["imei_of_removed_devices"];?></td>
                  <?php }else{ ?>
                  <td><?php echo $query1[$stc]["other_imei_removed"];?></td>
                  <?php } ?>
                  <td><?php echo $query1[$stc]["client"];?></td>
                  <td><?php echo $query1[$stc]["device_location"];?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Pending Amount</td>
          <td><?echo $row[0]["pay_pending"];?></td>
        </tr>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["deactivation_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["device_remove_status"]=="Y" && $row[0]["no_device_removed"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Stock";}
    elseif($row[0]["account_comment"]=="" && $row[0]["pay_pending"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["pay_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["deactivation_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["deactivation_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Stock Comment</td>
          <td><?echo $row[0]["stock_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<? }

    else If($tablename=="deactivate_sim")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
    ?>
<div id="databox">
  <div class="heading">Deactivate SIM</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tr>
        <td>Date</td>
        <td><?echo $row[0]["date"];?></td>
      </tr>
      <tr>
        <td>Request By</td>
        <td><?echo $row[0]["acc_manager"];?></td>
      </tr>
      <tr>
        <td>Account Manager</td>
        <td><?echo $row[0]["sales_manager"];?></td>
      </tr>
      <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
      <tr>
        <td>Client User Name </td>
        <td><?echo $rowuser[0]["sys_username"];?></td>
      </tr>
      <tr>
        <td>Company</td>
        <td><?echo $row[0]["client"];?></td>
      </tr>
      <tr>
        <td>Veh Num</td>
        <td><?echo $row[0]["vehicle"];?></td>
      </tr>
      <tr>
        <td>Device Model</td>
        <td><?echo $row[0]["device_model"];?></td>
      </tr>
      <tr>
        <td>Device IMEI</td>
        <td><?echo $row[0]["device_imei"];?></td>
      </tr>
      <tr>
        <td>Mobile Number</td>
        <td><?echo $row[0]["device_sim"];?></td>
      </tr>
      <tr>
        <td>Present Status of Device</td>
        <td>---------------------------</td>
      </tr>
      <tr>
        <td>Location</td>
        <td><?echo $row[0]["ps_of_location"];?></td>
      </tr>
      <tr>
        <td>Ownership</td>
        <td><?echo $row[0]["ps_of_ownership"];?></td>
      </tr>
      <tr>
        <td>Payment Status</td>
        <td><?echo $row[0]["payment_status"];?></td>
      </tr>
      <tr>
        <td>SIM Status</td>
        <td><?echo $row[0]["sim_status"];?></td>
      </tr>
      <tr>
        <td>Change Date</td>
        <td><?echo $row[0]["change_date"];?></td>
      </tr>
      <tr>
        <td>Reason</td>
        <td><?echo $row[0]["replace_date"];?></td>
      </tr>
      <tr>
        <td colspan="2">-------------------------------------------</td>
      </tr>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["account_comment"]=="" && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<? }

    else If($tablename=="stop_gps")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
    ?>
<div >
<div id="databox">
  <div class="heading">Stop Gps</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?echo $row[0]["date"];?></td>
        </tr>
        <? /* if($row[0]["acc_manager"]=='saleslogin') {
$account_manager=$row[0]["sales_manager"];
}
else {
$account_manager=$row[0]["acc_manager"];
}*/

?>
        <tr>
          <td>Request By</td>
          <td><?echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?echo $row[0]["sales_manager"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["client"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["company"];?></td>
        </tr>
        <tr>
          <td>Total No Of Vehicle </td>
          <td><?echo $row[0]["tot_no_of_vehicle"];?></td>
        </tr>
        <tr>
          <td>Vehicle to Stop GPS </td>
          <td><?echo $row[0]["no_of_vehicle"];?></td>
        </tr>
        <tr>
          <td>Persent Status Of</td>
          <td>:---</td>
        </tr>
        <tr>
          <td>Location </td>
          <td><?echo $row[0]["ps_of_location"];?></td>
        </tr>
        <tr>
          <td>OwnerShip </td>
          <td><?echo $row[0]["ps_of_ownership"];?></td>
        </tr>
        <tr>
          <td>Reason </td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td colspan="2">-------------------------------------------</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["stop_gps_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["account_comment"]=="" && $row[0]["total_pending"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["stop_gps_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["stop_gps_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?echo $row[0]["total_pending"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}
?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<? }


    else If($tablename=="start_gps")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
    ?>
<div id="databox">
  <div class="heading">Start Gps</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?echo $row[0]["sales_manager"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["client"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["company"];?></td>
        </tr>
        <tr>
          <td>Total No Of Vehicle </td>
          <td><?echo $row[0]["tot_no_of_vehicle"];?></td>
        </tr>
        <tr>
          <td>Persent Status Of</td>
          <td>:---</td>
        </tr>
        <tr>
          <td>OwnerShip </td>
          <td><?echo $row[0]["ps_of_ownership"];?></td>
        </tr>
        <tr>
          <td>Reason </td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td>Vehicle to Start GPS </td>
          <td><?php $vechile_no = explode(",",$row[0]["reg_no"]);
for($i=0;$i<=count($vechile_no);$i++){ if($i%3!=0){ echo $vechile_no[$i].", ";}else { echo "<br/>".$vechile_no[$i].", ";} }?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["start_gps_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["account_comment"]=="" && $row[0]["total_pending"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["start_gps_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["start_gps_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?echo $row[0]["total_pending"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?
    }

    else If($tablename=="transfer_the_vehicle")
        {
        $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

    ?>
<div id="databox">
  <div class="heading">Transfer Vehicle</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date</td>
          <td><?echo $row[0]["date"];?></td>
        </tr>
        <? /*if($row[0]["acc_manager"]=='saleslogin') {
$account_manager=$row[0]["sales_manager"];
}
else {
$account_manager=$row[0]["acc_manager"];
}*/

?>
        <tr>
          <td>Request By</td>
          <td><?echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?echo $row[0]["sales_manager"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["transfer_from_user"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["transfer_from_company"];?></td>
        </tr>
        <tr>
          <td>Total No Of Vehicle </td>
          <td><?echo $row[0]["date"];?></td>
        </tr>
        <!--<tr><td>Vehicle to move </td><td><?echo $row[0]["transfer_from_reg_no"];?></td></tr> -->
        
        <tr>
          <td>Vehicle to move </td>
          <td><?php $vechile_no = explode(",",$row[0]["transfer_from_reg_no"]);
for($i=0;$i<=count($vechile_no);$i++){ if($i%3!=0){ echo $vechile_no[$i].", ";}else { echo "<br/>".$vechile_no[$i].", ";} }?></td>
        </tr>
        <tr>
          <td>Transfer To:--</td>
          <td></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["transfer_to_user"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Transfer User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Transfer Company Name </td>
          <td><?echo $row[0]["transfer_to_company"];?></td>
        </tr>
        <tr>
          <td>Billing</td>
          <td><?echo $row[0]["transfer_to_billing"];?></td>
        </tr>
        <tr>
          <td>Billing Name</td>
          <td><?echo $row[0]["billing_name"];?></td>
        </tr>
        <tr>
          <td>Billing Address</td>
          <td><?echo $row[0]["billing_address"];?></td>
        </tr>
        <tr>
          <td>Reason</td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["transfer_veh_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["account_comment"]=="" && $row[0]["total_pending"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["transfer_veh_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["transfer_veh_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?echo $row[0]["total_pending"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
    </table>
  </div>
</div>
<? }
    else If($tablename=="del_form_debtors")
        {
        $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
        $query1 = select_query("SELECT imei_of_removel_devices,other_imei_removed,client,device_location FROM stock_del_form_debtors where del_debtors_id=".$RowId);

    ?>
<div id="databox">
  <div class="heading">Delete From Debtors</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?echo $row[0]["date"];?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?echo $row[0]["sales_manager"];?></td>
        </tr>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?echo $row[0]["company"];?></td>
        </tr>
        <tr>
          <td>Total No Of Vehicle </td>
          <td><?echo $row[0]["total_no_of_vehicle"];?></td>
        </tr>
        <tr>
          <td>Date Of Creation </td>
          <td><?echo $row[0]["date_of_creation"];?></td>
        </tr>
        <tr>
          <td>Reason</td>
          <td><?echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td colspan="2">-------------------------------------------</td>
        </tr>
        <tr>
          <td colspan="2"><table cellspacing="2" cellpadding="2">
              <tbody>
                <tr>
                  <td align="left">IMEI No.</td>
                  <td>Client Name</td>
                  <td>Device Location</td>
                </tr>
                <?php for($st=0;$st<count($query1);$st++){?>
                <tr>
                  <?php if($query1[$st]["imei_of_removel_devices"]!=""){ ?>
                  <td><?php echo $query1[$st]["imei_of_removel_devices"];?></td>
                  <?php }else{ ?>
                  <td><?php echo $query1[$st]["other_imei_removed"];?></td>
                  <?php } ?>
                  <td><?php echo $query1[$st]["client"];?></td>
                  <td><?php echo $query1[$st]["device_location"];?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?  if($row[0]["del_debtors_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["device_remove_status"]=="Y" && $row[0]["no_device_removed"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Stock";}
    elseif($row[0]["account_comment"]=="" && $row[0]["total_pending"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["del_debtors_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["del_debtors_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?echo $row[0]["total_pending"];?></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Stock Comment</td>
          <td><?echo $row[0]["stock_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?
if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}
?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?
if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td>
        </tr>
      <br />
        </tbody>
      
    </table>
  </div>
</div>
<?
}
  
    }
?>