<?php 
ini_set('display_errors', 1);
ob_start();
date_default_timezone_set ("Asia/Calcutta");

define('__SITE_URL', 'http://203.115.101.30/service');

define('__DOCUMENT_ROOT', 'D:/xampp/htdocs/service');

/*$hostname = "localhost";
$username1 = "internal";
$password = "internal!@#";
$databasename = "matrix";*/


$hostname2 = "203.115.101.124";
$username2 = "internal_soft";
$password2 = "123456";
$databasename2 = "internalsoftware";

 
//$dblink = mysql_connect($hostname,$username,$password) ;

//$dblink = mysql_connect($hostname,$username1,$password) ;
$dblink2 = @mysql_connect($hostname2,$username2,$password2) ;

//@mysql_select_db($databasename,$dblink);
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
	
	$dblink2 = @mysql_connect($hostname2,$username2,$password2) ;

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
	
	$dblink2 = @mysql_connect($hostname2,$username2,$password2) ;

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
 
 function insert_query($table_name, $form_data)
{
   
	global $dblink2;
	$hostname2 = "203.115.101.124";
	$username2 = "internal_soft";
	$password2 = "123456";
	$databasename2 = "internalsoftware";
	
	$dblink2 = @mysql_connect($hostname2,$username2,$password2) ;

    // retrieve the keys of the array (column titles)
    $fields = array_keys($form_data);
 
    // build the query
     $sql = "INSERT INTO ".$table_name."
    (`".implode('`,`', $fields)."`)
    VALUES('".implode("','", $form_data)."')";
 
    // run and return the query result resource
	$insert = mysql_query($sql,$dblink2);
    return $insert;
}

function update_query($table_name,$form_data,$condition)
{
   
	global $dblink2;
	$hostname2 = "203.115.101.124";
	$username2 = "internal_soft";
	$password2 = "123456";
	$databasename2 = "internalsoftware";
	
	$dblink2 = @mysql_connect($hostname2,$username2,$password2) ;

    // retrieve the keys of the array (column titles)
    //$fields = array_keys($form_data);
	$cond = array();
	foreach($condition as $field => $val) {
	   $cond[] = "$field = '$val'";
	}
	
	$fields = array();
	foreach($form_data as $field => $val) {
	   $fields[] = "$field = '$val'";
	}
	
    // build the query 	
	//$sql = "UPDATE ".$table_name." SET ". join(', ', $fields) ." WHERE ".implode('`,`', $cond)."='".implode("','", $condition)."'";
 	$sql = "UPDATE ".$table_name." SET ". join(', ', $fields) ." WHERE ".join(' and ', $cond);
 	 


    // run and return the query result resource
	$update = mysql_query($sql,$dblink2);
    return $update;
}

function getcountRow_live($query)
{
	$hostname = "203.115.101.124";
	$username1 = "harish124";
	$password = "123456";
	$databasename = "matrix";

	$dblink = @mysql_connect($hostname,$username1,$password) ;
	
	$Numberofservice = mysql_query($query,$dblink);
	$count=mysql_num_rows($Numberofservice);
	return $count;
}


function select_query_live($query,$condition=0){
	
	$hostname = "203.115.101.124";
	$username1 = "harish124";
	$password = "123456";
	$databasename = "matrix";

	$dblink = @mysql_connect($hostname,$username1,$password) ;
	
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

function update_query_live($table_name,$form_data,$condition)
{
   
	$hostname_live = "203.115.101.62";
	$username1_live = "for124server";
	$password_live = "priya@9876";
	$databasename_live = "matrix";

	$dblink_live = @mysql_connect($hostname_live,$username1_live,$password_live) ;

    // retrieve the keys of the array (column titles)
    //$fields = array_keys($form_data);
 	$cond = array();
	foreach($condition as $field => $val) {
	   $cond[] = "$field = '$val'";
	}
	
	$fields = array();
	foreach($form_data as $field => $val) {
		
	   	if($val=='0' || $val=='1')
		{
			$fields[] = "$field = $val";
		}
		else
		{
	   		$fields[] = "$field = '$val'";
		}
	}
	
    // build the query 	
	$sql = "UPDATE ".$table_name." SET ". join(', ', $fields) ." WHERE ".join(' and ', $cond);
 
    // run and return the query result resource
	$update = mysql_query($sql,$dblink_live);
    return $update;
}

function select_query_live_con($query,$condition=0){
	
	$hostname_live = "203.115.101.62";
	$username1_live = "for124server";
	$password_live = "priya@9876";
	$databasename_live = "matrix";

	$dblink_live = @mysql_connect($hostname_live,$username1_live,$password_live) ;
	
   if($condition==1){
    //echo "<br>".$query."<br>";
   }

  $qry=@mysql_query($query,$dblink_live);// or die( $query . " ". mysql_error());
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
  mysql_close();
  return $arr;
}

function insert_query_live_con($table_name, $form_data)
{
   
	$hostname_live = "203.115.101.62";
	$username1_live = "for124server";
	$password_live = "priya@9876";
	$databasename_live = "matrix";

	$dblink_live = @mysql_connect($hostname_live,$username1_live,$password_live) ;

    // retrieve the keys of the array (column titles)
    $fields = array_keys($form_data);
 
    // build the query
     $sql = "INSERT INTO ".$table_name."
    (`".implode('`,`', $fields)."`)
    VALUES('".implode("','", $form_data)."')";
 
    // run and return the query result resource
	$insert = mysql_query($sql,$dblink_live);
    return $insert;
}

function update_query_live_con($table_name,$form_data,$condition)
{
   
	$hostname_live = "203.115.101.62";
	$username1_live = "for124server";
	$password_live = "priya@9876";
	$databasename_live = "matrix";

	$dblink_live = @mysql_connect($hostname_live,$username1_live,$password_live) ;

    // retrieve the keys of the array (column titles)
    //$fields = array_keys($form_data);
 	$cond = array();
	foreach($condition as $field => $val) {
	   $cond[] = "$field = '$val'";
	}
	
	$fields = array();
	foreach($form_data as $field => $val) {
		
	   	if($val=='0' || $val=='1')
		{
			$fields[] = "$field = $val";
		}
		else
		{
	   		$fields[] = "$field = '$val'";
		}
	}
	
    // build the query 	
	//$sql = "UPDATE ".$table_name." SET ". join(', ', $fields) ." WHERE ".implode('`,`', $cond)."='".implode("','", $condition)."'";
	$sql = "UPDATE ".$table_name." SET ". join(', ', $fields) ." WHERE ".join(' and ', $cond);
 
    // run and return the query result resource
	$update = mysql_query($sql,$dblink_live);
    return $update;
}


/************  New Inventory Connection code  ***/

function getcountRow_inventory($query)
 {
    global $dblink_inv;
    $hostname_inv = "203.115.101.30";
    $username_inv = "visiontek11000";
    $password_inv = "123456";
    $databasename_inv = "inventory";
    
    $dblink_inv = mysql_connect($hostname_inv,$username_inv,$password_inv) ;

    $Numberofservice = mysql_query($query);
    $count=mysql_num_rows($Numberofservice);
    return $count;
 }
 
 function select_query_inventory($query,$condition=0){
     
    global $dblink_inv;
    $hostname_inv = "203.115.101.30";
    $username_inv = "visiontek11000";
    $password_inv = "123456";
    $databasename_inv = "inventory";
    
    $dblink_inv = mysql_connect($hostname_inv,$username_inv,$password_inv) ;

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
 
 function insert_query_inventory($table_name, $form_data)
{
   
    global $dblink_inv;
    $hostname_inv = "203.115.101.30";
    $username_inv = "visiontek11000";
    $password_inv = "123456";
    $databasename_inv = "inventory";
    
    $dblink_inv = mysql_connect($hostname_inv,$username_inv,$password_inv) ;

    // retrieve the keys of the array (column titles)
    $fields = array_keys($form_data);
 
    // build the query
     $sql = "INSERT INTO ".$table_name."
    (`".implode('`,`', $fields)."`)
    VALUES('".implode("','", $form_data)."')";
 
    // run and return the query result resource
    $insert = mysql_query($sql,$dblink_inv);
    return $insert;
}


function update_query_inventory($table_name,$form_data,$condition)
{
   
    global $dblink_inv;
    $hostname_inv = "203.115.101.30";
    $username_inv = "visiontek11000";
    $password_inv = "123456";
    $databasename_inv = "inventory";
    
    $dblink_inv = mysql_connect($hostname_inv,$username_inv,$password_inv) ;

    // retrieve the keys of the array (column titles)
    
     $cond = array();
	foreach($condition as $field => $val) {
	   $cond[] = "$field = '$val'";
	}
	
	$fields = array();
	foreach($form_data as $field => $val) {
	   $fields[] = "$field = '$val'";
	}
	
    // build the query 	
	 $sql = "UPDATE ".$table_name." SET ". join(', ', $fields) ." WHERE ".join(' and ', $cond);
 
    // run and return the query result resource
    $update = mysql_query($sql,$dblink_inv);
    return $update;
}


/*******************  End Connection Code ***********************/


function Sendmail($Subject,$message,$to,$cc)
{
if($to!='')
	{
 include("D:/xampp/htdocs/send_alert/class.phpmailer.php");
include("D:/xampp/htdocs/send_alert/class.smtp.php");
 
 
 
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