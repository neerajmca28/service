<?
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/private/master.php');


$masterObj = new master();
$username = ""; 
$selecttype = 1;

if($_SESSION['BranchId'] == 1)
{

    $telecaller = select_query("select * from internalsoftware.telecaller_users where login_name='".$_SESSION['username']."' and `status`='1'  
                                and branch_id='".$_SESSION['BranchId']."'");
    
    $assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
                    branch_id='".$_SESSION['BranchId']."' and sys_active='1' and telecaller_id='".$telecaller[0]['id']."'  order by client_type"); 

}else {
    
    $assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
                    branch_id='".$_SESSION['BranchId']."' and sys_active='1'  order by client_type"); 

}

for($i=0;$i<count($assign_client);$i++)
    {
        $client_id.= $assign_client[$i]['Userid'].",";
    }
    
$client_rslt = substr($client_id,0,strlen($client_id)-1);

$viewData = $_REQUEST['mode'];


if($viewData == "ClientProblem")
{
    $data = select_query("select user_id, sys_service_id as id, user_name, company_name, veh_no as veh_reg,device_imei as imei, vehicle_service, 
            device_service, date_of_installation as sys_created, notwoking as lastcontact, gps_latitude as lat, gps_longitude as lng, 
            gps_speed as speed, gps_fix, tel_voltage, case when tel_poweralert=true then true else false end as poweronoff, 
            case when tel_ignition=true then true else false end as aconoff, device_removed_service from 
            internalsoftware.client_notworking_vehicle where user_id IN (".$client_rslt.") and is_active='1' 
            and tel_voltage<3.5 and tel_voltage>0.0 and device_removed_service!='1'");
}
elseif($viewData == "DeviceRemoved")
{
    $data = select_query("select user_id, sys_service_id as id, user_name, company_name, veh_no as veh_reg,device_imei as imei, vehicle_service, 
            device_service, date_of_installation as sys_created, notwoking as lastcontact, gps_latitude as lat, gps_longitude as lng, 
            gps_speed as speed, gps_fix, tel_voltage, case when tel_poweralert=true then true else false end as poweronoff, 
            case when tel_ignition=true then true else false end as aconoff, device_removed_service from 
            internalsoftware.client_notworking_vehicle where user_id IN (".$client_rslt.") and is_active='1' 
            and device_removed_service='1'");
}
elseif($viewData == "NoofService")
{
    $data = select_query("select user_id, sys_service_id as id, user_name, company_name, veh_no as veh_reg,device_imei as imei, vehicle_service, 
            device_service, date_of_installation as sys_created, notwoking as lastcontact, gps_latitude as lat, gps_longitude as lng, 
            gps_speed as speed, gps_fix, tel_voltage, case when tel_poweralert=true then true else false end as poweronoff, 
            case when tel_ignition=true then true else false end as aconoff, device_removed_service from 
            internalsoftware.client_notworking_vehicle where user_id IN (".$client_rslt.") and is_active='1'
             and (vehicle_service > '2' or device_service > '2')");
}
else
{
    
    $data = select_query("select user_id, sys_service_id as id, user_name, company_name, veh_no as veh_reg,device_imei as imei, vehicle_service, 
            device_service, date_of_installation as sys_created, notwoking as lastcontact, gps_latitude as lat, gps_longitude as lng, 
            gps_speed as speed, gps_fix, tel_voltage, case when tel_poweralert=true then true else false end as poweronoff, 
            case when tel_ignition=true then true else false end as aconoff, device_removed_service, 
            TIMESTAMPDIFF(HOUR, notwoking, NOW()) as hourdiff from internalsoftware.client_notworking_vehicle where  user_id IN (".$client_rslt.")
            and is_active='1' and is_service='0' and TIMESTAMPDIFF(HOUR, notwoking, NOW()) > 2 and device_removed_service!='1'
            and (tel_voltage>3.5 or tel_voltage<=0.0)");
}

    //$data = $masterObj->getdebug_data($client_rslt,1);
     //echo "<pre>";print_r($data);die;

if($_POST["submit"])
{

    $selecttype = $_POST["selecttype"];    
    $username= $_POST["userid"];
    
    $userData = $masterObj->getUserDetails($username);
    //echo "<pre>";print_r($userData);die;
    
    if($selecttype == 1)
    {
        $data = select_query("select user_id,sys_service_id as id, user_name, company_name,veh_no as veh_reg,device_imei as imei,vehicle_service, 
            device_service, date_of_installation as sys_created, notwoking as lastcontact, gps_latitude as lat, gps_longitude as lng, 
            gps_speed as speed, gps_fix, tel_voltage, case when tel_poweralert=true then true else false end as poweronoff, 
            case when tel_ignition=true then true else false end as aconoff, device_removed_service, 
            TIMESTAMPDIFF(HOUR,notwoking,NOW()) as hourdiff from internalsoftware.client_notworking_vehicle where user_id='".$userData[0]['id']."'
            and is_active='1' and is_service='0' and TIMESTAMPDIFF(HOUR, notwoking, NOW()) > 2 and device_removed_service!='1' and 
            (tel_voltage>3.5 or tel_voltage<=0.0)");
    }
    elseif($selecttype == 2)
    {
        $data = select_query("select user_id,sys_service_id as id,user_name, company_name, veh_no as veh_reg,device_imei as imei,vehicle_service, 
            device_service, date_of_installation as sys_created, notwoking as lastcontact, gps_latitude as lat, gps_longitude as lng, 
            gps_speed as speed, gps_fix, tel_voltage, case when tel_poweralert=true then true else false end as poweronoff, 
            case when tel_ignition=true then true else false end as aconoff, device_removed_service from 
            internalsoftware.client_notworking_vehicle where user_id='".$userData[0]['id']."' and is_active='1' 
            and device_removed_service='1'");
    }
    else
    {
        $data = $masterObj->getdebug_data($userData[0]['id'],$selecttype);
        //echo "<pre>";print_r($data);die;
    }
}

?>
<script>
/*function ConfirmDelete(row_id)
{
    var retVal = prompt("Write Comment : ", "");
    if (retVal)
    {
        addComment(row_id,retVal);
        return ture;
    }
    else
    return false;
}

function addComment(row_id,retVal)
{
    $.ajax({
            type:"GET",
            url:"userInfo.php?action=debugComment",
            data:"row_id="+row_id+"&comment="+retVal,
            success:function(msg)
            {
                alert(msg);
                location.reload(true);                      
            }
    });

}*/

function VehicleLocation(latitude,longitude,DivId)
{
   //alert(DivId);
   $.ajax({
        type:"GET",
        url: "location.php?action=get_location",
        data: "latitude="+latitude+"&longitude="+longitude,
        success:function(msg){
          //alert(msg);
             document.getElementById(DivId).innerHTML = msg;
                        
        }
    });
}

</script>


<div class="top-bar">
  <div class="top-bar">
  <style>
      @keyframes blink {
        to { color: red; }
        }
        
        .my-element {
        color: #000;
        text-shadow:1px 1px #6F0;
        animation: blink 1s steps(2, start) infinite;
        font-size: 18px; text-align: center;
        }
  </style>
    
    <div style="float:right";><a href="debug.php?mode=ClientProblem">Green:</a>
        <font style="color:#01DF01;font-weight:bold;"> Problem from clientside</font></div>
    <br/>
    <div style="float:right";><a href="debug.php?mode=NotWorking">Blue:</a>
        <font style="color:#00FFFF;font-weight:bold;"> Not working vehicle</font></div>
    <br/>
    <div style="float:right";><a href="debug.php?mode=DeviceRemoved">Purple:</a>
        <font style="color:#D462FF;font-weight:bold;"> Device Removed </font></div>
    <br/>
    <div style="float:right";><a href="debug.php?mode=NoofService">Red:</a>
        <font style="color:#f44336;font-weight:bold;">No of Service</font></div>
  </div>
  <h1> Vehicle Detail</h1>
  <div style="float:right;font-weight:bold">
  
  <? echo "UserId= ".$userData[0]['id']."<br>";

    //echo "Phone Number= ".$userPhoneNumber."<br>";
    
    echo "Total Vehicles :". count($data);

if($userData[0]['id']!="")
{
    if($_SESSION['BranchId']==2)
    {
        echo '<br/><a href="downloadcsv_mumvai.php?csv=true&id='.$userData[0]['id'].'&name='.$userData[0]['sys_username'].'" >Create Excel</a>';
    }
    else
    {
        echo '<br/><a href="downloadcsv.php?csv=true&id='.$userData[0]['id'].'&name='.$userData[0]['sys_username'].'" >Create Excel</a>';
    }
}

 

?></div>
</div>
<div style="padding-left:5px;padding-top:5px">
  <form method="post" action="" onsubmit="return submitme();" name="form2">
    <input type="text" name="userid" id="userid" value="<?=$username?>">
    &nbsp;&nbsp;
    <select name="selecttype">
      <option id="0" value="0" <?php if($selecttype=="" or $selecttype==0){ ?> selected="selected" <?php } ?> >All Vehicles</option>
      <option id="1" value="1" <?php if($selecttype==1){ ?> selected="selected" <?php } ?>> Not Working Vehicles </option>
      <option id="2" value="2" <?php if($selecttype==2){ ?> selected="selected" <?php } ?> >Device removed</option>
    </select>
    &nbsp;&nbsp;
    <input type="submit" name="submit" value="submit">
  </form>
  <br/>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      <tr>
        <th>Sl. No</th>
        <!--<th>Id </th>-->
        <th> Vehicle Reg No</th>
        <th>User Name</th>
        <th>Imei</th>
        <th>Vehicle Service</th>
        <th>Device Service</th>
        <th>Last ContactTime </th>
        <th>LatLong </th>
        <th>Device Status</th>
        <? if($viewData == "DeviceRemoved" || $selecttype==2) { ?>
        <th>Pending Repair Days</th>
        <th>Repair Status </th>
        <? } ?>
        <th>Add Service </th>
        <th>Add Comment </th>
        <th>View Comment</th>
      </tr>
    </thead>
    <tbody>
      <?php

    for($i=0;$i<count($data);$i++)
    {
            $rowStyle="";
            $time1=date('Y-m-d H:i:s');
            $time2=$data[$i]['lastcontact'];
            $hourdiff = round((strtotime($time1) - strtotime($time2))/3600, 0);
            $device_removed=$data[$i]['device_removed_service'];

            if($device_removed==1 )
                        {$rowStyle='style="background-color:#D462FF"';}
            else if($data[$i]['tel_voltage']<'3.5' && $data[$i]['tel_voltage']>'0.0')
                        {$rowStyle='style="background-color:#01DF01"';}
            else if($data[$i]['tel_poweralert']=0)
                        {$rowStyle='style="background-color:#01DF01"';}
            else if($hourdiff>=2)
                        {$rowStyle='style="background-color:#00FFFF"';}

            $Imag='';
            
            $toolTip='';
            
            $imei_no = str_replace("_","",$data[$i]['imei']);
            
            if($_POST["submit"])
            {
                $userName = $userData[0]['sys_username'];
                $userId = $userData[0]['id'];
                $Company = $userData[0]['company'];
                
                if($selecttype == 1 || $selecttype == 2)
                {
                    $vehicle_service_total = $data[$i]['vehicle_service'];
                    $device_service_total = $data[$i]['device_service'];
                }
                else
                {
                    $vehicle_service_total = 0;
                    $device_service_total = 0;
                }
            }else{
                $userName = $data[$i]['user_name'];
                $userId = $data[$i]['user_id'];
                $Company = $data[$i]['company_name'];

                $vehicle_service_total = $data[$i]['vehicle_service'];
                $device_service_total = $data[$i]['device_service'];                            
            }
            
?>
      <tr align="center" <? echo $rowStyle;?> >
        <td><?php echo $i+1; ?></td>
        <!--<td>&nbsp;<?php //echo $data[$i]['id'];?></td>-->
        <td>&nbsp;<?php echo $data[$i]['veh_reg'];?></td>
        <td>&nbsp;<?php echo $userName;?></td>
        <td>&nbsp;<?php echo $data[$i]['imei'];?></td>
        <!--<td>&nbsp;<?php //echo $data[$i]['speed'];?></td>
        <td>&nbsp;<?php //if($data[$i]['aconoff']==1){echo "AC ON"; }else{ echo "AC OFF";}?></td>-->
        
        <? if($vehicle_service_total > 2) { ?>
        <td style="background-color:#f44336">&nbsp;<?php echo $vehicle_service_total;?></td>
        <? } else { ?>
        <td>&nbsp;<?php echo $vehicle_service_total;?></td>
        <? } ?>
        
        <? if($device_service_total > 2) { ?>
        <td style="background-color:#f44336">&nbsp;<?php echo $device_service_total;?></td>
        <? } else { ?>
        <td>&nbsp;<?php echo $device_service_total;?></td>
        <? } ?>
        
        <td>&nbsp;<?php echo $data[$i]['lastcontact'];?></td>
        
        <td><a href="#" onclick="return VehicleLocation(<?=$data[$i]["lat"];?>,'<?=$data[$i]["lng"];?>','location_id<?=$i;?>');"><?php echo round($data[$i]['lat'],3).','.round($data[$i]['lng'],3);?></a><div id="location_id<?=$i;?>"></div></td>
        
        <td>&nbsp;
          <?php if($data[$i]['tel_voltage']<'3.5' && $data[$i]['tel_voltage']>'0.0')
            {
                $Imag="nobattery.PNG";
    
                $toolTip= " No Battery";
            ?>
          <img width="20px" height="20px" border="0" src="<?echo $Imag;?>" title="<?echo $toolTip?>"><br/>
          <?
            }
             if($data[$i]['poweronoff']==false && $data[$i]['tel_voltage']!=null && $data[$i]['tel_voltage']>0)
            {
              $Imag="nopower.PNG";
            
              $toolTip= " No power running with bettery power";
            
              //$Imag="nopower.PNG";
            ?>
          <img width="20px" height="20px" border="0" src="<?echo $Imag;?>" title="<?echo $toolTip?>"><br/>
          <?

          }

        if($data[$i]['gps_fix']<1)
        
        {$toolTip= " No GPS";
        
        $Imag="nogps.PNG";

?>
          <img width="20px" height="20px" border="0" src="<?echo $Imag;?>" title="<?echo $toolTip?>"><br/>
       <?

  }?>
        
         <? if($viewData == "DeviceRemoved" || $selecttype==2) { 
         
             $reapir_status = select_query_inventory("select device.device_imei, device.device_status, device_repair.device_removed_date, 
                             device_repair.device_removed_recddate, device_repair.opencase_date, device_repair.closecase_date,
                             TIMESTAMPDIFF(DAY, device_repair.device_removed_recddate, NOW()) as pending_days 
                             from inventory.device left join inventory.device_repair on device.device_id=device_repair.device_id 
                             where current_record=1 and device_repair.device_imei='".$imei_no."' and device.device_status!='65' ");
         ?>
        
        <td><?php if($reapir_status[0]['pending_days']!=''){ echo $reapir_status[0]['pending_days']." Day";}?></td>
        <td>&nbsp;<?php if($reapir_status[0]['device_status']==20){ echo "Raw Inventory" ;} 
        if($reapir_status[0]['device_status']==55){ echo "Installed" ;} 
        if($reapir_status[0]['device_status']==56){ echo "Ready to Ship" ;} if($reapir_status[0]['device_status']==57){ echo "Out Of Stock" ;} 
        if($reapir_status[0]['device_status']==58){ echo "Tested" ;} if($reapir_status[0]['device_status']==60){ echo "Configure" ;} 
        if($reapir_status[0]['device_status']==61){ echo "Temprary Attachment" ;} 
        if($reapir_status[0]['device_status']==62){ echo "Final Attach Sim" ;} 
        if($reapir_status[0]['device_status']==63){ echo "Branch Recd" ;} 
        if($reapir_status[0]['device_status']==64){ echo "Assign To Installer" ;} 
        if($reapir_status[0]['device_status']==65){ echo "Device Installed" ;} 
        if($reapir_status[0]['device_status']==66){ echo "Device Removed" ;} 
        if($reapir_status[0]['device_status']==67){ echo "Device Removed Recd" ;} 
        if($reapir_status[0]['device_status']==68){ echo "Open Repair Case" ;}
        if($reapir_status[0]['device_status']==69){ echo "FFC Done But Pending for Replace as New/Old" ;} 
        if($reapir_status[0]['device_status']==70){ echo "Dead Device" ;}
        if($reapir_status[0]['device_status']==71){ echo "Replace On Repair Device" ;} 
        if($reapir_status[0]['device_status']==72){ echo "Dead Device Remarks Pending" ;} 
        if($reapir_status[0]['device_status']==75){ echo "Branch Repair" ;} 
        if($reapir_status[0]['device_status']==76){ echo "Device Against Pyment With FFC" ;} 
        if($reapir_status[0]['device_status']==77){ echo "Device Against Pyment Without FFC" ;} 
        if($reapir_status[0]['device_status']==79){ echo "Send To Repair Centre" ;}
        if($reapir_status[0]['device_status']==80){ echo "UnCracked Device" ;} 
        if($reapir_status[0]['device_status']==81){ echo "Recd Remove Device" ;} 
        if($reapir_status[0]['device_status']==82){ echo "Internal Branch Repaired" ;} 
        if($reapir_status[0]['device_status']==83){ echo "Send To Repair By Branch" ;}
        if($reapir_status[0]['device_status']==84){ echo "Device Manufacture" ;} 
        if($reapir_status[0]['device_status']==85){ echo "Device Manufacture send" ;}
        if($reapir_status[0]['device_status']==86){ echo "Device Replaced By Manufactured" ;} 
        if($reapir_status[0]['device_status']==87){ echo "Sim Dispatch" ;}
        if($reapir_status[0]['device_status']==88){ echo "Sim Recd" ;} if($reapir_status[0]['device_status']==89){ echo "Sim Reassign" ;}
        if($reapir_status[0]['device_status']==90){ echo "Sim Servies" ;} if($reapir_status[0]['device_status']==91){ echo "Sim Installed" ;}
        if($reapir_status[0]['device_status']==92){ echo "Sim Deactivation";} if($reapir_status[0]['device_status']==93){ echo "Sim Repair";}      
        if($reapir_status[0]['device_status']==94){ echo "Assign Dead Device" ;} 
        if($reapir_status[0]['device_status']==95){ echo "Dead Device To Client" ;}
        if($reapir_status[0]['device_status']==96){ echo "ReAssign Dead Device" ;} 
        if($reapir_status[0]['device_status']==97){ echo "Recd Dead Device" ;} 
        if($reapir_status[0]['device_status']==99){ echo "FFC AS New" ;} if($reapir_status[0]['device_status']==100){ echo "Replace FFC" ;}
        if($reapir_status[0]['device_status']==102){ echo "Very Old SIM" ;} if($reapir_status[0]['device_status']==103){ echo "Client Office" ;}
        if($reapir_status[0]['device_status']==104){ echo "Device Delete Account" ;} 
        if($reapir_status[0]['device_status']==105){ echo "Device Has been Repaired But Not Received By Stock" ;} 
        if($reapir_status[0]['device_status']==106){ echo "Sim is Pending for Deactivation" ;} 
        if($reapir_status[0]['device_status']==108){ echo "Archiev Dead List" ;}
        if($reapir_status[0]['device_status']==109){ echo "Send To Repair Centre Manufacture" ;} 
        if($reapir_status[0]['device_status']==120){ echo "Missing Device In Inventory" ;}
        if($reapir_status[0]['device_status']==116){ echo "Device Has been Repair and Deposist to Stock Delhi" ;}
        if($reapir_status[0]['device_status']==122){ echo "Device Has been Send But Not Received By R and D" ;}
     ?></td>
        
        <? } ?>
        
        <td><a href="service_request.php?u=<?php echo $userId;?>&c=<?php echo $Company;?>&v=<?php echo $data[$i]["veh_reg"];?>&i=<?php echo $imei_no;?>&d=<?php echo $data[$i]["sys_created"];?>&n=<?php echo $data[$i]["lastcontact"];?>&Addservice=true" target="_blank" >Add</a></td>
        
        <td><a href="addcomment-iframe.php?serviceid=<?=$data[$i]["id"]?>&height=220&width=480" class="thickbox">Add </a></td>
        <td><a href="#" onclick="Show_record(<?php echo $data[$i]["id"];?>,'comment','popup1'); " class="topopup">View </a></td>
      </tr>
      <?php  } ?>
  </table>
  
  <div id="toPopup">
    <div class="close">close</div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup1"></div>

    <!--your content end--> 
    
  </div>
  <!--toPopup end-->
  
  <div class="loader"></div>
  <div id="backgroundPopup"></div>
</div>
<?

include("../include/footer.inc.php");

 

?>