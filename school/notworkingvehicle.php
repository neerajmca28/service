<?
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu_school.php');
/*include_once(__DOCUMENT_ROOT.'/private/master.php');*/

//$masterObj = new master();

$username="";

//$userId = "'5997','6853','3708','6867'";
$groupId =  "'5923','6754','3733','6768','7470'";

$data = select_query_live_con("select latest_telemetry.gps_fix,latest_telemetry.tel_voltage, 
case when tel_poweralert=true then true else false end as poweronoff,services.id,sys_created,veh_reg, 
adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact,round(gps_speed*1.609,0) as speed, 
case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat, 
latest_telemetry.gps_longitude as lng ,devices.imei,services.device_removed_service from matrix.services join 
matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id join matrix.devices on devices.id=services.sys_device_id 
where services.id in (select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (".$groupId.")) and 
adddate(latest_telemetry.gps_time,INTERVAL 19800 second)< adddate('".date('Y-m-d H:i:s')."',interval -2 hour)");

//echo "<pre>";print_r($data);die;

/*if($_POST["submit"])
{

    $selecttype= $_POST["selecttype"];    
    $username= $_POST["userid"];
    
    $userData = $masterObj->getUserDetails($username);
    
    //echo "<pre>";print_r($userData);die;
    
    $userId=$userData[0]['id'];
    $Company=$userData[0]['company'];
    $userPhoneNumber=$userData[0]['phone_number'];

                                                                                   
    $data = $masterObj->getdebug_data($userId,$selecttype);


   //echo "<pre>";print_r($data);die;

}*/

?>
<script>
/*
function ConfirmDelete(row_id)
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

            //alert(user_id);

            //return false;

$.ajax({

                        type:"GET",

                        url:"userInfo.php?action=debugComment",

                         

                         data:"row_id="+row_id+"&comment="+retVal,

                        success:function(msg){

                                    alert(msg);

                         

                        location.reload(true);                      

                        }

            });

}*/

</script>
<script src="http://trackingexperts.com/thickbox/thickbox.js" type="text/javascript"></script>
<link rel="stylesheet" href="http://trackingexperts.com/thickbox/thickbox.css" type="text/css" media="projection, screen" />

<div class="top-bar">
  <div class="top-bar">
  <!--<style>
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
  <p class="my-element">NOTE: Don't use this page for current location. Data updated till 9:00 am </p>-->
    <div style="float:right";><font style="color:#D462FF;font-weight:bold;">Purple:</font> Device Removed</div>
    <br/>
    <div style="float:right";><font style="color:#01DF01;font-weight:bold;">Green:</font> Problem from clientside</div>
    <br/>
    <div style="float:right";><font style="color:#00FFFF;font-weight:bold;">Blue:</font> Not working vehicle</div>
  </div>
  <h1> Vehicle Detail</h1>
  <div style="float:right;font-weight:bold">
  
 </div>
</div>
<div style="padding-left:5px;padding-top:5px">
  <!--<form method="post" action="" onsubmit="return submitme();" name="form2">
    <input type="text" name="userid" id="userid" value="<?=$username?>">
    &nbsp;&nbsp;
    <select name="selecttype">
      <option id="0" value="0" <?php if($selecttype=="" or $selecttype==0){ ?> selected="selected" <?php } ?> >All Vehicles</option>
      <option id="1" value="1" <?php if($selecttype==1){ ?> selected="selected" <?php } ?> selected>
      Not Working Vehicles
      </option>
      <option id="2" value="2" <?php if($selecttype==2){ ?> selected="selected" <?php } ?> >Device removed</option>
    </select>
    &nbsp;&nbsp;
    <input type="submit" name="submit" value="submit">
  </form>-->
  <br/>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      <tr>
        <th>Sl. No</th>
        <!--<th>Id </th>-->
        <th>Vehicle Reg No</th>
        <th>Client Name</th>
        <th>Imei</th>
        <th>Speed </th>
        <th>Ac Status </th>
        <th>Last ContactTime </th>
        <th>Lat </th>
        <th>Long </th>
        <th>Contact Details</th>
        <th>Device Status</th>
        <!--<th>SSE </th>-->
        <th> Add Service </th>
        <!--<th> Add Comment </th>-->
        <th>View Comment</th>
      </tr>
    </thead>
    <tbody>
  <?php

$clientInfo = array('lotusvalleynoida' => 'Mr. Gaurav(9013272620)','dps' => 'Mr. Anuj(9599912448)','gdgoenkapashim' => 'Mr. Ashish(9990020989)','vedpal' => 'Mr. Vedpal(8826859265)','marutiemp' => 'Mr. Jitender(8130313268)');

for($i=0;$i<count($data);$i++)
{

        //device_removed_service
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
        elseif($hourdiff>=2)
                    {$rowStyle='style="background-color:#00FFFF"';}
          $Imag='';
        
          $toolTip=''; 

$namedata = select_query_live_con("select sys_username,id,company from matrix.users where id=(select sys_user_id from matrix.group_services join matrix.group_users on 
group_services.sys_group_id=group_users.sys_group_id where  group_services.active=true  and group_services.sys_service_id=".$data[$i]['id']." 
and sys_user_id  in (3708,5997,6853,6867,7567) limit 1)");    

$userName = $namedata[0]['sys_username'];
$userId = $namedata[0]['id'];
$Company = $namedata[0]['company'];


if(array_key_exists($namedata[0]['sys_username'], $clientInfo)) 
{
    $clientDetails = $clientInfo[$namedata[0]['sys_username']];
}
else
{
    $clientDetails = '';
}
/*$telecallerData = $masterObj->getSseName($userName);*/

 

?>
      <tr align="center" <? echo $rowStyle;?> >
        <td><?php echo $i+1; ?></td>
        <!--<td>&nbsp;<?php echo $data[$i]['id'];?></td>-->
        <td>&nbsp;<?php echo $data[$i]['veh_reg'];?></td>
        <td>&nbsp;<?php echo $userName;?></td>
        <td>&nbsp;<?php echo $data[$i]['imei'];?></td>
        <td>&nbsp;<?php echo $data[$i]['speed'];?></td>
        <td>&nbsp;<?php if($data[$i]['aconoff']==1){echo "AC ON"; }else{ echo "AC OFF";}?></td>
        <td>&nbsp;<?php echo $data[$i]['lastcontact'];?></td>
        <td>&nbsp;<?php echo $data[$i]['lat'];?></td>
        <td>&nbsp;<?php echo $data[$i]['lng'];?></td>
        <td>&nbsp;<?php echo $clientDetails;?></td>
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
          
          <? //echo $row['poweronoff'];?></td>
       <!-- <td>&nbsp;<?php echo $telecallerData[0]['telecaller'];?></td>-->
        
        <!--<td>&nbsp;<?php echo $sse_caller;?></td>-->
        
        <td><a href="service_request.php?u=<?php echo $userId;?>&c=<?php echo $Company;?>&v=<?php echo $data[$i]["veh_reg"];?>&i=<?php echo $data[$i]["imei"];?>&d=<?php echo $data[$i]["sys_created"];?>&n=<?php echo $data[$i]["lastcontact"];?>&Addservice=true" >Add Service</a></td>
        <!--<td>&nbsp;
          
          <a href="addcomment-iframe.php?serviceid=<?= $data[$i]["id"]?>&height=200&width=500" class="thickbox"  >Add Comment</a></td>-->
        <td><a href="#" onclick="Show_record(<?php echo $data[$i]["id"];?>,'comment','popup1'); " class="topopup">View Comment</a></td>
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
<?

include("../include/footer.inc.php");

 

?>