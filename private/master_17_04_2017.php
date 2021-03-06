<?php
session_start();
 
class master
{

		
		function getUserDetails($username)
		{
			
			$this->data = select_query_live_con("select id,phone_number,company from matrix.users where sys_username='".$username."'");
			
			return $this->data;
			
		}
		
		function getAllClientDetails($all)
		{
			
			$this->data = select_query("SELECT Userid AS user_id,UserName AS `name` FROM internalsoftware.addclient order by UserName");
			
			return $this->data;
			
		}
		
		function getVimalDetails($UserId)
		{
			
			$this->data = select_query_live_con("select sys_username,id,company from matrix.users where id=(select sys_user_id from matrix.group_services join matrix.group_users on group_services.sys_group_id=group_users.sys_group_id where group_services.sys_service_id='".$UserId."' and sys_user_id not in (2143,3052,3053,3068,3070,3073,3081) limit 1)");
			
			return $this->data;
			
		}
		
		
		function getSseName($username)
		{
			
			$this->data = select_query("select telecaller from internalsoftware.users where sys_username='".$username."'");
			
			return $this->data;
			
		}
		
		
        function getdebug_data($userId,$selecttype)
        {
				
				if($selecttype=="" or $selecttype==0)
				{
				
				$this->data = select_query_live_con("select latest_telemetry.tel_rfid,latest_telemetry.gps_fix,latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweronoff,services.id,sys_created,veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact,round(gps_speed*1.609,0) as speed,  case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng ,devices.imei,services.device_removed_service from matrix.services join matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id join matrix.devices on devices.id=services.sys_device_id where services.id in (select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in ( select sys_group_id from matrix.group_users where sys_user_id=(".$userId.")))");
								
				}
				
				else if( $selecttype==2)
				{
				
				$this->data = select_query_live_con("select latest_telemetry.tel_rfid,latest_telemetry.gps_fix,latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweronoff,services.id,sys_created,veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact,round(gps_speed*1.609,0) as speed, case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng ,devices.imei,services.device_removed_service from matrix.services join matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id join matrix.devices on devices.id=services.sys_device_id  where services.id in (select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in ( select sys_group_id from matrix.group_users where sys_user_id=(".$userId."))) and services.device_removed_service=1 and adddate(latest_telemetry.gps_time,INTERVAL 19800 second)< adddate(now(),interval -24 hour)");
				
				//$pagesize=500;  
				}
				
				else
				{

				$this->data = select_query_live_con("select latest_telemetry.tel_rfid,latest_telemetry.gps_fix,latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweronoff,services.id,sys_created,veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact,round(gps_speed*1.609,0) as speed,  case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng ,devices.imei,services.device_removed_service from matrix.services    join matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id join matrix.devices on devices.id=services.sys_device_id  where services.id in   (select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in ( select sys_group_id from matrix.group_users where sys_user_id IN (".$userId."))) and adddate(latest_telemetry.gps_time,INTERVAL 19800 second)< adddate(NOW(),interval -24 hour)");
				
				
				}


                return $this->data;

         }
		 

        function ImeiSearchDetails($userId)
		{
			
			$this->data = select_query_live_con("select services.id,veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact, 
										round(gps_speed*1.609,0) as speed, round(gps_orientation,1) as bearing,  
										case when tel_ignition=true then true else false end as aconoff , geo_street as street, geo_town as town,
										geo_country as country,veh_reg as reg, latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng ,
										devices.imei,mobile_simcards.mobile_no from matrix.services
										join matrix.latest_telemetry on latest_telemetry.sys_service_id=matrix.services.id
										join matrix.devices on devices.id=services.sys_device_id
										join matrix.mobile_simcards on mobile_simcards.id=devices.sys_simcard
										 where services.id in 
										(select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (
										select sys_group_id from matrix.group_users where sys_user_id=(".$userId.")))");
			
			return $this->data;
			
		}
		 
		function getDeviceId($TxtImei)
		{
			
			$this->data = select_query_live_con("select id,sys_simcard from matrix.devices where imei='".$TxtImei."'");
			
			return $this->data;
			
		}


		function getVehicleNo($deviceId)
		{
			
			$this->data = select_query_live_con('select id,log,veh_reg from matrix.services where sys_device_id =('.$deviceId.')');
			
			return $this->data;
			
		}
		
		function getNotWorkingVeh($userId)
		{
			
			$this->data = select_query_live_con("select latest_telemetry.gps_fix,latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweronoff,
			 services.id,sys_created,veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact,round(gps_speed*1.609,0) as speed,  
			 case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng ,
			 devices.imei,services.device_removed_service from matrix.services join matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id 
			 join matrix.devices on devices.id=services.sys_device_id  where services.id in (select distinct sys_service_id from matrix.group_services where active=true and 
			 sys_group_id in ( select sys_group_id from matrix.group_users where sys_user_id=(".$userId."))) and 
			 adddate(latest_telemetry.gps_time,INTERVAL 19800 second)< adddate(now(),interval -2 hour) GROUP BY lastcontact DESC");
						
			return $this->data;
			
		}
		
		function getServiceComment($service_id)
		{
			
			$this->data = select_query_live_con("select comment,comment_by,date from matrix.comment where service_id='".$service_id."' order by date desc");
			
			return $this->data;
			
		}
		
		function getUserVehicleVal($client_rslt)
		{
			
			$this->data = select_query_live_con("SELECT group_users.sys_user_id,COUNT(*) AS veh_total FROM matrix.group_users LEFT JOIN matrix.group_services ON 
	group_users.sys_group_id=group_services.sys_group_id WHERE sys_user_id IN ($client_rslt) GROUP BY sys_user_id");
			
			return $this->data;
			
		}
		
		function getVehicleDetail($user_Id)
		{
			
			$this->data = select_query_live_con("select services.id,veh_reg from matrix.services where services.id in (select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (select sys_group_id from matrix.group_users where sys_user_id=(".$user_Id.")))");
			
			return $this->data;
			
		}
		
		function getCompanyName($user_Id)
		{
			
			$this->data = select_query_live_con("select `group`.name as company from matrix.group_users left join matrix.`group` on group_users.sys_group_id=`group`.id where group_users.sys_user_id=".$user_Id);
			
			return $this->data;
			
		}
		
		function getCreationDate($id)
		{
			
			$this->data = select_query_live_con("select sys_added_date from matrix.users where id=".$id);
			
			return $this->data;
			
		}
		
		function getDeviceImei($veh_reg)
		{
			
			$this->data = select_query_live_con("select imei from matrix.devices where id in (select sys_device_id from matrix.services where veh_reg='".$veh_reg."') limit 1");
			
			return $this->data;
			
		}
		
		function getDeviceMobile($veh_reg)
		{
			
			$this->data = select_query_live_con("select mobile_no from matrix.mobile_simcards where id in ( select sys_simcard from matrix.devices where id in (select sys_device_id from matrix.services where veh_reg='".$veh_reg."'))");
			
			return $this->data;
			
		}
		
		function getDeviceInstaltiondate($veh_reg)
		{
			
			$this->data = select_query_live_con("select sys_created from matrix.services where veh_reg='".$veh_reg."' limit 1");
			
			return $this->data;
			
		}
		
		function getDeviceNotwokingdate($veh_reg)
		{
			
			$this->data = select_query_live_con("select  ADDDATE(gps_time, INTERVAL -330 MINUTE) as notworkingDate from matrix.latest_telemetry where sys_service_id in (select id from matrix.services where veh_reg='".$veh_reg."' )");
			
			return $this->data;
			
		}
		
		function getVehicleId($veh_reg)
		{
			
			$this->data = select_query_live_con("select id from matrix.services where veh_reg='".$veh_reg."' ");
			
			return $this->data;
			
		}
		
		function getClientDevice($UserId)
		{
			
			$this->data = select_query_live_con("select latest_telemetry.gps_fix,latest_telemetry.tel_voltage,latest_telemetry.tel_poweralert,services.id as id,services.id,sys_created,veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact,round(gps_speed*1.609,0) as speed, case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat,tbl_history_devices.sys_group_id,
latest_telemetry.gps_longitude as lng ,devices.imei,services.device_removed_service from matrix.services join matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id join matrix.devices on devices.id=services.sys_device_id join matrix.mobile_simcards on mobile_simcards.id=devices.sys_simcard left join matrix.tbl_history_devices on tbl_history_devices.sys_service_id=services.id where services.id in (select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in ( select sys_group_id from matrix.group_users where sys_user_id=(".$UserId.")))  and tbl_history_devices.sys_group_id!=1998");
			
			return $this->data;
			
		}
		
		function getDataforCsv($UserId)
		{
			
			$this->data = select_query_live_con("select latest_telemetry.gps_fix, latest_telemetry.tel_voltage, latest_telemetry.tel_poweralert,services.id, sys_created, veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact, round(gps_speed*1.609,0) as speed, case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat,tbl_history_devices.sys_group_id,
latest_telemetry.gps_longitude as lng, devices.imei,services.device_removed_service from matrix.services join matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id join matrix.devices on devices.id=services.sys_device_id join matrix.mobile_simcards on mobile_simcards.id=devices.sys_simcard left join matrix.tbl_history_devices on tbl_history_devices.sys_service_id=services.id where services.id in (select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in ( select sys_group_id from matrix.group_users where sys_user_id=(".$UserId.")))  and tbl_history_devices.sys_group_id!=1998 and adddate(latest_telemetry.gps_time,INTERVAL 19800 second)<'".date('Y-m-d')."' order by lastcontact DESC");
			
			return $this->data;
			
		}
		
		function getDataforCsv_mumbai($UserId)
		{
			$today_date = date('Y-m-d');
			$yest_date = date('Y-m-d', strtotime('-2 day', strtotime($today_date)));
									
			/* $this->data = select_query_live_con("select latest_telemetry.gps_fix, latest_telemetry.tel_voltage, latest_telemetry.tel_poweralert,services.id, sys_created, veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact, round(gps_speed*1.609,0) as speed, case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat,tbl_history_devices.sys_group_id,
latest_telemetry.gps_longitude as lng, devices.imei,services.device_removed_service from matrix.services join matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id join matrix.devices on devices.id=services.sys_device_id join matrix.mobile_simcards on mobile_simcards.id=devices.sys_simcard left join matrix.tbl_history_devices on tbl_history_devices.sys_service_id=services.id where services.id in (select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in ( select sys_group_id from matrix.group_users where sys_user_id=(".$UserId.")))  and tbl_history_devices.sys_group_id!=1998 and adddate(latest_telemetry.gps_time,INTERVAL 19800 second)<'".$yest_date." ' order by lastcontact DESC"); 
 */

 $this->data = select_query_live_con("select latest_telemetry.gps_fix, latest_telemetry.tel_voltage, latest_telemetry.tel_poweralert,services.id, sys_created, veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact, round(gps_speed*1.609,0) as speed, case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng, devices.imei,services.device_removed_service from matrix.services join matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id

												join matrix.devices on devices.id=services.sys_device_id

												join matrix.mobile_simcards on mobile_simcards.id=devices.sys_simcard

												 where services.id in 
(select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (select sys_group_id from matrix.group_users where sys_user_id=".$UserId.")) and adddate(latest_telemetry.gps_time,INTERVAL 19800 second)< adddate(now(),interval -2 hour) and latest_telemetry.gps_time<='".$yest_date." 18:30:00'");
 
			
			return $this->data;
			
		}

}

?>