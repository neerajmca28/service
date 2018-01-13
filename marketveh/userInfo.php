<?php 
echo "ddd";die();
include("../connection.php");
//include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

$row_id=$_GET["row_id"];

if(isset($_GET['action']) && $_GET['action']=='DeviceUnmapped')
{
   
     //$query = select_query_live_con("SELECT * FROM mapped_market_vehicle WHERE id=".$row_id);

     $data1 = array('internal_status' => 5, 'unmapped_time' => date("Y-m-d H:i:s"));
	 $condition = array('id' => $row_id);
	
	 update_query_live_con('matrix.mapped_market_vehicle', $data1, $condition);
	
     echo "Comment added Successfully";
}

?> 