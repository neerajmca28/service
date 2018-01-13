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
	//$rs = mysql_query("SELECT * FROM services where reason!='' and time!='' and branch_id=".$_SESSION['BranchId'] ." order by id desc");
	$rs = select_query("SELECT * FROM services where (service_status='5' or service_status='6') and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc limit 1000");
	
	//where 
	}
	else if($mode=='new')
	{
	//$rs = mysql_query("SELECT * FROM services  where inst_name!='' and inst_cur_location!='' and newpending!='1' order by id desc");
	
	//$rs = mysql_query("SELECT * FROM services  where reason='' and time='' and inst_name!='' and inst_cur_location!='' and newpending!='1' and branch_id=".$_SESSION['BranchId'] ." order by id desc");
	$rs = select_query("SELECT * FROM services  where service_status='2'  and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc");
	}	
 


?>
<div style="float:right"><a href="service.php?mode=new">New</a> | <a href="service.php?mode=close">Closed</a></div>
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
		
		<!--
		<th > <font color="#0E2C3C"><b>Person Name</b></font></th>
		<th  ><font color="#0E2C3C"><b>Contact No</b></font></th>
		<th ><font color="#0E2C3C"><b>Installer Name</b></font></th>
		<th  ><font color="#0E2C3C"><b>Installer Current Location</b></font></th>
		<th  ><font color="#0E2C3C"><b>Reason</b></font></th>
		<th  ><font color="#0E2C3C"><b>Time</b></font></th>
        <th  ><font color="#0E2C3C"><b>Payment/ Billing</b></font></th>
        <th  ><font color="#0E2C3C"><b>Data Pulling Time</b></font></th>-->
		  <? if($mode=='close')
	   {?>
		<th  ><font color="#0E2C3C"><b>Reason</b></font></th>
		<? } ?>
		
         <th>View Detail</th>
       <? if($mode=='close')
	   {?>
	    
        <th  ><font color="#0E2C3C"><b>Closed</b></font></th>
        <?}
        else
        {?>
                	
        <th  ><font color="#0E2C3C"><b>Back to service</b></font></th>
		<th  ><font color="#0E2C3C"><b>Edit</b></font></th>
        <th  ><font color="#0E2C3C"><b>Close</b></font></th>
		<!--<td ><font color="#0E2C3C"><b>Delete</b></font></td>-->
		
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
        
	<tr align="Center" <? if($rs[$i]['service_status']==5 or $rs[$i]['service_status']==6 ){ echo 'style="background-color:#CCCCCC"';} elseif($rs[$i]['required']=='urgent'){ 
	echo 'style="background-color:#ADFF2F"';} elseif($rs[$i]['service_status']=='2' && $rs[$i]['fwd_repair_to_serv']!=''){ echo 'style="background-color:#FFC184"';}?> >
    
 	<td><?php echo $i+1; ?></td>
         
        <td>&nbsp;<?php echo $rs[$i]['request_by'];?></td><td>&nbsp;<?php echo $rs[$i]['req_date'];?></td>
		<td >&nbsp;<?php echo $rs[$i]['name'];?></td>
		<td >&nbsp;<?php echo $rs[$i]['veh_reg']." <br/><br/>(".$ip_box.")";?></span></td>
			
		<td  >&nbsp;<?php echo  $rs[$i]['device_imei'] ?></td>
		<td  >&nbsp;<?php echo  $rs[$i]['Notwoking'] ?></td>
		<?php if($rs[$i]['location']!=""){?>
		<td >&nbsp;<?php echo $rs[$i]['location'];?></td>
        <?php }else{ $city= select_query("select * from tbl_city_name where branch_id='".$rs[$i]['inter_branch']."'");?>
        <td >&nbsp;<?php echo $city[0]['city'];?></td>
        <?php }?>
		<td  >&nbsp;<?php echo $rs[$i]['atime'] ;?></td>
		<td  style="font-size:12px">&nbsp;<?php echo $rs[$i]['cnumber'] ;?></td>
		<td  style="font-size:12px"> &nbsp;<strong><?php echo $rs[$i]['inst_name'];
		if($rs[$i]['job_type']==2)
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
	   		<td  >&nbsp;<?php echo $rs[$i]['reason'] ;?></td>
<? } ?>
		<!--
		<td >&nbsp;<?php echo $rs[$i]['pname'];?></td>
		<td  >&nbsp;<?php echo $rs[$i]['cnumber'];?></td>
		<td  >&nbsp;<?php echo $rs[$i]['inst_name'];?></td>
		<td  >&nbsp;<?php echo $rs[$i]['inst_cur_location'];?></td>
		<td >&nbsp;<?php echo $rs[$i]['reason'];?></td>
		<td  >&nbsp;<?php echo $rs[$i]['time'];?></td>
        <td >&nbsp;<?php echo "$rs[$i][payment]/$rs[$i][billing]";?></td>
        <td >&nbsp;<?php echo $rs[$i]['datapullingtime'];?></td>
		-->
         <td><a href="#" onclick="Show_record(<?php echo $rs[$i]["id"];?>,'services','popup1'); " class="topopup">View Detail</a>
    <?php if($rs[$i]['fwd_serv_to_repair']=="" && $rs[$i]['fwd_repair_to_serv']=="" && $rs[$i]['service_status']==2){?> 
         |<a href="#" onclick="return forwardtoRepair(<?php echo $rs[$i]["id"];?>);"  >Forward to Repair</a>
    <?php } ?>
</td>
        <? if($rs[$i]['service_status']==5 or $rs[$i]['service_status']==6 ) { ?>
        
          <td >&nbsp; <span onClick="return editreason(<?php echo $rs[$i]['id'];?>);"> Closed</span> </td>
		
        <?php }
        else
        {?>
               
        <?php if($rs[$i]["inter_branch"]==0 || $rs[$i]["inter_branch"]==$_SESSION['BranchId']){?>
        
        <td ><a href="editservice.php?id=<?=$rs[$i]['id'];?>&action=edit&show=backtoservice">Back to Service</a> </td>
        <td ><a href="editservice.php?id=<?=$rs[$i]['id'];?>&action=edit&show=edit">Edit</a>  </td>
        <td ><a href="editservice.php?id=<?=$rs[$i]['id'];?>&action=edit&show=close">Close</a>  </td>
        
        <?php }else{?>
        <td >Back to Service</td>
        <td >Edit  </td>
        <td >Close</td>
        
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
            

 
        </div> <!--your content end-->
    
    </div> <!--toPopup end-->
    
	<div class="loader"></div>
   	<div id="backgroundPopup"></div>
</div>
 
 
 
<?
include("../include/footer.inc.php");

?>





 