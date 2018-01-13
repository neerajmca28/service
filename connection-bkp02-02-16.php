<?php 
ob_start();
date_default_timezone_set ("Asia/Calcutta");

 

$hostname = "localhost";
$username1 = "internal";
$password = "internal!@#";
$databasename = "matrix";


$hostname2 = "203.115.101.124";
$username2 = "internal_soft";
$password2 = "123456";
$databasename2 = "internalsoftware";

 
//$dblink = mysql_connect($hostname,$username,$password) ;

$dblink = mysql_connect($hostname,$username1,$password) ;
$dblink2 = mysql_connect($hostname2,$username2,$password2) ;

@mysql_select_db($databasename,$dblink);
@mysql_select_db($databasename2,$dblink2);

$Date = date("Y-m-d");
$dateFrom=date('Y-m-d', strtotime($Date. ' - 5 days'));
 
 function getcountRow($query)
 {
	global $dblink2;
	$hostname2 = "203.115.101.124";
	$username2 = "internal_soft";
	$password2 = "123456";
	$databasename2 = "internalsoftware";
	
	$dblink2 = mysql_connect($hostname2,$username2,$password2) ;

	$Numberofservice = mysql_query($query);
	$count=mysql_num_rows($Numberofservice);
	return $count;
 }
 function select_query($query,$condition=0){
	 
	global $dblink2;
	$hostname2 = "203.115.101.124";
	$username2 = "internal_soft";
	$password2 = "123456";
	$databasename2 = "internalsoftware";
	
	$dblink2 = mysql_connect($hostname2,$username2,$password2) ;

 			if($condition==1){
				//echo "<br>".$query."<br>";
			}
		$qry=@mysql_query($query);  
		 
		  $num=@mysql_num_rows($qry);
		$num_field=@mysql_num_fields($qry);
		for($i=0;$i<$num_field;$i++)
		{
		$fname[]=@mysql_field_name($qry,$i);
		}
		for($i=0;$i<$num;$i++){
		$result=mysql_fetch_array($qry);
		foreach($fname as $key => $value ) {
			$arr[$i][$value]=$result[$value];
			}
		}


		return $arr;
}

function getcountRow_live($query)
{
	$hostname = "localhost";
	$username1 = "internal";
	$password = "internal!@#";
	$databasename = "matrix";

	$dblink = mysql_connect($hostname,$username1,$password) ;
	
	$Numberofservice = mysql_query($query,$dblink);
	$count=mysql_num_rows($Numberofservice);
	return $count;
}


function select_query_live($query,$condition=0){
	
	$hostname = "localhost";
	$username1 = "internal";
	$password = "internal!@#";
	$databasename = "matrix";

	$dblink = mysql_connect($hostname,$username1,$password) ;
	
   if($condition==1){
    //echo "<br>".$query."<br>";
   }

  $qry=@mysql_query($query,$dblink);// or die( $query . " ". mysql_error());
  $num=@mysql_num_rows($qry);
  $num_field=@mysql_num_fields($qry);
  for($i=0;$i<$num_field;$i++)
  {
  $fname[]=@mysql_field_name($qry,$i);
  }
  for($i=0;$i<$num;$i++){
  $result=mysql_fetch_array($qry);
  foreach($fname as $key => $value ) {
   $arr[$i][$value]=$result[$value];
   }
  }
  //mysql_close();
  return $arr;
}

function Sendmail($Subject,$message,$to,$cc)
{
if($to!='')
	{
 include("C:/xampp/htdocs/send_alert/class.phpmailer.php");
include("C:/xampp/htdocs/send_alert/class.smtp.php");
 
 
 
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
$mail->Body       = $message;                      //HTML Body
$mail->AltBody    = ""; //Text Body
$mail->WordWrap   = 50; // set word wrap



  $mail->AddAddress($to,'');
 if($cc!='')
	{
 $mail->AddAddress($cc,'');
	}

    
 
$mail->AddReplyTo("info@g-trac.in","G-trac"); 
$mail->IsHTML(true);  
 

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
}
	}
}
?>