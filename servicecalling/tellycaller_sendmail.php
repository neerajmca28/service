<?php
ob_start();
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/private/master.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); 
include($_SERVER['DOCUMENT_ROOT']."/service/private/master.php");*/

include("D:/xampp/htdocs/send_alert/class.phpmailer.php");
include("D:/xampp/htdocs/send_alert/class.smtp.php");


$masterObj = new master();


if($_POST["submit"]=="Ok")
{
	
	 $username= $_POST["userid"];
	
	 //$userData=select_query_live("select id from users where sys_username='".$username."'");
	 $userData = $masterObj->getUserDetails($username);
	 
	 $userId=$userData[0]['id'];
	 
	 $data = $masterObj->getNotWorkingVeh($userId);													
								
	//echo "<pre>";print_r($data);die;					  
}



$successmsg="";
if(isset($_POST['send_mail'])) {

	$from_name = $_SESSION['username'];
	
	if($from_name=="swati"){
  $from = "swati@g-trac.in <br>Customer Care<br>011-46254625,9971323232";
  $replyto = "swati@g-trac.in";
 }
 else if($from_name=="jyoti"){
  $from = "jyoti@g-trac.in <br>Customer Care<br>011-46254626,8130995093";
  $replyto = "jyoti@g-trac.in";
 }
 else if($from_name=="aarti"){
  $from = "arti@g-trac.in <br>Customer Care<br>011-46254627,8527597708";
  $replyto = "arti@g-trac.in";
 }
 else if($from_name=="Mamta"){
  $from = "mamta@g-trac.in <br>Customer Care<br>011-46254628,8527703555";
  $replyto = "mamta@g-trac.in";
 } 
 else if($from_name=="himanshi"){
  $from = "swatijr@g-trac.in <br>Customer Care<br>011-46254629,8287153535";
  $replyto = "swatijr@g-trac.in";
 }
 else if($from_name=="Himani"){
  $from = "himani@gtrac.in <br>Customer Care<br>011-46254633,8130995090";
  $replyto = "himani@gtrac.in";
 }
 else if($from_name=="lovely"){
  $from = "lovely@g-trac.in <br>Customer Care<br>011-46254633,8527707555";
  $replyto = "lovely@g-trac.in";
 }
 else {
  $from = "swati@g-trac.in <br>Customer Care<br>011-46254625,9971323232";
  $replyto = "swati@g-trac.in";
 }



	$Subject = $_POST['subject'];
	$mailto =  explode(",",$_POST['mailto']);
	$mailtocc = explode(",",$_POST['mailtocc']);
	
	/*$mail=new PHPMailer();
     $mail->IsSMTP();
     $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
     $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
     $mail->Port       = 465;                   // set the SMTP port 
     $mail->Username   = "service.gtrac@gmail.com";  // GMAIL username
     $mail->Password   = "gtrac@12345";            // GMAIL password
	$mail->From       = "service.gtrac@gmail.com";
     $mail->FromName   = "G-trac";
	$mail->Subject    = $Subject;
	//$mail->Body       = $message;                      //HTML Body
	$mail->AltBody    = ""; //Text Body
	$mail->WordWrap   = 50; // set word wrap*/

 $mail=new PHPMailer();
 $mail->IsSMTP();
 //$mail->SMTPDebug   = 1;
 $mail->SMTPAuth   =true;                  // enable SMTP authentication
 $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
 $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
 $mail->Port       = 465;                   // set the SMTP port 
 $mail->Username   = "serv.gtrac@gmail.com";  // GMAIL username
 $mail->Password   = "gtrac@12345";            // GMAIL password
 $mail->From       = "serv.gtrac@gmail.com";
 $mail->FromName   = "G-trac";
 $mail->Subject    = $Subject;
 //$mail->Body       = $message;                      //HTML Body
 $mail->AltBody    = ""; //Text Body
 $mail->WordWrap   = 50; // set word wrap
 
	
	$textTosend.='Dear Sir,<br>';
	$textTosend.='<br>Please check and confirm ,regarding the availability of the vehicles so, that we are able to provide the service ASAP.<br><br>';
	$textTosend.='Kindly Reply to this <br> E-Mail:- '.$from;
	$textTosend.='<br><br>
	<table id="MainTable" border="1" cellspacing="0" cellpadding="0" width="844">
	   <tr>
			<td colspan="6" align="center"><strong>Daily vehicle status</strong> </td>
	   </tr>
	   <tr>
			<td colspan="6" align="center"><strong>Name: '.$_REQUEST['user'].'</strong></td>
	   </tr>
	   <tr>
			<td colspan="6" align="center"><strong>Date: '.date("Y-m-d").'</strong></td>
	   </tr>
	  <tr>
		<td width="106" valign="middle"><p align="center"><strong>Vehicle No.</strong></p></td>
		<td width="137" valign="middle"><p align="center"><strong>Date of not working</strong></p></td>
		<td width="88" valign="middle"><p align="center"><strong>Date of service</strong></p></td>
		<td width="170" valign="middle"><p align="center"><strong>Availability</strong></p></td>
		<td width="171" valign="middle"><p align="center"><strong>Client Comment</strong></p></td>
		<td width="183" valign="middle"><p align="center"><strong>ITGT Comment</strong></p></td>	   
	  </tr>';
	 
	for ($i = 0; $i < $_REQUEST['total']; ++$i) {
	
	$textTosend.='<tr>
	  <td>'.$_REQUEST['vehcile'][$i].'</td>
	  <td>'.$_REQUEST['not_working_date'][$i].'</td>
	  <td>'.$_REQUEST['date'][$i].'  </td>
	  <td>'.$_REQUEST['availability'][$i].'</td>
	  <td>'.$_REQUEST['client_comment'][$i].'</td>
	  <td>'.$_REQUEST['itgt_comment'][$i].'</td>
	  </tr>';
	  }
	 $textTosend.='</table><br>'; 
	 
	 $textTosend.='Service stations : Kolkata, Bangalore, Panipat, Pune, Hyderabad, Jaipur, Kanpur, Pantnagar,Ahmedabad, Mumbai, Silvassa, Udaipur, Indore,Surat.<br><br>'; 
	$textTosend.='Thanks & Regards<br>
     G-Trac <br><br>
      
     ITG Telematics Pvt Ltd<br>
     A-12/3, Naraina Phase -1<br>
     industrial area near PVR cinema,<br>
     New Delhi - 110028<br>
     E-mail-info@g-trac.in  Website-  www.g-trac.in';
	 
	//$mail->AddAddress("harish@g-trac.in","harish");
	//$mail->AddAddress("harishsharma.sharma7@gmail.com","harish");
	//$mail->AddAddress("radhika@g-trac.in","radhika");
	for($j=0;$j<count($mailto);$j++)
	{
		$mail->AddAddress($mailto[$j]);
	}
	for($u=0;$u<count($mailtocc);$u++)
	{
		$mail->AddAddress($mailtocc[$u],"G-trac");
	}
	//$mail->AddAddress("priya@g-trac.in","Priya");
	
	 
	$mail->AddReplyTo($replyto,"G-trac"); 
	$mail->IsHTML(true); 
	
	$mail->Body = $textTosend;
	
	if(!$mail->Send()) {
	  echo "Mailer Error: " . $mail->ErrorInfo;
	}

	$successmsg= "Mail Successfully Sent";
  }
  
?> 

	<div class="top-bar">
	 
	<h1>Not Working Vehicles <?echo $successmsg;?></h1>
	</div>


<div style="float:left;padding-left:5px;padding-top:5px"> 
 

 <style>
.remove { display; }
</style>  
<link rel="stylesheet" href="/resources/themes/master.css" type="text/css" />
<link
 href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css"
 rel="stylesheet" type="text/css" />
<script
 src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js"
 type="text/javascript"></script>
<script
 src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"
 type="text/javascript"></script>
<script
 src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.js"
 type="text/javascript"></script>
 
<script src="/resources/scripts/mysamplecode.js" type="text/javascript"></script>
  <script type="text/javascript">
        $(document).ready(function () {
             $('body').on('focus',".datepicker_recurring_start", function(){
					$(this).datepicker();
				});
			$('.form-fields').on('click', '.remove', function(){
					$(this).closest('tr').remove();
				});
        });
    </script>
	<script language="javascript" type="text/javascript">
// Roshan's Ajax dropdown code with php
// This notice must stay intact for legal use
// Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
// If you have any problem contact me at http://roshanbh.com.np
function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
	
	
</script>
</head>
<body>
<form method="post" action="" name="form2">
<table width="500" border="0">
    <tr>
      <th width="151" scope="col"><div align="left">Client Username:- </div></th>
      <th width="211" scope="col"> <p align="left">
<?php 

/*$query="SELECT Userid AS id,UserName AS sys_username FROM addclient";
$result=mysql_query($query,$dblink2);*/

$user_query = select_query("SELECT Userid AS user_id,UserName AS `name` FROM internalsoftware.addclient order by UserName");

?>
          <select name="userid" id="userid">
            <option value="">Select Name</option>
            <? for($k=0;$k<count($user_query);$k++) { ?>
            <option value="<?php echo $user_query[$k]['name'];?>"<? if($_POST['userid']==$user_query[$k]['name']) {?> selected="selected" <? } ?> >
			<?php echo $user_query[$k]['name'];?></option>
            <? } ?>
          </select>

    </p></th>
	 
	  <th width="263"><div align="justify">
   <input type="submit" name="submit" value="Ok">
  </div></th>
    </tr>
  </table>
</form>
<p></p>

<form name="emailform" method="post" action="">
   <input type="hidden" name="user" value="<?=$username?>">
   <!--<input type="hidden" name="total" value="<?=$total?>">-->
   
    <div class="form-fields">
<table id="MainTable" border="1" cellspacing="0" cellpadding="0" >
   
  <tr>
   
    <td width="130" valign="middle"><p align="center"><strong>Vehicle No.</strong> </p></td>
    <td width="130"valign="middle"><p align="center"><strong>Date of not working</strong></p></td>
    <td width="130" valign="middle"><p align="center"><strong>Date of service</strong></p></td>
    <td width="130" valign="middle"><p align="center"><strong>Availability</strong></p></td>
    <td width="130" valign="middle"><p align="center"><strong>Client Comment</strong></p></td>
    <td width="130" valign="middle"><p align="center"><strong>ITGT Comment</strong></p></td>
    
	<th width="82"></th>
  </tr>
<?php 
 
//$query = mysql_query($qry,$dblink); 

$total = count($data);
?>
<input type="hidden" name="total" value="<?=$total?>">
<?php

//while($row=mysql_fetch_array($query))
for($m=0;$m<count($data);$m++)
{
	/*$qrycom="select comment,comment_by,date from `comment` where service_id=".$data[$m]['id']." order by date desc";
	$qrycom_data = mysql_query($qrycom,$dblink);			
	$Commentquery = mysql_fetch_array($qrycom_data);*/
	
	$Commentquery = $masterObj->getServiceComment($data[$m]['id']);
 ?> 
   
 <tr id="FirstRow">   
         
    <td width="130" height="30" valign="middle"><p align="center">
		<input type="text" name="vehcile[]" value="<?php echo $data[$m]['veh_reg'];?>" />          
     </p></td>
    <td width="130" valign="middle"><p align="center">
    <input type="text" name="not_working_date[]" value="<?php echo $data[$m]['lastcontact'];?>" />
     </p></td>
    <td width="130" valign="middle"><p align="center">
    <input type="text" name="date[]" value="" class="datepicker_recurring_start" />
     </p></td>
    <td width="130" valign="middle"><p align="center">
    <input type="text" name="availability[]" value="" />
     </p></td>
    <td width="130" valign="middle"><p align="center">
    <input type="text" name="client_comment[]" value="" />
     </p></td>
    <td width="130" valign="middle"><p align="center">
    <input type="text" name="itgt_comment[]" value="<?php echo $Commentquery[0]['comment'];?>" />
     </p></td>
    
	<td><p align="center"><input type="button" class="remove" value="remove" /></p></td>
  </tr>
  <?php } ?>
      </table>
         
  </div>
      
  
<p>&nbsp;</p>
<table width="429" border="0.5">
  <tr>
    <th width="65" height="26" scope="col"><div align="left">Subject:-</div></th>
    <th width="354" scope="col">
      <label>
        <div align="center">
          <input name="subject" type="text" value="Regarding GPS Tracker Service-<?php echo $username;?>"size="50" />
        </div>
      </label>
     </th>
  </tr>
  <tr>
    <td height="62"><div align="left"><strong>To:-</strong></div></td>
    <td>
      <label>
      <div align="center">
        <input name="mailto" type="text" size="50" />
      </div>
      </label>
    </td>
  </tr>
  <tr>
    <td><div align="left"><strong>Cc:-</strong></div></td>
    <td>
      <label>
      <div align="center">
        <input name="mailtocc" type="text" size="50" />
      </div>
      </label>
   </td>
  </tr>
  <tr>
  <td>
  </td><td>
    <div align="center">
  <input type="submit" name="send_mail" id="submit" value="Send E-mail" />
 </div></td>
  </tr>
</table>
  
</form>
 
 </div>
 
<?
include("../include/footer.inc.php");

?>

    
