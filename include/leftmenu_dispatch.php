<div id="middle">
<div id="left-column">
<? $username=$_SESSION['username'];?>
<? 
	  
	   switch($_SESSION['ParentId'])
	   {
	   case '5':
	                     
         ?>
            <h3>Live Debug</h3>
            <ul class="nav">
              <li><a href="<?php echo __SITE_URL;?>/dispatchview/debug.php" >Debug</a></li>
              <li><a href="<?php echo __SITE_URL;?>/dispatchview/sendsms.php">Send SMS</a></li>
              <li><a href="<?php echo __SITE_URL;?>/dispatchview/list_company_clients.php" >Client Details</a></li>
            </ul>
            
            <h3> Device</h3>
            <ul class="nav">
              <li ><a  class="home" href="<?php echo __SITE_URL;?>/dispatchview/show_repairs.php">Repairs</a></li>
            </ul>

            <h3>Service Provider</h3>
            <ul class="nav">

            <li> <a   href="<?php echo __SITE_URL;?>/dispatchview/newservice.php">Open Services(<? echo getcountRow("SELECT * FROM services WHERE service_status='1'  and ((inter_branch=0 and branch_id='".$_SESSION['BranchId']."') or (inter_branch='".$_SESSION['BranchId']."' and branch_id!='".$_SESSION['BranchId']."'))");?>)</a></li>
            
            <li><a   href="<?php echo __SITE_URL;?>/dispatchview/service.php">Running Services(<? echo getcountRow("SELECT * FROM services  where service_status='2'  and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");?>)</a></li>
            
            <li><a   href="<?php echo __SITE_URL;?>/dispatchview/forward_to_repair.php">Service Forward to Repair(<? echo getcountRow("SELECT * FROM services  where (service_status='11' and fwd_serv_to_repair is NOT NULL) and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')  ");?>)</a></li>
            
            <li> <a   href="<?php echo __SITE_URL;?>/dispatchview/new_installation.php">Open Installtion(<? echo getcountRow("SELECT * FROM installation WHERE installation_status='1'  and ((inter_branch=0 and branch_id='".$_SESSION['BranchId']."') or (inter_branch='".$_SESSION['BranchId']."' and branch_id!='".$_SESSION['BranchId']."'))");?>)</a></li>
            
            <li><a   href="<?php echo __SITE_URL;?>/dispatchview/installations.php">Running Installation(<? echo getcountRow("SELECT * FROM installation  where installation_status=2  and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') ");?>)</a></li>
            
            <li><a   href="<?php echo __SITE_URL;?>/dispatchview/installation_forward_to_repair.php">Installation Forward to Repair(<? echo getcountRow("SELECT * FROM installation  where (installation_status='11' and fwd_install_to_repair is NOT NULL) and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') ");?>)</a></li>
 
    	</ul>
        
        <h3>Service & Support</h3>
        <ul class="nav">
          
          <li ><a class="home"  href="<?php echo __SITE_URL;?>/dispatchview/installer_report.php">Installer Job Report</a></li>

          <li><a class="home" href="<?php echo __SITE_URL;?>/dispatchview/service_installation_details.php">Service & Installation </a></li>

        </ul>

<?php  
	       break;
}

?>
</div>
<div id="center-column">
