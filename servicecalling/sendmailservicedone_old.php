<?php
include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); 

 $successmsg='';
if(isset($_POST['submit'])) {

 $subject = $_POST['subject'];
 $mailto = $_POST['mailto'];
 $mailtocc = $_POST['mailtocc'];
 $message = $_POST['message'];

 $headers ="MIME-Version: 1.0\r\n";
  $headers .="Content-Type: text/html; charset=ISO-8859-1\r\n";
 
   $subject="$subject";
  $sendId ="$mailto,$mailtocc";
   
   $textTosend='Dear Sir';
   $textTosend.="<br><br>$message";
  $textTosend.='<br><br>
 <table id="MainTable" border="1" cellspacing="0" cellpadding="0" width="887">
   
  <tr>
   
    <td width="117" valign="middle"><p align="center"><strong>Vehicle No.</strong></p></td>
    <td width="111" valign="middle"><p align="center"><strong>Date</strong></p></td>
    <td width="111" valign="middle"><p align="center"><strong>Service Date</strong></p></td>
    <td width="111" valign="middle"><p align="center"><strong>Availability</strong></p></td>
    <td width="111" valign="middle"><p align="center"><strong>AC status</strong></p></td>
    <td width="113" valign="middle"><p align="center"><strong>Re-fitting</strong></p></td>
    <td width="111" valign="middle"><p align="center"><strong>Reason of service</strong></p></td>

	
  </tr>';
 
for ($i = 0; $i < count($_POST['vehicle']); ++$i) {

$textTosend.='<tr>
  <td >
  '.$_POST['vehicle'][$i].'  </td>
  <td >
  '.$_POST['date'][$i].'  </td>
  <td >
  '.$_POST['servicedate'][$i].'  </td>
  <td >
  '.$_POST['avail'][$i].'  </td>
  <td >
  '.$_POST['acstatus'][$i].'  </td>
  <td >
  '.$_POST['refitting'][$i].'  </td>
  <td >
  '.$_POST['reason'][$i].'  </td>
  
  </tr>
  ';
  }
  
  $textTosend.='</table>'; 
	$textTosend.='<br><br>Thanks & Regards<br>
  				G-Trac <br>
  				Customer Care<br>
				011-46254625	'; 
$headers  .= "From: info@g-trac.in";

Sendmail($subject,$textTosend,$mailto,$mailtocc);
$successmsg= "Mail Successfully Sent";
//mail($sendId,$subject,$textTosend,$headers);
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
$query="SELECT Userid AS user_id,UserName AS `name` FROM addclient";
$result=mysql_query($query);
?>
          <select name="name" onchange="getVeh(this.value)" id="name">
            <option>Select Name</option>
            <? while($row=mysql_fetch_array($result)) { ?>
            <option value=<?=$row['user_id']?>>
            <?=$row['name']?>
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
   
    <td   valign="middle"><p align="center"><strong>Vehicle No.</strong> </p></td>
    <td   valign="middle"><p align="center"><strong>Date</strong></p></td>
   <td   nowrap="nowrap" valign="bottom"><p align="center"><strong>Service Date</strong></p></td>
    <td  nowrap="nowrap" valign="bottom"><p align="center"><strong>Availability</strong></p></td>
    <td   nowrap="nowrap" valign="bottom"><p align="center"><strong>AC status</strong></p></td>
    <td   nowrap="nowrap" valign="bottom"><p align="center"><strong>Re-fitting</strong></p></td>
    <td  nowrap="nowrap" valign="bottom"><p align="center"><strong>Reason of service</strong></p></td>
	<th  ></th>
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
    <td width="163" valign="middle"><p align="center">
    <input type="text" class="datepicker_recurring_start" name="date[]"/>
     </p></td>
    <p></p>
   <td width="155" valign="middle"><p align="center">
    <input type="text" class="datepicker_recurring_service" name="servicedate[]" />
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
    <td width="136" valign="middle"><p align="center">
      <textarea cols="10" rows="1" name="reason[]"></textarea>
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
          <input name="subject" type="text" value="Service Status"size="50" />
        </div>
      </label>
     </th>
  </tr>
   <tr>
    <th width="119" height="26" scope="col"><div align="left">Messsage:-</div></th>
    <th width="300" scope="col"> <label>

        <div align="center">
              <textarea cols="37" rows="4" name="message">It is the mail to inform you that we serviced 1 vehicle out 10 vehicles because of rest of the vehicles were on duty/ not available and the service status of serviced vehicle  is mentioned below</textarea>
          </div>
            </label>
    </th></tr>
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
include("include/footer.inc.php");

?>

    
