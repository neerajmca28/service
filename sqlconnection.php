<?php 
/*ob_start();
date_default_timezone_set ("Asia/Calcutta");
 
 
 
$Server = "203.115.101.30";
$User = "sa_inv";
$Pass = "sa_inv@123";
//$DB = "Inventory";

$DB = "Inventory";

//connection to the database
$dbconn = @mssql_connect($Server, $User, $Pass)
  or die("Couldn't connect to SQL Server on $Server");

//select a database to work with
$selected = mssql_select_db($DB, $dbconn)
  or die("Couldn't open database $myDB");

//declare the SQL statement that will query the database
$query = "SELECT * from device ";

//execute the SQL query and return records
$result = mssql_query($query);

$numRows = mssql_num_rows($result);
//echo "<h1>" . $numRows . " Row" . ($numRows == 1 ? "" : "s") . " Returned </h1>";

//display the results
while($row = mssql_fetch_array($result))
{
 // echo "<br>" . $row["device_imei"];
}
//close the connection
mssql_close($dbconn);*/
?> 
 