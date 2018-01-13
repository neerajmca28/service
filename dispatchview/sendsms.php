<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu_dispatch.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu_dispatch.php");*/  
?>



<div class="top-bar">
<h1>Send SMS</h1>
</div>
<div class="table"> 

<?

 if(isset($_POST['submit']))
{ 

   $MSG="Vehicle : ".$_POST["MsgText"];
    $MobileNum=$_POST["Number"];
 
$ch = curl_init();
$user="gary@itglobalconsulting.com:itgc@123";
$receipientno=$MobileNum;
$senderID="GTRACK";
$msgtxt=$MSG;
curl_setopt($ch,CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
$buffer = curl_exec($ch);
if(empty ($buffer))
{ echo " buffer is empty "; }
else
{ echo $buffer; echo "Successfully Sent"; }
curl_close($ch);
 //header("location:installation.php");

		 }
 





?>
<script>
function validateForm()
			{
 
			  var txtNumber=document.forms["myForm"]["Number"].value;
			if (txtNumber==null || txtNumber=="")
			  {
			  alert("Enter Mobile Number");
			  return false;
			  }
			  
			var MsgText=document.forms["myForm"]["MsgText"].value;
			if (MsgText==null || MsgText=="")
			  {
			  alert("Enter message");
			  return false;
			  }
			  }
			  </script>
              
 
<form method="post" action="" name="myForm" onSubmit="return validateForm();">
<table width="100%" border="0" cellpadding="5" cellspacing="10" >

 <tr>
<td width="15%" height="29" align="right" >Mobile Numbers</td>
<td width="37%">
<textarea  rows="6"   style="width: 330px; height: 80px;" cols="40" id="Number" name="Number"></textarea>
</td>

<td width="12%" height="32"  align="right">Installer Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td width="36%">
<?php
 
$query = select_query("SELECT inst_id,inst_name FROM internalsoftware.installer where is_delete=1 and branch_id=".$_SESSION['BranchId']);

?> 
 <select name="inst_name" id="inst_name"  onchange="getInstallermobile(this.value,'Number');">
 <option value="0">Select Name</option>
<? for($i=0;$i<count($query);$i++) { ?>
<option value=<?=$query[$i]['inst_id'];?>><?=$query[$i]['inst_name']?></option>
<? } ?>
</select>
</td>
</tr>
<tr style="">
<td align="right">Message*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>
<textarea  rows="6"   style="width: 330px; height: 80px;" cols="40" id="MsgText" name="MsgText"></textarea>
</td>
</tr>

 <tr>
<td colspan="2" align ="center"><input type="submit" name="submit" value="submit"  /><input type="button" name="Cancel" value="Cancel" onClick="window.location = 'installation.php' " /></td>
</tr>
</table>
</form>
 
 
<?
include("../include/footer.inc.php");

?>

 
