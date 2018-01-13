<?php
session_start();
/*define('__DOCUMENT_ROOT', '/var/chroot/home/content/03/5296103/html/service/');

//$con=mysql_connect("localhost","root","") or die("Connecting to MySQL failed");
$con=mysql_connect("gtracservicedb.db.5296103.hostedresource.com","gtracservicedb","eevPriyambada0") or die("Connecting to MySQL failed");
mysql_select_db("gtracservicedb");  

*/
//define('__DOCUMENT_ROOT', '	/Applications/XAMPP/xamppfiles/htdocs');

 //$con=mysql_connect("localhost","root","") or die("Connecting to MySQL failed");
//$con=mysql_connect("gtracservicedb.db.5296103.hostedresource.com","gtracservicedb","eevPriyambada0") or die("Connecting to MySQL failed");
//mysql_select_db("gtracservicedb"); 

 

date_default_timezone_set ("Asia/Calcutta");

/*$hostname = "localhost";
$username = "format";
$password = "123456";
$databasename = "collectiondb";*/


$hostname = "localhost";
$username = "global";
$password = "123456";
$databasename = "gtracservicedb";

//$dblink = mysql_connect($hostname,$username,$password) ;

$dblink = mysql_connect($hostname,$username,$password) ;


@mysql_select_db($databasename,$dblink) or die("error");

 function select_query($query,$condition=0){
 
			if($condition==1){
				//echo "<br>".$query."<br>";
			}
		$qry=@mysql_query($query);  
		 
		  $num=@mysql_num_rows($qry);
		$num_field=@mysql_num_fields($qry);
		for($i=0;$i<$num_field;$i++)
		{
		$fname[]=@mysql_field_name($qry,$i);
		}
		for($i=0;$i<$num;$i++){
		$result=mysql_fetch_array($qry);
		foreach($fname as $key => $value ) {
			$arr[$i][$value]=$result[$value];
			}
		}


		return $arr;
}
 


?>