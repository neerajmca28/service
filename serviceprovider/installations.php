<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");  */
?>
<script>
var Path="<?php echo __SITE_URL;?>/";
//var Path="http://trackingexperts.com/service/";

function forwardtoRepair(row_id)
{
   var retVal = prompt("Write Comment : ", "Comment");
  if (retVal)
  {
  addComment(row_id,retVal);
      return ture;
  }
  else
    return false;
}

function addComment(row_id,retVal)
{
    //alert(user_id);
    //return false;
  $.ajax({
        type:"GET",
        url:Path +"userInfo.php?action=forward_Repair_Install_Comment",
          
         data:"row_id="+row_id+"&comment="+retVal,
        success:function(msg){
            alert(msg);
            location.reload(true);        
        }
    });
}
</script>

<div class="top-bar">
  <h1>Installation List</h1>
</div>
<div class="top-bar">
  <div style="float:right";><font style="color:#ADFF2F;font-weight:bold;">GreenYellow:</font> Urgent Installation</div>
  <br/>
  <div style="float:right";><font style="color:#FFC184;font-weight:bold;">Light Orange:</font> Back From Repair</div>
  <br/>
  <div style="float:right";><font style="color:#bbdefb;font-weight:bold;">Light Blue:</font> Installer Closed </div>
  <br/>
  <div style="float:right";><font style="color:#CCCCCC;font-weight:bold;">Grey:</font> Closed Installation</div>
</div>
<div class="table">
  <? $mode=$_GET['mode'];
if($mode=='') { $mode="new"; }
    
  if($mode=='close')
    {
    //$rs = mysql_query("SELECT * FROM installation where reason!='' or rtime!='' and branch_id=".$_SESSION['BranchId']."  order by id desc");
     
    $rs = select_query("SELECT * FROM installation where (installation_status='5' or installation_status='6') and  (branch_id=".$_SESSION['BranchId']."  or inter_branch=".$_SESSION['BranchId'].") order by id desc limit 1000");
    }
    else if($mode=='new')
    {
    //$rs = mysql_query("SELECT * FROM services  where inst_name!='' and inst_cur_location!='' and newpending!='1' order by id desc");
    //$rs = mysql_query("SELECT * FROM installation  where inst_name!='' and rtime='' and inst_cur_location!='' and newpending!='1'  and branch_id=".$_SESSION['BranchId'] ." order by id desc");
    //and reason='' and rtime=''
    
    $rs = select_query("SELECT * FROM installation  where (installation_status=2 or installation_status=17 or installation_status=18)  and (branch_id=".$_SESSION['BranchId'] ." or inter_branch=".$_SESSION['BranchId'].") order by id desc");
    }    

    ?>
  <div style="float:right"><a href="installations.php?mode=new">New</a> | <a href="installations.php?mode=close">Closed</a></div>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      <tr>
        <th>Job Type</th>
        <th>Request By </th>
        <th>Request Date </th>
        <th width="10%" align="center"><font color="#0E2C3C"><b>Sales Person </b></font></th>
        <th width="10%" align="center"><font color="#0E2C3C"><b>Client Name </b></font></th>
        <th width="7%" align="center"><font color="#0E2C3C"><b>No.Of Installation <br/>
          /IP Box</b></font></th>
           <th><font color="#0E2C3C"><b>Landmark</b></font></th>
        <th width="9%" align="center"><font color="#0E2C3C"><b>Branch Location</b></font></th>
         
        <th width="10%" align="center"><font color="#0E2C3C"><b>Time</b></font></th>
        <th><font color="#0E2C3C"><b>Device Type</b></font></th>
         <th><font color="#0E2C3C"><b>Accessories</b></font></th>
        <th width="10%" align="center"><font color="#0E2C3C"><b>Client Contact No.</b></font></th>
        <th width="10%" align="center"><font color="#0E2C3C"><b>Installer Name</b></font></th>
         <th>Current Status</th>
        <th>View Detail</th>
        <? if($mode=='close')
       {?>
        <th><font color="#0E2C3C"><b>Closed</b></font></th>
        <?}
        else
        {?>
        <th><font color="#0E2C3C"><b>Edit</b></font></th>
        <th><font color="#0E2C3C"><b>Back/Forward</b></font></th>
        <th><font color="#0E2C3C"><b>Close</b></font></th>
        <!--<td><font color="#0E2C3C"><b>Delete</b></font></td>-->
        
        <?}?>
      </tr>
    </thead>
    <tbody>
      <?php 
    
    //while ($row = mysql_fetch_array($rs)) 
    for($i=0;$i<count($rs);$i++)
    {
        if($rs[$i]['IP_Box']=="") $ip_box="Not Required";  else $ip_box="$rs[$i][IP_Box]"; 
    
    ?>
      
      <!-- <tr align="Center" <? if(($rs[$i]['reason'] && $rs[$i]['time']) ||  $rs[$i]['back_reason']) { ?> style="background:#CCCCCC" <? }?>> -->
      <tr align="Center"  <? if($rs[$i]['installation_status']==5 or $rs[$i]['installation_status']==6 )  {  ?> style="background:#CCCCCC;" <? }
    elseif($rs[$i]['required']=='urgent' && $rs[$i]['fwd_repair_to_install']==''){ ?>style="background:#ADFF2F" <? } elseif($rs[$i]['installation_status']=='2' && $rs[$i]['fwd_repair_to_install']!=''){ echo 'style="background-color:#FFC184"';} elseif($rs[$i]['installer_close_status']!=''){ echo 'style="background-color:#bbdefb"';}?>>
      <!--   <td><?php echo $i+1; ?></td> -->
      <td><?php 
       
            $sql1 = select_query("select instal_reinstall from installation_request WHERE id='".$rs[$i]['id']."'");
      
            if($rs[$i]['instal_reinstall'] == "installation"){ echo "I";} 
            elseif($rs[$i]['instal_reinstall'] == "re_install"){ echo "Re-Add";}
            elseif($rs[$i]['instal_reinstall'] == "online_crack"){ echo "OC";}
            elseif($rs[$i]['instal_reinstall'] == "crack"){ echo "C";}
          
          ?>
        </td>
        <td>&nbsp;<?php echo $rs[$i]['request_by'];?></td>
        <td>&nbsp;<?php echo $rs[$i]['req_date'];?></td>
        <td width="10%" align="center">&nbsp;
          <?php $sales_name = select_query("select name from sales_person where id='".$rs[$i]['sales_person']."' "); echo $sales_name[0]['name'];?></td>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$rs[$i]["user_id"];
          $rowuser=select_query($sql);
        ?>
        <td><?php echo $rowuser[0]["sys_username"];?></td>
        <?php if($_SESSION['BranchId']==1 && $rs[$i]['instal_reinstall']=='installation')
        {
          ?>
             <td width="10%" align="center">&nbsp;
              <?php $no_of_inst = '1'; echo "$no_of_inst <br/><br/>/$ip_box";?></td>
         <!--    <td width="10%" align="center">&nbsp;
              <?php $no_of_inst = $rs[$i]['installation_approve'] - $rs[$i]['installation_made']; echo "$no_of_inst <br/><br/>/$ip_box";?></td> -->
        <?php 
       }
       else 
        {?>
            <!-- <td width="10%" align="center">&nbsp;
              <?php $no_of_inst = $rs[$i]['no_of_vehicals'] - $rs[$i]['installation_made'];echo "$no_of_inst <br/><br/>/$ip_box";?></td> -->
              <td width="10%" align="center">&nbsp;
              <?php $no_of_inst = '1';echo "$no_of_inst <br/><br/>/$ip_box";?></td>
        <?php
         } ?> 
        
        <!--<td width="9%" align="center">&nbsp;<?php echo $rs[$i]['location'];?></td>-->
        
        <?php if($rs[$i]['location']!=""){?>
     <td align="">
         <?php 
          $sql_InstRqst2 = select_query("select inst_req_id from installation WHERE id='".$rs[$i]['id']."'");
            $sql_Inst2 = select_query("select landmark from installation_request WHERE id='".$sql_InstRqst2[0]['inst_req_id']."'");
                
                echo $sql_Inst2[0]['landmark']."<br>";
            ?>    
          

        </td>
        <?php }
        else
          { $city=select_query("select * from tbl_city_name where branch_id='".$rs[$i]['inter_branch']."'");?>
        <td width="9%" align="center">&nbsp;<?php echo $city[0]['city'];?></td>
        <?php }?>


        <td>
        <?php 
       
          $sql1 = select_query("select Zone_area from installation WHERE id='".$rs[$i]['id']."'");
      $sql2 = select_query("SELECT name FROM re_city_spr_1 WHERE id='".$sql1[0]['Zone_area']."'");

          echo $sql2[0]['name'];
          
         ?>
        </td>

       
        <td width="10%" align="center">&nbsp;<?php echo $rs[$i]['time'];?></td>
      <!--  <td align="center" nowrap>

          <?php 
       
            //  $sqlDevice=select_query("SELECT device_type FROM device_type where id='".$rs[$i]["device_type"]."' "); 
            //  $sqlModel=select_query("SELECT device_model FROM device_model where id='".$rs[$i]["model"]."' "); 
         
            // echo $sqlModel[0]['device_model']."</br>";
            // echo $sqlDevice[0]['device_type'];
            $sqlDevice=select_query("SELECT item_name FROM item_master where item_id='".$rs[$i]["device_type"]."'");

             $sqlModel=select_query("select im.* from installation_request ir left join item_master im on ir.model=im.item_id where ir.model='".$rs[$i]["model"]."'");
             echo $sqlModel[0]['item_name']."</br>";
             echo $sqlDevice[0]['item_name'];
        
          ?>

          
        </td> -->
        <td width="1%" align="center" nowrap>
        <?php 
          


          $sql1234 = select_query("select * from installation_request WHERE id='".$rs[$i]['inst_req_id']."'");
        
          if($sql1234[0]['instal_reinstall'] == 're_install')
          {
            echo $sql1234[0]['device_status']."<br>".$sql1234[0]['device_current_location'];
          }
          else
          {
            echo "tt";
            $sqlDevice=select_query("SELECT item_name FROM item_master where item_id='".$query[$i]["approved_model"]."'");
            $sqlModel=select_query("select im.* from installation_request ir left join item_master im on ir.model=im.item_id where ir.model='".$rs[$i]["model"]."'");
        
            echo $sqlModel[0]['item_name']."<br>".$rs[0]['item_name'];
          
          }
      
      
          ?>
        </td>
        <!-- <td>
          <?php
            echo $rs[$i]['acess_selection'];
          ?>
        </td> -->
         <td width="1%" align="center">
          <?php

          $sql5 = select_query("select accessories_tollkit from installation WHERE id='".$rs[$i]['id']."'");
          if($sql5[0]['accessories_tollkit']== '')
          {
            echo "No";
          }
          else
          {
            echo "Yes";
          }
          //echo $sql5[0]['acess_selection'];
          ?>

        </td>
         <td>
      <?php
    
        $sql_InstRqst = select_query("select inst_req_id from installation WHERE id='".$rs[$i]['id']."'");
                $sql_Inst = select_query("select contact_person,contact_number,designation from installation_request WHERE id='".$sql_InstRqst[0]['inst_req_id']."'");
                
                echo $sql_Inst[0]['contact_person']."<br>";
                echo $sql_Inst[0]['contact_number']."<br>";
                echo $sql_Inst[0]['designation']."<br>";
            ?>
    </td>
        <!--<td width="10%" align="center" style="font-size:12px">&nbsp;<?php echo $rs[$i]['inst_name'];?></td>-->
        <td  style="font-size:12px">&nbsp;<strong><?php echo $rs[$i]['inst_name'];
        if($rs[$i]['job_type']==2)
        {
            echo "<br/><font color='red'>(pending Job)</font>";
        }
        else
        {
            echo "<br/>(Ongoing Job)";
        }
        ?> </strong></td>
      <td><strong>
      <?  if($rs[0]["installation_status"]==7 && ($rs[0]["admin_comment"]!="" || $rs[0]["sales_comment"]=="")){echo "Reply Pending at Request Side";}
    elseif($rs[0]["installation_status"]==7 && $rs[0]["admin_comment"]=="" ){echo "Pending Saleslogin for Information";}
    elseif($rs[0]["approve_status"]==0 && $rs[0]["installation_status"]==8 ){echo "Pending Admin Approval";}
    elseif($rs[0]["installation_status"]==9 && $rs[0]["approve_status"]==1 ){echo "Pending Confirmation at Request Person";}
    elseif($rs[0]["installation_status"]==1 ){echo "Pending Dispatch Team";}
    elseif($rs[0]["installation_status"]==2 ){echo "Assign To Installer";}
    elseif($rs[0]["installation_status"]==11 ){echo "Request Forward to ".$forward_name;}
    elseif($rs[0]["installation_status"]==3 ){echo "Back Installation";}
    elseif($rs[0]["installation_status"]==15 ){echo "Pending Remaining Installation";}
    elseif($rs[0]["installation_status"]==5 || $rs[0]["installation_status"]==6){echo "Installation Close";}?>
            </strong></td>
        <td><a href="#" onclick="Show_record(<?php echo $rs[$i]["id"];?>,'installation','popup1'); " class="topopup">View Detail</a></td>
        <? if($mode=='close')
         {?>
          <td>&nbsp; <span onClick="return editreason(<?php echo $rs[$i]['id'];?>);"> Closed</span></td>
          <?php 
        }
        else
        {?>
          <?php if($rs[$i]["inter_branch"]==0 || $rs[$i]["inter_branch"]==$_SESSION['BranchId'])
            {?>
              <td><a href="edit_newinstallation.php?id=<?=$rs[$i]['id'];?>&action=edit&show=edit">Edit</a></td>
              <td><a href="edit_newinstallation.php?id=<?=$rs[$i]['id'];?>&action=edit&show=backtoservice">Back</a>
                <?php if($rs[$i]['fwd_install_to_repair']=="" && $rs[$i]['fwd_repair_to_install']=="" && $rs[$i]['installation_status']==2)
                {?>
                  |<!--<a href="#" onclick="return forwardtoRepair(<?php echo $rs[$i]["id"];?>);">Forward to Repair</a>--> 
                  <?php if($rs[$i]['job_type']==1)
                  {?>
                  <a href="forwardrequest-iframe.php?id=<?=$rs[$i]["id"];?>&req_id=2&height=220&width=480" class="thickbox">Forward</a>
                  <?php 
                  }else{?>

                  Forward


              <?}}
                 ?>
               </td>
               <?php //if($rs[$i]['job_type']==1)
               if((($rs[$i]['job_type']==1) && ($rs[$i]['instal_reinstall']!="online_crack")) || ((($rs[$i]['job_type']==1) &&($rs[$i]['instal_reinstall']=='online_crack') &&($rs[$i]['crack_status']=='1') )))
               {?>
              <td><a href="edit_newinstallation.php?id=<?=$rs[$i]['id'];?>&action=edit&show=close">Close</a></td>
              <?php }
              else{?>
               <td>Close</td>
              <?php }
          }
          else
          {?>
          <td>Edit </td>
          <td>Back</td>
          <td>Close</td>
          <?php 
          }
      }?>
      </tr>
      <?php  
    }
     
    ?>
  </table>
  <div id="toPopup">
    <div class="close">close</div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup1" style ="height:100%;width:100%"> <!--your content start--> 
      
    </div>
    <!--your content end--> 
    
  </div>
  <!--toPopup end-->
  
  <div class="loader"></div>
  <div id="backgroundPopup"></div>
</div>
<?
include("../include/footer.inc.php");

?>