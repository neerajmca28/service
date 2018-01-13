<?php
ob_start();
include($_SERVER["DOCUMENT_ROOT"]."/inc/connect.php");

$keyword = $_POST['data'];
	 $sql = "SELECT id,name,gps_radius,gps_longitude  FROM pois where sys_user_id='4679' and name like '".$keyword."%' ORDER BY name asc";
	//$sql = "select name from ".$db_table."";
	$result = mysql_query($sql) or die(mysql_error());

	
	if(mysql_num_rows($result))
	{
		echo '<ul class="list">';
		while($row = mysql_fetch_array($result))
		{
			$str = $row['name'];
			$start = strpos($str,$keyword); 
			$end   = similar_text($str,$keyword); 
			$last = substr($str,$end,strlen($str));
			$first = substr($str,$start,$end);
			
			$final = '<span class="bold">'.$first.'</span>'.$last;
		
			echo '<li><a href=\'javascript:void(0);\'>'.$final.'</a></li>';
		}
		echo "</ul>";
	}
	else
	{
		echo 0;
	}
	 
		
?>