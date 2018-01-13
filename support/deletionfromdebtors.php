<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/
	 
$action=$_GET['action'];
$id=$_GET['id'];
$page=$_POST['page'];
if($action=='edit' or $action=='editp')
	{
		$result=mysql_fetch_array(mysql_query("select * from del_form_debtors where id=$id"));	
	}
?> 

<div class="top-bar">
<h1>Delete from debtors</h1>
</div>
<div class="table"> 

<?


 
$temprary= 'checked';
$permanent = 'unchecked';
$tot_no_of_vehicles=0;
if(isset($_POST["submit"]))
{
$date=$_POST["date"];
$acc_manager=$_POST["account_manager"];
$company=$_POST["company"];
$main_user_id=$_POST["main_user_id"];
$tot_no_of_vehicles=$_POST["tot_no_of_vehicles"];
 $date_of_creation=$_POST["date_of_creation"];
  $service_comment = $_POST['service_comment'];
$payment_status=$_POST["payment_status"];

$reason=$_POST["reason"];

 
if($action=='edit')
	{
	
$query="update del_form_debtors set date='".$date."',acc_manager='".$acc_manager."',user_id='".$main_user_id."',company='".$company."',total_no_of_vehicle='".$tot_no_of_vehicles."',date_of_creation='".$date_of_creation."',reason='".$reason."',service_comment='".$service_comment."' where id=$id";
 
 $query;
 mysql_query($query);
echo "<script>document.location.href ='list_deletion_from_debtors.php'</script>";
	}
  else
  {
 
    $query="
INSERT INTO `del_form_debtors` ( `date`, `acc_manager`, `company`, `user_id`, `date_of_creation`, `total_no_of_vehicle`, `reason`) VALUES ('".$date."','".$acc_manager."', '".$company."', '".$main_user_id."', '".$date_of_creation."', '".$tot_no_of_vehicles."', '".$reason."');";


 mysql_query($query);
echo "<script>document.location.href ='list_deletion_from_debtors.php'</script>";
//header('location: sales_request.php');
}}

?>
 
 
<script type="text/javascript">
    function validateForm()
			{

				//date,account_manager,main_user_id,company,tot_no_of_vehicles,tot_no_of_vehicles,contact_number,name,req_sub_user_pass,billing_separate,billing_name,reason
 

			 
			  var main_user_id=document.forms["myForm"]["main_user_id"].value;
			if (main_user_id==null || main_user_id=="")
			  {
			  alert("Select Username");
			  return false;
			  }



			     var main_user_id=document.forms["myForm"]["reason"].value;
			if (main_user_id==null || main_user_id=="")
			  {
			  alert("Enter Reason");
			  return false;
			  }

			  
			    

			   
			}
    </script>
 <form name="myForm" action="" onsubmit="return validateForm()" method="post">
    <table style=" padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">
         <tr>
            <td>Date</td>
            <td>
                <input type="text" name="date" id="datepicker1" readonly value="<?= date("Y-m-d H:i:s")?>" /></td>
        </tr>

		<tr>
            <td>Account Manager</td>
            <td>
                <input type="text" name="account_manager" id="TxtAccManager" readonly value="<?=$result['acc_manager']?>"/></td>
        </tr>
               <tr>
            <td>
                User Name</td>
            <td>

<select name="main_user_id" id="TxtMainUserId"  onchange="gettotal_veh_byuser(this.value,'TxtTotalVehicle');getCompanyName(this.value,'TxtCompany');getCreationDate(this.value,'date_of_creation')">
            <option value="" name="main_user_id" id="TxtMainUserId">-- Select One --</option>
            <?php
			$main_user_id=mysql_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE Branch_id=".$_SESSION['BranchId']." order by name asc");
			while ($data=mysql_fetch_assoc($main_user_id))
					{
			?>
            
           <option name="main_user_id" value="<?=$data['user_id']?>" <? if($result['user_id']==$data['user_id']) {?> selected="selected" <? } ?> >
        <?php echo $data['name']; ?>
					</option>
				  <?php 
								} 
 
  ?>
</select>



                </td>
        </tr>


		 <tr>
            <td>
                Company Name</td>
            <td><input type="text" name="company" id="TxtCompany" value="<?=$result['company']?>" readonly />
                </td>
        </tr>

		  <tr>
            <td>
                Total No Of Vehicle</td>
            <td><input type="text" name="tot_no_of_vehicles" id="TxtTotalVehicle" value="<?=$result['total_no_of_vehicle']?>" readonly />
                </td>
        </tr>
    <tr>
            <td>
                Date Of Creation</td>
            <td><input type="text" name="date_of_creation" id="date_of_creation" value="<?=$result['date_of_creation']?>" readonly />
                </td>
        </tr>
        
        <tr>
            <td class="style2">
                Reason</td>
            <td>
                 <Textarea rows="5" cols="23" type="text" name="reason" id="TxtReason"><?=$result['reason']?></textarea></td>
        </tr>
		<tr>

<td height="32" align="right"><div align="left">No of Devices Removed*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td><td>
<select name="Installation_Made" id="Installation_Made" onchange="DetailVehicle(this.value,2143)">
	<option value="">Select Number</option>
	<option value="0">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="7">7</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
        </select>
</td>

  
</tr>
<tr>

<td height="32" align="right"><div align="left">Add vehicle number*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>

<td> 
<div id="DetailVehicle">
 
 </div>
</td>

  
</tr>

		 <tr>
            <td class="style2">
                Service Comment</td>
            <td><textarea rows="5" cols="25"  type="text" name="service_comment" id="TxtServiceComment" ><?=$result['service_comment']?></textarea>
                </td>
        </tr>
		
                <tr>

				<td class="submit"><input type="submit" id="button1" name="submit" value="Submit" onClick="return Check();"/>
				   <input type="button" name="Cancel" value="Cancel" onClick="window.location = 'list_deletion_from_debtors.php' " /></td>

		</tr>
     </table>
	 </div>
 
<?php
include("../include/footer.php"); ?>

   
	 
  