<?php
ob_start();
ini_set('max_execution_time', 50000);
include("D:/xampp/htdocs/service/connection.php");
include_once(__DOCUMENT_ROOT.'/private/master.php');

$masterObj = new master();

$from_date = date('Y-m-d', strtotime('-1 month'));
$to_date = date("Y-m-d", strtotime('-1 day'));

select_query("TRUNCATE TABLE internalsoftware.client_notworking_vehicle;");

$assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
                               sys_active='1' and sys_parent_user='1' and Userid!='3988' order by Branch_id,client_type");

/*$assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
                    branch_id='1' and sys_active='1' and sys_parent_user='1' and telecaller_id='3'  order by client_type");*/


    for($ac=0;$ac<count($assign_client);$ac++)
    {
        $client_id.= $assign_client[$ac]['Userid'].",";
    }
    
    $client_rslt = substr($client_id,0,strlen($client_id)-1);
        
    //$data = $masterObj->getdebug_data($client_rslt,1);
    $data = select_query_live_con("select latest_telemetry.gps_fix,latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweronoff,services.id,sys_created,veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact,round(gps_speed*1.609,0) as speed,  case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng ,devices.imei,services.device_removed_service from matrix.services join matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id join matrix.devices on devices.id=services.sys_device_id  where services.id in (select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in ( select sys_group_id from matrix.group_users where sys_user_id IN (".$client_rslt."))) and adddate(latest_telemetry.gps_time,INTERVAL 19800 second)< adddate(NOW(),interval -24 hour)");
    
    //echo "<pre>";print_r($data);die;
    
    for($i=0;$i<count($data);$i++)
    {
            $lastcontact = $data[$i]['lastcontact'];
            $device_removed = $data[$i]['device_removed_service'];

            if($data[$i]['tel_voltage'] == ''){ $tel_voltage = '0';}
            else{ $tel_voltage = $data[$i]['tel_voltage'];}

            $gps_fix = $data[$i]['gps_fix'];
            //$poweronoff = $data[$i]['poweronoff'];
            //$tel_ignition = $data[$i]['aconoff'];

			if($data[$i]['poweronoff'] == " ")
			{
				$poweronoff = 0;
			}
			else
			{
				  $poweronoff = $data[$i]['poweronoff'];
			}
            
			if($data[$i]['aconoff'] == " ")
			{
				$tel_ignition = 0;
			}
			else
			{
				$tel_ignition = $data[$i]['aconoff'];
			}


            $veh_no = $data[$i]['veh_reg'];
            $imei_no = $data[$i]['imei']; 
            $sys_created = $data[$i]["sys_created"];
            $gps_speed = $data[$i]['speed'];
            $gps_latitude = $data[$i]['lat'];
            $gps_longitude = $data[$i]['lng'];
            $sys_service_id = $data[$i]['id'];

            //$vimal_data = $masterObj->getVimalDetails($sys_service_id); 
            $vimal_data = select_query_live_con("select sys_username,id,company from matrix.users where id=(select sys_user_id from matrix.group_services join matrix.group_users on group_services.sys_group_id=group_users.sys_group_id where group_services.sys_service_id='".$sys_service_id."' and sys_user_id not in (2143,3052,3053,3068,3070,3073,3081) limit 1)");
            
            $userName = $vimal_data[0]['sys_username'];
            $Userid = $vimal_data[0]['id'];
            $companyName = $vimal_data[0]['company'];
        
            $imei_service = str_replace("_","",$imei_no); 
            
        
            $vehicle_service = select_query("select veh_reg from services where veh_reg='".trim($data[$i]['veh_reg'])."' and service_status='5' 
                                            and atime>='".$from_date." 00:00:00' and atime<='".$to_date." 23:59:59' ");
            $vehicle_service_total = count($vehicle_service);
            
            $device_service = select_query("select device_imei from services where device_imei='".trim($imei_service)."' and service_status='5' 
                                            and atime>='".$from_date." 00:00:00' and atime<='".$to_date." 23:59:59' ");
            $device_service_total = count($device_service);        
            
            
            $Notworking_request = array('user_id' => $Userid, 'sys_service_id' => $sys_service_id, 'user_name' => $userName, 
                                    'company_name' => $companyName, 'veh_no' => $veh_no, 'device_imei' => $imei_no, 
                                    'date_of_installation' => $sys_created, 'notwoking' => $lastcontact, 'gps_latitude' => $gps_latitude, 
                                    'gps_longitude' => $gps_longitude, 'gps_speed' => $gps_speed, 'tel_voltage' => $tel_voltage, 
                                    'device_removed_service' => $device_removed, 'gps_fix' => $gps_fix, 'tel_poweralert' => $poweronoff,
                                    'tel_ignition' => $tel_ignition, 'vehicle_service' => $vehicle_service_total, 
                                    'device_service' => $device_service_total);
                
             $Insert_msg = insert_query('internalsoftware.client_notworking_vehicle', $Notworking_request);
                 
    }
    
    echo "Data Insert Successfully.";

    
?>