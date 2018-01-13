<?php
include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");  
?> 

 
 
<div class="top-bar">
<h1>ADD City</h1>
</div>
<div class="table"> 

<?
 
 
if(isset($_POST['submit']))
{ 
	$date = date("Y-m-d H:i:s");
	$account_manager = $_SESSION['username'];
	 
	$Device_model = $_POST['Device_model'];
	$TxtDeviceIMEI = $TxtDeviceIMEI;
	$date_of_install = $date_of_install;
	$Notwoking = $Notwoking;
	$branch_type = $_POST['inter_branch'];
	if($branch_type == "Interbranch"){
		$city=$_POST['inter_branch_loc'];
		$location="";
	}else{
		$city=0;
		$location=$_POST['location'];
	}
	
	 
	 
}
?>
 
   <table style="width: 900px;" cellspacing="2" cellpadding="3" border="0">
		 
      

<?php
// define variables and set to empty values
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["name"])) {
    echo $nameErr = "Name is required";
	 die();
   } else {
     $name = test_input($_POST["name"]);
     // check if name only contains letters and whitespace
     if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
       $nameErr = "Only letters and white space allowed";
     }
   }
  
   if (empty($_POST["url"])) {
     echo $urlErr = "URL is required";
	 die();
   } else {
     $url = test_input($_POST["url"]);
     
     
   }
   
  
     $Label = test_input($_POST["Label"]);
  $state = test_input($_POST["state"]);

   if (empty($_POST["category"])) {

    echo $categoryErr = "category is required";
	 die();
   } else {
     $category = test_input($_POST["category"]);
   }
    //$url = "http://global.mapit.mysociety.org/area/646556.geojson";
$json = file_get_contents($url);
$json_data = json_decode($json, true);
$account_manager = $_SESSION['username'];
  $sql="INSERT INTO livetrack.`gtrac_mapit` (`name`, `state`, `category`, `label`,  `created_by`, `ref_url`) VALUES ('".$name."','".$state."', ".$category.", ".$Label.",  '".$account_manager."', '".$url."');";
 
	 $execute=mysql_query($sql);
	  
	     $insertId=mysql_insert_id();  

		 if($insertId>0)
	{
for($points=0;$points<count($json_data["coordinates"][0]);$points++)
{

$ArrLatlong=$json_data["coordinates"][0][$points];
  $latitude=$ArrLatlong[1];
  $Longitude=$ArrLatlong[0];
$mapit_id= $insertId;
$order=$points;

$sql1="INSERT INTO livetrack.`gtrac_geofence` (`mapit_id`, `order`, `gps_latitude`, `gps_longitude`) VALUES ( ".$mapit_id.", ".$order.", ".$latitude.", ".$Longitude.");";
	 $execute1=mysql_query($sql1);
   
}
$successMessgae="Success";
	}
	else
	{
		$successMessgae="Name already exist";

	}
 
    
 
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
  
<tr><td colspan="2"><font color="red" size="5"><b> <?=$successMessgae;?><b/></font></td></tr>
 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <tr><td> Name: </td><td><input type="text" name="name" value="">
   <span class="error">* <?php echo $nameErr;?></span>
   </td></tr>

<tr><td> State: </td><td><input type="text" name="state" value="">
   <span class="error">* <?php echo $stateerr;?></span>
   </td></tr>


   
   <tr><td> URL: </td><td> <input type="text" style="width: 300px" name="url" value="">
   <span class="error">* <?php echo $UrlErr;?></span>
  </td></tr>
  <tr><td> Label: </td><td> <input type="text" name="Label" value="">
   <span class="error"><?php echo $LabelErr;?></span>
 </td></tr>
    
   <tr><td> Category: </td><td> 
   
   <input type="radio" name="category" <?php if (isset($category) && $category=="1") echo "checked";?>  value="1">State
   <input type="radio" name="category" <?php if (isset($category) && $category=="2") echo "checked";?>  value="2">City
     <input type="radio" name="category" <?php if (isset($category) && $category=="3") echo "checked";?>  value="3">Sub City
      <input type="radio" name="category" <?php if (isset($category) && $category=="4") echo "checked";?>  value="4">District

   <span class="error">* <?php echo $categoryErr;?></span>
  </td></tr>
   <tr><td colspan="2"><input type="submit" name="submit" value="Submit"></td></tr> 
</form>
 

 
       

</table>
</form>
 


  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
        
            <th>Sl. No</th>
            <th>Name </th>
             <th>State </th>
            
            <th>URL</th>
			 <th>Created Date Time</th>


             
		</tr>
	</thead>
	<tbody>


 
<?php 
//echo "select name,state,category,created_by,ref_url from livetrack.gtrac_mapit  where created_by='". $_SESSION['username']."'";
$rs = mysql_query("select name,state,category,created_by,ref_url,created_datetime from livetrack.gtrac_mapit  where created_by='". $_SESSION['username']."'",$dblink2);
$i=1;
    while ($row = mysql_fetch_array($rs)) {
 	
    ?>  
    	<tr   >
		<td><?php echo $i ?></td>
		<td>&nbsp;<?php echo $row['name'];?></td>
		<td>&nbsp;<?php echo $row['state'];?></td>
		<td>&nbsp;<?php echo $row['ref_url'];?></td>
		<td>&nbsp;<?php echo $row['created_datetime'];?></td>
		 
 
</tr> <?php $i++; }?>
</table>
<?
include("../include/footer.inc.php");

?>
 