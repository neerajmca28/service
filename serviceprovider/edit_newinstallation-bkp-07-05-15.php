<?php
include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");

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


<div class="top-bar">
<h1><? echo $Header;?></h1>
</div>
<div class="table">
<?
if(isset($_REQUEST['action'])==edit)
        {
        $id=$_GET['id'];
        $query=mysql_query("SELECT * FROM installation WHERE id='$id'",$dblink2);

        $rows=mysql_fetch_array($query);
         $inst_id=$rows['inst_id'];

        $sales=mysql_fetch_array(mysql_query("select name from sales_person where id='$rows[sales_person]' ",$dblink2));
}
//print_r($rows);
if(isset($_POST['submit']))
{

        $name=$_POST['name'];
        $no_of_veh=$_POST['veh_reg'];
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
        $inst_cur_location=$_POST['inst_cur_location'];
        $reason=$_POST['reason'];
        $time=$_POST['time'];
        $pending=$_POST['pending'];
        $rtime=$_POST['rtime'];
        $newstatus=$_POST['newstatus'];

        $billing=$_POST['billing'];
        if($billing=="") { $billing="No"; }
        $payment=$_POST['payment'];
        if($payment=="") { $payment="No"; }

        $billing_no_reason=$_POST['billing_no_reason'];

        //$query1=("UPDATE installation SET  inst_name='$inst_name',inst_cur_location='$inst_cur_location',reason='$reason',rtime='$rtime',close_date=inst_date='".date("Y-m-d")."',newpending='0',newstatus='0' WHERE id='$id'");

        //echo "UPDATE installation SET payment_status='$payment_status',amount='$amount',paymode='$paymode',reason='$reason',rtime='$rtime',close_date=inst_date='".date("Y-m-d")."',newpending='0',newstatus='0' ,billing='$billing',payment='$payment', installation_status=5   WHERE id='$id'";



/**************INVENTORY DATABASE CONNECTION(SERVER DATABASE PATH)**********************/

include($_SERVER["DOCUMENT_ROOT"]."/service/sqlconnection.php");


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

  $DeviceId=$_POST["DeviceIMEI$N"];
$machine=$_POST["machine$N"];
  $ac=$_POST["ac$N"];
  $immobilizer=$_POST["immobilizer$N"];
  $immobilizer_type=$_POST["immobilizer_type$N"];

 if($veh_reg!="" && $DeviceId!="" && $ac!="" && $immobilizer!="")
                {

$resultDevice = mssql_query("select itgc_id ,device_imei,sim.phone_no,device_status,item_master.item_name from device left join sim on device .sim_id =sim.sim_id
left join item_master on item_master.item_id =device.device_type  where device_imei='".$DeviceId."'");
$rowDevice = mssql_fetch_array($resultDevice);

mssql_query("update device set device_status=65 where device_imei='".$DeviceId."'");


mysql_query("INSERT INTO `new_device_addition` (`inst_id`, `date`, `inst_close_date`, `acc_manager`, `client`, `user_id`, `vehicle_no`, `ac`,`immobilizer`,`immobilizer_type`, `device_type`, `device_model`, `device_id`, `device_imei`, `device_sim_num`,installtionRequestID,dimts,sales_manager,inst_name,billing_if_no_reason,billing,comment) VALUES ('".$id."','".$date."','".$rtime."','".$acc_manager."','".$rows['company_name']."',  '".$rows['user_id']."', '".$veh_reg."',  '".$ac."', '".$immobilizer."', '".$immobilizer_type."', '".$device_type."',  '".$rowDevice['item_name']."',  '".$rowDevice['itgc_id']."',  '".$rowDevice['device_imei']."',  '".$rowDevice['phone_no']."', '".$rows['id']."','".$rows['dimts']."','".$sales['name']."','".$rows['inst_name']."','".$billing_no_reason."','".$billing."','".$machine."')",$dblink2);
  $errMSG="";
        }
        else
                {
                $errMSG="Please enter vehicle number, device IMEI, AC and Immobilizer";
                }
        }

if($errMSG=="")
        {

                mysql_query("update installer set status=0 where inst_id='".$rows['inst_id']."'",$dblink2);
			
			if($installation_approve == $total_inst_made)
			{
                mysql_query("UPDATE installation SET payment_status='$payment_status',amount='$amount',paymode='$paymode',reason='$reason',rtime='$rtime',close_date='".date("Y-m-d")."',inst_date='".date("Y-m-d")."',newpending='0', newstatus='0', device_type='$device_type', billing='$billing', payment='$payment',installation_made='$total_inst_made', installation_status=5   WHERE id='$id'",$dblink2);
			}
			elseif($no_of_veh == $total_inst_made)
			{
                mysql_query("UPDATE installation SET payment_status='$payment_status',amount='$amount',paymode='$paymode',reason='$reason',rtime='$rtime',close_date='".date("Y-m-d")."',inst_date='".date("Y-m-d")."',newpending='0', newstatus='0', device_type='$device_type', billing='$billing', payment='$payment',installation_made='$total_inst_made', installation_status=5   WHERE id='$id'",$dblink2);
			}
			else
			{
				 mysql_query("UPDATE installation SET payment_status='$payment_status',amount='$amount',paymode='$paymode',reason='$reason',rtime='$rtime',close_date='".date("Y-m-d")."',inst_date='".date("Y-m-d")."',newpending='0', newstatus='0', device_type='$device_type', billing='$billing', payment='$payment',installation_made='$total_inst_made', installation_status=15   WHERE id='$id'",$dblink2);
			}
			
			
			
         header("location:installations.php");

	}
}

if(isset($_POST['update']))
        {
        $inst_name1=$_POST['inst_name1'];
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

                mysql_query("update installer set status=1 where inst_name='$inst_name'",$dblink2);
                mysql_query("update installer set status=0 where inst_name='$inst_name1'",$dblink2);
        }
        else
        {

                  $inst_idname=$_POST['inst_name_all'];

                $ArrInst=explode("#",$_POST['inst_name_all']);
                $inst_name =$ArrInst[1];
                $inst_id =$ArrInst[0];
                 mysql_query("update installer set status=0 where inst_name='$inst_name1'",$dblink2);
                $job_type=2;
        }


$pending=mysql_query("UPDATE installation SET inst_id='$inst_id', job_type='$job_type' , inst_name='$inst_name',inst_cur_location='$inst_cur_location' ,billing='$billing',payment='$payment' , installation_status=2  WHERE id='$id'",$dblink2);
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

        $update_bcakreason = "UPDATE installation SET newpending='1',newstatus='0' ,back_reason='".$last_reason."<br/>".$back_reason." - ".date("Y-m-d H:i:s")."' , installation_status=3 WHERE id=$id";
        $pending = mysql_query($update_bcakreason,$dblink2);

        /*$pending=mysql_query("UPDATE installation SET newpending='1',newstatus='0' ,back_reason='$back_reason' , installation_status=3 WHERE id='$id'");*/
        mysql_query("update installer set status=0 where inst_name='$inst_name1'",$dblink2);
        header("location:installations.php");
}
?>
 <script type="text/javascript">
function req_info(form3)
{
 //var totalcount = document.getElementById('Installtion_Made').value;
 var totalcount = document.form3.Installtion_Made.value;
 //alert(totalcount);
if(document.form3.rtime.value =="")
  {
   alert("please enter time");
   document.form3.rtime.focus();
   return false;
   }

  var close_tym = document.form3.rtime.value;
  var current_tym = document.form3.current_date.value;
  if(close_tym >= current_tym)
  {
        alert("Please Select only Back & current Date");
        return false;
  }

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

   if(billing_yes == true)
         {
                   if(document.form3.amount.value =="")
                   {
                           alert("please enter amount");
                           document.form3.amount.focus();
                           return false;
                   }

        }
        if(billing_no == true)
         {
                   if(document.form3.billing_no_reason.value =="")
                   {
                           alert("please enter Reason");
                           document.form3.billing_no_reason.focus();
                           return false;
                   }

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
function ltrim(stringToTrim) {
        return stringToTrim.replace(/^\s+/,"");
        }
</script>
 <script type="text/javascript">

        $(function () {

                $("#rtime").datetimepicker({});
        });

    </script>

<form method="post" action="" name="form3">
    <table width="80%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2"><input name="id" type="hidden" id="id" value="<?php echo $rows['id'];?>">
             <input type="hidden" name="done_inst" id="done_inst" value="<?=$rows['installation_made'];?>"/>
             </td>
        </tr>
        
        <tr>
            <td  colspan="2"><?=$errMSG;?></td>
        </tr>
        <tr>
            <td  align="right">Sales Person:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="name" id="name" readonly value="<?php  echo $sales['name'];?>" />
                    <input type="hidden" name="current_date" id="current_date" value="<?=date("Y-m-d H:i");?>"/></td>
        </tr>
        <tr>
            <td  align="right">Client Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="name" id="name" readonly value="<?php echo $rows['company_name'];?>" /></td>
        </tr>
        <tr>
            <td align="right">No.Of Vehicle :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="veh_reg" id="veh_reg" readonly value="<?php echo $rows['no_of_vehicals'];?>" />
                    <input type="hidden" name="inst_approve" id="inst_approve" value="<?=$rows['installation_approve'];?>"/></td>
        </tr>
        
        <tr>
            <td height="32" align="right">Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="location" readonly id="location" value="<?php echo $rows['location'];?>" /></td>
        </tr>
        <tr>
            <td height="32" align="right">Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="time" readonly id="time" value="<?php echo $rows['time'];?>" /></td>
        </tr>
        <tr>
            <td height="32" align="right">Vehicle Type :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="veh_type" id="veh_type" readonly value="<?php echo $rows['veh_type'];?>" /></td>
        </tr>
        
        <tr>
            <td height="32" align="right">Device Model:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="model" readonly id="model" value="<?php echo $rows['model'];?>" /></td>
        </tr>
        <tr>
            <td height="32" align="right">Immobilizer :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="immobilizer_type" id="immobilizer_type" readonly value="<?php echo $rows['immobilizer_type'];?>" /></td>
        </tr>
        <tr>
            <td height="32" align="right">DIMTS :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="dimts" id="dimts" readonly value="<?php echo $rows['dimts'];?>" /></td>
        </tr>
        <tr>
            <td height="32" align="right">Demo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="demo" id="demo" readonly value="<?php echo $rows['demo'];?>" /></td>
        </tr>
        <tr>
            <td height="32" align="right">Payment Request:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="payment_req" id="payment_req" readonly value="<?php echo $rows['payment_req'];?>" /></td>
        </tr>
        <tr>
        
            <td height="32" align="right">Contact No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="contact_number" readonly id="contact_number" value="<?php echo $rows['contact_number'];?>" /></td>
        </tr>
        
        <tr>
        
            <td height="32" align="right">Installer Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="text" name="inst_name1" id="inst_name1" readonly value="<?php echo $rows['inst_name'];?>" /></td>
        </tr>


        <?if($_GET['show']=="edit")
        {?>
        <tr>
        <td colspan="2" align="center" >
        <input type="radio" value="OngoingJob" id="assignJob" name="assignJob" onclick="document.getElementById('inst_name').style.display = 'block';document.getElementById('inst_name_all').style.display = 'none';">Ongoing Job <input type="radio" value="PendingJob" id="assignJob" name="assignJob" onclick="document.getElementById('inst_name').style.display = 'none';document.getElementById('inst_name_all').style.display = 'block';">Pending Job
        </td>
        </tr>
        
        <tr>
        <td height="32" align="right">Change Installer Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        
        
        <td>
		<?php
        
        $query="SELECT inst_id,inst_name,concat(inst_id,'#',inst_name) as idname FROM installer where status=0 and branch_id=".$_SESSION['BranchId'];
        $result=mysql_query($query,$dblink2);
        
        ?>
        <? //$name1=$row['inst_id']."#".$row['inst_name']; ?>
         <select name="inst_name" id="inst_name" style="display:none"  ><option value="0">Select Name</option>
        <? while($row=mysql_fetch_array($result)) { ?>
        <option value=<?=$row['idname'];?>><?=$row['inst_name']?></option>
        <? } ?>
        </select>
        <?php
        
        $query="SELECT inst_id,inst_name,concat(inst_id,'#',inst_name) as idname FROM installer where branch_id=".$_SESSION['BranchId'];
        $result=mysql_query($query,$dblink2);
        
        ?>
         <select name="inst_name_all" id="inst_name_all"  style="display:none" ><option value="0">Select Name</option>
        <? while($row=mysql_fetch_array($result)) { ?>
        <option value=<?=$row['idname'];?>><?=$row['inst_name']?></option>
        <? } ?>
        </select>
        </td>
        
        </tr>
        <?}?>
        <tr>
        <td height="32" align="right">Contact No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="contact_number" readonly id="contact_number" value="<?php echo $rows['contact_number'];?>" /></td>
        </tr>
        <tr>
        <td width="47%" height="27" align="right">Payment Status*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="53%"><select name="payment_status">
                <option value="">Select Payment Status</option>
                <option value="Not Required">Not Required</option>
                <option value="Collected">Collected</option>
                <option value="Not collected">Not collected</option>
                </select></td>
        </tr>
        <tr>
        <td height="32" align="right">Amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="amount" id="amount"   value="<?php echo $rows['amount'];?>" /></td>
        </tr>
        <tr>
        <td width="47%" height="27" align="right">Mode*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="53%"><select name="paymode">
                <option value="">Select Payment mode</option>
                <option value="Cash">Cash</option>
                <option value="cheque">cheque</option>
                <option value="DD">DD</option>
                </select></td>
        </tr>
        
        <tr><td width="47%" height="27" align="right">Payment*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>
        <input type="checkbox" name="payment" id="payment" value="Yes" /></td></tr>
        
        <tr>
        <td height="32" align="right">Installer Current Location*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="inst_cur_location" id="inst_cur_location"   value="<?php echo $rows['inst_cur_location'];?>" /></td>
        </tr>
        <tr>
        <td width="47%" height="27" align="right">Device Type*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="53%"><select name="device_type">
                        <option value="">Select device type</option>
                        <option value="new">New</option>
                        <option value="old">Old</option>
                        <option value="crack">Crack</option>
                        <option value="client device">Client Device</option>
                </select></td>
        </tr>
        <!--<tr>
        <td height="32" align="right">Reason:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="reason" id="reason" value="<?php echo $rows['reason'];?>" /></td>
        </tr>-->
        
        <tr>
        
        <td height="32" align="right">Installtion made*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>
        <select name="Installtion_Made" id="Installtion_Made" onchange="DetailInstalltion(this.value,<?echo $rows['inst_id']?>)">
        <option value="">Select Number</option>
        <?php  
			$row_inst_made = $rows['installation_made'];
			if(($rows['branch_id']==1 && $rows['inter_branch']==0) || ($rows['inter_branch']==1 && $rows['branch_id']!=1))
			{
				$inst_approve = $rows['installation_approve'];
			}else{
				$inst_approve = $rows['no_of_vehicals'];
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
        
        <tr><td width="47%" height="27" align="right"><span class="style17">Billing:&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;</td>
        <td><span class="style18">
                YES <input type="radio" name="billing" id="billing" value="Yes" />
            No <input type="radio" name="billing" id="billing" value="No" />
        <!--YES
            <input type="checkbox" name="billing" id="billing" value="Yes" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             NO
             <input type="checkbox" name="billing" id="billing" value="No" /> -->
             REASON
        </span>&nbsp;
          <textarea name="billing_no_reason" id="billing_no_reason" value="" ></textarea></td>
        </td></tr>
        
        <tr>
        
        <td height="32" align="right">Time*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="rtime" id="rtime" style="width:180px" value="" /></td>
        
        
        </tr>
        

        
        <?if($_GET['show']=="backtoservice")
        {?>
        
        <tr>
        <td height="32" align="right">Reason To Back Services:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><textarea name="reason_to_back" id="reason_to_back" rows="5" cols="15"></textarea>
                <input type="hidden" name="last_reason" value="<?php echo $rows['back_reason'];?>" /> </td>
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
include("include/footer.inc.php");

?>