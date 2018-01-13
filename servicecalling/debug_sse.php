<?
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/private/master.php');


$masterObj = new master();
$username="";


if($_POST["submit"])
{

    $selecttype = $_POST["selecttype"];    
    $username = $_POST["userid"];
    
    
    $assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
                                   sys_active='1' and UserName='".$username."' order by client_type");
    
    //echo "<pre>";print_r($userData);die;
    
    //$userData = $masterObj->getUserDetails($username);
    /*$userId=$userData[0]['id'];
    $Company=$userData[0]['company'];
    $userPhoneNumber=$userData[0]['phone_number'];*/
                                                                                   
    //$data = $masterObj->getdebug_data($userId,$selecttype);

   //echo "<pre>";print_r($data);die;

}
else
{

    $from_date = date('Y-m-d', strtotime('-1 month'));
    $to_date = date("Y-m-d", strtotime('-1 day'));
    $selecttype = 1;
    
    if($_SESSION['BranchId'] == 1)
    {
    
        $telecaller = select_query("select * from internalsoftware.telecaller_users where login_name='".$_SESSION['username']."' and `status`='1'  
                                    and branch_id='".$_SESSION['BranchId']."'");
        
        $assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
                    branch_id='".$_SESSION['BranchId']."' and sys_active='1' and telecaller_id='".$telecaller[0]['id']."' order by client_type"); 
    
    }else {
        
        $assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
                        branch_id='".$_SESSION['BranchId']."' and sys_active='1'  order by client_type"); 
    
    }
        
    
    
}

//echo "<pre>";print_r($assign_client);die;    

?>
<script>
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

}

</script>


<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=AIzaSyDdKcx6Qx6Dr4vtlkltAdyhFvNySaq8dXY" type="text/javascript"></script>


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

    <div style="float:right";><font style="color:#01DF01;font-weight:bold;">Green:</font> Problem from clientside</div>
    <br/>
    <div style="float:right";><font style="color:#00FFFF;font-weight:bold;">Blue:</font> Not working vehicle</div>
    <br/>
    <div style="float:right";><font style="color:#D462FF;font-weight:bold;">Purple:</font> Device Removed</div>
    <br/>
    <div style="float:right";><font style="color:#f44336;font-weight:bold;">Red:</font> No of Service</div>
  </div>
  <h1> Vehicle Detail</h1>
  <div style="float:right;font-weight:bold">
  
  <? //echo "UserId= ".$userId."<br>";

    //echo "Phone Number= ".$userPhoneNumber."<br>";
    
    //echo "Total Vehicles :". count($data);

if($userId!="")
{
    if($_SESSION['BranchId']==2)
    {
        echo '<br/><a href="downloadcsv_mumvai.php?csv=true&id='.$userId.'&name='.$username.'" >Create Excel</a>';
    }
    else
    {
        echo '<br/><a href="downloadcsv.php?csv=true&id='.$userId.'&name='.$username.'" >Create Excel</a>';
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
      <option id="1" value="1" <?php if($selecttype==1){ ?> selected="selected" <?php } ?> selected> Not Working Vehicles </option>
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
        <!--<th>Repair Status </th>-->
        <th>Add Service </th>
        <th>Add Comment </th>
        <th>View Comment</th>
      </tr>
    </thead>
    <tbody>
<?php

//echo "<pre>";print_r($assign_client);die;
$sr = 1;

for($ag=0;$ag<count($assign_client);$ag++)
{
    
    $data = $masterObj->getdebug_data($assign_client[$ag]['Userid'],$selecttype);    
    //echo "<pre>";print_r($data);die;
    
    /*$userData = $masterObj->getUserDetails($assign_client[$ag]['UserName']);
    $userId = $userData[0]['id'];
    $Company = $userData[0]['company'];
    $userPhoneNumber = $userData[0]['phone_number'];*/
    
    $userId = $assign_client[$ag]['Userid'];
    $userName = $assign_client[$ag]['UserName'];
    
    for($i=0;$i<count($data);$i++)
    {
            $rowStyle = "";
            $time1 = date('Y-m-d H:i:s');
            $time2 = $data[$i]['lastcontact'];
            $hourdiff = round((strtotime($time1) - strtotime($time2))/3600, 0);
            $device_removed = $data[$i]['device_removed_service'];

            if($device_removed==1 )
                        {$rowStyle='style="background-color:#D462FF"';}
            else if($data[$i]['tel_voltage']<'3.5' && $data[$i]['tel_voltage']>'0.0')
                        {$rowStyle='style="background-color:#01DF01"';}
            else if($data[$i]['tel_poweralert']=0)
                        {$rowStyle='style="background-color:#01DF01"';}
            elseif($hourdiff>=2)
                        {$rowStyle='style="background-color:#00FFFF"';}

            $Imag = '';
            
            $toolTip = '';
            
            /*if($username == "vimal")
            {
                $vimal_data = $masterObj->getVimalDetails($data[$i]['id']); 
                $userName=$vimal_data[0]['sys_username'];
            }else{
                //$userName=$username;
                $vimal_data = $masterObj->getVimalDetails($data[$i]['id']); 
                $userName=$vimal_data[0]['sys_username'];
            }*/
        
        //$telecallerData = $masterObj->getSseName($userName);
        
        $imei_no = str_replace("_","",$data[$i]['imei']); 
        
         $vehicle_service = select_query("select veh_reg from services where veh_reg='".trim($data[$i]['veh_reg'])."' and service_status='5' 
                                        and atime>='".$from_date." 00:00:00' and atime<='".$to_date." 23:59:59' ");
        $vehicle_service_total = count($vehicle_service);
        
        $device_service = select_query("select device_imei from services where device_imei='".trim($imei_no)."' and service_status='5' 
                                        and atime>='".$from_date." 00:00:00' and atime<='".$to_date." 23:59:59' ");
        $device_service_total = count($device_service);                            
    
?>
      <tr align="center" <? echo $rowStyle;?> >
        <td><?php echo $sr; ?></td>
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
        <td><a href="#" onclick="Vehicle_onmap('Showvehicleonmap',<?=$data[$i]['lat']?>,'<?=$data[$i]['lng']?>');" class="topopup"><?php echo $data[$i]['lat'].','.$data[$i]['lng'];?></a></td>
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
          
          <!--<img width="20px" height="20px" border="0" src="<?echo $Imag;?>" title="<?echo $toolTip?>"><br/>-->
          <? //echo $row['poweronoff'];?></td>
        <!--<td>&nbsp;<?php //echo $telecallerData[0]['telecaller'];?></td>-->
        
        <!--<td>&nbsp;<?php echo $sse_caller;?></td>-->
        
       <!-- <td>&nbsp;<?php echo "0";?></td>-->
        
        <td><a href="service_request.php?u=<?php echo $userId;?>&c=<?php echo $Company;?>&v=<?php echo $data[$i]["veh_reg"];?>&i=<?php echo $imei_no;?>&d=<?php echo $data[$i]["sys_created"];?>&n=<?php echo $data[$i]["lastcontact"];?>&Addservice=true" target="_blank" >Add</a></td>
        
        <td><a href="addcomment-iframe.php?serviceid=<?=$data[$i]["id"]?>&height=220&width=480" class="thickbox">Add </a></td>
        <td><a href="#" onclick="Show_record(<?php echo $data[$i]["id"];?>,'comment','popup1'); " class="topopup">View </a></td>
      </tr>
      <?php  
          $sr++;
        
          } 
    
    }
      ?>
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