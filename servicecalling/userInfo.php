<?php
error_reporting(0);
ob_start();
session_start();
include($_SERVER["DOCUMENT_ROOT"]."/service/connection.php");

$q=$_GET["user_id"];
$inst_id=$_GET["inst_id"];
$veh_reg=$_GET["veh_reg"];
$row_id=$_GET["row_id"];
$comment=$_GET["comment"];

$result="select services.id as id,services.id,veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact,round(gps_speed*1.609,0) as speed,  case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng ,devices.imei from matrix.services

join matrix.latest_telemetry on latest_telemetry.sys_service_id=services.id

join matrix.devices on devices.id=services.sys_device_id

join matrix.mobile_simcards on matrix.mobile_simcards.id=devices.sys_simcard

where services.id in

(select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (

select sys_group_id from matrix.group_users where sys_user_id=(".$q.")))";

                                                               
$data=select_query_live_con($result);
 


if(isset($_GET['action']) && $_GET['action']=='getdata')
{
 
//$result = mysql_query("SELECT veh_reg FROM vehicles WHERE user_id = '".$q."'");

$msg= "<table border='0' style='width:50%;'><tr>";
//$i=0;
//while($row = mysql_fetch_array($data))
for($k=0;$k<count($data);$k++) 
  {
    if($k%3==0) {
    $msg .="</tr><tr>";
    }
  $msg .="<td>".$data[$k]['veh_reg']."</td><td><input type='checkbox' name='$k' value='".$data[$k]['veh_reg']."' style='width=20px;'/></td>" ;
  }
 
 
  $msg .="</tr></table>";
 
  echo $msg;
}


if(isset($_GET['action']) && $_GET['action']=='getdataddl')
{
 
  $result2="select services.id as id,services.id,veh_reg from matrix.services
 
where services.id in

(select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (

select sys_group_id from matrix.group_users where sys_user_id=(".$q.")))";
                                                               
$data2=select_query_live_con($result2);
//$result = mysql_query("SELECT veh_reg FROM vehicles WHERE user_id = '".$q."'");ShowDeviceInfo(this.value);

$msg=' <select name="veh_reg" id="<?=$select_id?>" onchange="getdeviceImei(this.value,\'TxtDeviceIMEI\');getInstaltiondate(this.value,\'date_of_install\');getdevicemobile(this.value,\'Devicemobile\');">
<option value="0">Select Vehicle No</option>';
//$i=0;
//while($row = mysql_fetch_array($data))
for($k=0;$k<count($data2);$k++)
  {
    if($k%3==0) {
    $msg .="</tr><tr>";
    }
  $msg .="<option value=".$data2[$k]['veh_reg'].">".$data2[$k]['veh_reg']."</option>";
 
  }
 
 
  $msg .="</select>";
 
  echo $msg;
}

 
if(isset($_GET['action']) && $_GET['action']=='InstallationbackComment')
{

    $query = "SELECT sales_comment FROM installation_request  where id=".$row_id;
     $row=select_query($query);

     $Updateapprovestatus="update installation_request set sales_comment='".$row[0]["sales_comment"]."<br/>".date("Y-m-d H:i:s")." - " .$comment."',installation_status='8' where id=".$row_id;
     mysql_query($Updateapprovestatus);
}

if(isset($_GET['action']) && $_GET['action']=='InstallationClosed')
{
    $Updateapprovestatus="update installation set inst_close_reason='".date("Y-m-d H:i:s")." - ".$comment."',installation_status='5' where id=".$row_id;
    mysql_query($Updateapprovestatus);
}


/*if(isset($_GET['action']) && $_GET['action']=='InstallationConfirm')
{
    $Updateapprovestatus="update installation set installation_status=1 where id=".$row_id;
    mysql_query($Updateapprovestatus);
   
}*/

if(isset($_GET['action']) && $_GET['action']=='InstallationConfirm')
{
     $query = "SELECT * FROM installation_request  where id=".$row_id;
     $row=select_query($query);
     $approve_inst = $row[0]["installation_approve"];
     $time_status = $row[0]["atime_status"];
   
    for($N=1;$N<=$approve_inst;$N++)
    {       
        if($time_status == "Till")
        {
           
            $installation = "INSERT INTO installation(`inst_req_id`, `req_date`, `request_by`,sales_person,`user_id`, `company_name`, no_of_vehicals, location,model,time, contact_number,installed_date, status, contact_person, dimts,demo, veh_type,comment, immobilizer_type, payment_req,required, IP_Box,branch_id,installation_status, Zone_area,atime_status,`inter_branch`, branch_type, instal_reinstall, approve_status, installation_approve, approve_date, fuel_sensor, bonnet_sensor, rfid_reader, speed_alarm, door_lock_unlock, temperature_sensor, duty_box, panic_button) VALUES('".$row[0]["id"]."','".$row[0]["req_date"]."','".$row[0]["request_by"]."','".$row[0]["sales_person"]."', '".$row[0]["user_id"]."', '".$row[0]["company_name"]."','1','".$row[0]["location"]."','".$row[0]["model"]."','".$row[0]["time"]."','".$row[0]["contact_number"]."','".$row[0]["installed_date"]."',1,'".$row[0]["contact_person"]."','".$row[0]["dimts"]."','".$row[0]["demo"]."','".$row[0]["veh_type"]."','".$row[0]["comment"]."','".$row[0]["immobilizer_type"]."','".$row[0]["payment_req"]."','".$row[0]["required"]."','".$row[0]["IP_Box"]."','".$row[0]["branch_id"]."','1','".$row[0]["Zone_area"]."','".$row[0]["atime_status"]."','".$row[0]["inter_branch"]."','".$row[0]["branch_type"]."','".$row[0]["instal_reinstall"]."','".$row[0]["approve_status"]."','1','".$row[0]["approve_date"]."','".$row[0]["fuel_sensor"]."','".$row[0]["bonnet_sensor"]."','".$row[0]["rfid_reader"]."','".$row[0]["speed_alarm"]."','".$row[0]["door_lock_unlock"]."','".$row[0]["temperature_sensor"]."','".$row[0]["duty_box"]."','".$row[0]["panic_button"]."')";
               
            $execute_inst=mysql_query($installation);
        }
       
        if($time_status == "Between")
        {
            $installation = "INSERT INTO installation(`inst_req_id`, `req_date`, `request_by`,sales_person,`user_id`, `company_name`, no_of_vehicals, location,model,time,totime, contact_number,installed_date, status, contact_person, dimts,demo, veh_type,comment, immobilizer_type, payment_req,required, IP_Box,branch_id,installation_status, Zone_area,atime_status,`inter_branch`, branch_type, instal_reinstall, approve_status, installation_approve, approve_date, fuel_sensor, bonnet_sensor, rfid_reader, speed_alarm, door_lock_unlock, temperature_sensor, duty_box, panic_button) VALUES('".$row[0]["id"]."','".$row[0]["req_date"]."','".$row[0]["request_by"]."','".$row[0]["sales_person"]."', '".$row[0]["user_id"]."', '".$row[0]["company_name"]."','1','".$row[0]["location"]."','".$row[0]["model"]."','".$row[0]["time"]."','".$row[0]["totime"]."','".$row[0]["contact_number"]."','".$row[0]["installed_date"]."',1,'".$row[0]["contact_person"]."','".$row[0]["dimts"]."','".$row[0]["demo"]."','".$row[0]["veh_type"]."','".$row[0]["comment"]."','".$row[0]["immobilizer_type"]."','".$row[0]["payment_req"]."','".$row[0]["required"]."','".$row[0]["IP_Box"]."','".$row[0]["branch_id"]."','1','".$row[0]["Zone_area"]."','".$row[0]["atime_status"]."','".$row[0]["inter_branch"]."','".$row[0]["branch_type"]."','".$row[0]["instal_reinstall"]."','".$row[0]["approve_status"]."','1','".$row[0]["approve_date"]."','".$row[0]["fuel_sensor"]."','".$row[0]["bonnet_sensor"]."','".$row[0]["rfid_reader"]."','".$row[0]["speed_alarm"]."','".$row[0]["door_lock_unlock"]."','".$row[0]["temperature_sensor"]."','".$row[0]["duty_box"]."','".$row[0]["panic_button"]."')";
               
                $execute_inst=mysql_query($installation);
        }
    }
           
    $Updateapprovestatus="update installation_request set installation_status=1 where id=".$row_id;
    mysql_query($Updateapprovestatus);
   
}


if(isset($_GET['action']) && $_GET['action']=='total')
    {
         
        echo mysql_num_rows($data);
    }

if(isset($_GET['action']) && $_GET['action']=='companyname')
    {
         
        $sql="select `group`.name as company from matrix.group_users left join matrix.`group` on group_users.sys_group_id=`group`.id where group_users.sys_user_id=".$q;

        $row=select_query_live_con($sql);

        echo $row[0]["company"];
    }


if(isset($_GET['action']) && $_GET['action']=='creationdate')
    {
         
        $sql="select * from matrix.users where id=".$q;

        $row=select_query_live_con($sql);

        echo date("d-M-Y",strtotime($row[0]["sys_added_date"]));
    }
   
   
    if(isset($_GET['action']) && $_GET['action']=='creationdate')
    {
         
        $sql="select * from matrix.users where id=".$q;

        $row=select_query_live_con($sql);

        echo date("d-M-Y",strtotime($row[0]["sys_added_date"]));
    }



if(isset($_GET['action']) && $_GET['action']=='deviceImei')
    {
     
    $sql1="select imei from matrix.devices where id in (select sys_device_id from matrix.services where veh_reg='".$veh_reg."') limit 1";
    $row=select_query_live_con($sql1);
     
 echo $row[0]["imei"];
    }
   
   
if(isset($_GET['action']) && $_GET['action']=='deviceMobile')
    {
         
    $sql1="select mobile_no from matrix.mobile_simcards where id in ( select sys_simcard from matrix.devices where id in (select sys_device_id from matrix.services where veh_reg='".$veh_reg."'))";
    $row=select_query_live_con($sql1);
     
     echo $row[0]["mobile_no"];
    }

if(isset($_GET['action']) && $_GET['action']=='clientExtension')
{
    $Updateapprovestatus="update add_client_information set final_status=1,close_date='".date("Y-m-d H:i:s")."', 
 req_close_by='".$_SESSION['username']."' where id=".$row_id;
    select_query($Updateapprovestatus);
   
}

if(isset($_GET['action']) && $_GET['action']=='clientExtensionDelete')
{
    $Updateapprovestatus="DELETE FROM internalsoftware.add_client_information WHERE  `id`=".$row_id;
    select_query($Updateapprovestatus);
   
}


if(isset($_GET['action']) && $_GET['action']=='Instaltiondate')
    {
         
        $sql="select sys_created from matrix.services where veh_reg='".$veh_reg."' limit 1";
 
        $row=select_query_live_con($sql);

        echo date("d-M-Y",strtotime($row[0]["sys_created"]));
    }
   
 

    if(isset($_GET['action']) && $_GET['action']=='getrowSales')
    {
?>         
<style type="text/css">

#databox{width:840px; height:500px; margin: 30px auto auto; border:1px solid #bfc0c1; font-family:Arial, Helvetica, sans-serif; font-size:13px; font-weight:normal; color:#3f4041;}
.heading{ font-family:Arial, Helvetica, sans-serif; font-size:30px; font-weight:700; word-spacing:5px; text-align:center;   color:#3E3E3E;   background-color:#ECEFE7; margin-bottom:10px;  }
.dataleft{float:left; width:400px; height:400px; margin-left:10px; border-right:1px solid #bfc0c1;}
.dataright{float:left; width:400px; height:400px; margin-left:19px;}
td{padding-right:20px; padding-left:20px;}
</style>
    <?   
            $RowId=$_GET["RowId"];
            $tablename=$_GET["tablename"];
           
     

If($tablename=="new_account_creation")
        {
    $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
    ?>
   
   
   
   
   
    <div id="databox">
<div class="heading">New account creation</div>
<div class="dataleft"><table cellspacing="2" cellpadding="2">
     
<tbody><tr><td><strong>Date</strong></td><td><?echo $row[0]["date"];?></td></tr>
<tr><td><strong>Created By</strong>r</td><td><?echo $row[0]["created_by"];?></td></tr>

<tr><td><strong>Account Manage</strong>r</td><td><?echo $row[0]["account_manager"];?></td></tr>
<tr><td><strong>Company</strong></td><td><?echo $row[0]["company"];?></td></tr>
<tr><td><strong>Potential</strong></td>  <td><?echo $row[0]["potential"];?></td></tr>
<tr><td><strong>Contact Person</strong></td><td><?echo $row[0]["contact_person"];?></td></tr>
<tr><td><strong>Contact Number</strong></td><td><?echo $row[0]["contact_number"];?></td></tr>
<tr><td><strong>Billing Name</strong></td><td><?echo $row[0]["billing_name"];?></td></tr>
<tr><td><strong>Billing Address</strong></td><td><?echo $row[0]["billing_address"];?></td></tr><tr><td>
<tr><td><strong>mode of payment</strong></td><td><?echo $row[0]["mode_of_payment"];?></td>
</tr>
<tr><td><strong>Device Price</strong> </td><td><?echo $row[0]["device_price"];?></td></tr>   
<tr><td><strong>Vat(5%)</strong> </td><td><?echo $row[0]["device_price_vat"];?></td></tr>   
<tr><td><strong>Total </strong></td><td><?echo $row[0]["device_price_total"];?></td></tr>   
<tr><td><strong>Rent </strong></td><td><?echo $row[0]["device_rent_Price"];?></td></tr>   
<tr><td><strong>Service Tex(12.36%)</strong> </td><td><?echo $row[0]["device_rent_service_tax"];?></td></tr>   
<tr><td><strong>Total Rent</strong></td><td><?echo $row[0]["DTotalREnt"];?></td></tr>

</tbody></table></div>



<div class="dataright">
<table cellspacing="2" cellpadding="2"><tbody>
 
<tr><td><strong>Vehicle type</strong></td><td><?echo $row[0]["vehicle_type"];?></td></tr>
<tr><td><strong>Immobilizer (Y/N)</strong></td><td><?echo $row[0]["immobilizer"];?></td></tr>
<tr><td><strong>AC (ON/OFF)</strong></td><td><?echo $row[0]["ac_on_off"];?></td></tr>
<tr><td><strong>E_mail ID</strong></td>  <td><?echo $row[0]["email_id"];?></td></tr>
<tr><td><strong>User Name</strong></td><td><?echo $row[0]["user_name"];?></td></tr>
<tr><td><strong>Password</strong></td><td><?echo $row[0]["user_password"];?></td></tr>

<tr><td colspan="2">-------------------------------------------</td> </tr>

 <tr><td><strong>Admin Approval</strong></td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>
<tr><td><strong>Account Comment</strong></td>  <td><?echo $row[0]["account_comment"];?></td></tr>
<tr><td><strong>Support Comment</strong></td><td><?echo $row[0]["support_comment"];?></td></tr>
<tr><td><strong>Admin Comment</strong></td><td><?echo $row[0]["admin_comment"];?></td></tr>
<tr><td><strong>Closed Date</strong></td><td><?

if($row[0]["final_status"]==1)
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}?></td></tr>
</tbody></table>
</div>
</div>
 


    <?}


    else If($tablename=="stop_gps")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
    ?><div > <div style=" padding-left: 50px;">
    <h1>Stop Gps</h1> </div>
    <div class="table">
    <table cellspacing="2" cellpadding="2" style=" padding-left: 100px;width: 500px;">
     

<tr><td>Date     </td><td><?echo $row[0]["date"];?></td></tr>
<tr><td>Account Manager     </td><td><?echo $row[0]["acc_manager"];?></td></tr>
<? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["client"];
    $rowuser=select_query($sql);
    ?>
<tr><td>Client User Name     </td><td><?echo $rowuser[0]["sys_username"];?></td></tr>
<tr><td>Company Name     </td><td><?echo $row[0]["company"];?></td></tr>
<tr><td>Total No Of Vehicle     </td><td><?echo $row[0]["tot_no_of_vehicle"];?></td></tr>
<tr><td>Vehicle to Stop GPS </td><td><?echo $row[0]["no_of_vehicle"];?></td></tr>
<tr><td>Persent Status Of</td><td>:---</td></tr>
<tr><td>Location     </td><td><?echo $row[0]["ps_of_location"];?></td></tr>
<tr><td>OwnerShip     </td><td><?echo $row[0]["ps_of_ownership"];?></td></tr>
<tr><td>Reason     </td><td><?echo $row[0]["reason"];?></td></tr>
<tr><td>Sales Action     </td><td><?echo $row[0]["sales_action"];?></td></tr>
<tr><td colspan="2">-------------------------------------------</td> </tr>

 <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>
<tr><td>Account Comment</td>  <td><?echo $row[0]["account_comment"];?></td></tr>
<tr><td>Support Comment</td><td><?echo $row[0]["support_comment"];?></td></tr>
<tr><td>Admin Comment</td><td><?echo $row[0]["admin_comment"];?></td></tr>
<tr><td>Closed Date</td><td><?
if($row[0]["final_status"]==1)
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td></tr>
    </table>
    </div>
    </div>

    <? }
else If($tablename=="no_bills")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
           
           
          
            
            
    ?><div > <div style=" padding-left: 50px;">
    <h1>No Bills</h1> </div>
    <div class="table">
    <table cellspacing="2" cellpadding="2" style=" padding-left: 100px;width: 500px;">
     

<tr><td>Date     </td><td><?echo $row[0]["date"];?></td></tr>
<tr><td>Account Manager     </td><td><?echo $row[0]["acc_manager"];?></td></tr>
<? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["client"];
    $rowuser=select_query($sql);
    ?>
<tr><td>Client User Name     </td><td><?echo $rowuser[0]["sys_username"];?></td></tr>
<tr><td>Company Name     </td><td><?echo $row[0]["company_name"];?></td></tr>
<tr><td>Device Model    </td><td><?echo $row[0]["device_model"];?></td></tr>
<tr><td>Vehicle Num</td><td><?echo $row[0]["reg_no"];?></td></tr>
<tr><td>imei_device</td><td><?echo $row[0]["imei_device"];?></td></tr>
<tr><td>date_of_install     </td><td><?echo $row[0]["date_of_install"];?></td></tr>
<tr><td>device_mobilenum     </td><td><?echo $row[0]["device_mobilenum"];?></td></tr>
<tr><td>duration     </td><td><?echo $row[0]["duration"];?></td></tr>
<tr><td>No Bill For    </td><td><?echo $row[0]["rent_device"];?></td></tr>
<tr><td>Reason    </td><td><?echo $row[0]["reason"];?></td></tr>
<tr><td>Provision Bill    </td><td><?echo $row[0]["provision_bill"];?></td></tr>
<tr><td colspan="2">-------------------------------------------</td> </tr>

 <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>
<tr><td>Account Comment</td>  <td><?echo $row[0]["account_comment"];?></td></tr>
<tr><td>Support Comment</td><td><?echo $row[0]["support_comment"];?></td></tr>
<tr><td>Admin Comment</td><td><?echo $row[0]["admin_comment"];?></td></tr>

<tr><td>Closed Date</td><td><?
if($row[0]["final_status"]==1)
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td></tr>
    </table>
    </div>
    </div>

    <? }
     
 
    else If($tablename=="discount_details")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
           
           
          
            
            
    ?><div > <div style=" padding-left: 50px;">
    <h1>Discount</h1> </div>
    <div class="table">
    <table cellspacing="2" cellpadding="2" style=" padding-left: 100px;width: 500px;">
     

<tr><td>Date     </td><td><?echo $row[0]["date"];?></td></tr>
<tr><td>Account Manager     </td><td><?echo $row[0]["acc_manager"];?></td></tr>
<? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user"];
    $rowuser=select_query($sql);
    ?>
<tr><td>Client User Name     </td><td><?echo $rowuser[0]["sys_username"];?></td></tr>
<tr><td>Company Name     </td><td><?echo $row[0]["client"];?></td></tr>
<tr><td>Total No Of Vehicle    </td><td><?echo $row[0]["total_no_of_vehicles"];?></td></tr>
<tr><td>Discount For</td><td><?echo $row[0]["rent_device"];?></td></tr>
<tr><td>Month</td><td><?echo $row[0]["mon_of_dis_in_case_of_rent"];?></td></tr>
<tr><td>Discount Amount     </td><td><?echo $row[0]["dis_amt"];?></td></tr>
<tr><td>After Discount     </td><td><?echo $row[0]["amt_rec_after_dis"];?></td></tr>
<tr><td>Before Discount     </td><td><?echo $row[0]["amt_before_dis"];?></td></tr>
 
<tr><td>Reason    </td><td><?echo $row[0]["reason"];?></td></tr>
<tr><td>Service Action</td><td><?echo $row[0]["service_action"];?></td></tr> 

<tr><td colspan="2">-------------------------------------------</td> </tr>

 <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>
<tr><td>Account Comment</td>  <td><?echo $row[0]["account_comment"];?></td></tr>
<tr><td>Support Comment</td><td><?echo $row[0]["support_comment"];?></td></tr>
<tr><td>Admin Comment</td><td><?echo $row[0]["admin_comment"];?></td></tr>
<tr><td>Closed Date</td><td><?
if($row[0]["final_status"]==1)
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td></tr>
    </table>
    </div>
    </div>

    <? }
    elseIf($tablename=="sub_user_creation")
        {

          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

    ?><div > <div style=" padding-left: 50px;">
    <h1>Sub User Creation
</h1> </div>
    <div class="table">
    <table cellspacing="2" cellpadding="2" style=" padding-left: 100px;width: 500px;">
     
 <tr><td>Date    </td><td><?echo $row[0]["date"];?></td></tr>
<tr><td>Account Manager     </td><td><?echo $row[0]["acc_manager"];?></td></tr>
<? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["main_user_id"];
    $rowuser=select_query($sql);
    ?>
<tr><td>Client User Name     </td><td><?echo $rowuser[0]["sys_username"];?></td></tr>

 <tr><td>Company Name     </td><td><?echo $row[0]["company"];?></td></tr>
<tr><td>Total No Of Vehicle     </td><td><?echo $row[0]["tot_no_of_vehicles"];?></td></tr>
<tr><td>Vehicle to move     </td><td><?echo $row[0]["reg_no_of_vehicle_to_move"];?></td></tr>
<tr><td>Contact Person     </td><td><?echo $row[0]["contact_person"];?></td></tr>
<tr><td>Contact Number     </td><td><?echo $row[0]["contact_number"];?></td></tr>
<tr><td>Sub-User Name     </td><td><?echo $row[0]["name"];?></td></tr>
<tr><td>Password</td><td><?echo $row[0]["req_sub_user_pass"];?></td></tr>
<tr><td>Main User Separate</td><td><?echo $row[0]["billing_separate"];?></td></tr>
<tr><td>Billing Name</td><td><?echo $row[0]["billing_name"];?></td></tr>
<tr><td>Billing Address</td><td><?echo $row[0]["billing_address"];?></td></tr>
<tr><td>Reason</td><td><?echo $row[0]["reason"];?></td></tr>

<tr><td colspan="2">-------------------------------------------</td> </tr>

 <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>
<tr><td>Account Comment</td>  <td><?echo $row[0]["account_comment"];?></td></tr>
<tr><td>Support Comment</td><td><?echo $row[0]["support_comment"];?></td></tr>
<tr><td>Admin Comment</td><td><?echo $row[0]["admin_comment"];?></td></tr>
<tr><td>Closed Date</td><td><?
if($row[0]["final_status"]==1)
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td></tr>

    </table>
    </div>
    </div>


    <? }
    else If($tablename=="deactivation_of_account")
        {
         $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);


 
    ?><div > <div style=" padding-left: 50px;">
    <h1>Deactivation Of Account</h1> </div>
    <div class="table">
    <table cellspacing="2" cellpadding="2" style=" padding-left: 100px;width: 500px;">
     
<tr><td>Date </td><td><?echo $row[0]["date"];?></td></tr>    
<tr><td>Account Manager </td><td><?echo $row[0]["acc_manager"];?></td></tr>    
<? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
<tr><td>Client User Name     </td><td><?echo $rowuser[0]["sys_username"];?></td></tr>   
<tr><td>Company Name </td><td><?echo $row[0]["company"];?></td></tr>    
<tr><td>Total No Of Vehicle </td><td><?echo $row[0]["total_no_of_vehicles"];?></td></tr>    
<tr><td>Deactivate </td><td><?echo $row[0]["deactivate_temp"];?></td></tr>      
<tr><td>Reason</td><td><?echo $row[0]["reason"];?></td></tr>

<tr><td colspan="2">-------------------------------------------</td> </tr>

 <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>
<tr><td>Account Comment</td>  <td><?echo $row[0]["account_comment"];?></td></tr>
<tr><td>Support Comment</td><td><?echo $row[0]["support_comment"];?></td></tr>
<tr><td>Admin Comment</td><td><?echo $row[0]["admin_comment"];?></td></tr>
<tr><td>Closed Date</td><td><?
if($row[0]["final_status"]==1)
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td></tr>

    </table>
    </div>
    </div>


    <?}
    else If($tablename=="del_form_debtors")
        {
        $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

    ?><div > <div style=" padding-left: 50px;">
    <h1>Delete From Debtors</h1> </div>
    <div class="table">
    <table cellspacing="2" cellpadding="2" style=" padding-left: 100px;width: 500px;">
     
<tr><td>Date </td><td><?echo $row[0]["date"];?></td></tr>    
<tr><td>Account Manager </td><td><?echo $row[0]["acc_manager"];?></td></tr>    
<? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
<tr><td>Client User Name     </td><td><?echo $rowuser[0]["sys_username"];?></td></tr>   
<tr><td>Company Name </td><td><?echo $row[0]["company"];?></td></tr>    
<tr><td>Total No Of Vehicle </td><td><?echo $row[0]["total_no_of_vehicle"];?></td></tr>    
<tr><td>Date Of Creation  </td><td><?echo $row[0]["date_of_creation"];?></td></tr>      
<tr><td>Reason</td><td><?echo $row[0]["reason"];?></td></tr>

<tr><td colspan="2">-------------------------------------------</td> </tr>

 <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>
<tr><td>Account Comment</td>  <td><?echo $row[0]["account_comment"];?></td></tr>
<tr><td>Support Comment</td><td><?echo $row[0]["support_comment"];?></td></tr>
<tr><td>Admin Comment</td><td><?echo $row[0]["admin_comment"];?></td></tr>
<tr><td>Closed Date</td><td><?
if($row[0]["final_status"]==1)
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td></tr>

    </table>
    </div>
    </div>


    <?}
    else If($tablename=="software_request")
        {
        $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);



    ?><div > <div style=" padding-left: 50px;">
    <h1>Software Request</h1> </div>
    <div class="table">
    <table cellspacing="2" cellpadding="2" style=" padding-left: 100px;width: 500px;">
     
 

<tr><td>Date</td><td><?echo $row[0]["date"];?></td></tr>     
<tr><td>Account Manager</td><td><?echo $row[0]["acc_manager"];?></td></tr>     
<? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["main_user_id"];
    $rowuser=select_query($sql);
    ?>
<tr><td>Client User Name     </td><td><?echo $rowuser[0]["sys_username"];?></td></tr>   

<tr><td>Company Name</td><td><?echo $row[0]["company"];?></td></tr>     
<tr><td>Total No Of Vehicle</td><td><?echo $row[0]["total_no_of_vehicle"];?></td></tr>     
<tr><td>Potential</td><td><?echo $row[0]["potential"];?></td></tr> 

<tr><td>Requested Software:---</td><td></td></tr>     

<tr><td>Google Map</td><td><?echo $row[0]["rs_google_map"];?></td></tr>     
<tr><td>Admin </td><td><?echo $row[0]["rs_admin"];?></td></tr>    
<tr><td><tr><td>Type Of Alert</td><td><?echo $row[0]["alert"];?></td></tr>     
<tr><td>Other Alert/ Info</td><td><?echo $row[0]["rs_others"];?></td></tr>     
<tr><td>Customize Report </td><td><?echo $row[0]["rs_customize_report"];?></td></tr>    
<tr><td>Alert Contact Number</td><td><?echo $row[0]["alert_contact`"];?></td></tr>     
<tr><td>Client Contact Number </td><td><?echo $rowuser[0]["mobile_number"];?></td></tr>
<tr><td colspan="2">-------------------------------------------</td> </tr>

 <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>
<tr><td>Account Comment</td>  <td><?echo $row[0]["account_comment"];?></td></tr>
<tr><td>Support Comment</td><td><?echo $row[0]["support_comment"];?></td></tr>
<tr><td>Admin Comment</td><td><?echo $row[0]["admin_comment"];?></td></tr>

<tr><td>Closed Date</td><td><?
if($row[0]["final_status"]==1)
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td></tr>

    </table>
    </div>
    </div>
    <?
    }

    else If($tablename=="transfer_the_vehicle")
        {
        $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);



    ?><div > <div style=" padding-left: 50px;">
    <h1>Transfer Vehicle</h1> </div>
    <div class="table">
    <table cellspacing="2" cellpadding="2" style=" padding-left: 100px;width: 500px;">
     
 
 

<tr><td>Date</td><td><?echo $row[0]["date"];?></td></tr>     
<tr><td>Account Manager </td><td><?echo $row[0]["acc_manager"];?></td></tr>


<? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["transfer_to_user"];
    $rowuser=select_query($sql);
    ?>
<tr><td>Client User Name     </td><td><?echo $rowuser[0]["sys_username"];?></td></tr>   



 <tr><td>Company Name </td><td><?echo $row[0]["transfer_from_company"];?></td></tr>    
<tr><td>Total No Of Vehicle </td><td><?echo $row[0]["date"];?></td></tr>    
<tr><td>Vehicle to move </td><td><?echo $row[0]["transfer_from_reg_no"];?></td></tr>



<tr><td>Transfer To:--</td><td> </td></tr>
   
<? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row[0]["transfer_to_user"];
    $rowuser=select_query($sql);
    ?>
<tr><td>Transfer User Name     </td><td><?echo $rowuser[0]["sys_username"];?></td></tr>   

<tr><td>Transfer Company Name     </td><td><?echo $row[0]["transfer_to_company"];?></td></tr>
<tr><td>Billing</td><td><?echo $row[0]["transfer_to_billing"];?></td></tr>     
    
<tr><td>Reason</td><td><?echo $row[0]["reason"];?></td></tr>

<tr><td colspan="2">-------------------------------------------</td> </tr>

 <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>
<tr><td>Account Comment</td>  <td><?echo $row[0]["account_comment"];?></td></tr>
<tr><td>Support Comment</td><td><?echo $row[0]["support_comment"];?></td></tr>
<tr><td>Admin Comment</td><td><?echo $row[0]["admin_comment"];?></td></tr>

<tr><td>Closed Date</td><td><?
if($row[0]["final_status"]==1)
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}

?></td></tr>

 

    </table>
    </div>
    </div>
    <?
    }
    }
?> 