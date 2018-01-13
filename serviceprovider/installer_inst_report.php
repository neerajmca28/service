<?php
include("include/header.inc.php");
include ("config.php");


if(isset($_POST['submit']))
{
$client=$_POST['client'];
$new_client=$_POST['new_client'];

if($client=="") { $client=$new_client; }


$inst_name=$_POST['inst_name'];






$location=$_POST['location'];
$install_date=$_POST['install_date'];

$number_veh=$_POST['number_veh'];
$veh_reg=$_POST['veh_reg'];

$sql="INSERT INTO installation(client,inst_name,location,time,no_of_vehicals,veh_reg,flag)VALUES('".$client."','".$inst_name."','".$location."','".$install_date."','".$number_veh."','".$veh_reg."',1)"; 



$execute=mysql_query($sql);
header("location:new_installation.php");
}

?>


<link rel="stylesheet" type="text/css" media="all" href="js/calendar/calendar-win2k-1.css" title="win2k-1" />
  <link rel="stylesheet" type="text/css" media="all" href="js/calendar/calendar-win2k-1.css" title="win2k-1" />
<script src="../js/validation_new.js"></script>
    <!-- calendar stylesheet -->
  <link rel="stylesheet" type="text/css" media="all" href="js/calendar/calendar-win2k-1.css" title="win2k-1" />
  
   <!-- main calendar program -->
  <script type="text/javascript" src="js/calendar/calendar.js"></script>

  <!-- language for the calendar -->
  <script type="text/javascript" src="js/calendar/calendar-en.js"></script>

  <!-- the following script defines the Calendar.setup helper function, which makes
       adding a calendar a matter of 1 or 2 lines of code. -->
  <script type="text/javascript" src="js/calendar/calendar-setup.js"></script>


<script type="text/javascript">

function req_info()
{

  var name=document.getElementById(name)
 
   
  
   if(document.form1.location.value =="")
  {
   alert("please enter location");
   document.form1.location.focus();
   return false;
   }
   
    if(document.form1.install_veh.value =="")
  {
   alert("please enter available time");
   document.form1.install_veh.focus();
   return false;
   }
     if(document.form1.number_veh.value =="")
  {
   alert("please enter Veh number");
   document.form1.number_veh.focus();
   return false;
   }
   if(document.form1.veh_reg.value=="")
  {
  alert("please choose vehicle number") ;
  document.form1.veh_reg.focus();
  return false;
  }
   
 }  
</script>






<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<form method="post" action="" name="form1" onSubmit="return req_info();">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="right">

 <tr>
<td width="49%" height="29" align="right" >Client Name:*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td width="51%" colspan="2">
<?php
include("config.php");
$query="SELECT user_id,name FROM users";
$result=mysql_query($query);

?> 

 
<select name="client" id="client" style="width:150px">
<option value="">Select Name</option>
<? while($row=mysql_fetch_array($result)) { ?>
<option value=<?=$row['name']?>><?=$row['name']?></option>
<? } ?>
</select></td>
</tr>
<tr>
<td height="32" align="right">New Client:*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2"><input type="text" name="new_client" id="new_client" style="width:147px"/></td>
</tr>
<tr>
<td height="32" align="right">Installer Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<?php
include("config.php");
$query="SELECT inst_name FROM installer";
$result=mysql_query($query);
$name1=$rows['inst_name']; ?> 
<td><select name="inst_name" id="inst_name" ><option value="">Select Name</option>
<? while($row=mysql_fetch_array($result)) { ?>
<option value=<?=$row['inst_name']?>><?=$row['inst_name']?></option>
<? } ?>
</select>
</tr>
<tr>
<td height="32" align="right">Location:*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2"><input type="text" name="location" id="location" style="width:147px"/></td>
</tr>
<tr>
<td height="32" align="right">Date:*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td colspan="2">
	 <input type="text" name="install_date" id="install_date" style="width:147px"/> 		
	  	</td>
</tr>


<tr>
<td height="32" align="right">No of Vehicles:*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2"><input type="text" name="number_veh" id="number_veh" style="width:147px" /></td>
</tr>
<tr>
<td height="32" align="right">Vehicle RegNo.:*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2"><textarea type="text" name="veh_reg" id="veh_reg" cols="10" rows="6" wrap="hard" style="margin: 2px; width: 200px; height: 60px;">
</textarea></td>
</tr>
<tr>
<td height="32" align="right"><input type="submit" name="submit" value="submit" align="right" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="Cancel" value="Cancel" onClick="window.location = 'services.php' " /></td>
</tr>

</table>
</form>
</body>
</html>
<?
include("include/footer.inc.php");

?>

<script type="text/javascript">
   
     
    
    Calendar.setup({
        inputField     :    "install_date",
        ifFormat       :    "%Y-%m-%d %H:%M",
        showsTime      :    true,
        timeFormat     :    "24",
        firstDay       :    1
    });
   
</script>