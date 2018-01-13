
        <div id="middle">
            <div id="left-column">
              
         <? $username=$_SESSION['username'];?>
   
         <? 
		$fromdateof_service="";
		$todaydate = date("Y-m-d  H:i:s");
		$newdate = strtotime ( '-5 day' , strtotime ( $todaydate ) ) ;
		$fromdateof_service = date ( 'Y-m-j H:i:s' , $newdate );

	   switch($_SESSION['ParentId'])
		{
			case "1":    
			if($_SESSION['BranchId']==1)
			{
			 
		 
         ?>
        <h3>Live Debug</h3>
        <ul class="nav">
            <li><a href="http://trackingexperts.com/service/servicecalling/debug.php" >Debug</a></li> 
            <li><a href="http://trackingexperts.com/service/servicecalling/imeisearch.php" >Data BY IMEI</a></li>
            <li><a href="http://trackingexperts.com/service/sendsms.php">Send SMS</a></li> 
            <li><a href="http://trackingexperts.com/service/servicecalling/sendmail.php">Send Mail</a></li> 
            <li><a href="http://trackingexperts.com/service/servicecalling/tellycaller_sendmail.php">Send Mail to client</a></li> 
        </ul>
		
		<h3> Device</h3>
        <ul class="nav">
	        <li ><a  class="home" href="http://trackingexperts.com/service/servicecalling/show_repairs.php">Repairs</a></li>
        </ul>
	 
        <h3>Service Calling</h3>
        <ul class="nav">
            <li><a   href="http://trackingexperts.com/service/servicecalling/pendingservice.php">Back to Service<? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE  service_status='3' and branch_id=".$_SESSION['BranchId'] ."  and request_by='".$_SESSION['username']."'");
            
            $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
            <!-- <li><a   href="service_request.php">Add Service</a></li>-->
            <!--<li><a   href="services.php">Services</a></li>-->
            <li><a class="home" href="http://trackingexperts.com/service/servicecalling/services.php">Running Services
            (<?echo getcountRow("SELECT * FROM services where (service_status='1'  or  service_status='4' or service_status='2')   and service_status!='5' and  service_status!='6' and branch_id=".$_SESSION['BranchId'] ."  and request_by='".$_SESSION['username']."'");?>)</a></li>
            <li><a   href="pendinginstallation.php?for=formatrequest">Back to installation<? $sql_pending=mysql_query("SELECT COUNT(*) FROM installation   WHERE  installation_status='3' and branch_id=".$_SESSION['BranchId'] ."  and request_by='".$_SESSION['username']."'");
            
            $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
            <!--<li><a  href="add_installation.php">Add installation</a></li>-->
            <li><a href="installation.php?for=formatrequest"> Installation
            (<? echo getcountRow("SELECT * FROM installation where (installation_status='1' or installation_status='2'  or installation_status='4' or installation_status='8' or installation_status='9') and time>'".$fromdateof_service."' and branch_id=".$_SESSION['BranchId']."  and request_by='".$_SESSION['username']."'");?>)</a></li>
		<?php }
		else
			{?>
			<h3>Live Debug</h3>
            <ul class="nav">
                <li><a href="http://trackingexperts.com/service/servicecalling/debug.php" >Debug</a></li> 
                <li><a href="http://trackingexperts.com/service/servicecalling/imeisearch.php" >Data BY IMEI</a></li>
                <li><a href="http://trackingexperts.com/service/sendsms.php">Send SMS</a></li> 
                <li><a href="http://trackingexperts.com/service/servicecalling/sendmail.php">Send Mail</a></li> 
            </ul>
		
        
            <h3> Device</h3>
            <ul class="nav">
            	<li ><a  class="home" href="http://trackingexperts.com/service/servicecalling/show_repairs.php">Repairs</a></li>
            </ul>
		
	 
        <h3>Service Calling</h3>
        <ul class="nav">
            <li><a   href="http://trackingexperts.com/service/servicecalling/pendingservice.php">Back to Service<? $sql_pending=mysql_query("SELECT COUNT(*) FROM services WHERE  service_status='3' and branch_id=".$_SESSION['BranchId'] ."  and request_by='".$_SESSION['username']."'");
            
            $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
            <!-- <li><a   href="service_request.php">Add Service</a></li>-->
            <!--<li><a   href="services.php">Services</a></li>-->
            <li><a class="home" href="http://trackingexperts.com/service/servicecalling/services.php">Running Services
            (<?echo getcountRow("SELECT * FROM services where (service_status='1'  or  service_status='4' or service_status='2')   and service_status!='5' and  service_status!='6' and branch_id=".$_SESSION['BranchId'] ."  and request_by='".$_SESSION['username']."'");?>)</a></li>
            
            
            <li><a   href="pendinginstallation.php?for=formatrequest">Back to installation<? $sql_pending=mysql_query("SELECT COUNT(*) FROM installation   WHERE  installation_status='3' and branch_id=".$_SESSION['BranchId'] ."  and request_by='".$_SESSION['username']."'");
            
            $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
            <!--<li><a  href="add_installation.php">Add installation</a></li>?for=formatrequest-->
            <li><a href="installation.php"> Installation
            (<? echo getcountRow("SELECT * FROM installation  where (installation_status='1' or installation_status='2'  or installation_status='4' or installation_status='8')  and branch_id=".$_SESSION['BranchId']."  and request_by='".$_SESSION['username']."'");?>)</a></li>
            <?}?>
            
            
            
            <!-- 
            <li><a   href="http://trackingexperts.com/service/servicecalling/pendinginstallation.php">Back to installation<? $sql_pending=mysql_query("SELECT COUNT(*) FROM installation   WHERE  installation_status='3' and branch_id=".$_SESSION['BranchId'] ."  and request_by='".$_SESSION['username']."'");
            
            $row_pending=mysql_fetch_array($sql_pending);?>(<?=$row_pending[0]?>)</a></li>
            <!--<li><a  href="add_installation.php">Add installation</a></li> 
            <li><a href="http://trackingexperts.com/service/servicecalling/installation.php">Running installation
            (<?echo getcountRow("SELECT * FROM installation where  installation_status !='5' and installation_status!='6'  and branch_id=".$_SESSION['BranchId']."  and request_by='".$_SESSION['username']."'");?>)</a></li><!-- 
            <li><a href="http://trackingexperts.com/service/servicecalling/closed_installations.php">Closed installation</a></li>
            <li><a href="http://trackingexperts.com/service/servicecalling/closed_services.php">Closed Services</a></li> -->
        </ul>
        
        <?if($_SESSION['BranchId']==1)
        {?>
          <h3>Collection Calling</h3>
            <ul class="nav">
            
                <li><a href="http://trackingexperts.com/collection/calling/todayscalling.php" >Today's Calling (<?echo getcountRow("SELECT users.id   FROM matrix.users inner join payment_status on users.id=payment_status.user_id where users.sys_active=true  and users.id in ( 
                select user_id from calling_comments where furthere=1 and furthere_date=current_date())");?>)</a></li>
                <li><a href="http://trackingexperts.com/collection/calling/callinglist.php">Calling List
                (<?echo getcountRow("SELECT users.id as sys_id,sys_username,company,fullname,address,price_per_unit FROM matrix.users left join payment_status on users.id=payment_status.user_id where users.sys_active=true  and sys_parent_user=1 and no_bill!=1 and payment_status.add_payment=1 and payment_status.calling=0");?>)
                </a></li>
                <li><a href="http://trackingexperts.com/collection/calling/alreadycalled.php">Already Called ( <?echo getcountRow("SELECT users.id as sys_id,sys_username,company,fullname,address,price_per_unit FROM matrix.users inner join payment_status on users.id=payment_status.user_id where users.sys_active=true  and payment_status.calling=1");?>)</a></li> 
                <li><a href="http://trackingexperts.com/collection/calling/blacklisteduser.php">Black Listed User (<?echo getcountRow("SELECT users.id as sys_id,sys_username,company,fullname,address,price_per_unit FROM matrix.users inner join payment_status on users.id=payment_status.user_id where users.sys_active=true  and users.id in ( 
                select user_id from calling_comments where payment=1 
                group by currnet_month,current_year 
                
                having count(*)>2 ) ");?>)</a></li>
                <li><a href="http://trackingexperts.com/collection/calling/paymentrecieved.php">Payment Recieved (<?echo getcountRow("SELECT * FROM matrix.users inner join payment_status on users.id=payment_status.user_id where users.sys_active=true and  payment_received=1 ");?>)</a></li>
                
                <li><a href="http://trackingexperts.com/collection/calling/alluser.php">All user List</a></li>
            
            </ul>
    <?}?>

              <!--<li><a href="replace_device.php">Replace Device</a></li>-->
              <!-- <li><a class="home" href="#">Installtion</a></li>-->
		 
<?       break;
}  
 

	   switch($_SESSION['ParentId'])
	   {
	   case '3':
	                     
         ?>
		 
        <h3>Live Debug</h3>
        <ul class="nav">
            <li><a href="http://trackingexperts.com/service/servicecalling/debug.php" >Debug</a></li> 
            <li><a href="http://trackingexperts.com/service/sendsms.php">Send SMS</a></li> 
            <li><a href="http://trackingexperts.com/service/servicecalling/imeisearch.php" >Data BY IMEI</a></li> 
            <?php if($_SESSION['username']=="radhika"){?>
            <li><a href="http://trackingexperts.com/service/support/list_company_clients.php" >Client Details</a></li> 
        <?php } ?>
        </ul>
   		<?php if($_SESSION['username']=="harisir"){?>
            <h3>Service & Installation</h3>
            <ul class="nav">
                <li><a   href="http://trackingexperts.com/service/support/service_running.php">Running Services(<?echo getcountRow("SELECT * FROM services  where service_status='2'  and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");?>)</a></li>
                <li><a   href="http://trackingexperts.com/service/support/installations_running.php">Running Installation(<?echo getcountRow("SELECT * FROM installation  where installation_status=2  and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");?>)</a></li>
            </ul>
        <?php } else {?>
            <h3>Service & Support</h3>
            <ul class="nav">
                <li ><a class="home"  href="http://trackingexperts.com/service/support/service.php">Service </a></li>
                <li ><a class="home"  href="http://trackingexperts.com/service/support/installations.php">Installation</a></li>
                
                <li ><a class="home"  href=  "http://trackingexperts.com/service/serviceprovider/dailyinstaller.php">Installer Job Report</a></li>
                <li ><a class="home"  href="http://trackingexperts.com/service/support/clients_report.php">Client Wise Report</a></li>
                <li><a href="service_installation_details.php">Service & Installation </a></li>
                <!--<li ><a class="home"  href="http://trackingexperts.com/service/support/service_users_report.php">CCE Report</a></li>-->    
            </ul>
      <?php } ?>
           <h3>Reports</h3>
            <ul class="nav">
            
                <li><a class="home" href="http://trackingexperts.com/service/serviceprovider/servicebyzone.php">Zone wise Service</a></li>
                <li><a class="home" href="http://trackingexperts.com/service/serviceprovider/installtionbyzone.php">Zone wise Installation</a></li>
                <li><a class="home" href="http://trackingexperts.com/service/serviceprovider/list_installer.php">Add/Edit Installer  </a></li>
                
                <li><a class="home" href="http://trackingexperts.com/service/serviceprovider/installer.php">Installer Report</a></li>
                <li><a class="home" href="http://trackingexperts.com/service/serviceprovider/devicelist.php">Device List</a></li>
            
            </ul>
            
    	<?php if($_SESSION['username']=="ragini"){ ?>
        <h3>Daily Branch Report</h3>
        
        <ul class="nav">
        
                <li><a href="list_branch_account_report.php">Payment Reoprt </a></li>
        </ul>
        <?php } ?>
            
       <?php if($_SESSION['username']=="radhika" || $_SESSION['username']=="ragini"){?>
            <h3> Device</h3>
            <ul class="nav">
                <li ><a  class="home" href="http://trackingexperts.com/service/support/show_repairs.php">Repairs</a></li>
                <li ><a  class="home" href="http://trackingexperts.com/service/support/service_ffc_case.php">Service for FFC</a></li>
                <li ><a  class="home" href="http://trackingexperts.com/service/support/ffc_device_report.php">FFC Devices</a></li>
                <li ><a class="home"  href="http://trackingexperts.com/service/support/clients_device_report.php">Client Wise Device Report</a></li>
                </ul>
                <h3> Device Stock</h3>
                <ul class="nav">
                <li ><a  class="home" href="http://trackingexperts.com/service/support/stock_branch.php">Stock by Branch</a></li>
                <!-- <li ><a  class="home" href="http://trackingexperts.com/service/support/stock_installer.php">Stock by Installer</a></li>
                <li ><a  class="home" href="http://trackingexperts.com/service/support/stock_new.php">New stock</a></li>
                <li ><a  class="home" href="http://trackingexperts.com/service/support/device_history.php">Device History</a></li> -->
            
            </ul>
                
            <h3>Service Request</h3>
            <ul class="nav">
                <li><a href="http://trackingexperts.com/service/support/accountcreation.php">New Account Creation</a></li> 
                <li><a href="http://trackingexperts.com/service/support/list_new_device_addition.php">New Device Addition</a></li> 
                <li><a href="http://trackingexperts.com/service/support/list_device_change.php">Device Change</a></li>
                <li><a href="http://trackingexperts.com/service/support/list_delete_vehicle.php">Delete Vehicle</a></li>
                <li><a href="http://trackingexperts.com/service/support/list_veh_no_change.php">Vehicle Number Change</a></li>
                <li><a href="http://trackingexperts.com/service/support/list_sim_change.php">Sim Change</a></li>
            
            </ul>
            <h3>Admin Forwarded</h3>
            <ul class="nav">
                <li><a href="deact_of_acc_admin_req.php" >Deactivation Of Account Request(<?echo getcountRow("SELECT * FROM deactivation_of_account  where approve_status=0  and forward_back_comment is null and forward_req_user='".$_SESSION["username"]."'");?>)</a></li>
                <li><a href="admin_for_request.php" >Delete From Debtors Request(<?echo getcountRow("SELECT * FROM del_form_debtors where approve_status=0  and forward_back_comment is null and forward_req_user='".$_SESSION["username"]."'");?>)</a></li>                    
                <li><a href="device_change_admin_req.php" >Device Change Request(<?echo getcountRow("SELECT * FROM device_change where approve_status=0  and forward_back_comment is null and forward_req_user='".$_SESSION["username"]."'");?>)</a></li>
                <li><a href="new_device_addition_admin_req.php" >New Device Addition Request(<?echo getcountRow("SELECT * FROM new_device_addition where approve_status=0  and forward_back_comment is null and forward_req_user='".$_SESSION["username"]."'");?>)</a></li>
                <li><a href="veh_no_change_admin_req.php" >Vehicle Number Change Request(<?echo getcountRow("SELECT * FROM vehicle_no_change where approve_status=0  and forward_back_comment is null and forward_req_user='".$_SESSION["username"]."'");?>)</a></li>
                <li><a href="delete_vehicle_admin_req.php" >Delete Vehicle Request(<?echo getcountRow("SELECT * FROM deletion where approve_status=0  and forward_back_comment is null and forward_req_user='".$_SESSION["username"]."'");?>)</a></li>
                <li><a href="deactivate_sim_admin_req.php" >Deactivate Sim Request(<?echo getcountRow("SELECT * FROM deactivate_sim where approve_status=1  and forward_back_comment is null and forward_req_user='".$_SESSION["username"]."'");?>)</a></li>
                <li><a href="accountcreation_admin_req.php" >New Account Creation Request(<?echo getcountRow("SELECT * FROM new_account_creation where approve_status=0  and forward_back_comment is null and forward_req_user='".$_SESSION["username"]."'");?>)</a></li>
                <li><a href="stop_gps_admin_req.php" >Stop GPS Request(<?echo getcountRow("SELECT * FROM stop_gps where approve_status=0  and forward_back_comment is null and forward_req_user='".$_SESSION["username"]."'");?>)</a></li>
                <li><a href="transfer_the_vehicle_admin_req.php" >Transfer Vehicle Request(<?echo getcountRow("SELECT * FROM transfer_the_vehicle where approve_status=0  and forward_back_comment is null and forward_req_user='".$_SESSION["username"]."'");?>)</a></li>
            </ul>
            <h3>Sales Request</h3>
            <ul class="nav">
                <li><a href="http://trackingexperts.com/service/support/list_deactivate_of_account.php">Deactivation Of Account(<?echo getcountRow("SELECT * FROM deactivation_of_account where approve_status=0 and service_comment is null and acc_manager in ('gaurav','kuldeep','madan','basant','saleslogin')");?>)</a></li>
                <li><a href="http://trackingexperts.com/service/support/list_deletion_from_debtors.php">Delete From Debtors(<?echo getcountRow("SELECT * FROM del_form_debtors where approve_status=0 and service_comment is null and acc_manager in ('gaurav','kuldeep','madan','basant','saleslogin')");?>)</a></li> 
                <li><a href="http://trackingexperts.com/service/support/list_no_bill.php">No Bill(<?echo getcountRow("SELECT * FROM no_bills where no_bill_issue='Service Issue' and (approve_status=0 or approve_status=2) and acc_manager in ('gaurav','kuldeep','madan','basant','saleslogin')");?>)</a></li> 
                <li><a href="http://trackingexperts.com/service/support/list_discounting.php">Discounting(<?echo getcountRow("SELECT * FROM discount_details where discount_issue='Service Issue' and (approve_status=0 or approve_status=2) and service_comment is null and acc_manager in ('gaurav','kuldeep','madan','basant','saleslogin')");?>)</a></li>  
            </ul>
          <?php } ?>
<?       break;
}?>

	 
<? 
	   switch($_SESSION['ParentId'])
	   {
	   case '2':
	                     
         ?>
            <h3>Live Debug</h3>
            <ul class="nav">
            
                <li><a href="http://trackingexperts.com/service/serviceprovider/debug.php" >Debug</a></li> 
                <li><a href="http://trackingexperts.com/service/servicecalling/imeisearch.php" >Data BY IMEI</a></li> 
                <li><a href="http://trackingexperts.com/service/serviceprovider/sendsms.php">Send SMS</a></li> 
            
            </ul>
        
        <?php if($_SESSION['BranchId']==1) {?>
        
            <h3> Device</h3>
            <ul class="nav">
                <li ><a  class="home" href="http://trackingexperts.com/service/servicecalling/show_repairs.php">Repairs</a></li>
            </ul>
        
        <?php } ?>
        
            <h3>Service Provider</h3>
            <ul class="nav">
     
            <? if($_SESSION['BranchId']!=2)
		   {?>
           
                <li> <a   href="http://trackingexperts.com/service/serviceprovider/newservice.php">Open Services<?   $sql_status="SELECT COUNT(*) FROM services WHERE service_status='1' and inter_branch=0 and branch_id=".$_SESSION['BranchId'];
                $result_status=mysql_query($sql_status);
                $row_service=mysql_fetch_array($result_status);?>(<?=$row_service[0]?>)</a></li>
                
                <!-- <li> <a   href="http://trackingexperts.com/service/serviceprovider/newservice.php">Open Services<?   //$sql_status="SELECT COUNT(*) FROM services WHERE service_status='1' and service_reinstall='service' and inter_branch=0 and branch_id=".$_SESSION['BranchId'];
                // $result_status=mysql_query($sql_status);
                //$row_service=mysql_fetch_array($result_status);?>(<?=$row_service[0]?>)</a></li>-->
                
                
                <li><a   href="http://trackingexperts.com/service/serviceprovider/service.php">Running Services(<?echo getcountRow("SELECT * FROM services  where service_status='2'  and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");?>)</a></li>
                
                <li><a   href="http://trackingexperts.com/service/serviceprovider/forward_to_repair.php">Service Forward to Repair(<?echo getcountRow("SELECT * FROM services  where (service_status='11' and fwd_serv_to_repair is NOT NULL) and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");?>)</a></li>
                
                <li> <a   href="http://trackingexperts.com/service/serviceprovider/new_installation.php">Open Installtion<? $sql_status="SELECT COUNT(*) FROM installation WHERE installation_status='1' and inter_branch=0 and branch_id=".$_SESSION['BranchId'];
                $result_status=mysql_query($sql_status);
                $row_service=mysql_fetch_array($result_status);?>(<?=$row_service[0]?>)</a></li>
                
                <li><a   href="http://trackingexperts.com/service/serviceprovider/installations.php">Running Installation(<?echo getcountRow("SELECT * FROM installation  where installation_status=2  and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");?>)</a></li>
                
                <li><a   href="http://trackingexperts.com/service/serviceprovider/installation_forward_to_repair.php">Installation Forward to Repair(<?echo getcountRow("SELECT * FROM installation  where (installation_status='11' and fwd_install_to_repair is NOT NULL) and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");?>)</a></li>
                
			 <?}
			else
		   {
				if( $_SESSION['username']=="pankajservice")
			   {?>

                <li><a   href="http://trackingexperts.com/service/serviceprovider/service.php">Running Services(<?echo getcountRow("SELECT * FROM services  where service_status='2'  and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");?>)</a></li>
                
                <li><a   href="http://trackingexperts.com/service/serviceprovider/forward_to_repair.php">Service Forward to Repair(<?echo getcountRow("SELECT * FROM services  where (service_status='11' and fwd_serv_to_repair is NOT NULL) and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");?>)</a></li>
                
                <li><a   href="http://trackingexperts.com/service/serviceprovider/installations.php">Running Installation(<?echo getcountRow("SELECT * FROM installation  where installation_status=2  and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");?>)</a></li>
                
                <li><a   href="http://trackingexperts.com/service/serviceprovider/installation_forward_to_repair.php">Installation Forward to Repair(<?echo getcountRow("SELECT * FROM installation  where (installation_status='11' and fwd_install_to_repair is NOT NULL) and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");?>)</a></li>

			  <? }

			   	if( $_SESSION['username']=="kishore")
			   {?>

                <li> <a   href="http://trackingexperts.com/service/serviceprovider/newservice.php">Open Services<?   $sql_status="SELECT COUNT(*) FROM services WHERE service_status='1'  and inter_branch=0 and branch_id=".$_SESSION['BranchId'];
                $result_status=mysql_query($sql_status);
                $row_service=mysql_fetch_array($result_status);?>(<?=$row_service[0]?>)</a></li>
                <li> <a   href="http://trackingexperts.com/service/serviceprovider/new_installation.php">Open Installtion<? $sql_status="SELECT COUNT(*) FROM installation WHERE installation_status='1' and inter_branch=0 and branch_id=".$_SESSION['BranchId'];
                $result_status=mysql_query($sql_status);
                $row_service=mysql_fetch_array($result_status);?>(<?=$row_service[0]?>)</a></li>
                
			  <? }


		   }?> 
            
      </ul>
      
            <h3>Reports</h3>
            <ul class="nav">
                <li><a class="home" href="http://trackingexperts.com/service/serviceprovider/servicebyzone.php">Zone wise Service</a></li>
                <li><a class="home" href="http://trackingexperts.com/service/serviceprovider/installtionbyzone.php">Zone wise Installation</a></li>
                <li><a class="home" href="http://trackingexperts.com/service/serviceprovider/list_installer.php">Add/Edit Installer  </a></li>
                <li><a class="home" href="http://trackingexperts.com/service/serviceprovider/dailyinstaller.php">Installer Job Report</a></li>
                <li><a class="home" href="http://trackingexperts.com/service/serviceprovider/devicelist.php">Device List</a></li>
            
            </ul>
		 
<?       break;
}?>


</div>

 <div id="center-column">
   
          