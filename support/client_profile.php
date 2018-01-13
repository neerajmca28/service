<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
//include_once(__DOCUMENT_ROOT.'/private/master.php');


//$masterObj = new master();

$mode=$_GET['mode']; 
$user_id=$_GET['id'];

 if($_POST["submit"] == "submit")
 {
    $user_id = $_POST['user_id'];
 }
   $group_user_id = select_query_live_con("SELECT sys_group_id FROM matrix.group_users where sys_user_id='".$user_id."'");
    
    $group_id =  $group_user_id[0]['sys_group_id'];
    
    $active_query = select_query_live_con("SELECT sys_service_id,sys_added_date FROM matrix.group_services WHERE active=1 AND sys_group_id='".$group_id."'");
        
    $deactive_query = select_query_live_con("SELECT sys_service_id,sys_added_date FROM matrix.group_services WHERE active=0 AND sys_group_id='".$group_id."'");
    
    
    $veh_id_get = "";
    for($dt=0;$dt<count($deactive_query);$dt++)
    {
        $veh_id_get.= $deactive_query[$dt]['sys_service_id']."','";
    }
    $veh_id_data=substr($veh_id_get,0,strlen($veh_id_get)-3);
    
    $device_get_query = select_query_live_con("SELECT id,sys_device_id FROM matrix.services WHERE id IN ('".$veh_id_data."')");
    
    $sys_device_id = "";
    for($dg=0;$dg<count($device_get_query);$dg++)
    {
        $sys_device_id.= $device_get_query[$dg]['sys_device_id']."','";
    }
    $sys_device_id_data=substr($sys_device_id,0,strlen($sys_device_id)-3);
    
    $imei_query = select_query_live_con("SELECT device_imei FROM matrix.device_mapping WHERE device_id IN ('".$sys_device_id_data."')");  
    
    $imei_no_row = "";
    for($im=0;$im<count($imei_query);$im++)
    {
        $no_of_imei = str_replace("_","",$imei_query[$im]['device_imei']);
        $imei_no_row.= $no_of_imei."','";
    }
    $imei_no_data=substr($imei_no_row,0,strlen($imei_no_row)-3);  
    
    
    $active_total = count($active_query);
    
    $deactive_total = count($deactive_query);
    
    $total_device =  $active_total + $deactive_total;

?> 


<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
var j = jQuery.noConflict();

function Show_record(Veh_Reg,tablename,DivId)
{
    alert(Veh_Reg);
    //return false;
$.ajax({
        type:"GET",
        url:"userInfo.php?action=getrowSales",
        data:"Veh_Reg="+Veh_Reg+"&tablename="+tablename,
        success:function(msg){
        alert(msg);    
          
        document.getElementById("popup1").innerHTML = msg;
                        
        }
    });
}

</script>
 

<div class="top-bar">
    <?php  if($mode == "active"){$page_status = "Currently Activate";}
           elseif($mode == "deactivate"){$page_status = "Deactivate Device";}
           elseif($mode == "pending"){$page_status = "G-Trac Pending";}
           elseif($mode == "notexistence"){$page_status = "Device Not In Existence";}
           else{$page_status = "Total IMEI Buy";}
    ?>
    <h1>Client Vehicle List:- <?php echo $page_status;?></h1>
      
</div>

<div class="top-bar">
<form name="myForm" action=""   method="post">

<table cellspacing="5" cellpadding="5">

    <tr>
        <td><select name="user_id" id="user_id">
                <option value="" name="user_id">-- Select One --</option>
                <?php
                $main_user_id = select_query_live_con("SELECT id as user_id, sys_username as name FROM matrix.users where sys_active=1 and sys_parent_user=1 order by name asc");
                for($k=0;$k<count($main_user_id);$k++) 
                {
                ?>
               <option name="main_user_id" value="<?=$main_user_id[$k]['user_id']?>" <? if($user_id==$main_user_id[$k]['user_id']) {?> selected="selected" <? } ?> > 
                <?php echo $main_user_id[$k]['name']; ?>
              </option>
                <?php     }  ?>
            </select>
        </td>
        <td align="center"> <input type="submit" name="submit" value="submit"  /></td>
    </tr>
 
</table>
</form>


<div style="float:right";> <a href="client_profile.php?mode=totaldevice&id=<?=$user_id;?>">Total IMEI Buy - <?=$total_device;?></a></div>
<br/>
<div style="float:right";> <a href="client_profile.php?mode=active&id=<?=$user_id;?>">Currently Activate - <?=$active_total;?> </a></div>              
<br/>
<div style="float:right";> <a href="client_profile.php?mode=deactivate&id=<?=$user_id;?>">Deactivate Device - <?=$deactive_total;?> </a></div>              
<br/>
<div style="float:right";><a href="client_profile.php?mode=pending&id=<?=$user_id;?>">G-Trac Pending - <? $gtrac_query = select_query("SELECT count(*) as total FROM internalsoftware.deletion where user_id='".$user_id."' AND imei in ('".$imei_no_data."') AND final_status=1 AND ((device_status IN('Sold Vehicle','Vehicle Stand For Long Time','Stop GPS') and vehicle_location IN('gtrack office','client office')) or (device_status IN('Device Lost','Device Dead') and vehicle_location IN('gtrack office')) or  (vehicle_location IN('gtrack office','client office') and device_status is null))"); echo $gtrac_query[0]["total"];?> </a></div>              
<br/>
<div style="float:right";><a href="client_profile.php?mode=notexistence&id=<?=$user_id;?>">Device Not In Existence - <? $device_no_query = select_query("SELECT count(*) as total FROM internalsoftware.deletion where user_id='".$user_id."' AND imei in ('".$imei_no_data."') AND final_status=1 AND ((device_status NOT IN('Sold Vehicle','Vehicle Stand For Long Time','Stop GPS') and vehicle_location IN('device lost','client vehicle')) or (device_status IN('Device Lost','Device Dead') and vehicle_location IN('client office')) or (vehicle_location IN('device lost','client vehicle') and device_status is null))"); echo $device_no_query[0]["total"];?> </a></div>              
<br/>
<div style="float:right";>No Information - <?php $remain = $deactive_total -($gtrac_query[0]["total"] + $device_no_query[0]["total"]); if($remain >=0){ echo $remain;}else{echo 0;}?> </div>              
<br/>

</div>

<div class="table">
<?php 
 
if($mode=='pending')
 {                 
       $query = select_query("SELECT * FROM internalsoftware.deletion where user_id='".$user_id."' AND imei in ('".$imei_no_data."') AND final_status=1 AND ((device_status IN('Sold Vehicle','Vehicle Stand For Long Time','Stop GPS') and vehicle_location IN('gtrack office','client office')) or (device_status IN('Device Lost','Device Dead') and vehicle_location IN('gtrack office')) or  (vehicle_location IN('gtrack office','client office') and device_status is null)) order by id DESC");
  
 } 
 elseif($mode=='notexistence')
 { 
       $query = select_query("SELECT * FROM internalsoftware.deletion where user_id='".$user_id."' AND imei in ('".$imei_no_data."') AND final_status=1 AND ((device_status NOT IN('Sold Vehicle','Vehicle Stand For Long Time','Stop GPS') and vehicle_location IN('device lost','client vehicle')) or (device_status IN('Device Lost','Device Dead') and vehicle_location IN('client office')) or (vehicle_location IN('device lost','client vehicle') and device_status is null)) order by id DESC");
  
 } 
 elseif($mode=='active')
 { 
        $query = select_query_live_con("SELECT sys_service_id,sys_added_date FROM matrix.group_services WHERE active=1 AND sys_group_id='".$group_id."'");
 } 
 elseif($mode=='deactivate')
 { 
        $query = select_query_live_con("SELECT sys_service_id,sys_added_date FROM matrix.group_services WHERE active=0 AND sys_group_id='".$group_id."'");
 } 
 else
 { 
        $query = select_query_live_con("SELECT sys_service_id,sys_added_date FROM matrix.group_services WHERE sys_group_id='".$group_id."'");
 } 
 


?>
   
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
    <?php if($mode=='pending' || $mode=='notexistence'){?>
          <tr>
            <th>SL.No</th>
            <th>Date</th>
            <th>User Name</th>
            <th>Vehicle Number</th>
            <th>Device IMEI</th>
            <th>Device Model</th>
            <th>Location</th>
            <th>Device Status</th>
            <th>Reason</th> 
        </tr>
   <?php } else{?>
       <tr>
            <th>Sl No. </th>
            <th>IMEI No </th>
            <th>Vehicle No </th>
            <th>Vehicle ID </th>
            <th>Installed Date</th> 
            <th>Remove Date</th> 
            <th>Status</th> 
            <th>Current Location</th>
            <th>Device Status</th>
            <th>View Reason</th>      
        </tr>
    <?php } ?> 
    </thead>
    <tbody>
   
    <?php 
    
    for($i=0;$i<count($query);$i++) 
    {
        
     if($mode=='pending' || $mode=='notexistence'){
    ?>
    <tr align="Center" >
        <td><?php echo $i+1;?></td>
        <td><?php echo $query[$i]["date"];?></td>
        <td><?php echo $query[$i]["client"];?></td>
        <td><?php echo $query[$i]["reg_no"];?></td> 
        <td><?php echo $query[$i]["imei"];?></td> 
        <td><?php echo $query[$i]["device_model"];?></td> 
        <td><?php echo $query[$i]["vehicle_location"];?></td> 
        <td><?php echo $query[$i]["device_status"];?></td>
        <td><?php if($query[$i]['final_status'] == 1){$row_status = "(Close Request)";}else{$row_status = "(Pending Request)";} echo $query[$i]["reason"].' '.$row_status;?></td> 
    </tr>
    <?php }
     else
     {
        $veh_id = $query[$i]['sys_service_id'];
        $add_date = $query[$i]['sys_added_date'];
        
        $remove_history = select_query_live_con("SELECT remove_date FROM matrix.tbl_history_devices WHERE sys_service_id='".$veh_id."' AND sys_group_id!=1998");
        $remove_date = $remove_history[0]['remove_date'];
        
        $veh_query = select_query_live_con("SELECT id,sys_user_id,sys_created,sys_renewal_due,sys_device_id,veh_reg,veh_type_name FROM matrix.services WHERE id='".$veh_id."'");
        $veh_no = $veh_query[0]['veh_reg'];
        $device_id = $veh_query[0]['sys_device_id'];
        
        $device_query = select_query_live_con("SELECT device_imei FROM matrix.device_mapping WHERE device_id='".$device_id."'");     
    ?>  

    <tr align="Center" >
        <td><?php echo $i+1; ?></td>        
        <td>&nbsp;<?php echo $device_query[0]['device_imei'];?></td>
        <td>&nbsp;<?php echo $veh_no;?></td>
        <td>&nbsp;<?php echo $veh_id;?></td>
         <td>&nbsp;<?php echo $add_date;?></td>
        <td>&nbsp;<?php echo $remove_date;?></td>
        <td>&nbsp;<?php if($remove_history[0]['remove_date'] != ""){echo "Deactivate";}else{echo "Activate";}?></td>
        <?php 
            $delete_query = select_query("SELECT * FROM internalsoftware.deletion where reg_no='".$veh_no."' and user_id='".$user_id."'");
            
            $delete_veh_reason ="";$status = "";$location="";$device_status="";
           for($dl=0;$dl<count($delete_query);$dl++)
            {
                if($delete_query[$dl]['final_status'] == 1)
                {
                    $status = "(Close Request)";
                }
                else{
                    $status = "(Pending Request)";    
                }
                
                $delete_veh_reason.= $delete_query[$dl]['reason'].' '.$status.",";
                $location = $delete_query[$dl]["vehicle_location"];
                $device_status = $delete_query[$dl]["device_status"];
            }
                $delete_veh_rslt=substr($delete_veh_reason,0,strlen($delete_veh_reason)-1);
        ?>
        <td><?php echo $location;?></td> 
        <td><?php echo $device_status;?></td>
        <td><?php echo $delete_veh_rslt;?></td>     
        <!--<td><a href="#" onclick="Show_record(<?php echo $veh_no;?>,'deletion','popup1'); " class="topopup">View Detail</a></td> -->    
    </tr>
  <?php  }
    
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
include("../include/footer.php");

?>