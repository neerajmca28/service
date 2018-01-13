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
	$result=select_query("select * from deactivation_of_account where id=$id");	
	}
?> 

<div class="top-bar">
<h1>Deactivation Of Account</h1>
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
$alert_date=$_POST["alert_date"];

$selected_radio=$_POST['deactivateStatus'];

 $DeleteFromDebtors=$_POST['DeleteFromDebtors'];
 
  $service_comment = $_POST["service_comment"];

$payment_status=$_POST["payment_status"];


$reason=$_POST["reason"];


if($action=='edit')
	{
	if($result[0]['deactivate_temp']=='Permanent')
{ 
$query="update deactivation_of_account set date='".$date."',acc_manager='".$acc_manager."',user_id='".$main_user_id."',company='".$company."',total_no_of_vehicles='".$tot_no_of_vehicles."',deactivate_temp='".$selected_radio."',reason='".$reason."',delete_form_debtors='".$DeleteFromDebtors."',service_comment='".$service_comment."' where id=$id";
 }
 else if($result[0]['deactivate_temp']=='temporary') {
 $query="update deactivation_of_account set date='".$date."',acc_manager='".$acc_manager."',user_id='".$main_user_id."',company='".$company."',total_no_of_vehicles='".$tot_no_of_vehicles."',deactivate_temp='".$selected_radio."',reason='".$reason."',alert_date='".$alert_date."',service_comment='".$service_comment."' where id=$id";
 }
 
 $query;
 mysql_query($query);
echo "<script>document.location.href ='list_deactivate_of_account.php'</script>";
	}
  else
  {

if($selected_radio=='Permanent')
{ 
   $query="INSERT INTO `deactivation_of_account` (`date`, `acc_manager`, `company`, `user_id`, `total_no_of_vehicles`,deactivate_temp,`reason`,delete_form_debtors) VALUES ('".$date."','".$acc_manager."','".$company."','".$main_user_id."','".$tot_no_of_vehicles."','".$selected_radio."','".$reason."','".$DeleteFromDebtors."')";
}
else {
$query="INSERT INTO `deactivation_of_account` (`date`, `acc_manager`, `company`, `user_id`, `total_no_of_vehicles`,deactivate_temp,`reason`,alert_date) VALUES ('".$date."','".$acc_manager."','".$company."','".$main_user_id."','".$tot_no_of_vehicles."','".$selected_radio."','".$reason."','".$alert_date."')";

}
mysql_query($query);
 echo "<script>document.location.href ='list_deactivate_of_account.php'</script>";
//header('location: sales_request.php');
}
}
?>
 
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">
$(function() {
$( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });

$( "#datepickercheque" ).datepicker({ dateFormat: "yy-mm-dd" });

});
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
			
			function Status(radioValue)
{
 if(radioValue=="Permanent")
	{
	document.getElementById('temporarysat').style.display = "none";
	document.getElementById('Permanentsat').style.display = "block";
	document.getElementById('temporarysat1').style.display = "none";
	document.getElementById('Permanentsat1').style.display = "none";

	}
	else if(radioValue=="temporary")
	{

	document.getElementById('temporarysat').style.display = "block";
	document.getElementById('Permanentsat').style.display = "none";
	document.getElementById('Permanentsat1').style.display = "none";
	document.getElementById('temporarysat1').style.display = "none";
	}
	else
	{
	document.getElementById('temporarysat1').style.display = "none";
	document.getElementById('Permanentsat').style.display = "none";
	document.getElementById('Permanentsat1').style.display = "none";
	document.getElementById('temporarysat1').style.display = "none";
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
                <input type="text" name="account_manager" id="TxtAccManager" readonly value="<?=$result[0]['acc_manager']?>"/></td>
        </tr>
               <tr>
            <td>
                User Name</td>
            <td>

<select name="main_user_id" id="TxtMainUserId"  onchange="gettotal_veh_byuser(this.value,'TxtTotalVehicle');getCompanyName(this.value,'TxtCompany');">
 
            <option value="" name="main_user_id" id="TxtMainUserId">-- Select One --</option>
            <?php
			$main_user_id = select_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE Branch_id=".$_SESSION['BranchId']." order by name asc");
			//while ($data=mysql_fetch_assoc($main_user_id))
			for($cl=0;$cl<count($main_user_id);$cl++)
					{
			?>
            
            <option name="main_user_id" value="<?=$main_user_id[$cl]['user_id']?>" <? if($result[0]['user_id']==$main_user_id[$cl]['user_id']) {?> selected="selected" <? } ?> >
        <?php echo $main_user_id[$cl]['name']; ?>
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
            <td><input type="text" name="company" id="TxtCompany" value="<?=$result[0]['company']?>"  readonly />
                </td>
        </tr>

		  <tr>
            <td>
                Total No Of Vehicle</td>
            <td><input type="text" name="tot_no_of_vehicles" id="TxtTotalVehicle" value="<?=$result[0]['total_no_of_vehicles']?>"  readonly />
                </td>
        </tr>
   </table>
       <table cellspacing="5" cellpadding="5" style=" padding-left: 100px;width: 500px;" >

        <tr>
            <td class="style2">
                <h1>Deactivate</h1></td>
            <td>

 <Input type = 'Radio' Name ='deactivateStatus'    value= 'temporary' <?php if($result[0]['deactivate_temp']=="temporary"){echo "checked=\"checked\""; }?>
onchange="Status(this.value)"
>Temporary

<Input type = 'Radio' Name ='deactivateStatus'    value= 'Permanent' <?php if($result[0]['deactivate_temp']=="Permanent"){echo "checked=\"checked\""; }?>
onchange="Status(this.value)"
>Permanent</td>
</tr>

       <?php if($result[0]['deactivate_temp']=="temporary") { ?>
		  <table  id="temporarysat1" align="center"  style="width: 250px; border:1" cellspacing="5" cellpadding="5">
	<tr>
	
            
             <td> <label  id="lbDlDate">Alert Date</label></td>
			  <td> <input type="text" name="alert_date" id="datepicker" value="<?=$result[0]['alert_date']?>" /></td>
			  </tr>
		</table>
		<? } ?>
				  <table  id="temporarysat"  align="center"  style="width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">

		<tr>       
             <td> <label  id="lbDlDate">Alert Date</label></td>
			  <td> <input type="text" name="alert_date" id="datepicker" value="<?=$result[0]['alert_date']?>" /></td>
			 	</tr>
		 </table>
        
		<?php if($result[0]['deactivate_temp']=="Permanent") { ?>
		 		  <table  id="Permanentsat1" align="center"  style="width: 200px; border:1" cellspacing="5" cellpadding="5">

	<tr>
		<td>

		<Input type = 'Radio' Name ='DeleteFromDebtors'    value= 'Yes' <?php if($result[0]['delete_form_debtors']=="Yes"){echo "checked=\"checked\""; }?> />
		Delete From Debtors
		</td></tr></table>
		<? } ?>
		
		  <table  id="Permanentsat" align="center"   style="width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">

		<tr>       
         
          <td>

		<Input type = 'Radio' Name ='DeleteFromDebtors'    value= 'Yes' <?php if($result[0]['delete_form_debtors']=="Yes"){echo "checked=\"checked\""; }?> />
 
 Delete From Debtors
       </td></tr></table>

		
    <table style=" padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">
	
        <tr>
            <td width="173" class="style2">
                Reason</td>
            <td width="290">
          <Textarea rows="5" cols="23" type="text" name="reason" id="TxtReason"><?=$result[0]['reason']?></textarea></td>
        </tr>
		<tr>

<td height="32" align="right"><div align="center">No of Devices Removed*:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td><td>
<select name="Installtion_Made" id="Installtion_Made" onchange="DetailVehicle(this.value,2143)">
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
            <td><textarea rows="5" cols="25"  type="text" name="service_comment" id="TxtServiceComment" ><?=$result[0]['service_comment']?></textarea>
                </td>
        </tr>
		
        <tr>
		<td> </td>
		<td class="submit"><input type="submit" id="button1" name="submit" value="Submit" onClick="return Check();"/>
	<input type="button" name="Cancel" value="Cancel" onClick="window.location = 'list_deactivate_of_account.php' " /></td>
		</tr>
     </table>
	 </div>
 
<?php
include("../include/footer.php"); ?>

   
	 
  