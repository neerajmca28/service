<?

 include($_SERVER['DOCUMENT_ROOT']."/service/connection.php");

if(isset($_POST["submit"]))
{ 
$old_password=$_POST["old_password"];
$new_password=$_POST["new_password"];
$user_name=$_POST["user_name"];


$query="update servicelogin_user set password='".$new_password."' where password='".$old_password."' and user_name='".$user_name."'";
 
 $query;
 mysql_query($query);
echo "Password Reset successfully";
	}
 
?>

 <html>
<head>
 
</head>
<body>


<? if(!isset($_REQUEST["view"]) && $_REQUEST["view"]!=true)
{?>
 <form name="form1" method="post" action="">
<table width="100%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="3" align="center"><strong>Reset Password</strong></td>

  </tr>
    <tr>
    <td colspan="3" align="left" style="color:red">
		
	</td>

  </tr>
  <tr>
    <td width="41%">User Name</td>
    <td width="2%">:</td>
    <td width="57%"><input type="text" name="user_name" id="user_name" value=""></td>
  </tr>
  <tr>
    <td>Old Password</td>
    <td>:</td>
    <td><input type="password" name="old_password" id="old_password" value=""></td>
  </tr>
  <tr>
    <td>New Password </td>
    <td>:</td>
    <td><input type="password" name="new_password" id="new_password" value=""></td>
  </tr>
  <tr>
    <td>Confirm Password </td>
    <td>:</td>
    <td><input type="password" name="confirm_password" id="confirm_password" value=""></td>
  </tr>
  <tr>
   <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="submit" name="submit" value="Submit"></td>
  </tr>
</table>
</form>
<?  
}
 
?>
</body>
</html>
