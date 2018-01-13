<?php
session_start();
ob_start();
include("connection.php");

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    
   
    $_SESSION['user_name']="";
    $_SESSION['userId']="";
    $_SESSION['ParentId']="";
   
    $user_name=addslashes($_POST['user_name']);
    $password=addslashes($_POST['password']);
   
    $user_name = mysql_real_escape_string($user_name);
    $password = mysql_real_escape_string($password);
   
    $sql="SELECT * FROM servicelogin_user WHERE user_name='".$user_name."' and password='".$password."' ";
       
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
   
    $count=mysql_num_rows($result);

 
    if($count==1)
    {
    //print_r($_POST);
     $_SESSION['username']=$row["user_name"];
     $_SESSION['userId']=$row['id'];
     $_SESSION['ParentId']=$row['parent_id'];
     $_SESSION['BranchId']=$row['branch_id'];
     $_SESSION['isadmin']=$row['isadmin'];
    }
    else
    {
        $login_time = date("Y-m-d H:i:s");
       
        $sql_temp="SELECT * FROM servicelogin_user_temp WHERE user_name='".$user_name."' and password='".$password."' and from_date<='".$login_time."' AND to_date>='".$login_time."' ";
        $temp_result=mysql_query($sql_temp);
        $temp_row=mysql_fetch_array($temp_result);
       
        $temp_count=mysql_num_rows($temp_result);
       
        if($temp_count==1)
        {
         $_SESSION['username']=$temp_row["user_name"];
         $_SESSION['userId']=$temp_row['servicelogin_id'];
         $_SESSION['ParentId']=$temp_row['parent_id'];
         $_SESSION['BranchId']=$temp_row['branch_id'];
         $_SESSION['isadmin']="";
         
        }
               
    }     
     
   
    switch($_SESSION['ParentId'])
    {
             
        case "1":
        $_SESSION['id']=1;         
        header("Location: servicecalling/services.php");       
        break;
       
        case "2":
        $_SESSION['id']=2;
        header("Location: serviceprovider/newservice.php");
        break;       
       
        case "3":
        $_SESSION['id']=3;
        if($_SESSION['username'] == "dservicehead")
        {
			//die();
            header("Location: support/all_branch_ser_inst.php");
			//header("Location: index.php");
        }
        else
        {
            header("Location: support/service_installation_details.php");
        }
        break;
       
        case "4":
        $_SESSION['id']=4;
        header("Location: serviceprovider/rajvi_masterjourney_hour.php");
        break;
		
		case "5":
        $_SESSION['id']=5;
        header("Location: dispatchview/newservice.php");
        break; 
		case "6":
        $_SESSION['id']=6;
        header("Location: marketveh/list_trip_entry.php");
        break;

		case "7":
        $_SESSION['id']=1;
        header("Location: school/notworkingvehicle.php");
        break;

		case "8":
        $_SESSION['id']=8;
        header("Location: support/accountcreation.php");
        break;
		
		case "9":
        $_SESSION['id']=9;
        header("Location: servicecalling/list_add_clients_view.php");
        break;
		
		case "10":
        $_SESSION['id']=10;
        header("Location: kpmprocess/list_new_device_addition.php");
        break;

         
    }
   
    if(empty($count) && empty($temp_count))
    {
    //header('location: index.php');
    echo "<div style='color:red;'>"."Your Login Name or Password is invalid"."</div>";
    }
   


}
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Gtrac Client Support</title>
<link rel="stylesheet" href="css/login.css" type="text/css" />

<meta name="robots" content="noindex" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
        <script type="text/javascript" language="javascript" src="<?php echo __SITE_URL;?>/media/js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo __SITE_URL;?>/js/function.js"></script>
       
            <script src="http://trackingexperts.com/collection/thickbox/jquery-latest.js" type="text/javascript"></script>
<script src="http://trackingexperts.com/collection/thickbox/thickbox.js" type="text/javascript"></script>
<link rel="stylesheet" href="http://trackingexperts.com/collection/thickbox/thickbox.css" type="text/css" media="projection, screen" />
<style>
body,td{
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:12px;
}
</style>
</head>
<body id="loginBody">
<div id="loginBox">
    <h1 id="logo"><img src="img/logo.png"><a href="index.php">gtrac Staff Control Panel</a></h1>
    <h1>Authentication Required</h1>

    <br />

<form method="post" action="" onSubmit="return oklogin()">
<table border=0 align="center">
        <tr><td width=100 align="right"><b>Username</b>:</td>
        <td width="203"><input type="text"  name="user_name" id="username" placeholder="username" /></td></tr>
        <tr><td align="right"><b>Password</b>:</td><td><input type="password"  name="password" id="password" placeholder="password" /></td></tr>
         
        <tr><td align="right"><input type="submit" value="Go"/></td><td align="center">
   
  <!--<a href="resetpwd-iframe.php?height=200&width=500" class="thickbox"  >Reset Password</a>-->
		</td>
</td>
        </tr>

    </table>
</form>
 
 </div>
<script type="text/javascript">
function oklogin(){
    var u = document.getElementById('username');
    var p = document.getElementById('password');
   
    if((u.value != '' && u.value.length >0) && (p.value != '' && p.value.length >0)){
        return true;
    }else{
        return false;
    }
}   

</script>   

</body>
</html>

