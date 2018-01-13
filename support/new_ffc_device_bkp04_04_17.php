<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); 
include($_SERVER["DOCUMENT_ROOT"]."/service/sqlconnection.php");*/

?>
<div class="top-bar">
    <h1>New FFC</h1>
</div>
<div class="table"> 
<?php


if(isset($_POST["submit"]))
{  
	
	if($_POST["replace_within3"]!="")
	{
			$NewDevice_imei=$_POST["replace_within3"];
			$errorMsg = "";
			/*$mailTrue="false";*/
	}
	elseif($_POST["replace_within6"]!="")
	{
		$NewDevice_imei=$_POST["replace_within6"];
		$errorMsg = "";
		/*$mailTrue="true";
		$device_age="is more than 3 months to 6 months";*/
	}
	elseif($_POST["replace_within12"]!="")
	{
		$NewDevice_imei=$_POST["replace_within12"];
		$errorMsg = "";
		/*$mailTrue="true";
		$device_age="is more than 6 months to 12 months";*/
	}
	elseif($_POST["replace_above12"]!="")
	{
		$NewDevice_imei=$_POST["replace_above12"];
		$errorMsg = "";
		/*$mailTrue="true";
		$device_age="is more than 12 months";*/
	}
	else
	{
		$errorMsg = "Please select Device Replace with";
		//die;
	}
	
	if($errorMsg=="")	
	{	
		$date = $_POST["date"];
		$main_user_id = $_POST['main_user_id'];
		
		$client_name_query = select_query("SELECT UserName AS sys_username FROM addclient WHERE Userid='".$main_user_id."'");
		$client_name = $client_name_query[0]["sys_username"];
		
		$company = $_POST['company'];
		$veh_reg = $_POST['veh_reg'];
		if($veh_reg=='' || $veh_reg=='0')
		{
			$veh_reg=$_POST['Txtveh_reg'];
		}
		
		$reason = $_POST["ffc_reason"];
		$updated_by = $_SESSION['username'];
		
		$ffcrslt = mssql_query("select TOP 1 * from device_replace_ffc where imei_no='".$NewDevice_imei."' order by id desc");
		$ffc_imei_id = mssql_fetch_array($ffcrslt);
		
		$new_device_id_query = mssql_fetch_array(mssql_query("SELECT device_imei,device_id FROM device WHERE device_imei='".$NewDevice_imei."'"));
		$new_device_id = $new_device_id_query["device_id"];
		
	$count = mssql_num_rows($ffcrslt);
	
	if($count>0)
		{
			
			$update_inventory = mssql_query("update device_replace_ffc set new_device_id='".$new_device_id."', replace_device_imei='".$NewDevice_imei."', new_client_name='".$client_name."', new_veh_no='".$veh_reg."', replaced_date='".$date."',ffc_reason='".$reason."', updated_by='".$updated_by."',status='99'  where imei_no='".$NewDevice_imei."' and id='".$ffc_imei_id['id']."'");
		}
		else
		{
       		 $device_warranty = select_query_live("SELECT sys_added_date,TIMESTAMPDIFF( MONTH, sys_added_date, NOW()) as age, veh_reg,imei,sys_group_id,name FROM matrix.group_services LEFT JOIN matrix.services ON group_services.sys_service_id=services.id LEFT JOIN matrix.`group` ON `group`.id=group_services.sys_group_id
LEFT JOIN  matrix.devices ON services.sys_device_id =devices.id WHERE devices.imei ='".$NewDevice_imei."' AND sys_parent_group_id=1 AND sys_group_id!=1998 LIMIT 0,1");
			//$device_warranty = mysql_fetch_assoc($device_query);       		
			
			$insert_inventory = mssql_query("insert into device_replace_ffc(old_client_name, imei_no, old_veh, ffc_date, imei_old_installtion_date, new_device_id, replace_device_imei,  new_client_name, new_veh_no, replaced_date, updated_by, status, ffc_reason) values(".$device_warranty[0]['name']."','".$NewDevice_imei."','".$device_warranty[0]['veh_reg']."','".$date."','".$device_warranty[0]['sys_added_date']."','".$new_device_id."','".$NewDevice_imei."','".$client_name."','".$veh_reg."','".$date."','".$updated_by."','99','".$reason."')");
		}
	
		mssql_query("update device set device_status=99,dispatch_branch='".$_SESSION['BranchId']."' where device_imei='".$NewDevice_imei."'");
		mssql_query("update device_repair set device_status=99,device_removed_branch='".$_SESSION['BranchId']."' where current_record=1 and device_imei='".$NewDevice_imei."'");
		
		echo "<script>document.location.href ='new_ffc_device.php'</script>";
		$errorMsg = "New Device Replace Sucessfully";			
	}
}
?>


<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
$(function() {
$( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });

$( "#datepickercheque" ).datepicker({ dateFormat: "yy-mm-dd" });

});


function validateForm()
{
			
  if(document.myForm.main_user_id.value=="")
  {
	  alert("please Select Client Name") ;
	  return false;
  }
  if(document.myForm.veh_reg.value=="0" && document.myForm.Txtveh_reg.value=="")
  {
	  alert("please Enter Vehicle no.") ;
	  return false;
  }  

   var replace_within3=document.myForm.replace_within3.value;
   var replace_within6=document.myForm.replace_within6.value;
   var replace_within12=document.myForm.replace_within12.value;
   var replace_above12=document.myForm.replace_above12.value;
  
  if(replace_within3 == "" && replace_within6 == "" && replace_within12 == "" && replace_above12 == "")
  {
	  alert("Select replace device");
	  return false;
  }  
  
  if(document.myForm.ffc_reason.value=="")
  {
	  alert("please enter reason") ;
	  document.myForm.ffc_reason.focus();
	  return false;
  }  
  


} 
	
</script>

<?php echo "<p align='left' style='padding-left: 250px;width: 500px;' class='message'>" .$errorMsg. "</p>" ; ?>

<style type="text/css" >
.errorMsg{border:1px solid red; }
.message{color: red; font-weight:bold; }
</style>

 <form name="myForm" action="" onsubmit="return validateForm()" method="post">
 

   <table style=" padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">

         <tr>
            <td align="right">Date:</td>
            <td>
                <input type="text" name="date" id="datepicker1" readonly value="<?= date("Y-m-d H:i:s")?>" /></td>
        </tr>

        <tr>
            <td align="right">
            Client Name:</td>
            <td> 
                <select name="main_user_id" id="TxtMainUserId"  onchange="showUser(this.value,'ajaxdata'); getCompanyName(this.value,'TxtCompany');">
                <option value="" name="main_user_id" id="TxtMainUserId">-- Select One --</option>
                <?php
                $main_user_iddata=select_query("SELECT Userid AS user_id,UserName AS `name` FROM internalsoftware.addclient WHERE sys_active=1 ORDER BY `name` ASC");
               // while ($data=mysql_fetch_assoc($main_user_iddata))
				 for($i=0;$i<count($main_user_iddata);$i++)
                {
                    if($main_user_iddata[$i]['user_id']==$main_user_id)
                    {
                        $selected="selected";
                    }
                    else
                    {
                        $selected="";
                    }
                ?>
                
                <option name="main_user_id" value ="<?php echo $main_user_iddata[$i]['user_id'] ?>"  <?echo $selected;?>>
                <?php echo $main_user_iddata[$i]['name']; ?>
                </option>
                <?php 
                } 
                
                ?>
                </select>
	        </td>
        </tr>
        
        
        <tr>
            <td align="right"> Company Name:</td>
            <td><input type="text" name="company" id="TxtCompany" readonly value="<?echo $company?>"/> </td>
        </tr>
        
         
        <tr>
            <td align="right"> Vehicle No:</td>
            <td><div id="ajaxdata"> </div>
         		OR
       		 <input type="text" name="Txtveh_reg" id="Txtveh_reg" value="<?echo $veh_reg?>"/>
            </td>
        </tr>
        
        <? 
	   /*$replace_within3=mssql_query("SELECT DISTINCT(device.device_imei),device.recd_date, DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') AS age
 FROM device WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') < 3 AND  DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') >=0 AND active_status=1 and device_status='69' and dispatch_branch='".$_SESSION['BranchId']."'");*/
 		
		$replace_within3=mssql_query("SELECT DISTINCT(device.device_imei),item_name,device.recd_date, DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') AS age
 FROM device left join item_master on item_master.item_id=device.device_type WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') < 3 AND 
  DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') >=0 AND active_status=1 and device_status='69' and dispatch_branch='".$_SESSION['BranchId']."' order by age asc");
	   
		/*$NumberCountin3=mssql_num_rows($replace_within3);
		if($NumberCountin3>0)
		{*/
		?>
		<tr>
         <td  align="right">
        Replace with in 3 months :
        </td>
        <td> 
		 
        <select name="replace_within3" id="replace_within3" >
        <option value="" >-- Select One --</option>
        <?php

        while ($data=mssql_fetch_assoc($replace_within3))
        {
			if($data['device_imei']==$_POST["replace_within3"])
			{
				$selected="selected";
			}
			else
			{
				$selected="";
			}
        ?>
        
        <option   value ="<?php echo $data['device_imei'] ?>"  <?echo $selected;?>>
        <?php echo $data['device_imei']." (".$data['item_name']." - ".$data['age']." Month)"; ?>
        </option>
        <?php 
        } 
        
        ?>
        </select>
        
        </td>
        </tr>
		<? //} ?>
		<? 		
		/*$replace_within6=mssql_query("SELECT DISTINCT(device.device_imei),device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') AS age
 FROM device WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') < 6 AND  DATEDIFF(MONTH,device.recd_date,
 '".$device_warranty['sys_added_date']."') >=3 AND active_status=1 and device_status='69' and dispatch_branch='".$_SESSION['BranchId']."'");*/
		
	$replace_within6=mssql_query("SELECT DISTINCT(device.device_imei),item_name,device.recd_date, DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') AS age
 FROM device left join item_master on item_master.item_id=device.device_type WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') < 6 AND 
  DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') >=3 AND active_status=1 and device_status='69' and dispatch_branch='".$_SESSION['BranchId']."' order by age asc");
		
		/*$NumberCountin6=mssql_num_rows($replace_within6);
		if($NumberCountin6>0 && $NumberCountin3==0)
		{*/
		?>
		<tr>
         <td  align="right">
        Replace with in 6 months :</td>
        <td> 
        
        <select name="replace_within6" id="replace_within6" >
        <option value="" >-- Select One --</option>
        <?php
		
        while ($data1=mssql_fetch_assoc($replace_within6))
        {
			if($data1['device_imei']==$_POST["replace_within6"])
			{
				$selected="selected";
			}
			else
			{
				$selected="";
			}
        ?>
        
        <option   value ="<?php echo $data1['device_imei'] ?>"  <?echo $selected;?>>
         <?php echo $data1['device_imei']." (".$data1['item_name']." - ".$data1['age']." Month)"; ?>
        </option>
        <?php 
        } 
        
        ?>
        </select>
        
        </td>
        </tr>
		<? //}?>

		<? 
		/*$replace_within12=mssql_query("SELECT DISTINCT(device.device_imei),device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') AS age
 FROM device WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') < 12 AND  DATEDIFF(MONTH,device.recd_date,
 '".$device_warranty['sys_added_date']."') >=6 AND active_status=1 and device_status='69' and dispatch_branch='".$_SESSION['BranchId']."'");*/
		
	$replace_within12=mssql_query("SELECT DISTINCT(device.device_imei),item_name,device.recd_date, DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') AS age
 FROM device left join item_master on item_master.item_id=device.device_type WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') < 12 AND 
  DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') >=6 AND active_status=1 and device_status='69' and dispatch_branch='".$_SESSION['BranchId']."' order by age asc");
		
		/*$NumberCountin12=mssql_num_rows($replace_within12);
		if($NumberCountin12>0 && $NumberCountin6==0 && $NumberCountin3==0)
		{*/
		?>
		<tr>
         <td  align="right">
        Replace with in 12 months :</td>
        <td> 
        
        <select name="replace_within12" id="replace_within12" >
        <option value="" >-- Select One --</option>
        <?php
       
        while ($data2=mssql_fetch_assoc($replace_within12))
        {
			if($data2['device_imei']==$_POST["replace_within12"])
			{
				$selected="selected";
			}
			else
			{
				$selected="";
			}
        ?>
        
        <option   value ="<?php echo $data2['device_imei'] ?>"  <?echo $selected;?>>
        <?php echo $data2['device_imei']." (".$data2['item_name']." - ".$data2['age']." Month)"; ?>
        </option>
        <?php 
        } 
        ?>
        </select>
        
        </td>
        </tr>
		<? //}?>
        
        <? 
		/*$replace_above12=mssql_query("SELECT DISTINCT(device.device_imei),device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') AS age
 FROM device WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') >= 12 AND active_status=1 and device_status='69' and dispatch_branch='".$_SESSION['BranchId']."'");*/
		
	$replace_above12=mssql_query("SELECT DISTINCT(device.device_imei),item_name,device.recd_date, DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') AS age
 FROM device left join item_master on item_master.item_id=device.device_type WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".date("Y-m-d H:i:s")."') >= 12 AND active_status=1 and device_status='69' and dispatch_branch='".$_SESSION['BranchId']."' order by age asc");
		
		/*$NumberCount13=mssql_num_rows($replace_above12);
		if($NumberCount13>0 && $NumberCountin12==0 && $NumberCountin6==0 && $NumberCountin3==0)
		{*/
		?>
		<tr>
         <td  align="right">
        Replace with above 12 months :</td>
        <td> 
		 
        
        <select name="replace_above12" id="replace_above12" >
        <option value="" >-- Select One --</option>
        <?php

        while ($data3=mssql_fetch_assoc($replace_above12))
        {
			if($data3['device_imei']==$_POST["replace_above12"])
			{
				$selected="selected";
			}
			else
			{
				$selected="";
			}
        ?>
        
        <option   value ="<?php echo $data3['device_imei'] ?>"  <?echo $selected;?>>
        <?php echo $data3['device_imei']." (".$data3['item_name']." - ".$data3['age']." Month)"; ?>
        </option>
        <?php 
        } 
        
        ?>
        </select>
        
       
        
        </td>
        </tr>
		<? //}?>
        <tr>  
        	<td align="right">Reason:</td>
			<td> <textarea rows="5" cols="25"  type="text" name="ffc_reason" id="ffc_reason" ></textarea></td>
		
		</tr>
        <tr>
        	<td align="right"><input type="submit" name="submit" value="submit" /></td>
            <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="Reset" value="Reset" onClick="window.location = 'new_ffc_device.php'" /></td>
        </tr>

	</table>
  </form>
</div>
 
<?php
include("../include/footer.inc.php"); ?>
