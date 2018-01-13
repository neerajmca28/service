<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");  */

//print_r($_SESSION);die;
$branch_id=$_SESSION['branch_id]'];
function send_notification ($tokens,$message,$androidkey)
{
  //echo '<pre>'; print_r($message); die;
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
 
$Header="Edit Service";
if($_GET['show']=="backtoservice")
{
    $Header="Back to Service";
}
  

$date=date("Y-m-d H:i:s");
$account_manager=$_SESSION['username'];
if(isset($_REQUEST['action'])==edit)
{
    $id=$_GET['id'];
    $mode=$_GET['mode'];
    $setqry="";
    if(isset($_GET['newstatus']))
    {
        $newstst=$_GET['newstatus'];
        //$setqry=" , newstatus='1'";
        $setqry = '1' ;
    }

?>

<div class="top-bar">
  <h1><? echo $Header;?></h1>
</div>
<div class="table">
  <?
if($mode=='crack')
{
    $query = select_query("SELECT * FROM services_crack WHERE id='$id'");
    //echo '<pre>'; print_r($query); die;
    $crack_req_id=$query[0]['id'];
    $req_date=$query[0]['req_date'];
    $request_by=$query[0]['request_by'];
    $service_reinstall=$query[0]['service_reinstall'];
    $zone_area=$query[0]['Zone_area'];
    $branch_id=$query[0]['branch_id'];
    $no_of_vehicals=$query[0]['no_of_vehicals'];
    $device_imei_crack=$query[0]['device_imei'];
    $company_name_crack=$query[0]['company_name'];
    $veh_reg_crack=$query[0]['veh_reg'];
    $location_crack=$query[0]['location'];

    $pname_crack =$query[0]['pname'];
    $cnumber_crack=$query[0]['cnumber'];  
    $atime_status_crack=$query[0]['atime_status']; 
    $designation_crack =$query[0]['designation'];
    $alter_contact_no_crack=$query[0]['alter_contact_no'];  
    $alt_designation_crack=$query[0]['alt_designation']; 
    $alt_cont_person_crack=$query[0]['alt_cont_person']; 
    $atime_crack=$query[0]['atime']; 
    $atimeto_crack=$query[0]['atimeto']; 

}
else
{
    $query = select_query("SELECT * FROM services WHERE id='$id'");
}

  
    
    //$rows=mysql_fetch_array($query);
   //echo '<pre>';  print_r($query); die;
    
}
//print_r($rows);

if(isset($_POST['submit']))
{
    $name=$_POST['name'];
    $veh_reg=$_POST['veh_reg'];
    $Notwoking=$_POST['Notwoking'];
    $location=$_POST['location'];
    $atime=$_POST['atime'];
    $pname=$_POST['pname'];
    $cnumber=$_POST['cnumber'];
    
    $inst_cur_location=$_POST['inst_cur_location'];
    $newpending=$_POST['newpending'];
    $status=$_POST['status'];
    // $billing=$_POST['billing'];
    // if($billing=="") { $billing="no"; }
    // $payment=$_POST['payment'];
    // if($payment=="") { $payment="no"; }

        
        if($mode=='crack')
        {
          // if( $_POST['assignJob']=="OngoingJob")
          //  { 
          //     $job_type=1;
          //     $inst_idname=$_POST['inst_name'];
          //     $ArrInst=explode("#",$_POST['inst_name']);
          //     $inst_name =$ArrInst[1];
          //     $inst_id =$ArrInst[0];
          //  }
          //  else
          //  {
          //     $job_type=2;
          //     $inst_idname=$_POST['inst_name_all'];
          //     $ArrInst=explode("#",$_POST['inst_name_all']);
          //     $inst_name =$ArrInst[1];
          //     $inst_id =$ArrInst[0];
          //  }
          //         $Update_ser = array('status' => 1);
          //           $condition = array('inst_id' => $inst_id);        
          //           update_query('internalsoftware.installer', $Update_ser, $condition);

          //     $insert_service = array('request_by'=>$request_by,'req_date'=> $req_date,'branch_id'=>$branch_id,'crack_req_id'=> $crack_req_id,'name' => $name, 'Notwoking' => $Notwoking, 'inst_name' =>  $inst_name, 'inst_id' =>  $inst_id, 'inst_cur_location' => $inst_cur_location,'inst_date' => date("Y-m-d"), 'newpending' => '0', 'status' => '0', 'billing' => $billing, 'payment' => $payment, 'service_status' => '2',
          //             'job_type' => $job_type, 'Zone_area' => $zone_area,'branch_id'=>$branch_id,'no_of_vehicals'=> $no_of_vehicals,'device_imei'=>$device_imei_crack,'company_name'=>$company_name_crack,'veh_reg'=>$veh_reg_crack,'location'=>$location_crack,'pname'=>$pname_crack,'cnumber'=>$cnumber_crack,'atime_status'=>$atime_status_crack,'designation'=>$designation_crack,'alter_contact_no'=>$alter_contact_no_crack,'alt_designation'=>$alt_designation_crack,'alt_cont_person'=>$alt_cont_person_crack,'atime'=>$atime_crack,'atimeto'=>$atimeto_crack, 'service_reinstall' => $service_reinstall);
          //            // $condition1 = array('id' => $id);   


          //             insert_query('internalsoftware.services', $insert_service);

                  

              $vh=explode(',',$query[0]['veh_reg']);
              $device_imei=explode(',',$query[0]['device_imei']);
              if( $_POST['assignJob']=="OngoingJob")
              { 
                
                  $inst_idname=$_POST['inst_name'];
                  $ArrInst=explode("#",$_POST['inst_name']);
                  $inst_name =$ArrInst[1];
                  $inst_id =$ArrInst[0];
                    
                    $vhi=$vh[0];
                    $dv_imei=$device_imei[0];
                     $job_type=1;
                     $Update_ser = array('status' => 1);
                    $condition = array('inst_id' => $inst_id);        
                    update_query('internalsoftware.installer', $Update_ser, $condition);
                  //array mai vehicle reg no. add karna hia after form making
                 
                   $Update_service = array('request_by'=>$request_by,'req_date'=> $req_date,'branch_id'=>$branch_id,'crack_req_id'=> $crack_req_id,'name' => $name, 'veh_reg' => $vhi,'device_imei'=>$dv_imei, 'Notwoking' => $Notwoking, 'inst_name' =>  $inst_name, 'inst_id' =>  $inst_id, 'inst_cur_location' => $inst_cur_location,
                  'inst_date' => date("Y-m-d"), 'newpending' => '0', 'status' => '0', 'service_status' => '2',
                  'job_type' => $job_type, 'Zone_area' => $zone_area,'location'=>$location_crack,'pname'=>$pname_crack,'cnumber'=>$cnumber_crack,'atime_status'=>$atime_status_crack,'designation'=>$designation_crack,'alter_contact_no'=>$alter_contact_no_crack,'alt_designation'=>$alt_designation_crack,'alt_cont_person'=>$alt_cont_person_crack,'atime'=>$atime_crack,'atimeto'=>$atimeto_crack, 'service_reinstall' => $service_reinstall);
                  //$condition1 = array('id' => $id);   
                
                  //update_query('internalsoftware.services', $Update_service, $condition1);
                  insert_query('internalsoftware.services', $Update_service);

                    for($i=1;$i<count($vh);$i++)
                    {

                      $job_type=2;
                       $Update_service = array('request_by'=>$request_by,'req_date'=> $req_date,'branch_id'=>$branch_id,'crack_req_id'=> $crack_req_id,'name' => $name, 'veh_reg' => $vh[$i], 'device_imei'=>$device_imei[$i],'Notwoking' => $Notwoking, 'inst_name' =>  $inst_name, 'inst_id' =>  $inst_id, 'inst_cur_location' => $inst_cur_location,
                      'inst_date' => date("Y-m-d"), 'newpending' => '0', 'status' => '0', 'service_status' => '2',
                      'job_type' => $job_type, 'Zone_area' => $zone_area,'location'=>$location_crack,'pname'=>$pname_crack,'cnumber'=>$cnumber_crack,'atime_status'=>$atime_status_crack,'designation'=>$designation_crack,'alter_contact_no'=>$alter_contact_no_crack,'alt_designation'=>$alt_designation_crack,'alt_cont_person'=>$alt_cont_person_crack,'atime'=>$atime_crack,'atimeto'=>$atimeto_crack, 'service_reinstall' => $service_reinstall);
                      //$condition1 = array('id' => $id);   
                    
                      insert_query('internalsoftware.services', $Update_service);
                    }
              }
              else
              {

                  $inst_idname=$_POST['inst_name_all'];

                  $ArrInst=explode("#",$_POST['inst_name_all']);
                  $inst_name =$ArrInst[1];
                  $inst_id =$ArrInst[0];
                   
                  $job_type=2;
                  for($i=0;$i<count($vh);$i++)
                  {
                     $Update_service = array('request_by'=>$request_by,'req_date'=> $req_date,'branch_id'=>$branch_id,'crack_req_id'=> $crack_req_id,'name' => $name, 'veh_reg' => $vh[$i], 'device_imei'=>$device_imei[$i], 'Notwoking' => $Notwoking, 'atime' => $atime, 'inst_name' =>  $inst_name, 'inst_id' =>  $inst_id, 'inst_cur_location' => $inst_cur_location,
                      'inst_date' => date("Y-m-d"), 'newpending' => '0', 'status' => '0','service_status' => '2',
                      'job_type' => $job_type, 'Zone_area' => $zone_area,'location'=>$location_crack,'pname'=>$pname_crack,'cnumber'=>$cnumber_crack,'atime_status'=>$atime_status_crack,'designation'=>$designation_crack,'alter_contact_no'=>$alter_contact_no_crack,'alt_designation'=>$alt_designation_crack,'alt_cont_person'=>$alt_cont_person_crack,'atime'=>$atime_crack,'atimeto'=>$atimeto_crack, 'service_reinstall' => $service_reinstall);
                     // $condition1 = array('id' => $id);   
                    
                      insert_query('internalsoftware.services', $Update_service);
                  }

              }

            $condition1 = array('id' => $crack_req_id);
            $Update_crack=array('service_status' => 2,'inst_id'=>$inst_id,'inst_name'=>$inst_name);
            update_query('internalsoftware.services_crack', $Update_crack, $condition1);
        }
        else
          {
              //echo 'OngoingJob'; die;
              if( $_POST['assignJob']=="OngoingJob")
              {
                  $inst_idname=$_POST['inst_name'];

                  $ArrInst=explode("#",$_POST['inst_name']);
                  $inst_name =$ArrInst[1];
                  $inst_id =$ArrInst[0];
                  $job_type=1;
                   
                  //mysql_query("update installer set status=1 where inst_id='$inst_id'");
                  
                  $Update_ser = array('status' => 1);
                  //$condition = array('inst_id' => $inst_id);        
                  update_query('internalsoftware.installer', $Update_ser);
                  
              }
              else
              {
                  
                  $inst_idname=$_POST['inst_name_all'];

                  $ArrInst=explode("#",$_POST['inst_name_all']);
                  $inst_name =$ArrInst[1];
                  $inst_id =$ArrInst[0];
                   
                  $job_type=2;
              }
              $Update_service = array('crack_req_id'=> $crack_,'name' => $name, 'veh_reg' => $veh_reg, 'Notwoking' => $Notwoking, 'location' => $location, 'atime' => $atime, 
              'pname' =>  $pname, 'cnumber' =>  $cnumber, 'inst_name' =>  $inst_name, 'inst_id' =>  $inst_id, 'inst_cur_location' => $inst_cur_location,
              'inst_date' => date("Y-m-d"), 'newpending' => '0', 'status' => '0','service_status' => '2','job_type' => $job_type,'popup_alertStock' => '1');
             // $condition1 = array('id' => $id);   
            
              update_query('internalsoftware.services', $Update_service);
               $token_get = select_query("select * from internalsoftware.mobilekey where inst_id=".$inst_id);
    
            if(count($token_get)>0)
              {
                $tokens[] = $token_get[0]['AndroidKey'];
                 //print_r($tokens); die;
                $Notificato_msg = array("title" => "Service Request", "subtitle" => $name." Service are assign you", "installelrid" => $inst_id, "branchid" => $_SESSION['BranchId']);
                //echo '<pre>'; print_r($Notificato_msg); die;
                $androidkey = "AIzaSyD9pinPICJTK7ibz_5U69QgZVCyjvGa0DU";
                $message_status = send_notification($tokens,$Notificato_msg,$androidkey);
              
              }
    
          }
       
     if($mode='crack')
     {
        header("location:newservice.php?mode=crack");
     }
     else
     {
         header("location:newservice.php");
     }
   
 
}
if(isset($_POST['backservice']))
{

    $pending=$_POST['pending'];
    //$newpending=$_POST['newpending'];
    $last_reason = $_POST['last_reason'];
    $back_reason=$_POST['reason_to_back'];
    //echo $pending;die();
    //$pending=mysql_query("UPDATE services SET pending='1',status='0',newpending='0' ,back_reason='$back_reason' WHERE id='$id'");
    /*$pending=mysql_query("UPDATE services SET pending='1',status='0',newpending='0' ,back_reason='".$last_reason."<br/>".$back_reason." - ".$date."', service_status=3  WHERE id='$id'");*/
    if($mode=='crack')
    {
       $pending_installation = array('pending' => '1', 'status' => '0', 'newpending' => '0', 'service_status' => '3','back_reason' => $last_reason."<br/>".$back_reason." - ".$date);
        $condition4 = array('id' => $id);            
        update_query('internalsoftware.services_crack', $pending_installation, $condition4);
        
        $pg=$_GET['pg'];

    }
    else
    {
       $pending_installation = array('pending' => '1', 'status' => '0', 'newpending' => '0', 'service_status' => '3','back_reason' => $last_reason."<br/>".$back_reason." - ".$date);
        $condition4 = array('id' => $id);            
        update_query('internalsoftware.services', $pending_installation, $condition4);
        
        $pg=$_GET['pg'];
    
    }
   
    header("location:newservice.php");
    
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
    <table width="50%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>
        <td><input name="id" type="hidden" id="id" value="<?php echo $query[0]['id'];?>"></td>
          </td>
      </tr>
      <tr>
        <td height="29" align="right">Client Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="name" id="name" readonly value="<?php echo $query[0]['name'];?>" /></td>
      </tr>
      <?php if($mode=='crack')
      {
          $veh=explode(',',$query[0]['veh_reg']);
            $veh_no=$veh[0];

      }
      else
      {
        $veh_no=$query[0]['veh_reg'];
      }
      ?> 

      <tr>
        <td align="right">Vehicle No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="veh_reg" id="veh_reg" readonly value="<?php echo $veh_no;//$query[0]['veh_reg'];?>" /></td>
      </tr>
      <?php if($_GET['mode']!="crack")
      {?>
        <td height="32" align="right">Notwoking:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="Notwoking" readonly id="notwoking" value="<?php echo $query[0]['Notwoking'];?>" /></td>
      <?php } ?>
        
      </tr>
      <tr>
        <td height="32" align="right">Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="location" id="location" readonly value="<?php echo $query[0]['location'];?>" /></td>
      </tr>
      <tr>
        <td height="32" align="right">Available Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="atime" readonly id="atime" value="<?php echo $query[0]['atime'];?>" /></td>
      </tr>
      <tr>
        <td height="32" align="right">Person Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="pname" id="pname" readonly value="<?php echo $query[0]['pname'];?>" /></td>
      </tr>
      <tr>
        <td height="32" align="right">Contact No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="cnumber" readonly id="cnumber" value="<?php echo $query[0]['cnumber'];?>" /></td>
      </tr>
      <?if($_GET['show']=="edit")
{?>
      <tr>
        <td colspan="2" align="center" ><input type="radio" value="OngoingJob" id="assignJob" name="assignJob" onclick="document.getElementById('inst_name').style.display = 'block';document.getElementById('inst_name_all').style.display = 'none';">
          Ongoing Job
          <input type="radio" value="PendingJob" id="assignJob" name="assignJob" onclick="document.getElementById('inst_name').style.display = 'none';document.getElementById('inst_name_all').style.display = 'block';">
          Pending Job </td>
      </tr>
      <tr>
        <td height="32"  align="right">Installation Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><?php
 
$inst_query = select_query("SELECT inst_id,inst_name,concat(inst_id,'#',inst_name) as idname FROM installer where is_delete=1 and status=0 and branch_id=".$_SESSION['BranchId']);
//$result=mysql_query($query);

 //$name1=$row['inst_id']."#".$row['inst_name']; ?>
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
            <? for($nt=0;$nt<count($inst_query2);$nt++) {  ?>
            <option value=<?=$inst_query2[$nt]['idname'];?>>
            <?=$inst_query2[$nt]['inst_name']?>
            </option>
            <? } ?>
          </select></td>
      </tr>
      <!-- <tr>
        <td>&nbsp;</td>
        <td><input type="checkbox" name="billing" id="billing" value="yes" />
          Billing
          <input type="checkbox" name="payment" id="payment" value="yes" />
          Payment </td>
      </tr> -->
      <tr>
        <td height="32" align="right">Installer Current Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="text" name="inst_cur_location" id="inst_cur_location" value="<?php echo $query[0]['inst_cur_location'];?>" /></td>
      </tr>
      <?}?>
      <tr>
        <? if($_GET['show']=="backtoservice")
{?>
        <td height="32" align="right">Reason To Back Services:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><textarea name="reason_to_back" id="reason_to_back" rows="5" cols="15"><?php echo $query[0]['back_reason'];?></textarea>
          <input type="hidden" name="last_reason" value="<?php echo $query[0]['back_reason'];?>" /></td>
        <?}?>
      </tr>
      <tr>
        <td height="32" align="right"><?if($_GET['show']=="edit")
{?>
          <input type="submit" name="submit" value="submit" align="right" onClick="return req_info(form2)"/>
          <?}?>
          &nbsp;&nbsp;</td>
        <td>&nbsp;&nbsp;
          <input type="button" name="Cancel" value="Cancel" onClick="window.location = 'newservice.php' " />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <?if($_GET['show']=="backtoservice")
{?>
          <input type="submit" name="backservice" value="back to service" align="right"  onClick="return req_info1(form2)"/>
          <?}?></td>
      </tr>
    </table>
  </form>
  <?
include("../include/footer.inc.php");

?>