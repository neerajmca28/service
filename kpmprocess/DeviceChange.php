<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu_service.php');
include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

/*include($_SERVER['DOCUMENT_ROOT']."/format/include/header.php");
include($_SERVER['DOCUMENT_ROOT']."/format/include/leftmenu_service.php");
include($_SERVER["DOCUMENT_ROOT"]."/format/sqlconnection.php");*/

$action=$_GET['action'];
$id=$_GET['id'];
$page=$_POST['page'];

if($action=='edit' or $action=='editp')
{
		$result = select_query("select * from device_change where id=$id");

		$main_user_iddata = select_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE Userid='".$result[0]['user_id']."'");
		//$data = mysql_fetch_assoc($main_user_iddata);

		$result[0]['device_imei'] = trim($result[0]['device_imei']);
		$client_imei = str_replace("_","",$result[0]['device_imei']);

		/*$ffc_query= mssql_query("select imei_no, old_client_name, ffc_date, old_veh from device_replace_ffc where 
								replace_device_imei='".$client_imei."' and status='100' order by replaced_date desc");
		$ffc_count = mssql_num_rows($ffc_query);
		$ffc_details = mssql_fetch_array($ffc_query);*/
		
		$ffc_query_inv = select_query_inventory("select imei_no, old_client_name, ffc_date, old_veh from inventory.device_replace_ffc where 
												replace_device_imei='".$client_imei."' and status='100' order by replaced_date desc");

}

?>
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script>

$(document).ready(function() {
    $('.commentarea').keydown(function(event) {
        if (event.keyCode == 13) {
                        var ffc_imei = document.myForm.rdd_ffc_device_imei.value;
                        //alert(ffc_imei);
            //this.myForm.submit();
                        $.ajax({
                                type:"GET",
                                url:"userInfo.php?action=FFCDeviceRslt",

                                data:"ffc_imei="+ffc_imei,
                                success:function(msg){

                                //alert(msg);
                                //$("#value").html(msg) ;
                                 var tmparr = msg.split("~") ;

                                 var old_ffc_user  = tmparr['0'] ;
                                 var veh_no        = tmparr['1'] ;
                                 var ffc_date      = tmparr['2'] ;
                                 //alert(ffc_date);

                                document.getElementById("last_ffc_user").value = old_ffc_user;
                                document.getElementById("ffc_veh").value = veh_no;
                                document.getElementById("ffc_date").value = ffc_date;

                                }
                        });

            return false;
         }
    });
});
</script>-->

<div class="top-bar">
<h1>Device Change</h1>
</div>
<div class="table">

<?php
$billing_amt=0;
$reason=0;

if(isset($_POST["submit"]))
{
        $date = date("Y-m-d H:i:s");
        $acc_manager = $_SESSION['user_name'];
        $client_id = $_POST["client_id"];
       
        /*$client=$_POST["company"];
        $user_id=$_POST["main_user_id"];*/
        /*$veh_reg=$_POST["veh_reg"];
        $device_imei=$_POST["TxtDeviceIMEI"];
        $Devicemobile=$_POST["Devicemobile"];
        $date_of_install=$_POST["date_of_install"];*/

        $device_model=$_POST["Device_model"];
        $Device_type=$_POST["Device_type"];

        $rdd_device_model=$_POST["rdd_device_model"];

        $rdd_device_imei=$_POST["rdd_device_imei"];
        $rdd_device_id = $_POST["device_id"];

        $rdddevice_imei=$_POST["device_imei"];
        $rddDevicemobile=$_POST["rddDevicemobile"];

        $replace_user_id=$_POST["replace_user_id"];
        $ReplaceCompany=$_POST["ReplaceCompany"];
        $replaceDevice_model=$_POST["replaceDevice_model"];
        $replaceDeviceIMEI=$_POST["replaceDeviceIMEI"];
        //$ReplaceCompany=$_POST["replaceDevicemobile"];
        $replaceDevicemobile=$_POST["replaceDevicemobile"];
        $replacedate_of_install=$_POST["replacedate_of_install"];

        $veh_reg_replce=$_POST["veh_reg_replce"];
        $TxtReason=$_POST["TxtReason"];
        $Txtrdd_date=$_POST["rdd_date"];
        $sales_manager=$_POST["sales_manager"];

        $billing=$_POST["billing"];
        $payment_status=$_POST["payment_status"];
        $billing1=$_POST["billing1"];

        $rdd_ffc_device_model=$_POST["rdd_ffc_device_model"];
        //$ffc_device_id=$_POST["ffc_device_id"];
        $rdd_ffc_device_imei=$_POST["rdd_ffc_device_imei"];
        $billing_ffc=$_POST["billing_ffc"];

        /*if($Device_type=='New') {
                $billing_amount=$_POST["billing_amount_new"];
        }
        else*/
        if($Device_type=='Old') {
                $billing_reason=$_POST["billing_reason_old"];

        }
        else if($Device_type=='FFC') {
                //$billing_amount=$_POST["billing_amount_ffc"];
                $billing_reason=$_POST["billing_reason_ffc"];

        }

        $last_ffc_user=$_POST["last_ffc_user"];
        $ffc_veh=$_POST["ffc_veh"];
        $ffc_date=$_POST["ffc_date"];

        $replace_client_id=$_POST["replace_client_id"];
        $replace_Device_model = $_POST['replace_Device_model'];
        if($_POST["deleted_imei"]==""){  $deleted_imei=$result[0]['rdd_device_imei'];      }
        else{  $deleted_imei=$_POST["deleted_imei"];  }

        $billing2=$_POST["billing2"];

        $service_comment=$_POST["service_comment"];

        /*if($veh_reg=="") {
                $veh_reg_edit=$result[0]['reg_no'];
        }
        else {
        $veh_reg_edit=$veh_reg;
        }*/

        if($veh_reg_replce=="") {
                $veh_reg_replce_edit = $result[0]['rdd_reg_no'];
        }
        else {
                $veh_reg_replce_edit = $veh_reg_replce;
        }


if($action=='edit')
   {

        if($Device_type=="New")
         {
                /* If Device Type New then Billing Default Yes.*/
               
            /*if($client_id == 5011)
            {
                $query="update device_change set sales_manager='".$sales_manager."', device_model='".$device_model."', rdd_device_model='".$rdd_device_model."', rdd_device_id='".$rdd_device_id."', rdd_device_imei='".$rdd_device_imei."', rdd_device_type='".$Device_type."',rdd_date='".$Txtrdd_date."', rdd_reason='".$TxtReason."',billing='".$billing."', account_comment='Direct Process - ".$date."', approve_status='1' where id=$id";
               
                mysql_query($query);
               
                $veh_reg_id = select_query_live_con("select id from matrix.services where veh_reg='".$result[0]['reg_no']."'");
                
                $veh_service_id = $veh_reg_id[0]['id'];
                //mysql_query("update matrix.services set device_removed_service=0 where id='$veh_service_id'",$dblink);
				
				 $remove_st = 0;
				 $data1 = array('device_removed_service' => $remove_st);
				 $condition = array('id' => $veh_service_id);
			
				 update_query_live_con('matrix.services', $data1, $condition);
				 update_query_live('matrix.services', $data1, $condition);
				
            }
            else
            {*/
               
             	$query="update device_change set sales_manager='".$sales_manager."', device_model='".$device_model."', rdd_device_model='".$rdd_device_model."', rdd_device_id='".$rdd_device_id."', rdd_device_imei='".$rdd_device_imei."', rdd_device_type='".$Device_type."',rdd_date='".$Txtrdd_date."', rdd_reason='".$TxtReason."',billing='".$billing."' where id=$id";
                select_query($query);
            /*}*/
			
			$new_imei_query = mssql_query("update device set device_status=65 where device_imei='".trim($rdd_device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
       
       		$new_imei_query2 = mssql_query("update device_repair set device_status=65 where current_record=1 and device_imei='".trim($rdd_device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
			
			
			$new_imei_query_inv = select_query_inventory("update inventory.device set device_status=65 where device_imei='".trim($rdd_device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
       
       		$new_imei_repair_inv = select_query_inventory("update inventory.device_repair set device_status=65 where current_record=1 and device_imei='".trim($rdd_device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");


         }
         elseif($Device_type=="FFC")
         {

                $query="update device_change set sales_manager='".$sales_manager."', device_model='".$device_model."', rdd_device_model='".$rdd_ffc_device_model."', rdd_device_imei='".$rdd_ffc_device_imei."', last_ffc_user='".$last_ffc_user."', ffc_veh_no='".$ffc_veh."', ffc_date='".$ffc_date."', rdd_device_type='".$Device_type."',rdd_date='".$Txtrdd_date."', rdd_reason='".$TxtReason."',billing='".$billing_ffc."', billing_reason='".$billing_reason."' where id=$id";
               
                select_query($query);
				
				$new_imei_query = mssql_query("update device set device_status=65 where device_imei='".trim($rdd_ffc_device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
       
       		    $new_imei_query2 = mssql_query("update device_repair set device_status=65 where current_record=1 and device_imei='".trim($rdd_ffc_device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
				
				$new_imei_query_inv = select_query_inventory("update inventory.device set device_status=65 where device_imei='".trim($rdd_ffc_device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
       
       		    $new_imei_query_inv2 = select_query_inventory("update inventory.device_repair set device_status=65 where current_record=1 and device_imei='".trim($rdd_ffc_device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");

         }
        elseif($Device_type=="Deleted")
         {

                $query="update device_change set sales_manager='".$sales_manager."',device_model='".$device_model."', rdd_device_type='".$Device_type."', rdd_username='".$replace_client_id."', rdd_companyname='".$ReplaceCompany."', rdd_device_model='".$replace_Device_model."', rdd_device_imei='".$deleted_imei."', rdd_date='".$Txtrdd_date."', rdd_reason='".$TxtReason."',billing='".$billing2."' where id=$id";

                select_query($query);
				
				$new_imei_query = mssql_query("update device set device_status=65 where device_imei='".trim($deleted_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
       
       		    $new_imei_query2 = mssql_query("update device_repair set device_status=65 where current_record=1 and device_imei='".trim($deleted_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
				
				$new_imei_query_inv = select_query_inventory("update inventory.device set device_status=65 where device_imei='".trim($deleted_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
       
       		    $new_imei_query_inv2 = select_query_inventory("update inventory.device_repair set device_status=65 where current_record=1 and device_imei='".trim($deleted_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
				
         }
         else
         {

                 $query="update device_change set sales_manager='".$sales_manager."',device_model='".$device_model."', rdd_device_type='".$Device_type."', rdd_username='".$replace_user_id."', rdd_companyname='".$ReplaceCompany."', rdd_device_model='".$replaceDevice_model."', rdd_device_imei='".$replaceDeviceIMEI."',rdd_reg_no='".$veh_reg_replce_edit."', rdd_device_mobile_num='".$replaceDevicemobile."', rdd_date_replace='".$replacedate_of_install."', rdd_date='".$Txtrdd_date."', rdd_reason='".$TxtReason."',billing='".$billing1."', billing_reason='".$billing_reason."' where id=$id";

                select_query($query);
				
				$new_imei_query = mssql_query("update device set device_status=65 where device_imei='".trim($replaceDeviceIMEI)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
       
       		    $new_imei_query2 = mssql_query("update device_repair set device_status=65 where current_record=1 and device_imei='".trim($replaceDeviceIMEI)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
				
				
				$new_imei_query_inv = select_query_inventory("update inventory.device set device_status=65 where device_imei='".trim($replaceDeviceIMEI)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
       
       		    $new_imei_query_inv2 = select_query_inventory("update inventory.device_repair set device_status=65 where current_record=1 and device_imei='".trim($replaceDeviceIMEI)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
				
         }

       
        echo "<script>document.location.href ='list_device_change.php'</script>";

  }
  else
  {

         if($Device_type=="New")
         {
          /* If Device Type New then Billing Default Yes.*/

                $query="INSERT INTO `device_change` (`date`,acc_manager, `sales_manager`, `client`, `user_id`, `device_model`, `device_imei`, `reg_no`, `date_of_install`, `mobile_no`, `rdd_device_model`, `rdd_device_imei`,`rdd_device_id`,`rdd_date`, `rdd_reason`,`rdd_device_type`,billing) VALUES ('".$date."','".$acc_manager."','".$sales_manager."','".$client."','".$user_id."','".$device_model."','".$device_imei."','".$veh_reg."','".$date_of_install."','".$Devicemobile."','".$rdd_device_model."','".$rdd_device_imei."','".$rdd_device_id."','".$Txtrdd_date."','".$TxtReason."','".$Device_type."','".$billing."','".$billing_amount."')";

				$new_imei_query = select_query_inventory("update inventory.device set device_status=65 where device_imei='".trim($rdd_device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
       
        $new_imei_query2 = select_query_inventory("update inventory.device_repair set device_status=65 where current_record=1 and device_imei='".trim($rdd_device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
         }
         else if($Device_type=="FFC")
         {

                $query="INSERT INTO `device_change` (`date`,acc_manager, `sales_manager`, `client`, `user_id`, `device_model`, `device_imei`, `reg_no`, `date_of_install`, `mobile_no`, `rdd_device_model`, `rdd_device_imei`, last_ffc_user, ffc_veh_no, ffc_date,`rdd_date`, `rdd_reason`,`rdd_device_type`,billing,billing_reason) VALUES ('".$date."','".$acc_manager."','".$sales_manager."','".$client."','".$user_id."','".$device_model."','".$device_imei."','".$veh_reg."','".$date_of_install."','".$Devicemobile."','".$rdd_ffc_device_model."','".$rdd_ffc_device_imei."', '".$last_ffc_user."', '".$ffc_veh."','".$ffc_date."','".$Txtrdd_date."','".$TxtReason."','".$Device_type."','".$billing_ffc."','".$billing_amount."','".$billing_reason."')";

				$new_imei_query = select_query_inventory("update inventory.device set device_status=65 where device_imei='".trim($rdd_ffc_device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
       
        $new_imei_query2 = select_query_inventory("update inventory.device_repair set device_status=65 where current_record=1 and device_imei='".trim($rdd_ffc_device_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
         }
        else if($Device_type=="Deleted")
         {

                $query="INSERT INTO `device_change` (`date`, acc_manager,`sales_manager`, `client`, `user_id`, `device_model`, `device_imei`, `reg_no`, `date_of_install`, `mobile_no`, `rdd_username`, `rdd_companyname`, `rdd_device_model`, `rdd_device_imei`, `rdd_date`, `rdd_reason`, rdd_device_type, billing) VALUES ('".$date."','".$acc_manager."','".$sales_manager."','".$client."','".$user_id."','".$device_model."','".$device_imei."','".$veh_reg."','".$date_of_install."','".$Devicemobile."','".$replace_client_id."','".$ReplaceCompany."','".$replace_Device_model."','".$deleted_imei."','".$Txtrdd_date."','".$TxtReason."','".$Device_type."','".$billing2."')";

				$new_imei_query = select_query_inventory("update inventory.device set device_status=65 where device_imei='".trim($deleted_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
       
        $new_imei_query2 = select_query_inventory("update inventory.device_repair set device_status=65 where current_record=1 and device_imei='".trim($deleted_imei)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
         }
         else
         {

                $query="INSERT INTO `device_change` (`date`, acc_manager,`sales_manager`, `client`, `user_id`, `device_model`, `device_imei`, `reg_no`, `date_of_install`, `mobile_no`, `rdd_username`, `rdd_companyname`, `rdd_device_model`, `rdd_device_imei`, `rdd_reg_no`,`rdd_device_mobile_num`,`rdd_date_replace`,`rdd_date`, `rdd_reason`, rdd_device_type, billing, billing_reason) VALUES ('".$date."','".$acc_manager."','".$sales_manager."','".$client."','".$user_id."','".$device_model."','".$device_imei."','".$veh_reg."','".$date_of_install."','".$Devicemobile."','".$replace_user_id."','".$ReplaceCompany."','".$replaceDevice_model."','".$replaceDeviceIMEI."','".$veh_reg_replce."','".$replaceDevicemobile."','".$replacedate_of_install."','".$Txtrdd_date."','".$TxtReason."','".$Device_type."','".$billing1."','".$billing_reason."')";

				$new_imei_query = select_query_inventory("update inventory.device set device_status=65 where device_imei='".trim($replaceDeviceIMEI)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
       
        $new_imei_query2 = select_query_inventory("update inventory.device_repair set device_status=65 where current_record=1 and device_imei='".trim($replaceDeviceIMEI)."' and device_status not in(84,85,86,79,68,105,62,70,94,95,83,81,66,75,82,57,63,116,103)");
         }

         mysql_query($query);
         
 
         echo "<script>document.location.href ='list_device_change.php'</script>";

   }

}


?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
        $(function() {
                $( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });

                $( "#datepickercheque" ).datepicker({ dateFormat: "yy-mm-dd" });

        });

function NewOldDeviceDiv12(radioValue)
{
        //alert(radioValue);

   if(radioValue=="New")
        {
                document.getElementById('NewDevice').style.display = "block";
                document.getElementById('OldDevice').style.display = "none";
                document.getElementById('FFCDevice').style.display = "none";
                document.getElementById('deleteDevice').style.display = "none";
        }
        else if(radioValue=="Old")
        {
                document.getElementById('NewDevice').style.display = "none";
                document.getElementById('OldDevice').style.display = "block";
                document.getElementById('FFCDevice').style.display = "none";
                document.getElementById('deleteDevice').style.display = "none";
        }
        else if(radioValue=="FFC")
        {
                document.getElementById('NewDevice').style.display = "none";
                document.getElementById('OldDevice').style.display = "none";
                document.getElementById('FFCDevice').style.display = "block";
                document.getElementById('deleteDevice').style.display = "none";
        }
        else if(radioValue=="Deleted")
        {
                document.getElementById('NewDevice').style.display = "none";
                document.getElementById('OldDevice').style.display = "none";
                document.getElementById('FFCDevice').style.display = "none";
                document.getElementById('deleteDevice').style.display = "block";
        }
        else
        {
                document.getElementById('NewDevice').style.display = "none";
                document.getElementById('OldDevice').style.display = "none";
                document.getElementById('FFCDevice').style.display = "none";
                document.getElementById('deleteDevice').style.display = "none";
        }

}

function NewOldDeviceDiv(radioValue)
{

   if(radioValue=="New")
        {
                document.getElementById('NewDevice').style.display = "block";
                document.getElementById('OldDevice').style.display = "none";
                document.getElementById('FFCDevice').style.display = "none";
                document.getElementById('deleteDevice').style.display = "none";
                /*document.getElementById('NewDevice1').style.display = "none";
                document.getElementById('OldDevice1').style.display = "none";
                document.getElementById('FFCDevice1').style.display = "none";*/
        }
        else if(radioValue=="Old")
        {
                document.getElementById('NewDevice').style.display = "none";
                document.getElementById('OldDevice').style.display = "block";
                document.getElementById('FFCDevice').style.display = "none";
                document.getElementById('deleteDevice').style.display = "none";
                /*document.getElementById('NewDevice1').style.display = "none";
                document.getElementById('OldDevice1').style.display = "none";
                document.getElementById('FFCDevice1').style.display = "none";*/
        }
        else if(radioValue=="FFC")
        {
                document.getElementById('NewDevice').style.display = "none";
                document.getElementById('OldDevice').style.display = "none";
                document.getElementById('FFCDevice').style.display = "block";
                document.getElementById('deleteDevice').style.display = "none";
                /*document.getElementById('NewDevice1').style.display = "none";
                document.getElementById('OldDevice1').style.display = "none";
                document.getElementById('FFCDevice1').style.display = "none";*/
        }
        else if(radioValue=="Deleted")
        {
                document.getElementById('NewDevice').style.display = "none";
                document.getElementById('OldDevice').style.display = "none";
                document.getElementById('FFCDevice').style.display = "none";
                document.getElementById('deleteDevice').style.display = "block";
        }
        else
        {
                document.getElementById('NewDevice').style.display = "none";
                document.getElementById('OldDevice').style.display = "none";
                document.getElementById('FFCDevice').style.display = "none";
                document.getElementById('deleteDevice').style.display = "none";
                /*document.getElementById('NewDevice1').style.display = "none";
                document.getElementById('OldDevice1').style.display = "none";
                document.getElementById('FFCDevice1').style.display = "none";*/
        }

}


function validateForm()
{
        if(document.myForm.sales_manager.value=="")
        {
          alert("please Select Sales Manager") ;
          document.myForm.sales_manager.focus();
          return false;
        }

        /* if(document.myForm.TxtMainUserId.value=="")
        {
          alert("please choose Client Name") ;
          document.myForm.TxtMainUserId.focus();
          return false;
        }  */
        if(document.myForm.Device_model.value=="")
        {
          alert("please Select Device Model") ;
          document.myForm.Device_model.focus();
          return false;
        }
        if(document.myForm.Device_type.value=="")
        {
          alert("please Select Replace With") ;
          document.myForm.Device_type.focus();
          return false;
        }

        if(document.myForm.Device_type.value=="New")
        {

          if(document.myForm.rdd_device_model.value=="")
          {
                  alert("please Enter New Device Model") ;
                  document.myForm.rdd_device_model.focus();
                  return false;
          }
          if(document.myForm.TxtDModelNo.value=="")
          {
                  alert("please Enter New Device Id") ;
                  document.myForm.TxtDModelNo.focus();
                  return false;
          }
          if(document.myForm.rdd_device_imei.value=="")
          {
                  alert("please Enter New Device Imei") ;
                  document.myForm.rdd_device_imei.focus();
                  return false;
          }
          var billing_chk = document.myForm.billing[0].checked;
   var billing_chk1 = document.myForm.billing[1].checked;
   var billing_chk2 = document.myForm.billing[2].checked;
   if(billing_chk  == false && billing_chk1  == false && billing_chk2  == false)
   {
   alert("please Select Billing");
   return false;
    }
          /*if(billing_chk  == true && document.myForm.billing_amount.value=="")
          {
                 alert("please Enter New Device Amount");
                 document.myForm.billing_amount.focus();
                 return false;
           }*/
          /*if(document.myForm.rddDevicemobile.value=="")
          {
                  alert("please Enter Mobile No.") ;
                  document.myForm.rddDevicemobile.focus();
                  return false;
          }
          var rddDevicemobile=document.myForm.rddDevicemobile.value;
          if(rddDevicemobile!="")
                {
                var length=rddDevicemobile.length;

                        if(length < 9 || length > 15 || rddDevicemobile.search(/[^0-9\-()+]/g) != -1 )
                        {
                                alert('Please enter valid mobile number');
                                document.myForm.rddDevicemobile.focus();
                                document.myForm.rddDevicemobile.value="";
                                return false;
                        }
                }*/

        }

        else if(document.myForm.Device_type.value=="FFC")
        {

          if(document.myForm.rdd_ffc_device_model.value=="")
          {
                  alert("please Select Replace Device Model") ;
                  document.myForm.rdd_ffc_device_model.focus();
                  return false;
          }
          /*if(document.myForm.ffc_device_id.value=="")
          {
                  alert("please Enter Replace Device Id") ;
                  document.myForm.ffc_device_id.focus();
                  return false;
          }
          if(document.myForm.rdd_ffc_device_imei.value=="")
          {
                  alert("please Enter Replace Device Imei") ;
                  document.myForm.rdd_ffc_device_imei.focus();
                  return false;
          }*/
          var billing_ffc = document.myForm.billing_ffc[0].checked;
          var billing_ffc1 = document.myForm.billing_ffc[1].checked;
          if(billing_ffc  == false && billing_ffc1  == false)
          {
                 alert("please Select Replace Device Billing");
                 return false;
           }
          /*if(billing_ffc  == true && document.myForm.billing_amount_ffc.value=="")
          {
                 alert("please Enter Replace Device Amount");
                 document.myForm.billing_amount_ffc.focus();
                 return false;
           }*/
          if(billing_ffc1  == true && document.myForm.billing_reason_ffc.value=="")
          {
                 alert("please Enter Replace Device Billing Reason");
                 document.myForm.billing_reason_ffc.focus();
                 return false;
           }

        }

        else if(document.myForm.Device_type.value=="Old")
        {
                if(document.myForm.replace_user_id.value=="")
                {
                        alert("please Enter Replace Device User Name") ;
                        document.myForm.replace_user_id.focus();
                        return false;
                }
                if(document.myForm.veh_reg_replce.value=="0")
                {
                        alert("please Select Vehicle No.") ;
                        document.myForm.veh_reg_replce.focus();
                        return false;
                }
                if(document.myForm.replaceDevice_model.value=="")
                {
                        alert("please Enter Replace Device Model") ;
                        document.myForm.replaceDevice_model.focus();
                        return false;
                }
                var billing1_chk = document.myForm.billing1[0].checked;
                var billing1_chk1 = document.myForm.billing1[1].checked;
                if(billing1_chk  == false && billing1_chk1  == false)
                {
                        alert("please Select Replace Device Billing");
                        return false;
                }
                if(billing1_chk1  == true && document.myForm.billing_reason1.value=="")
                {
                        alert("please Enter Billing Reason");
                        document.myForm.billing_reason1.focus();
                        return false;
                }

        }
        else if(document.myForm.Device_type.value=="Deleted")
        {
                if(document.myForm.replace_client_id.value=="")
                {
                        alert("Please Select Replace Device User Name") ;
                        document.myForm.replace_client_id.focus();
                        return false;
                }
                if(document.myForm.replace_Device_model.value=="")
                {
                        alert("Please Enter Replace Device Model") ;
                        document.myForm.replace_Device_model.focus();
                        return false;
                }
                if(document.myForm.deleted_imei.value=="")
                {
                        alert("Please Select Device IMEI No.") ;
                        document.myForm.deleted_imei.focus();
                        return false;
                }
                var billing2_chk = document.myForm.billing2[0].checked;
                var billing2_chk1 = document.myForm.billing2[1].checked;
                if(billing2_chk  == false && billing2_chk1  == false)
                {
                        alert("please Select Replace Device Billing");
                        return false;
                }
        }

        if(document.myForm.rdd_date.value=="")
        {
                alert("please Enter Date") ;
                document.myForm.rdd_date.focus();
                return false;
        }
        if(document.myForm.TxtReason.value=="")
        {
                alert("please Enter Reason") ;
                document.myForm.TxtReason.focus();
                return false;
        }
}

/*function StatusValue(radioValue)
{
 if(radioValue=="Yes")
        {
        document.getElementById('amount_show').style.display = "block";
        document.getElementById('amount_show1').style.display = "none";

        }
        else if(radioValue=="No")
        {

        document.getElementById('amount_show').style.display = "none";
        document.getElementById('amount_show1').style.display = "none";
        }
        else
        {
        document.getElementById('amount_show').style.display = "none";
        document.getElementById('amount_show1').style.display = "none";
        }

} */

function StatusFFC(radioValue)
{
  if(radioValue=="No")
        {
                document.getElementById('ffc_reason_show').style.display = "block";
                /*document.getElementById('ffc_amount_show').style.display = "none";*/
                /*document.getElementById('ffc_reason_show1').style.display = "none";
                document.getElementById('ffc_amount_show1').style.display = "none";*/
        }
        else
        {
                /*document.getElementById('ffc_amount_show').style.display = "none";*/
                document.getElementById('ffc_reason_show').style.display = "none";
                /*document.getElementById('ffc_amount_show1').style.display = "none";
                document.getElementById('ffc_reason_show1').style.display = "none";*/
        }

}

function StatusFFC12(radioValue)
{
  if(radioValue=="No")
        {
                document.getElementById('ffc_reason_show').style.display = "block";
        }
        else
        {
                document.getElementById('ffc_reason_show').style.display = "none";
        }

}

function Status_old(radioValue)
{
 if(radioValue=="No")
        {
                document.getElementById('No').style.display = "block";
                /*document.getElementById('No1').style.display = "none";*/

        }
        else
        {
                document.getElementById('No').style.display = "none";
                /*document.getElementById('No1').style.display = "none";*/
        }

}

function Status_old12(radioValue)
{
 if(radioValue=="No")
        {
                document.getElementById('No').style.display = "block";

        }
        else
        {
                document.getElementById('No').style.display = "none";
        }

}

 </script>

 <form name="myForm" action="" onsubmit="return validateForm()" method="post">
    <input type="hidden" name="client_id" id="client_id" value="<?=$result[0]['user_id']?>" />
   
 <table style="width: 900px;" cellspacing="2" cellpadding="3" border="1">
                <tr>
            <td  align="right"><label  id="lbDlClient"><strong>Client User Name*:</strong></label></td>
            <td align="center">
            <label><strong><?php echo $main_user_iddata[0]['name'];?></strong> </label>
            </td>

            <td  align="right"><strong>Company Name*:</strong></td>
            <td align="center">
                <label><strong><?php echo $result[0]['client'];?></strong> </label></td>

            <td  align="right"><strong>Registration No*:</strong></td>
            <td align="center">
                    <label><strong><?php echo $result[0]['reg_no'];?></strong> </label></td>
        </tr>

        <tr>

            <td  align="right"><strong>Device IMEI*:</strong></td>
            <td align="center">
                <label ><strong><?php echo $result[0]['device_imei'];?></strong> </label></td>

            <td  align="right"><strong>Date Of Installation*:</strong></td>
            <td align="center">
                <label name="date_of_install" id="date_of_install"><strong><?php echo $result[0]['date_of_install'];?></strong> </label></td>

            <td  align="right"><strong>Device Mobile Number*: </strong></td>
            <td align="center">
                <label name="Notwoking" id="Notwoking"><strong><?php echo $result[0]['mobile_no'];?></strong> </label></td>
       </tr>
   </table>

   <table style=" padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">

         <!--<tr>
            <td>Date</td>
            <td>
                <input type="text" name="date" id="datepicker1" readonly value="<?= date("Y-m-d H:i:s")?>" /></td>
        </tr>

                <tr>
            <td>Account Manager</td>
            <td>
                <input type="text" name="account_manager" id="TxtAccManager" readonly value="<?echo $_SESSION['user_name'];?>"/></td>
        </tr>-->
        <tr>
                <td colspan="2">&nbsp;</td>
        </tr>
                 <tr>
            <td>Sales Manager</td>
                        <td><select name="sales_manager" id="sales_manager">
            <option value="" >-- select one --</option>
              <?php
				$sales_manager=select_query("SELECT name FROM sales_person where branch_id=".$_SESSION['BranchId']);
				//while ($data=mysql_fetch_assoc($sales_manager))
				for($i=0;$i<count($sales_manager);$i++)
				{
				?>

				<option name="sales_manager" value="<?=$sales_manager[$i]['name']?>" <? if($result[0]['sales_manager']==$sales_manager[$i]['name']) {?> selected="selected" <? } ?> >
				<?php echo $sales_manager[$i]['name']; ?>
				</option>
				<?php
				}
				?>

            </select>
            </td>
       </tr>
        <!--<tr>
        <td>
        Client User Name</td>
        <td>

        <!--<select name="main_user_id" id="TxtMainUserId"  onchange="showUser(this.value,'ajaxdata'); getCompanyName(this.value,'TxtCompany');">-->
        <!--<select name="main_user_id" id="TxtMainUserId">
        <option value="" name="main_user_id" id="TxtMainUserId">-- Select One --</option>
        <?php
        $main_user_id=select_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE sys_active=1 ORDER BY `name` ASC");
        //while ($data=mysql_fetch_assoc($main_user_id))
		for($u=0;$u<count($main_user_id);$u++)
        {
        ?>

        <option name="main_user_id" value="<?=$main_user_id[$u]['user_id']?>" <? if($result[0]['user_id']==$main_user_id[$u]['user_id']) {?> selected="selected" <? } ?> >
        <?php echo $main_user_id[$u]['name']; ?>
        </option>
        <?php
        }

        ?>
        </select>



        </td>
        </tr>-->


        <!--<tr>
        <td>
        Company Name</td>
        <td><input type="text" name="company" id="TxtCompany" value="<?=$result[0]['client']?>" readonly />
        </td>
        </tr>-->



        <!--<tr>
        <td>
        Registration No</td>
        <td><!-- <input type="value" name="reg_no_of_vehicle_to_move" id="TxtRegNoOfVehicle" /> -->
        <!--<div id="ajaxdata">
        <?=$result[0]['reg_no']?>
        </div>

        </td>
        </tr>-->
        <tr>
            <td>

            <label for="DeviceMOdel" id="lblDeviceModel">Device Model</label></td>
            <td>
            <select name="Device_model" id="Device_model">
            <option value=""  >-- Select One --</option>
            <?php
            $device=select_query("SELECT * FROM `device_type`");
            //while ($data=mysql_fetch_assoc($main_user_id))
			for($d=0;$d<count($device);$d++)
            {
            ?>


                <option value ="<?php echo $device[$d]['device_type'] ?>" <? if($result[0]['device_model']==$device[$d]['device_type']) {?> selected="selected" <? } ?> >
                <?php echo $device[$d]['device_type']; ?>
                </option>
            <?php
            }

            ?>
            </select></td>
        </tr>
        <!--<tr>
        <td>
        <label for="DeviceIMEI"  id="lblDeviceImie">Device IMEI</label></td>
        <td>
        <input type="text" name="TxtDeviceIMEI" id="TxtDeviceIMEI"  value="<?=$result[0]['device_imei']?>" readonly/></td>
        </tr>-->

        <!--<tr>
        <td>
        <label for="DeviceIMEI"  id="lblDeviceImie">Device Mobile Number</label></td>
        <td>
        <input type="text" name="Devicemobile" id="Devicemobile"  value="<?=$result[0]['mobile_no']?>" readonly/></td>
        </tr>

        <tr>
        <td>
        <label for="DtInstallation" id="lblDtInstallation">Date Of Installation</label></td>
        <td>
        <input type="text" name="date_of_install" id="date_of_install"  value="<?=$result[0]['date_of_install']?>" readonly/></td>
        </tr>-->




            <tr>
                <td><h2>Replaced Device Detail</h2></td>
                <td></td>
            </tr>

             <tr>
                <td>Replace with</td>
                <td><select name="Device_type" id="Device_type" onchange="NewOldDeviceDiv(this.value)" >
                    <option value="" >-- select one --</option>

                    <option value="New" <? if($result[0]['rdd_device_type']=='New') {?> selected="selected" <? } ?> > New Device</option>
                    <?php if(count($ffc_query_inv) > 0){ $ffc_value = "FFC";?>
                    <option value="FFC" <? if($ffc_value=='FFC') {?> selected="selected" <? } ?> > FFC Device</option>
                    <?php } else {?>
                    <option value="FFC" <? if($result[0]['rdd_device_type']=='FFC') {?> selected="selected" <? } ?> > FFC Device</option>
                    <?php } ?>
                    <option value="Old" <? if($result[0]['rdd_device_type']=='Old') {?> selected="selected" <? } ?> > Old Device</option>
                    <option value="Deleted" <? if($result[0]['rdd_device_type']=='Deleted') {?> selected="selected" <? } ?> > Deleted Device</option>
                    </select>
                </td>
            </tr>


            <tr>
            <td colspan="2" align="center">

                 <table  id="NewDevice" align="center" style="width: 500px;display:none;border:1"  cellspacing="5" cellpadding="5">
                    <tr>
                    <td> <label id="lblDmodel">Device Model</label></td>
                    <td>  <select name="rdd_device_model" id="rdd_device_model">
                    <option value="" >-- Select One --</option>
                    <?php
                    $device=select_query("SELECT * FROM `device_type`");
                    //while ($data=mysql_fetch_assoc($main_user_id))
					for($d=0;$d<count($device);$d++)
                    {
                    ?>
                    <option value ="<?php echo $device[$d]['device_type'] ?>" <? if($result[0]['rdd_device_model']==$device[$d]['device_type']) {?> selected="selected" <? } ?> >
                    <?php echo $device[$d]['device_type']; ?>
                    </option>
                    <?php
                    }

                    ?>
                    </select></td>
                    </tr>

                    <tr>
                    <td> <label id="lblDmodel">Device Id</label></td>
                    <td> <input type="text" name="device_id" id="TxtDModelNo" value="<?=$result[0]['rdd_device_id']?>" /></td>
                    </tr>

                    <tr>
                    <td> <label id="lblDmodel">Device IMEI.</label></td>
                    <td> <input type="text" name="rdd_device_imei" id="rdd_device_imei" value="<?=$result[0]['rdd_device_imei'];?>"/></td>
                    </tr>
                     <tr>
                    <td class="style2">
                        Billing</td>
                    <td>



                         <Input type = 'radio' Name ='billing' id="billing"   value= 'Yes' <?php if($result[0]['billing']=="Yes"){echo "checked=\"checked\"";}?> >Yes
                        <Input type = 'radio' Name ='billing' id="billing"   value= 'Lease' <?php if($result[0]['billing']=="Lease"){echo "checked=\"checked\"";}?> >Lease
                        <Input type = 'radio' Name ='billing' id="billing"   value= 'Advance' <?php if($result[0]['billing']=="Advance"){echo "checked=\"checked\"";}?> >Advance
                        </td>
                    </tr>
                    <!--<tr><td colspan="2">

                  <table  id="amount_show" align="left" style="widthF: 300px;display:none;border:1" cellspacing="5" cellpadding="5">

                        <tr>
                            <td> <label  id="lbDlAmount">Amount</label></td>
                            <td> <input type="text" name="billing_amount_new" id="billing_amount" value="<?=$result[0]['billing_amount']?>" /></td>
                       </tr>
                    </table>
                    </td>
                  </tr>-->
              </table>

                 </td>
          </tr>

           <tr>
            <td colspan="2" align="center">

             <table  id="FFCDevice" align="center" style="width: 500px;display:none;border:1"  cellspacing="5" cellpadding="5">
                <tr>
                <td> <label id="lblDmodel">Device Model</label></td>
                <td>  <select name="rdd_ffc_device_model" id="rdd_ffc_device_model">
                <option value="" >-- Select One --</option>
                <?php
                $device=select_query("SELECT * FROM `device_type`");
                //while ($data=mysql_fetch_assoc($main_user_id))
				for($d=0;$d<count($device);$d++)
                {
                ?>
                <option value ="<?php echo $device[$d]['device_type'] ?>" <? if($result[0]['rdd_device_model']==$device[$d]['device_type']) {?> selected="selected" <? } ?> >
                <?php echo $device[$d]['device_type']; ?>
                </option>
                <?php
                }

                ?>
                </select></td>
                </tr>

               <!-- <tr>
                <td> <label id="lblDmodel">Device Id</label></td>
                <td> <input type="text" name="ffc_device_id" id="ffc_device_id" value="<?=$result[0]['rdd_device_id']?>" /></td>
                </tr>-->

                <tr>
                <td> <label id="lblDmodel">Device IMEI</label></td>
                <td> <input type="text" name="rdd_ffc_device_imei" id="rdd_ffc_device_imei" value="<?=$ffc_query_inv[0]['imei_no']?>" readonly="readonly" /></td>
                </tr>
                <tr>
                <td> <label id="lblDmodel">FFC User Name</label></td>
                <td> <input type="text" name="last_ffc_user" id="last_ffc_user" value="<?=$ffc_query_inv[0]['old_client_name']?>" readonly="readonly" /></td>
                </tr>
                <tr>
                <td> <label id="lblDmodel">FFC Vehicle No</label></td>
                <td> <input type="text" name="ffc_veh" id="ffc_veh" value="<?=$ffc_query_inv[0]['old_veh']?>" readonly="readonly" /></td>
                </tr>
                <tr>
                <td> <label id="lblDmodel">FFC Date</label></td>
                <td> <input type="text" name="ffc_date" id="ffc_date" value="<?=$ffc_query_inv[0]['ffc_date']?>" readonly="readonly" /></td>
                </tr>
                 <tr>
                <td class="style2">
                    Billing</td>
                <td>

                     <Input type = 'radio' Name ='billing_ffc' id="billing_ffc"   value= 'Yes' <?php if($result[0]['billing']=="Yes"){echo 'checked="checked"'; }?>
                    onchange="StatusFFC(this.value)"
                    >Yes

                    <Input type = 'Radio' Name ='billing_ffc' id="billing_ffc"   value= 'No' <?php if($result[0]['billing']=="No"){echo "checked=\"checked\""; }?>
                    onchange="StatusFFC(this.value)"
                    >No
                    </td>
                </tr>
                <!--<tr><td colspan="2">

              <table  id="ffc_amount_show" align="left" style="width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">

                    <tr>
                        <td> <label  id="lbDlAmount">Amount</label></td>
                        <td> <input type="text" name="billing_amount_ffc" id="billing_amount_ffc" value="<?=$result[0]['billing_amount']?>" /></td>
                   </tr>
                </table>
                </td>
              </tr>-->

                <tr><td colspan="2">
                <?php //if($result[0]['billing']=='No') { ?>
                <!--<table  id="ffc_reason_show1" align="left"  style="width: 200px; border:1" cellspacing="5" cellpadding="5">

                    <tr>
                          <td> <label  id="lbDlAmount">Billing Reason</label></td>
                          <td> <input type="text" name="billing_reason_ffc" id="billing_reason_ffc" value="<?=$result[0]['billing_reason']?>" /></td>
                    </tr>
                </table>-->
            <? //} ?>

              <table  id="ffc_reason_show" align="left" style="width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">

                    <tr>
                        <td> <label  id="lbDlAmount">Billing Reason</label></td>
                        <td> <input type="text" name="billing_reason_ffc" id="billing_reason_ffc" value="<?=$result[0]['billing_reason']?>" /></td>
                   </tr>
                </table>

                </td>
              </tr>
          </table>

                </td>
         </tr>

         <tr>
            <td colspan="2" align="center">

                <table  id="OldDevice" align="center"  style="width: 500px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>
                        <td>
                        Client User Name</td><h2></h2>
                        <td>

                        <select name="replace_user_id" id="replace_user_id"  onchange="showUserreplace(this.value,'ajaxdatareplace'); getCompanyName(this.value,'ReplaceCompany');">
                        <option value="" name="main_user_id" id="TxtMainUserId">-- Select One --</option>
                        <?php
                        $main_user_id=select_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient where Userid='".$result[0]['user_id']."'");
                        //while ($data=mysql_fetch_assoc($main_user_id))
						for($c=0;$c<count($main_user_id);$c++)
                        {
                        ?>

                         <option   value="<?=$main_user_id[$c]['user_id']?>" <? if($result[0]['rdd_username']==$main_user_id[$c]['user_id']) {?> selected="selected" <? } ?> >
                    <?php echo $main_user_id[$c]['name']; ?>
                    </option>
                        <?php
                        }

                        ?>
                        </select>
                        </td>
                    </tr>


                    <tr>
                        <td>
                        Company Name</td>
                        <td><input type="text" name="ReplaceCompany" id="ReplaceCompany" value="<?=$result[0]['rdd_companyname']?>" readonly />
                        </td>
                    </tr>

                    <tr>
                        <td>
                        Registration No</td>
                        <td><!-- <input type="value" name="reg_no_of_vehicle_to_move" id="TxtRegNoOfVehicle" /> -->
                        <div id="ajaxdatareplace">
                        <?=$result[0]['rdd_reg_no']?>
                        </div>

                        </td>
                    </tr>
                    <tr>
                        <td>
                        <label for="DeviceMOdel" id="replacelblDeviceModel">Device Model</label></td>
                        <td>
                        <select name="replaceDevice_model" id="replaceDevice_model">
                        <option value="" >-- Select One --</option>
                        <?php
                        $device=select_query("SELECT * FROM `device_type`");
                        //while ($data=mysql_fetch_assoc($main_user_id))
						for($d=0;$d<count($device);$d++)
                        {
                        ?>

                        <option v value ="<?php echo $device[$d]['device_type'] ?>" <? if($result[0]['rdd_device_model']==$device[$d]['device_type']) {?> selected="selected" <? } ?> >
                        <?php echo $device[$d]['device_type']; ?>
                        </option>
                        <?php
                        }

                        ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td>
                        <label for="DeviceIMEI"  id="lblDeviceImie">Device IMEI</label></td>
                        <td>
                        <input type="text" name="replaceDeviceIMEI" id="replaceDeviceIMEI" value="<?=$result[0]['rdd_device_imei']?>" readonly/></td>
                    </tr>
                    <tr>
                        <td>
                        <label for="DeviceIMEI"  id="lblDeviceImie">Device Mobile Number</label></td>
                        <td>
                        <input type="text" name="replaceDevicemobile" id="replaceDevicemobile" value="<?=$result[0]['rdd_device_mobile_num']?>" readonly/></td>
                    </tr>
                    <tr>
                        <td>
                        <label for="DtInstallation" id="lblDtInstallation">Date Of Installation</label></td>
                        <td>
                        <input type="text" name="replacedate_of_install" id="replacedate_of_install" value="<?=$result[0]['date_of_install']?>" readonly/></td>
                    </tr>
                     <tr>
                        <td class="style2">
                            Billing</td>
                        <td>

                         <Input type = 'Radio' Name ='billing1' id="billing1"   value= 'Yes' <?php if($result[0]['billing']=="Yes"){echo "checked=\"checked\""; }?> onchange="Status_old(this.value)">Yes

                        <Input type = 'Radio' Name ='billing1' id="billing1"   value= 'No' <?php if($result[0]['billing']=="No"){echo "checked=\"checked\""; }?> onchange="Status_old(this.value)">No</td>
                    </tr>
                    <tr><td colspan="2">
                    <?php //if($result[0]['billing']=='No') { ?>
                    <!--<table  id="No1" align="left"  style="width: 300px; border:1" cellspacing="5" cellpadding="5">

                        <tr>
                              <td> <label  id="lbDlAmount">Billing Reason</label></td>
                              <td> <input type="text" name="billing_reason_old" id="billing_reason1" value="<?=$result[0]['billing_reason']?>" /></td>
                        </tr>
                    </table>-->
                <? //} ?>

                      <table  id="No" align="left"   style="width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">

                            <tr>
                                <td> <label  id="lbDlAmount">Billing Reason</label></td>
                                <td> <input type="text" name="billing_reason_old" id="billing_reason1" value="<?=$result[0]['billing_reason']?>" /></td>
                           </tr>
                        </table>
                    </td>
                  </tr>

                </table>

            </td>
          </tr>

          <tr>
            <td colspan="2" align="center">

                <table  id="deleteDevice" align="center"  style="width: 500px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>
                        <td>
                        Client User Name</td><h2></h2>
                        <td>

                        <select name="replace_client_id" id="replace_client_id"  onchange="showDeleteImei(this.value,'ClientDeletedImei');">
                        <option value="" name="main_user_id" id="TxtMainUserId">-- Select One --</option>
                        <?php
                        $deleted_user_id=select_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient where Userid='".$result[0]['user_id']."'");
                        //while ($deleted_data=mysql_fetch_assoc($deleted_user_id))
						for($ue=0;$ue<count($deleted_user_id);$ue++)
                        {
                        ?>

                         <option   value="<?=$deleted_user_id[$ue]['user_id']?>" <? if($result[0]['rdd_username']==$deleted_user_id[$ue]['user_id']) {?> selected="selected" <? } ?> >
                    <?php echo $deleted_user_id[$ue]['name']; ?>
                    </option>
                        <?php
                        }

                        ?>
                        </select>
                        </td>
                    </tr>


                    <tr>
                        <td>
                        Company Name</td>
                        <td><input type="text" name="ReplaceCompany" id="ReplaceCompany" value="<?=$result[0]['client']?>" readonly />
                        </td>
                    </tr>

                    <tr>
                        <td>
                        <label for="DeviceMOdel" id="replace_Device_model">Device Model</label></td>
                        <td>
                        <select name="replace_Device_model" id="replace_Device_model">
                        <option value="" >-- Select One --</option>
                        <?php
                        $main_user_id=select_query("SELECT * FROM `device_type`");
                        //while ($data=mysql_fetch_assoc($main_user_id))
						for($d=0;$d<count($device);$d++)
                        {
                        ?>

                       <option value ="<?php echo $device[$d]['device_type'] ?>" <? if($result[0]['rdd_device_model']==$device[$d]['device_type']) {?> selected="selected" <? } ?> >
                    <?php echo $device[$d]['device_type']; ?>
                    </option>
                    <?php
                   		 }

                        ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td>
                        <label for="DeviceIMEI"  id="lblDeviceImie">Device IMEI</label></td>
                        <td><div id="ClientDeletedImei">
                        <?=$result[0]['rdd_device_imei']?>
                        </div></td>
                    </tr>
                     <tr>
                        <td class="style2">
                            Billing</td>
                        <td>

                         <Input type = 'Radio' Name ='billing2' id="billing2"   value= 'Yes' <?php if($result[0]['billing']=="Yes"){echo "checked=\"checked\""; }?> >Yes

                        <Input type = 'Radio' Name ='billing2' id="billing2"   value= 'No' <?php if($result[0]['billing']=="No"){echo "checked=\"checked\""; }?> >No</td>
                    </tr>

                </table>

            </td>
          </tr>

     </table>
     <table style=" padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">

      <tr><td width="276"> <label  id="lbDlDate">Date</label></td>
      <td width="187"> <input type="text" name="rdd_date" id="datepicker" value="<?=$result[0]['rdd_date']?>" /></td>
      </tr>

      <tr><td> <label  id="lblReason">Reason</label></td>
      <td> <textarea rows="5" cols="25"  type="text" name="TxtReason" id="TxtReason"><?=$result[0]['rdd_reason']?></textarea>
        </td>
      </tr>

            <?php
                //if($action=='edit') {
                ?>
                 <!--<tr>
            <td class="style2">
                Service Comment</td>
            <td><textarea rows="5" cols="25"  type="text" name="service_comment" id="TxtServiceComment"></textarea>
                </td>
        </tr>-->
                <?php //} ?>
        <tr>
            <td>&nbsp;</td>
            <td align="center"> <input type="submit" name="submit" value="submit"  />&nbsp;&nbsp;
                   <input type="button" name="Cancel" value="Cancel" onClick="window.location = 'list_device_change.php' " /></td>

        </tr>
</table>

          </form>
   </div>

<?php
include("../include/footer.php");

if($ffc_count > 0)
{
?>
<script>NewOldDeviceDiv12("FFC");</script>
<?php
}
else
{
?>
<script>NewOldDeviceDiv12("<?=$result[0]['rdd_device_type'];?>");</script>
<?php
}
?>

 <script>Status_old12("<?=$result[0]['billing'];?>");StatusFFC12("<?=$result[0]['billing'];?>");</script>