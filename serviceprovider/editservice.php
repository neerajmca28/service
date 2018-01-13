<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/private/master.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); 
include($_SERVER['DOCUMENT_ROOT']."/service/private/master.php");*/

$masterObj = new master();

/*mysql_query("BEGIN");*/
?>
 
<?
$Header="Edit Service";
if($_GET['show']=="backtoservice")
{
    $Header="Back to Service";
}
if($_GET['show']=="close")
{
    $Header="Close Service";
}


?>
 
<div class="top-bar">
<h1><? echo $Header;?></h1>
</div>
<div class="table">
<?
$date=date("Y-m-d H:i:s");
$account_manager=$_SESSION['username'];
if(isset($_REQUEST['action'])==edit)
{
    $id=$_GET['id'];
    $rows = select_query("SELECT * FROM services WHERE id='$id'");
    $service_reinstall=$rows[0]['service_reinstall'];
     $crack_req_id=$rows[0]['crack_req_id'];
    //echo '<pre>'; print_r($rows); die;
    $mode=$_GET['mode'];
}


if(isset($_POST['submit']))
{
    
    $name=$rows[0]['name'];
    $client=$_POST['company_name'];
    $user_id=$_POST['user_id'];
    $veh_reg=$rows[0]['veh_reg'];
    $new_sim_no=$_POST['sim_no'];
    $device_imei= $_POST["device_imei"];
    $Notwoking=$_POST['Notwoking'];
    $location=$_POST['location'];
    $atime=$_POST['atime'];
    $cnumber=$_POST['cnumber'];
    $inst_name=$_POST['inst_name'];
    $inst_name1=$_POST['inst_name1'];
     $inst_id1=$_POST['installer_id'];
    $payment_status=$_POST['payment_status'];
    $device_office=$_POST['device_office'];
    
   // $req_type=$_POST['hidden_req_type'];
    $device_veh_change=$_POST['device_veh_change'];
    //$vehicle_no_change=$_POST['vehicle_no_change'];
    
    $sim_change=$_POST['sim_change'];
    $device_veh_sim_status = $device_veh_change."-".$sim_change;
    $amount=$_POST['amount'];
    $paymode=$_POST['paymode'];
    $inst_cur_location=$_POST['inst_cur_location'];
    $removed_device_for_replace=$_POST['removed_device_for_replace'];
    $reason=addslashes($_POST['reason']);
    $problemservice=$_POST['problem_in_service'];
    $problem=$_POST['problem'];
    $ant_billing=$_POST['ant_billing'];
    $ant_billing_amt=$_POST['ant_billing_amt']; 
    $ant_billing_reason=$_POST['ant_billing_reason']; 
    $time=$_POST['time'];
    $pending=$_POST['pending'];
    $newstatus=$_POST['newstatus'];
    $device_status=$_POST['device_status'];
    $service_chk=$_POST['service_chk'];
    $sim_remove_status=$_POST['sim_remove_status'];
    $ac_status=$_POST['ac_status'];
    $immobilizer_status=$_POST['immobilizer_status'];
    $data_check=$_POST['data_string'];
   
    if(isset($_POST['Extreason']) && $_POST['Extreason']!="")
    {
        $reason= $_POST['reason'] ."-" .$_POST['Extreason'];
    }
       
    $billing=$_POST['billing'];
    if($billing=="") { $billing="no"; }
    $payment=$_POST['payment'];
    if($payment=="") { $payment="no"; }

    if($_SESSION['BranchId']=="1"){
        $acc_manager = "triloki";
    }
    else if($_SESSION['BranchId']=="2"){
        $acc_manager = "pankaj";
    }
    else if($_SESSION['BranchId']=="3"){
        $acc_manager = "jaipurrequest";
    }
    else if($_SESSION['BranchId']=="6"){
        $acc_manager = "asaleslogin";
    }
    else if($_SESSION['BranchId']=="7"){
        $acc_manager = "ksaleslogin";
    }

    /*$mobileno_query = mysql_query("select mobile_no from matrix.mobile_simcards where id in ( select sys_simcard from matrix.devices where id in (select sys_device_id from matrix.services where veh_reg='".$veh_reg."'))",$dblink);    
    $sql1 = mysql_fetch_array($mobileno_query);*/
    
    $mobileno_query = $masterObj->getDeviceMobile($veh_reg);
    
    $Devicemobile = $mobileno_query[0]["mobile_no"];
   
    /*$get_date_query = mysql_query("select sys_created from matrix.services where veh_reg='".$veh_reg."' limit 1",$dblink);
    $sql=mysql_fetch_array($get_date_query);*/
    
    $get_instdate = $masterObj->getDeviceInstaltiondate($veh_reg);
    
    $date_of_install = date("Y-m-d",strtotime($get_instdate[0]["sys_created"]));


    //$query1=("UPDATE services SET name='$name', veh_reg='$veh_reg', Notwoking='$Notwoking', location='$location', atime='$atime',  cnumber='$cnumber',inst_name='$inst_name',inst_cur_location='$inst_cur_location',reason='$reason',time='$time',close_date=inst_date='".date("Y-m-d")."',newpending='0',newstatus='0' WHERE id='$id'");
   
    /*$service_query = mysql_query("UPDATE services SET name='$name', veh_reg='$veh_reg', Notwoking='$Notwoking', location='$location', atime='$atime',  cnumber='$cnumber',payment_status='$payment_status',amount='$amount',paymode='$paymode',reason='$reason',sim_remove_status='$sim_remove_status',time='$time',close_date='".date("Y-m-d")."',inst_date='".date("Y-m-d")."', newpending='0', newstatus='0',problem='$problem' ,problem_in_service='$problemservice', billing='$billing',payment='$payment', ant_billing='$ant_billing', ant_billing_amt='$ant_billing_amt',ant_billing_reason='$ant_billing_reason',ac_status='$ac_status',immobilizer_status='$immobilizer_status', service_status=5, device_status='".$device_status."',device_veh_sim_status='".$device_veh_sim_status."',device_office='$device_office'  WHERE id='$id'");*/
   //echo "update installer set status=0 where inst_id='$inst_id1'"; die;
    mysql_query("update installer set status=0 where inst_id='$inst_id1'",$dblink2);
    
/**************INVENTORY DATABASE CONNECTION(SERVER DATABASE PATH)**********************/   
   
     /*include($_SERVER['DOCUMENT_ROOT']."/service/sqlconnection.php");*/
    include_once(__DOCUMENT_ROOT.'/sqlconnection.php');
   
/************************* END ***********************************/

if($service_chk == "Service Done")
{
    /*$veh_id_get = mysql_query("select id from matrix.services where veh_reg='$veh_reg'");
    $veh_reg_id = mysql_fetch_array($veh_id_get);*/
    
    $veh_reg_id = $masterObj->getVehicleId($veh_reg);
    
    $veh_service_id = $veh_reg_id[0]['id'];
   
    if(stristr($reason, 'Device removed') != "")
    {
        if($device_office != "")
        {
            $errorMsg = "";
        }
        else
        {
            $errorMsg = "Please select Device Status";
        }
    
        if($errorMsg=="")    
        {     
            /*if(stristr($reason, '(device ok)') == "")
        {*/
        if($rows[0]['device_imei']!="")
        {
          
        $rows[0]['device_imei']=trim($rows[0]['device_imei']);
        $rows[0]['device_imei']=str_replace("_","",$rows[0]['device_imei']);
        
        //$device_id = mssql_fetch_array(mssql_query("Select device_id from device where device_imei='".$rows[0]['device_imei']."'"));
    
        $device_id_inv = select_query_inventory("Select device_id from inventory.device where device_imei='".$rows[0]['device_imei']."'");
        
        if($device_id_inv[0]['device_id'] != "")
        {
          
          /*$check_data = mssql_query("select count(device.device_imei) from device join device_repair on device.device_imei=device_repair.device_imei  where device.device_imei='".$rows[0]['device_imei']."' and device.device_status in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103) and datediff(dd,device_repair.device_removed_recddate,getdate())>15");
        
          $data_count = mssql_num_rows($check_data);*/
    
          $check_data_inv = select_query_inventory("select count(device.device_imei) as total from inventory.device join inventory.device_repair on device.device_imei=device_repair.device_imei  where device.device_imei='".$rows[0]['device_imei']."' and device.device_status in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103) and datediff(device_repair.device_removed_recddate,now())>15");
        

        //echo "<pre>";print_r($check_data_inv);die;
        
        if(count($check_data_inv) > 0)
        {

        if($reason == "Device removed(kept in clients office)")
        {
            /*$inventory_query=mssql_query("update device set device_status=103,dispatch_branch='".$_SESSION['BranchId']."' where device_imei='".$rows[0]['device_imei']."'");*/
            /*$inventory_query2 = mssql_query("update device_repair set device_status=103 where current_record=1 and device_imei='".$rows[0]['device_imei']."'");*/
            
            //mssql_query("update device_repair set current_record=0 where current_record=1 and  device_imei='".$rows[0]['device_imei']."'");
            
            /*$inventory_query2 = mssql_query("insert into device_repair(device_id, client_name,device_imei,veh_no, device_removed_date, device_removed_branch, device_removed_problem, current_record, device_status,Remove_installer_name,veh_service_id) values('".$device_id_inv[0]['device_id']."','".$name."','".$rows[0]['device_imei']."','".$veh_reg."','".date('Y-m-d H:i:s')."','".$_SESSION['BranchId']."','".$reason."','1','103','".$inst_name1."','".$veh_service_id."')");*/
            
        
            $Update_device_inv = array('device_status' => '103', 'dispatch_branch' =>  $_SESSION['BranchId']);
            $condition_inv = array('device_imei' => $rows[0]['device_imei']);    
            update_query_inventory('inventory.device', $Update_device_inv, $condition_inv);
        
            $Update_device_repair_inv = array('current_record' => '0');
            $condition_repair_inv = array('device_imei' => $rows[0]['device_imei'], 'current_record' => '1');    
            update_query_inventory('inventory.device_repair', $Update_device_repair_inv, $condition_repair_inv);
        
            $device_repair_inv = array('device_id' => $device_id_inv[0]['device_id'], 'client_name' => $name, 'device_imei' => $rows[0]['device_imei'], 
            'veh_no' => $veh_reg, 'device_removed_date' =>  date('Y-m-d H:i:s'), 'device_removed_branch' =>  $_SESSION['BranchId'], 'device_removed_problem' => $reason, 'current_record' =>  '1', 'device_status' =>  '103', 'Remove_installer_name' => $inst_name1, 
            'veh_service_id' =>  $veh_service_id);
            
            $Insert_inv = insert_query_inventory('inventory.device_repair', $device_repair_inv);
        
        }
        else
        {    
          
        if($device_status == "Permanent")
        {
            //echo "update device set device_status=66, is_ffc=1 where device_imei='".trim($rows[0]['device_imei'])."'";
            /*mssql_query("update device set device_status=66, is_permanent=1, dispatch_branch='".$_SESSION['BranchId']."' where device_imei='".trim($rows[0]['device_imei'])."'");*/
        
            $Update_device_inv = array('device_status' => '66', 'is_permanent' => '1', 'dispatch_branch' =>  $_SESSION['BranchId']);
            $condition_inv = array('device_imei' => $rows[0]['device_imei']);    
            update_query_inventory('inventory.device', $Update_device_inv, $condition_inv);
    
        }
        else
        {
            //echo "update device set device_status=66 where device_imei='".trim($rows[0]['device_imei'])."'";
            /*mssql_query("update device set device_status=66, dispatch_branch='".$_SESSION['BranchId']."' where device_imei='".trim($rows[0]['device_imei'])."'");*/
        
            $Update_device_inv = array('device_status' => '66', 'dispatch_branch' =>  $_SESSION['BranchId']);
            $condition_inv = array('device_imei' => $rows[0]['device_imei']);    
            update_query_inventory('inventory.device', $Update_device_inv, $condition_inv);
          
        }
        
        //mysql_query("update matrix.services set device_removed_service=1 where id='$veh_service_id'",$dblink);
        $remove_st = 1;
        $data1 = array('device_removed_service' => $remove_st);
        $condition = array('id' => $veh_service_id);
        
        update_query_live_con('matrix.services', $data1, $condition);
        update_query_live('matrix.services', $data1, $condition);
          
        /*$total = mssql_query("Select * from device_repair where device_imei='".$rows[0]['device_imei']."'");
        $total_count = mssql_num_rows($total);*/
            
        
        $total_inv = select_query_inventory("Select * from inventory.device_repair where device_imei='".$rows[0]['device_imei']."'");
    
        if(count($total_inv) > 0)
        {
            //echo "total";

			/*mssql_query("update device_repair set current_record=0 where device_imei='".$rows[0]['device_imei']."'");
              
            $inventory_query = mssql_query("insert into device_repair(device_id, client_name,device_imei,veh_no, device_removed_date, device_removed_branch, device_removed_problem, current_record, device_status,Remove_installer_name,veh_service_id) values('".$device_id_inv[0]['device_id']."','".$name."','".$rows[0]['device_imei']."','".$veh_reg."','".date('Y-m-d H:i:s')."','".$_SESSION['BranchId']."','".$reason."','1','66','".$inst_name1."','".$veh_service_id."')");*/

            $Update_device_repair_inv = array('current_record' => '0');
            $condition_repair_inv = array('device_imei' => $rows[0]['device_imei'], 'current_record' => '1');    
            update_query_inventory('inventory.device_repair', $Update_device_repair_inv, $condition_repair_inv);
              
            $device_repair_insert = array('device_id' => $device_id_inv[0]['device_id'], 'client_name' => $name, 
            'device_imei' => $rows[0]['device_imei'], 'veh_no' => $veh_reg, 'device_removed_date' =>  date('Y-m-d H:i:s'), 'device_removed_branch' =>  $_SESSION['BranchId'], 'device_removed_problem' => $reason, 'current_record' =>  '1', 
            'device_status' =>  '66', 'Remove_installer_name' => $inst_name1, 'veh_service_id' =>  $veh_service_id);
            
            $Insert_inv = insert_query_inventory('inventory.device_repair', $device_repair_insert);
        }
        else
        {
            //echo "insert";  
			
			/*$inventory_query = mssql_query("insert into device_repair(device_id, client_name,device_imei, veh_no,device_removed_date, device_removed_branch, device_removed_problem, current_record,device_status,Remove_installer_name,veh_service_id) values('".$device_id_inv[0]['device_id']."','".$name."','".$rows[0]['device_imei']."','".$veh_reg."','".date('Y-m-d H:i:s')."','".$_SESSION['BranchId']."','".$reason."','1','66','".$inst_name1."','".$veh_service_id."')");*/

            $device_repair_insert = array('device_id' => $device_id_inv[0]['device_id'], 'client_name' => $name, 
            'device_imei' => $rows[0]['device_imei'], 'veh_no' => $veh_reg, 'device_removed_date' =>  date('Y-m-d H:i:s'), 'device_removed_branch' =>  $_SESSION['BranchId'], 'device_removed_problem' => $reason, 'current_record' =>  '1', 
            'device_status' =>  '66', 'Remove_installer_name' => $inst_name1, 'veh_service_id' =>  $veh_service_id);
            
            $Insert_inv = insert_query_inventory('inventory.device_repair', $device_repair_insert);
          
        }
        }
        }
        }
        else
        {
             $errorMsg = "This IMEI No does not exist in Inventory.Kindly Re-check IMEI no in Your Inventory Login.";
        //die();
        }
         
        //}
        /*elseif(stristr($reason,  '(device ok)') == true)
        {
        $rows[0]['device_imei']=str_replace("_","",$rows[0]['device_imei']);
        mssql_query("update device set device_status=66 where device_imei='".$rows[0]['device_imei']."'");
        mssql_query("insert into device_repair(client_name,device_imei,veh_no,device_removed_date,device_removed_branch,device_removed_problem) values('".$name."','".$rows[0]['device_imei']."','".$veh_reg."','".date('Y-m-d')."','".$_SESSION['BranchId']."','".$reason."')");
          
        $status=1;
         
        }*/
        
          }
        }
    }
    else
    {
        //if($rows[0]['device_imei']!="")
        //{
        //mssql_query("update device set device_status=66 where device_imei='".$rows[0]['device_imei']."'");       
        //echo "NO case <br/> update device set device_status=65 where device_imei='".trim($rows[0]['device_imei'])."'";

       /* $inventory_query = mssql_query("update device set device_status=65, dispatch_branch='".$_SESSION['BranchId']."' where device_imei='".trim($rows[0]['device_imei'])."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
        
        $inventory_query2 = mssql_query("update device_repair set device_status=65 where current_record=1 and device_imei='".trim($rows[0]['device_imei'])."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");*/

        
        $Update_device_inv = select_query_inventory("update inventory.device set device_status='65', dispatch_branch='".$_SESSION['BranchId']."' where device_imei='".trim($rows[0]['device_imei'])."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
        
        $Update_device_repair_inv = select_query_inventory("update inventory.device_repair set device_status='65' where current_record='1' and device_imei='".trim($rows[0]['device_imei'])."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
        
        /*$Update_device_inv = array('device_status' => 65, 'dispatch_branch' =>  $_SESSION['BranchId']);
        $condition_inv = array('device_imei' => $rows[0]['device_imei']);    
        update_query_inventory('inventory.device', $Update_device_inv, $condition_inv);
    
        $Update_device_repair_inv = array('device_status' => 65);
        $condition_repair_inv = array('device_imei' => $rows[0]['device_imei'], 'current_record' => 1);    
        update_query_inventory('inventory.device_repair', $Update_device_repair_inv, $condition_repair_inv);*/


        //mysql_query("update matrix.services set device_removed_service=0 where veh_reg='$veh_reg'");

        //mysql_query("update matrix.services set device_removed_service=0 where id='$veh_service_id'",$dblink);
        $remove_st = 0;
        $data1 = array('device_removed_service' => $remove_st);
        $condition = array('id' => $veh_service_id);
        
        update_query_live_con('matrix.services', $data1, $condition);
        update_query_live('matrix.services', $data1, $condition);
         
        $errorMsg = "";
        //}
    }

}
else
{
    $errorMsg = "";
}

if($errorMsg=="")    
{
   // echo $mode; die;
    
    $service_query = mysql_query("UPDATE services SET name='$name', veh_reg='$veh_reg', Notwoking='$Notwoking', location='$location', atime='$atime',  cnumber='$cnumber',payment_status='$payment_status',amount='$amount',paymode='$paymode',reason='$reason',sim_remove_status='$sim_remove_status',time='$time',close_date='".date("Y-m-d")."',inst_date='".date("Y-m-d")."', newpending='0', newstatus='0',problem='$problem' ,problem_in_service='$problemservice', billing='$billing',payment='$payment', ant_billing='$ant_billing', ant_billing_amt='$ant_billing_amt',ant_billing_reason='$ant_billing_reason',ac_status='$ac_status',immobilizer_status='$immobilizer_status', service_status=5,service_close_status='$service_chk', device_status='$device_status',device_veh_sim_status='$device_veh_sim_status',device_office='$device_office' WHERE id='$id'",$dblink2);
   // if($mode=='crack')
   // {
   //      $service_status=201;
   // }
   // else
   // {
   //       $service_status=5;
   // }
   //  $service_query = mysql_query("UPDATE services SET payment_status='$payment_status', amount='$amount',paymode='$paymode', reason='$reason', sim_remove_status='$sim_remove_status', time='$time', close_date='".date("Y-m-d")."', inst_date='".date("Y-m-d")."', newpending='0', newstatus='0',problem='$problem' ,problem_in_service='$problemservice', billing='$billing',payment='$payment', ant_billing='$ant_billing', ant_billing_amt='$ant_billing_amt', ant_billing_reason='$ant_billing_reason', ac_status='$ac_status',immobilizer_status='$immobilizer_status', service_status='$service_status', service_close_status='$service_chk', device_status='$device_status', device_veh_sim_status='$device_veh_sim_status', device_office='$device_office',data_check_string='$data_check' WHERE id='$id'",$dblink2);
    if($mode=='crack')
    {
        $condition1 = array('id' => $crack_req_id);
            $Update_crack=array('service_status' => 5 );
            update_query('internalsoftware.services_crack', $Update_crack, $condition1);
    }

  
    
    
    //if(($service_query) and ($inventory_query)) 
    //        { 
    //            //*** Commit Transaction ***// 
    //            mysql_query("COMMIT"); 
    //            //echo "Database transaction was successful."; 
    //        } 
    //        else 
    //        { 
    //            //*** RollBack Transaction ***// 
    //            mysql_query("ROLLBACK"); 
    //            echo "Your request not submit successfully. Please try again."; 
    //            exit;
    //        } 


    if($_POST["sim_change"]=="SimChange")
    {
   
        $rdd_date = date("Y-m-d",strtotime($time));
        $sim_remove_date = date("Y-m-d h:i:s A",strtotime($time));
       
        /*$old_sim_sql_query = mssql_fetch_array(mssql_query("select * from sim where phone_no='".$Devicemobile."'"));
        
        $update_query = mssql_query("update sim set branch_id='".$_SESSION['BranchId']."',Remove_installer_name='".$inst_name1."',device_removed_problem='".$reason."', SimRemoveDate='".$sim_remove_date."' where sim_id='".$old_sim_sql_query["sim_id"]."'");
       
        $new_sim_query = mssql_fetch_array(mssql_query("select * from sim where phone_no='".$new_sim_no."'"));
        
        $new_update_query = mssql_query("update sim set Sim_Status=91, flag=1 where sim_id='".$new_sim_query["sim_id"]."'");
        
        $new_sim_update_device_tbl_query = mssql_query("update device set sim_id='".$new_sim_query["sim_id"]."' where device_imei='".trim($device_imei)."'");*/
        


        $old_sim_inv_query = select_query_inventory("select * from inventory.sim where phone_no='".$Devicemobile."'");
        
        $Update_sim_inv = array('branch_id' => $_SESSION['BranchId'], 'Remove_installer_name' =>  $inst_name1,
        'device_removed_problem' =>  $reason, 'SimRemoveDate' =>  $sim_remove_date);
        $condition_sim_inv = array('sim_id' => $old_sim_inv_query[0]["sim_id"]);    
        update_query_inventory('inventory.sim', $Update_sim_inv, $condition_sim_inv);
    
        $new_sim_inv_query = select_query_inventory("select * from inventory.sim where phone_no='".$new_sim_no."'");
    
        $Update_newsim_inv = array('Sim_Status' => '91', 'flag' =>  '1');
        $condition_newsim_inv = array('sim_id' => $new_sim_inv_query[0]["sim_id"]);    
        update_query_inventory('inventory.sim', $Update_newsim_inv, $condition_newsim_inv);
    
        $Update_newsim_device_inv = array('sim_id' => $new_sim_inv_query[0]["sim_id"]);
        $condition_newsim_device_inv = array('device_imei' => trim($device_imei));    
        update_query_inventory('inventory.device', $Update_newsim_device_inv, $condition_newsim_device_inv);


        if($sim_remove_status == "With Sim")
        {
            /*$old_sim_sql_query = mssql_fetch_array(mssql_query("select * from sim where phone_no='".$Devicemobile."'"));
            
            $update_query = mssql_query("update sim set Sim_Status=106, flag=2, active_status=1, branch_id='".$_SESSION['BranchId']."', SimChangeInstallerName='".$inst_name1."', SimRemoveDate='".$sim_remove_date."', SimChangeRemarks='Sim Change - With Sim -".$rows[0]['device_imei']."-".$veh_reg."' where sim_id='".$old_sim_sql_query["sim_id"]."'");*/

    
            $old_sim_inv_query = select_query_inventory("select * from inventory.sim where phone_no='".$Devicemobile."'");
        
            $Update_oldsim_inv = array('Sim_Status' => '106', 'flag' => '2', 'active_status' => '1', 'branch_id' => $_SESSION['BranchId'],
            'SimChangeInstallerName' =>  $inst_name1, 'SimRemoveDate' =>  $sim_remove_date, 
            'SimChangeRemarks' =>  "Sim Change - With Sim -".$rows[0]['device_imei']."-".$veh_reg);
            $condition_oldsim_inv = array('sim_id' => $old_sim_inv_query[0]["sim_id"]);    
            update_query_inventory('inventory.sim', $Update_oldsim_inv, $condition_oldsim_inv);


         }
            
        if($sim_remove_status == "Without Sim")
        {
            /*$old_sim_sql_query = mssql_fetch_array(mssql_query("select * from sim where phone_no='".$Devicemobile."'"));
            
            $update_query = mssql_query("update sim set Sim_Status=92, status=0, flag=3, active_status=1,  branch_id='".$_SESSION['BranchId']."', SimChangeInstallerName='".$inst_name1."', SimRemoveDate='".$sim_remove_date."', SimChangeRemarks='Sim Change - Without Sim -".$rows[0]['device_imei']."-".$veh_reg."' where sim_id='".$old_sim_sql_query["sim_id"]."'");*/

    
            $old_sim_inv_query = select_query_inventory("select * from inventory.sim where phone_no='".$Devicemobile."'");
        
            $Update_oldsim_inv = array('Sim_Status' => '92', 'status' => '0', 'flag' => '3', 'active_status' => '1', 'branch_id' => $_SESSION['BranchId'],
            'SimChangeInstallerName' =>  $inst_name1, 'SimRemoveDate' =>  $sim_remove_date, 
            'SimChangeRemarks' =>  "Sim Change - Without Sim -".$rows[0]['device_imei']."-".$veh_reg);
            $condition_oldsim_inv = array('sim_id' => $old_sim_inv_query[0]["sim_id"]);    
            update_query_inventory('inventory.sim', $Update_oldsim_inv, $condition_oldsim_inv);

         }
       
        $query="INSERT INTO `sim_change` (`date`,acc_manager, `client`, `user_id`, `reg_no`, `old_sim`, `new_sim`, reason, sim_change_date, inst_name) VALUES ('".$time."','".$acc_manager."','".$client."','".$user_id."','".$veh_reg."','".$Devicemobile."','".$new_sim_no."','".$reason."','".$rdd_date."','".$inst_name1."')";
           
        mysql_query($query,$dblink2);
           
    }
    
       
   if($_POST["device_veh_change"]=="DeviceChange")
    {
        /*$check_data = mssql_query("select count(device.device_imei) from device join device_repair on device.device_imei=device_repair.device_imei  where device.device_imei='".$rows[0]['device_imei']."' and device.device_status in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116) and datediff(dd,device_repair.device_removed_recddate,getdate())>15");
        
        $data_count = mssql_num_rows($check_data);
            
        if($data_count > 0)
        {
        
            $sim_remove_date = date("Y-m-d h:i:s A",strtotime($time));
            
            $inventory_old_imei_query = mssql_query("update device set device_status=66 where device_imei='".trim($device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
            
            $inventory_old_imei_query2 = mssql_query("update device_repair set device_status=66 where current_record=1 and device_imei='".trim($device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
            
            $old_imei_device_id = mssql_fetch_array(mssql_query("Select device_id from device where device_imei='".trim($device_imei)."'"));
            $total = mssql_query("Select * from device_repair where device_imei='".trim($device_imei)."'");
            $total_count = mssql_num_rows($total);
    
            if($sim_remove_status == "With Sim")
            {
                $old_sim_sql_query = mssql_fetch_array(mssql_query("select * from sim where phone_no='".$Devicemobile."'"));
                
                $update_sime_query = mssql_query("update sim set Sim_Status=91, flag=1, active_status=1, branch_id='".$_SESSION['BranchId']."', SimChangeInstallerName='".$inst_name1."',SimRemoveDate='".$sim_remove_date."', SimChangeRemarks='Device Change - With Sim -".$rows[0]['device_imei']."-".$veh_reg."' where sim_id='".$old_sim_sql_query["sim_id"]."'");
               
$inventory_device_query = mssql_query("insert into device_repair(device_id, client_name,device_imei, veh_no,device_removed_date, device_removed_branch, device_removed_problem, current_record,device_status,Remove_installer_name) values('".$old_imei_device_id['device_id']."','".$name."','".$device_imei."','".$veh_reg."','".date('Y-m-d H:i:s')."','".$_SESSION['BranchId']."','Device Interchange - With Sim -".$rows[0]['device_imei']."','0','66','".$inst_name1."')");
                    
                
                                
            }
                
            if($sim_remove_status == "Without Sim")
            {
                $old_sim_sql_query = mssql_fetch_array(mssql_query("select * from sim where phone_no='".$Devicemobile."'"));
                
                $update_sim_query = mssql_query("update sim set Sim_Status=92, status=0, flag=3, active_status=1, branch_id='".$_SESSION['BranchId']."', SimChangeInstallerName='".$inst_name1."', SimRemoveDate='".$sim_remove_date."', SimChangeRemarks='Device Change - Without Sim -".$rows[0]['device_imei']."-".$veh_reg."' where sim_id='".$old_sim_sql_query["sim_id"]."'");
                
                $inventory_device_query = mssql_query("insert into device_repair(device_id, client_name,device_imei, veh_no,device_removed_date, device_removed_branch, device_removed_problem, current_record,device_status,Remove_installer_name) values('".$old_imei_device_id['device_id']."','".$name."','".$device_imei."','".$veh_reg."','".date('Y-m-d H:i:s')."','".$_SESSION['BranchId']."','Device Interchange - Without Sim -".$rows[0]['device_imei']."','0','66','".$inst_name1."')");
                
                
                
            }
      }*/
        

    $check_data_inv = select_query_inventory("select count(device.device_imei) from inventory.device join inventory.device_repair on device.device_imei=device_repair.device_imei  where device.device_imei='".$rows[0]['device_imei']."' and device.device_status in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116) and datediff(device_repair.device_removed_recddate,now())>15");

    if(count($check_data_inv) > 0)
    {
    
        $sim_remove_date = date("Y-m-d h:i:s A",strtotime($time));
        
        $inventory_old_imei_query_inv = select_query_inventory("update inventory.device set device_status='66' where 
		device_imei='".trim($device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
    
        /*$inventory_old_imei_query_inv = array('device_status' => 66);
        $condition_old_imei_inv = array('device_imei' => trim($device_imei));    
        update_query_inventory('inventory.device', $inventory_old_imei_query_inv, $condition_old_imei_inv);*/
        
        $inventory_old_imei_repair_inv = select_query_inventory("update inventory.device_repair set device_status='66' where current_record='1' and device_imei='".trim($device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");

        /*$inventory_old_imei_repair_inv = array('device_status' => 66);
        $condition_old_imei_repair_inv = array('device_imei' => trim($device_imei), 'current_record' => 1);    
        update_query_inventory('inventory.device_repair', $inventory_old_imei_repair_inv, $condition_old_imei_repair_inv);*/
        
        $old_imei_device_id_inv = select_query_inventory("Select device_id from inventory.device where device_imei='".trim($device_imei)."'");
        $total_inv = select_query_inventory("Select * from inventory.device_repair where device_imei='".trim($device_imei)."'");
        //$total_count = mssql_num_rows($total);

        if($sim_remove_status == "With Sim")
        {
            /*$old_sim_sql_query_inv = select_query_inventory("select * from inventory.sim where phone_no='".$Devicemobile."'");
            
            $update_sim_query_inv = array('Sim_Status' => '91', 'flag' => '1', 'active_status' =>  '1', 'branch_id' => $_SESSION['BranchId'],
            'SimChangeInstallerName' =>  $inst_name1, 'SimRemoveDate' =>  $sim_remove_date, 
            'SimChangeRemarks' =>  "Device Change - With Sim -".$rows[0]['device_imei']."-".$veh_reg);
            $condition_sim_inv = array('sim_id' => $old_sim_sql_query_inv[0]["sim_id"]);    
            update_query_inventory('inventory.sim', $update_sim_query_inv, $condition_sim_inv);*/

            $device_change_repair_insert = array('device_id' => $old_imei_device_id_inv[0]['device_id'], 'client_name' => $name, 
            'device_imei' => $device_imei, 'veh_no' => $veh_reg, 'device_removed_date' =>  date('Y-m-d H:i:s'), 
            'device_removed_branch' =>  $_SESSION['BranchId'], 
            'device_removed_problem' => "Device Interchange - With Sim -".$rows[0]['device_imei'], 'current_record' =>  '0', 
            'device_status' =>  '66', 'Remove_installer_name' => $inst_name1);
    
            $Insert_inv = insert_query_inventory('inventory.device_repair', $device_change_repair_insert);
                                    
        }
        
        if($sim_remove_status == "Without Sim")
        {
            $old_sim_sql_query_inv = select_query_inventory("select * from inventory.sim where phone_no='".$Devicemobile."'");
            
            $update_sim_query_inv = array('Sim_Status' => '92', 'status' => '0', 'flag' => '3', 'active_status' => '1', 
            'branch_id' => $_SESSION['BranchId'],
            'SimChangeInstallerName' =>  $inst_name1, 'SimRemoveDate' =>  $sim_remove_date, 
            'SimChangeRemarks' =>  "Device Change - Without Sim -".$rows[0]['device_imei']."-".$veh_reg);
            $condition_sim_inv = array('sim_id' => $old_sim_sql_query_inv[0]["sim_id"]);    
            update_query_inventory('inventory.sim', $update_sim_query_inv, $condition_sim_inv);
            
            $device_change_repair_insert = array('device_id' => $old_imei_device_id_inv[0]['device_id'], 'client_name' => $name, 
            'device_imei' => $device_imei, 'veh_no' => $veh_reg, 'device_removed_date' =>  date('Y-m-d H:i:s'), 
            'device_removed_branch' =>  $_SESSION['BranchId'], 'device_removed_problem' => "Device Interchange - Without Sim -".$rows[0]['device_imei'], 'current_record' =>  '0', 'device_status' =>  '66', 'Remove_installer_name' => $inst_name1);
            
            $Insert_inv = insert_query_inventory('inventory.device_repair', $device_change_repair_insert);
                
        }
    }


         $query="INSERT INTO `device_change` (`date`,acc_manager, `client`, `user_id`, `device_imei`, `mobile_no`, `reg_no`, date_of_install, inst_name, device_model) VALUES ('".$time."','".$acc_manager."','".$client."','".$user_id."','".$device_imei."','".$Devicemobile."','".$veh_reg."','".$date_of_install."','".$inst_name1."','".$rows[0]['device_model']."')";
       
          mysql_query($query,$dblink2);
       
    }
   
    if($_POST["device_veh_change"]=="VehiclenoChange")
    {
           
         $query="INSERT INTO `vehicle_no_change` (`date`, acc_manager,`client`, `user_id`, `old_reg_no`, inst_name) VALUES ('".$time."','".$acc_manager."','".$client."','".$user_id."','".$veh_reg."','".$inst_name1."')";
       
        mysql_query($query,$dblink2);
      
    }   
    
    mysql_close($dblink2);
    header("location:service.php");
  }

}

if(isset($_POST['update']))
    {
        //echo $mode; die;
    $inst_name = $rows[0]['name'];
    $inst_name1=$_POST['inst_name1'];
    $inst_id1=$_POST['installer_id'];
    $inst_cur_location=$_POST['inst_cur_location'];
    $billing=$_POST['billing'];
    if($billing=="") { $billing="no"; }
    $payment=$_POST['payment'];
    if($payment=="") { $payment="no"; }
     if( $_POST['assignJob']=="OngoingJob")
    {
        $inst_idname=$_POST['inst_name'];

        $ArrInst=explode("#",$_POST['inst_name']);
        $inst_name =$ArrInst[1];
        $inst_id =$ArrInst[0];
        $job_type=1;
         //echo $inst_id;
        // echo $inst_id1; 
         //die;
        mysql_query("update installer set status=1 where inst_id='$inst_id'",$dblink2);
        mysql_query("update installer set status=0 where inst_id='$inst_id1'",$dblink2);
    }
    else
    {
       
          $inst_idname=$_POST['inst_name_all'];

        $ArrInst=explode("#",$_POST['inst_name_all']);
        $inst_name =$ArrInst[1];
        $inst_id =$ArrInst[0];
         mysql_query("update installer set status=0 where inst_id='$inst_id1'",$dblink2);
        $job_type=2;
    }

    $pending=mysql_query("UPDATE services SET inst_id='$inst_id',inst_name='$inst_name',inst_cur_location='$inst_cur_location', service_status=2, job_type='$job_type'  WHERE id='$id'",$dblink2);
    //mysql_query("update installer set status=1 where inst_name='$inst_name'",$dblink2);
   // mysql_query("update installer set status=0 where inst_name='$inst_name1'",$dblink2);
   
    mysql_close($dblink2);
    header("location:service.php");
    }
   
if(isset($_POST['backservice']))
    {
    $pending=$_POST['newpending'];
    $last_reason = $_POST['last_reason'];
    $back_reason=$_POST['reason_to_back'];
    $inst_name1=$_POST['inst_name1'];
    $inst_id1=$_POST['installer_id'];
   
    $update_backquery = "UPDATE services SET newpending='1', newstatus='0', back_reason='".$last_reason." ".$back_reason." - ".$date."', service_status=3  WHERE id='$id'";
   
    mysql_query($update_backquery,$dblink2);
   
    mysql_query("UPDATE installer SET STATUS=0 WHERE inst_id='$inst_id1'",$dblink2);
   
    mysql_close($dblink2);
    header("location:service.php");
    }
?>
<script type="text/javascript">
function req_info(form3)
    {         
        
        if(document.getElementById('problemservice').checked == true)
         {
      if(document.form3.problem.value ==""){
               alert("please select Problem");
               return false;
           }
          }

        if(document.getElementById('ant_billing').checked == true)
         {
      if(document.form3.ant_billing_amt.value ==""){
               alert("please enter Billing Amount");
               document.form3.ant_billing_amt.focus();
               return false;
           }
          }

    var reason_list=ltrim(document.form3.reason.value);
        //alert(reason_list);
        if(reason_list ==0)
        {
           alert("please Select reason");
           document.form3.reason.focus();
           return false;
         }
       
        if(reason_list == "Device removed (against payment)" || reason_list == "Device removed(Buy Back)")
        {
    var device_chk = document.form3.device_status[0].checked;
    var device_chk1 = document.form3.device_status[1].checked;
    if(device_chk  == false && device_chk1  == false)
    {
      alert("please Select Device Removed Temporary/Permanent");
      return false;
    }
    }

        if(document.getElementById('sim_change').checked == true)
         {
           if(document.form3.sim_no.value ==""){
               alert("please Select Sim No");
               return false;
           }
          
          }

    //  var device_change = document.getElementById('device_veh_change').checked;
      var sim_change = document.getElementById('sim_change').checked;
      //device_change == true ||
       if( sim_change == true)
         {
            var sim_remove_status = document.form3.sim_remove_status[0].checked;
            var sim_remove_status1 = document.form3.sim_remove_status[1].checked;
            if(sim_remove_status  == false && sim_remove_status1  == false)
            {
               alert("please Select Device Remove With Sim/Without Sim");
               return false;
             }
        }
       
         var ac_list=ltrim(document.form3.ac_status.value);
        if(ac_list == "")
        {
           alert("please Select AC Status");
           document.form3.ac_status.focus();
           return false;
         }
         var immobilizer_list=ltrim(document.form3.immobilizer_status.value);
        if(immobilizer_list == "")
        {
           alert("please Select Immobilizer Status");
           document.form3.immobilizer_status.focus();
           return false;
         }
       
        var service_chked = document.form3.service_chk[0].checked;
        var service_chked1 = document.form3.service_chk[1].checked;
        if(service_chked  == false && service_chked1  == false)
        {
           alert("please Select Service Status");
           return false;
         }
     
    var data_chk = ltrim(document.form3.data_string.value);
        if(data_chk == "")
        {
           alert("please Enter Device Data String.");
           return false;
         }
         
         //alert(document.getElementById('ant_billing').value);
        /*if(reason_list =="Antenna Change")
        {
           if(document.getElementById('ant_billing').checked == false){
               alert("please Select Antenna Billing");
               document.getElementById('ant_billing').focus();
               return false;
           }
         }*/
         
         
        /*var remove_device=document.form3.removed_device_for_replace.checked;
        //alert(remove_device);
       
        if(remove_device==true && reason_list==0 ){
           
               alert("please select Reason List");
               document.form3.reason.focus();
               return false;
              
        }
        if(reason_list=='Device Removed (DIMTS Problem)' && remove_device==false){
           
               alert("please select Device Removed for Replace");
               document.form3.removed_device_for_replace.focus();
               return false;
              
        }*/
       
        var Job=ltrim(document.form3.assignJob.value);   
        //alert(Job);
        if(Job=="OngoingJob")
        {
        var inst_name=ltrim(document.form3.inst_name.value);

        if(inst_name=="")
        {
        alert("Please Enter Intallation Name");
        document.form3.inst_name.focus();
        return false;
        }
        }
        else
        {
        var inst_name=ltrim(document.form3.inst_name_all.value);

        if(inst_name=="")
        {
        alert("Please Enter Intallation Name");
        document.form3.inst_name.focus();
        return false;
        }
        }
   var inst_cur_location=ltrim(document.form3.inst_cur_location.value);   
   if(inst_cur_location=="")
  {
   alert("Please Enter Installer Current Location");
   document.form3.inst_cur_location.focus();
   return false;
   }
   /*if(document.form3.reason.value =="")
  {
   alert("please enter reason");
   document.form3.reason.focus();
   return false;
   }*/
if(document.form3.time.value =="")
  {
   alert("please enter time");x
   document.form3.time.focus();
   return false;
   }
  
}
function req_info1(form3){   
    var reason=ltrim(document.form3.reason_to_back.value);   
   if(reason=="")
  {
   alert("Please Enter Reason To Back Services");
   document.form3.reason_to_back.focus();
   return false;
   }

}

function req_info2(form3){   
            var Job=ltrim(document.form3.assignJob.value);   
        //alert(Job);
        if(Job=="OngoingJob")
        {
        var inst_name=ltrim(document.form3.inst_name.value);

        if(inst_name=="")
        {
        alert("Please Enter Installer Name");
        document.form3.inst_name.focus();
        return false;
        }
        }
        else
        {
        var inst_name=ltrim(document.form3.inst_name_all.value);

        if(inst_name=="")
        {
        alert("Please Enter Installer Name");
        document.form3.inst_name.focus();
        return false;
        }
        }

   var inst_cur_location=ltrim(document.form3.inst_cur_location.value);   
   if(inst_cur_location=="")
  {
   alert("Please Enter Installer Current Location");
   document.form3.inst_cur_location.focus();
   return false;
   }

}
function ltrim(stringToTrim) {
    return stringToTrim.replace(/^\s+/,"");
    }
   
function Status(radioValue)
{
   if(radioValue=="Yes")
    {
    document.getElementById('No').style.display = "none";
    document.getElementById('Yes').style.display = "block";
    }
    else if(radioValue=="No")
    {

    document.getElementById('No').style.display = "block";
    document.getElementById('Yes').style.display = "none";
    }
    else
    {
    document.getElementById('No').style.display = "none";
    document.getElementById('Yes').style.display = "none";
    }
   
}  

function Status12(radioValue)
{
   if(radioValue=="Yes")
    {
    document.getElementById('No').style.display = "none";
    document.getElementById('Yes').style.display = "block";
    }
    else if(radioValue=="No")
    {

    document.getElementById('No').style.display = "block";
    document.getElementById('Yes').style.display = "none";
    }
    else
    {
    document.getElementById('No').style.display = "none";
    document.getElementById('Yes').style.display = "none";
    }
   
}  

//var Path="http://trackingexperts.com/service/";
var Path="<?php echo __SITE_URL;?>/";

function DropdownShow(InstallerId)
{

    if(document.getElementById('sim_change').checked == true)
    {
    document.getElementById('DropdownShow').style.display = "block";
       
    var rootdomain="http://"+window.location.hostname
    var loadstatustext="<img src='"+rootdomain+"/images/icons/other/waiting.gif' />"
    document.getElementById("DropdownShow").innerHTML=loadstatustext;
    $.ajax({
            type:"GET",
            url:Path +"userInfo.php?action=sim_change_dropdown",
   
             data:"InstallerId="+InstallerId,
            success:function(msg){
                 
            document.getElementById("DropdownShow").innerHTML = msg;
                           
            }
        });
      }
      else
        {
        document.getElementById('DropdownShow').style.display = "none";
        }
} 

</script>

<script type="text/javascript">

        $(function () {
             
            $("#time").datetimepicker({});
        });

    </script>     
<?php echo "<p align='left' style='padding-left: 250px;width: 500px;' class='message'>" .$errorMsg. "</p>" ; ?>

<style type="text/css" >
.errorMsg{border:1px solid red; }
.message{color: red; font-weight:bold; }
</style>

<form method="post" action="" name="form3">

<table style="width: 900px;" cellspacing="2" cellpadding="3" border="1">
    <tr>
            <td  align="right"><label  id="lbDlClient"><strong>Client Name:</strong></label></td>
            <td align="center"> 
               <label><strong><?php echo $rows[0]['name'];?></strong> </label>
            </td>
            <td><input type="hidden" name="hidden_req_type" id="hidden_req_type" value="<?php echo $service_reinstall?>">
            
            <td  align="right"><strong>Vehicle No:</strong></td>
            <td align="center">
                <label><strong><?php echo $rows[0]['veh_reg'];?></strong> </label></td>
                
            <td  align="right"><strong>Notwoking:</strong></td>
            <td align="center"> 
                    <label><strong><?php echo $rows[0]['Notwoking'];?></strong> </label></td>
        </tr>
        <tr>
            <td  align="right"><strong>Location:</label></strong></td>
            <td align="center">
                <label><strong><?php echo $rows[0]['location'];?></strong> </label></td>

            <td  align="right"><strong>Available Time:</label></strong></td>
            <td align="center">
                <label><strong><?php echo $rows[0]['atime'];?></strong> </label></td>
            
            <td  align="right"><strong>Person Name: </strong></td>
            <td align="center">
                <label><strong><?php echo $rows[0]['pname'];?></strong> </label></td>
       </tr>
       <tr>
          <td  align="right"><strong>Contact No:</label></strong></td>
          <td align="center"> <label><strong><?php echo $rows[0]['cnumber'];?></strong> </label></td>
          
          <td  align="right"><strong>Device Model:</label></strong></td>
          <td align="center"> <label><strong><?php echo $rows[0]['device_model'];?></strong> </label></td>
          
          <td  align="right"><strong>Installer Name:</label></strong></td>
          <td align="center"> <label><strong><?php echo $rows[0]['inst_name'];?></strong> </label></td>
       </tr>
   </table>
   
   <table width="50%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td><td><input name="id" type="hidden" id="id" value="<?php echo $rows[0]['id'];?>">
                <input name="user_id" type="hidden" id="user_id" value="<?php echo $rows[0]['user_id'];?>">
                <input name="company_name" type="hidden" id="company_name" value="<?php echo $rows[0]['company_name'];?>">
                <input name="device_imei" type="hidden" id="device_imei" value="<?php echo $rows[0]['device_imei'];?>">
                <input type="hidden" name="installer_id" value="<?php echo $rows[0]['inst_id'];?>" />
                <input type="hidden" name="inst_name1" id="inst_name1" value="<?php echo $rows[0]['inst_name'];?>" />
        </td></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <!--<tr>
        <td  align="right">Client Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="name" id="name" readonly value="<?php echo $rows[0]['name'];?>" /></td>
    </tr>
    <tr style="">
        <td align="right">Vehicle No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="veh_reg" id="veh_reg" readonly value="<?php echo $rows[0]['veh_reg'];?>" /></td>
    </tr>
    <tr>
        <td height="32" align="right">Notwoking:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="Notwoking" id="Notwoking" readonly value="<?php echo $rows[0]['Notwoking'];?>" /></td>
    </tr>
    <tr>
        <td height="32" align="right">Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="location" readonly id="location" value="<?php echo $rows[0]['location'];?>" /></td>
    </tr>
    <tr>
        <td height="32" align="right">Available Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="atime" readonly id="atime" value="<?php echo $rows[0]['atime'];?>" /></td>
    </tr>
    <tr>
        <td height="32" align="right">Person Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="pname" readonly id="pname" value="<?php echo $rows[0]['pname'];?>" /></td>
    </tr>
    <tr>
        <td height="32" align="right">Contact No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="cnumber" readonly id="cnumber" value="<?php echo $rows[0]['cnumber'];?>" /></td>
    </tr>
    <tr>
        <td height="32" align="right">Device Model:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="device_model" readonly id="device_model" value="<?php echo $rows[0]['device_model'];?>" /></td>
    </tr>-->

    <tr>
        <!-- <td height="32" align="right">Installer Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="inst_name1" readonly id="inst_name1" value="<?php echo $rows[0]['inst_name'];?>" /> -->
        <input type="hidden" name="installer_id" value="<?php echo $rows[0]['inst_id'];?>" /></td>
    </tr>
    
    <? if($_GET['show']=="edit"){?>
    
    <tr>
        <td>&nbsp;</td>
        <td>
        <input type="radio" value="OngoingJob" id="assignJob" name="assignJob" onclick="document.getElementById('inst_name').style.display = 'block';document.getElementById('inst_name_all').style.display = 'none';">Ongoing Job
       
        <input type="radio" value="PendingJob" id="assignJob" name="assignJob" onclick="document.getElementById('inst_name').style.display = 'none';document.getElementById('inst_name_all').style.display = 'block';">Pending Job
        </td>
    </tr>
    <tr>
        <td height="32" align="right">Change Installer Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>
        <?php
        
        $query="SELECT inst_id,inst_name,concat(inst_id,'#',inst_name) as idname FROM installer where status=0 and is_delete=1 and branch_id=".$_SESSION['BranchId'];
        $result=mysql_query($query,$dblink2);
       
        ?>
        <? //$name1=$row['inst_id']."#".$row['inst_name']; ?>
         <select name="inst_name" id="inst_name" style="display:none"  ><option value="0">Select Name</option>
        <? while($row=mysql_fetch_array($result)) { ?>
        <option value=<?=$row['idname'];?>><?=$row['inst_name']?></option>
        <? } ?>
        </select>
        <?php
        
        $query="SELECT inst_id,inst_name,concat(inst_id,'#',inst_name) as idname FROM installer where is_delete=1 and branch_id=".$_SESSION['BranchId'];
        $result=mysql_query($query,$dblink2);
       
        ?>
         <select name="inst_name_all" id="inst_name_all"  style="display:none" ><option value="0">Select Name</option>
        <? while($row=mysql_fetch_array($result)) { ?>
        <option value=<?=$row['idname'];?>><?=$row['inst_name']?></option>
        <? } ?>
        </select>
        </td>
    </tr>
    
    <?php } ?>
    
<!--     <tr>
        <td width="47%" height="27" align="right">Payment Status*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="53%"><select name="payment_status" id="payment_status">
                            <option value="">Select Payment Status</option>
                            <option value="Not Required" <? if($_POST["payment_status"]=='Not Required') {?> selected="selected" <? } ?> >Not Required</option>
                            <option value="Collected" <? if($_POST["payment_status"]=='Collected') {?> selected="selected" <? } ?> >Collected</option>
                            <option value="Not collected" <? if($_POST["payment_status"]=='Not collected') {?> selected="selected" <? } ?> >Not collected</option>
                    </select></td>
    </tr> -->
    <!-- <tr>
        <td height="32" align="right">Amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="amount" id="amount" value="<?php echo $rows[0]['amount'];?>" /></td>
    </tr> -->
   <!--  <tr>
        <td width="47%" height="27" align="right">Mode*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="53%"><select name="paymode" id="paymode">
                            <option value="">Select Payment mode</option>
                            <option value="Cash" <? if($_POST["paymode"]=='Cash') {?> selected="selected" <? } ?> >Cash</option>
                            <option value="cheque" <? if($_POST["paymode"]=='cheque') {?> selected="selected" <? } ?> >cheque</option>
                            <option value="DD" <? if($_POST["paymode"]=='DD') {?> selected="selected" <? } ?> >DD</option>
                   </select></td>
    </tr> -->

   <!--  <tr>
        <td>&nbsp;</td>
        <td><input type="checkbox" name="billing" id="billing" value="yes" />Bill Delivery
        <input type="checkbox" name="payment" id="payment" value="yes" />Collect Payment </td>
    </tr> -->
     <? if($_GET['show']=="edit"){?>
    <tr>
        <td height="32" align="right">Installer Current Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="inst_cur_location" id="inst_cur_location" value="<?php echo $rows[0]['inst_cur_location'];?>" /></td>
    </tr>
    <? } ?>
    <tr>
        <td height="32" align="right"> Problem in service:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>
    <input type="radio" value="G-Trac Side" <?php if($result['problem_in_service']=="G-Trac Side"){echo "checked=\"checked\""; }?> id="problemservice" name="problem_in_service" >G-Trac Side
       
         <input type="radio" value="Client Side" <?php if($result['problem_in_service']=="Client Side"){echo "checked=\"checked\""; }?> id="problemservice" name="problem_in_service" >Client Side        
         
         <!--<input type="radio" value="Installer Side" <?php //if($result['problem_in_service']=="Installer Side"){echo "checked=\"checked\""; }?> id="problemservice" name="problem_in_service" onclick="document.getElementById('problem').style.display = 'block';">Installer Side
       
         <input type="radio" value="Client Side" <?php //if($result['problem_in_service']=="Client Side"){echo "checked=\"checked\""; }?> id="problemservice" name="problem_in_service" onclick="document.getElementById('problem').style.display = 'none';">Client Side-->
        </td>
    </tr>
    <tr>
        <td height="32" align="right">Problem&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>
            <select name="problem" id="problem" >
                <option value="">--Select One</option>
                <option value="Software Problem" <? if($_POST["problem"]=='Software Problem') {?> selected="selected" <? } ?> >Software Problem</option>
                <option value="Repair / R & D" <? if($_POST["problem"]=='Repair / R & D') {?> selected="selected" <? } ?> >Repair / R & D </option>
                <option value="Installer" <? if($_POST["problem"]=='Installer') {?> selected="selected" <? } ?> >Installer </option>
                <option value="Sim Problem" <? if($_POST["problem"]=='Sim Problem') {?> selected="selected" <? } ?> >Sim Problem </option>
            </select>
            <!--<textarea name="problem" id="problem" style="display:none" value="<?php echo $rows[0]['problem'];?>" /></textarea>-->
        </td>

    </tr>
    <!-- <tr>
        <td height="32" align="right">Antenna Billing:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>
       
        <Input type = 'Radio' Name ='ant_billing' id="ant_billing" value= 'Yes' <?php if($result['ant_billing']=="Yes"){echo "checked=\"checked\""; }?>
        onchange="Status(this.value)"
        >Yes
       
        <Input type = 'Radio' Name ='ant_billing' id="ant_billing" value= 'No' <?php if($result['ant_billing']=="No"){echo "checked=\"checked\""; }?>
        onchange="Status(this.value)"
        >No</td>
    </tr> -->
    <!-- <tr>
       <td colspan="2">
            <table  id="Yes"  align="center"  style="padding-left: 80px;width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
                <tr>
                    <td> <label  id="lbDlBilling">Billing Amount</label></td>
                    <td> <input type="text" name="ant_billing_amt" id="billing_amt" value="<?=$result['ant_billing_amt']?>" /></td>
                </tr>
            </table>
       
          <table  id="No" align="center"   style="padding-left: 80px;width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
            <tr>
                <td> <label  id="lbDlBilling">Billing Reason</label></td>
                <td> <input type="text" name="ant_billing_reason" id="billing_reason" value="<?=$result['ant_billing_reason']?>" /></td>
            </tr>
          </table>
      </td>
 </tr> -->
</table>

<table width="50%" border="0" cellpadding="2" cellspacing="2">

<? if($_GET['show']=="close"){?>

    <!--<tr>
        <td align="right">
        Device Removed For Replace
        </td>
        <td>
            <input type="checkbox" name="removed_device_for_replace" id="removed_device_for_replace" value="Device removed" />
        </td>
    </tr>-->
    <tr>
    <td height="32" align="right">Reason:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>

    <?php
     $query="SELECT * FROM reason where reason_for='service' order by reason asc";
    $result_reason=select_query($query);
   
    ?>
    <SCRIPT LANGUAGE="JavaScript">
     <!--
        function ShowTextBox(reason)
        {
            //alert(reason);
    if(reason=="Device re-installed")
            {
               document.getElementById("Extreason").style.display = 'block';
    document.getElementById("against_payment").style.display = 'none';
            }
            else if(reason=="Device removed (against payment)" || reason=="Device removed(Buy Back)")
            {
               document.getElementById("against_payment").style.display = 'block';
    document.getElementById("Extreason").style.display = 'none';
            }
            else
            {
               document.getElementById("Extreason").style.display = 'none';
     document.getElementById("against_payment").style.display = 'none';
            }
   
        }
     //-->
     </SCRIPT>
    
    <select name="reason" id="reason"   style="width:200px" onChange="ShowTextBox(this.value)">
        <option value="0">Select Name</option>
        <? for($i=0;$i<count($result_reason);$i++) {
        $highlight="";
        if($_POST["reason"]==$result_reason[$i]['reason'])
            {
            $highlight="selected";
            }
            ?>
        <option value="<?=$result_reason[$i]['reason']?>" <?=$highlight ?> ><?=$result_reason[$i]['reason']?></option>
        <? } ?>
    </select>

      <input type="text" name="Extreason" id="Extreason" value="" style="display:none" />   </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <table id='against_payment' style="display:none" >
                <tr >
                    <td align="right">Device Remove:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>
                    <input type="radio" name="device_status" id="device_status" value="Temporary" />Temporary
                    <input type="radio" name="device_status" id="device_status" value="Permanent" />Permanent
                    </td>
                </tr> 
           </table>  
       </td>
    </tr>
    <tr>
        <td align="right">Device Status:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><select name="device_office" id="device_office">
            <option value="">--Select--</option>
            <option value="G-Trac Office" <? if($_POST["device_office"]=='G-Trac Office') {?> selected="selected" <? } ?> >G-Trac Office</option>
            <option value="Client Office" <? if($_POST["device_office"]=='Client Office') {?> selected="selected" <? } ?> >Client Office</option>
            </select>
        </td>
    </tr>   
    <tr>
        <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>
        <input type="radio" name="device_veh_change" id="device_veh_change" value="DeviceChange" />Device Change
        <input type="radio" name="device_veh_change" id="device_veh_change" value="VehiclenoChange" />Vehicle No. Change
        </td>
    </tr>
    <tr>
        <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>
        <input type="checkbox" name="sim_change" id="sim_change" value="SimChange" onchange="DropdownShow(<?echo $rows[0]['inst_id'];?>)" />Sim Change
        </td>
    </tr>
    <tr>
        <td height="32" align="right">New Sim No*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>
        <div id="DropdownShow">
        
         </div>
        </td> 
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
        <input type="radio" name="sim_remove_status" id="sim_remove_status" value="With Sim" />With Sim
        <input type="radio" name="sim_remove_status" id="sim_remove_status" value="Without Sim" />Without Sim
        </td>
    </tr>   
   
    <tr>
        <td height="32" align="right">AC Status*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><select name="ac_status" id="ac_status">
                <option value="">--Select--</option>
                <option value="Working" <? if($_POST["ac_status"]=='Working') {?> selected="selected" <? } ?> >Working</option>
                <option value="Not Working" <? if($_POST["ac_status"]=='Not Working') {?> selected="selected" <? } ?> >Not Working</option>
                <option value="Not Applicable" <? if($_POST["ac_status"]=='Not Applicable') {?> selected="selected" <? } ?> >Not Applicable</option>
                </select></td>
    </tr>
    <tr>
        <td height="32" align="right">Immobilizer Status*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><select name="immobilizer_status" id="immobilizer_status">
                <option value="">--Select--</option>
                <option value="Working" <? if($_POST["immobilizer_status"]=='Working') {?> selected="selected" <? } ?> >Working</option>
                <option value="Not Working" <? if($_POST["immobilizer_status"]=='Not Working') {?> selected="selected" <? } ?> >Not Working</option>
                <option value="Not Applicable" <? if($_POST["immobilizer_status"]=='Not Applicable') {?> selected="selected" <? } ?> >Not Applicable</option>
                </select></td>
    </tr>
    <tr>       
        <td width="48%" height="32" align="right">Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="52%"><input type="text" style="width:180px" name="time" id="time" value="<?php echo date('Y-m-d H:i');?>" /></td>
    </tr>
    <tr>

        <td align="right">Service Status:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>
        <input type="radio" name="service_chk" id="service_chk" value="Service Done" />Service Done
        <input type="radio" name="service_chk" id="service_chk" value="Service Not Done" />Service Not Done
        </td>
    </tr>
    <tr>
        <td align="right">Data Check:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><textarea name="data_string" id="data_string" rows="5" cols="25" placeholder="last 2 to 3 string line enter"></textarea> </td>
    </tr>
<?}//$rows[0]['time']?>
<? if($_GET['show']=="backtoservice"){?>
    <tr>
        <td height="32" align="right">Reason To Back Services:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><textarea name="reason_to_back" id="reason_to_back" rows="5" cols="15"></textarea>
            <input type="hidden" name="last_reason" value="<?php echo $rows[0]['back_reason'];?>" /> </td>
    </tr>
<? } ?>

    <tr>
        <td height="32" align="right">
        <? if($_GET['show']=="edit")
        {
            ?><input type="submit" name="update" id="update" value="Update" onClick="return req_info2(form3)"/><?
        }
        if($_GET['show']=="close")
        {
            ?><input type="submit" name="submit" value="Close" align="right" onClick="return req_info(form3)"/>&nbsp;&nbsp;&nbsp;&nbsp;<?
        }
        if($_GET['show']=="backtoservice")
        {
            ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="backservice" value="back to service" align="right" onClick="return req_info1(form3)" /><?
        }
        ?>
       
        </td>
        <td><input type="button" name="Cancel" value="Cancel" onClick="window.location = 'service.php' " /></td>
    </tr>

 
</table>
</form>
 
 
<?
include("../include/footer.inc.php");

?>

<script>Status12("<?=$result['ant_billing'];?>");</script>