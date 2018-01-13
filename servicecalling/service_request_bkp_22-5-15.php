<?php
include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");  
?> 
 
<div class="top-bar">
<h1>ADD Service</h1>
</div>
<div class="table"> 

<?

  
//$date=date("Y-m-d H:i:s");
//$account_manager=$_SESSION['username'];
if(isset($_GET["Addservice"]) && $_GET["Addservice"]="true" )
{
	$main_user_id=$_GET['u'];
	$company=$_GET['c'];
	$veh_reg=$_GET['v'];
	$Device_model=$_GET['m'];
	$TxtDeviceIMEI=$_GET['i'];
	$date_of_install=$_GET['d'];
	$Notwoking=$_GET['n'];
}

if(isset($_POST['submit']))
{ 
	$date=date("Y-m-d H:i:s");
	$account_manager=$_SESSION['username'];
	$main_user_id=$_POST['main_user_id'];
	$company=$_POST['company'];
	$veh_reg=$_POST['veh_reg'];
	if($veh_reg=='' || $veh_reg=='0')
	{
		$veh_reg=$_POST['Txtveh_reg'];
	}
	$Device_model=$_POST['Device_model'];
	$TxtDeviceIMEI=$_POST['TxtDeviceIMEI'];
	$date_of_install=$_POST['date_of_install'];
	$Notwoking=$_POST['Notwoking'];
	if($_POST['inter_branch']=="Y"){
		$city=$_POST['inter_branch_loc'];
	}else{
		$city=0;
	}
	 $Zone_area=$_POST['Zone_area'];
	$location=$_POST['location'];
	//$atime=$_POST['datetimepicker'];
	//$atimeto=$_POST['datetimepickerto'];
	$pname=$_POST['pname'];
	$cnumber=$_POST['cnumber'];
	$status=$_POST['status'];
	$required=$_POST['required'];
	$IP_Box=$_POST['IP_Box'];
	$datapullingtime=$_POST['datapullingtime'];
	  $comment=$_POST['TxtComment'];
	$atime_status=$_POST['atime_status'];
	$service_reinstall=$_POST['service_reinstall'];
	
	$sql=mysql_query("SELECT UserName AS sys_username FROM addclient  WHERE Userid='$main_user_id'",$dblink2);
	$row=mysql_fetch_array($sql);
	$username=$row['sys_username'];


// `inst_name`, `inst_cur_location`, `inst_date`, `reason`, `time`, `payment_status`, `amount`, `paymode`, `back_reason`, `close_date`, `pending`, `newpending`, `status`, `newstatus`, `move_vehicles`, `billing`, `payment`, `required`, `datapullingtime`, `IP_Box`, `updated_date`, `pending_closed`, `branch_id`,
if($_GET["edit"]==true && $_GET["rowid"]!='')
{
 if($atime_status=="Till"){
		$time=(isset($_POST["time"])) ? trim($_POST["time"])  : "";
		
    $sql="update `services` set `req_date`='".$date."', `request_by`='".$account_manager."', `name`= '".$username."', `user_id`= '".$main_user_id."', `company_name`='".$company."', `veh_reg`='".$veh_reg."', `device_imei`='".$TxtDeviceIMEI."', `date_of_installation`='".$date_of_install."',  `Notwoking`='".$Notwoking."', `location`='".$location."', `atime`='".$time."',`atime_status`='".$atime_status."', `pname`='".$pname."', `cnumber`= '".$cnumber."', `device_model`='".$Device_model."', `comment`='".$comment."',`required`='".$required."',`IP_Box`='".$IP_Box."',Zone_area='".$Zone_area."',service_reinstall='".$service_reinstall."',service_status=1,`inter_branch`='".$city."' where id='".$_GET["rowid"]."'";
	
 $execute=mysql_query($sql,$dblink2) or die(mysql_error());
	header("location:services.php");
	}
	 if($atime_status=="Between"){
		$time=(isset($_POST["time1"])) ? trim($_POST["time1"])  : "";
		$totime=(isset($_POST["totime"])) ? trim($_POST["totime"])  : "";
		//$time=$_POST['time1'];
		//$totime=$_POST['totime'];
		
		$sql="update `services` set `req_date`='".$date."', `request_by`='".$account_manager."', `name`= '".$username."', `user_id`= '".$main_user_id."', `company_name`='".$company."', `veh_reg`='".$veh_reg."', `device_imei`='".$TxtDeviceIMEI."', `date_of_installation`='".$date_of_install."',  `Notwoking`='".$Notwoking."', `location`='".$location."', `atime`='".$time."', atimeto='".$totime."',`atime_status`='".$atime_status."', `pname`='".$pname."', `cnumber`= '".$cnumber."', `device_model`='".$Device_model."', `comment`='".$comment."',`required`='".$required."',`IP_Box`='".$IP_Box."',Zone_area='".$Zone_area."',service_reinstall='".$service_reinstall."',service_status=1,`inter_branch`='".$city."' where id='".$_GET["rowid"]."'";
	
	$execute=mysql_query($sql,$dblink2) or die(mysql_error());
	header("location:services.php");
	 }
}
else
{
  if($atime_status=="Till"){
		$time=$_POST['time'];

//1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
   $sql="INSERT INTO `services` (`req_date`, `request_by`, `name`, `user_id`, `company_name`, `veh_reg`, `device_imei`, `date_of_installation`,  `Notwoking`, `location`, `atime`, `pname`,atime_status, `cnumber`,`device_model`, `comment`,`required`,`IP_Box`,branch_id,service_status,Zone_area,service_reinstall,`inter_branch`) VALUES ('".$date."','".$account_manager."', '".$username."', '".$main_user_id."', '".$company."', '".$veh_reg."','".$TxtDeviceIMEI."','".$date_of_install."','".$Notwoking."', '".$location."', '".$time."','".$pname."','".$atime_status."', '".$cnumber."', '".$Device_model."', '".$comment."','".$required."','".$IP_Box."','".$_SESSION['BranchId']."',1,'".$Zone_area."','".$service_reinstall."','".$city."');";

$execute=mysql_query($sql,$dblink2) or die(mysql_error());
 header("location:services.php");
 	}
	if($atime_status=="Between"){
		$time=$_POST['time1'];
		$totime=$_POST['totime'];
		
		//1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
   $sql="INSERT INTO `services` (`req_date`, `request_by`, `name`, `user_id`, `company_name`, `veh_reg`, `device_imei`, `date_of_installation`,  `Notwoking`, `location`, `atime`, `atimeto`, `pname`,atime_status, `cnumber`,`device_model`, `comment`,`required`,`IP_Box`,branch_id,service_status,Zone_area,service_reinstall,`inter_branch`) VALUES ('".$date."','".$account_manager."', '".$username."', '".$main_user_id."', '".$company."', '".$veh_reg."','".$TxtDeviceIMEI."','".$date_of_install."','".$Notwoking."', '".$location."', '".$time."','".$totime."','".$pname."','".$atime_status."', '".$cnumber."', '".$Device_model."', '".$comment."','".$required."','".$IP_Box."','".$_SESSION['BranchId']."',1,'".$Zone_area."','".$service_reinstall."','".$city."');";
 
 $execute=mysql_query($sql,$dblink2) or die(mysql_error());
 header("location:services.php");  
	}
}
 
 
}
if($_GET["edit"]==true && $_GET["rowid"]!='')
{
	$query=mysql_query("SELECT * FROM services WHERE id='".$_GET["rowid"]."'",$dblink2);
	$rows=mysql_fetch_array($query);
	
	 
	//$date= $rows["req_date"]; 
	//$account_manager=$rows["request_by"]; 
	$main_user_id=$rows["user_id"]; 
	$company=$rows["company_name"]; 
	$veh_reg=$rows["veh_reg"]; 
	$city=$rows['inter_branch'];
	$Device_model=$rows["device_model"]; 
	$TxtDeviceIMEI=$rows["device_imei"]; 
	$date_of_install=$rows["date_of_installation"]; 
	$Notwoking=$rows["Notwoking"]; 
	$Zone_area=$rows["Zone_area"]; 
	$location=$rows["location"]; 
	$atime=$rows["atime"]; 
	$atimeto=$rows["datetimepickerto"]; 
	$pname=$rows["pname"]; 
	$cnumber=$rows["cnumber"]; 
	$required=$rows['required'];
	$IP_Box=$rows['IP_Box'];
	$atime_status=$rows['atime_status'];
	
	$datapullingtime=$rows["datapullingtime"]; 
	$comment=$rows["comment"]; 
	
	$sql=mysql_query("SELECT Userid AS id,UserName AS sys_username FROM addclient WHERE Userid='$main_user_id' and Branch_id='".$_SESSION['BranchId']."'",$dblink2);
	$row=mysql_fetch_array($sql);
	$username=$row['sys_username'];



}



?>
<script type="text/javascript">

 
 function validateForm()
			{ 
			  var main_user_id=document.forms["myForm"]["main_user_id"].value;
			if (main_user_id==null || main_user_id=="")
			  {
			  alert("Select Username");
			  return false;
			  }
			   /*var veh_reg=document.forms["myForm"]["veh_reg"].value;
			   
			  var Txtveh_reg=document.forms["myForm"]["Txtveh_reg"].value;
			  
			if ((veh_reg==null || veh_reg=="0") && (Txtveh_reg==null || Txtveh_reg==""))
			  {
			  alert("Enter vehicle number");
			  return false;
			  } */
			  
			
			  
			  
			var location=document.forms["myForm"]["Zone_area"].value;
			if (location==null || location=="")
			  {
			alert("Please Select Area") ;
			  return false;
			  }
			 
			  var location=document.forms["myForm"]["location"].value;
			  var branch=document.getElementById('inter_branch').checked;
			if (location==null || location=="" && branch!=true)
			  {
			  alert("Enter location");
			  return false;
			  }
			  var interbranch=document.forms["myForm"]["inter_branch_loc"].value;
			if (branch==true && interbranch=="")
			  {
			  alert("Please select branch location");
			  return false;
			  } 
			 
			 var timestatus=document.forms["myForm"]["atime_status"].value;
			if (timestatus==null || timestatus=="")
			  {
			  alert("Please select Availbale Time");
			  return false;
			  }
			  /*var datetimepicker=document.forms["myForm"]["datetimepicker"].value;
			if (datetimepicker==null || datetimepicker=="")
			  {
			  alert("Enter Available Time");
			  return false;
			  }*/
			  
			  var pname=document.forms["myForm"]["pname"].value;
			if (pname==null || pname=="")
			  {
			  alert("Enter Person Name");
			  return false;
			  }
			  
			  var cnumber=document.forms["myForm"]["cnumber"].value;
			  
			if (cnumber==null || cnumber=="")
			  {
			  alert("Enter Contact Number");
			  return false;
			  }
		   if(cnumber!="")
		   {			   
			var charnumber=cnumber.length;
			if(charnumber < 10 || charnumber > 12 || cnumber.search(/[^0-9\-()+]/g) != -1) {
			  alert("Please enter valid mobile number");
			  document.myForm.cnumber.focus();
			  document.myForm.cnumber.value="";
			  return false;
			  }
			}
			  if (
	myForm.service.checked == false &&
	myForm.re_install.checked == false) 
	{
		alert ('Select the Job!');
		return false;
	} else { 	
		return true;
	}
			
			  
			}

 function TillBetweenTime(radioValue)
{
	
	 
 if(radioValue=="Till")
	{
	document.getElementById('TillTime').style.display = "block";
	document.getElementById('BetweenTime').style.display = "none";
	document.getElementById('TillTime1').style.display = "none";
	document.getElementById('BetweenTime1').style.display = "none";

	}
	else if(radioValue=="Between")
	{
	document.getElementById('TillTime').style.display = "none";
	document.getElementById('BetweenTime').style.display = "block";
	document.getElementById('TillTime1').style.display = "none";
	document.getElementById('BetweenTime1').style.display = "none";
	}
	else
	{
	document.getElementById('TillTime').style.display = "none";
	document.getElementById('BetweenTime').style.display = "none";
	document.getElementById('TillTime1').style.display = "none";
	document.getElementById('BetweenTime1').style.display = "none";
	} 
	
}
function StatusBranch()
{
//alert(document.getElementById('inter_branch'));
 
 if(document.getElementById('inter_branch').checked == true)
	{
	document.getElementById('branchlocation').style.display = "block";

	}
	else
	{
	document.getElementById('branchlocation').style.display = "none";
	} 
	
}			

function enableDisable() { 
  if(document.myForm.inter_branch.checked){ 
     document.myForm.location.disabled = true; 
  } else { 
     document.myForm.location.disabled = false; 
  } 
} 

    </script>


   <script type="text/javascript">

    	$(function () {
    		 
    		$("#datetimepicker").datetimepicker({});
			$("#datetimepicker1").datetimepicker({});
			$("#datetimepicker2").datetimepicker({});
			$("#datetimepicker3").datetimepicker({});
    	});
   </script>      
 
 
 <form name="myForm" action="" onsubmit="return validateForm()" method="post">

 

   <table style=" padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">

        <!-- <tr>
            <td width="112"  align="right">Date*:</td>
            <td width="167">
           <input type="text" name="date" id="datepicker1" readonly value="<?echo $date;?>" /></td>
        </tr>

		<tr>
            <td  align="right">Request By*:</td>
            <td>
                <input type="text" name="account_manager" id="TxtAccManager" readonly value="<?echo $account_manager?>"/></td>
        </tr>-->
        <tr>
         <td  align="right">
        Client User Name*:</td>
        <td> 
        
        <select name="main_user_id" id="TxtMainUserId"  onchange="showUser(this.value,'ajaxdata'); getCompanyName(this.value,'TxtCompany');">
        <option value="" name="main_user_id" id="TxtMainUserId">-- Select One --</option>
        <?php
        $main_user_iddata=mysql_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE sys_active=1",$dblink2);
        while ($data=mysql_fetch_assoc($main_user_iddata))
        {
			if($data['user_id']==$main_user_id)
			{
				$selected="selected";
			}
			else
			{
				$selected="";
			}
        ?>
        
        <option name="main_user_id" value ="<?php echo $data['user_id'] ?>"  <?echo $selected;?>>
        <?php echo $data['name']; ?>
        </option>
        <?php 
        } 
        
        ?>
        </select>
        
        
        
        </td>
        </tr>
        
        
        <tr>
         <td  align="right">
        Company Name*:</td>
        <td><input type="text" name="company" id="TxtCompany" readonly value="<?echo $company?>"/>
        </td>
        </tr>
        
        
       
        <tr>
        <td  align="right">
        Registration No*:</td>
        <td><!-- <input type="value" name="reg_no_of_vehicle_to_move" id="TxtRegNoOfVehicle" /> --> 
        <div id="ajaxdata">
         
        </div> 
        OR
        <input type="text" name="Txtveh_reg" id="Txtveh_reg" value="<?echo $veh_reg?>"/>
        </td>
        </tr>
        <tr>
          <td  align="right">Device Model*:</label></td>
        <td>
        <select name="Device_model" id="Device_model">
        <option value=""  >-- Select One --</option>
        <?php
		
        $device_type=mysql_query("SELECT * FROM `device_type`",$dblink2);
        while ($data=mysql_fetch_assoc($device_type))
        {
			if($data['device_type']==$Device_model)
			{
				$selected="selected";
			}
			else
			{
				$selected="";
			}
        ?>
        
        <option name="Device_model" value="<?php echo $data['device_type'] ?>"  <?echo $selected?>  >
        <?php echo $data['device_type']; ?>
        </option>
        <?php 
        } 
        
        ?>
        </select></td>
        </tr>
        
     
        <tr>
          <td  align="right">Device IMEI*:</label></td>
        <td>
        <input type="text" name="TxtDeviceIMEI" id="TxtDeviceIMEI" readonly value="<?echo $TxtDeviceIMEI?>"/></td>
        </tr>
        
        
        <tr>
         <td  align="right">Date Of Installation*:</label></td>
        <td>
        <input type="text" name="date_of_install" id="date_of_install" readonly value="<?echo $date_of_install?>"/></td>
        </tr>
        
         <tr>
        <td  align="right">
         Not working*: </td>
        <td>
        <input type="text" name="Notwoking" id="Notwoking" readonly value="<?echo $Notwoking?>"/></td>
        </tr>
        
         <tr>
        <td  align="right">
        Area:*</td>
        <td> 
        
        <select name="Zone_area" id="Zone_area" >
        <option value="" >-- Select One --</option>
        <?php
       // $main_city=mysql_query(" select id,name from re_city_spr_1 where region_code='".$_SESSION['BranchId']."'");
        $main_city=mysql_query(" select id,name from re_city_spr_1 ORDER BY name ASC",$dblink2);
        while($data=mysql_fetch_assoc($main_city))
        {
			if($data['id']==$Zone_area)
			{
				$selected="selected";
			}
			else
			{
				$selected="";
			}
        ?>
        
        <option value ="<?php echo $data['id'] ?>"  <?echo $selected;?>>
        <?php echo $data['name']; ?>
        </option>
        <?php 
        } 
        
        ?>
        </select>
        
        
        
        </td>
        </tr> 


<tr>
  <td  align="right">Location:*</td>
<td  ><input type="text" name="location"  id="location"   style="width:147px" value="<?echo $location?>"/></td>
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
                $city=mysql_query(" select * from tbl_city_name where branch_id!='".$_SESSION['BranchId']."'",$dblink2);
                while($data=mysql_fetch_assoc($city))
                {
                    if($data['city']==$city)
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
<tr>
<td align="right">Availbale Time status:*</td><td colspan="2">

<select name="atime_status" id="atime_status" style="width:150px" onchange="TillBetweenTime(this.value)">
<option value="">Select Status</option>
<option value="Till" <? if($atime_status=='Till') {?> selected="selected" <? } ?> >Till</option>
<option value="Between" <? if($atime_status=='Between') {?> selected="selected" <? } ?> >Between</option></select></td></tr>

<tr><td colspan="2" align="right">
		
          <?php if($rows['atime_status']=='Till'){ ?>
            <table  id="TillTime1" align="left" style="width: 320px; border:1"  cellspacing="5" cellpadding="5">
                <tr>
                <td height="32" align="right">Time:*</td>
                <td>
                     <input type="text" name="time" id="datetimepicker3" value="<?=$rows['atime']?>" style="width:147px"/>
                       
                     </td>
                </tr>
             </table>
        <?php }?>
        
        <table  id="TillTime" align="left" style="width: 240px;display:none;border:1"  cellspacing="5" cellpadding="5">
                <tr>
                <td height="32" align="right">Time:*</td>
                <td>
                     <input type="text" name="time" id="datetimepicker" value="<?=$rows['atime']?>" style="width:147px"/>
                       
                     </td>
                </tr>
        </table>
     </td>
</tr>

<tr><td colspan="2" align="right">
		
          <?php if($rows['atime_status']=='Between'){ ?>
            <table  id="BetweenTime" align="left" style="width: 240px; border:1"  cellspacing="5" cellpadding="5">
                <tr>
                <td height="32" align="right">From Time:*</td>
                <td>
                     <input type="text" name="time1" id="datetimepicker1" value="<?=$rows['atime']?>" style="width:147px"/>
                       
                     </td>
                </tr>
                <tr>
                <td height="32" align="right">To Time:*</td>
                <td>
                     <input type="text" name="totime" id="datetimepicker2" value="<?=$rows['atimeto']?>" style="width:147px"/>
                       
                     </td>
                </tr>
             </table>
        <?php }?>
        
        <table  id="BetweenTime" align="left" style="width: 240px;display:none;border:1"  cellspacing="5" cellpadding="5">
                <tr>
                <td height="32" align="right">From Time:*</td>
                <td>
                     <input type="text" name="time1" id="datetimepicker1" value="<?=$rows['atime']?>" style="width:147px"/>
                       
                     </td>
                </tr>
                <tr>
                <td height="32" align="right">To Time:*</td>
                <td>
                     <input type="text" name="totime" id="datetimepicker2" value="<?=$rows['atimeto']?>" style="width:147px"/>
                       
                     </td>
                </tr>
        </table>
     </td>
</tr>

<!--<tr>
  <td  align="right">Available Time:*</td>
<td>
	 <input type="text" name="datetimepicker" id="datetimepicker"   style="width:147px" autocomplete="off" readonly value="<?echo $atime?>"/> </td> </tr>
	-->

<tr>
  <td  align="right">Person Name:*</td>
<td>
	 <input type="text" name="pname" id="pname"   style="width:147px" value="<?echo $pname?>"/> 		
	  	</td>
</tr>
<tr>
  <td  align="right">Contact No:*</td><td colspan="2"><input value="<?echo $cnumber?>" type="text" name="cnumber"   style="width:147px" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/></td>
</tr>
<tr>
  <td  align="right">Required:</td>
<td><input type="checkbox" name="required" id="required" value="urgent"  <?php if($required=='urgent') {?> checked="checked" <? }?>/> Urgent </td>
</tr>
 
<tr>
  <td  align="right">IP Box:</td>
<td><input type="checkbox" name="IP_Box" id="IP_Box" value="required" <?php if($IP_Box=='required') {?> checked="checked" <? }?> /> Required </td>
</tr>

<tr>
  <td  align="right">Job:</td>
<td><input type="checkbox" name="service_reinstall"  value="service" id="service" <?php if($service_reinstall=='service') {?> checked="checked" <? }?> /> Service 
<input type="checkbox" name="service_reinstall" value="re_install" id="re_install" <?php if($service_reinstall=='re_Install') {?> checked="checked" <? }?> /> Re-Install </td>
</tr>


  <tr>  <td  align="right">Comment</td>
			  <td> <textarea rows="5" cols="25"  type="text" name="TxtComment" id="TxtComment" ><?echo $comment?></textarea>
</td>
			  </tr>

<tr>
<td  ><input type="submit" name="submit" value="submit" align="right" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="Cancel" value="Cancel" onClick="window.location = 'services.php' " /></td>
</tr>

</table>
</form>
 
<?
include("include/footer.inc.php");

?>

 