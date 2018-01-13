<?php
ob_start();
ini_set('max_execution_time', 50000);
include("D:/xampp/htdocs/service/connection.php");
include_once(__DOCUMENT_ROOT.'/private/master.php');

$masterObj = new master();

$from_date = date('Y-m-d', strtotime('-1 month'));
$to_date = date("Y-m-d", strtotime('-1 day'));

    select_query("TRUNCATE TABLE internalsoftware.client_notworking_vehicle_testing;");

/*$Update_query = array('is_active' => 0);
$condition = array('is_active' => 1);        
update_query('internalsoftware.client_notworking_vehicle', $Update_query, $condition);*/

$assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
                               sys_active='1' order by Branch_id,client_type");

/*$assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
                    branch_id='1' and sys_active='1' and telecaller_id='4'  order by client_type");*/


    for($ac=0;$ac<count($assign_client);$ac++)
    {
        $client_id.= $assign_client[$ac]['Userid'].",";
    }
    
    $client_rslt = substr($client_id,0,strlen($client_id)-1);
        
    $data = $masterObj->getdebug_data($client_rslt,1);
   
    for($i=0;$i<count($data);$i++)
    {
            $lastcontact = $data[$i]['lastcontact'];
            $device_removed = $data[$i]['device_removed_service'];
            $tel_voltage = $data[$i]['tel_voltage'];
            $gps_fix = $data[$i]['gps_fix'];
			if($data[$i]['poweronoff']=="")
		{
            $poweronoff = 0;
		}
		else
		{
			  $poweronoff = $data[$i]['poweronoff'];
		}
            
if($data[$i]['tel_ignition']=="")
		{
            $tel_ignition =0;
		}
		else
		{
			  $tel_ignition = $data[$i]['tel_ignition'];
		}
            $veh_no = $data[$i]['veh_reg'];
            $imei_no = $data[$i]['imei']; 
            $sys_created = $data[$i]["sys_created"];
            $gps_speed = $data[$i]['speed'];
            $gps_latitude = $data[$i]['lat'];
            $gps_longitude = $data[$i]['lng'];
            $sys_service_id = $data[$i]['id'];

            $vimal_data = $masterObj->getVimalDetails($sys_service_id); 
			//print_r($vimal_data);
             $userName = $vimal_data[0]['sys_username'];
              $Userid = $vimal_data[0]['id'];
            $companyName = $vimal_data[0]['company'];
			if($data[$i]['tel_voltage'] == ''){ $tel_voltage = '0';}
   else{ $tel_voltage = $data[$i]['tel_voltage'];}
        
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
			 
                
            echo $Insert_msg = insert_query1('internalsoftware.client_notworking_vehicle_testing', $Notworking_request);
			echo "<br/>";
             //die();    
    }
    
    echo "Data Insert Successfully.";
     
   

	function insert_query1($table_name, $form_data)
{
   
	global $dblink2;
	$hostname2 = "203.115.101.124";
	$username2 = "internal_soft";
	$password2 = "123456";
	$databasename2 = "internalsoftware";
	
	$dblink2 = @mysql_connect($hostname2,$username2,$password2) ;

    // retrieve the keys of the array (column titles)
    $fields = array_keys($form_data);
 
    // build the query
      $sql = "INSERT INTO ".$table_name."
    (`".implode('`,`', $fields)."`)
    VALUES('".implode("','", $form_data)."');";
 
    // run and return the query result resource
	$insert = mysql_query($sql,$dblink2);
    return $sql;
}
        
?>