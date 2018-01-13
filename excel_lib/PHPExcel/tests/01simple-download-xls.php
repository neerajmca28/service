<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2012 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2012 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.8, 2012-10-12
 */

/** Error reporting */
$data=array();
$arr=array (
'id' => '24480' ,
'veh_reg'=> 'HR38LD7437' ,
'veh_driver_name' => "Spare", 
'updated' => '2013-02-25', 
'location' => 'delhi', 
'speed' => 0, 
'distance_today' => 282.0, 
'distance_this_month' => '1000') ;
array_push($data,$arr);

$arr=array (
'id' => '24480' ,
'veh_reg'=> 'HR38LD7432' ,
'veh_driver_name' => "Spare1", 
'updated' => '2013-02-25', 
'location' => 'netaji subhash palace delhi', 
'speed' => 23, 
'distance_today' => '45' ,
'distance_this_month' => '450') ;
array_push($data,$arr);

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once '../Classes/PHPExcel.php';


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


$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Sl. No')
            ->setCellValue('B1', 'Vehicle Reg. No!')
            ->setCellValue('C1', 'Driver Name')
            ->setCellValue('D1', 'Updated!')
			->setCellValue('E1', 'Location')
			->setCellValue('F1', 'Speed(Km/h)')
			->setCellValue('G1', 'Distance Travelled Today(KM)')
			->setCellValue('H1', 'Distance this month(KM)')	;

// Miscellaneous glyphs, UTF-8
$i=2;
foreach($data as $row)
	{
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $row['id'])
			->setCellValue('B'.$i, $row['veh_reg'])
			->setCellValue('C'.$i, $row['veh_driver_name'])
			->setCellValue('D'.$i, $row['updated'])
			->setCellValue('E'.$i, $row['location'])
			->setCellValue('F'.$i, $row['speed'])
			->setCellValue('G'.$i, $row['distance_today'])
			->setCellValue('H'.$i, $row['distance_this_month']);
			$i++;
	}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
