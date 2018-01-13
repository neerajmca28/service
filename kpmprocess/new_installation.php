<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu_kpm.php');

$userId = '77727';
$groupId = '7781';

$action=$_GET['action'];
$id=$_GET['id'];
$page=$_POST['page'];
if($action=='edit' or $action=='editp')
{
	//$result=select_query_live_con("select * from installation_request where id=$id");   
}

$assign_device = select_query_inventory("select device.device_id, device.device_imei, device.itgc_id, sim.sim_no, device.dispatch_date, 
				installerid from inventory.ChallanDetail left join inventory.device on ChallanDetail.deviceid=device.device_id left join 
				inventory.sim on device.sim_id=sim.sim_id where  device.device_status=64 and ChallanDetail.branchid=1   
				and ChallanDetail.CurrentRecord=1 and ChallanDetail.installerid=341");

$destination = select_query_live_con("select name,id from matrix.pois where sys_user_id='".$userId."' ");
//echo "<pre>";print_r($destination);die;

 function new_device_additionclose($phone_no,$dev_imei,$vehicle_no) {
        
         $query1 = select_query_live_con('SELECT * FROM matrix.devices where ffc_status=0 AND imei=$dev_imei');

         $query2 = select_query_live_con('SELECT * FROM matrix.mobile_simcards where ffc_status=0 AND mobile_no=$phone_no');         
       
      $t=0;
      if(count($query1)>0)
      {
          //print_r($query1->result_array()) ; die;
          $t=1;
          return $t;
      }
      else
      {
          if(count($query2)>0)
          {
               $t=2;
              return $t;
          }
      }
    
    }				
?>

<div class="top-bar">
<h1>New Device Addition</h1>
</div>
<div class="table">
<?php

if(isset($_POST["submit"]))
{
  echo "<pre>";print_r($_POST);die;
  
  $reqdate = date("Y-m-d H:i:s");
  $date = $_POST["date"];
  
  $customer_name = $_POST["customer_name"];
  $vehicle_no = trim($_POST["truck_no"]);
  $device_imei = trim($_POST["device_imei"]);
  $destination = $_POST["destination"];
  $warehouse = $_POST["warehouse"];
   
  $transport_name = $_POST["transport_name"];
  $transport_mob_no = $_POST["transport_mob_no"];
  $lr_number = $_POST["lr_number"];
  $driver_mobile_no = $_POST["driver_mobile_no"];
  $trip = $_POST["trip"];
  $rate = $_POST["rate"];
    
	$resultDevice_inv = select_query_inventory("select itgc_id,device_imei,sim.phone_no,device_status,item_master.item_name from 
                        inventory.device left join inventory.sim on device.sim_id =sim.sim_id left join inventory.item_master on 
                        item_master.item_id=device.device_type  where device_imei='".$device_imei."'");  
   
   
	$device_id = $resultDevice_inv[0]['itgc_id']; 
	$userId = $userId; 
	$sales_id = ''; 
	$dtms = 0;

	$phone_no = $resultDevice_inv[0]['phone_no']; 
	$dev_imei = $resultDevice_inv[0]['device_imei']; 

	$detailsCheck = new_device_additionclose($phone_no,$dev_imei,$vehicle_no);
	
	if($detailsCheck==1)
	{
			//echo "IMEI ".$dev_imei." Already Exist";
			 $error = "IMEI ".$dev_imei." Already Exist";
	}
	else
	{
		if($detailsCheck==2)
		{
				//echo "SIM ".$phone_no." Already Exist";
				$error = "SIM ".$phone_no." Already Exist";

		}
		else
		{
			$logText = "";
			$flag = 0;

			 $post_data = array(
								'group_id' => $groupId,
								'imei' =>  $dev_imei,
								'mobile_no'=> $phone_no,
								'device_id' =>  $device_id,
								'veh_reg' => $vehicle_no,
								'sales_user_id' => $sales_id,
								'device_status' => 2,
								);
			//trans_begin();
				
			$sql_req_dev= "insert into matrix.requested_device(group_id,imei,mobile_no,device_id,veh_reg,sales_user_id,device_status) values('".$userId."','".$dev_imei."','".$phone_no."','".$device_id."','".$vehicle_no."','".$sales_id."','2')";
				 
			$query_sql_req_dev = select_query_live_con($sql_req_dev);
	 
			$SELECT3 = "SELECT ffc_status FROM matrix.mobile_simcards where mobile_no='".$phone_no."' limit 1";
				 //echo $SELECT3; die;
			$query = select_query_live_con($SELECT3);
				  
			  if(count($query)>0)
				 {
					 $sql_sim_del = "delete from mobile_simcards where mobile_no='".$phone_no."'";
					 $query1 = select_query_live_con($sql_sim_del);

				 }
					$date=date('Y-m-d H:i:s'); 
					$sql="insert into matrix.mobile_simcards(network,mobile_no,created,last_updated) values(2,'".$phone_no."','".$date."','".$date."')";
					$query1 = select_query_live_con($sql);
					$sim_id = $matrix_db->insert_id();
					



					//$logText1=$date;
				 $logText1 = "--------------------------------------\nDate: ".date('Y-m-d H:i:s')."\nNotes: \n**Created**\n--------------------------------------\n";			
				 //------------------------devices--------------------//	 		
				 
				 $SELECT4="SELECT ffc_status FROM matrix.devices where imei='".$dev_imei."' limit 1";
				 $query4 = select_query_live_con($SELECT4);
				 
				 //echo count($query4); die;
				 if(count($query4)>0)
				 {
					 $sql_dev_del="delete from matrix.devices where imei='".$dev_imei."'";
					 $query1 = select_query_live_con($sql_dev_del);
					 
				 }

				$sql1="insert into devices(sys_user_id,sys_type,sys_simcard,imei,serial_no,fleet_key,heartbeat_type,odometer_offset,hours_offset,`log`,notes) values('3114','15','".$sim_id."','".$dev_imei."','".$dev_imei."','Shggg123',0,'54718','43200','".$logText1."','')";
				  
				$query1 = select_query_live_con($sql1);
				$sys_device_id = $matrix_db->insert_id();

				$SELECT_services = "SELECT sys_device_id FROM matrix.services where sys_device_id='".$sys_device_id."' limit 1";
				$query_sys_device_id = select_query_live_con($SELECT_services);

				 if(count($query_sys_device_id)>0)
				 {
						
						echo $error= "Device ".$sys_device_id." Already Exist";
				 }
				 else
				 {
					$veh_icon='http://www.trackingexperts.com/images/icons/vehicles/60x60/lorry-blue.PNG';
					$veh_type_val=1;
					$veh_type_item='Truck';
					
					$logText2 = "--------------------------------------\nDate: ".date('Y-m-d H:i:s')."\nNotes: \n**Created**\n--------------------------------------\n";	
					
					$sql2="insert into services(sys_user_id,sys_sage_reference,sys_created,sys_renewal_due,sys_renewal_cost,sys_device_id,veh_reg,veh_icon_1,veh_icon_2,veh_body,veh_chasis,veh_year,veh_seats,veh_avempg,veh_costpermile,veh_wd_auth_start,veh_wd_auth_end,veh_we_auth_start,veh_we_auth_end,veh_sun_auth_start,veh_sun_auth_end,veh_type,`log`,veh_type_name,is_dimts,Ac_lastchecked) values(3114,345,'".$date."','0001-01-01 00:00:00','0.0','".$sys_device_id."','".$vehicle_no."','".$veh_icon."','','','','','0','0.0','0.0','00:00','00:00','00:00','00:00','00:00','23:59','".$veh_type_val. "','".$logText2."','".$veh_type_item."','".$dtms."','".$date."')";

					$query = select_query_live_con($sql2);
					$serviceid = $matrix_db->insert_id();

					 //------------------------------------group_services-------------------------//

				  $sql="select sys_service_id from matrix.group_services where sys_service_id='".$serviceid."'";
				  //echo $sql; die;
				  $queryselect = select_query_live_con($sql);
				  
				  if(count($queryselect)>0)
				  {
					$errMessage="Service Id already Exist in group_services.";
					$flag=1;
				  }
				  if($flag!=1)
				  {
					$sql3="insert into group_services(sys_service_id,sys_group_id,sys_added_date) values('".$serviceid."','1998','".$date."')";
					$query = select_query_live_con($sql3);
					 
				  }

	
					$logText3=$date;
					$update_services="update services set log=CONCAT(log,'".$logText3."') where id='" .$serviceid."'";
					$query = select_query_live_con($update_services);
					

					//---------------------------------tbl_history_devices----------------------//
					 $sql4="insert into tbl_history_devices (`sys_group_id`,`sys_service_id`) values('1998','".$serviceid."')";
					$query_tbl_history_devices=select_query_live_con($sql4);
					
					if($userId=="" || $userId==0)
					{
							$flag=2;
					}
					else
					{
						 $SELECT5="select name from matrix.group where id='".$userId."'";
						 //echo $SELECT5; die;
						 $queryselect= select_query_live_con($SELECT5);
						 //$groupName=$queryselect[0]['name'];
					}
				
					
				if($userId!=1998)
				 {
					$sql6="insert into group_services(sys_service_id,sys_group_id,sys_added_date) values('".$serviceid."','".$userId."','".$date."')";
					//echo $sql6;die;
					$query_group_services=select_query_live_con($sql6);
					


					$logText4 = "--------------------------------------\nDate: ".date('Y-m-d H:i:s')."\nNotes: \n**Created**\n--------------------------------------\n";
					$update_services="update services set log=CONCAT(log,'".$logText4."') where id='" .$serviceid."'";
					//echo $update_services; die;
					$query=select_query_live_con($update_services);
						


					$sql7="insert into tbl_history_devices(`sys_group_id`,`sys_service_id`) values('".$userId."','".$serviceid."')";
					 //echo  $sql7; die;
					$query=select_query_live_con($sql7);


					 $sql8="insert into device_mapping(device_id,device_imei,sys_simcard,vehID,NewVehID,repair_with_IMEI,reason,device_placed,current_mapped_status) values('".$sys_device_id."','".$dev_imei."','".$sim_id."','".$serviceid."','','','','',1)";
					$query=select_query_live_con($sql8);
					
				   }

				  $sql="select sys_service_id from matrix.latest_telemetry where sys_service_id='".$serviceid."'";
				  $queryselect = select_query_live_con($sql);
				  
				  if(count($queryselect>0))
				  {
					$errMessage="Service Id already Exist in latest_telemetry.";
					$flag=3;
				  }
				 
				   $sql9="insert into latest_telemetry (sys_service_id,sys_msg_type,sys_proc_time,sys_proc_host,sys_geofence_id,sys_device_id,gps_time,jny_distance,jny_duration,jny_idle_time,jny_leg_code,jny_device_jny_id,des_movement_id,des_vehicle_id,tel_hours,tel_input_0,tel_input_1,tel_input_2,tel_input_3,tel_temperature,tel_voltage,tel_odometer) values('".$serviceid."','0',date_add(now(),interval -330 minute),'None','0','".$sys_device_id."',now(),'0.0','0','0','0','0','0','0','0',false,false,false,false,'0.0','0.0','0')";

				  $query_lat_tele= select_query_live_con($sql9);
					

				  $sql="select sys_service_id from matrix.lastspeed_row where sys_service_id='".$serviceid."'";
				  $queryselect= select_query_live_con($sql);
				  
				  if(count($queryselect>0))
				  {
					$errMessage="Service Id already Exist in lastspeed_row.";
					$flag=4;
				  }

					$sql10="insert into lastspeed_row(sys_service_id,sys_msg_type,sys_proc_time,sys_proc_host,sys_geofence_id,sys_device_id,gps_time,jny_distance,jny_duration,jny_idle_time,jny_leg_code,jny_device_jny_id,des_movement_id,des_vehicle_id,tel_hours,tel_input_0,tel_input_1,tel_input_2,tel_input_3,tel_temperature,tel_voltage,tel_odometer) values('".$serviceid."','0',date_add(now(),interval -330 minute),'None','0','".$sys_device_id."','".$date."','0.0','0','0','0','0','0','0','0',false,false,false,false,'0.0','0.0','0')";
					
				  $query_lastspeed_row = select_query_live_con($sql10);
	
			 }
		//-----------------------------transaction close------------------------------//
			   
			}
				
		}

	

   
   
   
    
    if($action=='edit')
	{
	   
		
	
	 
		echo "<script>document.location.href ='list_new_device_addition.php'</script>";
	}
    else
    {
         
    	$insert_data = "insert into services(sys_user_id,sys_sage_reference,sys_created,sys_renewal_due,sys_renewal_cost,sys_device_id,veh_reg,veh_icon_1,veh_icon_2,veh_body,veh_chasis,veh_year,veh_seats,veh_avempg,veh_costpermile,veh_wd_auth_start,veh_wd_auth_end,veh_we_auth_start,veh_we_auth_end,veh_sun_auth_start,veh_sun_auth_end,veh_type,`log`,veh_type_name,is_dimts,Ac_lastchecked,veh_driver_name,thana_name,veh_destination,driver_contact_no,veh_advance,veh_phone_number,card_number)";
      
     	echo "<script>document.location.href ='list_new_device_addition.php'</script>";
    }
 
}

?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script type="text/javascript">
function Check() 
{
  if(document.myForm.datepicker1.value=="")
  {
	  alert("Please Enter Date") ;
	  document.myForm.datepicker1.focus();
	  return false;
  }
  if(document.myForm.customer_name_id.value=="")
  {
	  alert("Please Enter Customer Name") ;
	  document.myForm.customer_name_id.focus();
	  return false;
  }
  if(document.myForm.truck_no_id.value=="")
  {
	  alert("Please Enter Truck No") ;
	  document.myForm.truck_no_id.focus();
	  return false;
  }
  if(document.myForm.device_imei_id.value=="")
  {
	  alert("Please Select Device IMEI") ;
	  document.myForm.device_imei_id.focus();
	  return false;
  }
  if(document.myForm.destination_id.value=="")
  {
	  alert("Please Enter Destination") ;
	  document.myForm.destination_id.focus();
	  return false;
  }
  if(document.myForm.warehouse_id.value=="")
  {
	  alert("Please Enter Ware House") ;
	  document.myForm.warehouse_id.focus();
	  return false;
  }
  if(document.myForm.transport_name_id.value=="")
  {
	  alert("Please Enter Transport Name") ;
	  document.myForm.transport_name_id.focus();
	  return false;
  }
  if(document.myForm.transport_mob_no_id.value=="")
  {
	  alert("Please Enter Contact No. ") ;
	  document.myForm.transport_mob_no_id.focus();
	  return false;
  }
  var TxtContactNumber=document.myForm.transport_mob_no_id.value;
  if(TxtContactNumber!="")
    {
    	var length=TxtContactNumber.length;
   
        if(length < 9 || length > 11 || TxtContactNumber.search(/[^0-9\-()+]/g) != -1 )
        {
			alert('Please enter valid mobile number');
			document.myForm.transport_mob_no_id.focus();
			document.myForm.transport_mob_no_id.value="";
			return false;
        }
    }
 }
</script>

<script>
var j = jQuery.noConflict();
j(function() 
{
	j( "#datepicker1" ).datepicker({ dateFormat: "yy-mm-dd" });
	
	/*j( "#ToDate" ).datepicker({ dateFormat: "yy-mm-dd" });*/

});

</script>    
<form method="POST" action="" name="myForm" onsubmit="return Check()">

    <table width="520" cellpadding="5" cellspacing="5" style=" padding-left: 100px;width: 500px;">
        <tr>
            <td width="114">Date:*</td>
            <td width="170"><input type="text" name="date" id="datepicker1" value="<?=$result[0]['date']?>" /></td>
        </tr>
        <tr>
            <td>Customer Name:*</td>
            <td><input type="text" name="customer_name" id="customer_name_id"  value="<?=$result[0]['customer_name']?>"/></td>
        </tr>
        <tr>
            <td>Truck No:*</td>
            <td><input type="text" name="truck_no" id="truck_no_id" value="<?=$result[0]['truck_no']?>"  /></td>
        </tr> 
        <tr>
            <td>Device IMEI:*</td>
            <td><select name="device_imei" id="device_imei_id" style="width:150px;">
            		<option value=''>--Select imei--</option>
                 <?php for($aid=0;$aid<count($assign_device);$aid++){?>
                    <option value="<?=$assign_device[$aid]['device_imei']?>" <? if($result[0]['device_imei']==$assign_device[$aid]['device_imei']) {?> selected="selected" <? } ?>><?=$assign_device[$aid]['device_imei']?></option>
                  <?php } ?>
            </select></td>
        </tr>        
         <tr>
            <td>Destination:*</td>
            <td><select name="destination" id="destination_id" style="width:150px;">
            		<option value=''>--Select Destination--</option>
                 <?php for($dt=0;$dt<count($destination);$dt++){?>
                    <option value="<?=$destination[$dt]['name']?>" <? if($result[0]['destination']==$destination[$dt]['name']) {?> selected="selected" <? } ?>><?=$destination[$dt]['name']?></option>
                  <?php } ?>
            </select></td>
        </tr>
        <tr>
            <td>Ware House:*</td>
            <td><input type="text" name="warehouse" id="warehouse_id" value="<?=$result[0]['warehouse']?>" /></td>
        </tr>
        <tr>
            <td>Transport Name:*</td>
            <td><input type="text" name="transport_name" id="transport_name_id" value="<?=$result[0]['transport_name']?>" /></td>
        </tr>
        <tr>
            <td>Transport Mob No:*</td>
            <td><input type="value" name="transport_mob_no" id="transport_mob_no_id" value="<?=$result[0]['transport_mob_no']?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' /></td>
        </tr>
         
         <tr>
            <td>LR Number:</td>
            <td><input type="text" name="lr_number" id="lr_number_id" value="<?=$result[0]['lr_number']?>" /></td>
        </tr>
        <tr>
            <td>Driver Mob No:</td>
            <td><input type="value" name="driver_mobile_no" id="driver_mobile_no_id" value="<?=$result[0]['driver_mobile_no']?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' /></td>
        </tr>
        <tr>
            <td>Trip Plan:</td>
            <td><select name="trip" id="trip_id" style="width:150px;">
            		<option value=''>--Select Trip Plan--</option>
                	<option value="Monthly"<? if($result[0]['trip']=='Monthly') {?> selected="selected" <? } ?>>Monthly</option>
                    <option value="Trip"<? if($result[0]['trip']=='Trip') {?> selected="selected" <? } ?>>Trip</option>
                    <option value="Fix"<? if($result[0]['trip']=='Fix') {?> selected="selected" <? } ?>>Fix</option>
                </select>
             </td>
        </tr>
        <tr>
            <td>Rate:</td>
            <td><input type="text" name="rate" id="rate_id" value="<?=$result[0]['rate']?>" /></td>
        </tr>
        
        <tr>
        <td>&nbsp;</td>
       <td class="submit">
           <input id="Button1" type="submit" name="submit" value="submit" runat="server" />
           <input type="button" name="Cancel" value="Cancel" onClick="window.location = 'list_new_device_addition.php' " /></td>
      </tr>
    </table>
  </form>
   </div>
 
<?php
include("../include/footer.php"); 
?>