<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */

include("C:/xampp/htdocs/send_alert/class.phpmailer.php");
include("C:/xampp/htdocs/send_alert/class.smtp.php");

$action=$_GET['action'];
$id=$_GET['id'];
$page=$_POST['page'];
if($action=='edit' or $action=='editp')
    {
        $result=mysql_fetch_array(mysql_query("select * from servicelogin_user_temp where id=$id"));   
    }
?>

<div class="top-bar">
<h1>Login Assign Details</h1>
</div>
<div class="table">
<?php
 

if(isset($_POST["submit"]))
{

    $user_name = $_POST["user_name"];
    $assign_to = $_POST["assign_to"];
    $password = $_POST["password"];
    $email_id = $_POST["email_id"];
    $from_date = $_POST["from_date"];
    $to_date = $_POST["to_date"];
    $TxtReason = $_POST["reason"];
    $login_user_id = $_POST["login_user_id"];
    $parent_id = $_POST["parent_id"];
    $branch_id = $_POST["branch_id"];
    $reply_id = $_SESSION['email'];

 if($action=='edit')
    {
        $query="update servicelogin_user_temp set user_name='".$user_name."',assign_to='".$assign_to."',password='".$password."',email='".$email_id."',from_date='".$from_date."',to_date='".$to_date."',reason='".$TxtReason."' where id=$id";
     
        $insert_query = mysql_query($query);
        /*echo "<script>document.location.href ='list_login_assign.php'</script>";*/
    }
    else
    {
        $query="INSERT INTO servicelogin_user_temp (servicelogin_id, user_name, password, assign_to, email, parent_id, branch_id, from_date, to_date, reason)
    VALUES('".$login_user_id."','".$user_name."','".$password."','".$assign_to."','".$email_id."','".$parent_id."','".$branch_id."','".$from_date."','".$to_date."','".$TxtReason."')";
     
        $insert_query = mysql_query($query);
        /*echo "<script>document.location.href ='list_login_assign.php'</script>";*/

    }
   
    if($insert_query)
    {
   
        $Subject = $user_name." - Login Forwarded Details";
   
        $mail=new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port
        $mail->Username   = "info@g-trac.in";  // GMAIL username
        $mail->Password   = "info@123456";            // GMAIL password
        $mail->From       = "info@g-trac.in";
        $mail->FromName   = "G-trac";
        $mail->Subject    = $Subject;
        //$mail->Body       = $message;                      //HTML Body
        $mail->AltBody    = ""; //Text Body
        $mail->WordWrap   = 50; // set word wrap
       
        $textTosend.='Dear '.$assign_to.',<br>';
        $textTosend.='<br>I am forwarding my login to you.Login details are given below.<br><br>';
        $textTosend.='User:- '.$user_name;
        $textTosend.='<br>Password:- '.$password;
        $textTosend.='<br>From:- '.$from_date;
        $textTosend.='<br>To:- '.$to_date;
        $textTosend.='<br>Reason:- '.$TxtReason;
        $textTosend.='<br>URL:- http://trackingexperts.com/service/index.php';
   
        $mail->AddAddress($email_id,$assign_to);
        $mail->AddAddress("ritesh@g-trac.in","Ritesh Kapoor");   
         
        $mail->AddReplyTo($reply_id,"G-trac");
        $mail->IsHTML(true); 
       
        $mail->Body = $textTosend;
       
        if(!$mail->Send()) {
          echo "Mailer Error: " . $mail->ErrorInfo;
        }   
    }
   
  echo "<script>document.location.href ='list_login_assign.php'</script>";
 
 }


?>

 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>

$(function() {
$( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd 00:00:00" });
$( "#datepicker2" ).datepicker({ dateFormat: "yy-mm-dd 23:59:59" });
$( "#datepickercheque" ).datepicker({ dateFormat: "yy-mm-dd" });

});

function trim(inputString){
     inputString=inputString.replace(/^\s+/g,"");
     inputString=inputString.replace(/\s+$/g,"");
     return inputString;
}

function is_email(email) {
    if(!email.match(/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/))
        return false;
    else
        return true;
}


function validateForm()
{
 
    if(document.myForm.assign_to.value=="")
    {
        alert("Please Enter Assign To") ;
        document.myForm.assign_to.focus();
        return false;
    } 
    if(document.myForm.password.value=="")
    {
        alert("Please Enter Password") ;
        document.myForm.password.focus();
        return false;
    } 
    if(document.myForm.email_id.value=="")
    {
        alert("Please Enter Email Id") ;
        document.myForm.email_id.focus();
        return false;
    }
   
    else if(trim(document.myForm.email_id.value) != "")
    {
        if(is_email(document.myForm.email_id.value) == false)
        {
            alert("Please Enter valid Email");
            document.myForm.email_id.focus();
            return false;
        }
    }
   
     
    if(document.myForm.datepicker.value=="")
    {
        alert("Please Enter From Date") ;
        document.myForm.datepicker.focus();
        return false;
    } 
    if(document.myForm.datepicker2.value=="")
    {
        alert("Please Enter To Date") ;
        document.myForm.datepicker2.focus();
        return false;
    } 
    if(document.myForm.reason.value=="")
    {
        alert("please Enter Reason") ;
        document.myForm.reason.focus();
        return false;
    }
   
} 
           
</script>

 <form name="myForm" action="" onSubmit="return validateForm()" method="post">
 

    <table width="589" cellpadding="5" cellspacing="5" style=" padding-left: 100px;width: 550px;">
       
        <tr>
            <td colspan="2">
                <input type="hidden" name="login_user_id" value="<?=$_SESSION['userId'];?>" />
                <input type="hidden" name="parent_id" value="<?=$_SESSION['ParentId'];?>" />
                <input type="hidden" name="branch_id" value="<?=$_SESSION['BranchId'];?>" />
             </td>
        </tr>
        <tr>
            <td><label  id="lbDlUserName">User Name </label></td>
            <td><input type="text" name="user_name" id="user_name" readonly value="<?=$_SESSION['username'];?>"/></td>
        </tr>
        <tr>
            <td><label  id="lbDlAssignTo">Assign To </label></td>
            <td><input type="text" name="assign_to" id="assign_to" value="<?=$result['assign_to'];?>"/></td>
        </tr>
        <tr>
            <td><label  id="lbDlPassword">Password </label></td>
            <td><input type="text" name="password" id="password"  value="<?=$result['password'];?>"/></td>
        </tr>
        <tr>
            <td><label  id="lbDlEmailId">Email ID </label></td>
            <td><input type="text" name="email_id" id="email_id" value="<?=$result['email'];?>"/></td>
        </tr>
        <tr>
            <td> <label  id="lbDlFromDate">From date </label></td>
            <td> <input type="text" name="from_date" id="datepicker" value="<?=$result['from_date']?>" /></td>
        </tr>
       
        <tr>
            <td> <label  id="lbDlToDate">To date </label></td>
            <td> <input type="text" name="to_date" id="datepicker2" value="<?=$result['to_date']?>" /></td>
        </tr>

        <tr>
            <td> <label  id="lblReason">Reason</label></td>
            <td> <textarea rows="5" cols="25"  type="text" name="reason" id="reason" > <?=$result['reason']?></textarea></td>
        </tr>
       
        <tr>
            <td colspan="2"> <input type="submit" name="submit" value="submit"  />
            <input type="button" name="Cancel" value="Cancel" onClick="window.location = 'list_login_assign.php' " /></td>
        </tr>

    </table>
 </form>
 
 
<?php
include("../include/footer.php"); ?>