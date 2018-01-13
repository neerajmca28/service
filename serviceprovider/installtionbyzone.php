<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/
?>
 
<div class="top-bar">

<h1>Zone Wise Installation</h1>

</div>
<div style="float:right"><br/><?
$rs = mysql_query("select * from zone where branch=".$_SESSION['BranchId']); 
 while ($row = mysql_fetch_array($rs))
 {
 $Numberofinst = mysql_query("select installation.id from  installation left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  where  installation.installation_status !=5 and 
installation.installation_status !=6 and id_region = ".$row["id"] ." and branch_id=".$_SESSION['BranchId']);
$count=mysql_num_rows($Numberofinst);
	 ?>
<a href="installtionbyzone.php?zone_id=<?= $row["id"];?>">
<?echo $row["name"]."(". $count.")";?>
</a>&nbsp;&nbsp;  || &nbsp;&nbsp;&nbsp;
 
 <?}
?>


 <br/>
</div>
 
 <br/>


                        
              <div class="top-bar">
                    
                  <div style="float:right";><font style="color:#F6F;font-weight:bold;">Purple:</font> Urgent Service</div>
<br/>
<div style="float:right";><font style="color:#CCCCCC;font-weight:bold;">Grey:</font> Closed Service</div>			  
               
                </div>
                <div class="table">
                <?php
 
	$mode=$_GET['zone_id'];
if($mode=='') { $mode="1"; }
	
    
	$rs = mysql_query("SELECT installation.* FROM installation  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id where installation_status!='5' and  installation_status!='6' and id_region = ".$mode." and branch_id=".$_SESSION['BranchId']);
	
	 


?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
        <th>Sl. No</th>
        <th>Request By </th>
         <th>Request Date </th>
      <th width="10%" align="center"><font color="#0E2C3C"><b>Sales Person  </b></font></th>
		<th width="10%" align="center"><font color="#0E2C3C"><b>Client Name </b></font></th>
		<th width="7%" align="center"><font color="#0E2C3C"><b>No.Of Vehicle <br/>/IP Box</b></font></th>
		
		<th width="9%" align="center"><font color="#0E2C3C"><b>Location</b></font></th>
		<th width="10%" align="center"><font color="#0E2C3C"><b>Model</b></font></th>
		<th width="10%" align="center"><font color="#0E2C3C"><b>Time</b></font></th><!--
		<th width="11%" align="center"><font color="#0E2C3C"><b>DIMTS</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Demo</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Amount</b></font></th>
		
		<th width="11%" align="center"><font color="#0E2C3C"><b>Vehicle Type</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Immobilizer Type</b></font></th>
		
		<th width="9%" align="center"><font color="#0E2C3C"><b>Contact No</b></font></th>
		<th width="13%" align="center"><font color="#0E2C3C"><b>Installer Name</b></font></th>
		<th width="8%" align="center"><font color="#0E2C3C"><b>Installer Current Location</b></font></th>
		<!--<th width="7%" align="center"><font color="#0E2C3C"><b>Reason</b></font></th> 
		<th width="4%" align="center"><font color="#0E2C3C"><b>Time</b></font></th>
		
        <th width="4%" align="center"><font color="#0E2C3C"><b>Payment/ Billing</b></font></th>
		
         <th width="4%" align="center"><font color="#0E2C3C"><b>Data Pulling Time</b></font></th>-->
		 <th>View Detail</th>
       <? if($mode=='close')
	   {?>
        <th  ><font color="#0E2C3C"><b>Closed</b></font></th>
        <?}
        else
        {?>
        
		<th  ><font color="#0E2C3C"><b>Edit</b></font></th>
        	
            	<th  ><font color="#0E2C3C"><b>Back to service</b></font></th>
                <th  ><font color="#0E2C3C"><b>Close</b></font></th>
		<!--<td ><font color="#0E2C3C"><b>Delete</b></font></td>-->
		
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
	<tr align="Center"  <? if($row['installation_status']==5 or $row['installation_status']==6 )  {  ?> style="background:#CCCCCC;" <? }
	else  if($row['required']=='urgent'){ ?>style="background:#F6F" <? }?>>
	<td><?php echo $i ?></td>
 
  
        
        <td>&nbsp;<?php echo $row['request_by'];?></td><td>&nbsp;<?php echo $row['req_date'];?></td>
		<td width="10%" align="center">&nbsp;<?php $sales=mysql_fetch_array(mysql_query("select name from sales_person where id='$row[sales_person]' ")); echo $sales['name'];?></td> <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row["user_id"];
	$rowuser=select_query($sql);
	?>
  <td><?echo $rowuser[0]["sys_username"];?></td> 
		
		<td width="10%" align="center">&nbsp;<?php echo "$row[no_of_vehicals] <br/><br/>/$ip_box";?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['location'];?></td>
		<td width="10%" align="center">&nbsp;<?php echo $row['model'];?></td>
		<td width="10%" align="center">&nbsp;<?php echo $row['time'];?></td><!--
		<td width="12%" align="center">&nbsp;<?php echo $row['dimts'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['demo'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['payment_req'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['veh_type'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['immobilizer_type'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['contact_number'];?></td>
		<td width="13%" align="center">&nbsp;<?php echo $row['inst_name'];?></td>
		<td width="8%" align="center">&nbsp;<?php echo $row['inst_cur_location'];?></td>
		<!--<td width="7%" align="center">&nbsp;<?php echo $row['reason'];?></td> 
		<td width="4%" align="center">&nbsp;<?php echo @date('d-M-Y h:m:s',@strtotime($row['rtime']));?></td>
       
         <td width="4%" align="center">&nbsp;<?php echo "$row[payment]/$row[billing]";?></td>
         
         <td width="4%" align="center">&nbsp;<?php echo $row['datapullingtime'];?></td>-->
         <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'installation','popup1'); " class="topopup">View Detail</a>
</td>
          <? if($mode=='close')
	   {?>
        
          <td >&nbsp; <span onClick="return editreason(<?php echo $row['id'];?>);"> Closed</span> </td>
		
        <?}
        else
        {?>
        
        <td ><a href="edit_newinstallation.php?id=<?=$row['id'];?>&action=edit&show=edit">Edit</a>  </td>
        
        <td ><a href="edit_newinstallation.php?id=<?=$row['id'];?>&action=edit&show=backtoservice">Back to Service</a> </td>
        
        <td ><a href="edit_newinstallation.php?id=<?=$row['id'];?>&action=edit&show=close">Close</a>  </td>
        
       <?}?> 
      
		  
     
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





 