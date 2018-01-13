<?php
error_reporting(0);
ob_start();
session_start();
 
if($_SESSION['username']=="")
{

	echo "<script>document.location.href ='http://trackingexperts.com/service/index.php'</script>";
	//header('Location: http://trackingexperts.com/format/index.php');

}
else
{
	 
}
 
include ("config.php");

$sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE (pending='1' or newpending='1') and (pending_closed='0')");
$row_pending=mysql_fetch_array($sql_pending);

 if(isset($_POST['logout']))
 {
 session_destroy();
 header("location:index.php");
 }
 
 
 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico" />
		<link  href="http://trackingexperts.com/service/css/admin.css" rel="stylesheet" type="text/css" />
		<title>Online Request Portal</title>
<script></script>
 
<!--  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"> </script> 
 -->		<style type="text/css" title="currentStyle">
			@import "http://trackingexperts.com/service/media/css/demo_page.css";
			@import "http://trackingexperts.com/service/media/css/demo_table.css";
		</style>
		<script type="text/javascript" language="javascript" src="http://trackingexperts.com/service/media/js/jquery.js"></script>
			<script type="text/javascript" language="javascript" src="http://trackingexperts.com/service/js/function.js"></script>
            
		<script type="text/javascript" language="javascript" src="http://trackingexperts.com/service/media/js/popup.js"></script>
		<script type="text/javascript" language="javascript" src="http://trackingexperts.com/service/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable( {
					"iDisplayLength": 50,
					"aaSorting": [[ 4, "desc" ]]
				} );
			} );
		</script> 

	</head>
		<body  >

	 
		<div id="container">
 
    <div id="main">
        <div id="header">
            <a href="#" class="logo"><img src="http://trackingexperts.com/service/img/logo.gif" width="101" height="29" alt="" /></a>


		<div style="float:right;font-size:12px;padding-top:15px;color:#FF6F00;">Welcome <? echo  $_SESSION['user_name']?> <br/>	<a   href="http://trackingexperts.com/service/logout.php" >Logout</a></div>
            <ul id="top-navigation">
                <li><a href="#" class="active">Homepage</a></li>
                 
            </ul>

			  
        </div>








 