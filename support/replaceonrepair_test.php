<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");
include($_SERVER["DOCUMENT_ROOT"]."/service/sqlconnection.php");*/

if(isset($_POST["submit"]))
{
        if($_POST["replace_within3"]!="")
        {
            $NewDevice_imei=$_POST["replace_within3"];
            $mailTrue="false";
            $errorMsg = "";
        }
        elseif($_POST["replace_within6"]!="")
        {
            $NewDevice_imei=$_POST["replace_within6"];
            $mailTrue="true";
            $device_age="is more than 3 months to 6 months";
            $errorMsg = "";
        }
        elseif($_POST["replace_within12"]!="")
        {
            $NewDevice_imei=$_POST["replace_within12"];
            $mailTrue="true";
            $device_age="is more than 6 months to 12 months";
            $errorMsg = "";
        }
        elseif($_POST["replace_above12"]!="")
        {
            $NewDevice_imei=$_POST["replace_above12"];
            $mailTrue="true";
            $device_age="is more than 12 months";
            $errorMsg = "";
        }
        elseif($_POST["replace_withother"]!="")
        {
            $device_data = explode("-",$_POST["replace_withother"]);
            $NewDevice_imei = $device_data[0];
            $age = $device_data[1];
            $device_model = $device_data[2];
            $mailTrue="true";
            $device_age=" is $age Months and Replace With Other Device Model - $device_model";
            $errorMsg = "";
        }
        else
        {
            $errorMsg = "Please select Device Replace with";
            //die;
        }
   
    if($errorMsg=="")   
    {
        $client_name = $_POST['client_name'];
        $new_device_id = $_POST["device_id"];
        $device_imei = $_POST["device_imei"];
        $new_veh_no = $_POST["veh_no"];
        $updated_by = $_SESSION['username'];
        $reason = str_replace("'","",$_POST["TxtComment"]);
       
    $ffcrslt = mssql_query("select * from device_replace_ffc where imei_no='".$NewDevice_imei."'");
   
    $count = mssql_num_rows($ffcrslt);
   
    if($count>0)
        {
            echo "update device_replace_ffc set new_device_id='".$new_device_id."', replace_device_imei='".$device_imei."', new_client_name='".$client_name."', new_veh_no='".$new_veh_no."', replaced_date='".date("Y-m-d H:i:s")."', ffc_reason='".$reason."', updated_by='".$updated_by."',status='69'  where imei_no='".$NewDevice_imei."'";
           
            /*$update_inventory = mssql_query("update device_replace_ffc set new_device_id='".$new_device_id."', replace_device_imei='".$device_imei."', new_client_name='".$client_name."', new_veh_no='".$new_veh_no."', replaced_date='".date("Y-m-d H:i:s")."', ffc_reason='".$reason."', updated_by='".$updated_by."',status='69'  where imei_no='".$NewDevice_imei."'");*/
        }
        else
        {
           $device_query = mysql_query("SELECT sys_added_date,TIMESTAMPDIFF( DAY, sys_added_date, NOW()) as age, veh_reg,imei,sys_group_id,name FROM matrix.group_services LEFT JOIN matrix.services ON group_services.sys_service_id=services.id LEFT JOIN matrix.`group` ON `group`.id=group_services.sys_group_id
LEFT JOIN  matrix.devices ON services.sys_device_id =devices.id WHERE devices.imei ='".$NewDevice_imei."' AND sys_parent_group_id=1 AND sys_group_id!=1998 LIMIT 0,1",$dblink);
		$device_warranty = mysql_fetch_assoc($device_query);
           
        echo "insert into device_replace_ffc(old_client_name, imei_no, old_veh, ffc_date, imei_old_installtion_date, new_device_id, replace_device_imei, new_client_name, new_veh_no, replaced_date, updated_by, status, ffc_reason) values('".$device_warranty['name']."','".$NewDevice_imei."','".$device_warranty['veh_reg']."','".date("Y-m-d H:i:s")."','".$device_warranty['sys_added_date']."','".$new_device_id."','".$device_imei."','".$client_name."','".$new_veh_no."','".date("Y-m-d H:i:s")."','".$updated_by."','69','".$reason."')";
           
            /*$insert_inventory = mssql_query("insert into device_replace_ffc(old_client_name, imei_no, old_veh, ffc_date, imei_old_installtion_date, new_device_id, replace_device_imei, new_client_name, new_veh_no, replaced_date, updated_by, status, ffc_reason) values('".$device_warranty['name']."','".$NewDevice_imei."','".$device_warranty['veh_reg']."','".date("Y-m-d H:i:s")."','".$device_warranty['sys_added_date']."','".$new_device_id."','".$device_imei."','".$client_name."','".$new_veh_no."','".date("Y-m-d H:i:s")."','".$updated_by."','69','".$reason."')");*/
        }
       
    echo "update device set device_status=69,dispatch_branch=1 where device_imei='".$NewDevice_imei."'";
    echo "update device set device_status=98,is_permanent=1 where device_imei='".$device_imei."'";
   
    /*mssql_query("update device set device_status=69,dispatch_branch=1 where device_imei='".$NewDevice_imei."'");
   
    mssql_query("update device set device_status=98,is_permanent=1 where device_imei='".$device_imei."'");*/
   
    if($mailTrue=="true")
        {
       
        $to  = 'harish@g-trac.in';
        /*$to  = 'anuj@g-trac.in';
        $cc  = 'priya@g-trac.in';*/
        $subject = 'Replace with FFC Device, Client - '.$client_name;
        $message = $_POST["device_imei"].' Device has been replaced with '.$NewDevice_imei.' device.<br/><br/>
                Client name - '.$client_name.'<br/>
                Client Device IMEI - '.$device_imei.'<br/>
                Client Device Model - '.$_POST["device_model"].'<br/>
                Client Device Installed Date - '.$_POST["Installdate"].'<br/>
                Veh. no - '.$new_veh_no.'<br/>
                Client device age - '.$_POST["device_age"].'<br/>
                Replace Device age '.$device_age.' of client device.<br/>';
   
        $message .= 'Reason: '.$reason;
        $headers = 'From: info@g-trac.in' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        Sendmail($subject,$message,$to,$cc);
        //mail($to, $subject, $message, $headers);
        }
   die;
     header("location:show_repairs.php");
  }
 
}
?>
 
<div class="top-bar">
<h1>Replace On Repair</h1>
</div>
<div class="table">

<?

if($_GET["edit"]==true && $_GET["rowid"]!='')
{
       
/*$query=mssql_query("select id,device_repair.client_name ,veh_no ,device_repair.device_imei,DATEDIFF(MONTH, device.recd_date, GETDATE()) as age ,opencase_date ,
closecase_date,actual_problem,spare_cost,device.device_status,device_repair.device_removed_date
 from device_repair left join device on device_repair.device_id=device.device_id
  WHERE device_repair.id='".$_GET["rowid"]."'");*/
 
  $query=mssql_query("select id,device_repair.client_name ,veh_no ,device_repair.device_imei,DATEDIFF(MONTH, device.recd_date, GETDATE()) as age ,opencase_date ,
closecase_date,problem,spare_cost,device.device_status,device_repair.device_removed_date,device_type,item_name
 from device_repair left join device on device_repair.device_id=device.device_id left join item_master on item_master.item_id=device.device_type  
  WHERE device_repair.id='".$_GET["rowid"]."'");
 
$row=mssql_fetch_array($query);

 $device_model_id = mssql_fetch_array(mssql_query("select parent_id from item_master where item_id='".$row['device_type']."'"));

 $device_query_data = mysql_query("SELECT sys_added_date,TIMESTAMPDIFF(MONTH, sys_added_date, NOW()) as age, veh_reg,imei,sys_group_id,NAME FROM matrix.group_services LEFT JOIN  
									matrix.services ON group_services.sys_service_id=services.id LEFT JOIN matrix.`group` ON `group`.id=group_services.sys_group_id
									LEFT JOIN  matrix.devices ON services.sys_device_id =devices.id
									WHERE devices.imei ='".$row['device_imei']."' AND sys_parent_group_id=1 AND sys_group_id!=1998 LIMIT 0,1",$dblink);
 $device_warranty = mysql_fetch_assoc($device_query_data);

}
?>
<script type="text/javascript">

 
 function validateForm()
{
    var spare_cost=document.forms["myForm"]["spare_cost"].value;
    if (spare_cost==null || spare_cost=="")
      {
      alert("Please enter Spare Cost");
      return false;
      }
     
     var comment=document.forms["myForm"]["TxtComment"].value;
    if (comment==null || comment=="")
      {
      alert("Please enter comment");
      return false;
      }                             
}

function HideShow(Value)
{
   
 if(Value!="")
    {
        document.getElementById('hide_tr').style.display = "none";
        document.getElementById('hide_tr2').style.display = "none";
    }
  else
  {
       document.getElementById('hide_tr').style.display = "block";
       document.getElementById('hide_tr2').style.display = "block";
  }
}


//var Path="http://trackingexperts.com/service/";
 var Path="<?php echo __SITE_URL;?>/"; 
 
function DropdownShow(DeviceModel,sys_added_date)
{
    //alert(sys_added_date);
    /*var device_list = document.myForm.device_model_list[0].checked;
    var device_list1 = document.myForm.device_model_list[1].checked;
 var device_list2 = document.myForm.device_model_list[2].checked;
 var device_list3 = document.myForm.device_model_list[3].checked;
 var device_list4 = document.myForm.device_model_list[4].checked;
 var device_list5 = document.myForm.device_model_list[5].checked;
   
 if(device_list == true || device_list1 == true || device_list2 == true || device_list3 == true || device_list4 == true || device_list5 == true)
    {
    document.getElementById('DropdownShow').style.display = "block";*/
      
    var rootdomain="http://"+window.location.hostname
    var loadstatustext="<img src='"+rootdomain+"/images/icons/other/waiting.gif' />"
    document.getElementById("DropdownShow").innerHTML=loadstatustext;
    $.ajax({
            type:"GET",
            url:Path +"userInfo.php?action=replace_FFC_device",
  
             data:"DeviceModel="+DeviceModel+"&sys_added_date="+sys_added_date,
            success:function(msg){
                
            document.getElementById("DropdownShow").innerHTML = msg;
                          
            }
        });
      /*}
      else
        {
        document.getElementById('DropdownShow').style.display = "none";
        }*/
}
            
</script>

<script type="text/javascript">

    $(function () {
         
        $("#datetimepicker").datetimepicker({});
    });

</script>     
<?php echo "<p align='left' style='padding-left: 250px;width: 500px;' class='message'>" .$errorMsg. "</p>" ; ?>

<style type="text/css" >
.errorMsg{border:1px solid red; }
.message{color: red; font-weight:bold; }
</style>
 
 <form name="myForm" action="" onsubmit="return validateForm()" method="post">


   <table style=" padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">

         <tr>
            <td  align="right">Client Name:</td>
            <td>
                <input type="text" name="client_name" id="client_name" readonly value="<?php echo $row['client_name'];?>" />
                <input type="hidden" name="device_id" id="device_id" value="<?php echo $_GET["rowid"];?>"/>
            </td>
        </tr>

        <tr>
            <td  align="right">Vehicle No:</td>
            <td>
                <input type="text" name="veh_no" id="veh_no" readonly value="<?php echo $device_warranty['veh_reg'];?>"/></td>
        </tr>
        <tr>
            <td  align="right">Device Installed Date:</td>
            <!--<td>
                <input type="text" name="Installdate" id="Installdate" readonly value="<?php
//$date_Install=mysql_fetch_assoc(mysql_query("select sys_created as month from matrix.services where veh_reg='". $row['veh_no']."' ")) ;
//echo $date_Install["month"];
                ?>"/></td>-->
             <td><input type="text" name="Installdate" id="Installdate" readonly value="<?php echo $device_warranty['sys_added_date'];?>"/></td>
        </tr>

       

         <tr>
            <td  align="right">Device Imei:</td>
            <td>
                <input type="text" name="device_imei" id="device_imei" readonly value="<?php echo $row['device_imei'];?>" /></td>
        </tr>
       
        <tr>
            <td  align="right">Device Model:</td>
            <td>
                <input type="text" name="device_model" id="device_model" readonly value="<?php echo $row['item_name'];?>" /></td>
        </tr>
       
        <tr>
            <td  align="right">Device Age:</td>
            <td>
                <input type="text" name="device_age" id="device_age" readonly value="<?php echo $device_warranty['age']." Months";?>" /></td>
        </tr>
       
        <tr>
            <td  align="right">Problem    Device:</td>
            <td>
                <input type="text" name="actual_problem" id="actual_problem" readonly value="<?php echo  $row['problem'] ?>"/></td>
        </tr>
  
        <tr>
            <td  align="right">Remove date:</td>
            <td>
                <input type="text" name="device_removed_date" id="device_removed_date" readonly value="<?php echo $row['device_removed_date'];?>"/></td>
        </tr>


<tr>
            <td  align="right"> Spare Cost:</td>
            <td>
                <input type="text" name="spare_cost" id="spare_cost" value="<?php //echo $row['spare_cost'] ;?>"/></td>
        </tr>


       <?
       /*$replace_within3=mssql_query("SELECT DISTINCT(device.device_imei),device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') AS age
 FROM device WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') < 3 AND  DATEDIFF(MONTH,device.recd_date,
 '".$device_warranty['sys_added_date']."') >=0 AND active_status=1 and device_status='69' and dispatch_branch='".$_SESSION['BranchId']."'");*/
 
       $replace_within3=mssql_query("SELECT DISTINCT(device.device_imei),item_name,device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') AS age
 FROM device left join item_master on item_master.item_id=device.device_type WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') < 3 AND
  DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') >=0 AND active_status=1 and device_status='69' and device_type in(select item_id from item_master where parent_id='".$device_model_id['parent_id']."') and dispatch_branch='".$_SESSION['BranchId']."' order by age asc");
 
       /*$replace_within3=mysql_query("select devices.imei,services.id,services.veh_reg,devices.ffc_status,services.sys_created,group_services.sys_group_id from matrix.services left join matrix.devices on services.sys_device_id=devices.id left join matrix.mobile_simcards on devices.sys_simcard=mobile_simcards.id left join matrix.group_services on group_services.sys_service_id=services.id left join matrix.device_mapping ON services.sys_device_id=device_mapping.device_id where  devices.ffc_status=1 and group_services.sys_group_id!=1998 and sys_created<=(select  DATE_ADD(sys_created, INTERVAL 3 month) as month from matrix.services where veh_reg='".$row['veh_no']."') and sys_created>(select sys_created as month from matrix.services where veh_reg='".$row['veh_no']."')   group by devices.imei,services.id,services.veh_reg ");*/
        $NumberCountin3=mssql_num_rows($replace_within3);
        if($NumberCountin3>0)
        {
        ?>
        <tr>
         <td  align="right">
        Replace with in 3 months :
        </td>
        <td>
         
        <select name="replace_within3" id="replace_within3" onchange="HideShow(this.value)">
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
        <? } ?>
        <?        
        /*$replace_within6=mssql_query("SELECT DISTINCT(device.device_imei),device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') AS age
 FROM device WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') < 6 AND  DATEDIFF(MONTH,device.recd_date,
 '".$device_warranty['sys_added_date']."') >=3 AND active_status=1 and device_status='69' and dispatch_branch='".$_SESSION['BranchId']."'");*/
       
    $replace_within6=mssql_query("SELECT DISTINCT(device.device_imei),item_name,device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') AS age
 FROM device left join item_master on item_master.item_id=device.device_type WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') < 6 AND
  DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') >=3 AND active_status=1 and device_status='69' and device_type in(select item_id from item_master where parent_id='".$device_model_id['parent_id']."') and dispatch_branch='".$_SESSION['BranchId']."' order by age asc");
       
        /*$replace_within6=mysql_query("select devices.imei,services.id,services.veh_reg,devices.ffc_status,services.sys_created,group_services.sys_group_id from matrix.services left join matrix.devices on services.sys_device_id=devices.id left join matrix.mobile_simcards on devices.sys_simcard=mobile_simcards.id left join matrix.group_services on group_services.sys_service_id=services.id left join matrix.device_mapping ON services.sys_device_id=device_mapping.device_id where  devices.ffc_status=1 and group_services.sys_group_id!=1998 and sys_created<=(select  DATE_ADD(sys_created, INTERVAL 6 month) as month from matrix.services where veh_reg='".$row['veh_no']."') and sys_created>(select DATE_ADD(sys_created, INTERVAL 3 month) as month from matrix.services where veh_reg='".$row['veh_no']."')   group by devices.imei,services.id,services.veh_reg ");*/
        $NumberCountin6=mssql_num_rows($replace_within6);
        if($NumberCountin6>0 && $NumberCountin3==0)
        {
        ?>
        <tr>
         <td  align="right">
        Replace with in 6 months :</td>
        <td>
       
        <select name="replace_within6" id="replace_within6" onchange="HideShow(this.value)">
        <option value="" >-- Select One --</option>
        <?php
       
        while ($data=mssql_fetch_assoc($replace_within6))
        {
            if($data['device_imei']==$_POST["replace_within6"])
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
        <? }?>

        <?
        /*$replace_within12=mssql_query("SELECT DISTINCT(device.device_imei),device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') AS age
 FROM device WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') < 12 AND  DATEDIFF(MONTH,device.recd_date,
 '".$device_warranty['sys_added_date']."') >=6 AND active_status=1 and device_status='69' and dispatch_branch='".$_SESSION['BranchId']."'");*/
       
    $replace_within12=mssql_query("SELECT DISTINCT(device.device_imei),item_name,device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') AS age
 FROM device left join item_master on item_master.item_id=device.device_type WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') < 12 AND
  DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') >=6 AND active_status=1 and device_status='69' and device_type in(select item_id from item_master where parent_id='".$device_model_id['parent_id']."') and dispatch_branch='".$_SESSION['BranchId']."' order by age asc");
       
        /*$replace_within12=mysql_query("select devices.imei,services.id,services.veh_reg,devices.ffc_status,services.sys_created,group_services.sys_group_id from matrix.services left join matrix.devices on services.sys_device_id=devices.id left join matrix.mobile_simcards on devices.sys_simcard=mobile_simcards.id left join matrix.group_services on group_services.sys_service_id=services.id left join matrix.device_mapping ON services.sys_device_id=device_mapping.device_id where  devices.ffc_status=1 and group_services.sys_group_id!=1998 and sys_created<=(select  DATE_ADD(sys_created, INTERVAL 12 month) as month from matrix.services where veh_reg='".$row['veh_no']."') and sys_created>(select DATE_ADD(sys_created, INTERVAL 6 month) as month from matrix.services where veh_reg='".$row['veh_no']."')   group by devices.imei,services.id,services.veh_reg ");*/
        $NumberCountin12=mssql_num_rows($replace_within12);
        if($NumberCountin12>0 && $NumberCountin6==0 && $NumberCountin3==0)
        {
        ?>
        <tr>
         <td  align="right">
        Replace with in 12 months :</td>
        <td>
       
        <select name="replace_within12" id="replace_within12" onchange="HideShow(this.value)" >
        <option value="" >-- Select One --</option>
        <?php
      
        while ($data=mssql_fetch_assoc($replace_within12))
        {
            if($data['device_imei']==$_POST["replace_within12"])
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
        <? }?>
       
        <?
        /*$replace_above12=mssql_query("SELECT DISTINCT(device.device_imei),device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') AS age
 FROM device WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') >= 12 AND active_status=1 and device_status='69' and dispatch_branch='".$_SESSION['BranchId']."'");*/
       
    $replace_above12=mssql_query("SELECT DISTINCT(device.device_imei),item_name,device.recd_date, DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') AS age
 FROM device left join item_master on item_master.item_id=device.device_type WHERE is_ffc=1 AND DATEDIFF(MONTH,device.recd_date,'".$device_warranty['sys_added_date']."') >= 12 AND active_status=1 and device_status='69' and device_type in(select item_id from item_master where parent_id='".$device_model_id['parent_id']."') and dispatch_branch='".$_SESSION['BranchId']."' order by age asc");
       
        $NumberCount13=mssql_num_rows($replace_above12);
        if($NumberCount13>0 && $NumberCountin12==0 && $NumberCountin6==0 && $NumberCountin3==0)
        {
        ?>
        <tr>
         <td  align="right">
        Replace with above 12 months :</td>
        <td>
         
       
        <select name="replace_above12" id="replace_above12" onchange="HideShow(this.value)" >
        <option value="" >-- Select One --</option>
        <?php

        while ($data=mssql_fetch_assoc($replace_above12))
        {
            if($data['device_imei']==$_POST["replace_above12"])
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
        <?php } ?>
       
        <tr>
            <td align="right">&nbsp;</td>
            <td>
            <table id="hide_tr">
            <tr>
            <td>
          <?php if($device_model_id['parent_id'] == 79){?>
           <input type="radio" name="device_model_list" id="device_model_list" value="Visiontek" onchange="DropdownShow('69','<?=$device_warranty['sys_added_date'];?>')" />Visiontek
           <input type="radio" name="device_model_list" id="device_model_list" value="Teltonika" onchange="DropdownShow('86','<?=$device_warranty['sys_added_date'];?>')" />Teltonika
           <input type="radio" name="device_model_list" id="device_model_list" value="Atlanta" onchange="DropdownShow('95','<?=$device_warranty['sys_added_date'];?>')" />Atlanta
           <br/>
            <input type="radio" name="device_model_list" id="device_model_list" value="Fleet eye" onchange="DropdownShow('93','<?=$device_warranty['sys_added_date'];?>')"/>Fleet eye
            <input type="radio" name="device_model_list" id="device_model_list" value="Others" onchange="DropdownShow('150','<?=$device_warranty['sys_added_date'];?>')" />Others
           
            <?php } else if($device_model_id['parent_id'] == 69){?>
           
            <input type="radio" name="device_model_list" id="device_model_list" value="Pointer" onchange="DropdownShow('79','<?=$device_warranty['sys_added_date'];?>')" />Pointer
            <input type="radio" name="device_model_list" id="device_model_list" value="Teltonika" onchange="DropdownShow('86','<?=$device_warranty['sys_added_date'];?>')" />Teltonika
            <input type="radio" name="device_model_list" id="device_model_list" value="Atlanta" onchange="DropdownShow('95','<?=$device_warranty['sys_added_date'];?>')" />Atlanta <br/>
            <input type="radio" name="device_model_list" id="device_model_list" value="Fleet eye" onchange="DropdownShow('93','<?=$device_warranty['sys_added_date'];?>')"/>Fleet eye
            <input type="radio" name="device_model_list" id="device_model_list" value="Others" onchange="DropdownShow('150','<?=$device_warranty['sys_added_date'];?>')" />Others
          
            <?php } else if($device_model_id['parent_id'] == 86){?>
           
            <input type="radio" name="device_model_list" id="device_model_list" value="Pointer" onchange="DropdownShow('79','<?=$device_warranty['sys_added_date'];?>')" />Pointer
            <input type="radio" name="device_model_list" id="device_model_list" value="Visiontek" onchange="DropdownShow('69','<?=$device_warranty['sys_added_date'];?>')" />Visiontek
            <input type="radio" name="device_model_list" id="device_model_list" value="Atlanta" onchange="DropdownShow('95','<?=$device_warranty['sys_added_date'];?>')" />Atlanta <br/>
            <input type="radio" name="device_model_list" id="device_model_list" value="Fleet eye" onchange="DropdownShow('93','<?=$device_warranty['sys_added_date'];?>')"/>Fleet eye
            <input type="radio" name="device_model_list" id="device_model_list" value="Others" onchange="DropdownShow('150','<?=$device_warranty['sys_added_date'];?>')" />Others
          
            <?php } else if($device_model_id['parent_id'] == 95){?>
           
            <input type="radio" name="device_model_list" id="device_model_list" value="Pointer" onchange="DropdownShow('79','<?=$device_warranty['sys_added_date'];?>')" />Pointer
            <input type="radio" name="device_model_list" id="device_model_list" value="Visiontek" onchange="DropdownShow('69','<?=$device_warranty['sys_added_date'];?>')" />Visiontek
             <input type="radio" name="device_model_list" id="device_model_list" value="Teltonika" onchange="DropdownShow('86','<?=$device_warranty['sys_added_date'];?>')" />Teltonika <br/>
            <input type="radio" name="device_model_list" id="device_model_list" value="Fleet eye" onchange="DropdownShow('93','<?=$device_warranty['sys_added_date'];?>')"/>Fleet eye
            <input type="radio" name="device_model_list" id="device_model_list" value="Others" onchange="DropdownShow('150','<?=$device_warranty['sys_added_date'];?>')" />Others
          
            <?php } else if($device_model_id['parent_id'] == 93){?>
           
            <input type="radio" name="device_model_list" id="device_model_list" value="Pointer" onchange="DropdownShow('79','<?=$device_warranty['sys_added_date'];?>')" />Pointer
            <input type="radio" name="device_model_list" id="device_model_list" value="Visiontek" onchange="DropdownShow('69','<?=$device_warranty['sys_added_date'];?>')" />Visiontek
             <input type="radio" name="device_model_list" id="device_model_list" value="Teltonika" onchange="DropdownShow('86','<?=$device_warranty['sys_added_date'];?>')" />Teltonika <br/>
            <input type="radio" name="device_model_list" id="device_model_list" value="Atlanta" onchange="DropdownShow('95','<?=$device_warranty['sys_added_date'];?>')" />Atlanta
            <input type="radio" name="device_model_list" id="device_model_list" value="Others" onchange="DropdownShow('150','<?=$device_warranty['sys_added_date'];?>')" />Others
          
           
            <?php } else{?>
           
            <input type="radio" name="device_model_list" id="device_model_list" value="Pointer" onchange="DropdownShow('79','<?=$device_warranty['sys_added_date'];?>')" />Pointer
            <input type="radio" name="device_model_list" id="device_model_list" value="Visiontek" onchange="DropdownShow('69','<?=$device_warranty['sys_added_date'];?>')" />Visiontek
             <input type="radio" name="device_model_list" id="device_model_list" value="Teltonika" onchange="DropdownShow('86','<?=$device_warranty['sys_added_date'];?>')" />Teltonika <br/>
            <input type="radio" name="device_model_list" id="device_model_list" value="Atlanta" onchange="DropdownShow('95','<?=$device_warranty['sys_added_date'];?>')" />Atlanta
            <input type="radio" name="device_model_list" id="device_model_list" value="Fleet eye" onchange="DropdownShow('93','<?=$device_warranty['sys_added_date'];?>')"/>Fleet eye
            <input type="radio" name="device_model_list" id="device_model_list" value="Others" onchange="DropdownShow('150','<?=$device_warranty['sys_added_date'];?>')" />Others
          
            <?php } ?>
            </td>
            </tr>
            </table>
            </td>
       </tr>
      
        <tr>
            <!--<td align="right"> Replace with other Model :</td>-->
            <td colspan="2">
                <table id="hide_tr2">
                    <tr>
                        <td>
                            <div id="DropdownShow">
                           
                             </div>
                         </td>
                     </tr>
                 </table>
            </td>
        </tr>
     
          <tr>  <td  align="right">Comment</td>
              <td> <textarea rows="5" cols="25"  type="text" name="TxtComment" id="TxtComment" ></textarea>
                </td>
         </tr>
        <tr>
        <td><input type="submit" name="submit" value="submit" align="right" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="Cancel" value="Cancel" onClick="window.location = 'show_repairs.php' " /></td>
        </tr>

</table>
</form>
 
<?
include("../include/footer.inc.php");

?>