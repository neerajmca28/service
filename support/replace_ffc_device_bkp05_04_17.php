<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); 
include($_SERVER["DOCUMENT_ROOT"]."/service/sqlconnection.php");*/

?>
 
        <div class="top-bar">
        <h1>Replace FFC</h1>
        </div>

         <div class="top-bar">
                   
            <div style="float:right";><font style="color:#F6F;font-weight:bold;">Purple:</font> Open Device</div>
            <br/>
            <div style="float:right";><font style="color:#CCCCCC;font-weight:bold;">Grey:</font> Closed Case</div>             
<br/>
            <div style="float:right";><font style="color:red;font-weight:bold;">Red:</font> Dead device </div>             

            </div>
           <div class="table">

<?php
if($_POST["submit"])
{
   
    $selecttype= $_POST["selecttype"];
    $value= $_POST["userid"];
   
    if($selecttype=="" or $selecttype==0)
    {
        $rs = mssql_query("select id,device_repair.client_name ,veh_no,device.device_imei,opencase_date,closecase_date,device_removed_problem, problem,actual_problem, spare_cost, device.device_status,device_repair.device_removed_date,device_repair.ManufactureRemarks from device left join device_repair on device.device_id=device_repair.device_id where current_record=1 and device.device_status in(68,79,84,85,86) and device_repair.device_imei='".$value."'");
       
    }
    elseif($selecttype==1)
    {
        $rs = mssql_query("select id,device_repair.client_name ,veh_no,device.device_imei,opencase_date,closecase_date,device_removed_problem, problem,actual_problem, spare_cost, device.device_status,device_repair.device_removed_date,device_repair.ManufactureRemarks from device left join device_repair on device.device_id=device_repair.device_id where  current_record=1 and device.device_status in(68,79,84,85,86) and device_repair.client_name LIKE '%".$value."%'");
    }
    else
    {
        $rs = mssql_query("select id,device_repair.client_name ,veh_no,device.device_imei,opencase_date,closecase_date,device_removed_problem, problem,actual_problem, spare_cost, device.device_status,device_repair.device_removed_date,device_repair.ManufactureRemarks from device left join device_repair on device.device_id=device_repair.device_id where  current_record=1 and device.device_status in(68,79,84,85,86)");
    }
 
}
 
?>
<form method="post" action="" onsubmit="return submitme();" name="repair">

    <input type="text" name="userid" id="userid" value="<?=$value?>">
    &nbsp;&nbsp;
    <select name="selecttype">
   
    <option id="0" value="0" <?php if($selecttype=="" or $selecttype==0){ ?> selected="selected" <?php } ?> >IMEI</option>
   
    <option id="1" value="1" <?php if($selecttype==1){ ?> selected="selected" <?php } ?>>Client </option>
   
    <option id="2" value="2" <?php if($selecttype==2){ ?> selected="selected" <?php } ?>>Device </option>
    
    </select>&nbsp;&nbsp;
   
    <input type="submit" name="submit" value="submit">

 
</form>
<!--<div style="float:right"><a href="show_repairs.php?mode=new">Open Device</a> | <a href="show_repairs.php?mode=dead">Dead device</a> | <a href="show_repairs.php?mode=close">Closed Device</a></div>-->

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
       <!-- <th>Sl. No</th>-->
        <th><font color="#0E2C3C"><b>Client Name </b></font></th>
        <th><font color="#0E2C3C"><b>Vehicle No</b></font></th>
        <th><font color="#0E2C3C"><b>Device Imei </b></font></th>
        <th><font color="#0E2C3C"><b>Device Add Date</b></font></th>
        <th><font color="#0E2C3C"><b>Device Remove date</b></font></th>
        <th><font color="#0E2C3C"><b>Device Open date</b></font></th>
        <th><font color="#0E2C3C"><b>Open Reason</b></font></th>
        <th><font color="#0E2C3C"><b>Close Reason</b></font></th>
        <th><font color="#0E2C3C"><b>Manufacture Remarks</b></font></th>
        <th><font color="#0E2C3C"><b>Spare Cost</b></font></th>
        <th><font color="#0E2C3C"><b>Warranty</b></font></th>
        <th><font color="#0E2C3C"><b>Status</b></font></th>
        <th><font color="#0E2C3C"><b>Replace on repair</b></font></th>
      <!--    <th>View Detail</th> -->
       
        
        </tr>
    </thead>
    <tbody>

 
    <?php
    $i=1;
    while($row = mssql_fetch_array($rs)) {
    
	$device_warranty = select_query_live("select group_services.sys_added_date,TIMESTAMPDIFF( month, sys_added_date, NOW()) as age from matrix.services left join matrix.group_services on services.id=group_services.sys_service_id where veh_reg='".$row['veh_no']."' limit 1");
	
    //$device_warranty = mysql_fetch_assoc($device_query);

    ?> 

     <tr align="Center"  <?=$Bgcolor;?> >
    <!-- <td><?php echo $i ?></td>-->
     
        <td>&nbsp;<?php echo $row['client_name'];?></td>
        <td>&nbsp;<?php echo $row['veh_no'];?></td>
        <td>&nbsp;<?php echo $row['device_imei'];?></td>
        <td>&nbsp;<?php echo $device_warranty[0]['sys_added_date'] ;?></td>
        <td>&nbsp;<?php echo $row['device_removed_date'];?></td>
        <td>&nbsp;<?php echo $row['opencase_date'];?></td>       
        <td>&nbsp;<?php echo $row['problem'];?></td>
        <td>&nbsp;<?php echo $row['actual_problem']; ?></td>
        <td>&nbsp;<?php echo $row['ManufactureRemarks']; ?></td>
        <td>&nbsp;<?php echo $row['spare_cost'];?></td>
        <td>&nbsp;<?php  $days = $device_warranty[0]['age'] ; if($days < 12 && $days >=0){ echo "Warranty" ;} else{ echo "Out of Warranty";}?></td>
        <td>&nbsp;<?php if($row['device_status']==20){ echo "Raw Inventory" ;} if($row['device_status']==55){ echo "Installed" ;} 
        if($row['device_status']==56){ echo "Ready to Ship" ;} if($row['device_status']==57){ echo "Out Of Stock" ;} 
        if($row['device_status']==58){ echo "Tested" ;} if($row['device_status']==60){ echo "Configure" ;} 
        if($row['device_status']==61){ echo "Temprary Attachment" ;} if($row['device_status']==62){ echo "Final Attach Sim" ;} 
        if($row['device_status']==63){ echo "Branch Recd" ;} if($row['device_status']==64){ echo "Assign To Installer" ;} 
        if($row['device_status']==65){ echo "Device Installed" ;} if($row['device_status']==66){ echo "Device Removed" ;} 
        if($row['device_status']==67){ echo "Removed Device Recd" ;} if($row['device_status']==68){ echo "Open Case For Repaired Device" ;}
        if($row['device_status']==69){ echo "FFC Device Pending" ;} if($row['device_status']==70){ echo "Dead Device" ;}
        if($row['device_status']==71){ echo "Replace On Repair Device" ;} if($row['device_status']==72){ echo "Dead Device Remarks Pending" ;} 
        if($row['device_status']==75){ echo "Branch Repair" ;} if($row['device_status']==76){ echo "Device Against Pyment With FFC" ;} 
        if($row['device_status']==77){ echo "Device Against Pyment Without FFC" ;} if($row['device_status']==79){ echo "Send To Repair Centre" ;}
        if($row['device_status']==80){ echo "UnCracked Device" ;} if($row['device_status']==81){ echo "Recd Remove Device" ;} 
        if($row['device_status']==82){ echo "Internal Branch Repaired" ;} if($row['device_status']==83){ echo "Send To Repair By Branch" ;}
        if($row['device_status']==84){ echo "Device Manufacture" ;} if($row['device_status']==85){ echo "Device Manufacture send" ;}
        if($row['device_status']==86){ echo "Device Replaced By Manufactured" ;} if($row['device_status']==87){ echo "Sim Dispatch" ;}
        if($row['device_status']==88){ echo "Sim Recd" ;} if($row['device_status']==89){ echo "Sim Reassign" ;}
        if($row['device_status']==90){ echo "Sim Servies" ;} if($row['device_status']==91){ echo "Sim Installed" ;}
        if($row['device_status']==92){ echo "Sim Deactivation" ;} if($row['device_status']==93){ echo "Sim Repair" ;}        
        if($row['device_status']==94){ echo "Assign Dead Device" ;} if($row['device_status']==95){ echo "Dead Device To Client" ;}
        if($row['device_status']==96){ echo "ReAssign Dead Device" ;} if($row['device_status']==97){ echo "Recd Dead Device" ;} 
        if($row['device_status']==99){ echo "FFC AS New" ;} if($row['device_status']==100){ echo "Replace FFC" ;}
        if($row['device_status']==102){ echo "Very Old SIM" ;} if($row['device_status']==103){ echo "Client Office" ;}
        if($row['device_status']==104){ echo "Device Delete Account" ;} 
		//if($row['device_status']==105){ echo "Close Case Repair Device" ;}} 
        if($row['device_status']==106){ echo "Sim is Pending for Deactivation" ;} if($row['device_status']==108){ echo "Archiev Dead List" ;}
        if($row['device_status']==109){ echo "Send To Repair Centre Manufacture" ;} if($row['device_status']==120){ echo "Missing Device In Inventory" ;}
        if($row['device_status']==116){ echo "Recd Repair Device Stock" ;}
     ?></td>
         <!-- <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'services','popup1'); " class="topopup">View Detail</a>
</td> -->
         <td><a href="replaceonrepair.php?rowid=<?=$row['id'];?>&action=edit&edit=true">Replace On Repair</a>  </td>
      
       
    </tr>
        <?php 
    $i++; }
     
    ?>
</table>
    
   <div id="toPopup">
       
        <div class="close">close</div>
           <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
        <div id="popup1" style ="height:100%;width:100%"> 

 
        </div> 
   
    </div> 
   
    <div class="loader"></div>
       <div id="backgroundPopup"></div>
</div>
 
 
 
<?
include("../include/footer.inc.php");

?>




 