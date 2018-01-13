<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */ 
?>
<script>
 //var Path="http://trackingexperts.com/service/";
 var Path="<?php echo __SITE_URL;?>/";
 
function forwardtoRepair(row_id
){
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
        url:Path +"userInfo.php?action=forwardtoRepairComment",
          
         data:"row_id="+row_id+"&comment="+retVal,
        success:function(msg){
             alert(msg);
             location.reload(true);        
        }
    });
}
</script>

<div class="top-bar">
  <h1>Service List</h1>
</div>
<div class="top-bar">
  <div style="float:right";><font style="color:#ADFF2F;font-weight:bold;">GreenYellow:</font> Urgent Service</div>
  <br/>
  <div style="float:right";><font style="color:#FFC184;font-weight:bold;">Light Orange:</font> Back From Repair</div>
  <br/>
  <div style="float:right";><font style="color:#bbdefb;font-weight:bold;">Light Blue:</font> Installer Closed </div>
  <br/>
  <div style="float:right";><font style="color:#CCCCCC;font-weight:bold;">Grey:</font> Closed Service</div>
</div>
<div class="table">
  <?php
 
    $mode=$_GET['mode'];
    if($mode=='') { $mode="new"; }
    
  if($mode=='close')
    {
    //$rs = mysql_query("SELECT * FROM services where reason!='' and time!='' and branch_id=".$_SESSION['BranchId'] ." order by id desc");
    $rs = select_query("SELECT * FROM services where (service_status='5' or service_status='6') and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc limit 1000");
    
    //where 
    }
    else if($mode=='new')
    {
    //$rs = mysql_query("SELECT * FROM services  where inst_name!='' and inst_cur_location!='' and newpending!='1' order by id desc");
    
    //$rs = mysql_query("SELECT * FROM services  where reason='' and time='' and inst_name!='' and inst_cur_location!='' and newpending!='1' and branch_id=".$_SESSION['BranchId'] ." order by id desc");
    $rs = select_query("SELECT * FROM services  where (service_status='2' or service_status='17' or service_status='18') and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc");
    }    
 


?>
  <div style="float:right"><a href="service.php?mode=new">New</a> | <a href="service.php?mode=close">Closed</a></div>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      <tr>
        <th>Job Type</th>
        <th>Request By </th>
        <th>Request Date </th>
        <th><font color="#0E2C3C"><b>Client Name </b></font></th>
        <th><font color="#0E2C3C"><b>Vehicle No <br/>
          (IP Box/ Required)</b></font></th>
        <th><font color="#0E2C3C"><b>Device IMEI</b></font></th>
        <th><font color="#0E2C3C"><b>Notworking </b></font></th>
        <th><font color="#0E2C3C"><b>Branch Location</b></font></th>
        <th><font color="#0E2C3C"><b>Landmark</b></font></th>
        <th><font color="#0E2C3C"><b>Available Time</b></font></th>
        <th><font color="#0E2C3C"><b>Client Contact No.</b></font></th>
        <th><font color="#0E2C3C"><b>Installer Name</b></font></th>
        <? if($mode=='close') {?>
        <th><font color="#0E2C3C"><b>Reason</b></font></th>
        <? } ?>
           <th>Current Status</th>
        <th>View Detail</th>
        <? if($mode=='close') {?>
        <th><font color="#0E2C3C"><b>Closed</b></font></th>
        <? }
        else
        { ?>
        <th><font color="#0E2C3C"><b>Back/Forward</b></font></th>
        <th><font color="#0E2C3C"><b>Edit</b></font></th>
        <th><font color="#0E2C3C"><b>Close</b></font></th>
        
        <?}?>
      </tr>
    </thead>
    <tbody>
      <?php 
    
    //while ($row = mysql_fetch_array($rs)) 
    for($i=0;$i<count($rs);$i++)
    {
        if($rs[$i][IP_Box]=="") $ip_box="Not Required";  else $ip_box="$rs[$i][IP_Box]"; 
    
    ?>
      
      <!-- <tr align="Center" <? if(($rs[$i]['reason'] && $rs[$i]['time']) ||  $rs[$i]['back_reason']) { ?> style="background:#CCCCCC" <? }?> > -->
      
      <tr align="Center" <? if($rs[$i]['service_status']==5 or $rs[$i]['service_status']==6 ){ echo 'style="background-color:#CCCCCC"';} elseif($rs[$i]['required']=='urgent'){ echo 'style="background-color:#ADFF2F"';} elseif($rs[$i]['service_status']=='2' && $rs[$i]['fwd_repair_to_serv']!=''){ echo 'style="background-color:#FFC184"';} elseif($rs[$i]['installer_close_status']!=''){ echo 'style="background-color:#bbdefb"';}?> >
         <td><?php 
       
            $sql1 = select_query("select instal_reinstall from installation_request WHERE id='".$rs[$i]['id']."'");
      
            if($rs[$i]['service_reinstall'] == "service"){ echo "S";} 
            elseif($rs[$i]['service_reinstall'] == "re_install"){ echo "Re-Add";}
            elseif($rs[$i]['service_reinstall'] == "crack"){ echo "C";}
          
          ?>
        </td>
        <td>&nbsp;<?php echo $rs[$i]['request_by'];?></td>
        <td>&nbsp;<?php echo $rs[$i]['req_date'];?></td>
        <td>&nbsp;<?php echo $rs[$i]['name'];?></td>
        <td>&nbsp;<?php echo $rs[$i]['veh_reg']." <br/><br/>(".$ip_box.")";?></span></td>
        <td>&nbsp;<?php echo  $rs[$i]['device_imei'] ?></td>
        <td>&nbsp;<?php echo  $rs[$i]['Notwoking'] ?></td>
        <?php  
        if($rs[$i]['inter_branch']="1")
        {
          $city_code=1;
        }
        else
        {
          $city_code=$rs[$i]['inter_branch'];
        }


        $city= select_query("select * from internalsoftware.tbl_city_name where branch_id='".$city_code."'");
         $sql2 = select_query("SELECT name FROM re_city_spr_1 WHERE id='".$rs[0]['Zone_area']."'");

         // echo $sql2[0]['name'];

          ?>
        <td >&nbsp;<?php echo $sql2[0]['name']." ".$city[0]['city'];?></td>

   
        <td >&nbsp;<?php echo $rs[$i]['location'];?></td>
        <td>&nbsp;<?php echo $rs[$i]['atime'] ;?></td>

         <td>
      
                
                <?php echo $rs[$i]['pname']."<br>";
                echo $rs[$i]['cnumber']."<br>";
                echo $rs[$i]['designation']."<br>";
                ?>
            
    </td>
        <!-- <td style="font-size:12px">&nbsp;<?php echo $rs[$i]['cnumber'] ;?></td> -->
        <td style="font-size:12px">&nbsp;<strong><?php echo $rs[$i]['inst_name'];
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
            <? if($rs[0]["service_status"]==1 ){echo "Pending Dispatch Team";}
                elseif($rs[0]["service_status"]==2 ){echo "Assign To Installer";}
                elseif($rs[0]["service_status"]==11 ){echo "Request Forward to ".$forward_name;}
                elseif($rs[0]["service_status"]==3 ){echo "Back Installation";}
                elseif($rs[0]["service_status"]==5 || $rs[0]["service_status"]==6){echo "Service Close";}?>
            </strong></td>
        <? if($mode=='close')
       {?>
        <td>&nbsp;<?php echo $rs[$i]['reason'] ;?></td>
        <? } ?>
        <td><a href="#" onclick="Show_record(<?php echo $rs[$i]["id"];?>,'services','popup1'); " class="topopup">View</a></td>
        <? if($rs[$i]['service_status']==5 or $rs[$i]['service_status']==6 ) { ?>
        <td>&nbsp; <span onClick="return editreason(<?php echo $rs[$i]['id'];?>);"> Closed</span></td>
        <?php }
        else
        {?>
        <?php if($rs[$i]["inter_branch"]==0 || $rs[$i]["inter_branch"]==$_SESSION['BranchId']){?>
        <td><a href="editservice.php?id=<?=$rs[$i]['id'];?>&action=edit&show=backtoservice">Back</a>
        <?php if($rs[$i]['fwd_serv_to_repair']=="" && $rs[$i]['fwd_repair_to_serv']=="" && $rs[$i]['service_status']==2){?>
        |<!--<a href="#" onclick="return forwardtoRepair(<?php //echo $rs[$i]["id"];?>);">-->
        <?php if($rs[$i]['job_type']==1 && $rs[$i]['service_reinstall']!='crack')
          {?>
          <a href="forwardrequest-iframe.php?id=<?=$rs[$i]["id"];?>&req_id=1&height=220&width=480" class="thickbox">Forward</a>
         <?php } else 
         { ?>
                Forward
           <?}}
            ?>
        </td>
        <td><a href="editservice.php?id=<?=$rs[$i]['id'];?>&action=edit&show=edit&mode=<?php echo $rs[$i]['service_reinstall']; ?>">Edit</a></td>
         <?php if($rs[$i]['job_type']==1)
               {?>
        <td><a href="editservice.php?id=<?=$rs[$i]['id'];?>&action=edit&show=close&mode=<?php echo $rs[$i]['service_reinstall']; ?>">Close</a></td>
          <?php }
              else{?>
               <td>Close</td>
              <?php }
          }
        else{?>
        <td>Back</td>
        <td>Edit </td>
        <td>Close</td>
        <?php }}?>
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