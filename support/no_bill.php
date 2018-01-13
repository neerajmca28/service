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
        $result=mysql_fetch_array(mysql_query("select * from no_bills where id=$id"));   
    }
?>

<div class="top-bar">
<h1>No Bill</h1>
</div>
<div class="table">
<?php
 

if(isset($_POST["submit"]))
{
   
     $service_comment = $_POST['service_comment'];
   
    /*$date = $_POST["date"];
    $account_manager = $_POST["account_manager"];
    $main_user_id = $_POST["main_user_id"];
    $company = $_POST["company"];
    $Device_model = $_POST["Device_model"];
    $tot_no_of_vehicles = $_POST["tot_no_of_vehicles"];
    $TxtDeviceIMEI = $_POST["TxtDeviceIMEI"];
    $Devicemobile = $_POST["Devicemobile"];
    $nobill = $_POST["nobill"];
    $Duration = $_POST["Duration"];
    $TxtReason = $_POST["TxtReason"];
   
    $payment_status=$_POST["payment_status"];
    $no_bill_issue=$_POST["no_bill_issue"];
   
    $date_of_install=$_POST["date_of_install"];
    $provision_bill=$_POST["provision_bill"];
    $tot_no_of_vehicles=(isset($_POST["tot_no_of_vehicles"])) ? trim($_POST["tot_no_of_vehicles"]): "";
    $number="";
    $no_of_veh_move=0;
    for($j=0;$j<=$tot_no_of_vehicles;$j++)
    {
        if(isset($_POST[$j]))
            {
            $no_of_veh_move++;
        $numbe1=(isset($_POST[$j])) ? trim($_POST[$j])  : "";
        $number .=$numbe1.",";
            }
    }
       $veh_num=substr($number,0,-1);
   
   
    if($number=="") {
    $veh_num_edit=$result['reg_no'];
    }
    else {
    $veh_num_edit=$veh_num;
    }
   
    if($no_of_veh_move=="") {
    $veh_no_bill=$result['veh_no_bill'];
    }
    else {
    $veh_no_bill=$no_of_veh_move;
    }*/


 if($action=='edit')
    {
   
    $query="update no_bills set service_comment='".date("Y-m-d H:i:s")." - ".$service_comment."' where id=$id";
   
    mysql_query($query);
    echo "<script>document.location.href ='list_no_bill.php'</script>";
    }
 
 }


?>


 
   
   
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>

 function validateForm()
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
    }   }
</script>
 <form name="myForm" action="" onSubmit="return validateForm()" method="post">
 

    <table width="589" cellpadding="5" cellspacing="5" style=" padding-left: 100px;width: 550px;">

         <!--<tr>
            <td>Date</td>
            <td>
                <input type="text" name="date" id="datepicker1" readonly value="<?= date("Y-m-d H:i:s")?>" /></td>
        </tr>

        <tr>
            <td>Account Manager</td>
            <td>
                <input type="text" name="account_manager" id="TxtAccManager" readonly value="<?=$result['acc_manager']?>"/></td>
        </tr>
        <tr>-->
       <td>
                Client User Name</td>
            <td>

<select name="main_user_id" id="TxtMainUserId"  onchange="showUser(this.value,'ajaxdata');gettotal_veh_byuser(this.value,'TxtTotalVehicle');getCompanyName(this.value,'TxtCompany');">
            <option value="" name="main_user_id" id="TxtMainUserId">-- Select One --</option>
            <?php
            $main_user_id=mysql_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE Branch_id=".$_SESSION['BranchId']." order by name asc");
            while ($data=mysql_fetch_assoc($main_user_id))
                    {
            ?>
           
               <option name="main_user_id" value="<?=$data['user_id']?>" <? if($result['client']==$data['user_id']) {?> selected="selected" <? } ?> >
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
        <td><input type="text" name="company" id="TxtCompany" value="<?=$result['company_name']?>" readonly />
        </td>
        </tr>
       
        <tr>
            <td>
                Total No Of Vehicle</td>
            <td><input type="value" name="tot_no_of_vehicles" id="TxtTotalVehicle" value="<?=$result['tot_no_of_vehicles']?>"  readonly/>
                </td>
        </tr>
       
        <tr>
        <td>
        Registration No</td>
        <td><!-- <input type="value" name="reg_no_of_vehicle_to_move" id="TxtRegNoOfVehicle" /> -->
        <div id="ajaxdata">
        <?=$result['reg_no']?>
        </div>
       
        </td>
        </tr>
       
          <tr>
        <td>
        <label for="DtInstallation" id="lblDtInstallation">No Bill For</label></td>
        <td>
        <input type="checkbox" name="nobill" value="Rent" <?php if($result['rent_device']=="Rent"){echo "checked=\"checked\""; }?>/>Rent<br>
<input type="checkbox" name="nobill" value="Device" <?php if($result['rent_device']=="Device"){echo "checked=\"checked\""; }?>/>Device </td>
        </tr>
       
        </table>
  
    <table cellspacing="5" cellpadding="5" style=" padding-left: 100px;width: 500px;" >
           <tr>
        <td width="231">
        <label for="DtInstallation" id="lblDtInstallation">Provision Bill</label></td>
        <td width="232">
         <input type="Radio"  style="width:10px;" name="provision_bill" id="provision_bill" value="Yes"  <?php if($result['provision_bill']=="Yes"){echo "checked=\"checked\""; }?> onChange="Status(this.value)" />Yes
         <input type="Radio"  style="width:10px;" name="provision_bill" id="provision_bill" value="No"  <?php if($result['provision_bill']=="No"){echo "checked=\"checked\""; }?> onChange="Status(this.value)" />No
        </td>
 <?php if($result['provision_bill']=="Yes") { ?>
    <tr id="new1">
        <td>
        Duration for Provision Bill</td><td>
        <select name="Duration" id="Duration" >
  <option value="" name="Duration" id="Duration">-- Select One --</option>
 <option value="1 week" <? if($result['duration']=='1 week') {?> selected="selected" <? } ?> >1 week</option>
  <option value="2 week" <? if($result['duration']=='2 week') {?> selected="selected" <? } ?> >2 week</option>
  <option value="3 week" <? if($result['duration']=='3 week') {?> selected="selected" <? } ?> >3 week</option>
   <option value="4 week" <? if($result['duration']=='4 week') {?> selected="selected" <? } ?> >4 week</option>
  <option value="5 week" <? if($result['duration']=='5 week') {?> selected="selected" <? } ?> >5 week</option>
  <option value="6 week" <? if($result['duration']=='6 week') {?> selected="selected" <? } ?> >6 week</option>
  <option value="7 week" <? if($result['duration']=='7 week') {?> selected="selected" <? } ?> >7 week</option>
  <option value="8 week" <? if($result['duration']=='8 week') {?> selected="selected" <? } ?> >8 week</option>
  <option value="9 week" <? if($result['duration']=='9 week') {?> selected="selected" <? } ?> >9 week</option>
</select>
</td>
</tr>
         <?php } ?>
         <tr id="new" style="display:none">
        <td>
       Duration for Provision Bill</td><td>
        <select name="Duration" id="Duration" >
  <option value="" name="Duration" id="Duration">-- Select One --</option>
 <option value="1 week" <? if($result['duration']=='1 week') {?> selected="selected" <? } ?> >1 week</option>
  <option value="2 week" <? if($result['duration']=='2 week') {?> selected="selected" <? } ?> >2 week</option>
  <option value="3 week" <? if($result['duration']=='3 week') {?> selected="selected" <? } ?> >3 week</option>
   <option value="4 week" <? if($result['duration']=='4 week') {?> selected="selected" <? } ?> >4 week</option>
  <option value="5 week" <? if($result['duration']=='5 week') {?> selected="selected" <? } ?> >5 week</option>
  <option value="6 week" <? if($result['duration']=='6 week') {?> selected="selected" <? } ?> >6 week</option>
  <option value="7 week" <? if($result['duration']=='7 week') {?> selected="selected" <? } ?> >7 week</option>
  <option value="8 week" <? if($result['duration']=='8 week') {?> selected="selected" <? } ?> >8 week</option>
  <option value="9 week" <? if($result['duration']=='9 week') {?> selected="selected" <? } ?> >9 week</option>
</select>
</td>
</tr>
    </tr>
    </table>
    <table cellspacing="5" cellpadding="5" style=" padding-left: 100px;width: 500px;">
        <tr>
        <td>
       Issue for No Bill</td><td>
        <select name="no_bill_issue" id="no_bill_issue" >
  <option value="" name="no_bill_issue" id="no_bill_issue">-- Select One --</option>
 <option value="Software Issue" <? if($result['no_bill_issue']=='Software Issue') {?> selected="selected" <? } ?> >Software Issue</option>
  <option value="Service Issue" <? if($result['no_bill_issue']=='Service Issue') {?> selected="selected" <? } ?> >Service Issue</option>
  <option value="Client Side Issue" <? if($result['no_bill_issue']=='Client Side Issue') {?> selected="selected" <? } ?> >Client Side Issue</option>
  
</select>
</td>
</tr>
              <tr><td> <label  id="lblReason">Reason</label></td>
              <td> <textarea rows="5" cols="25"  type="text" name="TxtReason" id="TxtReason" > <?=$result['reason']?></textarea>
</td>
              </tr>
       
 
         <tr>
            <td class="style2">
                Service Comment</td>
            <td><textarea rows="5" cols="25"  type="text" name="service_comment" id="Txtservice_comment" ><?=$result['service_comment']?></textarea>
                </td>
        </tr> 
           
           
    <tr>
    <td> <input type="submit" name="submit" value="submit"  />
    <input type="button" name="Cancel" value="Cancel" onClick="window.location = 'list_no_bill.php' " /></td>
    </tr>

    </table>
      </form>
 
 
<?php
include("../include/footer.inc.php"); ?>