<?php
ob_start();
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/ 

include("D:/xampp/htdocs/send_alert/class.phpmailer.php");
include("D:/xampp/htdocs/send_alert/class.smtp.php");

 $successmsg='';
if(isset($_POST['submit'])) {

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
	
	$mail=new PHPMailer();
	$mail->IsSMTP();
	//$mail->SMTPDebug   = 1;
	$mail->SMTPAuth   =true;                  // enable SMTP authentication
	$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	$mail->Port       = 465;                   // set the SMTP port 
	$mail->Username   = "services.gtrac@gmail.com";  // GMAIL username
	$mail->Password   = "gtrac@12345";            // GMAIL password
	$mail->From       = "services.gtrac@gmail.com";
	$mail->FromName   = "G-trac";
	$mail->Subject    = $Subject;
	//$mail->Body       = $message;                      //HTML Body
	$mail->AltBody    = ""; //Text Body
	$mail->WordWrap   = 50; // set word wrap
	
	$textTosend='Dear Sir,';
   $textTosend.='<br><br>It is the mail to inform you that we serviced following vehicles and rest of the vehicles were on duty/ not available and the service status of serviced vehicle  is mentioned below';
  $textTosend.='<br><br>
	 <table id="MainTable" border="1" cellspacing="0" cellpadding="0" width="540">
	   
	  <tr>
	   
		<td width="117" valign="middle"><p align="center"><strong>Vehicle No.</strong></p></td>
		<td width="111" valign="middle"><p align="center"><strong>Date of Service</strong></p></td>
		<td width="111" valign="middle"><p align="center"><strong>Availability</strong></p></td>
		<td width="111" valign="middle"><p align="center"><strong>AC status</strong></p></td>
		<td width="113" valign="middle"><p align="center"><strong>Re-fitting</strong></p></td>
	   </tr>';
	 
	 
	for ($i = 0; $i < count($_POST['vehicle']); ++$i) {
	
	$textTosend.='<tr>
	  <td >
	  '.$_POST['vehicle'][$i].'  </td>
	  <td >
	  '.$_POST['servicedate'][$i].'  </td>
	  <td >
	  '.$_POST['avail'][$i].'  </td>
	  <td >
	  '.$_POST['acstatus'][$i].'  </td>
	  <td >
	  '.$_POST['refitting'][$i].'  </td>
	 
	  
	  </tr>
	  ';
	  }
	  
	  $textTosend.='</table><br>';  
	 
	 $textTosend.='NOTE: Company released an offer of new device with renewal of warranty period costs of Rs.4000 +tax to replace the devices which are out of warranty and incurs the spare cost.<br><br>';
	 $textTosend.='Service stations : Kolkata, Bangalore, Panipat, Pune, Hyderabad, Jaipur, Kanpur, Pantnagar,Ahmedabad, Mumbai, Silvassa, Udaipur, Indore,Surat.<br><br>'; 
	 $textTosend.='Thanks & Regards<br>
							G-Trac <br>'.$from;
	
	
	//$mail->AddAddress("harish@g-trac.in","harish");
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

 /*$subject = $_POST['subject'];
 $mailto = $_POST['mailto'];
 $mailtocc = $_POST['mailtocc'];
 $message = $_POST['message'];

 $headers ="MIME-Version: 1.0\r\n";
  $headers .="Content-Type: text/html; charset=ISO-8859-1\r\n";
 
   $subject="$subject";
  $sendId ="$mailto,$mailtocc";
   
   $textTosend='Dear Sir,';
   $textTosend.='<br><br>It is the mail to inform you that we serviced following vehicles and rest of the vehicles were on duty/ not available and the service status of serviced vehicle  is mentioned below';
  $textTosend.='<br><br>
 <table id="MainTable" border="1" cellspacing="0" cellpadding="0" width="540">
   
  <tr>
   
    <td width="117" valign="middle"><p align="center"><strong>Vehicle No.</strong></p></td>
    <td width="111" valign="middle"><p align="center"><strong>Date of Service</strong></p></td>
    <td width="111" valign="middle"><p align="center"><strong>Availability</strong></p></td>
    <td width="111" valign="middle"><p align="center"><strong>AC status</strong></p></td>
    <td width="113" valign="middle"><p align="center"><strong>Re-fitting</strong></p></td>
   </tr>';
 
 
for ($i = 0; $i < count($_POST['vehicle']); ++$i) {

$textTosend.='<tr>
  <td >
  '.$_POST['vehicle'][$i].'  </td>
  <td >
  '.$_POST['servicedate'][$i].'  </td>
  <td >
  '.$_POST['avail'][$i].'  </td>
  <td >
  '.$_POST['acstatus'][$i].'  </td>
  <td >
  '.$_POST['refitting'][$i].'  </td>
 
  
  </tr>
  ';
  }
  
  $textTosend.='</table>'; 
	$textTosend.='<br><br>Thanks & Regards<br>
  				G-Trac <br>
  				Customer Care<br>
				011-46254625	'; 
$headers  .= "From: info@g-trac.in";

Sendmail($subject,$textTosend,$mailto,$mailtocc);*/

$successmsg= "Mail Successfully Sent";
  }
  
?> 

	<div class="top-bar">
	 
	<h1>Send mail: Service Done <?echo $successmsg;?></h1>
	</div>

 <div class="top-bar">
                    
                 <div style="float:right";><a href="sendmail.php">Repair Done</a></div>
<br/>
<div style="float:right";><a href="sendmailservicereceive.php">Service Received</a></div>			  
               
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
         
        $('#btnAdd').click(function () {
              var count = parseInt($('#HowManyRows').val()), first_row = $('#FirstRow');
                while(count-- > +1)                    first_row.clone().appendTo('#MainTable');
    });  
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
	
	function getVeh(vehId) {		
		var strURL="vehreg.php?name="+vehId;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('vehdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
				
	}
</script>
 
<form name="form1" action="" method="POST">

<table width="943" border="0">
    <tr>
      <th width="151" scope="col"><div align="left">Client Username:- </div></th>
      <th width="195" scope="col"> <p align="left">
          <?php 
include ("config.php");

$user_query = select_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient order by UserName");
//$result=mysql_query($query,$dblink2);

?>
          <select name="name" onchange="getVeh(this.value)" id="name">
            <option>Select Name</option>
            <? for($k=0;$k<count($user_query);$k++) { ?>
            <option value=<?=$user_query[$k]['user_id']?>>
            <?=$user_query[$k]['name']?>
            </option>
            <? } ?>
          </select>

    </p></th>
	 <th width="141" scope="col"><div align="left">No of vehicle:- </div></th>
      <th width="192" scope="col"> <p align="left">
	  <input type="text" name="HowManyRows" id="HowManyRows" style="width:110px;" />
	  </p></th>
	  <th width="242"><div align="justify">
    <input type="button" id="btnAdd" value="Add Vehicle"/>
  </div></th>
    </tr>
  </table>
    <div class="form-fields">
<table id="MainTable" border="1" cellspacing="0" cellpadding="0"  >
   
  <tr>
   
   <td width="163" valign="middle"><p align="center"><strong>Vehicle No.</strong> </p></td>
    
   <td width="155" nowrap="nowrap" valign="bottom"><p align="center"><strong>Date of Service</strong></p></td>
    <td width="134" nowrap="nowrap" valign="bottom"><p align="center"><strong>Availability</strong></p></td>
    <td width="94" nowrap="nowrap" valign="bottom"><p align="center"><strong>AC status</strong></p></td>
    <td width="138" nowrap="nowrap" valign="bottom"><p align="center"><strong>Re-fitting</strong></p></td>
	<th width="69"></th>
  </tr>
  
   
 <tr id="FirstRow">    
    <td width="163" height="81" valign="middle"><p align="center"></p>
        <div id="vehdiv">
          <div align="center">
            <select name="vehicle[]" id="vehicle">
              <option>Select Name First</option>
            </select>
          </div>
        </div>
    <p></p></td>
    <p></p>
   <td width="155" valign="middle"><p align="center">
    <input type="text" name="servicedate[]" class="datepicker_recurring_start"/>
     </p></td>
    <td width="134" valign="middle"><p align="center">
      <textarea cols="10" rows="1" name="avail[]"></textarea>
    </p></td>
    <td width="94" valign="middle"><p align="center">
      <select name="acstatus[]" id="acstatus">
	  
              <option>N/A</option>
			   <option>OK</option>
		    </select>
    </p></td>
    <td width="138" valign="middle"><p align="center">
      <select name="refitting[]" id="refitting">
	   <option>Select Re-Fitting</option>
              <option>Yes</option>
			   <option>No</option>
		    </select>
    </p></td>
    	<td><p align="center"><input type="button" class="remove" value="remove" /></p></td>
  </tr>
      </table>
         
  </div>
      
  
      
  
<p>&nbsp;</p>
<table width="429" border="0.5">
  <tr>
    <th width="65" height="26" scope="col"><div align="left">Subject:-</div></th>
    <th width="354" scope="col">
      <label>
        <div align="center">
          <input name="subject" type="text" value="Service Done Status"size="50" />
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
  <input type="submit" name="submit" id="submit" value="Send E-mail" />
 </div></td>
  </tr>
</table>
  
</form>

 
 </div>
 
<?
include("../include/footer.inc.php");

?>

    
