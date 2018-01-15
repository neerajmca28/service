<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
?>
<link  href="<?php echo __SITE_URL;?>/css/auto_dropdown.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo __SITE_URL;?>/build/jquery.datetimepicker.css"/>
<script src="<?php echo __SITE_URL;?>/build/jquery.datetimepicker.full.js"></script>
<!-- <script src="<?php echo __SITE_URL;?>/js/jquery.min.js"></script> -->
<script type="text/javascript">
 var $s = jQuery.noConflict();
$s(document).ready(function(){
  //$('#toolkitAccessory').attr("disabled", true);   
  
    $s("#hide").click(function(){
        $s("#acn").hide();
    });
    $s("#show").click(function(){
        $s("#acn").show();
    });

    // Designation Hide Show

   


    
});

/* End auto ajax value load code*/
</script>
<?php
$Header="Online Crack";

$date=date("Y-m-d H:i:s");
$account_manager=$_SESSION['username'];

?>

<div class="top-bar">
  <h1><? echo $Header;?></h1>
</div>
<div class="table">
<?


if(isset($_POST['submit']))
{ 
   //echo '<pre>'; print_r($_POST);die;
  
    $date=date("Y-m-d H:i:s");
    $account_manager=$_SESSION['username'];
    $sales_person=trim($_POST['sales_person']);
    $sales_manager = select_query("select id as sales_id from $internalsoftware.sales_person where name='".$sales_person."' limit 1");
    $sales_person_id=$sales_manager[0]['sales_id'];
    $main_user_id=$_POST['main_user_id'];
    $company=$_POST['company'];
    $model=$_POST['model'];
    $model_parent=$_POST['deviceType'];
    $deviceType="Crack Online";
    $no_of_vehicals=$_POST['no_of_vehicals'];
    
    // for($i=0;$i<=$no_of_vehicals;$i++)
    // {
    //   $veh_reg_string1.=$_POST['veh_reg_no'][$i].",";

    // }
    
    // for($i=0;$i<=$no_of_vehicals;$i++)
    // {
    //   $imei_string1.=$_POST['imei_no'][$i].",";

    // }
    //   $veh_reg_string=substr($veh_reg_string1,0,strlen($veh_reg_string1)-2);
    //   $imei_string=substr($imei_string1,0,strlen($imei_string1)-2);

     for($i=1;$i<=$no_of_vehicals;$i++)
    {
      $veh_reg_string1.=$_POST['reg_no'.$i].",";

    }
    
    for($i=1;$i<=$no_of_vehicals;$i++)
    {
      $imei_string1.=$_POST['device_imei'.$i].",";

    }
      $veh_reg_string=substr($veh_reg_string1,0,strlen($veh_reg_string1)-1);
      $imei_string=substr($imei_string1,0,strlen($imei_string1)-1);
  
    $designation=$_POST['designation'];
    $alt_designation=$_POST['designation2'];
    $contact_person=$_POST['contact_person'];
    $alt_cont_person=$_POST['contact_person2'];
    $contact_number=$_POST['contact_number'];
    $alt_cont_number=$_POST['contact_number2'];
    $atime_status=$_POST['atime_status'];
    $back_reason=$_POST['back_reason'];
    $instal_reinstall = 'online_crack';
    // $accessories_tollkit="";
    // for($i=0;$i<count($_POST['toolkit']);$i++)
    // {
    //   $accessories_tollkit.=$_POST['toolkit'][$i]."#";
    //   $accessories_tollkits=substr($accessories_tollkit,0,strlen($accessories_tollkit)-1);
    // }
   
    $veh_type=$_POST['veh_type'];
    $del_nodelux=$_POST['standard'];
    $actype=$_POST['actype'];
    $TruckType=$_POST['TruckType'];
    $TrailerType=$_POST['TrailerType'];
    $MachineType=$_POST['MachineType'];
    $billing='Yes';
    $luxury = $_POST['lux'];
    $Zone_data = select_query("SELECT id,`name` FROM $internalsoftware.re_city_spr_1 WHERE `name`='".$_POST['Zone_area']."'");
    $zone_count = count($Zone_data);
    if($zone_count > 0)
    {
        $Area = $Zone_data[0]["id"];
        $errorMsg = "";
    }
    else
    {
        $errorMsg = "Please Select Type View Listed Area. Not Fill Your Self.";
    }
    
    $branch_type = $_POST['inter_branch'];
    if($branch_type == "Interbranch"){
        $city=$_POST['inter_branch_loc'];
    }else{
        $city=0;
    }

    $installation_status=1;
    $location=$_POST['location'];
    $location1=$_POST['inter_branch'];
    $interbranch = $_POST['inter_branch_loc'];

    if($location1 == 'Interbranch'){
      $query = select_query("select city from $internalsoftware.tbl_city_name where branch_id='".$interbranch."'");
      $branchLocation = $query[0]['city'];
    }
    else{
      $branchLocation = "Delhi";
    }

    $required=$_POST['required'];
        if($required=="") { $required="normal"; }
       
        $datapullingtime=$_POST['datapullingtime'];
    if($errorMsg=="")   
    { 
      if($atime_status=="Till")
      {
      
               $time=date('Y-m-d H:i:s',strtotime($_POST['time']));  
               //echo $time; die;
              
              $sql="INSERT INTO $internalsoftware.installation_request(`req_date`, `request_by`,sales_person,`user_id`, `company_name`, no_of_vehicals, 
              location,model,time, contact_number, status, contact_person, veh_type,required,branch_id,installation_status, Zone_area,atime_status,`inter_branch`, branch_type, instal_reinstall,designation,device_type,alter_contact_no,billing,TrailerType,TruckType,MachineType,actype,standard,alt_cont_person,alt_designation,landmark,approve_status,installation_approve,veh_reg,device_imei,model_parent) VALUES('".$date."','".$account_manager."','".$sales_person_id."', '".$main_user_id."', 
              '".$company."','".$no_of_vehicals."','".$branchLocation."','".$model."','".$time."','".$contact_number."',1,'".$contact_person."','".$veh_type."','".$required."',
              '".$_SESSION['BranchId']."','".$installation_status."','".$Area."','".$atime_status."','".$city."','".$branch_type."',
              '".$instal_reinstall."','".$designation."','".$deviceType."','".$alt_cont_number."','".$billing."','".$TrailerType."','".$TruckType."','".$MachineType."','".$actype."','".$del_nodelux."','".$alt_cont_person."','".$alt_designation."','".$location."','1','".$no_of_vehicals."','".$veh_reg_string."','".$imei_string."','".$model_parent."')";
             // echo $sql; die;
                 //$model_parent
      
               $execute=mysql_query($sql);
             //   $insert_id = mysql_insert_id();   
             //  if($installation_status == 1)
             //  {
             //    for($N=1;$N<=$no_of_vehicals;$N++)
             //    { 
             //        $installation = "INSERT INTO installation(`inst_req_id`, `req_date`, `request_by`,sales_person,`user_id`, `company_name`, 
             //        no_of_vehicals, location,model,time, contact_number,installed_date, status, contact_person, veh_type, required,branch_id,installation_status, Zone_area,atime_status,`inter_branch`, 
             //        branch_type, instal_reinstall,designation,device_type,alter_contact_no,billing,TrailerType,TruckType,MachineType,actype,standard,alt_cont_person,alt_designation,landmark,approve_status,installation_approve,accessories_tollkit,veh_reg,device_imei) VALUES('".$insert_id."','".$date."',
             //        '".$account_manager."','".$sales_person_id."', '".$main_user_id."', '".$company."','1','".$branchLocation."','".$model."','".$time."',
             //        '".$contact_number."',now(),1,'".$contact_person."','".$veh_type."','".$required."','".$_SESSION['BranchId']."','".$installation_status."','".$Area."',
             //        '".$atime_status."','".$city."','".$branch_type."','".$instal_reinstall."','".$designation."','".$deviceType."','".$alt_cont_number."','".$billing."','".$TrailerType."','".$TruckType."','".$MachineType."','".$actype."','".$del_nodelux."','".$alt_cont_person."','".$alt_designation."','".$location."','1','".$no_of_vehicals."','".$accessories_tollkits."')";

             //         //echo $sql; die;
                   
             //        $execute_inst=mysql_query($installation);
             //    }
             // }
           
             header("location:installation.php");
        }
           
        if($atime_status=="Between")
        {
            //$time=$_POST['time1'];
           // $totime=$_POST['totime'];
              $time=date('Y-m-d H:i:s',strtotime($_POST['time1']));  
                $totime=date('Y-m-d H:i:s',strtotime($_POST['totime']));  
           
            //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
             $sql="INSERT INTO $internalsoftware.installation_request(`req_date`, `request_by`,sales_person,`user_id`, `company_name`, no_of_vehicals,location,model, 
             time,totime,contact_number, installed_date, status, contact_person, veh_type, 
             required,branch_id, installation_status,Zone_area, atime_status,`inter_branch`, branch_type, instal_reinstall,designation,device_type,alter_contact_no,billing,TrailerType,TruckType,MachineType,actype,standard,alt_cont_person,alt_designation,landmark,approve_status,installation_approve,veh_reg,device_imei,model_parent) VALUES('".$date."','".$account_manager."','".$sales_person_id."', '".$main_user_id."', '".$company."',
             '".$no_of_vehicals."','".$branchLocation."','".$model."','".$time."','".$totime."','".$contact_number."',now(),1,'".$contact_person."','".$veh_type."','".$required."',
             '".$_SESSION['BranchId']."','".$installation_status."','".$Area."','".$atime_status."','".$city."','".$branch_type."',
             '".$instal_reinstall."','".$designation."','".$deviceType."','".$alt_cont_number."','".$billing."','".$TrailerType."','".$TruckType."','".$MachineType."','".$actype."','".$del_nodelux."','".$alt_cont_person."','".$alt_designation."','".$location."','1','".$no_of_vehicals."','".$veh_reg_string."','".$imei_string."','".$model_parent."')";
            // echo $sql; die;
             
                 
                   $execute=mysql_query($sql);
                //    $insert_id = mysql_insert_id();   

                //   if($installation_status == 1)
                //   {

                //       for($N=1;$N<=$no_of_vehicals;$N++)
                //       {
                //           $installation = "INSERT INTO installation(`inst_req_id`, `req_date`, `request_by`,sales_person,`user_id`, `company_name`, 
                //           no_of_vehicals, location,model,time, totime,contact_number,installed_date, status, contact_person, veh_type,required,branch_id,installation_status, Zone_area,atime_status,`inter_branch`,
                //            branch_type, instal_reinstall,designation,device_type,alter_contact_no,billing,TrailerType,TruckType,MachineType,actype,standard,alt_cont_person,alt_designation,landmark,approve_status,installation_approve,accessories_tollkit) VALUES('".$insert_id."','".$date."',
                //            '".$account_manager."','".$sales_person_id."', '".$main_user_id."', '".$company."','1','".$branchLocation."','".$model."','".$time."',
                //            '".$totime."','".$contact_number."',now(),1,'".$contact_person."','".$veh_type."'  ,
                //            ".$required."','".$_SESSION['BranchId']."',
                //            '".$installation_status."','".$Area."','".$atime_status."','".$city."','".$branch_type."','".$instal_reinstall."','".$designation."','".$deviceType."','".$alt_cont_number."','".$billing."','".$TrailerType."','".$TruckType."','".$MachineType."','".$actype."','".$del_nodelux."','".$alt_cont_person."','".$alt_designation."','".$location."','1','".$no_of_vehicals."','".$accessories_tollkits."'
                //            )";
                   
                //           $execute_inst=mysql_query($installation);
                //       }  
                //      // echo $installation; die;   
                // }
             header("location:installation.php");
        }
      }
}
?>

<script type="text/javascript">
var mode;
function req_info()
{
  
  // var instal_reinstall=document.forms["form1"]["instal_reinstall"].value;
  // if (instal_reinstall==null || instal_reinstall=="")
  // {
  // alert("Please Select Job") ;
  // return false;
  // }

  if(document.form1.main_user_id.value=="")
  {
  alert("Please Select Client Name") ;
  document.form1.main_user_id.focus();
  return false;
  }
  
  if(document.form1.no_of_vehicals.value=="")
  {
  alert("Please Select No Of Installation") ;
  document.form1.no_of_vehicals.focus();
  return false;
  }

  if(document.form1.sales_person.value=="")
  {
  alert("Please Select Sales Person Name") ;
  document.form1.sales_person.focus();
  return false;
  }
 
  if(document.form1.deviceType.value=="")
  {
        alert("Please Enter Model") ;
        document.form1.deviceType.focus();
        return false;
  }
  if(document.form1.model.value=="")
  {
        alert("Please Enter Model") ;
        document.form1.model.focus();
        return false;
  }
  

  var inter_branch=document.forms["form1"]["inter_branch"].value;
  if (inter_branch==null || inter_branch=="")
  {
  alert("Please Select Branch Button") ;
  return false;
  }

 if(document.form1.Zone_area.value=="")
  {
  alert("Please Select Area") ;
  document.form1.Zone_area.focus();
  return false;
  }
 
    var barnch=document.forms["form1"]["inter_branch"].value;
    if (barnch==null || barnch=="")
    {
        alert("Please Select Branch") ;
        return false;
    }
  
    var location=document.forms["form1"]["location"].value;
    if (location==null || location=="")
    {
        alert("Please Enter location");
        document.form1.location.focus();
        return false;
    }
    var interbranch=document.forms["form1"]["inter_branch_loc"].value;
    if ((interbranch==null || interbranch=="") && barnch=="Interbranch")
    {
        alert("Please select branch location");
        document.form1.inter_branch_loc.focus();
        return false;
    }
                
     
    var timestatus=document.forms["form1"]["atime_status"].value;
    if (timestatus==null || timestatus=="")
      {
          alert("Please select Availbale Time");
          document.form1.atime_status.focus();
          return false;
      }
 
    var tilltime=document.forms["form1"]["datetimepicker"].value;  
      if(timestatus == "Till" && (tilltime==null || tilltime==""))  
      {     
           alert("Please select Time");    
            document.form1.datetimepicker.focus();  
            return false;   
      } 
      else
      {
        var inputTime = new Date(tilltime).getTime();  
        var time=(inputTime/(3600*1000));    
        var d = new Date(); 
        var n = d.getTime();       
        var currntTime4=(n/(3600*1000));     
        var diff=time-currntTime4;   
        if(timestatus=="Till")
        {       
          if(diff<=3.80)   
          {  
              alert('Please enter 4 hour difference for available time');  
              document.form1.datetimepicker.focus();   
              return false;    
          }
        }

      }  
  
    var betweentime=document.forms["form1"]["datetimepicker1"].value;
    var betweentime2=document.forms["form1"]["datetimepicker2"].value;
    if(timestatus == "Between" && (betweentime==null || betweentime==""))
    {
        alert("Please select From Time");
        document.form1.datetimepicker1.focus();
        return false;
    }
  
    if(timestatus == "Between" && (betweentime2==null || betweentime2==""))
    {
        alert("Please select To Time");
        document.form1.datetimepicker2.focus();
        return false;
    }
    else
    {
        var inputTime = new Date(betweentime).getTime();  
        var time=(inputTime/(3600*1000));    
        var d = new Date(); 
        var n = d.getTime();       
        var currntTime4=(n/(3600*1000));     
        var diff=time-currntTime4;  
        //alert(diff); 
        if(timestatus=="Between")
        {  
          //alert('tt');     
          if(diff<=3.80)   
          {  
              alert('Please enter 4 hour difference for available time');  
              document.form1.datetimepicker1.focus();   
              return false;    
          }
        }
    } 
 

  if(document.form1.designation.value=="")
  {
  alert("Please Select Designation") ;
  document.form1.designation.focus();
  return false;
  }

  
  var contactPerson=document.forms["form1"]["contact_person"].value; 
  
  if(contactPerson == "" || contactPerson == null){
    alert("Please Select Contact Person") ;
    document.form1.contact_person.focus();
    return false;
  }
  else if (/[\d]/.test(contactPerson)) {
    alert("Contact Person should be Characters");
    document.form1.contact_person.focus();
    return false;
  }

  var contactNumber=document.forms["form1"]["contact_number"].value;

  if(contactNumber == "" || contactNumber == null){
    alert("Please Select Contact Number") ;
    document.form1.contact_number.focus();
    return false;
  }
  else if (!(/[\d]/.test(contactNumber))) {
    alert("Contact Number should be Numeric");
    document.form1.contact_number.focus();
    return false;
  } 
  
  
  if(document.getElementById("dataDesignation").style.display=='none' )
  {

  }
  else
  {
    if(document.form1.designation2.value=="")
    {
      alert("Please Select Alternate Designation") ;
      document.form1.designation2.focus();
      return false;
    }
    
    var contactPerson2=document.forms["form1"]["contact_person2"].value; 
  
    if(contactPerson2 == "" || contactPerson2 == null){
      alert("Please Select Alternate Contact Person") ;
      document.form1.contact_person2.focus();
      return false;
    }
    else if (/[\d]/.test(contactPerson2)) {
      alert("Contact Person should be Characters");
      document.form1.contact_person2.focus();
      return false;
    }

    var contactNumber=document.forms["form1"]["contact_number2"].value;

    if(contactNumber == "" || contactNumber == null){
      alert("Please Select Alternate Contact Number") ;
      document.form1.contact_number2.focus();
      return false;
    }
    else if (!(/[\d]/.test(contactNumber))) {
      alert("Contact Number should be Numeric");
      document.form1.contact_number2.focus();
      return false;
    }
  }


  if(document.form1.veh_type.value=="")
  {
  alert("Please Select Vehicle Type") ;
  document.form1.veh_type.focus();
  return false;
  }

  var vehType=document.forms["form1"]["veh_type"].value;
  if(vehType=="Car")
  {

    if(document.form1.lux.value=="")
    {
    alert("Please Select Luxury Type") ;
    document.form1.lux.focus();
    return false;
    }

    var luxaryType=document.forms["form1"]["lux"].value;

    if(luxaryType=="luxury" || luxaryType=="NonLuxury")
    {

      if(document.form1.actype.value=="")
      {
        alert("Please Select AC Type") ;
        document.form1.actype.focus();
        return false;
      }
    }

  }

  if(vehType=="Bus")
  {

    if(document.form1.standard.value=="")
    {
    alert("Please Select Delux Type") ;
    document.form1.standard.focus();
    return false;
    }

    var deluxType=document.forms["form1"]["standard"].value;

    if(deluxType=="Delux" || deluxType=="NonDelux")
    {

      if(document.form1.actype.value=="")
      {
        alert("Please Select AC Type") ;
        document.form1.actype.focus();
        return false;
      }

    }

  }

  if(vehType=="Truck")
  {

    if(document.form1.TruckType.value=="")
    {
    alert("Please Select Truck Category") ;
    document.form1.TruckType.focus();
    return false;
    }

  }
  
  if(vehType=="Trailer")
  {

    if(document.form1.TrailerType.value=="")
    {
    alert("Please Select Trailer Type") ;
    document.form1.TrailerType.focus();
    return false;
    }

  }
  
  if(vehType=="Machine")
  {

    if(document.form1.MachineType.value=="")
    {
    alert("Please Select Machine Type") ;
    document.form1.MachineType.focus();
    return false;
    }

  }
  if(vehType=="Tempo")
  {

    if(document.form1.actype.value=="")
    {
      alert("Please Select Tempo Type") ;
      document.form1.actype.focus();
      return false;
    }

  } 
  
}
   
function setVisibility(id, visibility)
{
    document.getElementById(id).style.display = visibility;
}

function TillBetweenTime(radioValue)
{
 if(radioValue=="Till")
    {
    document.getElementById('TillTime').style.display = "block";
    document.getElementById('BetweenTime').style.display = "none";
    }
    else if(radioValue=="Between")
    {
    document.getElementById('TillTime').style.display = "none";
    document.getElementById('BetweenTime').style.display = "block";
    }
    else
    {
    document.getElementById('TillTime').style.display = "none";
    document.getElementById('BetweenTime').style.display = "none";
    }
   
}

function TillBetweenTime12(radioValue)
{
 if(radioValue=="Till")
    {
    document.getElementById('TillTime').style.display = "block";
    document.getElementById('BetweenTime').style.display = "none";
    }
    else if(radioValue=="Between")
    {
    document.getElementById('TillTime').style.display = "none";
    document.getElementById('BetweenTime').style.display = "block";
    }
    else
    {
    document.getElementById('TillTime').style.display = "none";
    document.getElementById('BetweenTime').style.display = "none";
    }
   
}

function StatusBranch(radioValue)
{
  //alert(radioValue)
   if(radioValue=="Interbranch")
    {
        document.getElementById('branchlocation').style.display = "block";
    }
    else if(radioValue=="Samebranch")
    {
        document.getElementById('branchlocation').style.display = "none";
    }
    else
    {
        document.getElementById('branchlocation').style.display = "none";
    }
   
} 

function StatusBranch12(radioValue)
{
  //alert(radioValue)
   if(radioValue=="Interbranch")
    {
        document.getElementById('branchlocation1').style.display = "block";
    }
    else if(radioValue=="Samebranch")
    {
        document.getElementById('branchlocation').style.display = "none";
    }
    else
    {
        document.getElementById('branchlocation').style.display = "none";
        //document.getElementById('samebranchid').style.display = "none";
    }
   
}  

</script> 
  

<?php echo "<p align='left' style='padding-left: 250px;width: 500px;' class='message'>" .$errorMsg. "</p>" ; ?>
  
<style type="text/css" >
.errorMsg{border:1px solid red; }
.message{color: red; font-weight:bold; }
</style>
<style>
body { font-family:'Open Sans' Arial, Helvetica, sans-serif}
ul,li { margin:0; padding:0; list-style:none;}
.label { color:#000; font-size:16px;}
td{
  white-space: nowrap;
  /*border:1px solid #000;*/
}
</style>

 <form method="post" action="" name="form1"   autocomplete="off" onSubmit="return req_info(); ">
    <table style="padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">
      <tr>
        <td colspan="2"><input type="hidden" name="back_reason" id="back_reason" value="<?php echo $result['back_reason']?>"/></td>
      </tr>
      <tr>
        <td align="right"> Client User Name:* </td>
        <td nowrap>
          <select style="width:150px;" name="main_user_id" id="main_user_id"  onchange="getCompanyName(this.value,'TxtCompany');getSalesPersonName(this.value,'TxtSalesPersonName'); getdevicetype(this.value,'deviceType');gettoolkits(this.value);" />
            <option value="" >-- Select One --</option>
            <?php

              $main_user_iddata=select_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE sys_active=1 ORDER BY `name` asc");
              
              for($u=0;$u<count($main_user_iddata);$u++)
              {
                if($main_user_iddata[$u]['user_id']==$result['user_id'])
                {
                  $selected="selected";
                }
                else
                {
                  $selected="";
                }
            ?>
            <option value ="<?php echo $main_user_iddata[$u]['user_id'] ?>"  <?echo $selected;?>> <?php echo $main_user_iddata[$u]['name']; ?> </option>
            <?php
              }
            ?>
          </select>
        </td>
      </tr>       
      <tr>
        <td  align="right">Urgent:</td>
        <td> <input type="checkbox" name="required" id="required" value="urgent" <?php if($result['required']=='urgent') {?> checked="checked" <? }?> />
        </td>
      </tr>
      <tr>
        <td  align="right">Sales Person:*</td>
        <td><input type="text" name="sales_person" id="TxtSalesPersonName" readonly /></td>
      </tr>
      <tr>
        <td  align="right">Company Name:</td>
        <td><input type="text" name="company" id="TxtCompany" readonly value="<?=$result['company_name']?>"/></td>
      </tr>
      <tr>
        <td height="32" align="right">No. Of Installation:*</td>
        <td><select name="no_of_vehicals" id="no_of_vehicals" style="width:150px" onchange="deviceRecords(this.value)">
            <option value="">Select Installation</option>
            <?             
      for($inlp=1;$inlp<=50;$inlp++){
             ?>
            <option value="<?=$inlp;?>" <? if($result['no_of_vehicals']==$inlp) {?> selected="selected" <? } ?> >
            <?=$inlp?>
            </option>
            <? } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <table>
            <tr>
              <td>
                 <tbody id="tt"></tbody>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr id="deviceTyp">
        <td height="32" align="right">Device Type:*</td>
        <td>
          <select name="deviceType" value="" id="deviceType" style="width:150px" onchange="getModelName(this.value,'modelName');">
          
          </select>
        </td>
      </tr>
      <tr id="deviceMdl">
        <td height="32" align="right">Model:*</td>
        <td>
          <select name="model" id="modelName" style="width:150px">

          </select>
        </td>
      </tr>
  <!--     <tr id="toolkitAccessory_td" style="display:none">
        <td>Accessory Selected:<font color="#000">* </font></td>
        <td id="toolkitAccessory"></td>
      </tr> -->
      <tr>
        <td  align="right">Branch:* </td>
        <td><?php $branch_data = select_query("select * from tbl_city_name where branch_id='".$_SESSION['BranchId']."'"); ?>
          <input type='radio' Name ='inter_branch' id='inter_branch' value= 'Samebranch' onchange="StatusBranch(this.value);" checked="checked">
          <?php echo $branch_data[0]["city"];?>
          <Input type='radio' Name ='inter_branch' id='inter_branch' value= 'Interbranch' 
        onchange="StatusBranch(this.value);">
          Inter Branch 
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <table  id="branchlocation"  align="right"  style="width: 100%;display:none;margin-right:-2%;" cellspacing="5" cellpadding="5">
            <tr>
              <td align="right" style="width: 18%;margin-right:-1px;">Branch Name:*</td>
              <td><select name="inter_branch_loc" id="inter_branch_loc" style="width:150px;">
                  <option value="" >-- Select One --</option>
                  <?php
                      $city1=select_query("select * from tbl_city_name where branch_id!='".$_SESSION['BranchId']."'");
                      //while($data=mysql_fetch_assoc($city1))
                      for($i=0;$i<count($city1);$i++)
                      {
                          if($city1[$i]['branch_id']==$result['inter_branch'])
                          {
                              $selected="selected";
                          }
                          else
                          {
                              $selected="";
                          }
                      ?>
                      <option value ="<?php echo $city1[$i]['branch_id'] ?>"  <?echo $selected;?>> <?php echo $city1[$i]['city']; ?> </option>
                      <?php
                      }
                      ?>
                </select>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td  align="right"> Location:*</td>
        <td><input type="text" name="Zone_area" id="Zone_area" value="<?php echo $area["name"];?>" />
          <div id="ajax_response"></div>
        </td>
      </tr>
      <tr>
        <td align="right"> Area:*</td>
        <td><input type="text" name="location"  id="location" value="<?=$result['location']?>" minlength="15"/></td>
      </tr> 
      <tr>
        <td align="right">Available Time status:*</td>
        <td><select name="atime_status" id="atime_status" style="width:150px" onchange="TillBetweenTime(this.value)">
            <option value="">Select Status</option>
            <option value="Till" <? if($result['atime_status']=='Till') {?> selected="selected" <? } ?> >Till</option>
            <option value="Between" <? if($result['atime_status']=='Between') {?> selected="selected" <? } ?> >Between</option>
          </select>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <table  id="TillTime" align="left" style="width:100%;display:none;margin-left:10.5%;"  cellspacing="5" cellpadding="5">
            <tr>
              <td height="32" align="right">Time:<font color="red">* </font></td>
              <td><input type="text" name="time" id="datetimepicker" value="<?=$result['time']?>" style="width:147px"/></td>
            </tr>
          </table>
        
          <table  id="BetweenTime" align="left" style="width:100%;display:none;margin-left:6.5%;"  cellspacing="5" cellpadding="5">
            <tr>
              <td height="32" align="right">From Time:<font color="red">* </font></td>
              <td><input type="text" name="time1" id="datetimepicker1" value="<?=$result['time']?>" style="width:147px"/></td>
            </tr>
            <tr>
              <td height="32" align="right">To Time:<font color="red">* </font></td>
              <td><input type="text" name="totime" id="datetimepicker2" value="<?=$result['totime']?>" style="width:147px"/></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td align="right" valign="top">Contact Details</td>
        <td style="margin-left:20px;">
          <table cellspacing="0" cellpadding="0">
            <tr>
              <td>
                  <INPUT type="button" value="+" id='addRowss' />
              </td>
              <td>
                  <INPUT type="button" value="-" id='delRowss' />
              </td>
            </tr>
          </table>
          <table id="dataTable"  cellspacing="" cellpadding="">
           <tr>
              <td  height="32" align="right">
                <select name="designation" id="designation" style="margin-left:-4px"
              onchange="designationChange(this.value)">
                  <option value="" disabled selected>-- Select Designation --</option>
                  <option value="driver" >Driver</option>
                  <option value="supervisor" >Supervisor</option>
                  <option value="manager" >Manager</option>
                  <option value="senior manager" >Senior Manager</option>
                   <option value="owner">Owner</option>
                   <option value="sale person">Sale Person</option>
                   <option value="others">Others</option>
                
                </select>
              </td>
              <td>
                <input type="text" name="contact_person" placeholder="Contact Person"  id="contact_person" value="<?=$result['contact_person']?>" style="width:147px"/>
              </td>
              <td>
                <input type="text" name="contact_number" placeholder="Contact Number" id="contact_number"  minlength="10" maxlength="10" value="<?=$result['contact_number']?>"  onkeypress='return event.charCode >= 48 && event.charCode <= 57'  style="width:147px"/>
              </td>       
           </tr>
           <tr id="dataDesignation" style="display:none">
              <td  height="32" align="right">
                <select name="designation2" id="designation2" style="margin-left:-4px"
              onchange="designationChange(this.value)">
                  <option value="" disabled selected>-- Select Designation --</option>
                  <option value="driver" >Driver</option>
                  <option value="supervisor" >Supervisor</option>
                  <option value="manager" >Manager</option>
                  <option value="senior manager" >Senior Manager</option>
                   <option value="owner">Owner</option>
                   <option value="sale person">Sale Person</option>
                   <option value="others">Others</option>
                
                </select>
              </td>
              <td>
                <input type="text" name="contact_person2" placeholder="Contact Person"  id="contact_person2" value="<?=$result['contact_person']?>" style="width:147px"/>
              </td>
              <td>
                <input type="text" name="contact_number2" placeholder="Contact Number" id="contact_number2"  minlength="10" maxlength="10" value="<?=$result['contact_number']?>"  onkeypress='return event.charCode >= 48 && event.charCode <= 57'  style="width:147px"/>
              </td>       
           </tr>
          </table>
        </td>
      </tr>    
      <tr>
        <td height="32" align="right">Vehicle Type:<font color="red">*</font></td>
        <td>
          <table align="left" cellspacing="5" cellpadding="5">
            <tr>
              <td>
                <select name="veh_type" id="veh_type" style="width:150px;margin-left:-12px" onchange="vehicleType(this.value,'standard');" >
                  <option value=""  disabled selected>-- Select Vehicle Type --</option>
                  <option value="Car" <?php if($result[0]['veh_type']=='Car') {?> selected="selected" <?php } ?>>Car</option>
                  <option value="Bus" <?php if($result[0]['veh_type']=='Bus') {?> selected="selected" <?php } ?>>Bus</option>
                  <option value="Truck" <?php if($result[0]['veh_type']=='Truck') {?> selected="selected" <?php } ?>>Truck</option>
                  <option value="Bike" <?php if($result[0]['veh_type']=='Bike') {?> selected="selected" <?php } ?>>Bike</option>
                  <option value="Trailer" <?php if($result[0]['veh_type']=='Trailer') {?> selected="selected" <?php } ?>>Trailer</option>
                  <option value="Tempo" <?php if($result[0]['veh_type']=='Tempo') {?> selected="selected" <?php } ?>>Tempo</option>
                  <option value="Machine" <?php if($result[0]['veh_type']=='Machine') {?> selected="selected" <?php } ?>>Machine</option>
                </select>
              </td>
              <td>
                <select name="lux" id="lux" style="width:150px;display:none" onchange="aclux(this.value);" >
                  <option value="" disabled selected>Select Luxury Category</option>
                  <option value="luxury" <?php if($result[0]['luxury']=='luxury') {?> selected="selected" <?php } ?> >Lurxury</option>
                  <option value="NonLuxury" <?php if($result[0]['luxury']=='NonLuxury') {?> selected="selected" <?php } ?>>Non-Luxury</option>
                </select>
              </td>
              <td>
                <select name="actype" id="actype" style="width:150px;display:none" onchange="checkbox_lease();" >
                  <option value="" disabled  selected>Select AC Category</option>
                  <option value="AC" <?php if($result[0]['actype']=='AC') {?> selected="selected" <?php } ?>>AC</option>
                  <option value="NonAC" <?php if($result[0]['actype']=='NonAC') {?> selected="selected" <?php } ?>>Non-AC</option>
                </select>
              </td>
              <td>
                <select name="standard" id="standard" palceholder="Vehicle Type" style="width:150px;display:none" onchange="standardType(this.value,'actype');" >
                  <option value="" disabled selected>Select Delux category</option>
                  <option value="Delux" <?php if($result[0]['standard']=='Delux') {?> selected="selected" <?php } ?> >Delux</option>
                  <option value="NonDelux" <?php if($result[0]['standard']=='NonDelux') {?> selected="selected" <?php } ?>>NonDelux</option>
                </select>
              </td>
              
            <td>
                <select name="TrailerType" id="TrailerType" palceholder="Vehicle Type" style="width:150px;display:none" >
                  <option value="" disabled selected>Select your category</option>
                  <option value="Genset  AC Trailer" <?php if($result[0]['TrailerType']=='Genset  AC Trailer') {?> selected="selected" <?php } ?>>Genset  AC Trailer</option>
                  <option value="Refrigerated Trailer" <?php if($result[0]['TrailerType']=='Refrigerated Trailer') {?> selected="selected" <?php } ?>>Refrigerated Trailer</option>
                </select>
              </td>
              <td>
                <select name="MachineType" id="MachineType" palceholder="Vehicle Type" style="width:150px;display:none" >
                  <option value="" disabled selected>Select Machine category</option>
                  <option value="Vermeer Series-2" <?php if($result[0]['MachineType']=='Vermeer Series-2') {?> selected="selected" <?php } ?>>Vermeer Series-2</option>
                  <option value="Ditch Witch" <?php if($result[0]['MachineType']=='Ditch Witch') {?> selected="selected" <?php } ?>>Ditch Witch</option>
                  <option value="Halyma" <?php if($result[0]['MachineType']=='Halyma') {?> selected="selected" <?php } ?>>Halyma</option>
                  <option value="Drillto" <?php if($result[0]['MachineType']=='Drillto') {?> selected="selected" <?php } ?>>Drillto</option>
                  <option value="LCV" <?php if($result[0]['MachineType']=='LCV') {?> selected="selected" <?php } ?>>LCV</option>
                  <option value="Oil Filtering Machine" <?php if($result[0]['MachineType']=='Oil Filtering Machine') {?> selected="selected" <?php } ?>>Oil Filtering Machine</option>
                  <option value="JCB" <?php if($result[0]['veh_type']=='JCB') {?> selected="selected" <?php } ?>>JCB</option>
                  <option value="Sudhir Generator" <?php if($result[0]['MachineType']=='Sudhir Generator') {?> selected="selected" <?php } ?>>Sudhir Generator</option>
                  <option value="Container Loading Machine (Kony)" <?php if($result[0]['MachineType']=='Container Loading Machine (Kony)') {?> selected="selected" <?php } ?>>Container Loading Machine (Kony)</option>
                </select>
              </td>
              <td>
                <select name="TruckType" id="TruckType" palceholder="Vehicle Type" style="width:150px;display:none" >
                  <option value="" disabled selected>Select Truck Category</option>
                  <option value="Refrigerated Truck" <?php if($result[0]['TruckType']=='Refrigerated Truck') {?> selected="selected" <?php } ?>>Refrigerated Truck</option>
                  <option value="Pickup Van" <?php if($result[0]['TruckType']=='Pickup Van') {?> selected="selected" <?php } ?>>Pickup Van</option>
                </select>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td height="32" align="right"><input type="submit" name="submit" id="button1" value="submit" align="right" /></td>
        <td><input type="button" name="Cancel" value="Cancel" onClick="window.location = 'installation.php' " /></td>
      </tr>
    </table>
  </form>
</div>

<?
include("../include/footer.php");

?>
<script type="text/javascript">
var $t = jQuery.noConflict();
// function deviceRecords(total)
//   {
//     //alert(total);
//     if(total > 0)
//       {
//           $("#status").show();
//           var d_status = $("#deviecestatus").val();
//           var user_id = $("#main_user_id").val();
//            //alert(d_status)
//           $.ajax({
//             type:"GET",
//             url:"userInfo.php?action=getAllImei",
//             data:"userId="+user_id+"&dStatus="+d_status,
//             success:function(msg){
//              //alert(msg);
//              var deviceImei = JSON.parse(msg)
//              var imeiNumber = [];
//              //alert(""+deviceImei.length)
//              for(var i=0;i<deviceImei.length;i++){
//               //alert(deviceImei.length)
//               //alert(deviceImei[i].imei)

//               imeiNumber.push(deviceImei[i].device_imei)
                    
//              }
             
//              var num = String(imeiNumber).split(',')
//              var option = "<option value=''>Select Imei No</option>"
//              for(var a = 0; a < num.length; a++) {
              
//               option += "<option value='"+num[a]+"'>"+num[a]+"</option>"
              
//              }
//               for(var i =1; i <= total; i++){

//                 var age1 = `<tr><td><td><input type='text' style="width:145px" placeholder="Vehicle Reg No." name='veh_reg_no[]' id='veh_reg_no${i}'></td><td><input type='text' style="width:145px" name='imei_no[]' placeholder="Dummy IMEI No." id='imei_no${i}'></td></tr>`;
//                 $("#textA").append(age1);

//               }
//             }    
//           });
//         } 
// }

function deviceRecords(total)
  {
    //alert('tt');

           for(var i =1; i <= total; i++)
           {
                //alert(total);
                var age1 = `<tr><td>Vehicle Reg. No.:<font color="red">*</font></td><td><input type='text' style="width:145px"  name="reg_no${i}" id="reg_no${i}" ></td><td>Imei No.:<font color="red">*</font></td><td><input type='text' name='device_imei${i}' id="device_imei${i}" style="width:150px"></td></tr>`;
                $t("#tt").append(age1);

              }

  }
  function gettoolkits(user_id)
  {
  //alert(user_id);
  //return false;
$t.ajax({
    type:"GET",
    url:"userInfo.php?action=toolsAccessories",
    //data:"category="+category+"&expairy="+expairy1+"&duration="+durationlist+"&sortby="+sortby+"&price="+price+"&search="+search1,
    data:"user_id="+user_id,
     beforeSend: function(msg){
        $t("#button1").prop('disabled', true);
      },
    success:function(msg){
      //alert(msg);
          $t("#toolkitAccessory_td").show();
      var data = JSON.parse(msg);
      var dataLength=data.length;
     // alert(data);
      var tblBodyData='';

      if(data)
      {
          for (var i = 0; i < dataLength;  i++){
              tblBodyData += '<input type="checkbox" name="toolkit[]" value="'+data[i].item_id+'" id="'+data[i].item_id+'" checked>'+data[i].item_name+"<br>";
          }
          //alert(tblBodyData);
      }
      else{
          tblBodyData = "<h4>No Accessories</h>";
      }

      $t("#toolkitAccessory").html(tblBodyData);

    }
  });
}

   var counter=0;

    $t("#delRowss").click(function(){
        $t("#dataDesignation").hide();
        $t("#designation2").val('');
        $t("#contact_person2").val('');
        $t("#contact_number2").val('');
        if($t("#dataDesignation").hide()){ --counter; }
        //alert(counter)
        if(counter < 0){alert("Atleast One Contact Detail Must")}

    });
    $t("#addRowss").click(function(){
        $t("#dataDesignation").show();
        if($t("#dataDesignation").show()){ ++counter; }
        if(counter > 1){alert("No More Add Contacts")}
    });

$t("#required").focus();

    var offset = $t("#Zone_area").offset();
    var width = $t("#Zone_area").width()-2;
    $t("#ajax_response").css("left",offset);
    $t("#ajax_response").css("width","15%");
    $t("#ajax_response").css("z-index","1");
    $t("#Zone_area").keyup(function(event){
         //alert(event.keyCode);
         var keyword = $t("#Zone_area").val();
         var city_id= $t("#inter_branch_loc").val();
         var inter_branch= $t("#inter_branch").val();
          //alert(inter_branch);
         if(city_id=='')
         {
            city_id=1;
         }
         //alert(city_id);
         if(keyword.length)
         {
             if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13)
             {
                 $t("#loading").css("visibility","visible");
                 $t.ajax({
                   type: "POST",
                   url: "load_zone_area.php",
                   data: "data="+keyword+"&city_id="+city_id,
                   success: function(msg){  
                  // alert(msg); 
                    if(msg != 0)
                      $t("#ajax_response").fadeIn("slow").html(msg);
                    else
                    {
                      $t("#ajax_response").fadeIn("slow");   
                      $t("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
                    }
                    $t("#loading").css("visibility","hidden");
                   }
                 });
             }
             else
             {
                switch (event.keyCode)
                {
                 case 40:
                 {
                      found = 0;
                      $t("li").each(function(){
                         if($t(this).attr("class") == "selected")
                            found = 1;
                      });
                      if(found == 1)
                      {
                        var sel = $t("li[class='selected']");
                        sel.next().addClass("selected");
                        sel.removeClass("selected");
                      }
                      else
                        $t("li:first").addClass("selected");
                     }
                 break;
                 case 38:
                 {
                      found = 0;
                      $t("li").each(function(){
                         if($t(this).attr("class") == "selected")
                            found = 1;
                      });
                      if(found == 1)
                      {
                        var sel = $t("li[class='selected']");
                        sel.prev().addClass("selected");
                        sel.removeClass("selected");
                      }
                      else
                        $t("li:last").addClass("selected");
                 }
                 break;
                 case 13:
                    $t("#ajax_response").fadeOut("slow");
                    $t("#Zone_area").val($t("li[class='selected'] a").text());
                 break;
                }
             }
         }
         else
            $t("#ajax_response").fadeOut("slow");
    });
    $t("#ajax_response").mouseover(function(){
        $t(this).find("li a:first-child").mouseover(function () {
              $t(this).addClass("selected");
        });
        $t(this).find("li a:first-child").mouseout(function () {
              $t(this).removeClass("selected");
        });
        $t(this).find("li a:first-child").click(function () {
              $t("#Zone_area").val($t(this).text());
              $t("#ajax_response").fadeOut("slow");
        });
    });

     $t('.checkbox1').on('change', function() {
     var bool = $t('.checkbox1:checked').length === $t('.checkbox1').length;  
      $t('#acess_all').prop('checked', bool);       
       }); 
       $t('#acess_all').on('change', function() {    
       $t('.checkbox1').prop('checked', this.checked);      
      });







        $t(document).click(function(){
        $t("#ajax_response").fadeOut('slow');
    });




var logic = function( currentDateTime ){
  if (currentDateTime && currentDateTime.getDay() == 6){
    this.setOptions({
      minTime:'11:00'
    });
  }else
    this.setOptions({
      minTime:'8:00'
    });
};
$t('#datetimepicker').datetimepicker({
  onChangeDateTime:logic,
  onShow:logic
});
$t('#datetimepicker1').datetimepicker({
  onChangeDateTime:logic,
  onShow:logic
});

$t('#datetimepicker2').datetimepicker({
  onChangeDateTime:logic,
  onShow:logic
});




  // var counter = 1;
  
  // function addRow(tableID) 
  // {
  //   var table = document.getElementById(tableID);
  //   var rowCount = table.rows.length;
            
  //   if(rowCount>1)
  //   {
  //     alert("No more than 2 contact Details fills");
  //     return false;
  //   }
    
  //   var row = table.insertRow(rowCount);
  //   var colCount = table.rows[0].cells.length;

  //   for(var i=0; i<colCount; i++) 
  //   {

  //     var newcell = row.insertCell(i);
      
  //     newcell.innerHTML = table.rows[0].cells[i].innerHTML;
   
  //     switch(i) {
  //       case 0:
  //         newcell.childNodes[0].selectedIndex = 0;
  //         newcell.childNodes[0].id = 'designation' + counter ;  
    
  //         break;
  //       case 2:
         
  //         newcell.childNodes[0].id = 'contact_person' + counter ;
  //         //alert(newcell.childNodes[0].id);
  //         break;
  //       case 4:
          
  //         newcell.childNodes[0].id = 'contact_number' + counter ;
  //             //alert(newcell.childNodes[0].id);
  //         break;
  //     }
      
  //   }
    
  //   counter++;
  // }


  //   function deleteRow(tableID) {
  //   try {
  //     var table = document.getElementById(tableID);
  //     var rowCount = table.rows.length;
  //      // alert(rowCount); 
      
  //     if(rowCount <=1) {
  //       alert("Cannot delete all the rows.");
  //       return false;
  //     }
  //     if(rowCount > 1) {
  //       var row = table.rows[rowCount-1];
  //       //alert(row); 
  //       table.deleteRow(rowCount-1);
  //       // table.deleteRow(rowCount-2);
  //       // table.deleteRow(rowCount-3);
  //       rowCount = rowCount-3;
  //       rowCount--;
  //     }
  //   }
  //   catch(e) {
  //     alert(e);
  //   }
  // }

function vehicleType(radioValue)
{
  // alert(radioValue)
   if(radioValue=="Bus")
    {
        document.getElementById('MachineType').style.display = "none";
        document.getElementById('TruckType').style.display = "none";
        document.getElementById('TrailerType').style.display = "none";
        document.getElementById('standard').style.display = "block";
        document.getElementById('lux').style.display = "none";
    }
    else if(radioValue=="Car")
    {
        document.getElementById('standard').style.display = "none";
        document.getElementById('TruckType').style.display = "none";
        document.getElementById('MachineType').style.display = "none";
        document.getElementById('TrailerType').style.display = "none";
        document.getElementById('lux').style.display = "block";
    }
    else if(radioValue=="Tempo")
    {
        document.getElementById('standard').style.display = "none";
        document.getElementById('TruckType').style.display = "none";
        document.getElementById('TrailerType').style.display = "none";
        document.getElementById('MachineType').style.display = "none";
        document.getElementById('lux').style.display = "none";
        document.getElementById('actype').style.display = "block";
    }
    else if(radioValue=="Truck")
    {
        document.getElementById('standard').style.display = "none";
        document.getElementById('TrailerType').style.display = "none";
        document.getElementById('MachineType').style.display = "none";
        document.getElementById('actype').style.display = "none";
        document.getElementById('lux').style.display = "none";
        document.getElementById('TruckType').style.display = "block";
    }
    else if(radioValue=="Trailer")
    {
        document.getElementById('standard').style.display = "none";
        document.getElementById('TruckType').style.display = "none";
        document.getElementById('MachineType').style.display = "none";
        document.getElementById('lux').style.display = "none";
        document.getElementById('actype').style.display = "none";
        document.getElementById('TrailerType').style.display = "block";
    }
    else if(radioValue=="Machine")
    {
        document.getElementById('standard').style.display = "none";
        document.getElementById('TruckType').style.display = "none";
        document.getElementById('actype').style.display = "none";
        document.getElementById('actype').style.display = "none";
        document.getElementById('TrailerType').style.display = "none";
        document.getElementById('lux').style.display = "none";
        document.getElementById('MachineType').style.display = "block";
    }
    else if(radioValue=="Bike")
    {
        document.getElementById('standard').style.display = "none";
        document.getElementById('actype').style.display = "none";
        document.getElementById('MachineType').style.display = "none";
        document.getElementById('TruckType').style.display = "none";
        document.getElementById('TrailerType').style.display = "none";
        document.getElementById('lux').style.display = "none";
    }
    else
    {
        document.getElementById('standard').style.display = "none";
        document.getElementById('TruckType').style.display = "none";
        //document.getElementById('deviceMdl').style.display = "none";
    }
   
}

function standardType(radioValue)
{

   document.getElementById('actype').style.display = "block";

}
function aclux(radioValue)
{

  //alert(radioValue)
  
          document.getElementById('actype').style.display = "block";
          //document.getElementById('deviceMdl').style.display = "block";

}



</script>

<script>StatusBranch12("<?=$result['branch_type'];?>");
TillBetweenTime12("<?=$result['atime_status'];?>");</script>
