<?php
error_reporting(1);
ob_start();
session_start();
 
if($_SESSION['username']=="")
{

	echo "<script>document.location.href ='".__SITE_URL."/index.php'</script>";

}
else
{
	 
}

 /*include("../connection.php");*/
 



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
		<link  href="<?php echo __SITE_URL;?>/css/admin.css" rel="stylesheet" type="text/css" />
		<title>Online Request Portal</title>
<script></script>
 
<!--  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"> </script> 
 -->		<style type="text/css" title="currentStyle">
			@import "<?php echo __SITE_URL;?>/media/css/demo_page.css";
			@import "<?php echo __SITE_URL;?>/media/css/demo_table.css";
		</style>
		<script type="text/javascript" language="javascript" src="<?php echo __SITE_URL;?>/media/js/jquery.js"></script>
			<script type="text/javascript" language="javascript" src="<?php echo __SITE_URL;?>/js/function.js"></script>
            
		<script type="text/javascript" language="javascript" src="<?php echo __SITE_URL;?>/media/js/popup.js"></script>
       <script type="text/javascript" src="<?php echo __SITE_URL;?>/js/calender/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="<?php echo __SITE_URL;?>/js/calender/jquery.ui.core.js"></script>
    <script type="text/javascript" src="<?php echo __SITE_URL;?>/js/calender/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="<?php echo __SITE_URL;?>/js/calender/jquery.ui.datepicker.js"></script>
    <script type="text/javascript" src="<?php echo __SITE_URL;?>/js/jsDateTimePickerV1/DateTimePicker.js"></script>
	
	<link type="text/css" href="<?php echo __SITE_URL;?>/css/jquery.ui.theme.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo __SITE_URL;?>/css/jquery.ui.datepicker.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo __SITE_URL;?>/css/demos.css" rel="stylesheet" />
       
        
        
	<script src="http://trackingexperts.com/collection/thickbox/jquery-latest.js" type="text/javascript"></script>
	<script src="http://trackingexperts.com/collection/thickbox/thickbox.js" type="text/javascript"></script>
    <link rel="stylesheet" href="http://trackingexperts.com/collection/thickbox/thickbox.css" type="text/css" media="projection, screen" />

	<script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#example').dataTable( {
				"iDisplayLength": 50,
                "aaSorting": [[ 7, "desc" ]]
            } );
        } );
    </script> 
    <script type="text/javascript" language="javascript" src="<?php echo __SITE_URL;?>/media/js/jquery.dataTables.js"></script>

	</head>
		<body  >

	 
		<div id="container">
 
    <div id="main">
        <div id="header">
            <a href="#" class="logo"><img src="<?php echo __SITE_URL;?>/img/logo1.png"  alt="" /></a>


		<div style="float:right;font-size:12px;padding-top:15px;color:#FF6F00;">Welcome <? echo  $_SESSION['username']?> <br/>	  <a   href="<?php echo __SITE_URL;?>/logout.php" >Logout</a> </div>
           

			  
        </div>








 