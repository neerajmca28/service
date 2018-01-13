<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
 	include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */

 $date=date("Y-m-d H:i:s");
$created_by=$_SESSION['username'];

if($_GET["rowid"])
{
	$querydevice = mysql_query("SELECT * FROM new_account_creation where id=".$_GET["rowid"]);   
$row=mysql_fetch_array($querydevice);

if(count($row)>0)
  {

$Date=$row["date"];                     
  $Account_Manager=$row["account_manager"];
$created_by=$row["created_by"];
$Company=$row["company"];                     
$Potential= $row["potential"];                     
$Contact_Person=$row["contact_person"];                     
$Contact_Number=$row["contact_number"];                     
$Billing_Name=$row["billing_name"];                     
$Billing_Address=$row["billing_address"];                     

$Device_Price= $row["device_price"];                     	
$Vat= $row["device_price_vat"];                     	
$Total= $row["device_price_total"];                     	
$Rent= $row["device_rent_Price"];                     	
$Service_tax= $row["device_rent_service_tax"];                     	
$Total_Rent=$row["DTotalREnt"];                     
 $modeofpayment=$row["mode_of_payment"];                     
$Vehicle_type=$row["vehicle_type"];                     
$Immobilizer =$row["immobilizer"];                     
$AC =$row["ac_on_off"];                     
$E_mail_ID =$row["email_id"];                     
$User_Name=$row["user_name"];                     
$Password=$row["user_password"];                     


 


  } 

} ?>

<div class="top-bar">
<h1>New account creation</h1>
</div>
<div class="table"> 
<?php
 $device_price=0;
 $device_price_vat=0;
 $device_price_total=0;
 $device_rent_Price=0;
 $device_rent_service_tax=0;
 $TxtDTotalREnt=0;







if(isset($_POST["submit"]) && $_POST["submit"]=="submit")
{

$date=(isset($_POST["date"])) ? trim($_POST["date"])  : "";
$company=(isset($_POST["company"])) ? trim($_POST["company"]): "";
$account_manager=(isset($_POST["account_manager"])) ? trim($_POST["account_manager"]): "";
$potential=(isset($_POST["potential"])) ? trim($_POST["potential"]): "";
$contact_person=(isset($_POST["contact_person"])) ? trim($_POST["contact_person"]): "";
$contact_number=(isset($_POST["contact_number"])) ? trim($_POST["contact_number"]): "";
$billing_name=(isset($_POST["billing_name"])) ? trim($_POST["billing_name"]): "";
$billing_address=(isset($_POST["billing_address"])) ? trim($_POST["billing_address"]): "";
$mode_of_payment=(isset($_POST["mode_of_payment"])) ? trim($_POST["mode_of_payment"]): "";


//device_price, device_price_vat,device_price_total,device_rent_Price,device_rent_service_tax,TxtDTotalREnt

if($mode_of_payment=="Cash")
{
$device_price_total=(isset($_POST["device_price_total1"])) ? trim($_POST["device_price_total1"]): 0;
$TxtDTotalREnt=(isset($_POST["TxtDTotalREnt1"])) ? trim($_POST["TxtDTotalREnt1"]): 0;
}
else if($mode_of_payment=="Cheque")

{

	$device_price=(isset($_POST["device_price"])) ? trim($_POST["device_price"]): 0;
$device_price_vat=(isset($_POST["device_price_vat"])) ? trim($_POST["device_price_vat"]): 0;
$device_price_total=(isset($_POST["device_price_total"])) ? trim($_POST["device_price_total"]): 0;
$device_rent_Price=(isset($_POST["device_rent_Price"])) ? trim($_POST["device_rent_Price"]): 0;
$device_rent_service_tax=(isset($_POST["device_rent_service_tax"])) ? trim($_POST["device_rent_service_tax"]): 0;
$TxtDTotalREnt=(isset($_POST["TxtDTotalREnt"])) ? trim($_POST["TxtDTotalREnt"]): 0;
}



 




$vehicle_type=(isset($_POST["vehicle_type"])) ? trim($_POST["vehicle_type"]): "";
$immobilizer=(isset($_POST["immobilizer"])) ? trim($_POST["immobilizer"]): "";
$ac_on_off=(isset($_POST["ac_on_off"])) ? trim($_POST["ac_on_off"]): "";
$email_id=(isset($_POST["email_id"])) ? trim($_POST["email_id"]): "";
$user_name=(isset($_POST["user_name"])) ? trim($_POST["user_name"]): "";
$user_password=(isset($_POST["user_password"])) ? trim($_POST["user_password"]): "";
$created_by=$_POST["created_by"];
	//device_price, device_price_vat,device_price_total,device_rent_Price,device_rent_service_tax,TxtDTotalREnt
 
if($_GET["rowid"])
{
    
	  $query="update new_account_creation set account_manager='".$account_manager."',company='".$company."', potential='".$potential."', contact_person='".$contact_person."', contact_number='".$contact_number."', billing_name='".$billing_name."', billing_address='".$billing_address."', device_price='".$device_price."', device_price_vat='".$device_price_vat."', device_price_total='".$device_price_total."', device_rent_Price='".$device_rent_Price."', device_rent_service_tax='".$device_rent_service_tax."', DTotalREnt='".$TxtDTotalREnt."', mode_of_payment='".$mode_of_payment."', vehicle_type='".$vehicle_type."', immobilizer='".$immobilizer."', ac_on_off='".$ac_on_off."', email_id='".$email_id."', user_name='".$user_name."', user_password='".$user_password."', approve_status=1 where id=".$_GET["rowid"];
	 
}
else
	{
	 $query="insert into new_account_creation(date,account_manager,company,potential,contact_person,contact_number,billing_name,billing_address,device_price, device_price_vat,device_price_total,device_rent_Price,device_rent_service_tax,DTotalREnt,mode_of_payment,vehicle_type,immobilizer,ac_on_off,email_id,user_name,user_password,created_by,approve_status) values('".$date."','".$account_manager."','".$company."','".$potential."','".$contact_person."',".$contact_number.",'".$billing_name."','".$billing_address."',".$device_price.",".$device_price_vat.",".$device_price_total.",".$device_rent_Price.",".$device_rent_service_tax.",".$TxtDTotalREnt.",'".$mode_of_payment."','".$vehicle_type."','".$immobilizer."','".$ac_on_off."','".$email_id."','".$user_name."','".$user_password."','".$created_by."',1)";
	}

 
if(mysql_query($query))
	{
	//echo "record saved";
  
	 	echo "<script>document.location.href ='accountcreation.php'</script>";
	}
	 



}

?> 
    
	
	 
 

    <script type="text/javascript">
        
   function validateForm()
{

	if(document.myForm.account_manager.value=="")
	{
  	alert("please Enter Account manager name") ;
  	document.myForm.account_manager.focus();
  	return false;
  	}
 
 
  if(document.myForm.TxtCompany.value=="")
  {
  alert("please Enter Company Name") ;
  document.myForm.TxtCompany.focus();
  return false;
  }  
   if(document.myForm.TxtPotentail.value=="")
  {
  alert("please Enter Potential") ;
  document.myForm.TxtPotentail.focus();
  return false;
  }  
 if(document.myForm.TxtContactPerson.value=="")
  {
  alert("please Enter Contact Person") ;
  document.myForm.TxtContactPerson.focus();
  return false;
  }
  if(document.myForm.TxtContactNumber.value=="")
  {
  alert("please Enter Contact No. ") ;
  document.myForm.TxtContactNumber.focus();
  return false;
  }
   var TxtContactNumber=document.myForm.TxtContactNumber.value;
  if(TxtContactNumber!="")
        {
	var length=TxtContactNumber.length;
	
        if(length < 9 || length > 15 || TxtContactNumber.search(/[^0-9\-()+]/g) != -1 )
        {
        alert('Please enter valid mobile number');
        document.myForm.TxtContactNumber.focus();
        document.myForm.TxtContactNumber.value="";
        return false;
        }
        }
  if(document.myForm.TxtBillingName.value=="")
  {
  alert("please Enter Billing name") ;
  document.myForm.TxtBillingName.focus();
  return false;
  }

 if(document.myForm.TxtBillingAdd.value=="")
  {
  alert("please Enter Billing Address") ;
  document.myForm.TxtBillingName.focus();
  return false;
  }  
 
  
  if(document.myForm.mode_of_payment.value=="")
  {
  alert("please Choose Mode") ;
  document.myForm.mode_of_payment.focus();
  return false;
  }
  
  
  			if(document.myForm.mode_of_payment.value=="Cash")

 {
  if(document.myForm.TxtDPricecash.value=="")
  {
  alert("please Enter Device Price") ;
  document.myForm.TxtDPricecash.focus();
  return false;
  }
  if(document.myForm.TxtDRentCash.value=="")
  {
  alert("please Enter Rent") ;
  document.myForm.TxtDRentCash.focus();
  return false;
  }
	}
	
	else
	{
	
	if(document.myForm.TxtDPrice.value=="")
	{
  	alert("please Enter Device Price") ;
  	document.myForm.TxtDPrice.focus();
  	return false;
  	}
	
	if(document.myForm.TxtDRent.value=="")
	{
  	alert("please Enter Rent") ;
  	document.myForm.TxtDRent.focus();
  	return false;
  	}	
	}
	
	
	if(document.myForm.TxtVehicleType.value=="")
	{
  	alert("please Enter Vehicle type") ;
  	document.myForm.TxtVehicleType.focus();
  	return false;
  	}
	if(document.myForm.TxtEmailId.value=="")
	{
  	alert("please Enter E-mail ID") ;
  	document.myForm.TxtEmailId.focus();
  	return false;
  	}
	if(document.myForm.TxtUserName.value=="")
	{
  	alert("please Enter Username") ;
  	document.myForm.TxtUserName.focus();
  	return false;
	}
	if(document.myForm.TxtUserPass.value=="")
	{
  	alert("please Enter Password") ;
  	document.myForm.TxtUserPass.focus();
  	return false;
	}
	
			} 
 
function PaymentProcessBYCash(radioValue)
{
	 
if(radioValue=="Cash")
	{
	document.getElementById('InTaxCase').style.display = "none";

	document.getElementById('InNoTaxCase').style.display = "block";

	}
	else if(radioValue=="Cheque")
	{

	document.getElementById('InTaxCase').style.display = "block";

	document.getElementById('InNoTaxCase').style.display = "none";

	}
	else
	{
		document.getElementById('InTaxCase').style.display = "none";

	document.getElementById('InNoTaxCase').style.display = "none";

	}

 

}


function calculatetotal(price)
{
 var vatp = 5;
 
 
document.getElementById('TxtDVat').value= price * vatp / 100;
document.getElementById('TxtDTotal').value=parseInt(price)+parseInt(document.getElementById('TxtDVat').value);
//alert(result);

}

function calculaterent(price)
{

var vatp = 12.36; 
 
document.getElementById('TxtDServiceTax').value= price * vatp / 100;
document.getElementById('TxtDTotalREnt').value=parseInt(price)+parseInt(document.getElementById('TxtDServiceTax').value); 


//alert(result);

}

  

    </script>
	                     
 
<form method="POST" action="" name="myForm" onsubmit="return validateForm()">

    <table style=" padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">
	
        <tr>
            <td>Date</td>
            <td>
                <input type="text" name="date" id="datepicker1" readonly value="<?= $Date?>" /></td>
        </tr>

			<tr>
            <td  >Created By: *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>
                <input type="text" name="created_by" id="created_by" readonly value="<?echo $created_by?>"/></td>
        </tr>

		<tr>
            <td>Account Manager</td>
            <td>
			
			<? $query="select id,name from matrix.salescollection_user where role=1";
			$result=select_query_live($query);
			 
 
?>
			 <select name="account_manager" id="account_manager" ><option value="0">Select Name</option>
<? //while($row=mysql_fetch_array($result)) 
	for($k=0;$k<count($result);$k++)
	{
?>	
		 
<option value="<?=$result[$k]['name'];?>" <? if($Account_Manager == $result[$k]['name']) echo 'selected="selected"' ?> ><?=$result[$k]['name']?></option>
<? } ?>
</select>

               <!--  <input type="text" name="account_manager" id="TxtAccManager" readonly value="<?echo $_SESSION['user_name'];?>"/></td>
        --> </tr>
        <tr>
        <td>Company Name</td>
        
        <td>
         <input type="text" name="company" id="TxtCompany" value="<?echo $Company;?>"/>
        </td>
        </tr>
 

        
        <tr>
            <td>Potential</td>
            <td>
               <input type="text" name="potential" id="TxtPotentail" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="<?echo $Potential;?>" /></td>
        </tr>
        <tr>
            <td>Contact Person</td>
            <td>
                <input type="text" name="contact_person" id="TxtContactPerson" value="<?echo $Contact_Person;?>" /></td>
        </tr>
        <tr>
            <td>Contact Number</td>
            <td>
                <input type="value" name="contact_number" id="TxtContactNumber" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="<?echo $Contact_Number;?>"/></td>
        </tr>
        <tr>
            <td>Billing Name</td>
            <td>
                <input type="text" name="billing_name" id="TxtBillingName" value="<?echo $Billing_Name;?>" /></td>
        </tr>
        <tr>
            <td>Billing Address</td>
            <td>
                
			   <textarea rows="4" cols="25"  name="billing_address" id="TxtBillingAdd"><?echo $Billing_Address;?>
 </textarea> 
			   
			   </td>
        </tr>
       
            <tr>
            <td><h1>Device Rate</h1></td>
			<td></td>
            </tr>
			<tr>
            <td>
               <label for="Modof_payment"  id="lblModPayment">Mode Of Payment</label></td>
            <td>

<select name="mode_of_payment" id="mode_of_payment" onchange="PaymentProcessBYCash(this.value)" >
			<option value="" >-- select one --</option>
  <option value="Cash" <?if($modeofpayment=="Cash")echo "selected"; ?>>Cash</option>
  <option value="Cheque" <?if($modeofpayment=="Cheque")echo "selected"; ?>>Cheque</option>
   
  </select>




			<!-- <Input type = 'Radio' Name ='mode_of_payment' id='mode_of_payment' value= 'Cash' onclick="PaymentProcessBYCash(this.value)"
<?PHP //print $male_status; ?>
>Cash

<Input type = 'Radio' Name ='mode_of_payment'  id='mode_of_payment' value='Cheque'   onclick="PaymentProcessBYCash(this.value)"
<?PHP //print $female_status; ?>
>Cheque
             -->   <!-- <input type="text" name="mode_of_payment" id="TxtModPayment" /> --></td>
        </tr>
           
            <tr><td colspan="2"> 
 			     <table  id="InTaxCase"  style="width: 500px;display:none;border:1" cellspacing="5" cellpadding="0">

 






			  <tr><td> <label for="price" id="lblDprice">Device Price</label></td>
			 <td> <input type="value" value="<?echo $Device_Price;?>" name="device_price" id="TxtDPrice"  onkeypress='return event.charCode >= 48 && event.charCode <= 57' onblur="calculatetotal(this.value);"/></td></tr>
            <tr><td> <label for="vat" id="lblDvat">Vat(5%)</label></td>
			<td> <input type="value" name="device_price_vat" id="TxtDVat" readonly value="<?echo $Vat;?>" /></td></tr>
              <tr><td> <label for="total" id="lblDTotal">Total</label></td>
			  <td> <input type="value" name="device_price_total" id="TxtDTotal" readonly value="<?echo $Total;?>" /></td></tr>
              <tr><td> <label for="rent" id="lblDRent">Rent</label></td>
			   <td> <input type="value" name="device_rent_Price" id="TxtDRent" value="<?echo $Rent;?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' onblur="calculaterent(this.value);"/></td></tr>
              <tr><td> <label for="service_tax" id="lblDServiceTax">Service Tex(12.36%)</label></td>
            <td> <input type="value" name="device_rent_service_tax" id="TxtDServiceTax" readonly value="<?echo $Service_tax;?>"/></td></tr>

			<tr><td> <label for="TxtDTotalREnt" id="lblDrentTotal">Total Rent</label></td>
            <td> <input type="value" name="TxtDTotalREnt" id="TxtDTotalREnt" readonly value="<?echo $Total_Rent;?>" /></td></tr>
          
  </table>
 </tr></td> 
  <tr><td colspan="2">

  
 			     <table  id="InNoTaxCase"    style="width: 500px;display:none;border:1"  cellspacing="5" cellpadding="0">
		  <tr><td> <label for="price" id="lblDprice">Device Price</label></td><td> <input type="value" name="device_price_total1" id="TxtDPricecash" value="<?echo $Total;?>"  onkeypress='return event.charCode >= 48 && event.charCode <= 57'  /></td></tr>
            
			 
              <tr><td> <label for="rent" id="lblDRent">Rent</label></td>
			   <td> <input type="value" name="TxtDTotalREnt1" id="TxtDRentCash" value="<?echo $Total_Rent;?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' /></td></tr> 
           
 </table>
         </tr></td>       
        </tr>
        
        <tr>
            <td>
               <label for="Vehicle_Type" id="lblVehicleType">Vehicle Type</label></td>
            <td>
			<select name="vehicle_type" id="TxtVehicleType"  >
			<option value="" id="TxtVehicleType">-- select one --</option>
  <option value="car" id="TxtVehicleType" <?if($Vehicle_type=="car")echo "selected";?>>Taxi</option>
  <option value="bus" id="TxtVehicleType" <?if($Vehicle_type=="bus")echo "selected";?>>Bus</option>
  <option value="truck" id="TxtVehicleType" <?if($Vehicle_type=="truck")echo "selected";?>>Truck</option>
  </select>

			</td>
        </tr>
        <tr>
		
 




            <td>
               <label for="Imobillizer" id="lblImmobilizer">Immobilizer </label></td>
            <td>

			<Input type = 'Radio' Name ='immobilizer' id="TxtImmobilizer" value= 'Yes'  <?if($Immobilizer=="Yes")echo "checked";?>
<?PHP //print $male_status; ?>
>Yes

<Input type = 'Radio' Name ='immobilizer' value= 'No' <?if($Immobilizer=="No")echo "checked";?>
<?PHP //print $female_status; ?>
>No
               </td>
        </tr>
        <tr>
            <td>
               <label for="AC" id="lblACStatus">AC </label></td>
            <td>
<?
 

?>
			<Input type = 'Radio' Name ='ac_on_off'  id="TxtACStatus" value= 'on'  <?if($AC =="on")echo "checked";?>
<?PHP //print $male_status; ?>
>Yes

<Input type = 'Radio' Name ='ac_on_off' id="TxtACStatus"  value= 'off'  <?if($AC =="off")echo "checked";?>
<?PHP //print $female_status; ?>
>No
                 </td>
        </tr>
        <tr>
            <td>
               <label for="Email_Id" id="lblEmailId">Email Id</label></td>
            <td>
                <input type="text" name="email_id" id="TxtEmailId" value="<?echo $E_mail_ID;?>" /></td>
        </tr>
		<tr>
            <td>
               <label for="user_name"  id="lblUserName">User Name</label></td>
            <td>
                <input type="text" name="user_name" id="TxtUserName" value="<?echo $User_Name;?>"/></td>
        </tr>
        <tr>
            <td>
               <label for="user_Password"  id="lblUserPass"> Password</label></td>
            <td>
                <input type="text" name="user_password" id="TxtUserPass" value="<?echo $Password;?>" /></td>
        </tr>
       <tr><td></td>
	   <td class="submit">
           <input id="Button1" type="submit" name="submit" value="submit"   /></td></tr>
    </table>
	</form>
   </div>
 <SCRIPT LANGUAGE="JavaScript">
 <!--
	  <?
if($_GET["rowid"])
{?>
	PaymentProcessBYCash('<?echo $modeofpayment?>');
  
   <?}?>
 //-->
 </SCRIPT>
<?php
include("include/footer.php"); ?>
