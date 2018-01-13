<?php
ob_start();
ini_set('max_execution_time', 50000);
include("C:/xampp/htdocs/service/connection.php");
//include_once(__DOCUMENT_ROOT.'/private/master.php');

	$data = select_query_live_con("select latest_telemetry.des_movement_id as port, veh_reg, 
			adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact, mobile_simcards.mobile_no, devices.imei from matrix.services
            join matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id
            join matrix.devices on devices.id=services.sys_device_id
            join matrix.mobile_simcards on mobile_simcards.id=devices.sys_simcard where  
			latest_telemetry.des_movement_id >15050 and latest_telemetry.des_movement_id <15090
            and adddate(latest_telemetry.gps_time,INTERVAL 19800 second)< adddate(now(),interval -2 hour) and 
			adddate(latest_telemetry.gps_time,INTERVAL 19800 second)> adddate(now(),interval -600 hour)");
    
    //echo "<pre>";print_r($data);die;
    
    for($i=0;$i<count($data);$i++)
	/*for($i=0;$i<1;$i++)*/
    {
            $lastcontact = $data[$i]['lastcontact'];
            $port = trim($data[$i]['port']);
            $veh_reg = $data[$i]['veh_reg'];
            $MobileNum = trim($data[$i]['mobile_no']); 
			$imei_no = $data[$i]['imei'];
	        
			/*$MobileNum = '8527050111,9971805001';*/
			
			$MSG = array(0 => "PARAM#");
			
			$ch = curl_init();
			$user="gary@itglobalconsulting.com:itgc@123";
			$receipientno=$MobileNum;
			$senderID="GTRACK";
			$msgtxt=$MSG[0];
			curl_setopt($ch,CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
			$buffer = curl_exec($ch);
			/*if(empty ($buffer))
			{ echo " buffer is empty "; }
			else
			{ echo $buffer; echo "Successfully Sent"; }*/
			curl_close($ch);
			  
    }
	
	sleep(20);

	for($i=0;$i<count($data);$i++)
	/*for($i=0;$i<1;$i++)*/
    {
            $lastcontact = $data[$i]['lastcontact'];
            $port = trim($data[$i]['port']);
            $veh_reg = $data[$i]['veh_reg'];
            $MobileNum = trim($data[$i]['mobile_no']); 
			$imei_no = $data[$i]['imei'];
	        
			/*$MobileNum = '8527050111,9971805001';*/
			
			$chk_data = select_query_inventory("select phone_no,operator from sim where phone_no='".$MobileNum."' ");
			
			if($chk_data[0]['operator'] == 'Airtel')
			{
				$MSG = array(0 => "PARAM#", 1 => "APN,AIRTELGPRS.COM#", 2 => "SERVER,1,WWW.TRACKINGEXPERTS.COM,".$port.",0#", 3 => "GMT,E,0,0#");
			}
			else if($chk_data[0]['operator'] == 'Vodafone')
			{
				$MSG = array(0 => "PARAM#", 1 => "APN,IOT.COM#", 2 => "SERVER,1,WWW.TRACKINGEXPERTS.COM,".$port.",0#", 3 => "GMT,E,0,0#");
			}
			else
			{
				$MSG = array(0 => "PARAM#", 1 => "APN,AIRTELGPRS.COM#", 2 => "SERVER,1,WWW.TRACKINGEXPERTS.COM,".$port.",0#", 3 => "GMT,E,0,0#");
			}
			
			
			$ch = curl_init();
			$user="gary@itglobalconsulting.com:itgc@123";
			$receipientno=$MobileNum;
			$senderID="GTRACK";
			$msgtxt=$MSG[1];
			curl_setopt($ch,CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
			$buffer = curl_exec($ch);
			/*if(empty ($buffer))
			{ echo " buffer is empty "; }
			else
			{ echo $buffer; echo "Successfully Sent"; }*/
			curl_close($ch);
                 
    }
	
	sleep(20);
	
	for($i=0;$i<count($data);$i++)
    /*for($i=0;$i<1;$i++)*/
	{
            $lastcontact = $data[$i]['lastcontact'];
            $port = trim($data[$i]['port']);
            $veh_reg = $data[$i]['veh_reg'];
            $MobileNum = trim($data[$i]['mobile_no']); 
			$imei_no = $data[$i]['imei'];
			
			/*$MobileNum = '8527050111,9971805001';*/
	        
			$MSG = array(0 => "PARAM#", 1 => "APN,AIRTELGPRS.COM#", 2 => "SERVER,1,WWW.TRACKINGEXPERTS.COM,".$port.",0#", 3 => "GMT,E,0,0#");
		
			$ch = curl_init();
			$user="gary@itglobalconsulting.com:itgc@123";
			$receipientno=$MobileNum;
			$senderID="GTRACK";
			$msgtxt=$MSG[2];
			curl_setopt($ch,CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
			$buffer = curl_exec($ch);
			/*if(empty ($buffer))
			{ echo " buffer is empty "; }
			else
			{ echo $buffer; echo "Successfully Sent"; }*/
			curl_close($ch);
			                   
    }
	
	sleep(20);
	
	for($i=0;$i<count($data);$i++)
    /*for($i=0;$i<1;$i++)*/
	{
            $lastcontact = $data[$i]['lastcontact'];
            $port = trim($data[$i]['port']);
            $veh_reg = $data[$i]['veh_reg'];
            $MobileNum = trim($data[$i]['mobile_no']); 
			$imei_no = $data[$i]['imei'];
	        
			/*$MobileNum = '8527050111,9971805001';*/
			
			
			$MSG = array(0 => "PARAM#", 1 => "APN,AIRTELGPRS.COM#", 2 => "SERVER,1,WWW.TRACKINGEXPERTS.COM,".$port.",0#", 3 => "GMT,E,0,0#");
		
			$ch = curl_init();
			$user="gary@itglobalconsulting.com:itgc@123";
			$receipientno=$MobileNum;
			$senderID="GTRACK";
			$msgtxt=$MSG[3];
			curl_setopt($ch,CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
			$buffer = curl_exec($ch);
			/*if(empty ($buffer))
			{ echo " buffer is empty "; }
			else
			{ echo $buffer; echo "Successfully Sent"; }*/
			curl_close($ch);
                 
    }
    
    echo "Command Successfully process.";

    
?>