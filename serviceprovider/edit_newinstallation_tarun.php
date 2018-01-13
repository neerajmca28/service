<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/

?>

<?
$Header="Edit Installation";
if($_GET['show']=="backtoservice")
{
        $Header="Back to Service";
}

if($_GET['show']=="close")
{
        $Header="Close Installation";
}



?>
<style type="text/css">
<!--
.style17 {
        font-size: 12px;
        font-weight: bold;
}
.style18 {
        font-size: 9px;
        font-weight: bold;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="<?php echo __SITE_URL;?>/build/jquery.datetimepicker.css"/>
<script src="<?php echo __SITE_URL;?>/build/jquery.datetimepicker.full.js"></script>

<script src="<?php echo __SITE_URL;?>/js/jquery.min.js"></script>

<div class="top-bar">
<h1><? echo $Header;?></h1>
</div>
<div class="table">
<?
if(isset($_REQUEST['action'])==edit)
        {
        $id=$_GET['id'];
        $rows = select_query("SELECT * FROM installation WHERE id='$id'");

        $sales_name = select_query("select name from sales_person where id='".$rows[0]['sales_person']."'");
        $current_date = date("Y-m-d H:i");
        $back_date = date("Y-m-d H:i", strtotime('-7 days',strtotime($current_date)));
}
//print_r($rows);
if(isset($_POST['submit']))
{

        $name=$_POST['name'];
        $no_of_veh=$rows[0]['no_of_vehicals'];
        $done_inst=$_POST['done_inst'];
        $installation_approve=$_POST['inst_approve'];        
        $Notwoking=$_POST['Notwoking'];
        $location=$_POST['location'];
        $atime=$_POST['atime'];
        $cnumber=$_POST['cnumber'];
        $payment_status=$_POST['payment_status'];
        $device_type=$_POST['device_type'];
        $amount=$_POST['amount'];
        $paymode=$_POST['paymode'];
        $inst_name=$_POST['inst_name'];
        $inst_name1=$_POST['inst_name1'];
        $inst_id1=$_POST['installer_id'];
        $inst_cur_location=$_POST['inst_cur_location'];
        $reason=$_POST['reason'];
        $time=$_POST['time'];
        $pending=$_POST['pending'];
        $rtime=$_POST['rtime'];
        $newstatus=$_POST['newstatus'];
        $data_check=$_POST['data_string'];

        $billing=$_POST['billing'];
        if($billing=="") { $billing="No"; }
        $payment=$_POST['payment'];
        if($payment=="") { $payment="No"; }

        $billing_no_reason=$_POST['billing_no_reason'];
        $inst_req_id = $_POST['inst_req_id'];
        $sim_change=$_POST['sim_change'];
        $new_sim_no=$_POST['sim_no'];
        
        $fuel_sensor=$_POST['fuel_sensor'];
        $bonnet_sensor=$_POST['bonnet_sensor'];
        $rfid_reader=$_POST['rfid_reader'];
        $speed_alarm=$_POST['speed_alarm'];
        $door=$_POST['door'];
        $temperature=$_POST['temperature'];
        $duty_box=$_POST['duty_box'];
        $panic_button=$_POST['panic_button'];
        
        $done_required = $fuel_sensor." - ".$bonnet_sensor." - ".$rfid_reader." - ".$speed_alarm." - ".$door." - ".$temperature." - ".$duty_box." - ".$panic_button;

        
        //$query1=("UPDATE installation SET  inst_name='$inst_name',inst_cur_location='$inst_cur_location',reason='$reason',rtime='$rtime',close_date=inst_date='".date("Y-m-d")."',newpending='0',newstatus='0' WHERE id='$id'");

        //echo "UPDATE installation SET payment_status='$payment_status',amount='$amount',paymode='$paymode',reason='$reason',rtime='$rtime',close_date=inst_date='".date("Y-m-d")."',newpending='0',newstatus='0' ,billing='$billing',payment='$payment', installation_status=5   WHERE id='$id'";



/**************INVENTORY DATABASE CONNECTION(SERVER DATABASE PATH)**********************/

/*include($_SERVER["DOCUMENT_ROOT"]."/service/sqlconnection.php");*/
include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

/************************* END ***********************************/

$errMSG="";
  $inst_made=$_POST["Installtion_Made"];
  $total_inst_made = $inst_made + $done_inst;

for($N=1;$N<=$inst_made;$N++)
  {

    $date=date("Y-m-d H:i:s");
    $account_manager=$_SESSION['username'];
    
     if($account_manager=='prabhakarsir') {
     $acc_manager='triloki';
     }
    elseif($account_manager=='surabhi') {
     $acc_manager='jaipurrequest';
     }
     elseif($account_manager=='ragini') {
     $acc_manager='ragini';
     }
     elseif($account_manager=='pankajservice') {
     $acc_manager='pankaj';
     }
     elseif($account_manager=='kavita') {
     $acc_manager='asaleslogin';
     }
     elseif($account_manager=='kolkataservice') {
     $acc_manager='ksaleslogin';
     }
     else {
     $acc_manager=$account_manager;
     }

  $veh_reg=$_POST["Veh_name$N"];

  $DeviceId=str_replace("_","",$_POST["DeviceIMEI$N"]);
  $machine=$_POST["machine$N"];
  $ac=$_POST["ac$N"];
  $immobilizer=$_POST["immobilizer$N"];
  $immobilizer_type=$_POST["immobilizer_type$N"];
  
  if($_POST["sim_change"]=="SimChange")
    {
        
        $rdd_date = date("Y-m-d",strtotime($time));
        $sim_remove_date = date("Y-m-d h:i:s",strtotime($time));
       
        /*$old_sim_sql_query = mssql_fetch_array(mssql_query("select sim_id from device where device_imei='".$DeviceId."'"));
        
        $update_query = mssql_query("update sim set Sim_Status=106, flag=2, active_status=1, branch_id='".$_SESSION['BranchId']."', SimChangeInstallerName='".$inst_name1."', SimRemoveDate='".$sim_remove_date."', SimChangeRemarks='Sim Change at installation close time - ".$DeviceId."' where sim_id='".$old_sim_sql_query["sim_id"]."'");
       
        $new_sim_query = mssql_fetch_array(mssql_query("select * from sim where phone_no='".$new_sim_no."'"));
        
        $new_sim_update_query = mssql_query("update sim set Sim_Status=91 where sim_id='".$new_sim_query["sim_id"]."'");
        
        $new_sim_update_device_query = mssql_query("update device set sim_id='".$new_sim_query["sim_id"]."' where device_imei='".$DeviceId."'");*/
        
                                 
        $old_sim_sql_query_inv = select_query_inventory("select sim_id from inventory.device where device_imei='".$DeviceId."'");

        $Update_oldsim_inv = array('Sim_Status' => '106', 'flag' =>  '2', 'active_status' =>  '1', 'branch_id' => $_SESSION['BranchId'],
                'SimChangeInstallerName' =>  $inst_name1, 'SimRemoveDate' =>  $sim_remove_date, 
                'SimChangeRemarks' =>  "Sim Change at installation close time -".$DeviceId);
        $condition_oldsim_inv = array('sim_id' => $old_sim_sql_query_inv[0]["sim_id"]);        
        update_query_inventory('inventory.sim', $Update_oldsim_inv, $condition_oldsim_inv);

        $new_sim_query_inv = select_query_inventory("select * from inventory.sim where phone_no='".$new_sim_no."'");

        $Update_newsim_inv = array('Sim_Status' => '91');
        $condition_newsim_inv = array('sim_id' => $new_sim_query_inv[0]["sim_id"]);        
        update_query_inventory('inventory.sim', $Update_newsim_inv, $condition_newsim_inv);

        $Update_newsim_device_inv = array('sim_id' => $new_sim_query_inv[0]["sim_id"]);
        $condition_newsim_device_inv = array('device_imei' => trim($DeviceId));        
        update_query_inventory('inventory.device', $Update_newsim_device_inv, $condition_newsim_device_inv);

    }
  
  

 if($veh_reg!="" && $DeviceId!="" && $ac!="" && $immobilizer!="")
  {

    /*$resultDevice = mssql_query("select itgc_id ,device_imei,sim.phone_no,device_status,item_master.item_name from device left join sim 
                                on device.sim_id =sim.sim_id left join item_master on item_master.item_id=device.device_type  
                                where device_imei='".$DeviceId."'");
    $rowDevice = mssql_fetch_array($resultDevice);
    
    if($rowDevice['device_status'] != '70')
    {
        mssql_query("update device set device_status=65, is_permanent=0, is_ffc=0 where device_imei='".$DeviceId."'");
        
        //mssql_query("update device_repair set device_status=65 where current_record=1 and device_imei='".$DeviceId."'");
        
        $repair_total = mssql_query("Select * from device_repair where device_imei='".$DeviceId."'");
        $repair_count = mssql_num_rows($repair_total);
        
        if($repair_count>0)
        {
            mssql_query("update device_repair set device_status=65 where current_record=1 and device_imei='".$DeviceId."'");
        }
    }*/

    $resultDevice_inv = select_query_inventory("select itgc_id ,device_imei,sim.phone_no,device_status,item_master.item_name from 
                        inventory.device left join inventory.sim on device.sim_id =sim.sim_id left join inventory.item_master on 
                        item_master.item_id=device.device_type  where device_imei='".$DeviceId."'");

    if($resultDevice_inv[0]['device_status'] != '70')
    {
        $Update_device_inv = array('device_status' => '65', 'is_permanent' =>  '0', 'is_ffc' =>  '0');
        $condition_inv = array('device_imei' => $DeviceId);        
        update_query_inventory('inventory.device', $Update_device_inv, $condition_inv);
                

        $repair_total_inv = select_query_inventory("Select * from inventory.device_repair where device_imei='".$DeviceId."'");
                
        if(count($repair_total_inv) > 0)
        {
            $Update_device_repair_inv = array('device_status' => '65');
            $condition_repair_inv = array('device_imei' => $DeviceId, 'current_record' => '1');        
            update_query_inventory('inventory.device_repair', $Update_device_repair_inv, $condition_repair_inv);

			 //mssql_query("update device_repair set device_status=65 where current_record=1 and device_imei='".$DeviceId."'");
        }

		//mssql_query("update device set device_status=65, is_permanent=0, is_ffc=0 where device_imei='".$DeviceId."'");

    }

	mysql_query("INSERT INTO `new_device_addition` (`inst_id`, `date`, `inst_close_date`, `acc_manager`, `client`, `user_id`, `vehicle_no`, `ac`,`immobilizer`,`immobilizer_type`, `device_type`, `device_model`, `device_id`, `device_imei`, `device_sim_num`,installtionRequestID,dimts,sales_manager,inst_name,billing_if_no_reason,billing,comment) VALUES ('".$id."','".$date."','".$rtime."','".$acc_manager."','".$rows[0]['company_name']."',  '".$rows[0]['user_id']."', '".$veh_reg."',  '".$ac."', '".$immobilizer."', '".$immobilizer_type."', '".$device_type."',  '".$resultDevice_inv[0]['item_name']."',  '".$resultDevice_inv[0]['itgc_id']."',  '".$resultDevice_inv[0]['device_imei']."',  '".$resultDevice_inv[0]['phone_no']."', '".$rows[0]['id']."','".$rows[0]['dimts']."','".$sales_name[0]['name']."','".$rows[0]['inst_name']."','".$billing_no_reason."','".$billing."','".$machine."')",$dblink2);
    
    /*mysql_query("INSERT INTO `new_device_addition` (`inst_id`, `date`, `inst_close_date`, `acc_manager`, `client`, `user_id`, 
    `vehicle_no`, `ac`,`immobilizer`,`immobilizer_type`, `device_type`, `device_model`, `device_id`, `device_imei`, `device_sim_num`,
    installtionRequestID, dimts, sales_manager, inst_name, billing_if_no_reason, billing, comment, mode_of_payment, device_price, device_price_vat,
    device_price_total, device_rent_Price, device_rent_service_tax, DTotalREnt, demo_time, rent_status, rent_month, rent_payment) VALUES ('".$id."',
    '".$date."','".$rtime."','".$acc_manager."','".$rows[0]['company_name']."',  '".$rows[0]['user_id']."', '".$veh_reg."',  '".$ac."', '".$immobilizer."', 
    '".$immobilizer_type."', '".$device_type."',  '".$rowDevice['item_name']."',  '".$rowDevice['itgc_id']."',  '".$rowDevice['device_imei']."',  
    '".$rowDevice['phone_no']."', '".$rows[0]['id']."','".$rows[0]['dimts']."','".$sales_name[0]['name']."','".$rows[0]['inst_name']."',
    '".$billing_no_reason."','".$billing."','".$machine."','".$rows[0]['mode_of_payment']."','".$rows[0]['device_price']."','".$rows[0]['device_price_vat']."'
    ,'".$rows[0]['device_price_total']."','".$rows[0]['device_rent_Price']."','".$rows[0]['device_rent_service_tax']."','".$rows[0]['DTotalREnt']."'
    ,'".$rows[0]['demo_time']."','".$rows[0]['rent_status']."','".$rows[0]['rent_month']."','".$rows[0]['rent_payment']."')",$dblink2);*/

        $errMSG="";
  
    }
    else
    {
        $errMSG="Please enter vehicle number, device IMEI, AC and Immobilizer";
    }
 }

if($errMSG=="")
    {

        mysql_query("update installer set status=0 where inst_id='".$inst_id1."'",$dblink2);
        
        mysql_query("UPDATE installation SET payment_status='$payment_status',amount='$amount',paymode='$paymode',reason='$reason',
        rtime='$rtime',close_date='".date("Y-m-d")."',inst_date='".date("Y-m-d")."',newpending='0', newstatus='0', device_type='$device_type', 
        billing='$billing', payment='$payment',installation_made='$total_inst_made', installation_status=5, sim_change_status='$sim_change', 
        done_required='$done_required',data_check_string='$data_check'   WHERE id='$id'",$dblink2);
        
        $total_row = select_query("SELECT COUNT(*) AS total_count FROM installation WHERE inst_req_id='".$inst_req_id."'");
        
        $close_inst_row = select_query("SELECT COUNT(*) AS total_row FROM installation WHERE inst_req_id='".$inst_req_id."' AND 
        installation_status IN ('5','6')");
        
        if($total_row[0]['total_count'] == $close_inst_row[0]['total_row'])
        {
            mysql_query("UPDATE installation_request SET close_date='".date("Y-m-d")."', installation_made='".$close_inst_row[0]['total_row']."', 
            installation_status=5 WHERE id='".$inst_req_id."'",$dblink2);
        }        
        
        header("location:installations.php");

    }
}

if(isset($_POST['update']))
{
        $inst_name1=$_POST['inst_name1'];
        $inst_id1=$_POST['installer_id'];
        $inst_name=$_POST['inst_name'];
        $inst_cur_location=$_POST['inst_cur_location'];

        $billing=$_POST['billing'];
        if($billing=="") { $billing="No"; }
        $payment=$_POST['payment'];
        if($payment=="") { $payment="No"; }



         if( $_POST['assignJob']=="OngoingJob")
        {
                $inst_idname=$_POST['inst_name'];

                $ArrInst=explode("#",$_POST['inst_name']);
                $inst_name =$ArrInst[1];
                $inst_id =$ArrInst[0];
                $job_type=1;

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


        $pending=mysql_query("UPDATE installation SET inst_id='$inst_id', job_type='$job_type' , inst_name='$inst_name',
        inst_cur_location='$inst_cur_location' ,billing='$billing',payment='$payment' , installation_status=2  WHERE id='$id'",$dblink2);
        //mysql_query("update installer set status=1 where inst_name='$inst_name'");
        //mysql_query("update installer set status=0 where inst_name='$inst_name1'");

        header("location:installations.php");
     }
     
if(isset($_POST['backservice']))
{

        $pending=$_POST['newpending'];
        $last_reason = $_POST['last_reason'];
        $back_reason=$_POST['reason_to_back'];
        $inst_name1=$_POST['inst_name1'];
        $inst_id1=$_POST['installer_id'];

        $update_bcakreason = "UPDATE installation SET newpending='1',newstatus='0' ,back_reason='".$last_reason."<br/>".date("Y-m-d H:i:s")." - ".$back_reason."' , installation_status=3 WHERE id=$id";
        $pending = mysql_query($update_bcakreason,$dblink2);

        /*$pending=mysql_query("UPDATE installation SET newpending='1',newstatus='0' ,back_reason='$back_reason' , installation_status=3 WHERE id='$id'");*/
        mysql_query("update installer set status=0 where inst_id='$inst_id1'",$dblink2);
        header("location:installations.php");
}
?>
 <script type="text/javascript">
function req_info(form3)
{
    //var totalcount = document.getElementById('Installtion_Made').value;
    if(document.getElementById('sim_change').checked == true)
     {
       if(document.form3.sim_no.value ==""){
           alert("please Select Sim No");
           return false;
       }
      
      }

    var totalcount = document.form3.Installtion_Made.value;

    if(document.form3.device_type.value =="")
    {
    alert("please select Device Type");
    document.form3.device_type.focus();
    return false;
    }
    
    if(document.form3.Installtion_Made.value =="")
    {
    alert("please select Installtion made");
    document.form3.Installtion_Made.focus();
    return false;
    }
    
    var billing_yes = document.form3.billing[0].checked;
    var billing_no = document.form3.billing[1].checked;

    if(billing_yes == false && billing_no == false)
    {
       alert("please Select Billing");
       return false;
     }

   /*if(billing_yes == true)
     {
           if(document.form3.amount.value =="")
           {
                   alert("please enter amount");
                   document.form3.amount.focus();
                   return false;
           }

        }*/
    if(billing_no == true)
    {
       if(document.form3.billing_no_reason.value =="")
       {
               alert("please enter Reason");
               document.form3.billing_no_reason.focus();
               return false;
       }

    }

    if(document.form3.rtime.value =="")
    {
    alert("please enter time");
    document.form3.rtime.focus();
    return false;
    }
    
    var close_tym = document.form3.rtime.value;  
    var current_tym = document.form3.current_date.value;
    var back_tym = document.form3.back_date.value;
    if(close_tym >= current_tym)
    {
        alert("Please Select only Back & current Date");
        return false;
    }
    if(close_tym <= back_tym)
    {
        alert("Please Select current Date & Last 6 Days Date");
        return false;
    }
    
    var data_chk = ltrim(document.form3.data_string.value);
    if(data_chk == "")
    {
      alert("please Enter Device Data String.");
      return false;
    }
        
   if(document.form3.immobilizer1.value =="Yes")
    {
                if(document.form3.immobilizer_type1.value =="")
           {
                alert("please select Immobilizer Type");
                document.form3.immobilizer_type1.focus();
           return false;
           }
        }
        if(document.form3.immobilizer2.value =="Yes")
    {
                if(document.form3.immobilizer_type2.value =="")
           {
                alert("please select Immobilizer Type");
                document.form3.immobilizer_type2.focus();
           return false;
           }
        }
        if(document.form3.immobilizer3.value =="Yes")
    {
                if(document.form3.immobilizer_type3.value =="")
           {
                alert("please select Immobilizer Type");
                document.form3.immobilizer_type3.focus();
           return false;
           }
        }
        if(document.form3.immobilizer4.value =="Yes")
    {
                if(document.form3.immobilizer_type4.value =="")
           {
                alert("please select Immobilizer Type");
                document.form3.immobilizer_type4.focus();
           return false;
           }
        }
        if(document.form3.immobilizer5.value =="Yes")
    {
                if(document.form3.immobilizer_type5.value =="")
           {
                alert("please select Immobilizer Type");
                document.form3.immobilizer_type5.focus();
           return false;
           }
        }
        if(document.form3.immobilizer6.value =="Yes")
    {
                if(document.form3.immobilizer_type6.value =="")
           {
                alert("please select Immobilizer Type");
                document.form3.immobilizer_type6.focus();
           return false;
           }
        }
        if(document.form3.immobilizer7.value =="Yes")
    {
                if(document.form3.immobilizer_type7.value =="")
           {
                alert("please select Immobilizer Type");
                document.form3.immobilizer_type7.focus();
           return false;
           }
        }
        if(document.form3.immobilizer8.value =="Yes")
    {
                if(document.form3.immobilizer_type8.value =="")
           {
                alert("please select Immobilizer Type");
                document.form3.immobilizer_type8.focus();
           return false;
           }
        }
        if(document.form3.immobilizer9.value =="Yes")
    {
                if(document.form3.immobilizer_type9.value =="")
           {
                alert("please select Immobilizer Type");
                document.form3.immobilizer_type9.focus();
           return false;
           }
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


//function req_info2(form3){
//
//      var inst_name=ltrim(document.form3.inst_name.value);
//
//   if(inst_name=="0")
//  {
//   alert("Please Select Installer Name");
//   document.form3.inst_name.focus();
//   return false;
//   }
//   var inst_cur_location=ltrim(document.form3.inst_cur_location.value);
//   if(inst_cur_location=="")
//  {
//   alert("Please Enter Installer Current Location");
//   document.form3.inst_cur_location.focus();
//   return false;
//   }
//     if(document.form3.billing_no_reason.value =="")
//  {
//   alert("please Enter Billing and Reason");
//   document.form3.billing_no_reason.focus();
//   return false;
//   }
// if(document.form3.Installtion_Made.value =="")
//  {
//   alert("please select Installtion made");
//   document.form3.Installtion_Made.focus();
//   return false;
//   }
//}

function ltrim(stringToTrim) 
{
    return stringToTrim.replace(/^\s+/,"");
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

                $("#rtime").datetimepicker({});
        });

    </script>

<form method="post" action="" name="form3">

    <table style="width: 900px;" cellspacing="2" cellpadding="3" border="1">
        <tr>
            <td  align="right"><label  id="lbDlClient"><strong>Sales Person:</strong></label></td>
            <td align="center"> 
                <label><strong><?php echo $sales_name[0]['name'];?></strong> </label>
            </td>
            
            <td  align="right"><strong>Client Name:</strong></td>
            <td align="center">
                <label><strong><?php echo $rows[0]['company_name'];?></strong> </label></td>
                
            <td  align="right"><strong>No.Of Vehicle:</strong></td>
            <td align="center"> 
                    <label><strong><?php echo $rows[0]['no_of_vehicals'];?></strong> </label></td>
        </tr>
        <tr>
            <td  align="right"><strong>Location:</label></strong></td>
            <td align="center">
                <label><strong><?php echo $rows[0]['location'];?></strong> </label></td>

            <td  align="right"><strong>Available Time:</label></strong></td>
            <td align="center">
                <label><strong><?php echo $rows[0]['time'];?></strong> </label></td>
            
            <td  align="right"><strong>Vehicle Type: </strong></td>
            <td align="center">
                <label><strong><?php echo $rows[0]['veh_type'];?></strong> </label></td>
       </tr>
       <tr>
          <td  align="right"><strong>Device Model:</label></strong></td>
          <td align="center"> <label><strong><?php echo $rows[0]['model'];?></strong> </label></td>
          
          <td  align="right"><strong>Immobilizer:</label></strong></td>
          <td align="center"> <label><strong><?php echo $rows[0]['immobilizer_type'];?></strong> </label></td>
          
          <td  align="right"><strong>DIMTS:</label></strong></td>
          <td align="center"> <label><strong><?php echo $rows[0]['dimts'];?></strong> </label></td>
       </tr>
       <tr>
          <td  align="right"><strong>Demo:</label></strong></td>
          <td align="center"> <label><strong><?php echo $rows[0]['demo'];?></strong> </label></td>
          
          <td  align="right"><strong>Payment Request:</label></strong></td>
          <td align="center"> <label><strong><?php echo $rows[0]['payment_req'];?></strong> </label></td>
          
          <td  align="right"><strong>Installer Name:</label></strong></td>
          <td align="center"> <label><strong><?php echo $rows[0]['inst_name'];?></strong> </label></td>
       </tr>
       <tr>
          <td  align="right"><strong>Contact No:</label></strong></td>
          <td align="center"> <label><strong><?php echo $rows[0]['contact_number'];?></strong> </label></td>
          
          <td  align="right"><strong>Contact Person:</label></strong></td>
          <td align="center"> <label><strong><?php echo $rows[0]['contact_person'];?></strong> </label></td>
          <td colspan="2">&nbsp;</td>
       </tr>
   </table>
   
    <table width="80%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2"><input name="id" type="hidden" id="id" value="<?php echo $rows[0]['id'];?>">
             <input type="hidden" name="done_inst" id="done_inst" value="<?=$rows[0]['installation_made'];?>"/>
             <input type="hidden" name="current_date" id="current_date" value="<?=$current_date;?>"/>
             <input type="hidden" name="back_date" id="back_date" value="<?=$back_date?>"/>
             <input type="hidden" name="inst_approve" id="inst_approve" value="<?=$rows[0]['installation_approve'];?>"/>
             <input type="hidden" name="inst_name1" id="inst_name1" value="<?php echo $rows[0]['inst_name'];?>" />
             <input type="hidden" name="installer_id" value="<?php echo $rows[0]['inst_id'];?>" />
             <input type="hidden" name="inst_req_id" id="inst_req_id" value="<?php echo $rows[0]['inst_req_id'];?>" />
             </td>
        </tr>
        
        <tr>
            <td  colspan="2"><?=$errMSG;?></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <!--<tr>
            <td  align="right">Sales Person:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="name" id="name" readonly value="<?php  echo $sales['name'];?>" />
                    <input type="hidden" name="current_date" id="current_date" value="<?=date("Y-m-d H:i");?>"/></td>
        </tr>
        <tr>
            <td  align="right">Client Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="name" id="name" readonly value="<?php echo $rows[0]['company_name'];?>" /></td>
        </tr>
        <tr>
            <td align="right">No.Of Vehicle :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="veh_reg" id="veh_reg" readonly value="<?php echo $rows[0]['no_of_vehicals'];?>" />
                   </td>
        </tr>
        
        <tr>
            <td height="32" align="right">Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="location" readonly id="location" value="<?php echo $rows[0]['location'];?>" /></td>
        </tr>
        <tr>
            <td height="32" align="right">Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="time" readonly id="time" value="<?php echo $rows[0]['time'];?>" /></td>
        </tr>
        <tr>
            <td height="32" align="right">Vehicle Type :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="veh_type" id="veh_type" readonly value="<?php echo $rows[0]['veh_type'];?>" /></td>
        </tr>
        
        <tr>
            <td height="32" align="right">Device Model:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="model" readonly id="model" value="<?php echo $rows[0]['model'];?>" /></td>
        </tr>
        <tr>
            <td height="32" align="right">Immobilizer :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="immobilizer_type" id="immobilizer_type" readonly value="<?php echo $rows[0]['immobilizer_type'];?>" /></td>
        </tr>
        <tr>
            <td height="32" align="right">DIMTS :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="dimts" id="dimts" readonly value="<?php echo $rows[0]['dimts'];?>" /></td>
        </tr>
        <tr>
            <td height="32" align="right">Demo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="demo" id="demo" readonly value="<?php echo $rows[0]['demo'];?>" /></td>
        </tr>
        <tr>
            <td height="32" align="right">Payment Request:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="payment_req" id="payment_req" readonly value="<?php echo $rows[0]['payment_req'];?>" /></td>
        </tr>
        <tr>
        
            <td height="32" align="right">Contact No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="contact_number" readonly id="contact_number" value="<?php echo $rows[0]['contact_number'];?>" /></td>
        </tr>
        
        <tr>
        
            <td height="32" align="right">Installer Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="inst_name1" id="inst_name1" readonly value="<?php echo $rows[0]['inst_name'];?>" /></td>
        </tr>-->


        <? if($_GET['show']=="edit"){?>
        <tr>
        <td colspan="2" align="center" >
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
        <? } ?>
        <!--<tr>
        <td height="32" align="right">Contact No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="contact_number" readonly id="contact_number" value="<?php echo $rows[0]['contact_number'];?>" /></td>
        </tr>-->
       <!--  <tr>
            <td width="47%" height="27" align="right">Payment Status*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td width="53%"><select name="payment_status">
                    <option value="">Select Payment Status</option>
                    <option value="Not Required">Not Required</option>
                    <option value="Collected">Collected</option>
                    <option value="Not collected">Not collected</option>
                    </select></td>
            </tr> -->
        <!-- <tr>
            <td height="32" align="right">Amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="amount" id="amount"   value="<?php echo $rows[0]['amount'];?>" /></td>
        </tr> -->
   <!--      <tr>
            <td width="47%" height="27" align="right">Mode*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td width="53%"><select name="paymode">

                    <option value="">Select Payment mode</option>
                    <option value="Cash">Cash</option>
                    <option value="cheque">cheque</option>
                    <option value="DD">DD</option>
                    </select>
            </td>
        </tr>
         -->
       <!--  <tr>
            <td width="47%" height="27" align="right">Payment*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="checkbox" name="payment" id="payment" value="Yes" />Yes</td>
        </tr> -->
        
    <!--     <tr>
            <td width="47%" height="27" align="right">Required Done:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td width="53%">
                <input type="checkbox" name="fuel_sensor" id="fuel_sensor" value="Fuel Sensor" /> Fuel Sensor 
                <input type="checkbox" name="bonnet_sensor" id="bonnet_sensor" value="Bonnet Sensor" /> Bonnet Sensor </br>
                <input type="checkbox" name="rfid_reader" id="rfid_reader" value="RFID Reader" /> RFID Reader
                <input type="checkbox" name="speed_alarm" id="speed_alarm" value="Speed Alarm" /> Speed Alarm </br>
                <input type="checkbox" name="door" id="door" value="Door lock/unlock circuit" /> Door lock/unlock circuit </br> 
                <input type="checkbox" name="temperature" id="temperature" value="Temperature Sensor" /> Temperature Sensor </br>
                <input type="checkbox" name="duty_box" id="duty_box" value="Duty Box" /> Duty Box 
                <input type="checkbox" name="panic_button" id="panic_button" value="Panic Button" /> Panic Button
            </td>
        </tr> -->
        
        <tr>
            <td align="right">Sim Change:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>
            <input type="checkbox" name="sim_change" id="sim_change" value="SimChange" onchange="DropdownShow(<?echo $rows[0]['inst_id'];?>)" />Yes
            </td>
        </tr>
        <tr>
            <td height="32" align="right">New Sim No*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>
            <div id="DropdownShow">
            
            </div>
            </td> 
        </tr>

        
        <!--<tr>
            <td height="32" align="right">Installer Current Location*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="inst_cur_location" id="inst_cur_location"   value="<?php echo $rows[0]['inst_cur_location'];?>" /></td>
        </tr>-->
        
         <? if($_GET['show']=="close" && $rows[0]['instal_reinstall']=='installation'){?>

        <tr>
            <td align="right"><h2>Installation Details</h2></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td width="47%" height="27" align="right">Device Type*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td width="53%"><select name="device_type">
                            <option value="">Select device type</option>
                            <option value="New">New</option>
                            <option value="Old">Old</option>
                            <option value="Crack">Crack</option>
                            <option value="Client Device">Client Device</option>
                    </select>
            </td>
        </tr>
        <!--<tr>
        <td height="32" align="right">Reason:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="reason" id="reason" value="<?php echo $rows[0]['reason'];?>" /></td>
        </tr>-->
        
        <tr>
        
        <td height="32" align="right">Installtion made*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>
        <select name="Installtion_Made" id="Installtion_Made" onchange="DetailInstalltion(this.value,<?echo $rows[0]['inst_id']?>)">
        <option value="">Select Number</option>
        <?php  
            $row_inst_made = $rows[0]['installation_made'];
            if(($rows[0]['branch_id']==1 && $rows[0]['inter_branch']==0 && $rows[0]['instal_reinstall']=='installation') || ($rows[0]['inter_branch']==1 && $rows[0]['branch_id']!=1 && $rows[0]['instal_reinstall']=='installation'))
            {
                $inst_approve = $rows[0]['installation_approve'];
            }
            elseif($rows[0]['instal_reinstall']=='installation')
            {
                $inst_approve = $rows[0]['no_of_vehicals'];
            }
            $total = $inst_approve - $row_inst_made;
            
                for($i=1;$i<=$total;$i++)
                {
        ?>
                <option value="<?=$i;?>"><?=$i;?></option>
        <?php  } ?>
                <!--<option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>-->
                </select>
                </td>
        
        
        </tr>
        <tr>
            <td height="32" align="right">Add vehicle number*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            
            <td>
            <div id="DetailInstalltion">
            
             </div>
            </td>
        
        </tr>
   <!--      
        <tr>
            <td width="47%" height="27" align="right"><span class="style17">Billing:&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;</td>
            <td><span class="style18">
            YES <input type="radio" name="billing" id="billing" value="Yes" />
            No <input type="radio" name="billing" id="billing" value="No" />
            </span>
           <!-- REASON
            &nbsp;
            <textarea name="billing_no_reason" id="billing_no_reason" value="" ></textarea></td>-->
            </td>
        </tr> -->
        
        <tr>
             <td height="32" align="right">Reason: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
             <td><textarea name="billing_no_reason" id="billing_no_reason" value="" ></textarea></td>
        </tr>
        
       <?php } 
       
       else if($_GET['show']=="close" && $rows[0]['instal_reinstall']=='re_install'){ ?>

        <tr>
            <td align="right"><h2>Re-installation Details</h2></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td width="47%" height="27" align="right">Device Type*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td width="53%"><select name="device_type">
                            <!--<option value="">Select device type</option>
                            <option value="Old">Old</option>-->
                            <option value="Client Device">Client Device</option>
                    </select>
            </td>
        </tr>
        
        <tr>
        
        <td height="32" align="right">Installtion made*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>
        <select name="Installtion_Made" id="Installtion_Made" onchange="ReInstalltion(this.value,<?echo $rows[0]['user_id']?>)">
        <option value="">Select Number</option>
        <?php  
            $row_inst_made = $rows[0]['installation_made'];
            if($rows[0]['instal_reinstall']=='re_install')
            {
                $inst_approve = $rows[0]['no_of_vehicals'];
            }
            $total = $inst_approve - $row_inst_made;
            
                for($i=1;$i<=$total;$i++)
                {
        ?>
                <option value="<?=$i;?>"><?=$i;?></option>
        <?php  } ?>
                </select>
                </td>
        
        </tr>
        <tr>
            <td height="32" align="right">Add vehicle number*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            
            <td>
            <div id="ReInstalltion">
            
             </div>
            </td>
        
        </tr>
        <tr>
            <td width="47%" height="27" align="right"><span class="style17">Billing:&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;</td>
            <td><span class="style18">
                YES <input type="radio" name="billing" id="billing" value="Yes" />
                No <input type="radio" name="billing" id="billing" value="No" checked="checked" />
            
            </span>&nbsp;
            </td>
        </tr>
        <tr>
             <td height="32" align="right">Reason: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
             <td><textarea name="billing_no_reason" id="billing_no_reason" value="" ></textarea></td>
        </tr>
        
       <?php } ?>

        <tr>
            
            <td height="32" align="right">Time*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="rtime" id="rtime" style="width:180px" value="" /></td>
            
        </tr>
        
        <tr>
            <td align="right">Data Check:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><textarea name="data_string" id="data_string" rows="5" cols="25" placeholder="last 2 to 3 string line enter"></textarea> </td>
        </tr>

        
        <? if($_GET['show']=="backtoservice")
        {?>
        
        <tr>
        <td height="32" align="right">Reason To Back Services:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><textarea name="reason_to_back" id="reason_to_back" rows="5" cols="15"></textarea>
                <input type="hidden" name="last_reason" value="<?php echo $rows[0]['back_reason'];?>" /> </td>
        </tr>
        <?}?>
        
        <tr>
        <td height="32" align="right">
        
        
        <? if($_GET['show']=="edit")
        {
                ?><input type="submit" name="update" id="update" value="Update" onClick="return req_info2(form3)"/><?
        }
        if($_GET['show']=="close")
        {
                ?><input type="submit" name="submit" value="close" align="right" onClick="return req_info(form3)"/>&nbsp;&nbsp;&nbsp;&nbsp;<?
        }
        if($_GET['show']=="backtoservice")
        {
                ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="backservice" value="back to service" align="right" onClick="return req_info1(form3)"/><?
        } ?>
         </td>
         <td><input type="button" name="Cancel" value="Cancel" onClick="window.location = 'installations.php' " />
        
        </tr>
    </table>
</form>



<?
include("../include/footer.inc.php");

?>