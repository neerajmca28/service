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
	 
	 $query = $masterObj->getDataforCsv_mumbai($userId);
	 //echo '<pre>';print_r($query);die;
			 
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
//require_once 'D:/xampp/htdocs/user/PHPExcel/Classes/PHPExcel.php';
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