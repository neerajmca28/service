<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/

$ftp_server = 'ftp.gpsindelhi.com';
$ftp_user_name = 'itglobal';
$ftp_user_pass = '1TGl0b4lCon5ulT';


$branchid = $_SESSION['BranchId'];

if($branchid == 2)
{
	$name = 'mu';	
}
else if($branchid == 3)
{
	$name = 'jp';	
}
else if($branchid == 6)
{
	$name = 'ab';	
}
else
{
	$name = ' ';	
}

$conn_id = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");

$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

$arr= ftp_nlist($conn_id,"/www.gpsindelhi.com/web/$name");

//var_dump($arr);


?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
var j = jQuery.noConflict();
j(function() 
{
j( "#FromDate" ).datepicker({ dateFormat: "yy-mm-dd" });


});

</script>

<div class="top-bar">
  <div align="center"> </div>
  <h1>Download Client Bill File</h1>
  <br/>
 
 <?php 
 
 foreach ($arr as $value)
{
   /*echo '<a href="'.$value.'">'.basename($value).'</a>';*/
  
   echo '<a href="ftp://ftp.gpsindelhi.com'.$value.'" target="_blank" style="display: inline-block; margin-top: 20px; font-size: 13px;"><b>Download file - '.basename($value).' </b></a>';
   
         echo '<br/>';
}

ftp_close($conn_id);

 
                 


?>
</div>
<?php
include("../include/footer.inc.php"); ?>