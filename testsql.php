<?php

//mssql.secure_connection = On

// Need to upload ntwdblib.dll from net

 ini_set('display_errors', '1');

ob_start();

date_default_timezone_set ("Asia/Calcutta");



//$Server = '203.115.101.62'; // host/instance_name

$Server = '203.115.101.30'; // host/instance_name

$User = 'sa_inv'; // username

$Pass = 'sa_inv@123'; // paasword

$DB = 'Inventory'; // database name



//connection to the database

$dbconn = @mssql_connect($Server, $User, $Pass)

  or die("Couldn't connect to SQL Server on $Server");



//select a database to work with

$selected = mssql_select_db($DB, $dbconn)

  or die("Couldn't open database $myDB");



//declare the SQL statement that will query the database

$query = "SELECT TOP 10 * from device ";



//execute the SQL query and return records

$result = mssql_query($query);



$numRows = mssql_num_rows($result);

//echo "<h1>" . $numRows . " Row" . ($numRows == 1 ? "" : "s") . " Returned </h1>";



//display the results

while($row = mssql_fetch_array($result))

{

  echo "<br>" .$row["device_imei"] . ' ## '.$row["device_id"];

}

//close the connection

mssql_close($dbconn);



?>







 