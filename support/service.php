<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/  
?> 
 
		<div class="top-bar">
		<h1>Service List</h1>
		</div> 
		<div class="top-bar">
		<div style="float:right";><font style="color:#F6F;font-weight:bold;">Purple:</font> Urgent Service</div>
		<br/>
		<div style="float:right";><font style="color:#CCCCCC;font-weight:bold;">Grey:</font> Closed Service</div>			  
		</div>  <div class="table">

		<?php
		$mode=$_GET['mode'];
		if($mode=='') { $mode="new"; }
//limit 500
  if($mode=='close')
	{
	 
	$rs = mysql_query("SELECT * FROM services where (service_status='5' or service_status='6') and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc limit 1000");
	 
	}
	else if($mode=='new')
	{
	 
	$rs = mysql_query("SELECT * FROM services  where service_status!='5' and service_status!='6'  and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc");
	}	

	else if($mode=='backtoservice')
	{
	 
	$rs = mysql_query("SELECT * FROM services  where  (service_status='3' or service_status='4')  and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc");
	}
 

 
?>
<div style="float:right"><a href="service.php?mode=new">New</a> | <a href="service.php?mode=backtoservice">Back to service</a> | <a href="service.php?mode=close">Closed</a></div>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
        <th>Sl. No</th>
        <th>Request By </th>
         <th>Request Date </th>
      <th  ><font color="#0E2C3C"><b>Client Name </b></font></th>
		<th  ><font color="#0E2C3C"><b>Vehicle No <br/>(IP Box/ Required)</b></font></th>
		<th  ><font color="#0E2C3C"><b>Notworking </b></font></th>
		<th  ><font color="#0E2C3C"><b>Location</b></font></th>
		<th  ><font color="#0E2C3C"><b>Available Time</b></font></th> 
		<th  ><font color="#0E2C3C"><b>Reason</b></font></th> 
		<th  ><font color="#0E2C3C"><b>Installer Name</b></font></th> 
         <th>View Detail</th>
       <? if($mode=='close')
	   {?>
        <th  ><font color="#0E2C3C"><b>Closed</b></font></th>
        <?}?>
         
		</tr>
	</thead>
	<tbody>

  
	<?php 
	$i=1;
    while ($row = mysql_fetch_array($rs)) {
		if($row[IP_Box]=="") $ip_box="Not Required";  else $ip_box="$row[IP_Box]"; 
	
    ?>  

	<!-- <tr align="Center" <? if(($row['reason'] && $row['time']) ||  $row['back_reason']) { ?> style="background:#CCCCCC" <? }?>> -->
	<tr align="Center"   <? if($row['service_status']==5 or $row['service_status']==6 )  {  ?> style="background:#CCCCCC;" <? }
	else  if($row['required']=='urgent'){ ?>style="background:#F6F" <? }?> >
 	<td><?php echo $i ?></td>
 
  
        
        <td>&nbsp;<?php echo $row['request_by'];?></td><td>&nbsp;<?php echo $row['req_date'];?></td>
		<td >&nbsp;<?php echo $row['name'];?></td>
		<td >&nbsp;<?php echo "$row[veh_reg] <br/><br/>($ip_box)";?></span></td>
		
		<td  >&nbsp;<?php echo  $row['Notwoking'] ?></td>
        
        <?php if($row['location']!=""){?>
		<td >&nbsp;<?php echo $row['location'];?></td>
        <?php }else{ $city= mysql_fetch_array(mysql_query("select * from tbl_city_name where branch_id='".$row['inter_branch']."'"));?>
        <td >&nbsp;<?php echo $city['city'];?></td>
        <?php }?>
        
		<td  >&nbsp;<?php echo $row['atime'] ;?></td> 
			<?  
		if($mode=='new')
	{
	$reason=$row['back_reason'];
	}
	elseif($mode=='backtoservice')
	{
	$reason=$row['back_reason'];
	}
	else{
		$reason=$row['reason'];
		}
		?>
		<td  >&nbsp;<?php echo $reason ;?></td>
		<td  >&nbsp;<?php echo $row['inst_name'] ;?></td> 
         <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'services','popup1'); " class="topopup">View Detail</a>
</td>
        <? if($row['service_status']==5 or $row['service_status']==6 ) { ?>
        
          <td >&nbsp; <span onClick="return editreason(<?php echo $row['id'];?>);"> Closed</span> </td>
		
        <?}?>
        
      
		 
		
	</tr>
		<?php  
    $i++;}
	 
    ?>
</table>
     
   <div id="toPopup"> 
    	
        <div class="close">close</div>
       	<span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
		<div id="popup1" style ="height:100%;width:100%">  

 
        </div>  
    
    </div>  
    
	<div class="loader"></div>
   	<div id="backgroundPopup"></div>
</div>
 
 
 
<?
include("../include/footer.inc.php");

?>





 