<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu_market.php');

     
$action=$_GET['action'];
$id=$_GET['id'];
$page=$_POST['page'];
if($action=='edit' or $action=='editp')
    {
        $result = select_query_live_con("SELECT * FROM matrix.mapped_market_vehicle WHERE id='".$id."'");  
		if($result[0]['sys_subuser_id']=="7113")
		{
		$NbV="Network";
		}
		else
		{
			$NbV="Business";
		}
    }
	
?>

<div class="top-bar">
<h1>Trip Details</h1>
</div>
<div class="table">
<?php
 

if(isset($_POST["submit"]))
{
     $getid = $_POST['rowid'];
	 $source = $_POST['source'];
	 $destination = $_POST['destination'];
	 $consignmentno = $_POST['consignmentno'];
	 $driver_name = $_POST['driver_name'];
	 $phone_no = $_POST['phone_no'];
	 $transport_name = $_POST['transport_name'];
	 $N_b= $_POST['N_b'];

	 if(strtolower($N_b)=="network")
	{
		 $sys_subuser_id="7113";
	}
	else
	{
		 $sys_subuser_id="7112";
	}

	    
 if($action=='edit')
    {
   
		$data1 = array( 'source' => $source, 'destination' => $destination, 'consignment_no' => $consignmentno, 'driver_name' => $driver_name, 
		'driver_number' => $phone_no, 'tranport_name' => $transport_name,'sys_subuser_id' => $sys_subuser_id,  'internal_status' => 2);
		$condition = array('id' => $getid);
		
		update_query_live_con('matrix.mapped_market_vehicle', $data1, $condition);
		
		echo "<script>document.location.href ='list_trip_entry.php'</script>";
    
	}
  
 }

?>


 
   
   
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>

 /*function validateForm()
{
 
  if(document.myForm.TxtMainUserId.value=="")
  {
  alert("please Enter Client Name") ;
  document.myForm.TxtMainUserId.focus();
  return false;
  } 
 

    if(document.myForm.TxtReason.value=="")
    {
      alert("please Enter Reason") ;
      document.myForm.TxtReason.focus();
      return false;
    }
   
            } 
           
            function Status(radioValue)
{
 if(radioValue=="Yes")
    {
    document.getElementById('new').style.display = "block";
    }
    else
    {
    document.getElementById('new').style.display = "none";
    }   }*/
</script>

 <form name="myForm" action="" onSubmit="return validateForm()" method="post">
 

    <table width="589" cellpadding="5" cellspacing="5" style=" padding-left: 100px;width: 550px;">
		
        <tr>
            <td>Vehicle No</td>
            <td><input type="text" name="vehicleno" id="vehicleno" value="<?=$result[0]['veh_no']?>"  readonly/>
            <input type="hidden" name="rowid" value="<?=$result[0]['id']?>"</td>
        </tr>
       
        <tr>
            <td>Device Imei</td>
            <td><input type="value" name="device_imei" id="device_imei" value="<?=$result[0]['device_imei']?>"  readonly/></td>
        </tr>

        <tr>
            <td>Recept No</td>
            <td><input type="text" name="receptno" id="receptno" value="<?=$result[0]['recept_no']?>"  readonly/></td>
        </tr>
       
        <tr>
            <td>Image</td>
            <td><? echo '<img src="http://103.16.141.201/tripwise/uploads/'.$result[0]['pic_image'].'" style="width:300px;" height="300px;" />';?> </td>


        </tr>


        <tr>
            <td>Source</td>
            <td><input type="text" name="source" id="source" value="<?=$result[0]['source']?>"  /></td>
        </tr>
       
        <tr>
            <td>Destination</td>
            <td><input type="value" name="destination" id="destination" value="<?=$result[0]['destination']?>"  /></td>
        </tr>


        <tr>
            <td>Consignment No</td>
            <td><input type="text" name="consignmentno" id="consignmentno" value="<?=$result[0]['consignment_no']?>"  /></td>
        </tr>
       
        <tr>
            <td>Driver Name</td>
            <td><input type="value" name="driver_name" id="driver_name" value="<?=$result[0]['driver_name']?>"  /></td>
        </tr>
		 <tr>
            <td>Network/Business</td>
            <td><input type="value" name="N_b" id="N_b" value="<?=$NbV?>"  /></td>
        </tr>

        <tr>
            <td>Driver Phone no</td>
            <td><input type="value" name="phone_no" id="phone_no" value="<?=$result[0]['driver_number']?>"  /></td>
        </tr>

        <tr>
            <td>Transport Name</td>
            <td><input type="value" name="transport_name" id="transport_name" value="<?=$result[0]['tranport_name']?>"  /></td>
        </tr>

        <tr>
            <td colspan="2" align="center"> <input type="submit" name="submit" value="submit"  />
            <input type="button" name="Cancel" value="Cancel" onClick="window.location = 'list_trip_entry.php' " /></td>
        </tr>

    </table>
      </form>
 
 
<?php
include("../include/footer.inc.php"); ?>