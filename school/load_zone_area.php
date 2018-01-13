<?php
session_start();
include("../connection.php");

/*include($_SERVER['DOCUMENT_ROOT']."/service/connection.php");*/

$keyword = $_POST['data'];
$city_id = $_POST['city_id'];
$sql = "SELECT id,`name` FROM re_city_spr_1 WHERE `name` LIKE '".$keyword."%' and region_code='".$city_id."' ORDER BY `name` ASC LIMIT 15";
	//echo $sql; die;
	$result = mysql_query($sql);
	if(mysql_num_rows($result))
	{
		echo '<ul class="list">';
		while($row = mysql_fetch_array($result))
		{
			$str = strtolower($row['name']);
			$start = strpos($str,$keyword); 
			$end   = similar_text($str,$keyword); 
			$last = substr($str,$end,strlen($str));
			$first = substr($str,$start,$end);
			
			$final = strtoupper('<span class="bold">'.$first.'</span>'.$last);
		
			echo '<li><a href=\'javascript:void(0);\'>'.$final.'</a></li>';
		}
		echo "</ul>";
	}
	else
		echo 0;
		
?>