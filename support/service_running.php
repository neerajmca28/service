<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/  
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
  url:Path +"userInfo.php?action=forwardtoRepairComment",
    
   data:"row_id="+row_id+"&comment="+retVal,
  success:function(msg){
   // alert(msg);
   
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
                <div style="float:right";><font style="color:#FFC184;font-weight:bold;">Light Brown:</font> Back From Repair</div>
                <br/>
                <div style="float:right";><font style="color:#CCCCCC;font-weight:bold;">Grey:</font> Closed Service</div>   
               
                </div>
                <div class="table">
                <?php
 
 $mode=$_GET['mode'];
if($mode=='') { $mode="new"; }
 
  if($mode=='close')
 {
 $rs = mysql_query("SELECT * FROM services where (service_status='5' or service_status='6') and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc limit 500");
 
 }
 else if($mode=='new')
 {
 $rs = mysql_query("SELECT * FROM services  where service_status='2'  and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc");
 } 
 


?>
<div style="float:right"><a href="service_running.php?mode=new">New</a> | <a href="service_running.php?mode=close">Closed</a></div>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
 <thead>
  <tr>
        <th>Sl. No</th>
        <th>Request By </th>
         <th>Request Date </th>
      <th  ><font color="#0E2C3C"><b>Client Name </b></font></th>
  <th  ><font color="#0E2C3C"><b>Vehicle No <br/>(IP Box/ Required)</b></font></th>
  <th ><font color="#0E2C3C"><b>Device IMEI</b></font></th>
  <th  ><font color="#0E2C3C"><b>Notworking </b></font></th>
  <th  ><font color="#0E2C3C"><b>Location</b></font></th>
  <th  ><font color="#0E2C3C"><b>Available Time</b></font></th>
  <th  ><font color="#0E2C3C"><b>Client Contact No.</b></font></th>
  <th ><font color="#0E2C3C"><b>Installer Name</b></font></th>
  
    <? if($mode=='close')
    {?>
  <th  ><font color="#0E2C3C"><b>Reason</b></font></th>
  <? } ?>
  
         <th>View Detail</th>
       
  </tr>
 </thead>
 <tbody>

  
 <?php 
 $i=1;
    while ($row = mysql_fetch_array($rs)) {
  if($row[IP_Box]=="") $ip_box="Not Required";  else $ip_box="$row[IP_Box]"; 
 
    ?>  
        
 <tr align="Center" <? if($row['service_status']==5 or $row['service_status']==6 ){ echo 'style="background-color:#CCCCCC"';} elseif($row['required']=='urgent'){ 
 echo 'style="background-color:#ADFF2F"';} elseif($row['service_status']=='2' && $row['fwd_repair_to_serv']!=''){ echo 'style="background-color:#FFC184"';}?> >
    
  <td><?php echo $i ?></td>
         
        <td>&nbsp;<?php echo $row['request_by'];?></td><td>&nbsp;<?php echo $row['req_date'];?></td>
  <td >&nbsp;<?php echo $row['name'];?></td>
  <td >&nbsp;<?php echo "$row[veh_reg] <br/><br/>($ip_box)";?></span></td>
   
  <td  >&nbsp;<?php echo  $row['device_imei'] ?></td>
  <td  >&nbsp;<?php echo  $row['Notwoking'] ?></td>
  <?php if($row['location']!=""){?>
  <td >&nbsp;<?php echo $row['location'];?></td>
        <?php }else{ $city= mysql_fetch_array(mysql_query("select * from tbl_city_name where branch_id='".$row['inter_branch']."'"));?>
        <td >&nbsp;<?php echo $city['city'];?></td>
        <?php }?>
  <td  >&nbsp;<?php echo $row['atime'] ;?></td>
  <td  style="font-size:12px">&nbsp;<?php echo $row['cnumber'] ;?></td>
  <td  style="font-size:12px"> &nbsp;<strong><?php echo $row['inst_name'];
  if($row['job_type']==2)
  {
   echo "<br/><font color='red'>(pending Job)</font>";
  }
  else
  {
   echo "<br/>(Ongoing Job)";
  }
  ?>
  </strong>
  </td>
    <? if($mode=='close')
    {?>
      <td  >&nbsp;<?php echo $row['reason'] ;?></td>
<? } ?>
         <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'services','popup1'); " class="topopup">View Detail</a></td>
       
  
 </tr>
  <?php  
    $i++;}
  
    ?>
</table>
     
   <div id="toPopup"> 
     
        <div class="close">close</div>
        <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
  <div id="popup1" style ="height:100%;width:100%"> <!--your content start-->
            

 
        </div> <!--your content end-->
    
    </div> <!--toPopup end-->
    
 <div class="loader"></div>
    <div id="backgroundPopup"></div>
</div>
 
 
 
<?
include("../include/footer.inc.php");

?>