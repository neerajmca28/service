<?php
session_start();
include("../connection.php");
 
if(isset($_REQUEST["id"]))
{
      $id=$_REQUEST["id"];
      $req_id=$_REQUEST["req_id"];
      
      if($req_id == 1){
          $type = 'is_service';
      }else if($req_id == 2){
          $type = 'is_installation';
      }

}


 
if(isset($_POST['submit']))
{
     
   $support_user=$_POST['support_user'];
   $fwd_req_comment=$_POST['fwd_req_comment'];
 
   if($support_user != '' && $fwd_req_comment != '')
   {
    
    if($req_id=='1')
    {
        /*$Updateapprovestatus="update services set service_status='11', fwd_tech_rm_id='".$support_user."', fwd_datetime='".date("Y-m-d H:i:s")."', fwd_reason='".$fwd_req_comment."' where id='".$id."'";        
        mysql_query($Updateapprovestatus);*/
        
        //$check_assign = select_query("SELECT * FROM services  where fwd_tech_rm_id is null and fwd_reason is null and  id='".$id."'");
        $check_assign = select_query("SELECT * FROM services  where fwd_repair_id is null and fwd_serv_to_repair is null and  id='".$id."'");
        
        if(count($check_assign) > 0)
        {
            $installer_detail = select_query("select installer_mobile from installer where inst_id='".$check_assign[0]['inst_id']."'");
            $installer_mobile = trim($installer_detail[0]['installer_mobile']);
            $installer_name = $check_assign[0]['inst_name'];
            
            $forward_person_detail = select_query("select * from request_forward_list where user_id='".$support_user."'");
            $forward_mobile = trim($forward_person_detail[0]['phone_no']);
            $forward_name = $forward_person_detail[0]['user_name'];
            
            $send_msg = 'This Service Request Forward to '.$forward_name.' ('.$forward_mobile.') and '.$installer_name.' ('.$installer_mobile.') Installer assign for service. Kindly co-ordinate to each other.';
            
            /*$Update_status = array('service_status' => 11, 'fwd_tech_rm_id' =>  $support_user, 'fwd_datetime' => date("Y-m-d H:i:s"),  
            'fwd_reason' => $fwd_req_comment);*/
            $Update_status = array('service_status' => 11, 'fwd_repair_id' =>  $support_user, 'fwd_repair_date' => date("Y-m-d H:i:s"),  
            'fwd_serv_to_repair' => $fwd_req_comment);
            $condition = array('id' => $id);    
            update_query('internalsoftware.services', $Update_status, $condition);
            
            if($support_user != '60')
            {
                $request_list = array('is_service' => 0);
                $condition2 = array('user_id' => $support_user);
                update_query('internalsoftware.request_forward_list', $request_list, $condition2);
            }
            
            
            /*$request_list = array('is_service' => 0);
            $condition2 = array('user_id' => $support_user);
            update_query('internalsoftware.request_forward_list', $request_list, $condition2);*/
            
            $MSG="Vehicle : ".$send_msg;
            $MobileNum = $installer_mobile.','.$forward_mobile;
            
            $ch = curl_init();
            $user="gary@itglobalconsulting.com:itgc@123";
            $receipientno=$MobileNum;
            $senderID="GTRACK";
            $msgtxt=$MSG;
            curl_setopt($ch,CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
            $buffer = curl_exec($ch);
            /*if(empty ($buffer))
            { echo " buffer is empty "; }
            else
            { echo $buffer; echo "Successfully Sent"; }*/
            curl_close($ch);
            
            echo "Request Successfully Forward";
        }
        else
        {
            echo "Request Already Forward,Kindly Reload Page.";
        }
    
    }
    
    if($request_forward_list=='2')
    {
        /*$Updateapprovestatus="update installation set service_status='11', fwd_tech_rm_id='".$support_user."', fwd_datetime='".date("Y-m-d H:i:s")."', fwd_reason='".$fwd_req_comment."' where id='".$id."'";        
        mysql_query($Updateapprovestatus);*/
        
        /*$check_assign_inst = select_query("SELECT * FROM installation  where fwd_tech_rm_id is null and fwd_reason is null and  id='".$id."'");*/
        $check_assign_inst = select_query("SELECT * FROM installation  where fwd_repair_id is null and fwd_install_to_repair is null and id='".$id."'");
        
        if(count($check_assign_inst) > 0)
        {
            $installer_detail = select_query("select installer_mobile from installer where inst_id='".$check_assign_inst[0]['inst_id']."'");
            $installer_mobile = trim($installer_detail[0]['installer_mobile']);
            $installer_name = $check_assign[0]['inst_name'];
            
            $forward_person_detail = select_query("select * from request_forward_list where user_id='".$support_user."'");
            $forward_mobile = trim($forward_person_detail[0]['phone_no']);
            $forward_name = $forward_person_detail[0]['user_name'];
            
            $send_msg = 'This Installation Request Forward to '.$forward_name.' ('.$forward_mobile.') and '.$installer_name.' ('.$installer_mobile.') Installer assign for Installation. Kindly co-ordinate to each other.';
            
            
            /*$Update_inst_status = array('installation_status' => 11, 'fwd_tech_rm_id' =>  $support_user, 'fwd_datetime' => date("Y-m-d H:i:s"),  
            'fwd_reason' => $fwd_req_comment);*/
            $Update_inst_status = array('installation_status' => 11, 'fwd_repair_id' =>  $support_user, 'fwd_repair_date' => date("Y-m-d H:i:s"),  
            'fwd_install_to_repair' => $fwd_req_comment);
            $condition_inst = array('id' => $id);    
            update_query('internalsoftware.installation', $Update_inst_status, $condition_inst);
            
            if($support_user != '60')
            {            
                $request_list_inst = array('is_installation' => 0);
                $condition2_inst = array('user_id' => $support_user);
                update_query('internalsoftware.request_forward_list', $request_list_inst, $condition2_inst);
            }
            
            /*$request_list_inst = array('is_installation' => 0);
            $condition2_inst = array('user_id' => $support_user);
            update_query('internalsoftware.request_forward_list', $request_list_inst, $condition2_inst);*/
            
            $MSG="Vehicle : ".$send_msg;
            $MobileNum = $installer_mobile.','.$forward_mobile;
            
            $ch = curl_init();
            $user="gary@itglobalconsulting.com:itgc@123";
            $receipientno=$MobileNum;
            $senderID="GTRACK";
            $msgtxt=$MSG;
            curl_setopt($ch,CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
            $buffer = curl_exec($ch);
            /*if(empty ($buffer))
            { echo " buffer is empty "; }
            else
            { echo $buffer; echo "Successfully Sent"; }*/
            curl_close($ch);
            
            echo "Request Successfully Forward";
        }
        else
        {
            echo "Request Already Forward,Kindly Reload Page.";
        }
    
    }
    
  }
  else
  {
       echo "Both Fields Never Be Blank!";
  }
        
}

 
?>

 <html>
<head>
 
</head>
<body>


<? if(!isset($_REQUEST["view"]) && $_REQUEST["view"]!=true)
{?>

<form name="addname" method="post" action="forward_service_request.php?id=<?echo $_REQUEST["id"]?>&req_id=<?echo$_REQUEST["req_id"]?>">

<table border="0" cellspacing="5" cellpadding="5" align="left">
 <tr><td>Forward Request to</td></tr>
 <tr>
     <td>
      <select name="support_user" id="support_user" width="150px">
            <option value="" >-- Select One --</option>
            <?php
            /*$support_query = select_query("select * from request_forward_list where profile_id in ('1','2') and ".$type."='1' and is_active='1'");*/
            
            $support_query = select_query("select rfl.user_name, rfl.user_id, las.name
                                            from request_forward_list as rfl left join login_active_status as las
                                            on rfl.active_status=las.id
                                            where rfl.profile_id in ('3') and rfl.".$type."='1' and rfl.is_active='1'");
            
            for($i=0;$i<count($support_query);$i++)
            {
            ?>            
            <option value ="<?php echo $support_query[$i]['user_id'] ?>"  <?echo $selected;?>>
            <?php echo $support_query[$i]['user_name']." (".$support_query[$i]['name'].")"; ?>
            </option>
            <?php 
            } 
            
            ?>
            </select>
     </td>
 </tr>
 <tr>
     <!--<td>
      <textarea cols="35" rows="3" type="text" name="fwd_req_comment" id="fwd_req_comment"></textarea>
     </td>-->
     <td>
      <select name="fwd_req_comment" id="fwd_req_comment" width="150px">
            <option value="" >-- Select One --</option>
            <?php
            $fwd_reason = select_query("select reason from request_forward_reason where reason_type='Forward' and is_active='1'");
            for($r=0;$r<count($fwd_reason);$r++)
            {
            ?>            
            <option value ="<?php echo $fwd_reason[$r]['reason'] ?>"  <?echo $selected;?>>
            <?php echo $fwd_reason[$r]['reason']; ?>
            </option>
            <?php 
            } 
            
            ?>
            </select>
     </td>
 </tr>
 <tr><td><input type="submit" name="submit" value="Submit"></td></tr>
</table>
</form>

<?  
}
 
?>
</body>
</html>