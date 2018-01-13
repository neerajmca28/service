<?php
include("../connection.php");

if(isset($_GET['action']) && $_GET['action']=='ChkInstallerName')
{
$no=$_GET["RowId"];

$sql="SELECT inst_id,inst_name FROM installer WHERE inst_name='$no'";
$data = mysql_num_rows(mysql_query($sql));
if($data){
	echo "This User Name Allready Exist";
}
echo $msg;
}
?>