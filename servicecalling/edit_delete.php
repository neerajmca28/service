<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");  */
?> 



<div class="top-bar">
<h1>Edit Service</h1>
</div>
<div class="table"> 

<?
if(isset($_REQUEST['action'])=="edit")
{
$id=$_GET['id'];
$setqry="";
if(isset($_GET['pending']))
	{
		
		//1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
	$newstst=$_GET['pending'];
	$setqry=", status='1' , service_status=1";
	
	
	//$newstst=$_GET['pending'];
	//$setqry=", status='1'";
	}
$query=mysql_query("SELECT * FROM services WHERE id='$id'");

$rows=mysql_fetch_array($query);
}
//print_r($rows);
if(isset($_POST['submit']))
{
$id=$_POST['id'];
$name=$_POST['name'];
$veh_reg=$_POST['veh_reg1'];
if($veh_reg=="")
	{
		$veh_reg=$_POST['veh_reg'];
	}
$Notwoking=$_POST['Notwoking'];
$location=$_POST['location'];

$atime=$_POST['atime'];
$pname=$_POST['pname'];
$cnumber=$_POST['cnumber'];
$pending=$_POST['pending'];
$device_model=$_POST['device_model'];

$new_name=$_POST['new_name'];
$new_veh_reg=$_POST['new_veh_reg'];
$new_Notwoking=$_POST['new_Notwoking'];
$new_location=$_POST['new_location'];

$new_atime=$_POST['new_atime'];
$new_pname=$_POST['new_pname'];
$new_cnumber=$_POST['new_cnumber'];
$new_pending=$_POST['new_pending'];

$required=$_POST['required'];
	if($required=="") { $required="normal"; }
	
	$datapullingtime=$_POST['datapullingtime'];
	$new_device_model=$_POST['new_device_model'];
	
	$IP_Box=$_POST['IP_Box'];
//echo $pending;die(); 
$sql=mysql_query("SELECT sys_username as name FROM matrix.users  WHERE user_id='$name'");
$row=mysql_fetch_array($sql);

if($new_name!="" || $new_veh_reg!="" )
	{
	
	$sql1=mysql_query("SELECT sys_username as name FROM matrix.users WHERE user_id='$new_name'");
	$row1=mysql_fetch_array($sql1);
	
	$query1="UPDATE services SET name='".$row1['name']."', veh_reg='$new_veh_reg', Notwoking='$new_Notwoking', location='$new_location', atime='$new_atime', pname='$new_pname',  cnumber='$new_cnumber', pending='0',move_vehicles=1 ,IP_Box='".$IP_Box."' ".$setqry.", device_model='$new_device_model' WHERE id='$id'";
	
	
	 $back_sql="INSERT INTO services_backup(service_id,name,veh_reg,Notwoking,location,device_model,atime,pname,cnumber,req_date)VALUES(".$id.",'".$row['name']."','".$veh_reg."','".$Notwoking."','".$location."','".$atime."','".$pname."','".$cnumber."','".date("Y-m-d")."','".$row['device_model']."')"; 
	
	
	 mysql_query($back_sql);

	}
	else {
  $query1=("UPDATE services SET name='".$row['name']."', veh_reg='$veh_reg', Notwoking='$Notwoking', location='$location', atime='$atime', pname='$pname',  cnumber='$cnumber', pending='0' ,required='".$required."',datapullingtime='".$datapullingtime."',IP_Box='".$IP_Box."' ".$setqry.",device_model='$device_model' WHERE id='$id'");
		}
mysql_query($query1);

$pg=$_GET['pg'];
//echo $pg;		
header("location:services.php");
}

?>
<script type="text/javascript">

function req_info()
{

  var name=document.getElementById(name)
  if(document.form1.name.value==0)
  {
  alert("please choose one name") ;
  document.form1.name.focus();
  return false;
  }
  
  if(document.form1.veh_reg.value==0)
  {
  alert("please choose vehicle number") ;
  document.form1.veh_reg.focus();
  return false;
  }

  if(document.form1.Notwoking.value =="")
  {
   alert("please enter not working time");
   document.form1.Notwoking.focus();
   return false;
   }
   if(document.form1.location.value =="")
  {
   alert("please enter location");
   document.form1.location.focus();
   return false;
   }
   
    if(document.form1.atime.value =="")
  {
   alert("please enter available time");
   document.form1.atime.focus();
   return false;
   }
   
   if(document.form1.pname.value =="")
  {
   alert("please enter person name");
   document.form1.pname.focus();
   return false;
   }
   
   if(document.form1.cnumber.value =="")
  {
   alert("please enter contact number");
   document.form1.cnumber.focus();
   return false;
   }
 }  
 
    
	function showdiv()
		{
		document.getElementById('replace').style.display = '';
		}
</script>
<form method="post" action="" name="form1" onSubmit="return req_info();">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td width="26%"><td width="27%"><input name="id" type="hidden" id="id" value="<?php echo $rows['id'];?>"></td><td width="47%"></td></tr>
 <tr>
<td height="29" align="right">Client Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td colspan="2">
<?php
 
$query="SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE sys_active=1 AND Branch_id=".$_SESSION['BranchId'];
$result=mysql_query($query);

?> 

<?
$name=$rows['name'];

?> 

<select name="name" onChange="getYear(this.value,'statediv','veh_reg')">
<option value="0">Select Name</option>
<? while($row1=mysql_fetch_array($result)) { ?>
<option value=<?=$row1['user_id']?><? if($name==$row1['name']) { ?> selected="selected" <? }?>><?=$row1['name']?></option>
<? } ?>
</select></td>
</tr>
<tr>
<td align="right">Vehicle No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><div id="statediv"><select name="veh_reg" style="width:150px" id="veh_reg">
<option>Select Name First</option>

 <? if($rows['veh_reg']!="") { ?><option value="<?=$rows['veh_reg']?>" selected="selected"><?=$rows['veh_reg']?></option> <? } ?>
</select> 
</div></td>
<td> Or &nbsp;&nbsp;&nbsp;<input type="text" name="veh_reg1" id="veh_reg1" value="<?=$rows['veh_reg']?>" /></td>
</tr>
<td height="32" align="right">Notwoking1:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2"><input type="text" name="Notwoking" id="Notwoking" value="<?php echo $rows['Notwoking'];?>" readonly/></td>
</tr>
<tr>
<td height="32" align="right">Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2"><input type="text" name="location" id="location" value="<?php echo $rows['location'];?>" /></td>
</tr>

<tr>
<td height="32" align="right">Available Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2"><input type="text" name="atime" id="atime" value="<?php echo $rows['atime'];?>" readonly/></td>
</tr>
<tr>
<td height="32" align="right">Person Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2"><input type="text" name="pname" id="pname" value="<?php echo $rows['pname'];?>" /></td>
</tr>
<tr>
<td height="32" align="right">Contact No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2"><input type="text" name="cnumber" id="cnumber" value="<?php echo $rows['cnumber'];?>" /></td>
<tr>
<td height="32" align="right">Required:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2"><input type="checkbox" name="required" id="required" value="urgent" <?php if($rows['required']=='urgent') {?> checked="checked" <? }?> /> Urgent </td></tr>



<tr>
<td height="32" align="right">IP Box.:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><input type="checkbox" name="IP_Box" id="IP_Box" value="required" <?php if($result['IP_Box']=='required') {?> checked="checked" <? }?> /> Required </td>
</tr>
<tr><td height="32" align="right">Data Pulling Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2"><input type="text" name="datapullingtime" id="datapullingtime" value="<?php echo $rows['datapullingtime'];?>" /></td></tr>
</tr><tr>
<td height="32" align="right">Device Model:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2"><?php
include("config.php");
$query="SELECT device_model FROM device_model";
$result=mysql_query($query);
?> 
<?
$device_model=$rows['device_model'];

?> 

<select name="device_model">
<option value="0">Select Device Model</option>
<? while($row=mysql_fetch_array($result)) { ?>
<option value=<?=$row['device_model']?><? if($name==$row['device_model']) { ?> selected="selected" <? }?>><?=$row['device_model']?></option>
<? } ?>
</select></td>
</tr>
<tr><td></td><td><input type="button"  value="Click Here To Move Vehicles"  onclick="showdiv();"/></td></tr>
<tr><td colspan="2"><div id="replace" style="display:none;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td align="right" style="color:#CC0000">Replace Vehicles To-</td><td></td></tr>

<tr><td><td><input name="id" type="hidden" id="id" value="<?php echo $rows['id'];?>"></td></td></tr>
 <tr>
<td height="29" align="right">Client Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>
<?php
include("config.php");
$query="SELECT user_id,name FROM users";
$result=mysql_query($query);

?> 

<?
$name=$rows['name'];

?> 

<select name="new_name" onChange="getYear(this.value,'statediv1','new_veh_reg')">
<option value="">Select Name</option>
<? while($row=mysql_fetch_array($result)) { ?>
<option value=<?=$row['user_id']?>><?=$row['name']?></option>
<? } ?>
</select></td>
</tr>
<tr>
<td align="right">Vehicle No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><div id="statediv1"><select name="new_veh_reg" style="width:150px" id="veh_reg">
<option value="">Select Name First</option>
 
</select></div></td>
</tr>
<td height="32" align="right">Notwoking:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="new_Notwoking" id="new_Notwoking" value="<?php echo $rows['Notwoking'];?>" readonly /></td>
</tr>
<tr>
<td height="32" align="right">Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="new_location" id="new_location" value="<?php echo $rows['location'];?>" /></td>
</tr>
<tr>
<td height="32" align="right">Available Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="new_atime" id="new_atime" value="<?php echo $rows['atime'];?>" readonly /></td>
</tr>
<tr>
<td height="32" align="right">Person Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="new_pname" id="new_pname" value="<?php echo $rows['pname'];?>" /></td>
</tr>
<tr>
<td height="32" align="right">Contact No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="new_cnumber" id="new_cnumber" value="<?php echo $rows['cnumber'];?>" /></td>
</tr>


</table>

</div></td></tr>
<tr>
<td height="32" align="right"><input type="submit" name="submit" value="submit" align="right" />&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<input type="button" name="Cancel" value="Cancel" onClick="window.location = 'services.php' " /></td>
 
</tr>

</table>


</form>

 
 
<?
include("../include/footer.inc.php");

?>
