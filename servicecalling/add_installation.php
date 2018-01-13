<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */
?>

<link  href="../css/auto_dropdown.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

/*Start auto ajax value load code*/

$(document).ready(function(){
    $(document).click(function(){
        $("#ajax_response").fadeOut('slow');
    });
    $("#Zone_area").focus();
    var offset = $("#Zone_area").offset();
    var width = $("#Zone_area").width()-2;
    $("#ajax_response").css("left",offset);
    $("#ajax_response").css("width","15%");
    $("#ajax_response").css("z-index","1");
    $("#Zone_area").keyup(function(event){
         //alert(event.keyCode);
         var keyword = $("#Zone_area").val();
         if(keyword.length)
         {
             if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13)
             {
                 $("#loading").css("visibility","visible");
                 $.ajax({
                   type: "POST",
                   url: "load_zone_area.php",
                   data: "data="+keyword,
                   success: function(msg){   
                    if(msg != 0)
                      $("#ajax_response").fadeIn("slow").html(msg);
                    else
                    {
                      $("#ajax_response").fadeIn("slow");   
                      $("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
                    }
                    $("#loading").css("visibility","hidden");
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
                      $("li").each(function(){
                         if($(this).attr("class") == "selected")
                            found = 1;
                      });
                      if(found == 1)
                      {
                        var sel = $("li[class='selected']");
                        sel.next().addClass("selected");
                        sel.removeClass("selected");
                      }
                      else
                        $("li:first").addClass("selected");
                     }
                 break;
                 case 38:
                 {
                      found = 0;
                      $("li").each(function(){
                         if($(this).attr("class") == "selected")
                            found = 1;
                      });
                      if(found == 1)
                      {
                        var sel = $("li[class='selected']");
                        sel.prev().addClass("selected");
                        sel.removeClass("selected");
                      }
                      else
                        $("li:last").addClass("selected");
                 }
                 break;
                 case 13:
                    $("#ajax_response").fadeOut("slow");
                    $("#Zone_area").val($("li[class='selected'] a").text());
                 break;
                }
             }
         }
         else
            $("#ajax_response").fadeOut("slow");
    });
    $("#ajax_response").mouseover(function(){
        $(this).find("li a:first-child").mouseover(function () {
              $(this).addClass("selected");
        });
        $(this).find("li a:first-child").mouseout(function () {
              $(this).removeClass("selected");
        });
        $(this).find("li a:first-child").click(function () {
              $("#Zone_area").val($(this).text());
              $("#ajax_response").fadeOut("slow");
        });
    });
});
/* End auto ajax value load code*/
</script>

<?php
$Header="New Installation";

$date=date("Y-m-d H:i:s");
$account_manager=$_SESSION['username'];
/*$action=$_GET['action'];
$id=$_GET['id'];
$page=$_POST['page'];
if($action=='edit' or $action=='editp')
    {
        $Header="Edit Installation";
        $result=mysql_fetch_array(mysql_query("select * from installation where id=$id and branch_id=".$_SESSION['BranchId']));   
       
        $Zone_area = $result["Zone_area"];
        $area = mysql_fetch_array(mysql_query("SELECT id,`name` FROM re_city_spr_1 WHERE id='".$Zone_area."'"));
        //$_POST["time"]=$result['time'];
    }*/
   
?>

<div class="top-bar">
<h1><? echo $Header;?></h1>
</div>
<div class="table">
<?
if(isset($_POST['submit']))
{
   
    $date=date("Y-m-d H:i:s");
    $account_manager=$_SESSION['username'];
    $sales_person=$_POST['sales_person'];
    $main_user_id=$_POST['main_user_id'];
    $company=$_POST['company'];
    $no_of_vehicals=$_POST['no_of_vehicals'];
    //$location=$_POST['location'];
    $model=$_POST['model'];
    $cnumber=$_POST['cnumber'];
    $contact_person=$_POST['contact_person'];
    $atime_status=$_POST['atime_status'];
    $back_reason=$_POST['back_reason'];
    $comment = $_POST['comment'];
    $branch_type = $_POST['inter_branch'];
    $instal_reinstall = $_POST['instal_reinstall'];
       
    if($_SESSION['BranchId']==1 && $branch_type == "Samebranch" && $instal_reinstall == "installation")
    {
        $installation_status=8;
    }
    elseif($branch_type == "Interbranch" && $_POST['inter_branch_loc']==1 && $instal_reinstall == "installation")
    {
        $installation_status=8;
    }
    else{
        $installation_status=1;
    }


    $dimts=$_POST['dimts'];
        if($dimts=="") { $dimts="no"; }
    $demo=$_POST['demo'];
        if($demo=="") { $demo="no"; }
   
    $veh_type=$_POST['veh_type'];
    $immobilizer_type=$_POST['immobilizer_type'];
    $payment_req=$_POST['payment_req'];
    //$contact_person_no=$_POST['contact_person_no'];
   
    $Zone_id = mysql_query("SELECT id,`name` FROM re_city_spr_1 WHERE `name`='".$_POST['Zone_area']."'");
    $zone_count = mysql_num_rows($Zone_id);
    $Zone_data = mysql_fetch_array($Zone_id);
    if($zone_count > 0)
    {
        $Area = $Zone_data["id"];
        $errorMsg = "";
    }
    else
    {
        $errorMsg = "Please Select Type View Listed Area. Not Fill Your Self.";
    }

    if($branch_type == "Interbranch"){
        $city=$_POST['inter_branch_loc'];
        $location="";
    }else{
        $city=0;
        $location=$_POST['location'];
    }

    $required=$_POST['required'];
        if($required=="") { $required="normal"; }
       
        $datapullingtime=$_POST['datapullingtime'];
        $IP_Box=$_POST['IP_Box'];
   
    $fuel_sensor=$_POST['fuel_sensor'];
    $bonnet_sensor=$_POST['bonnet_sensor'];
    $rfid_reader=$_POST['rfid_reader'];
    $speed_alarm=$_POST['speed_alarm'];
    $door=$_POST['door'];
    $temperature=$_POST['temperature'];
    $duty_box=$_POST['duty_box'];
    $panic_button=$_POST['panic_button'];
   
   
    /*if($_SESSION['BranchId']==1 && $branch_type == "Samebranch" && $back_reason=="" && $instal_reinstall == "installation")
    {
        $inst_status=", installation_status=8";
    }
    elseif($branch_type == "Interbranch" && $_POST['inter_branch_loc']==1 && $back_reason=="" && $instal_reinstall == "installation")
    {
        $inst_status=", installation_status=8";
    }
    else{
        $inst_status=", installation_status=1";
    }*/



    if($errorMsg=="")   
    {
        if($atime_status=="Till")
        {
              $time=$_POST['time'];
                       
                    //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
              $sql="INSERT INTO installation_request(`req_date`, `request_by`,sales_person,`user_id`, `company_name`, no_of_vehicals, location,model,time, contact_number,installed_date, status, contact_person, dimts,demo, veh_type,comment, immobilizer_type, payment_req,required, IP_Box,branch_id,installation_status, Zone_area,atime_status,`inter_branch`, branch_type, instal_reinstall, fuel_sensor, bonnet_sensor, rfid_reader, speed_alarm, door_lock_unlock, temperature_sensor, duty_box, panic_button) VALUES('".$date."','".$account_manager."','".$sales_person."', '".$main_user_id."', '".$company."','".$no_of_vehicals."','".$location."','".$model."','".$time."','".$cnumber."',now(),1,'".$contact_person."','".$dimts."','".$demo."','".$veh_type."','".$comment."','".$immobilizer_type."','".$payment_req."','".$required."','".$IP_Box."','".$_SESSION['BranchId']."','".$installation_status."','".$Area."','".$atime_status."','".$city."','".$branch_type."','".$instal_reinstall."','".$fuel_sensor."','".$bonnet_sensor."','".$rfid_reader."','".$speed_alarm."','".$door."','".$temperature."','".$duty_box."','".$panic_button."')";
           
             $execute=mysql_query($sql);
             $insert_id = mysql_insert_id();   
            if($installation_status == 1)
            {
                for($N=1;$N<=$no_of_vehicals;$N++)
                {
                    $installation = "INSERT INTO installation(`inst_req_id`, `req_date`, `request_by`,sales_person,`user_id`, `company_name`, no_of_vehicals, location,model,time, contact_number,installed_date, status, contact_person, dimts,demo, veh_type,comment, immobilizer_type, payment_req,required, IP_Box,branch_id,installation_status, Zone_area,atime_status,`inter_branch`, branch_type, instal_reinstall, fuel_sensor, bonnet_sensor, rfid_reader, speed_alarm, door_lock_unlock, temperature_sensor, duty_box, panic_button) VALUES('".$insert_id."','".$date."','".$account_manager."','".$sales_person."', '".$main_user_id."', '".$company."','1','".$location."','".$model."','".$time."','".$cnumber."',now(),1,'".$contact_person."','".$dimts."','".$demo."','".$veh_type."','".$comment."','".$immobilizer_type."','".$payment_req."','".$required."','".$IP_Box."','".$_SESSION['BranchId']."','".$installation_status."','".$Area."','".$atime_status."','".$city."','".$branch_type."','".$instal_reinstall."','".$fuel_sensor."','".$bonnet_sensor."','".$rfid_reader."','".$speed_alarm."','".$door."','".$temperature."','".$duty_box."','".$panic_button."')";
                   
                    $execute_inst=mysql_query($installation);
                }
            }
           
             header("location:installation.php");
        }
           
        if($atime_status=="Between")
        {
            $time=$_POST['time1'];
            $totime=$_POST['totime'];
               
                //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
             $sql="INSERT INTO installation_request(`req_date`, `request_by`,sales_person,`user_id`, `company_name`, no_of_vehicals,location,model, time,totime,contact_number, installed_date, status, contact_person, dimts,demo, veh_type, comment, immobilizer_type,payment_req, required,IP_Box,branch_id, installation_status,Zone_area, atime_status,`inter_branch`, branch_type, instal_reinstall, fuel_sensor, bonnet_sensor, rfid_reader, speed_alarm, door_lock_unlock, temperature_sensor, duty_box, panic_button) VALUES('".$date."','".$account_manager."','".$sales_person."', '".$main_user_id."', '".$company."','".$no_of_vehicals."','".$location."','".$model."','".$time."','".$totime."','".$cnumber."',now(),1,'".$contact_person."','".$dimts."','".$demo."','".$veh_type."','".$comment."','".$immobilizer_type."','".$payment_req."','".$required."','".$IP_Box."','".$_SESSION['BranchId']."','".$installation_status."','".$Area."','".$atime_status."','".$city."','".$branch_type."','".$instal_reinstall."','".$fuel_sensor."','".$bonnet_sensor."','".$rfid_reader."','".$speed_alarm."','".$door."','".$temperature."','".$duty_box."','".$panic_button."')";
           
             $execute=mysql_query($sql);
             $insert_id = mysql_insert_id();   

            if($installation_status == 1)
            {

                for($N=1;$N<=$no_of_vehicals;$N++)
                {
                    $installation = "INSERT INTO installation(`inst_req_id`, `req_date`, `request_by`,sales_person,`user_id`, `company_name`, no_of_vehicals, location,model,time, totime,contact_number,installed_date, status, contact_person, dimts,demo, veh_type,comment, immobilizer_type, payment_req,required, IP_Box,branch_id,installation_status, Zone_area,atime_status,`inter_branch`, branch_type, instal_reinstall, fuel_sensor, bonnet_sensor, rfid_reader, speed_alarm, door_lock_unlock, temperature_sensor, duty_box, panic_button) VALUES('".$insert_id."','".$date."','".$account_manager."','".$sales_person."', '".$main_user_id."', '".$company."','1','".$location."','".$model."','".$time."','".$totime."','".$cnumber."',now(),1,'".$contact_person."','".$dimts."','".$demo."','".$veh_type."','".$comment."','".$immobilizer_type."','".$payment_req."','".$required."','".$IP_Box."','".$_SESSION['BranchId']."','".$installation_status."','".$Area."','".$atime_status."','".$city."','".$branch_type."','".$instal_reinstall."','".$fuel_sensor."','".$bonnet_sensor."','".$rfid_reader."','".$speed_alarm."','".$door."','".$temperature."','".$duty_box."','".$panic_button."')";
                   
                    $execute_inst=mysql_query($installation);
                }     
            }
             header("location:installation.php");
        }
               
    }
}
?>

<script type="text/javascript">

function req_info()
{
   
  var instal_reinstall=document.forms["form1"]["instal_reinstall"].value;
  if (instal_reinstall==null || instal_reinstall=="")
  {
        alert("Please Select Job") ;
        return false;
  }

  if(document.form1.sales_person.value=="")
  {
  alert("Please Select sales person name") ;
  document.form1.sales_person.focus();
  return false;
  }
 
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
  /*else
  var no_of_vehicals=document.form1.no_of_vehicals.value;
  if(no_of_vehicals!="")
        {
       
        if(no_of_vehicals.search(/[^0-9\-()+]/g) != -1 )
        {
        alert('Please enter No. of Vehicals');
        document.form1.no_of_vehicals.focus();
        document.form1.no_of_vehicals.value="";
        return false;
        }
       }*/
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
    if ((location==null || location=="") && barnch=="Samebranch")
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

    if(document.form1.model.value=="")
      {
      alert("Please Enter Model") ;
      document.form1.model.focus();
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
   
    if(document.form1.cnumber.value=="")
    {
    alert("Please Enter Contact No.") ;
    document.form1.cnumber.focus();
    return false;
    }
    var cnumber=document.form1.cnumber.value;
    if(cnumber!="")
        {
    var lenth=cnumber.length;
   
        if(lenth < 10 || lenth > 12 || cnumber.search(/[^0-9\-()+]/g) != -1 )
        {
        alert('Please enter valid mobile number');
        document.form1.cnumber.focus();
        document.form1.cnumber.value="";
        return false;
        }
     }
    if(document.form1.contact_person.value=="")
    {
        alert("Please Enter Contact Persion") ;
        document.form1.contact_person.focus();
        return false;
    }
   
    if(document.form1.veh_type.value=="")
    {
        alert("Please Select Vehicle Type") ;
        document.form1.veh_type.focus();
        return false;instal_reinstall
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
   if(radioValue=="Interbranch")
    {
        document.getElementById('branchlocation').style.display = "block";
        document.getElementById('samebranchid').style.display = "none";
    }
    else if(radioValue=="Samebranch")
    {
        document.getElementById('samebranchid').style.display = "block";
        document.getElementById('branchlocation').style.display = "none";
    }
    else
    {
        document.getElementById('branchlocation').style.display = "none";
        document.getElementById('samebranchid').style.display = "none";
    }
   
}           

function StatusBranch12(radioValue)
{
   if(radioValue=="Interbranch")
    {
        document.getElementById('branchlocation').style.display = "block";
        document.getElementById('samebranchid').style.display = "none";
    }
    else if(radioValue=="Samebranch")
    {
        document.getElementById('samebranchid').style.display = "block";
        document.getElementById('branchlocation').style.display = "none";
    }
    else
    {
        document.getElementById('branchlocation').style.display = "none";
        document.getElementById('samebranchid').style.display = "none";
    }
   
}           

/*function enableDisable() {
  if(document.form1.inter_branch.checked){
     document.form1.location.disabled = true;
  } else {
     document.form1.location.disabled = false;
  }
} */
</script>
 <script type="text/javascript">

        $(function () {
             
            $("#datetimepicker").datetimepicker({});
            $("#datetimepicker1").datetimepicker({});
            $("#datetimepicker2").datetimepicker({});
            $("#datetimepicker3").datetimepicker({});
        });

    </script>
   
<?php echo "<p align='left' style='padding-left: 250px;width: 500px;' class='message'>" .$errorMsg. "</p>" ; ?>

<style type="text/css" >
.errorMsg{border:1px solid red; }
.message{color: red; font-weight:bold; }
</style>

<form method="post" action="" name="form1" onSubmit="return req_info();">

    <table style=" padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">
   
        <!--<tr>
            <td align="right">Date</td>
            <td>
              <input type="text" name="date" id="datepicker1" readonly value="<?echo $date;?>" />
              <input type="hidden" name="back_reason" id="back_reason" value="<?=$result['back_reason']?>"/>
              </td>
        </tr>

        <tr>
            <td align="right">Request By: * </td>
            <td>
                <input type="text" name="account_manager" id="TxtAccManager" readonly value="<?echo $account_manager?>"/></td>
        </tr>-->
        <tr>
            <td colspan="2"><input type="hidden" name="back_reason" id="back_reason" value="<?=$result['back_reason']?>"/></td>
        </tr>
       
        <tr>
            <td  align="right">Job:</td>
            <td><input type="radio" name="instal_reinstall"  value="installation" id="instal_reinstall" <?php if($result['instal_reinstall']=='installation') {echo "checked=\"checked\""; }?> /> installation
            <input type="radio" name="instal_reinstall" value="re_install" id="instal_reinstall" <?php if($result['instal_reinstall']=='re_install') {echo "checked=\"checked\""; }?> /> Re-Install </td>
        </tr>

        <tr>
            <td width="46%" height="32" align="right">Required:</td>
            <td width="54%"><input type="checkbox" name="required" id="required" value="urgent" <?php if($result['required']=='urgent') {?> checked="checked" <? }?> /> Urgent
                <input type="checkbox" name="IP_Box" id="IP_Box" value="required" <?php if($result['IP_Box']=='required') {?> checked="checked" <? }?> /> IP Box
                <input type="checkbox" name="dimts" id="dimts" value="yes" <?php if($result['dimts']=="yes")echo "checked";?> /> DIMTS </br>
              <?php if($_SESSION['BranchId'] == 1) { ?>
                <input type="checkbox" name="fuel_sensor" id="fuel_sensor" value="Yes" <?php if($result['fuel_sensor']=='Yes') {?> checked="checked" <? }?> /> Fuel Sensor
                <input type="checkbox" name="bonnet_sensor" id="bonnet_sensor" value="Yes" <?php if($result['bonnet_sensor']=='Yes') {?> checked="checked" <? }?> /> Bonnet Sensor
                </br>
                <input type="checkbox" name="rfid_reader" id="rfid_reader" value="Yes" <?php if($result['rfid_reader']=="Yes")echo "checked";?> /> RFID Reader
                <input type="checkbox" name="speed_alarm" id="speed_alarm" value="Yes" <?php if($result['speed_alarm']=='Yes') {?> checked="checked" <? }?> /> Speed Alarm </br>
                <input type="checkbox" name="door" id="door" value="Yes" <?php if($result['door_lock_unlock']=='Yes') {?> checked="checked" <? }?> /> Door lock/unlock circuit </br>
                <input type="checkbox" name="temperature" id="temperature" value="Yes" <?php if($result['temperature_sensor']=="Yes")echo "checked";?> /> Temperature Sensor </br>
                <input type="checkbox" name="duty_box" id="duty_box" value="Yes" <?php if($result['duty_box']=='Yes') {?> checked="checked" <? }?> /> Duty Box
                <input type="checkbox" name="panic_button" id="panic_button" value="Yes" <?php if($result['panic_button']=="Yes")echo "checked";?> /> Panic Button
			<?php } ?>
            </td>
        </tr>
    
        <tr>
            <td height="32" align="right">Demo:*</td>
            <td>
                    <input type="checkbox" name="demo" id="demo" value="yes"  <? if($result['demo']=="yes")echo "checked";?> />
            </td>
        </tr>
       
        <tr>
            <td align="right">Sales Person:*</td>
            <td>
           
            <select name="sales_person" id="sales_person" style="width:150px">
            <option value="">Select Name</option>
            <?
            $query=mysql_query("select * from sales_person order by name asc");
            while($data=mysql_fetch_array($query)) {
             ?>
            <option value="<?=$data['id']?>" <? if($result['sales_person']==$data['id']) {?> selected="selected" <? } ?> ><?=$data['name']?></option>
            <? } ?>
            </select></td>
        </tr>
       
        <tr>
            <td  align="right">
            Client User Name:*</td>
            <td>
           
            <select name="main_user_id" id="main_user_id"  onchange="showUser(this.value,'ajaxdata'); getCompanyName(this.value,'TxtCompany');">
            <option value="" >-- Select One --</option>
            <?php
            $main_user_iddata=mysql_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE sys_active=1 ORDER BY `name` asc");
            while ($data=mysql_fetch_assoc($main_user_iddata))
            {
                if($data['user_id']==$result['user_id'])
                {
                    $selected="selected";
                }
                else
                {
                    $selected="";
                }
            ?>
           
            <option   value ="<?php echo $data['user_id'] ?>"  <?echo $selected;?>>
            <?php echo $data['name']; ?>
            </option>
            <?php
            }
           
            ?>
            </select>
            </td>
        </tr>
       
        <tr>
            <td  align="right">Company Name:*</td>
            <td><input type="text" name="company" id="TxtCompany" readonly value="<?=$result['company_name']?>"/> </td>
        </tr>
       
        <!--<tr>
            <td height="32" align="right">No. Of Vehicles:*</td>
            <td><input type="text" name="no_of_vehicals" value="<?=$result['no_of_vehicals']?>" id="no_of_vehicals" style="width:147px" autocomplete="off"/></td>
        </tr>-->

		<tr>
			<td height="32" align="right">No. Of Installation:*</td>
			<td><select name="no_of_vehicals" id="no_of_vehicals" style="width:150px">
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
            <td  align="right"> Area:*</td>
            <td> <input type="text" name="Zone_area" id="Zone_area" value="<?php echo $area["name"];?>" /> <div id="ajax_response"></div></td>
        </tr>
       
        <!--<tr>
            <td  align="right">
            Area:*</td>
            <td>
           
            <select name="Zone_area" id="Zone_area" >
            <option value="" >-- Select One --</option>
            <?php
            /*$main_city=mysql_query(" select id,name from re_city_spr_1 order by name asc");
            while($data=mysql_fetch_assoc($main_city))
            {
                if($data['id']==$result['Zone_area'])
                {
                    $selected="selected";
                }
                else
                {
                    $selected="";
                }*/
            ?>
           
            <option value ="<?php //echo $data['id'] ?>"  <?echo $selected;?>>
            <?php //echo $data['name']; ?>
            </option>
            <?php
            //}
           
            ?>
            </select>
            </td>
        </tr>-->
   
         <tr>
          <td  align="right">Branch </td>
          <td>
          <?php $branch = mysql_query("select * from tbl_city_name where branch_id='".$_SESSION['BranchId']."'",$dblink2);
                  $branch_data = mysql_fetch_array($branch);
          ?>
          <Input type='radio' Name ='inter_branch' id='inter_branch' value= 'Samebranch' <?php if($result['branch_type']=='Samebranch'){echo "checked=\"checked\""; }?> onchange="StatusBranch(this.value);"> <?php echo $branch_data["city"];?>
         
          <Input type='radio' Name ='inter_branch' id='inter_branch' value= 'Interbranch' <?php if($result['branch_type']=='Interbranch'){echo "checked=\"checked\""; }?>
        onchange="StatusBranch(this.value);"> Inter Branch
            </td>
             <td colspan="2">
               
                <table  id="samebranchid"  align="left"  style="width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>       
                        <td  align="right">Location:*</td>
                        <td  ><input type="text" name="location"  id="location"   style="width:147px" value="<?=$result['location']?>"/></td>
                    </tr>
                </table>
               
                <table  id="branchlocation"  align="left"  style="width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>
                        <td  align="right">Branch Location</td>
                        <td>
                          <select name="inter_branch_loc" id="inter_branch_loc">
                                <option value="" >-- Select One --</option>
                                <?php
                                $city1=mysql_query("select * from tbl_city_name where branch_id!='".$_SESSION['BranchId']."'",$dblink2);
                                while($data=mysql_fetch_assoc($city1))
                                {
                                    if($data['branch_id']==$result['inter_branch'])
                                    {
                                        $selected="selected";
                                    }
                                    else
                                    {
                                        $selected="";
                                    }
                                ?>
                               
                                <option value ="<?php echo $data['branch_id'] ?>"  <?echo $selected;?>>
                                <?php echo $data['city']; ?>
                                </option>
                                <?php
                                }
                               
                                ?>
                          </select>
                         </td>
                     </tr>
                </table>
            </td>
        </tr>
       
        <!--<tr>
               <td colspan="2">
               
                <table  id="samebranchid"  align="left"  style="padding-left: 50px;width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>       
                        <td  align="right">Location:*</td>
                        <td  ><input type="text" name="location"  id="location"   style="width:147px" value="<?=$result['location']?>"/></td>
                    </tr>
                </table>
               
                <table  id="branchlocation"  align="left"  style="padding-left: 30px;width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>
                        <td  align="right">Branch Location</td>
                        <td>
                          <select name="inter_branch_loc" id="inter_branch_loc">
                                <option value="" >-- Select One --</option>
                                <?php
                                $city1=mysql_query("select * from tbl_city_name where branch_id!='".$_SESSION['BranchId']."'",$dblink2);
                                while($data=mysql_fetch_assoc($city1))
                                {
                                    if($data['branch_id']==$result['inter_branch'])
                                    {
                                        $selected="selected";
                                    }
                                    else
                                    {
                                        $selected="";
                                    }
                                ?>
                               
                                <option value ="<?php echo $data['branch_id'] ?>"  <?echo $selected;?>>
                                <?php echo $data['city']; ?>
                                </option>
                                <?php
                                }
                               
                                ?>
                          </select>
                         </td>
                     </tr>
                </table>
            </td>
        </tr>-->
           
       <!-- <tr>
            <td height="32" align="right">Location:*</td><td><input type="text" name="location" id="location" value="<?=$result['location']?>" style="width:147px" /></td>
            </tr>
            <tr>
              <td  align="right">Inter Branch </td>
              <td><Input type='checkbox' Name ='inter_branch' id='inter_branch' value= 'Y' <?php if($rows['inter_branch']!=0){echo "checked=\"checked\""; }?>
            onchange="StatusBranch();enableDisable()">
                </td>
        </tr>
        <tr>
            <td colspan="2">
            <table  id="branchlocation"  align="left"  style="width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
            <tr>
                <td  align="right">Branch Location</td>
                <td>
                  <select name="inter_branch_loc" id="inter_branch_loc" onchange="AreaChange()">
                        <option value="" >-- Select One --</option>
                        <?php
                       /* $city=mysql_query(" select * from tbl_city_name where branch_id!='".$_SESSION['BranchId']."'");
                        while($data=mysql_fetch_assoc($city))
                        {
                            if($data['city']==$city)
                            {
                                $selected="selected";
                            }
                            else
                            {
                                $selected="";
                            }*/
                        ?>
                       
                        <option value ="<?php //echo $data['branch_id'] ?>"  <?echo $selected;?>>
                        <?php //echo $data['city']; ?>
                        </option>
                        <?php
                        //}
                       
                        ?>
                  </select>
                 </td>
               </tr>
              </table>
          </td>
        </tr>-->
        <tr>
            <td height="32" align="right">Model:*</td>
            <td>
           
            <select name="model" id="model" style="width:150px">
            <option value="">Select Model:*</option>
            <?
            $query=mysql_query("select * from device_model");
            while($data=mysql_fetch_array($query)) {
             ?>
            <option value="<?=$data['device_model']?>" <? if($result['model']==$data['device_model']) {?> selected="selected" <? } ?> ><?=$data['device_model']?></option>
            <? } ?>
            </select></td>
        </tr>
        <tr>
            <td align="right">Availbale Time status:*</td>
            <td>
                <select name="atime_status" id="atime_status" style="width:150px" onchange="TillBetweenTime(this.value)">
                    <option value="">Select Status</option>
                    <option value="Till" <? if($result['atime_status']=='Till') {?> selected="selected" <? } ?> >Till</option>
                    <option value="Between" <? if($result['atime_status']=='Between') {?> selected="selected" <? } ?> >Between</option>
                 </select>
           </td>
           <td colspan="2">
       
                <table  id="TillTime" align="left" style="width: 300px;display:none;border:1"  cellspacing="5" cellpadding="5">
                        <tr>
                        <td height="32" align="right">Time:*</td>
                        <td>
                             <input type="text" name="time" id="datetimepicker" value="<?=$result['time']?>" style="width:147px"/>
                              
                             </td>
                        </tr>
                </table>
               
                <table  id="BetweenTime" align="left" style="width: 300px;display:none;border:1"  cellspacing="5" cellpadding="5">
                        <tr>
                        <td height="32" align="right">From Time:*</td>
                        <td>
                             <input type="text" name="time1" id="datetimepicker1" value="<?=$result['time']?>" style="width:147px"/>
                              
                             </td>
                        </tr>
                        <tr>
                        <td height="32" align="right">To Time:*</td>
                        <td>
                             <input type="text" name="totime" id="datetimepicker2" value="<?=$result['totime']?>" style="width:147px"/>
                              
                             </td>
                        </tr>
                </table>
             </td>
        </tr>

        <!--<tr>
            <td colspan="2" align="right">
       
                <table  id="TillTime" align="left" style="padding-left: 75px;width: 240px;display:none;border:1"  cellspacing="5" cellpadding="5">
                        <tr>
                        <td height="32" align="right">Time:*</td>
                        <td>
                             <input type="text" name="time" id="datetimepicker" value="<?=$result['time']?>" style="width:147px"/>
                              
                             </td>
                        </tr>
                </table>
             </td>
        </tr>-->

        <!--<tr>
            <td colspan="2" align="right">
               
                <table  id="BetweenTime" align="left" style="padding-left: 75px;width: 240px;display:none;border:1"  cellspacing="5" cellpadding="5">
                        <tr>
                        <td height="32" align="right">From Time:*</td>
                        <td>
                             <input type="text" name="time1" id="datetimepicker1" value="<?=$result['time']?>" style="width:147px"/>
                              
                             </td>
                        </tr>
                        <tr>
                        <td height="32" align="right">To Time:*</td>
                        <td>
                             <input type="text" name="totime" id="datetimepicker2" value="<?=$result['totime']?>" style="width:147px"/>
                              
                             </td>
                        </tr>
                </table>
             </td>
        </tr>-->

        <tr>
            <td height="32" align="right">Contact No.:*</td>
            <td><input type="text" name="cnumber" value="<?=$result['contact_number']?>" style="width:147px"/></td>
        </tr>
     
        <tr>
            <td height="32" align="right">Contact Person:*</td>
            <td><input type="text" name="contact_person" value="<?=$result['contact_person']?>" style="width:147px"/></td>
        </tr>

        <tr>
            <td height="32" align="right">Vehicle Type:*</td>
            <td>
            <select name="veh_type" id="veh_type" style="width:150px">
            <option value="">Select Vehicle Type:*</option>
            <?
            $query=mysql_query("select * from veh_type");
            while($data=mysql_fetch_array($query)) {
             ?>
            <option value="<?=$data['veh_type']?>" <? if($result['veh_type']==$data['veh_type']) {?> selected="selected" <? } ?> ><?=$data['veh_type']?></option>
            <? } ?>
            </select></td>
        </tr>
    
        <tr>
            <td height="32" align="right">Comment:</td>
            <td><textarea rows="5" cols="25"  type="text" name="comment" id="TxtComment" ><?=$result['comment']?></textarea></td>
        </tr>
   
        <tr>
          <td width="579" height="32" align="right">Immobilizer:*</td>
          <td width="721">
            <input type="radio" name="group1" value='immobilizer_type_yes' <? if($result['immobilizer_type']!='')echo "checked";?> onClick="setVisibility('sub4', 'block');";>Yes
            <input type="radio" name="group1" value='immobilizer_type_no' <? if($result['immobilizer_type']=='')echo "checked";?> onClick="setVisibility('sub4', 'none');";>No
          </td>
        </tr>
   
        <tr>
          <td colspan="2">
               
                <table  id="sub4"  align="left"  style="padding-left:25px; width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>       
                        <td  align="right">Immobilizer Type*</td>
                        <td><select name="immobilizer_type">
                                <option value="">Select Type</option>
                                <option value="12V"<? if($result['immobilizer_type']=="12V")echo "selected"?>>12V</option>
                                <option value="24V" <? if($result['immobilizer_type']=="24V")echo "selected"?>>24V</option>
                            </select>
                        </td>
   
                    </tr>
                </table>
            </td>
        </tr>
       
        <tr>
            <td ><div align="right">Payment:*</div></td>
              <td  >
              <input type="radio" name="group2" value='payment_req_yes' <? if($result['payment_req']!='')echo "checked";?>  onClick="setVisibility('sub3', 'block');";>Yes
                <input type="radio" name="group2" value='payment_req_no' <? if($result['payment_req']=='')echo "checked";?>   onClick="setVisibility('sub3', 'none');";>No
            </td>
        </tr>
       
        <tr>
          <td colspan="2">
               
                <table  id="sub3"  align="left"  style="padding-left:25px; width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>       
                        <td  align="right">Amount*:</td>
                        <td  ><input type="text" name="payment_req" maxlength="500" value="<?php echo $result['payment_req'];?>" /></td>
                    </tr>
                </table>
            </td>
        </tr>
           
        <!--<tr>
            <td height="32" align="right">IP Box:</td>
            <td><input type="checkbox" name="IP_Box" id="IP_Box" value="required" <?php if($result['IP_Box']=='required') {?> checked="checked" <? }?> /></td>
        </tr>-->

        <tr>
            <td height="32" align="right"><input type="submit" name="submit" id="button1" value="submit" align="right" /></td>
            <td><input type="button" name="Cancel" value="Cancel" onClick="window.location = 'installation.php' " /></td>
        </tr>
      </table>
   </form>
 </div>


<?
include("../include/footer.inc.php");

?>
<script>StatusBranch12("<?=$result['branch_type'];?>");TillBetweenTime12("<?=$result['atime_status'];?>");</script> 