<?php
ob_start();
session_start();
include ("config.php");

$sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE (pending='1' or newpending='1') and (pending_closed='0')");
$row_pending=mysql_fetch_array($sql_pending);

 if(isset($_POST['logout']))
 {
 session_destroy();
 header("location:index.php");
 }
 
 
 if($_SESSION['username']=="")
	{
	echo "<script> location.href='index.php' </script>";
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Cleint Support</title>
    <link rel="stylesheet" href="./styles/main.css" media="screen">
    <link rel="stylesheet" href="./styles/colors.css" media="screen">
    
    <script type="text/javascript" src="js/sorting/jquery-1.5.1.js"></script>
	<script type="text/javascript" src="js/sorting/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="js/sorting/jquery.tablesorter.pager.js"></script>
	
    
   <link href="css/soring_style.css" rel="stylesheet" type="text/css" /> 

</head>
<body>
<div id="container">
    <div id="header" align="right">
	<p><form method="post">
	<? $username=$_SESSION['username']; echo "welcome to $username" ?>	
	<input type="submit" name="logout" value="logout" align="right"></p>
	</form>
        <a id="logo" href="index.php" title="Support Center"><img src="./images/logo2.jpg" border=0 alt="Support Center"></a>
        <p><span>CLIENT SUPPORT</span> SYSTEM</p>
		
    </div>
	<? $username=$_SESSION['username'];?>
    <ul id="nav">
         <? 
		 
	   switch($username){
	   
	   case 'swati':
	                     
         ?>
    
		 <li><a   href="pendingservice.php">Pending Service<? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE (pending='1' or newpending='1') and (pending_closed='0') and branch_id=1");
		 
$row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
         <li><a   href="service_request.php">Add Service</a></li>
		 <!--<li><a   href="services.php">Services</a></li>-->
         <li><a class="home" href="services.php">Services</a></li>
         <li><a   href="pendinginstallation.php">Pending Intallation<? $sql_pending=mysql_query("SELECT COUNT(*) FROM installation WHERE (pending='1' or newpending='1')  and (pending_closed='0') and branch_id=1");
		 
$row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
         <li><a  href="add_installation.php">Add Installtion</a></li>
         <li><a href="installation.php">Installtion</a></li>
          <!--<li><a href="replace_device.php">Replace Device</a></li>-->
          <!-- <li><a class="home" href="#">Installtion</a></li>-->
		 
<?       break;
}  


	   switch($username){
	   
	   case 'mumbai':
	                     
         ?>
    
		 <li><a   href="pendingservice.php">Pending Service<? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE (pending='1' or newpending='1') and (pending_closed='0') and branch_id=2");
		 
$row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
         <li><a   href="service_request.php">Add Service</a></li>
		 <!--<li><a   href="services.php">Services</a></li>-->
         <li><a class="home" href="services.php">Services</a></li>
         <li><a   href="pendinginstallation.php">Pending Intallation<? $sql_pending=mysql_query("SELECT COUNT(*) FROM installation WHERE (pending='1' or newpending='1')  and (pending_closed='0') and branch_id=2");
		 
$row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
         <li><a  href="add_installation.php">Add Installtion</a></li>
         <li><a href="installation.php">Installtion</a></li>

		          <li><a href="DownloadBill.php">Download Bill</a></li>

          <!--<li><a href="replace_device.php">Replace Device</a></li>-->
          <!-- <li><a class="home" href="#">Installtion</a></li>-->
		 
<?       break;
} 

switch($username){
	   
	   case 'jaipur':
	                     
         ?>
    
		 <li><a   href="pendingservice.php">Pending Service<? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE (pending='1' or newpending='1') and (pending_closed='0') and  branch_id=".$_SESSION['branch_id']);
		 
$row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
         <li><a   href="service_request.php">Add Service</a></li>
		 <!--<li><a   href="services.php">Services</a></li>-->
         <li><a class="home" href="services.php">Services</a></li>
         <li><a   href="pendinginstallation.php">Pending Intallation<? $sql_pending=mysql_query("SELECT COUNT(*) FROM installation WHERE (pending='1' or newpending='1')  and (pending_closed='0') and branch_id=2");
		 
$row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
         <li><a  href="add_installation.php">Add Installtion</a></li>
         <li><a href="installation.php">Installtion</a></li>
		  <li><a href="DownloadBill.php">Download Bill</a></li>
          <!--<li><a href="replace_device.php">Replace Device</a></li>-->
          <!-- <li><a class="home" href="#">Installtion</a></li>-->
		 
<?       break;
}

	    switch($username){
	   case 'praveen':
	                     
         ?>
 
			<li><a href="add_bill.php">Add Bill</a></li>
			<!--<li><a href="replace_device.php">Replace Device</a></li>-->
			<!-- <li><a class="home" href="#">Installtion</a></li>-->
			<li><a href="billupload.php">Upload Bill</a></li>
		 
		 
<?       break;
 
		}





	   switch($username){
	   case 'radhika':
	                     
         ?>
   
		<!-- <li><a   href="newpending.php">Pending Services<? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE newpending='1'");
                 $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
		 
         <li><img src="new.gif"><a   href="newservice.php">New Services<? $sql_status="SELECT COUNT(*) FROM services WHERE status='1'";
                                         $result_status=mysql_query($sql_status);
                                         $row_service=mysql_fetch_array($result_status);?>(<?=$row_service[0]?>)</a></li>-->
		 <!--<li><a   href="services_from_sales.php">Services from sales</a></li>-->
         <!--<li><a class="home" href="services_from_sales.php">Services</a></li>
         <li><a class="home" href="#" onClick="toggle('adv')">Services Stats</a></li>
         <br/><br/>-->
           
		   <li><a   href="servicelist.php">servicelist</a></li>
        
         
         <br/><br/>
         
<?       break;
}?>

	<?   switch($username){
	   case 'stock':
	                     
         ?>
    
      <li ><a  class="home" href="show_repairs.php">Repairs</a></li>
		    
         <br/><br/>
         
<?       break;
}?>
<? 
	   switch($username){
	   case 'gurmeet':
	                     
         ?>
     <!--<li><a   href="newpending.php">Radhika Services<? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE newpending='1'");
                 $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>-->
		 
         <li><img src="new.gif"><a   href="newservice.php">New Services<? $sql_status="SELECT COUNT(*) FROM services WHERE status='1' and branch_id=1";
                                         $result_status=mysql_query($sql_status);
                                         $row_service=mysql_fetch_array($result_status);?>(<?=$row_service[0]?>)</a></li>
		<!-- <li><a   href="">Pending Service<? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE pending='1'");
                 $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>-->
		 
         <!--<li><a   href="newservice2.php">New Services<? $sql_status="SELECT COUNT(*) FROM services WHERE newstatus='1'  and inst_name!='' and inst_cur_location!=''";
                            $result_status=mysql_query($sql_status);
                        $row=mysql_fetch_array($result_status);?>(<?=$row[0]?>)</a></li>-->
		 <!--<li><a   href="newinstallation.php">New Installation</a></li>-->
         <li><a class="home" href="newinstallation.php">Services</a></li>
         
         <li><img src="new.gif"><a   href="new_installation.php">New Installtion<? $sql_status="SELECT COUNT(*) FROM installation WHERE status='1' and branch_id=1 ";
                                         $result_status=mysql_query($sql_status);
                                         $row_service=mysql_fetch_array($result_status);?>(<?=$row_service[0]?>)</a></li>
                                         
                                          <li><a class="home" href="installations.php">Installation</a></li>
                                           <li><a class="home" href="stocks.php?mode=in">Stock In</a></li>
                                            <li><a class="home" href="stocks.php?mode=out">Stock Out</a></li>
                                            <li><a   href="add_device.php">Add Device</a></li>
                                             <li><a   href="repair_device.php">Device</a></li>
		 
<?       break;
}?>


<? 
	   switch($username){
	   case 'mumbai1':
	                     
         ?>
     <!--<li><a   href="newpending.php">Radhika Services<? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE newpending='1'");
                 $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>-->
		 
         <li><img src="new.gif"><a   href="newservice.php">New Services<? $sql_status="SELECT COUNT(*) FROM services WHERE status='1' and branch_id=2 ";
                                         $result_status=mysql_query($sql_status);
                                         $row_service=mysql_fetch_array($result_status);?>(<?=$row_service[0]?>)</a></li>
		<!-- <li><a   href="">Pending Service<? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE pending='1'");
                 $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>-->
		 
         <!--<li><a   href="newservice2.php">New Services<? $sql_status="SELECT COUNT(*) FROM services WHERE newstatus='1'  and inst_name!='' and inst_cur_location!=''";
                            $result_status=mysql_query($sql_status);
                        $row=mysql_fetch_array($result_status);?>(<?=$row[0]?>)</a></li>-->
		 <!--<li><a   href="newinstallation.php">New Installation</a></li>-->
         <li><a class="home" href="newinstallation.php">Services</a></li>
         
         <li><img src="new.gif"><a   href="new_installation.php">New Installtion<? $sql_status="SELECT COUNT(*) FROM installation WHERE status='1' and branch_id=2";
                                         $result_status=mysql_query($sql_status);
                                         $row_service=mysql_fetch_array($result_status);?>(<?=$row_service[0]?>)</a></li>
                                         
                                          <li><a class="home" href="installations.php">Installation</a></li>
                                      
		 
<?       break;
}?>


<? 
	   switch($username){
	   case 'jaipur1':
	                     
         ?>
     <!--<li><a   href="newpending.php">Radhika Services<? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE newpending='1'");
                 $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>-->
		 
         <li><img src="new.gif"><a   href="newservice.php">New Services<? $sql_status="SELECT COUNT(*) FROM services WHERE status='1' and  branch_id=".$_SESSION['branch_id'];
                                         $result_status=mysql_query($sql_status);
                                         $row_service=mysql_fetch_array($result_status);?>(<?=$row_service[0]?>)</a></li>
		<!-- <li><a   href="">Pending Service<? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE pending='1'");
                 $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>-->
		 
         <!--<li><a   href="newservice2.php">New Services<? $sql_status="SELECT COUNT(*) FROM services WHERE newstatus='1'  and inst_name!='' and inst_cur_location!=''";
                            $result_status=mysql_query($sql_status);
                        $row=mysql_fetch_array($result_status);?>(<?=$row[0]?>)</a></li>-->
		 <!--<li><a   href="newinstallation.php">New Installation</a></li>-->
         <li><a class="home" href="newinstallation.php">Services</a></li>
         
         <li><img src="new.gif"><a   href="new_installation.php">New Installtion<? $sql_status="SELECT COUNT(*) FROM installation WHERE status='1' and  branch_id=".$_SESSION['branch_id'];
                                         $result_status=mysql_query($sql_status);
                                         $row_service=mysql_fetch_array($result_status);?>(<?=$row_service[0]?>)</a></li>
                                         
                                          <li><a class="home" href="installations.php">Installation</a></li>
                                       
		 
<?       break;
}?>

<? 
	   switch($username){
	   case 'prabhakar':
	                     
         ?>
		 
         <li><a   href="stock_listing.php">Stock Listing</a></li>
<li><a class="home" href="installations.php">Installation</a></li>
		 <li><a href="add_vehicle.php">Add Vehicle</a></li>
		 <li><a href="add_user.php">Add User</a></li>
		 <li><a   href="add_device.php">Add Device</a></li>
         <li><a class="home" href="repair_device.php">Repair device</a></li>
		 
<?       break;
}?>
<? 
	   switch($username){
	   case 'santosh':
	                     
         ?>
		 
		 <li><a   href="repair_report.php">Device Report</a></li>
         <!--<li><a   href="repair.php">Add Device</a></li>-->
		 <!--<li><a   href="repair_device.php">Open device</a></li>-->
         <li><a class="home" href="open_device.php">Open device</a></li>
		     <li><a class="home" href="close_device.php">Close device</a></li>
<?       break;
}?>
 

    </ul>
    
         <div id="adv" style="height:25px;display:none; padding-top:1px;">
         <ul id="nav">
         <li><a  href="pending.php?status=pending">Pending <? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE reason='' and time=''");
                 $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
          <li><a  href="pending.php?status=back_to">Back To Services<? $sql_back=mysql_query("SELECT COUNT(*) FROM services WHERE (newpending='1' and newstatus='0') or (pending=1 and status=0 and newpending=0)");
                 $row_back=mysql_fetch_array($sql_back);?>(<?=$row_back[0]?>)</a></li>
           <li><a  href="show_repairs.php">Repairs</a></li>
            <li><a  href="show_stock.php">Stock</a></li>
             </ul>
         </div>
		  
    <script language="JavaScript">
    function toggle(id) {
        var state = document.getElementById(id).style.display;
            if (state == 'block') {
                document.getElementById(id).style.display = 'none';
            } else {
                document.getElementById(id).style.display = 'block';
            }
        }
</script>
    