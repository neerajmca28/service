<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");  
include($_SERVER["DOCUMENT_ROOT"]."/service/sqlconnection.php");*/
$mode=$_GET['mode']; 


if(isset($_POST['submit'])) 
{
	$ffc_id="";
	for ($j = 0; $j < count($_POST['ffc_check']); $j++) 
	{
		$ffc_id.= "'".$_POST['ffc_check'][$j]."',";
	}
	
	 $ffc_data=substr($ffc_id,0,strlen($ffc_id)-1);
		
	$query = mssql_query("select distinct(device.device_imei),device_repair.client_name,device.recd_date,device.device_id, DATEDIFF(MONTH, device.recd_date, GETDATE()) as age from device  join device_repair on device.device_id=device_repair.device_id where is_permanent=1 and device_repair.current_record=1 and device.device_id in($ffc_data)");
	 
	 while($rowdata = mssql_fetch_array($query))
	 {
		 
	 $device_warranty = select_query_live("SELECT sys_added_date,TIMESTAMPDIFF( DAY, sys_added_date, NOW()) as age, veh_reg,imei,sys_group_id,name FROM matrix.group_services LEFT JOIN  matrix.services ON group_services.sys_service_id=services.id LEFT JOIN matrix.`group` ON `group`.id=group_services.sys_group_id
 LEFT JOIN  matrix.devices ON services.sys_device_id =devices.id WHERE devices.imei ='".$rowdata['device_imei']."' AND sys_parent_group_id=1 AND sys_group_id!=1998 LIMIT 0,1");
 
	
	 mssql_query("insert into device_replace_ffc(old_client_name, imei_no, old_veh, ffc_date, imei_old_installtion_date) 
	 values('".$rowdata['client_name']."','".$rowdata['device_imei']."','".$device_warranty[0]['veh_reg']."','".date("Y-m-d H:i:s")."','".$device_warranty[0]['sys_added_date']."')");
	 
	 $Updateapprovestatus="update device set is_ffc='1',device_status='69' where device_id='".$rowdata['device_id']."'";
	 mssql_query($Updateapprovestatus);
	 mssql_query("update device_repair set device_status=69 where current_record=1 and device_imei='".$rowdata['device_imei']."'");
	 	 
	}
	echo "<script>document.location.href ='ffc_device_report.php'</script>";
}
?> 
<script>
 
function ConfirmFFCdevice(row_id)
{
  var x = confirm("Is FFC Device?");
  if (x)
  {
  ConfirmDevice(row_id);
      return ture;
  }
  else
    return false;
}

function ConfirmDevice(row_id)
{
	//alert(user_id);
	//return false;
$.ajax({
		type:"GET",
		url:"userInfo.php?action=ffcDeviceConfirmation",
 		data:"row_id="+row_id,
		success:function(msg){
		 
		location.reload(true);		
		}
	});
}
</script>

<form name="myform" action="" method="post">

<div class="top-bar">
		<h1>Permanent FFC : <?=$mode; ?></h1>


			<div align="right">
            	<input type="submit" name="submit" id="submit" value="Submit" />
            </div>

        
        <div style="float:center">
					  <div align="center"><br/>
	 	                <a href="ffc_device_report.php?mode=Branch_Device">Branch Device
	 	                <? $sql_pending=mssql_query("select COUNT(*) from device join device_repair on device.device_id=device_repair.device_id where is_permanent=1 and device_repair.current_record=1 and device.device_status not in(70,95,94,97,96,65,57,63,64,100) and dispatch_branch='".$_SESSION['BranchId']."'");
        
        $row_pending=mssql_fetch_array($sql_pending);?>
	 	                (
	 	                <?=$row_pending[0]?>
	 	                )</a> 
                       
                    <?php if($_SESSION['BranchId']==1){?>
                        
                        || <a href="ffc_device_report.php?mode=All_Device">All Device
	 	                <? $sql_pending=mssql_query("select COUNT(*) from device join device_repair on device.device_id=device_repair.device_id where is_permanent=1 and device_repair.current_record=1 and device.device_status not in(70,95,94,97,96,65,57,63,64,100)");
        
        $row_pending=mssql_fetch_array($sql_pending);?>
	 	                (
	 	                <?=$row_pending[0]?>
	 	                )</a>
 					<?php } ?>


                        <br/>
                      </div>
  				</div>  	 
        
</div> 
		 
		   <div class="table">

<?php
 

if($mode=='') { $mode="Branch_Device"; }

if($mode=='All_Device')
	{
		$rs = mssql_query("select distinct(device.device_imei), item_master.item_name, device_type, device_repair.client_name, device_repair.device_removed_problem, device.recd_date, device.device_id, DATEDIFF(MONTH, device.recd_date, GETDATE()) as age from device  join device_repair on device.device_id=device_repair.device_id 
  left join item_master on item_master.item_id=device.device_type where is_permanent=1 and device_repair.current_record=1 and device.device_status not in(70,95,94,97,96,65,57,63,64,100)");
	}
 	else
	{
		$rs = mssql_query("select distinct(device.device_imei), item_master.item_name, device_type, device_repair.client_name, device_repair.device_removed_problem, device.recd_date, device.device_id, DATEDIFF(MONTH, device.recd_date, GETDATE()) as age from device  join device_repair on device.device_id=device_repair.device_id 
  left join item_master on item_master.item_id=device.device_type where is_permanent=1 and device_repair.current_record=1 and device.device_status not in(70,95,94,97,96,65,57,63,64,100)and dispatch_branch='".$_SESSION['BranchId']."'");
	
	}

?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead> 
		<tr>
            <th>Sl. No</th>
            <th><font color="#0E2C3C"><b>Client Name </b></font></th>
            <th><font color="#0E2C3C"><b>Device Imei </b></font></th>
            <th><font color="#0E2C3C"><b>Device Model </b></font></th>
            <th><font color="#0E2C3C"><b>Recived Date</b></font></th>
            <th><font color="#0E2C3C"><b>Age of Device</b></font></th>
            <th><font color="#0E2C3C"><b>Reason</b></font></th>
            <th><font color="#0E2C3C"><b>FFC Status </b></font></th>
            <th><font color="#0E2C3C"><b>FFC Process </b></font></th>
       
        </tr>
	</thead>
	<tbody>

  
	<?php 
	$i=1;
    while ($row = mssql_fetch_array($rs)) { 
	
    ?>  

 	<tr align="Center"  <?=$Bgcolor;?> >
 	<td><?php echo $i ?></td>
      
        <td>&nbsp;<?php echo $row['client_name'];?></td>
		<td>&nbsp;<?php echo $row['device_imei'];?></td>
        <td>&nbsp;<?php echo $row['item_name'];?></td>
		<td>&nbsp;<?php echo $row['recd_date'];?></td> 
        <td>&nbsp;<?php echo $row['age']." Months"; ?></td>
        <td>&nbsp;<?php echo $row['device_removed_problem'];?></td>
		<?php $ffc = mssql_fetch_array(mssql_query("select is_ffc,device_status from device where device_id='".$row["device_id"]."'")); ?>
        <td>&nbsp;
        <!--<a href="#" onclick="return ConfirmFFCdevice(<?php echo $row["device_id"];?>);"  >Confirm FFC </a>-->
        <?php  if($ffc["is_ffc"]==1 && $ffc["device_status"]==69){echo "FFC Done But Pending for Replace as New/Old"; }  
               else if($ffc['device_status']==66){ echo "Device Removed" ;} 
               else if($ffc['device_status']==67){ echo "Device Removed Recd" ;} 
               else if($ffc['device_status']==68){ echo "Open Repair Case" ;}
               else    if($ffc['device_status']==71){ echo "Replace On Repair Device" ;} 
               else if($ffc['device_status']==72){ echo "Dead Device Remarks Pending" ;} 
               else if($ffc['device_status']==75){ echo "Branch Repair" ;} 
               else if($ffc['device_status']==76){ echo "Device Against Pyment With FFC" ;} 
               else if($ffc['device_status']==77){ echo "Device Against Pyment Without FFC" ;} 
               else if($ffc['device_status']==79){ echo "Send To Repair Centre" ;}
               else if($ffc['device_status']==80){ echo "UnCracked Device" ;} 
               else if($ffc['device_status']==81){ echo "Branch Repair Status" ;} 
               else if($ffc['device_status']==82){ echo "Internal Branch Repaired" ;} 
               else if($ffc['device_status']==83){ echo "Send To Repair By Branch" ;}
               else if($ffc['device_status']==84){ echo "Device Manufacture" ;} 
               else if($ffc['device_status']==85){ echo "Device Send To Manufacture" ;}
               else if($ffc['device_status']==86){ echo "Device IMEI Change by Repair/Manufacture" ;} 
               //else if($ffc['device_status']==87){ echo "Sim Dispatch" ;}
               //else if($ffc['device_status']==88){ echo "Sim Recd" ;} 
               //else if($ffc['device_status']==89){ echo "Sim Reassign" ;}
               //else if($ffc['device_status']==90){ echo "Sim Servies" ;} else if($ffc['device_status']==91){ echo "Sim Installed" ;}
               //else if($ffc['device_status']==92){ echo "Sim Deactivation" ;} else if($ffc['device_status']==93){ echo "Sim Repair" ;}      
               else if($ffc['device_status']==99){ echo "Ready For Dispatch FFC AS New" ;} 
               else if($ffc['device_status']==100){ echo "Ready For Dispatch Replace FFC" ;}
               else if($ffc['device_status']==103){ echo "Device Removed Kept In Clients Office" ;}
               else if($ffc['device_status']==104){ echo "Device Delete From Client Account" ;}
               else if($ffc['device_status']==105){ echo "Device Has been Repaired But Not Received By Stock" ;}
               else if($ffc['device_status']==116 && $ffc["is_ffc"]==0){ echo "Device Has been Repair and Deposist to Stock Delhi" ;}
               else if($ffc['device_status']==116 && $ffc["is_ffc"]==1){ echo "CHECK In FFC Device on Dispatch" ;}
               else if($ffc['device_status']==120){ echo "Missing Device In Inventory" ;}
               else if($ffc['device_status']==122){ echo "Device Has been Send But Not Received By R and D" ;}
               
        ?>
        </td>
        <td><?php if($ffc["is_ffc"]!=1 && $ffc["device_status"]==116){?>
        	<input type="checkbox" name="ffc_check[]" id="ffc_check" value="<?php echo $row["device_id"];?>" /><?php } ?>
        </td>
		
	</tr>
		<?php  
    $i++;}
	 
    ?>
    </tbody>
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
 
 </form>
 
<?
include("../include/footer.inc.php");

?>
