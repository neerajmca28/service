<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/private/master.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/connection.php");
include($_SERVER['DOCUMENT_ROOT']."/service/private/master.php");*/

$masterObj = new master();
  

if($_GET["csv"]=="true")
{ 
	 $userId=$_GET["id"];
	 $name = $_GET["name"];

	 $MergevehicleArray=array();
	 
	 $query = $masterObj->getDataforCsv($userId);
	 
  /*$qrycsv="select services.id as id,services.id,sys_created,veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact,round(gps_speed*1.609,0) as speed,  case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng ,devices.imei,services.device_removed_service from matrix.services 	join matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id join matrix.devices on devices.id=services.sys_device_id 	join matrix.mobile_simcards on mobile_simcards.id=devices.sys_simcard  where services.id in  	(select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in ( select sys_group_id from matrix.group_users where sys_user_id=(".$userId."))) and adddate(latest_telemetry.gps_time,INTERVAL 19800 second)< adddate(now(),interval -2 hour) and adddate(latest_telemetry.gps_time,INTERVAL 19800 second)<'".date('Y-m-d')."' order by lastcontact DESC"; 
			
			$query = mysql_query($qrycsv,$dblink);*/
			
			//while($row=mysql_fetch_array($query))
			 
			for($j=0;$j<count($query);$j++)
				{ 

				
				 
				 $Commentquery = select_query_live_con("select comment,comment_by,date from matrix.comment where service_id=".$query[$j]['id']." order by date desc");
				//$qrycom_data = mysql_query($qrycom,$dblink);
				 //$Commentquery = mysql_fetch_array($qrycom_data);
				
				$vehicleArray=array (
					'vehReg' => $query[$j]['veh_reg'],
					'lastcontact' => $query[$j]['lastcontact'],
					'servicedate' => $Commentquery[0][9],
					'availability' => $Commentquery[0][9],
					'client' => $Commentquery[0][9],
					'itgt' => $Commentquery[0]['comment']);
				array_push($MergevehicleArray,$vehicleArray);
				}
		

 
 error_reporting(E_ALL); 
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

//if (PHP_SAPI == 'cli')
	//die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once 'D:/xampp/htdocs/service/excel_lib/PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


// Add some data
//$ShowDat=date('d-M-Y',$date);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Daily vehicle status ') 	;
			
			
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'Name: '.$name);
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', 'Date:'.date('Y-m-d'));
					     			
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', ' Vehicle No.')
            ->setCellValue('B4', 'Date of not working')
			->setCellValue('C4', 'Date of service')
			->setCellValue('D4', 'Availability')
			->setCellValue('E4', 'Client Comment')
			->setCellValue('F4', 'ITGT Comment');
 
 $i=5;            
 foreach($MergevehicleArray as $row)
	{

if($row['vehReg']!="")
		{
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,$row['vehReg'])
			->setCellValue('B'.$i, $row['lastcontact'])
			->setCellValue('C'.$i, $row['servicedate'])
			->setCellValue('D'.$i, $row['availability'])
			->setCellValue('E'.$i, $row['client']) 
			->setCellValue('F'.$i, $row['itgt']) ;
			  
			$i++;
	}
	}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
/*header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');*/

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(__DOCUMENT_ROOT.'/servicecalling/excel/'.$userId.'-'.date('Y-m-d').'.xls');

  

 header("location:"."excel/".$userId."-".date('Y-m-d').".xls");
}
?>