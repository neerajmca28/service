<?php  
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
?>
<link rel="stylesheet" type="text/css" href="<?php echo __SITE_URL;?>/build/jquery.datetimepicker.css"/>
<script src="<?php echo __SITE_URL;?>/build/jquery.datetimepicker.full.js"></script>

<?php
  $date=date("Y-m-d H:i:s");
  $account_manager=$_SESSION['username'];
?>

<div class="top-bar">
  <h1><?php echo $Header;?></h1>
</div>

<div class="table">
<?php

$Header="Edit Installation";
$account_manager=$_SESSION['username'];
$action=$_GET['action'];
$id=$_GET['id'];
$page=$_POST['page'];

if($action=='edit')
{
    $Header="Edit Re-Addition";
    $result = select_query("select * from $internalsoftware.installation_request where id=$id and branch_id=".$_SESSION['BranchId']);   
    //echo "<pre>";print_r($result);
    $Zone_area = $result[0]["Zone_area"];
    $area = select_query("SELECT id,`name` FROM $internalsoftware.re_city_spr_1 WHERE id='".$Zone_area."'");
    $devicelList = select_query("SELECT dtype.id as dev_type_id,dtype.device_type as deviceType FROM $internalsoftware.new_account_model_master as newmodel LEFT JOIN $internalsoftware.device_type as dtype ON newmodel.device_type=dtype.id WHERE new_account_reqid='".$result[0]['user_id']."'");
    $devModelList = select_query("SELECT dm.id as model_id,dm.device_model as model_name from $internalsoftware.new_account_model_master as newmodel inner join $internalsoftware.device_model as dm  ON newmodel.device_model=dm.id WHERE newmodel.new_account_reqid='".$result[0]['user_id']."' and dm.parent_id='".$result[0]['device_type']."'");
    $sql=select_query("select $internalsoftware.access_toolkit from new_account_creation where user_id='".$result[0]["user_id"]."' ");
    $toolName=array();

      $sql1=select_query("select $internalsoftware.accessories_tollkit from installation_request where id=".$id);
      $toolkitId = explode("#",$sql1[0]['accessories_tollkit']);

      for($i=0;$i<=count($toolkitId)-1;$i++)
      {
        $sqlToolsName=select_query("select * from $internalsoftware.toolkit_access where id='".$toolkitId[$i]."'");
        $data = array(

          "item_id"=>$sqlToolsName[0]['id'],
          "item_name"=>$sqlToolsName[0]['items']

        );
        array_push($toolName,$data);
      }
}
?>

<div class="top-bar">
  <h1><?php echo $Header;?></h1>
</div>
<div class="table">
<?php
if(isset($_POST['submit']))
{ 
   // echo '<pre>'; print_r($_POST);die;
   
    $date=date("Y-m-d H:i:s");
    $account_manager=$_SESSION['username'];
    $sales_person=trim($_POST['sales_person']);
    $sales_manager = select_query("select id as sales_id from $internalsoftware.sales_person where name='".$sales_person."' limit 1");
    $sales_person_id=$sales_manager[0]['sales_id'];
    $main_user_id2=$_POST['main_user_id2'];
    $company=$_POST['company'];
    $no_of_vehicals=$_POST['no_of_vehicals'];
    $model=$_POST['model'];
    $designation=$_POST['designation'];
    $alt_designation=$_POST['designation2'];
    $contact_person=$_POST['contact_person'];
    $alt_cont_person=$_POST['contact_person2'];
    $contact_number=$_POST['contact_number'];
    $alt_cont_number=$_POST['contact_number2'];
    $atime_status=$_POST['atime_status'];
    $back_reason=$_POST['back_reason'];
    $instal_reinstall = 'installation';
    $accessories_tollkit="";
    $deviceimeiupdate=$_POST['deviceimeiupdate'];
    $devicestatusUpdate = $_POST['devicestatusUpdate'];

    for($i=0;$i<count($_POST['toolkit']);$i++)
    {
      $accessories_tollkit.=$_POST['toolkit'][$i]."#";
      $accessories_tollkits=substr($accessories_tollkit,0,strlen($accessories_tollkit)-1);
    }

    $veh_type=$_POST['veh_type'];

    if($veh_type == 'Car'){

      $luxury = $_POST['lux'];
      $actype=$_POST['actype'];
      
      $TruckType='';
      $TrailerType='';
      $MachineType='';
      $del_nodelux='';
      
    }
    else if($veh_type == 'Bus'){

      $del_nodelux=$_POST['standard'];
      $actype=$_POST['actype'];

      $TruckType='';
      $TrailerType='';
      $MachineType='';
      $luxury='';
      
    }
    else if($veh_type == 'Truck'){

      $TruckType=$_POST['TruckType'];
      $luxury='';
      $del_nodelux='';
      $actype='';
      $TrailerType='';
      $MachineType='';

    }
    else if($veh_type == 'Bike'){
      $TruckType='';
      $luxury='';
      $del_nodelux='';
      $actype='';
      $TrailerType='';
      $MachineType='';

    }
    else if($veh_type == 'Trailer'){

      $TrailerType=$_POST['TrailerType'];
      $TruckType='';
      $luxury='';
      $del_nodelux='';
      $actype='';
      $MachineType='';

    }
    else if($veh_type == 'Machine'){

      $MachineType=$_POST['MachineType'];
      $TrailerType='';
      $TruckType='';
      $luxury='';
      $del_nodelux='';
      $actype='';

    }
    else if($veh_type == 'Tempo'){
      $actype=$_POST['actype'];
      $MachineType='';
      $TrailerType='';
      $TruckType='';
      $luxury='';
      $del_nodelux='';
      

    }
    else{
      $MachineType='';
      $TrailerType='';
      $TruckType='';
      $luxury='';
      $del_nodelux='';
      $actype='';
    }
   
    $billing = $_POST['billing'];
    $acess_selection = $_POST['access_radio'];
    $deviceType=$_POST['deviceType'];
    $landmark = $_POST['landmark'];
   
    if($_POST['required']=="") { $required="normal"; }
    else { $required="urgent"; }

    // $installation_status = $_POST['installation_status'];
    $installation_status=1;
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
   
   
    $location1=$_POST['inter_branch'];
    $interbranch = $_POST['inter_branch_loc'];

    // if($location1 == 'Interbranch'){
    //   $query = select_query("select city from $internalsoftware.tbl_city_name where branch_id='".$interbranch."'");
    //   $branchLocation = $query[0]['city'];
    // }
    // else
    // {
    //   $branchLocation = "Delhi";
    // }


    if(isset($_POST['inter_branch_loc']))
     {
        $city=$_POST['inter_branch_loc'];
         $query = select_query("select city from $internalsoftware.tbl_city_name where branch_id='".$_POST['inter_branch_loc']."' ");
         $branchLocation = $query[0]['city'];
    
     }
     
     if(($_POST['inter_branch_loc']) == '')
     {
      
       $branchLocation = "Delhi";
     }



    if($errorMsg=="")   
    { 

        if($atime_status=="Till")
        {

       
            $time=$_POST['time'];
            $sql="update $internalsoftware.installation_request set sales_person='".$sales_person_id."', `user_id`= '".$main_user_id2."', `company_name`='".$company."', time='".$time."', atime_status='".$atime_status."', model='".$model."', contact_number='".$contact_number."' , contact_person='".$contact_person."',Zone_area='".$Area."', location='".$branchLocation."',veh_type='".$veh_type."',required='".$required."',designation='".$designation."',alt_designation='".$alt_designation."',alt_cont_person='".$alt_cont_person."',standard='".$del_nodelux."',actype='".$actype."',MachineType='".$MachineType."',TruckType='".$TruckType."',TrailerType='".$TrailerType."',billing='".$billing."',accessories_tollkit='".$accessories_tollkits."',alter_contact_no='".$alt_cont_number."',device_type='".$deviceType."',luxury='".$luxury."',inter_branch='".$interbranch."',installation_status='".$installation_status."' where id='".$id."'";
            
            $execute=mysql_query($sql);
        }
        
        if($atime_status=="Between")
        {
            $time=$_POST['time1'];
            $totime=$_POST['totime'];

           $sql="update $internalsoftware.installation_request set sales_person='".$sales_person_id."', `user_id`= '".$main_user_id2."', `company_name`='".$company."', time='".$time."',totime='".$totime."',atime_status='".$atime_status."', model='".$model."', contact_number='".$contact_number."' ,contact_person='".$contact_person."',Zone_area='".$Area."', location='".$branchLocation."',  veh_type='".$veh_type."',required='".$required."',designation='".$designation."',alt_designation='".$alt_designation."',alt_cont_person='".$alt_cont_person."',standard='".$del_nodelux."',actype='".$actype."',MachineType='".$MachineType."',TruckType='".$TruckType."',TrailerType='".$TrailerType."',billing='".$billing."',accessories_tollkit='".$accessories_tollkits."',alter_contact_no='".$alt_cont_number."',device_type='".$deviceType."',luxury='".$luxury."',landmark='".$landmark."',inter_branch='".$interbranch."',installation_status='".$installation_status."' where id='".$id."'";
            $execute=mysql_query($sql);
            
        }
         
        $idImei = select_query("select id from $internalsoftware.installation where inst_req_id=$id");

        if(count($idImei) > 0){
          
          $id=array();
          
          for($i=0;$i<=count($idImei);$i++){
            array_push($id, $idImei[$i]['id']);
          }
          
          for($j=0;$j<=count($id);$j++){
            $sql="update $internalsoftware.installation set device_imei='".$deviceimeiupdate[$j]."', `imei_status`= '".$devicestatusUpdate[$j]."' where id='".$id[$j]."'"; 
            $execute=mysql_query($sql);
          }
        }

        if($installation_status == '7')
        {
            $update_query = mysql_query("update $internalsoftware.installation_request set installation_status=8 where id=$id");
        }
       
        echo "<script>document.location.href ='installation.php'</script>";

    }
   
}

?>

<script type="text/javascript">
var mode;

function req_info()
{
   var inter_branch=document.forms["form1"]["inter_branch"].value;
    if (inter_branch==null || inter_branch=="")
    {
      alert("Please Select Branch ") ;
      return false;
    }
    if(inter_branch=='Interbranch')
    {
      var interbranch=document.forms["form1"]["inter_branch_loc"].value;
      if(interbranch==null || interbranch=="")
      {
          alert("Please Select Branch");
          document.form1.inter_branch_loc.focus();
          return false;
      }
    }

  if(document.form1.Zone_area.value=="")
    {
      alert("Please Enter Location") ;
      document.form1.Zone_area.focus();
      return false;
    }

    var location=document.forms["form1"]["location"].value;
    if (location==null || location=="")
    {
        alert("Please Enter Area");
        document.form1.location.focus();
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

        var d1 = new Date(betweentime);
        var d2 = new Date(betweentime2);       

        if(timestatus=="Between")
        {  
          //alert('tt');     
          if(diff<=3.80)   
          {  
              alert('Please enter 4 hour difference for available time');  
              document.form1.datetimepicker1.focus();   
              return false;    
          }
          if(d1.getTime() >= d2.getTime()){

              alert('Please check (To Time) is greater (From Time)');  
              document.form1.datetimepicker2.focus();   
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
  //alert('tt');
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
        document.getElementById('inter_branch1').checked = true;
        document.getElementById('branchlocation').style.display = "block";
    }
    else if(radioValue=="Samebranch")
    {
      document.getElementById('inter_branch').checked = true;
        document.getElementById('branchlocation').style.display = "none";
    }
    else
    {
        document.getElementById('branchlocation').style.display = "none";
    }
   
} 

function showAccess(radioValue)
{
  //alert(radioValue)
   if(radioValue=="yes")
    {
        document.getElementById('accessTable').style.display = "block";
    }
    else if(radioValue=="no")
    {
        document.getElementById('accessTable').style.display = "none";
    }
    else
    {
        document.getElementById('accessTable').style.display = "none";
    }
   
}
 
        

function StatusBranch12(radioValue)
{
  //alert(radioValue)
   if(radioValue=="Interbranch")
    {
        document.getElementById('inter_branch_loc').style.display = "block";
    }
    else if(radioValue=="Samebranch")
    {
        document.getElementById('branchlocation').style.display = "none";
    }
    else
    {
        document.getElementById('branchlocation').style.display = "none";
        document.getElementById('samebranchid').style.display = "none";
    }
   
}

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
        document.getElementById('actype').style.display = "block";
        
    }
    else if(radioValue=="Car")
    {
        document.getElementById('standard').style.display = "none";
        document.getElementById('TruckType').style.display = "none";
        document.getElementById('MachineType').style.display = "none";
        document.getElementById('TrailerType').style.display = "none";
        document.getElementById('lux').style.display = "block";
        document.getElementById('actype').style.display = "block";
        
    }
    else if(radioValue=="Tempo")
    {
        document.getElementById('actype').style.display = "none";
        document.getElementById('standard').style.display = "none";
        document.getElementById('TruckType').style.display = "none";
        document.getElementById('TrailerType').style.display = "none";
        document.getElementById('MachineType').style.display = "none";
        document.getElementById('lux').style.display = "none";
      
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


function standardType(radioValue){

   if(radioValue=='Delux')
   {
        document.getElementById('actype').style.display = "block";
   }
   else if(radioValue=='NonDelux')
   {
        document.getElementById('actype').style.display = "none";
   }
  
}

function aclux(value){

  if(radioValue=='luxury')
  {
      document.getElementById('actype').style.display = "block";
  }
  else if(radioValue=='NonLuxury')
  {
      document.getElementById('actype').style.display = "none";
  }
  

}

function accesShow(radioValue)
{
    if(radioValue=="yes")
    {
        document.getElementById('acc_yes').checked = true;
        document.getElementById('accessTable').style.display = "block";
          
    }
    else
    {
          document.getElementById('acc_no').checked = true;
           document.getElementById('accessTable').style.display = "none";
     }

}
</script> 

<?php echo "<p align='left' style='padding-left: 250px;width: 500px;' class='message'>" .$errorMsg. "</p>" ; ?>
  
<style type="text/css" >
.errorMsg{border:1px solid red; }
.message{color: red; font-weight:bold; }
body { font-family:'Open Sans' Arial, Helvetica, sans-serif}
ul,li { margin:0; padding:0; list-style:none;}
.label { color:#000; font-size:16px;}
</style>

 <form method="post" action="" name="form1" onSubmit="return req_info();" autocomplete="off">
    <table style="padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">
       
      <tr>
        <td nowrap align="right">Billing: </td>
        <td><?php $branch_data = select_query("select * from tbl_city_name where branch_id='".$_SESSION['BranchId']."'"); ?>
          <input type='radio' Name ='billing' id='bill_yes' value= 'no' <?php if($result['branch_type']==' '){echo "checked=\"checked\""; }?> checked="checked">
          No

          &#160;&#160;&#160;&#160;&#160;&#160; Urgent:<input type="checkbox" name="required"  id="required" value="urgent" <?php if($result[0]['required']=='urgent') {?> checked="checked" <?php }?> />
        </td>
    </tr>
    <tr>
      <td nowrap align="right">Sales Person:*</td>
      <td>
        <?php 

          $sql = "SELECT name,id from sales_person where id=".$result[0]['sales_person']; 
          $row=select_query($sql);

        ?>
        <input type="text" name="sales_person" id="TxtCompany" readonly value="<?php echo $row[0]['name']?>" style="width:30%;border-radius: 5px;padding:2px;"/>
        <input type="hidden" name="sales_person_id" id="TxtCompany" readonly value="<?php echo $row[0]['id']?>"/>
      </td>
    </tr>
    <tr>
        <td nowrap align="right"> Client User Name:*</td>
        <td>
          <?php 

            $sql = "SELECT UserName from addclient where Userid=".$result[0]['user_id']; 
            
            $row=select_query($sql);

          ?>
          <input type="text" name="main_user_id" id="TxtCompany" readonly value="<?php echo $row[0]['UserName']?>" style="width:30%;border-radius: 5px;padding:2px;"/>
          <input type="hidden" name="main_user_id2" id="TxtCompany" readonly value="<?php echo $result[0]['user_id']?>"/>
        </td>
      </tr>
      <tr>
        <td nowrap align="right"> Company Name:*</td>
        <td><input type="text" name="company" id="TxtCompany" readonly value="<?php echo $result[0]['company_name']?>" style="width:30%;border-radius: 5px;padding:2px;"/></td>
      </tr>
      <tr>
        <td nowrap align="right">No. Of Vehicles:*</td>
        <td>
          <input type="text" name="no_installation" id="no_installation" readonly value="<?php echo $result[0]['no_of_vehicals']?>" style="width:30%;border-radius: 5px;padding:2px;"/>
        </td>
      </tr>
      <tr>
        <td nowrap align="right">Device Status</td>
        <td><input type="text" readonly style="width:30%;border-radius: 5px;padding:2px;" value="<?php echo $result[0]['device_status'];?>"></td>
      </tr>
      <tr>
        <td nowrap align="right">Device Current Status</td>
        <td><?php

         if($result[0]['device_current_location'] == 1){ ?>
          
          <input type="text" readonly style="width:30%;border-radius: 5px;padding:2px;" value="<?php echo "G-Trac";?>">
         <?php 
         }
         else{
         ?> 
          <input type="text" readonly style="width:30%;border-radius: 5px;padding:2px;" value="<?php echo "Client";?>">
         <?php } ?>
       </td>
      </tr>
      <tr>
        <td  align="right">ReAddition IMEI: </td>
        <td nowrap>
          <h3><?php  echo $result[0]['device_imei']; ?></h3>
        </td>
      </tr>
     <?php 
    if(($sql1[0]['accessories_tollkit'] == '') || empty($sql1[0]['accessories_tollkit']))
    {?>
       <tr>
        <td colspan="2">
          <table  id="accessTable"  align="left" style="margin-left:8px" cellspacing="2" cellpadding="2">
          <tr>
            <td>Accessory Selected :</td>
            <td><h4>No Accessories</h4></td>

          </tr>

          </table>
        </td>
      </tr>
      <?php
       }
      else
      { ?>

      <tr>
        <td colspan="2">
          <table  id="accessTable"  align="left" style="margin-left:10px" cellspacing="2" cellpadding="2">
          <tr>
            <td>Accessory Selected :</td>
            <td>
              <div style="margin-left:10px">
              <?php
              for($i=0;$i<count($toolName);$i++){?>
                <input type="checkbox" checked name="toolkit[]" value="<?=$toolName[$i]['item_id']?>"><?php echo $toolName[$i]['item_name']."<br>"; ?>
              <?php } ?>
              </div>
            </td>
          </tr>

          </table>
        </td>
      </tr>

      <?php } ?>
      <tr>
        <td nowrap align="right">Branch:* </td>
        <td><?php $branch_data = select_query("select * from tbl_city_name where branch_id='".$_SESSION['BranchId']."'"); ?>
          <input type='radio' name ='inter_branch' id='inter_branch' value= 'Samebranch' <?php if($result[0]['branch_type']=='Samebranch'){echo "checked=\"checked\""; }?> onchange="StatusBranch(this.value);" disabled="disabled">
          <?php echo $branch_data[0]["city"];?>
          <input type='radio' name ='inter_branch' id='inter_branch1' value= 'Interbranch' <?php if($result[0]['branch_type']=='Interbranch'){echo "checked=\"checked\""; }?>
        onchange="StatusBranch(this.value);" disabled="disabled">
          Inter Branch 
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <table  id="branchlocation"  align="left"  <?php if($result[0]['branch_type']=='Interbranch') { ?> style="margin-left:25px;" <?php } ?> style="display:none;margin-left:15px;" cellspacing="5" cellpadding="5">
            <tr>
              <td align="left">Branch Name:*</td>
              <td >
                <select name="inter_branch_loc" id="inter_branch_loc" style="width:150px;">
                  <option value="" >-- Select One --</option>
                  <?php

                      $city1=select_query("select * from tbl_city_name where branch_id!='".$_SESSION['BranchId']."'");
                      
                      for($i=0;$i<count($city1);$i++)
                      {
                          if($city1[$i]['branch_id']==$result[0]['inter_branch'])
                          {
                              $selected="selected";
                          }
                          else
                          {
                              $selected="";
                          }
                      ?>
                      <option value ="<?php echo $city1[$i]['branch_id'] ?>"  <?php echo $selected;?>> <?php echo $city1[$i]['city']; ?> </option>
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
        <td colspan="2">
          <span style="width:8px;margin-left:58px;align:right">Location:*&#160;&#160;&#160;&#160;&#160;</span><input type="text" name="Zone_area" id="Zone_area" style="width:24%;border-radius: 5px;padding:2px;" value="<?php echo $area[0]["name"];?>" /> 
          <div id="ajax_response" style="width:8px;margin-left:124px;align:right;"></div>         
        </td>
      </tr>
      
     <tr>
        <td align="right">Area :*</td>
        <td><input type="text" name="landmark"  id="location" minlength="15" style="width:30%;border-radius: 5px;padding:2px;" value="<?php echo $result[0]['landmark']?>"/></td>
     </tr>
      
    <tr>
        <td nowrap align="right">Availbale Time status:*</td>
        <td><select name="atime_status" id="atime_status" style="width:150px" onchange="TillBetweenTime(this.value)">
            <option value="">Select Status</option>
            <option value="Till" <?php if($result[0]['atime_status']=='Till') {?> selected="selected" <?php } ?> >Till</option>
            <option value="Between" <?php if($result[0]['atime_status']=='Between') {?> selected="selected" <?php } ?> >Between</option>
          </select>
        </td>
    </tr>
    <tr>
      <td colspan="2">
          <table  id="TillTime" align="left" style="width:100%;display:none;margin-left:63px;"  cellspacing="5" cellpadding="5">
            <tr>
              <td align="right">Time:*</td>
              <td><input type="text" name="time" id="datetimepicker" value="<?php echo date("Y-m-d H:i",strtotime($result[0]['time']))?>"  style="width:145px;border-radius: 5px;padding:2px;"/></td>
            </tr>
          </table>
          <table  id="BetweenTime" align="left" style="width:100%;display:none;margin-left:34px;"  cellspacing="5" cellpadding="5">
            <tr>
              <td align="right">From Time:*</td>
              <td><input type="text" name="time1" id="datetimepicker1" style="width:145px;border-radius: 5px;padding:2px;" value="<?php echo date("Y-m-d H:i",strtotime($result[0]['time']))?>" /></td>
            </tr>
            <tr>
              <td align="right">To Time:*</td>
              <td>
                <?php
                if($result[0]['totime'] == ''){ ?>
                  <input type="text" name="totime" id="datetimepicker2"  style="width:145px;border-radius: 5px;padding:2px;" <?php if(empty($result[0]['totime'])) { ?> value="" <?php } else { ?> value="<?php echo date("Y-m-d H:i",strtotime($result[0]['totime']))?>" <?php } ?> /></td>
                <?php }
                 else{ 
                 ?>  
                 
                <input type="text" name="totime" id="datetimepicker2" value="<?php echo date("Y-m-d H:i",strtotime($result[0]['totime']))?>" style="width:145px;border-radius: 5px;padding:2px;"/></td>
                <?php } ?>

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
          <table id="dataTable" cellspacing="" cellpadding="">
          <tr>
            <td  height="32" align="right"><select name="designation" id="designation" style="margin-left:-4px"
            onchange="designationChange(this.value)">
                <option value="">-- Select Designation --</option>
                <option value="driver" <?php if($result[0]['designation']=='driver') {?> selected="selected" <?php } ?> >Driver</option>
                <option value="supervisor"  <?php if($result[0]['designation']=='supervisor') {?> selected="selected" <?php } ?> >Supervisor</option>
                <option value="manager"  <?php if($result[0]['designation']=='manager') {?> selected="selected" <?php } ?> >Manager</option>
                <option value="senior manager"  <?php if($result[0]['designation']=='senior manager') {?> selected="selected" <?php } ?>  >Senior Manager</option>
                 <option value="owner"  <?php if($result[0]['designation']=='owner') {?> selected="selected" <?php } ?> >Owner</option>
                 <option value="sale person"  <?php if($result[0]['designation']=='sale person') {?> selected="selected" <?php } ?> >Sale Person</option>
                 <option value="others"  <?php if($result[0]['designation']=='others') {?> selected="selected" <?php } ?>>Others</option>
              
              </select>
            </td>
            <td>
              <input type="text" name="contact_person" id="contact_person"  placeholder="Contact Person" value="<?php echo $result[0]['contact_person']?>"  style="width:147px"/>
            </td>
            <td>
              <input type="text" name="contact_number" id="contact_number" placeholder="Contact Number" minlength="10" maxlength="10" value="<?php echo $result[0]['contact_number']?>"  onkeypress="return event.charCode >= 48 && event.charCode <= 57"  style="width:147px"/>
            </td>       
          </tr>

           <tr id="dataDesignation" <?php if(strlen($result[0]['alt_designation']) > 0) {} else{?>style="display:none"<?php } ?>>
            <td  height="32" align="right">
              <select name="designation2" id="designation2" style="margin-left:-4px" onchange="designationChange(this.value)">
                <option value="">-- Select Designation --</option>
                <option value="Driver" <?php if($result[0]['alt_designation']=='Driver') {?> selected="selected" <?php } ?> >Driver</option>
                <option value="Supervisor"  <?php if($result[0]['alt_designation']=='Supervisor') {?> selected="selected" <?php } ?> >Supervisor</option>
                <option value="Manager"  <?php if($result[0]['alt_designation']=='Manager') {?> selected="selected" <?php } ?> >Manager</option>
                <option value="Senior Manager"  <?php if($result[0]['alt_designation']=='Senior Manager') {?> selected="selected" <?php } ?>  >Senior Manager</option>
                 <option value="Owner"  <?php if($result[0]['alt_designation']=='Owner') {?> selected="selected" <?php } ?> >Owner</option>
                <option value="Sale Person"  <?php if($result[0]['alt_designation']=='Sale Person') {?> selected="selected" <?php } ?> >Sale Person</option>
                <option value="Others"  <?php if($result[0]['alt_designation']=='Others') {?> selected="selected" <?php } ?>>Others</option>
              </select>
            </td>
            <td>
              <input type="text" name="contact_person2" placeholder="Contact Person"  id="contact_person2" value="<?=$result[0]['alt_cont_person']?>"  style="width:147px"/>
            </td>
            <td>
              <input type="text" name="contact_number2" placeholder="Contact Number" id="contact_number2"  minlength="10" maxlength="10" value="<?php echo $result[0]['alter_contact_no']?>"  onkeypress="return event.charCode >= 48 && event.charCode <= 57"   style="width:147px"/>
            </td>       
          </tr>
           
        </table>

        </td>
      </tr>

      <tr>
        <td align="right">Vehicle Type:*</td>
        <td>
          <table align="left" cellspacing="" cellpadding="">
            <tr>
              <td>
                <select name="veh_type" id="veh_type" style="width:150px;margin-left:-4px" onchange="vehicleType(this.value,'standard');" >
                  <option value=""  selected>Select your option</option>
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
                  <option value="" selected>Select Luxury Category</option>
                  <option value="luxury" <?php if($result[0]['luxury']=='luxury') {?> selected="selected" <?php } ?> >Lurxury</option>
                  <option value="NonLuxury" <?php if($result[0]['luxury']=='NonLuxury') {?> selected="selected" <?php } ?>>Non-Luxury</option>
                </select>
              </td>
              <td>
                <select name="standard" id="standard" placeholder="Vehicle Type" style="width:150px;display:none" onchange="standardType(this.value,'actype');" >
                  <option value="" selected>Select Delux Category</option>
                  <option value="Delux" <?php if($result[0]['standard']=='Delux') {?> selected="selected" <?php } ?> >Delux</option>
                  <option value="NonDelux" <?php if($result[0]['standard']=='NonDelux') {?> selected="selected" <?php } ?>>NonDelux</option>
                </select>
              </td>
              <td>
                <select name="actype" id="actype" >
                  <option value="" selected>Select AC Category</option>
                  <option value="AC" <?php if($result[0]['actype']=='AC') {?> selected="selected" <?php } ?>>AC</option>
                  <option value="NonAC" <?php if($result[0]['actype']=='NonAC') {?> selected="selected" <?php } ?>>Non-AC</option>
                </select>
              </td>
              <td>
                <select name="TrailerType" id="TrailerType" palceholder="Vehicle Type" style="width:150px;display:none" >
                  <option value="" selected>Select Trailer Type</option>
                  <option value="Genset  AC Trailer" <?php if($result[0]['TrailerType']=='Genset  AC Trailer') {?> selected="selected" <?php } ?>>Genset  AC Trailer</option>
                  <option value="Refrigerated Trailer" <?php if($result[0]['TrailerType']=='Refrigerated Trailer') {?> selected="selected" <?php } ?>>Refrigerated Trailer</option>
                </select>
              </td>
              <td>
                <select name="MachineType" id="MachineType" palceholder="Vehicle Type" style="width:150px;display:none" >
                  <option value="" selected>Select Machine category</option>
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
                  <option value="" selected>Select Truck Type</option>
                  <option value="Refrigerated Truck" <?php if($result[0]['TruckType']=='Refrigerated Truck') {?> selected="selected" <?php } ?>>Refrigerated Truck</option>
                  <option value="Pickup Van" <?php if($result[0]['TruckType']=='Pickup Van') {?> selected="selected" <?php } ?>>Pickup Van</option>
                </select>
              </td>
            </tr>
          </table>  

        </td>
      </tr>
      <tr>
        <td align="right"><input type="submit" name="submit" id="button1" value="UPDATE" align="right" /></td>
        <td><input type="button" name="Cancel" style="margin-left:-4px" value="CANCEL" onClick="window.location = 'installation.php' " /></td>
      </tr>
    </form>
</div>
<script type="text/javascript">

var $jq = jQuery.noConflict();

$jq(document).ready(function(){

//  alert("1")

    $jq("#hide").click(function(){
        $jq("#acn").hide();
    });
    $jq("#show").click(function(){
        $jq("#acn").show();
    });


   // Designation Hide Show

  var counter=0;

  $jq("#delRowss").click(function(){
      $jq("#dataDesignation").hide();
      $jq("#designation2").val('');
      $jq("#contact_person2").val('');
      $jq("#contact_number2").val('');
      if($jq("#dataDesignation").hide()){ --counter; }
      //alert(counter)
      if(counter < 0){alert("Atleast One Contact Must")}

  });
  $jq("#addRowss").click(function(){
      $jq("#dataDesignation").show();
      if($jq("#dataDesignation").show()){ ++counter; }
      if(counter > 1){alert("No More Add Contacts")}
  });

  // End Designation Hide Show   


  
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
$jq('#datetimepicker').datetimepicker({
  'format': 'Y-m-d H:i',
    'minDate': 0,
    'closeOnDateSelect' : true,
    'interval': 20,
    'validateOnBlur' : true,
    'minDateTime': new Date(),
    'step':30

});
$jq('#datetimepicker1').datetimepicker({
    'format': 'Y-m-d H:i',
    'minDate': 0,
    'closeOnDateSelect' : true,
    'interval': 20,
    'validateOnBlur' : true,
    'minDateTime': new Date(),
    'step':30
});

$jq('#datetimepicker2').datetimepicker({
  'format': 'Y-m-d H:i',
    'minDate': 0,
    'closeOnDateSelect' : true,
    'interval': 20,
    'validateOnBlur' : true,
    'minDateTime': new Date(),
    'step':30
});


     // Accessories Checked Unchecked

      $jq('.checkbox1').on('change', function() {
      var bool = $jq('.checkbox1:checked').length === $jq('.checkbox1').length;
        $jq('#acess_all').prop('checked', bool);
        });

        $jq('#acess_all').on('change', function() {
        $jq('.checkbox1').prop('checked', this.checked);
      });

      // End Accessories Checked Unchecked  

    $jq(document).click(function(){
      $jq("#ajax_response").fadeOut('slow');
    });

    $jq("#required").focus();

    var offset = $jq("#Zone_area").offset();
    var width = $jq("#Zone_area").width()-2;
    $jq("#ajax_response").css("left",offset);
    $jq("#ajax_response").css("width","15%");
    $jq("#ajax_response").css("z-index","1");
    $jq("#Zone_area").keyup(function(event){
         //alert(event.keyCode);
         var keyword = $jq("#Zone_area").val();
         var city_id= $jq("#inter_branch_loc").val();
         var inter_branch= $jq("#inter_branch").val();
          //alert(inter_branch);
         if(city_id=='')
         {
            city_id=1;
         }
          //alert(keyword);
         if(keyword.length)
         {
             if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13)
             {
                 $jq("#loading").css("visibility","visible");
                 $jq.ajax({
                   type: "POST",
                   url: "load_zone_area.php",
                   data: "data="+keyword+"&city_id="+city_id,
                   success: function(msg){   
                    //alert(msg)
                    if(msg != 0)
                      $jq("#ajax_response").fadeIn("slow").html(msg);
                    else
                    {
                      $jq("#ajax_response").fadeIn("slow");   
                      $jq("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
                    }
                    $jq("#loading").css("visibility","hidden");
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
                      $jq("li").each(function(){
                         if($jq(this).attr("class") == "selected")
                            found = 1;
                      });
                      if(found == 1)
                      {
                        var sel = $jq("li[class='selected']");
                        sel.next().addClass("selected");
                        sel.removeClass("selected");
                      }
                      else
                        $jq("li:first").addClass("selected");
                     }
                 break;
                 case 38:
                 {
                      found = 0;
                      $jq("li").each(function(){
                         if($jq(this).attr("class") == "selected")
                            found = 1;
                      });
                      if(found == 1)
                      {
                        var sel = $jq("li[class='selected']");
                        sel.prev().addClass("selected");
                        sel.removeClass("selected");
                      }
                      else
                        $jq("li:last").addClass("selected");
                 }
                 break;
                 case 13:
                    $jq("#ajax_response").fadeOut("slow");
                    $jq("#Zone_area").val($jqs("li[class='selected'] a").text());
                 break;
                }
             }
         }
         else
            $jq("#ajax_response").fadeOut("slow");
    });
    $jq("#ajax_response").mouseover(function(){
      $jq(this).find("li a:first-child").mouseover(function () {
        $jq(this).addClass("selected");
      });
      $jq(this).find("li a:first-child").mouseout(function () {
        $jq(this).removeClass("selected");
      });
      $jq(this).find("li a:first-child").click(function () {
        $jq("#Zone_area").val($jq(this).text());
        $jq("#ajax_response").fadeOut("slow");
      });
   
    

    $jq('#accessories').multiselect({
      columns: 1,
      placeholder: 'Select Accessories',
      search: true
    });
  });
});

</script>

<?php
include("../include/footer.php");

?>


<script>StatusBranch12("<?php echo $result[0]['branch_type'];?>");TillBetweenTime12("<?php echo $result[0]['atime_status'];?>");
vehicleType("<?php echo $result[0]['veh_type'];?>");standardType("<?php echo $result[0]['standard'];?>");aclux("<?php echo $result[0]['luxury'];?>");accesShow("<?php echo $result[0]['acess_selection'];?>")

function deviceStaus(imei,setDivId){
  $jq.ajax({
      type:"GET",
      url:"userinfo.php?action=imeistatus",
      data:"imeiNo="+imei,
      success:function(msg){
        document.getElementById(setDivId).value = msg;
      }
  });
}

function imeiDeviceType(imei,setDivId){
  $jq.ajax({
      type:"GET",
      url:"userinfo.php?action=imeiDeviceType",
      data:"imeiNo="+imei,
      success:function(msg){
        document.getElementById(setDivId).value = msg;
      }
  });
}

function imeiDeviceModel(imei,setDivId){
  $jq.ajax({
      type:"GET",
      url:"userinfo.php?action=imeiModelName",
      data:"imeiNo="+imei,
      success:function(msg){
        //alert(msg)
        document.getElementById(setDivId).value = msg;
      }
  });
}
</script>
