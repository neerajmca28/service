<?php
include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");  
?> 
 
<?
$Header="Edit Service";
if($_GET['show']=="backtoservice")
{
	$Header="Back to Service";
}
if($_GET['show']=="close")
{
	$Header="Close";
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
$query=mysql_query("SELECT * FROM services WHERE id='$id'");

$rows=mysql_fetch_array($query);
}
//print_r($rows);

if(isset($_POST['submit']))
	{
 
	$name=$_POST['name'];
	$client=$_POST['company_name'];
	$user_id=$_POST['user_id'];
	$veh_reg=$_POST['veh_reg'];
	$new_sim_no=$_POST['sim_no'];
	$device_imei= $_POST["device_imei"];
	$Notwoking=$_POST['Notwoking'];
	$location=$_POST['location'];
	$atime=$_POST['atime'];
	$cnumber=$_POST['cnumber'];
	$inst_name=$_POST['inst_name'];
	$inst_name1=$_POST['inst_name1'];
	$payment_status=$_POST['payment_status'];
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
	$sim_remove_status=$_POST['sim_remove_status'];
	$ac_status=$_POST['ac_status'];
	$immobilizer_status=$_POST['immobilizer_status'];
	
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

$sql1 = mysql_fetch_array(mysql_query("select mobile_no from matrix.mobile_simcards where id in ( select sys_simcard from matrix.devices where id in (select sys_device_id from matrix.services where veh_reg='".$veh_reg."'))"));
$Devicemobile = $sql1["mobile_no"];

$sql=mysql_fetch_array(mysql_query("select sys_created from matrix.services where veh_reg='".$veh_reg."' limit 1"));
$date_of_install = date("Y-m-d",strtotime($sql["sys_created"]));


	//$query1=("UPDATE services SET name='$name', veh_reg='$veh_reg', Notwoking='$Notwoking', location='$location', atime='$atime',  cnumber='$cnumber',inst_name='$inst_name',inst_cur_location='$inst_cur_location',reason='$reason',time='$time',close_date=inst_date='".date("Y-m-d")."',newpending='0',newstatus='0' WHERE id='$id'");
	
	mysql_query("UPDATE services SET name='$name', veh_reg='$veh_reg', Notwoking='$Notwoking', location='$location', atime='$atime',  cnumber='$cnumber',payment_status='$payment_status',amount='$amount',paymode='$paymode',reason='$reason',sim_remove_status='$sim_remove_status',time='$time',close_date='".date("Y-m-d")."',inst_date='".date("Y-m-d")."',newpending='0',newstatus='0',problem='$problem',problem_in_service='$problemservice',billing='$billing',payment='$payment',ant_billing='$ant_billing', 
	ant_billing_amt='$ant_billing_amt',ant_billing_reason='$ant_billing_reason',ac_status='$ac_status',immobilizer_status='$immobilizer_status', service_status=5,device_status='".$device_status."'  WHERE id='$id'");
	
	mysql_query("update installer set status=0 where inst_name='$inst_name1'");
	
	
	 include($_SERVER['DOCUMENT_ROOT']."/service/sqlconnection.php");
	
	
	 if(stristr($reason, 'Device removed') != "")
	{ 
	
	 /*if(stristr($reason, '(device ok)') == "")
		{*/
		if($rows['device_imei']!="")
		{
		
				$rows['device_imei']=trim($rows['device_imei']);
				
				$rows['device_imei']=str_replace("_","",$rows['device_imei']);
				
				if($device_status == "Permanent"){
				
					//echo "update device set device_status=66, is_ffc=1 where device_imei='".trim($rows['device_imei'])."'";
					mssql_query("update device set device_status=66, is_permanent=1 where device_imei='".trim($rows['device_imei'])."'");
				}
				else{
					//echo "update device set device_status=66 where device_imei='".trim($rows['device_imei'])."'";
					mssql_query("update device set device_status=66 where device_imei='".trim($rows['device_imei'])."'");
				
				}
								
				$device_id =mssql_fetch_array(mssql_query("Select device_id from device where device_imei='".$rows['device_imei']."'"));
				
				$total = mssql_query("Select * from device_repair where device_imei='".$rows['device_imei']."'"); 
				$total_count = mssql_num_rows($total);
				
				if($total_count>0){
				//echo "total";
				 mssql_query("update device_repair set current_record=0 where device_imei='".$rows['device_imei']."'");
				
				mssql_query("insert into device_repair(device_id,client_name,device_imei,veh_no,device_removed_date,device_removed_branch,device_removed_problem,current_record,device_status,Remove_installer_name) values('".$device_id['device_id']."','".$name."','".$rows['device_imei']."','".$veh_reg."','".date('Y-m-d')."','".$_SESSION['BranchId']."','".$reason."','1','66','".$inst_name1."')");
				
				//$status=1;
				
				 mysql_query("update matrix.services set device_removed_service=1 where veh_reg='$veh_reg'");
				}
				else{
				//echo "insert";	
				mssql_query("insert into device_repair(device_id,client_name,device_imei,veh_no,device_removed_date,device_removed_branch,device_removed_problem,current_record,device_status,Remove_installer_name) values('".$device_id['device_id']."','".$name."','".$rows['device_imei']."','".$veh_reg."','".date('Y-m-d')."','".$_SESSION['BranchId']."','".$reason."','1','66','".$inst_name1."')");
				
				//$status=1;
				
				mysql_query("update matrix.services set device_removed_service=1 where veh_reg='$veh_reg'");
				
				}
		  }
		//}
		/*elseif(stristr($reason,  '(device ok)') == true)
		{
			$rows['device_imei']=str_replace("_","",$rows['device_imei']);
			mssql_query("update device set device_status=66 where device_imei='".$rows['device_imei']."'");
		mssql_query("insert into device_repair(client_name,device_imei,veh_no,device_removed_date,device_removed_branch,device_removed_problem) values('".$name."','".$rows['device_imei']."','".$veh_reg."','".date('Y-m-d')."','".$_SESSION['BranchId']."','".$reason."')");
	
		$status=1;
		 
		}*/
	
	  
	}
  else
	{
		//if($rows['device_imei']!="")
		//{
		//mssql_query("update device set device_status=66 where device_imei='".$rows['device_imei']."'");		
		//echo "NO case <br/> update device set device_status=65 where device_imei='".trim($rows['device_imei'])."'";
		mssql_query("update device set device_status=65 where device_imei='".trim($rows['device_imei'])."'");
		mysql_query("update matrix.services set device_removed_service=0 where veh_reg='$veh_reg'");
		
		//}
	}

	if($_POST["sim_change"]=="SimChange")
	{
	
		$rdd_date = date("Y-m-d",strtotime($time));
		
		$new_sim_query = mssql_fetch_array(mssql_query("select * from sim where phone_no='".$new_sim_no."'"));
		$new_update_query = mssql_query("update sim set Sim_Status=91, flag=1 where sim_id='".$new_sim_query["sim_id"]."'");
		
	    $query="INSERT INTO `sim_change` (`date`,acc_manager, `client`, `user_id`, `reg_no`, `old_sim`, `new_sim`, reason, sim_change_date, inst_name) VALUES ('".$time."','".$acc_manager."','".$client."','".$user_id."','".$veh_reg."','".$Devicemobile."','".$new_sim_no."','".$reason."','".$rdd_date."','".$inst_name1."')";
	
		mysql_query($query) or die(mysql_error());
	
	}
		
	if($_POST["device_change"]=="DeviceChange")
	{
			$sim_remove_date = date("Y-m-d h:i:s A",strtotime($time));
			
			if($sim_remove_status == "With Sim")
			{
				$old_sim_sql_query = mssql_fetch_array(mssql_query("select * from sim where phone_no='".$Devicemobile."'"));
				$update_query = mssql_query("update sim set Sim_Status=66, flag=2, active_status=1, branch_id='".$_SESSION['BranchId']."', SimRemoveDate='".$sim_remove_date."' where sim_id='".$old_sim_sql_query["sim_id"]."'");
			}
			if($sim_remove_status == "Without Sim")
			{
				$old_sim_sql_query = mssql_fetch_array(mssql_query("select * from sim where phone_no='".$Devicemobile."'"));
				$update_query = mssql_query("update sim set Sim_Status=92, status=0, flag=3, active_status=1,  branch_id='".$_SESSION['BranchId']."', SimRemoveDate='".$sim_remove_date."' where sim_id='".$old_sim_sql_query["sim_id"]."'");
			}
			
		  $query="INSERT INTO `device_change` (`date`,acc_manager, `client`, `user_id`, `device_imei`, `mobile_no`, `reg_no`, date_of_install, inst_name) VALUES ('".$time."','".$acc_manager."','".$client."','".$user_id."','".$device_imei."','".$Devicemobile."','".$veh_reg."','".$date_of_install."','".$inst_name1."')";
		
		mysql_query($query) or die(mysql_error());
		
	}
	
	if($_POST["vehicle_no_change"]=="VehiclenoChange")
	{
			
		 $query="INSERT INTO `vehicle_no_change` (`date`, acc_manager,`client`, `user_id`, `old_reg_no`, inst_name) VALUES ('".$time."','".$acc_manager."','".$client."','".$user_id."','".$veh_reg."','".$inst_name1."')";
		
		mysql_query($query) or die(mysql_error());
		
	}	
	
	header("location:service.php");
	
	}

if(isset($_POST['update']))
	{
	$inst_name=$_POST['inst_name'];
	$inst_name1=$_POST['inst_name1'];
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
		 
		mysql_query("update installer set status=1 where inst_name='$inst_name'");
		mysql_query("update installer set status=0 where inst_name='$inst_name1'");
	}
	else
	{
		
		  $inst_idname=$_POST['inst_name_all'];

		$ArrInst=explode("#",$_POST['inst_name_all']);
		$inst_name =$ArrInst[1];
		$inst_id =$ArrInst[0];
		 mysql_query("update installer set status=0 where inst_name='$inst_name1'");
		$job_type=2;
	}

	$pending=mysql_query("UPDATE services SET inst_id='$inst_id',inst_name='$inst_name',inst_cur_location='$inst_cur_location' ,billing='$billing',payment='$payment', service_status=2, job_type='$job_type'  WHERE id='$id'");
	mysql_query("update installer set status=1 where inst_name='$inst_name'");
	mysql_query("update installer set status=0 where inst_name='$inst_name1'");
	
	header("location:service.php");
	}
if(isset($_POST['backservice']))
	{
	$pending=$_POST['newpending'];
	$last_reason = $_POST['last_reason'];
	$back_reason=$_POST['reason_to_back'];
	$inst_name1=$_POST['inst_name1'];
	$inst_id1=$_POST['installer_id'];
	
	$update_backquery = "UPDATE services SET newpending='1',newstatus='0',back_reason='".$last_reason."<br/>".$back_reason." - ".$date."', service_status=3  WHERE id='$id'";
	
	mysql_query($update_backquery);

	/*$pending=mysql_query("UPDATE services SET newpending='1',newstatus='0',back_reason='$back_reason', service_status=3  WHERE id='$id'");*/
	mysql_query("update installer set status=0 where inst_id='$inst_id1'");
	
	header("location:service.php");
	}
?>
 <script type="text/javascript">
function req_info(form3)
{
		
		if(document.getElementById('sim_change').checked == true)
		 {
		   if(document.form3.sim_no.value ==""){
			   alert("please Select Sim No");
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
		
		var device_chk = document.form3.device_status[0].checked;
		var device_chk1 = document.form3.device_status[1].checked;
		if(device_chk  == false && device_chk1  == false)
		{
		   alert("please Select Device Status Temporary/Permanent");
		   return false;
		 }
		
	  if(document.getElementById('device_change').checked == true)
		 {
			var sim_remove_status = document.form3.sim_remove_status[0].checked;
			var sim_remove_status1 = document.form3.sim_remove_status[1].checked;
			if(sim_remove_status  == false && sim_remove_status1  == false)
			{
			   alert("please Select Device Remove Status With Sim/Without Sim");
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
		 
		 //alert(document.getElementById('ant_billing').value);
		/*if(reason_list =="Antenna Change")
		{
		   if(document.getElementById('ant_billing').checked == false){
			   alert("please Select Antenna Billing");
			   document.getElementById('ant_billing').focus();
			   return false;
		   }
		 }*/
		if(document.getElementById('ant_billing').checked == true)
		 {
		   if(document.form3.ant_billing_amt.value ==""){
			   alert("please enter Billing Amount");
			   document.form3.ant_billing_amt.focus();
			   return false;
		   }
		  }
		  
		  
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
		alert(Job);
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
	document.getElementById('No1').style.display = "none";
	document.getElementById('Yes1').style.display = "none";

	}
	else if(radioValue=="No")
	{

	document.getElementById('No').style.display = "block";
	document.getElementById('Yes').style.display = "none";
	document.getElementById('No1').style.display = "none";
	document.getElementById('Yes1').style.display = "none";
	}
	else
	{
	document.getElementById('No').style.display = "none";
	document.getElementById('Yes').style.display = "none";
	document.getElementById('Yes1').style.display = "none";
	document.getElementById('No1').style.display = "none";
	} 
	
}	

var Path="http://trackingexperts.com/service/";
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
<form method="post" action="" name="form3">
<table width="50%" border="0" cellpadding="0" cellspacing="0">
<tr><td><td><input name="id" type="hidden" id="id" value="<?php echo $rows['id'];?>">
<input name="user_id" type="hidden" id="user_id" value="<?php echo $rows['user_id'];?>">
<input name="company_name" type="hidden" id="company_name" value="<?php echo $rows['company_name'];?>">
<input name="device_imei" type="hidden" id="device_imei" value="<?php echo $rows['device_imei'];?>">
</td></td></tr>
 <tr>
<td  align="right">Client Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><input type="text" name="name" id="name" readonly value="<?php echo $rows['name'];?>" /></td>
</tr>
<tr style="">
<td align="right">Vehicle No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><input type="text" name="veh_reg" id="veh_reg" readonly value="<?php echo $rows['veh_reg'];?>" /></td>
</tr>
<td height="32" align="right">Notwoking:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="Notwoking" id="Notwoking" value="<?php echo $rows['Notwoking'];?>" /></td>
</tr>
<tr>
<td height="32" align="right">Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="location" readonly id="location" value="<?php echo $rows['location'];?>" /></td>
</tr>
<tr>
<td height="32" align="right">Available Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="atime" readonly id="atime" value="<?php echo $rows['atime'];?>" /></td>
</tr>
<tr>
<tr>
<td height="32" align="right">Person Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="pname" readonly id="pname" value="<?php echo $rows['pname'];?>" /></td>
</tr>
<tr>
<td height="32" align="right">Contact No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="cnumber" readonly id="cnumber" value="<?php echo $rows['cnumber'];?>" /></td>
</tr>
<tr>
<td height="32" align="right">Device Model:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="device_model" readonly id="device_model" value="<?php echo $rows['device_model'];?>" /></td>
</tr>

<tr>
<td height="32" align="right">Installer Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="inst_name1" readonly id="inst_name1" value="<?php echo $rows['inst_name'];?>" /><input type="hidden" name="installer_id" value="<?php echo $rows['inst_id'];?>" /></td>
</tr>
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
 
$query="SELECT inst_id,inst_name,concat(inst_id,'#',inst_name) as idname FROM installer where status=0 and branch_id=".$_SESSION['BranchId'];
$result=mysql_query($query);

?> 
<? //$name1=$row['inst_id']."#".$row['inst_name']; ?> 
 <select name="inst_name" id="inst_name" style="display:none"  ><option value="0">Select Name</option>
<? while($row=mysql_fetch_array($result)) { ?>
<option value=<?=$row['idname'];?>><?=$row['inst_name']?></option>
<? } ?>
</select>
<?php
 
$query="SELECT inst_id,inst_name,concat(inst_id,'#',inst_name) as idname FROM installer where branch_id=".$_SESSION['BranchId'];
$result=mysql_query($query);

?> 
 <select name="inst_name_all" id="inst_name_all"  style="display:none" ><option value="0">Select Name</option>
<? while($row=mysql_fetch_array($result)) { ?>
<option value=<?=$row['idname'];?>><?=$row['inst_name']?></option>
<? } ?>
</select>
</td>
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
<td height="32" align="right">Amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="amount" id="amount" value="<?php echo $rows['amount'];?>" /></td>
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



<tr><td>&nbsp;</td>
<td>
<input type="checkbox" name="billing" id="billing" value="yes" />Billing <input type="checkbox" name="payment" id="payment" value="yes" />Payment
</td></tr>
<tr>
<td height="32" align="right">Installer Current Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="inst_cur_location" id="inst_cur_location" value="<?php echo $rows['inst_cur_location'];?>" /></td>
</tr>
<tr>
<td height="32" align="right"> Problem in service:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>
<input type="radio" value="Installer Side" <?php if($result['problem_in_service']=="Installer Side"){echo "checked=\"checked\""; }?> id="problemservice" name="problem_in_service" onclick="document.getElementById('problem').style.display = 'block';">Installer Side

 <input type="radio" value="Client Side" <?php if($result['problem_in_service']=="Client Side"){echo "checked=\"checked\""; }?> id="problemservice" name="problem_in_service" onclick="document.getElementById('problem').style.display = 'none';">Client Side
</td>
</tr>
<tr>
<td height="32" align="right">Problem&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><textarea name="problem" id="problem" style="display:none" value="<?php echo $rows['problem'];?>" /></textarea></td>

</tr>
<tr>
          <td height="32" align="right">Antenna Billing:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>

 <Input type = 'Radio' Name ='ant_billing' id="ant_billing" value= 'Yes' <?php if($result['ant_billing']=="Yes"){echo "checked=\"checked\""; }?>
onchange="Status(this.value)"
>Yes

<Input type = 'Radio' Name ='ant_billing' id="ant_billing" value= 'No' <?php if($result['ant_billing']=="No"){echo "checked=\"checked\""; }?>
onchange="Status(this.value)"
>No</td>
</tr>

 <?php if($result['ant_billing']=="Yes") { ?>
		  <table  id="Yes1" align="center"  style="width: 250px; border:1" cellspacing="5" cellpadding="5">
	<tr>
	<td> <label  id="lbDlBilling">Billing Amount</label></td>
			  <td> <input type="text" name="ant_billing_amt" id="billing_amt" value="<?=$result['ant_billing_amt']?>" /></td>
			  </tr>
			 		  
		</table>
		<? } ?>
				  <table  id="Yes"  align="center"  style="width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
		<tr>
	<td> <label  id="lbDlBilling">Billing Amount</label></td>
			  <td> <input type="text" name="ant_billing_amt" id="billing_amt" value="<?=$result['ant_billing_amt']?>" /></td>
			  </tr>
			 </table>
        
		<?php if($result['ant_billing']=="No") { ?>
		 		  <table  id="No1" align="center"  style="width: 200px; border:1" cellspacing="5" cellpadding="5">

	<tr>
	<td> <label  id="lbDlBilling">Reason</label></td>
			  <td> <input type="text" name="ant_billing_reason" id="billing_reason" value="<?=$result['ant_billing_reason']?>" /></td>
			  </tr></table>
		<? } ?>
		  <table  id="No" align="center"   style="width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">

		<tr>
	<td> <label  id="lbDlBilling">Reason</label></td>
			  <td> <input type="text" name="ant_billing_reason" id="billing_reason" value="<?=$result['ant_billing_reason']?>" /></td>
			  </tr></table>
			  </tr></table>
<?if($_GET['show']=="close")
{?>
<table width="50%" border="0" cellpadding="0" cellspacing="0">
<!--<tr>
	<td align="right">
    Device Removed For Replace
    </td>
    <td>
    	<input type="checkbox" name="removed_device_for_replace" id="removed_device_for_replace" value="Device removed" />
    </td>
</tr>-->
<tr>
    <td align="right">Device Status:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>
    <input type="radio" name="device_status" id="device_status" value="Temporary" />Temporary 
    <input type="radio" name="device_status" id="device_status" value="Permanent" />Permanent
    </td>
</tr>	

<tr>
<td height="32" align="right">Reason:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>

<?php
 $query="SELECT * FROM reason where reason_for='service' order by reason asc";
$result_reason=mysql_query($query);

?> 
<SCRIPT LANGUAGE="JavaScript">
 <!--
	function ShowTextBox(reason)
	{
		if(reason=="Device re-installed")
		{
		document.getElementById("Extreason").style.display = 'block';
		}
		else
		{
		document.getElementById("Extreason").style.display = 'none';
		}

	}
 //-->
 </SCRIPT> 
<select name="reason" id="reason"   style="width:200px" onChange="ShowTextBox(this.value)">
<option value="0">Select Name</option>
<? while($row=mysql_fetch_array($result_reason)) { 
$highlight="";
if($_POST["reason"]==$row['reason'])
	{
	$highlight="selected";
	}
	?>
<option value="<?=$row['reason']?>" <?=$highlight ?> ><?=$row['reason']?></option>
<? } ?>
</select>

  <input type="text" name="Extreason" id="Extreason" value="" style="display:none" />   </td>
</tr>
<tr>
    <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>
    <input type="checkbox" name="device_change" id="device_change" value="DeviceChange" />Device Change
    <input type="checkbox" name="vehicle_no_change" id="vehicle_no_change" value="VehiclenoChange" />Vehicle No. Change
    </td>
</tr>
<tr>
    <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>
    <input type="checkbox" name="sim_change" id="sim_change" value="SimChange" onchange="DropdownShow(<?echo $rows['inst_id'];?>)" />Sim Change 
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
<td><select name="ac_status">
	<option value="">Select AC Status</option>
	<option value="Working">Working</option>
	<option value="Not Working">Not Working</option>
	<option value="Not Applicable">Not Applicable</option>
        </select></td>
</tr>
<tr>
<tr>
<td height="32" align="right">Immobilizer Status*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><select name="immobilizer_status">
	<option value="">Select Immobilizer Status</option>
	<option value="Working">Working</option>
	<option value="Not Working">Not Working</option>
	<option value="Not Applicable">Not Applicable</option>
        </select></td>
</tr>
<tr>		
<tr>
<td width="48%" height="32" align="right">Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td width="52%"><input type="text" style="width:180px" name="time" id="time" value="<?php echo date('Y-m-d H:i');?>" /></td>
</tr><?}//$rows['time']?>
<?if($_GET['show']=="backtoservice")
{?>
<tr>
<td height="32" align="right">Reason To Back Services:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><textarea name="reason_to_back" id="reason_to_back" rows="5" cols="15"><?php echo $rows['back_reason'];?></textarea>
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
	?><input type="submit" name="submit" value="Close" align="right" onClick="return req_info(form3)"/>&nbsp;&nbsp;&nbsp;&nbsp;<?
} 
if($_GET['show']=="backtoservice")
{
	?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="backservice" value="back to service" align="right" onClick="return req_info1(form3)" /><?
} 
?>

</td>
<td><input type="button" name="Cancel" value="Cancel" onClick="window.location = 'service.php' " />
</tr>

 
</table>
</form>
 
 
<?
include("include/footer.inc.php");

?>

  