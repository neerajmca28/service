<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */ 
//include($_SERVER["DOCUMENT_ROOT"]."/service/sqlconnection.php");

$data=array();

    /*$internal_service = mysql_query("select * from internalsoftware.services where service_reinstall='service' and device_status='Temporary' and temp_to_permanent is null and service_status=5 AND reason IN('Device removed (against payment)','Device removed,it was on demo','Demo device removed') and branch_id='".$_SESSION['BranchId']."' GROUP BY device_imei order by req_date DESC");*/
    
    $internal_service = mysql_query("select * from (select * from internalsoftware.services where service_reinstall='service' and device_status!='Permanent' and temp_to_permanent is null and service_status=5 AND reason IN('Device removed (against payment)','Device removed,it was on demo','Demo device removed','Device removed(Buy Back)') and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc) as services GROUP BY device_imei order by req_date DESC");
    
    $internal_ok = "";
    while($internal_service_row = mysql_fetch_array($internal_service))
        {
            $internal_ok.= $internal_service_row['device_imei']."','";
            
            $arr=array (
                    'id' => $internal_service_row["id"],
                    'req_date'=> $internal_service_row["req_date"],
                    'request_by' => $internal_service_row["request_by"],
                    'company_name' => $internal_service_row["company_name"],
                    'inst_id' => $internal_service_row["inst_id"],
                    'inst_name' => $internal_service_row["inst_name"],
                    'close_date' => $internal_service_row["time"],
                    'veh_reg' => $internal_service_row["veh_reg"],
                    'imei_no' => $internal_service_row["device_imei"],
                    'reason' => $internal_service_row["reason"],
                    'temp_to_permanent' => $internal_service_row["temp_to_permanent"],                    
                    'temp_permnt_date' => $internal_service_row["temp_permnt_date"],
                    'available_time' => $internal_service_row["atime"],
                    'name' => $internal_service_row["name"],
                    'read_unread' => $internal_service_row["read_unread_status"],
                    ) ;
            array_push($data,$arr);
        } 
    $internal_imei_rslt=substr($internal_ok,0,strlen($internal_ok)-3);    
    
  //116 -> Repaired ok device status, 30 days diffrance / DispatchRepairDevice_At_Delhi_Stock
  //62 -> Dispatch /  FinalAttachSim
  //57 -> Recevice Dispatch / OutOfStock
  //63 -> About To Assigne to Installer but Not Assigne / BranchRecd
  //82-> Internal Branch /InternalBranchRepaired
 
 $inventory_imei = mssql_query("select device.device_imei from device join device_repair on device.device_imei=device_repair.device_imei where device.device_status in (116,62,57,63,82) and dispatch_branch='".$_SESSION['BranchId']."' and device_repair.current_record=1 and datediff(dd,device_repair.closecase_date,getdate())>=30");
 
    
    $inventory_ok = "";
    while($inventory_imei_row = mssql_fetch_array($inventory_imei))
    {
        $inventory_ok.= $inventory_imei_row['device_imei']."','";
    }
    $inventory_imei_rslt=substr($inventory_ok,0,strlen($inventory_ok)-3);
    
        
    $queryRes = mysql_query("select * from (select * from internalsoftware.services where device_imei in ('".$inventory_imei_rslt."') AND device_imei NOT IN ('".$internal_imei_rslt."') and service_reinstall='service' and temp_to_permanent is null and service_status=5 AND (reason like '%Device removed%' or reason like '%Device Changed%') and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc) as services GROUP BY device_imei order by req_date DESC");
            
    while($inventory_data = mysql_fetch_array($queryRes))
        {
            
            $arr=array (
                    'id' => $inventory_data["id"],
                    'req_date'=> $inventory_data["req_date"],
                    'request_by' => $inventory_data["request_by"],
                    'company_name' => $inventory_data["company_name"],
                    'inst_id' => $inventory_data["inst_id"],
                    'inst_name' => $inventory_data["inst_name"],
                    'close_date' => $inventory_data["time"],
                    'veh_reg' => $inventory_data["veh_reg"],
                    'imei_no' => $inventory_data["device_imei"],
                    'reason' => $inventory_data["reason"],
                    'temp_to_permanent' => $inventory_data["temp_to_permanent"],                    
                    'temp_permnt_date' => $inventory_data["temp_permnt_date"],
                    'available_time' => $inventory_data["atime"],
                    'name' => $inventory_data["name"],
                    'read_unread' => $inventory_data["read_unread_status"],
                    ) ;
            array_push($data,$arr);
        } 
    
$rslt_data = $data;

/*echo "<pre>";
print_r($rslt_data);
die;*/


 /*if($_POST["submit"])
 {
    
    $selecttype= $_POST["selecttype"];
    $value= $_POST["userid"];
    
    if($selecttype=="" or $selecttype==0)
    {
         if($_SESSION['BranchId'] == 1)
         {
            $service_query = mysql_query("select * from internalsoftware.services where device_imei like'%".$value."%' and service_reinstall='service' and 
            (device_status='Permanent' or temp_to_permanent='Temporary To Permanent') and service_status=5 AND reason LIKE '%Device removed (against payment)%' order by req_date DESC");
            $total = mysql_num_rows($service_query);
            
            if($total == 0)
            {
                $queryRes = mysql_query("select * from internalsoftware.services where device_imei like'%".$value."%' and service_reinstall='service' and device_status='Temporary' and service_status=5 AND reason LIKE '%Device removed (against payment)%' order by req_date DESC LIMIT 1");
            }
         }
         else
         {
            $service_query = mysql_query("select * from internalsoftware.services where device_imei like'%".$value."%' and service_reinstall='service' and 
            (device_status='Permanent' or temp_to_permanent='Temporary To Permanent') and service_status=5 AND reason LIKE '%Device removed (against payment)%' and branch_id='".$_SESSION['BranchId']."' order by req_date DESC");
            $total = mysql_num_rows($service_query);
            
            if($total == 0)
            {
                 $queryRes = mysql_query("select * from internalsoftware.services where device_imei like'%".$value."%' and service_reinstall='service' and device_status='Temporary' and service_status=5 AND reason LIKE '%Device removed (against payment)%' and branch_id='".$_SESSION['BranchId']."' order by req_date DESC LIMIT 1");
            }
         }
        
    }
    else{
        
        $main_user_iddata=mysql_query("SELECT id as user_id, sys_username as name FROM matrix.users where sys_active=1 and sys_username='".$value."'");
        $data=mysql_fetch_assoc($main_user_iddata);
        $user_id = $data["user_id"];
        
        if($_SESSION['BranchId'] == 1)
         {
             $imei_get_query = mysql_query("SELECT DISTINCT device_imei FROM internalsoftware.services WHERE user_id='".$user_id."' and service_reinstall='service' and device_status='Temporary' and service_status=5 AND reason LIKE '%Device removed (against payment)%'");
            
            $get_id = "";
            while($imei_row = mysql_fetch_array($imei_get_query))
            {
                $imei_no = $imei_row["device_imei"];
                
                $service_query = mysql_query("select * from internalsoftware.services where device_imei like'%".$imei_no."%' and service_reinstall='service' and 
            (device_status='Permanent' or temp_to_permanent='Temporary To Permanent') and service_status=5 AND reason LIKE '%Device removed (against payment)%' order by req_date DESC");
                $total = mysql_num_rows($service_query);
                
                if($total == 0)
                {
                    $get_id_query = mysql_fetch_array(mysql_query("select * from internalsoftware.services where device_imei like'%".$imei_no."%' and service_reinstall='service' and device_status='Temporary' and service_status=5 AND reason LIKE '%Device removed (against payment)%' order by req_date DESC LIMIT 1"));

                    $get_id.= $get_id_query["id"].",";
                }
            }
            
            $user_req_id=substr($get_id,0,strlen($get_id)-1); 
            $queryRes = mysql_query("select * from internalsoftware.services where id IN ($user_req_id) order by req_date DESC");
            
         }
         else
         {        
            
            $imei_get_query = mysql_query("SELECT DISTINCT device_imei FROM internalsoftware.services WHERE user_id='".$user_id."' and service_reinstall='service' and device_status='Temporary' and service_status=5 AND reason LIKE '%Device removed (against payment)%' and branch_id='".$_SESSION['BranchId']."'");
            
            $get_id = "";
            while($imei_row = mysql_fetch_array($imei_get_query))
            {
                $imei_no = $imei_row["device_imei"];
                
                $service_query = mysql_query("select * from internalsoftware.services where device_imei like'%".$imei_no."%' and service_reinstall='service' and 
            (device_status='Permanent' or temp_to_permanent='Temporary To Permanent') and service_status=5 AND reason LIKE '%Device removed (against payment)%' and branch_id='".$_SESSION['BranchId']."' order by req_date DESC");
                $total = mysql_num_rows($service_query);
                
                if($total == 0)
                {
                    $get_id_query = mysql_fetch_array(mysql_query("select * from internalsoftware.services where device_imei like'%".$imei_no."%' and service_reinstall='service' and device_status='Temporary' and service_status=5 AND reason LIKE '%Device removed (against payment)%' and branch_id='".$_SESSION['BranchId']."' order by req_date DESC LIMIT 1"));

                    $get_id.= $get_id_query["id"].",";
                }
            }
            
            $user_req_id=substr($get_id,0,strlen($get_id)-1); 
            $queryRes = mysql_query("select * from internalsoftware.services where id IN ($user_req_id) and branch_id='".$_SESSION['BranchId']."' order by req_date DESC");
                        
         }
        
    }    
    
    
  
 }*/
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


function ConfirmToPermanent(row_id,imei)
{
  var x = confirm("Is Confirm Permanent Close?");
  if (x)
  {
  ConfirmDevice(row_id,imei);
      return ture;
  }
  else
    return false;
}

function ConfirmDevice(row_id,imei)
{
    //alert(user_id);
    //return false;
$.ajax({
        type:"GET",
        url:"userInfo.php?action=TemptoPermanentConfirmation",
        data:"row_id="+row_id+"&imei="+imei,
        success:function(msg){
         alert(msg);
         location.reload(true);        
        }
    });
}


function ReadUnread(row_id)
{
  var x = confirm("Read this Request?");
  if (x)
  {
  approve(row_id);
      return ture;
  }
  else
    return false;
}

function approve(row_id)
{
    //alert(user_id);
    //return false;
$.ajax({
        type:"GET",
        url:"userInfo.php?action=read_temp_request",
         data:"row_id="+row_id,
        success:function(msg){
        // alert(msg);
        location.reload(true);        
        }
    });
}
</script>
 

 <div class="top-bar">
                    
                    <h1>Temporary FFC</h1>
                      
                </div>
                <div class="top-bar">
                    
                 <div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font> Permanent Case</div><br/>
                 <div style="float:right";><font style="color:#CFBF7E;font-weight:bold;">Brown:</font>Unread Request</div><br/>
                 <div style="float:right";><font style="color:#68C5CA;font-weight:bold;">Blue:</font> Read Request</div>
                </div> 
            
                <div class="table">

 <!--<form method="post" action="" onsubmit="return submitme();" name="ffc_case">

    <input type="text" name="userid" id="userid" value="<?=$value?>">
    &nbsp;&nbsp;
    <select name="selecttype">
    
    <option id="0" value="0" <?php if($selecttype=="" or $selecttype==0){ ?> selected="selected" <?php } ?> >IMEI</option>
    
    <option id="1" value="1" <?php if($selecttype==1){ ?> selected="selected" <?php } ?>>Client </option>
    
     
    </select>&nbsp;&nbsp;
    <input type="submit" name="submit" value="submit">

</form>-->
   
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>Sl. No</th>
            <th>Request By </th>
            <th width="10%" align="center"><font color="#0E2C3C"><b>Company Name </b></font></th>
            <th width="10%" align="center"><font color="#0E2C3C"><b>Vehicle Number </b></font></th>
            <th>Request Date </th>
            <th width="10%" align="center"><font color="#0E2C3C"><b>Device IMEI </b></font></th>
            <th width="10%" align="center"><font color="#0E2C3C"><b>Installer</b></font></th>
            <th width="10%" align="center"><font color="#0E2C3C"><b>Reason</b></font></th>        
            <th width="9%" align="center"><font color="#0E2C3C"><b>Close date</b></font></th>
            <th>View Detail</th>
            <th width="10%" align="center"><font color="#0E2C3C"><b>Action</b></font></th>
       </tr>
    </thead>
    <tbody>
   
    <?php 
    //$i=1;
     
   for($i=0;$i<count($rslt_data);$i++)
    {
        $imei_no = str_replace("_","",$rslt_data[$i]["imei_no"]); 
    ?>  

    <tr align="Center" <? if( $rslt_data[$i]["temp_to_permanent"]!="" && $rslt_data[$i]["temp_permnt_date"]!='' ){ echo 'style="background-color:#B6B6B4"';} elseif( $rslt_data[$i]["read_unread"]==0 && $rslt_data[$i]["temp_to_permanent"]=="" && $rslt_data[$i]["temp_permnt_date"]==''){ echo 'style="background-color:#F2F5A9"';} elseif( $rslt_data[$i]["read_unread"]==1 && $rslt_data[$i]["temp_to_permanent"]=="" && $rslt_data[$i]["temp_permnt_date"]==''){ echo 'style="background-color:#68C5CA"';}?> >

    <td><?php echo $i+1; ?></td>
    <td>&nbsp;<?php echo $rslt_data[$i]['request_by'];?></td>
    <td><?php echo $rslt_data[$i]['name'];?></td> 
    <td>&nbsp;<?php echo $rslt_data[$i]['veh_reg'];?></td>
    <td>&nbsp;<?php echo $rslt_data[$i]['req_date'];?></td>
    <td>&nbsp;<?php echo $imei_no;?></td>
    <td>&nbsp;<?php echo $rslt_data[$i]['inst_name'];?></td>
    <td>&nbsp;<?php echo $rslt_data[$i]['reason'];?></td>        
    <td width="9%" align="center">&nbsp;<?php echo $rslt_data[$i]['close_date'];?></td>
    <td><a href="#" onclick="Show_record(<?php echo $rslt_data[$i]["id"];?>,'services','popup1'); " class="topopup">View Detail</a>
        <? if($rslt_data[$i]["read_unread"]==0){?>
        |<a href="#" onclick="return ReadUnread(<?php echo $rslt_data[$i]["id"];?>);" >Read</a>
        <?php } ?>
    </td>
    <td width="10%" align="center"> 
        <? if($rslt_data[$i]["temp_to_permanent"]=="" || $rslt_data[$i]["temp_to_permanent"]=='NULL'){?>
            <a href="#" onclick="return ConfirmToPermanent(<?php echo $rslt_data[$i]["id"];?>,'<?php echo $imei_no;?>');"  >Confirm To Permanent</a>
        <?php } else{?> Permanent Done <?php } ?></td>

</td>
           
    </tr>
        <?php  
    }
     
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
include("../include/footer.inc.php");

?>