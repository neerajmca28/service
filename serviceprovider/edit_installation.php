<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/  

function send_notification ($tokens,$message,$androidkey)
{
    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array(
    'registration_ids' => $tokens,
    'data' => $message
    );
    $headers = array(
    'Authorization:key = '.$androidkey,
    'Content-Type: application/json'
    );   
    $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       return $result;
}

$condition = "";

$Header="Edit Installation";
if($_GET['show']=="backtoservice")
{
    $Header="Back to Installation";
}

   
 
?>

<div class="top-bar">
  <h1><? echo $Header;?></h1>
</div>
<div class="table">
  <?
if(isset($_REQUEST['action'])==edit)
{
    $id=$_GET['id'];
    $setqry="";
    
    if(isset($_GET['newstatus']))
    {
        $newstst = $_GET['newstatus'];
        //$setqry = " , newstatus='1' ";
        $setqry = '1' ;
    }

    $query = select_query("SELECT * FROM installation_request WHERE id='".$id."'");
    // echo '<pre>'; print_r($query); die;
    $approve_install=$query[0]['installation_approve'];
    $inst_req_id=$query[0]["id"];
    $req_date=$query[0]["req_date"];
    $request_by=$query[0]["request_by"];
    $sales_person=$query[0]["sales_person"];
    $user_id=$query[0]["user_id"];
    $company_name=$query[0]["company_name"];
    $location=$query[0]["location"];
    $veh_reg=$query[0]["veh_reg"];
    $newVehReg=explode(",", $veh_reg);
    $device_imei=$query[0]["device_imei"];
    $newDeviceImei=explode(",", $device_imei);
    $no_of_vehicals=$query[0]['no_of_vehicals'];
    $model=$query[0]["model"];
    $time=$query[0]["time"];
    $totime=$query[0]["totime"];
    $installed_date=$query[0]["installed_date"];
    $contact_number=$query[0]["contact_number"];
    $status=1;
    $contact_person=$query[0]["contact_person"];
    $veh_type=$query[0]["veh_type"];
    $required=$query[0]["required"];
    $branch_id=$query[0]["branch_id"];
    $installation_status=2;
    $Zone_area=$query[0]["Zone_area"];
    $atime_status=$query[0]["atime_status"];
    $inter_branch=$query[0]["inter_branch"];
    $branch_type=$query[0]["branch_type"];
    $instal_reinstall=$query[0]["instal_reinstall"];
    $designation=$query[0]["designation"];
    $device_type=$query[0]["device_type"];
    $alter_contact_no=$query[0]["alter_contact_no"];
    $accessories_tollkit=$query[0]["accessories_tollkit"];
    $billing=$query[0]["billing"];
    $TrailerType=$query[0]["TrailerType"];
    $TruckType=$query[0]["TruckType"];
    $MachineType=$query[0]["MachineType"];
    $actype=$query[0]["actype"];
    $standard=$query[0]["standard"];
    $alt_cont_person=$query[0]["alt_cont_person"];
    $alt_designation=$query[0]["alt_designation"];
    $acess_selection=$query[0]["acess_selection"];
    $installation_approve=1;

}
//print_r($rows);

if(isset($_POST['submit']))
{
    //echo "<pre>";print_r($_POST); die;Approved Installations :     
    $inst_name=$_POST['inst_name'];
    $inst_cur_location=$_POST['inst_cur_location'];
    $inst_req_id = $_POST['inst_req'];

    // $billing=$_POST['billing'];
    // if($billing=="") { $billing="no"; }
    // $payment=$_POST['payment'];
    // if($payment=="") { $payment="no"; }

    //echo '<pre>'; print_r($query); die;
/*echo $query1=("UPDATE installation SET  inst_name='$inst_name',inst_cur_location='$inst_cur_location',inst_date='".date("Y-m-d")."',newpending='0',status='0' ".$setqry." WHERE id='$id'");*/
    if( $_POST['assignJob']=="OngoingJob")
    {

       // echo 'tt'; die;

        $ArrInst=explode("#",$_POST['inst_name']);
        $inst_name =$ArrInst[1];
        $inst_id =$ArrInst[0];

        $inst_idname=$_POST['inst_name'];

        //echo $veh_reg1 =$Veh_Reg[1];die;
        $job_type=1;
        
        $Update_installation = array('inst_req_id' => $id,'req_date' => $req_date,'request_by' => $request_by,'veh_reg' => $newVehReg[0],'device_imei' => $newDeviceImei[0],'sales_person' => $sales_person,'user_id' => $user_id,'company_name' => $company_name,'location' => $location,'no_of_vehicals' => $no_of_vehicals,'model' => $model,'time' => $time,'totime' => $totime,'installed_date' => $installed_date,'contact_number' => $contact_number,'contact_person' => $contact_person,'veh_type' => $veh_type,'required' => $required,'branch_id' => $branch_id,'Zone_area' => $Zone_area,'atime_status' => $atime_status,'inter_branch' => $inter_branch,'branch_type' => $branch_type, 'instal_reinstall' => $instal_reinstall,'designation' => $designation,'device_type' => $device_type,'alter_contact_no' => $alter_contact_no,'accessories_tollkit' => $accessories_tollkit,'billing' => $billing,'TrailerType' => $TrailerType,'TruckType' => $TruckType,'MachineType' => $MachineType,'actype' => $actype,'standard' => $standard,'alt_cont_person' => $alt_cont_person,'alt_designation' => $alt_designation,'acess_selection' => $acess_selection,'installation_approve' => 1, 'popup_alertStock' =>'1','inst_name' => $inst_name, 'inst_id' => $inst_id, 'inst_cur_location' => $inst_cur_location, 'newpending' => '0', 'status' => '0', 'job_type' =>  $job_type, 'installation_status' =>  '2');
       
       //echo $approve_install;die;
       //echo '<pre>'; print_r($Update_installation); 
            $tt=insert_query('internalsoftware.installation', $Update_installation);
           //echo $tt; die;

        $Update_inst = array('status' => 1);
        $condition = array('inst_id' => $inst_id);        
        update_query('internalsoftware.installer', $Update_inst, $condition);

        for($i=1;$i< $approve_install;$i++)
        {
          //echo $approve_install; die;
           $job_type1=2;
           $Update_installation = array('inst_req_id' => $id,'req_date' => $req_date,'request_by' => $request_by,'veh_reg' => $newVehReg[$i],'device_imei' => $newDeviceImei[$i],'sales_person' => $sales_person,'user_id' => $user_id,'company_name' => $company_name,'location' => $location,'no_of_vehicals' => $no_of_vehicals,'model' => $model,'time' => $time,'totime' => $totime,'installed_date' => $installed_date,'contact_number' => $contact_number,'contact_person' => $contact_person,'veh_type' => $veh_type,'required' => $required,'branch_id' => $branch_id,'Zone_area' => $Zone_area,'atime_status' => $atime_status,'inter_branch' => $inter_branch,'branch_type' => $branch_type, 'instal_reinstall' => $instal_reinstall,'designation' => $designation,'device_type' => $device_type,'alter_contact_no' => $alter_contact_no,'accessories_tollkit' => $accessories_tollkit,'billing' => $billing,'TrailerType' => $TrailerType,'TruckType' => $TruckType,'MachineType' => $MachineType,'actype' => $actype,'standard' => $standard,'alt_cont_person' => $alt_cont_person,'alt_designation' => $alt_designation,'acess_selection' => $acess_selection,'installation_approve' => 1, 'popup_alertStock' =>'1','inst_name' => $inst_name, 'inst_id' => $inst_id, 'inst_cur_location' => $inst_cur_location, 'newpending' => '0', 'status' => '0', 'job_type' =>  $job_type1, 'installation_status' =>  '2');
           //print_r($Update_installation); die;
           $tt= insert_query('internalsoftware.installation', $Update_installation);
           //echo $tt; die;

        }
    }
    else
    {
        $inst_idname=$_POST['inst_name_all'];
        $ArrInst=explode("#",$_POST['inst_name_all']);
        $inst_name =$ArrInst[1];
        $inst_id =$ArrInst[0];
         
        $job_type1=2;
        for($i=0;$i< $approve_install;$i++)
        {
           //$job_type=2;
           $Update_installation = array('inst_req_id' => $id,'req_date' => $req_date,'request_by' => $request_by,'veh_reg' => $newVehReg[$i],'device_imei' => $newDeviceImei[$i],'sales_person' => $sales_person,'user_id' => $user_id,'company_name' => $company_name,'location' => $location,'no_of_vehicals' => $no_of_vehicals,'model' => $model,'time' => $time,'totime' => $totime,'installed_date' => $installed_date,'contact_number' => $contact_number,'contact_person' => $contact_person,'veh_type' => $veh_type,'required' => $required,'branch_id' => $branch_id,'Zone_area' => $Zone_area,'atime_status' => $atime_status,'inter_branch' => $inter_branch,'branch_type' => $branch_type, 'instal_reinstall' => $instal_reinstall,'designation' => $designation,'device_type' => $device_type,'alter_contact_no' => $alter_contact_no,'accessories_tollkit' => $accessories_tollkit,'billing' => $billing,'TrailerType' => $TrailerType,'TruckType' => $TruckType,'MachineType' => $MachineType,'actype' => $actype,'standard' => $standard,'alt_cont_person' => $alt_cont_person,'alt_designation' => $alt_designation,'acess_selection' => $acess_selection,'installation_approve' => 1, 'popup_alertStock' =>'1','inst_name' => $inst_name, 'inst_id' => $inst_id, 'inst_cur_location' => $inst_cur_location, 'newpending' => '0', 'status' => '0', 'job_type' =>  $job_type1, 'installation_status' =>  '2');
            insert_query('internalsoftware.installation', $Update_installation);
        }
    }
 
    $Update_inst_req = array('installation_status' => 2,'popup_alertStock' => 1,'inst_id' => $inst_id,'inst_name' => $inst_name);
    $condition2 = array('id' => $id);  
    update_query('internalsoftware.installation_request', $Update_inst_req, $condition2);      
   

    $token_get = select_query("select * from internalsoftware.mobilekey where inst_id=".$inst_id);
    
    if(count($token_get)>0)
    {
        $tokens[] = $token_get[0]['AndroidKey'];
         
        $Notificato_msg = array("title" => "Installation Request", "subtitle" => trim($query[0]['company_name'])." Installation are assign you", "installelrid" => $inst_id,   "branchid" => $_SESSION['BranchId']);
        
        $androidkey = "AIzaSyD9pinPICJTK7ibz_5U69QgZVCyjvGa0DU";
        
        $message_status = send_notification($tokens,$Notificato_msg,$androidkey);
    
    }
    
    header("location:new_installation.php");

}
if(isset($_POST['backservice']))
{

    /*$pending=$_POST['pending'];
    $newpending=$_POST['newpending'];*/
    //echo $pending;die();
    $last_reason = $_POST['last_reason'];
    $back_reason=$_POST['reason_to_back'];
    
    /*$pending=mysql_query("UPDATE installation SET pending='1',status='0',newpending='0', installation_status='3',back_reason='".$last_reason."<br/>".$back_reason." - ".date("Y-m-d H:i:s")."' WHERE id='$id'");*/
    
    //mysql_query("update installer set status=1 where inst_name='$inst_name'");
    
    
    $pending_installation = array('pending' => '1', 'status' => '0', 'newpending' => '0', 'installation_status' => '3',
                                  'back_reason' => $last_reason."<br/>".$back_reason." - ".date("Y-m-d H:i:s"));
    $condition4 = array('id' => $id);            
    update_query('internalsoftware.installation_request', $pending_installation, $condition4);
    
    
    header("location:new_installation.php");
}



?>
  <script type="text/javascript">
function req_info(form2)
{

 /* var inst_name=document.getElementById(inst_name)
  if(document.form2.inst_name.value==0)
  {
  alert("please choose one name") ;
  document.form2.inst_name.focus();
  return false;
  }*/
  
  if(document.form2.inst_cur_location.value =="")
  {
   alert("please enter installer current location");
   document.form2.inst_cur_location.focus();
   return false;
   }
}
function req_info1(form2){    
    var reason=ltrim(document.form2.reason_to_back.value);    
   if(reason=="")
  {
   alert("Please Enter Reason To Back Services");
   document.form2.reason_to_back.focus();
   return false;
   }

}
function ltrim(stringToTrim) {
    return stringToTrim.replace(/^\s+/,"");
    }

</script>
  <script type="text/javascript">

        $(function () {
             
            $("#datetimepicker").datetimepicker({});
        });

    </script>

  <form method="post" action="" name="form2">
    <table width="60%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>
        <td><input name="id" type="hidden" id="id" value="<?php echo $query[0]['id'];?>">
          <input name="inst_req" type="hidden" id="inst_req" value="<?php echo $query[0]['inst_req_id'];?>"></td>
          </td>
      </tr>
      <tr>
        <td height="29" align="right">Sales Person:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="sales" id="sales" readonly value="<?php $sales=select_query("select name from sales_person where id='".$query[0]['sales_person']."' "); echo $sales[0]['name'];?>" /></td>
      </tr>
      <tr>
        <td height="29" align="right">Client Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="client" id="client" readonly value="<?php echo trim($query[0]['company_name']);?>" /></td>
      </tr>
      <tr>
        <td align="right">Approved Installations :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="no_of_vehicals" id="no_of_vehicals" readonly value="<?php echo $query[0]['installation_approve'];?>" /></td>
      </tr>
      <tr>
        <td height="32" align="right">Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="location" id="location" readonly value="<?php echo $query[0]['location'];?>" /></td>
      </tr>
  <!--     <tr>
        <td height="32" align="right">Model:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="model" readonly id="model" value="<?php echo $query[0]['model'];?>" /></td>
      </tr> -->
        <!--  <tr>
        <?php $sqlModel=select_query("SELECT device_model FROM device_model where id='".$query[0]["model"]."' ");   ?>
          <td height="32" align="right">Model:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
           <td><input type="text" name="model" readonly id="model" value="<?php echo $sqlModel[0]["device_model"];?>" /></td>
        </tr>
        <?php if($_GET['show']=="close")
        {?>
      <tr>
        <td height="32" align="right">Installed Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="installed_date" id="installed_date" readonly value="<?php echo $query[0]['installed_date'];?>" /></td>
      </tr>
      <?php } ?> -->
     <!--  <tr>
        <td height="32" align="right">DIMTS :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="dimts" id="dimts" readonly value="<?php echo $query[0]['dimts'];?>" /></td>
      </tr>
      <tr>
        <td height="32" align="right">Demo :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="demo" id="demo" readonly value="<?php echo $query[0]['demo'];?>" /></td>
      </tr>
      <tr>
        <td height="32" align="right">Amount :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="payment_req" id="payment_req" readonly value="<?php echo $query[0]['payment_req'];?>" /></td>
      </tr> -->
      <tr>
        <td height="32" align="right">Vehicle Type :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="veh_type" id="veh_type" readonly value="<?php echo $query[0]['veh_type'];?>" /></td>
      </tr>
      <!-- <tr>
        <td height="32" align="right">Immobilizer :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="immobilizer_type" id="immobilizer_type" readonly value="<?php echo $query[0]['immobilizer_type'];?>" /></td>
      </tr> -->
      <tr>
        <td height="32" align="right">Contact No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="contact_number" readonly id="contact_number" value="<?php echo $query[0]['contact_number'];?>" /></td>
      </tr>
      <? if($_GET['show']=="edit")
    {?>
      <tr>
        <td colspan="2" align="center" ><input type="radio" value="OngoingJob" id="assignJob" name="assignJob" onclick="document.getElementById('inst_name').style.display = 'block';document.getElementById('inst_name_all').style.display = 'none';">
          Ongoing Job
          <input type="radio" value="PendingJob" id="assignJob" name="assignJob" onclick="document.getElementById('inst_name').style.display = 'none';document.getElementById('inst_name_all').style.display = 'block';">
          Pending Job </td>
      </tr>
      <tr>
        <td height="32" align="right">Installer Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><?php
         
        $inst_query = select_query("SELECT inst_id,inst_name,concat(inst_id,'#',inst_name) as idname FROM installer where is_delete=1 and status=0 and branch_id=".$_SESSION['BranchId']);
        //$result=mysql_query($query);
        
        ?>
          <? //$name1=$row['inst_id']."#".$row['inst_name']; ?>
          <select name="inst_name" id="inst_name" style="display:none"  >
            <option value="0">Select Name</option>
            <? for($inst=0;$inst<count($inst_query);$inst++) { ?>
            <option value=<?=$inst_query[$inst]['idname'];?>>
            <?=$inst_query[$inst]['inst_name']?>
            </option>
            <? } ?>
          </select>
          <?php
         
        $inst_query2 = select_query("SELECT inst_id,inst_name,concat(inst_id,'#',inst_name) as idname FROM installer where is_delete=1 and branch_id=".$_SESSION['BranchId']);
        //$result=mysql_query($query);
        
        ?>
          <select name="inst_name_all" id="inst_name_all"  style="display:none" >
            <option value="0">Select Name</option>
            <? for($nt=0;$nt<count($inst_query2);$nt++) { ?>
            <option value=<?=$inst_query2[$nt]['idname'];?>>
            <?=$inst_query2[$nt]['inst_name']?>
            </option>
            <? } ?>
          </select></td>
      </tr>
   <!--    <tr>
        <td>&nbsp;</td>
        <td><input type="checkbox" name="billing" id="billing" value="yes" />
          Bill Delivery
          <input type="checkbox" name="payment" id="payment" value="yes" />
          Collect Payment </td>
      </tr> -->
      <tr>
        <td height="32" align="right">Installer Current Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="inst_cur_location" id="inst_cur_location" value="<?php echo $query[0]['inst_cur_location'];?>" /></td>
      </tr>
      <? }
     if($_GET['show']=="backtoservice")
    {?>
      <tr>
        <td height="32" align="right">Reason To Back Services:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><textarea name="reason_to_back" id="reason_to_back" rows="5" cols="15"><?php echo $query[0]['back_reason'];?></textarea>
          <input type="hidden" name="last_reason" value="<?php echo $query[0]['back_reason'];?>" /></td>
      </tr>
      <?}?>
      <tr>
        <? if($_GET['show']=="edit")
    {?>
        <td height="32" align="right"><input type="submit" name="submit" value="submit" align="right" onClick="return req_info(form2)"/>
          <?}?>
          &nbsp;&nbsp;</td>
        <td height="32" align="right">&nbsp;&nbsp;
          <input type="button" name="Cancel" value="Cancel" onClick="window.location = 'new_installation.php' " />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <? if($_GET['show']=="backtoservice")
        {?>
          <input type="submit" name="backservice" value="back to service" align="right" onClick="return req_info1(form2)" />
          <? } ?></td>
      </tr>
        </tr>
      
    </table>
  </form>
  <?
include("../include/footer.inc.php");

?>