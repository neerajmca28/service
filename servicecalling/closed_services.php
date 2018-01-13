<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */ 
?> 

 <div class="top-bar">
                   
                    <h1>Closed Service</h1>
					  
                </div>
                
         
                
                <div class="table">
<?php
 
//$num=mysql_num_rows(mysql_query("SELECT * FROM new_account_creation order by id DESC"));
  
 
//$query = mysql_query("SELECT * FROM services where reason!=''and time!='' and branch_id=".$_SESSION['BranchId']." and atime>'".date("Y-m-d", strtotime($dateFrom))."' order by req_date desc ");  

  //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose

$query = mysql_query("SELECT * FROM services where (service_status=5 or service_status=6) and branch_id=".$_SESSION['BranchId']." and atime>'".date("Y-m-d", strtotime($dateFrom))."' order by req_date desc ");  

?>
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
		<th  ><font color="#0E2C3C"><b>Available Time</b></font></th><!--
		<th > <font color="#0E2C3C"><b>Person Name</b></font></th>
		<th  ><font color="#0E2C3C"><b>Contact No</b></font></th>
		<th ><font color="#0E2C3C"><b>Installer Name</b></font></th>
		<th  ><font color="#0E2C3C"><b>Installer Current Location</b></font></th>
		<th  ><font color="#0E2C3C"><b>Reason</b></font></th>
		<th  ><font color="#0E2C3C"><b>Time</b></font></th>
        <th  ><font color="#0E2C3C"><b>Payment/ Billing</b></font></th>
        <th  ><font color="#0E2C3C"><b>Data Pulling Time</b></font></th>-->
         <th>View Detail
</th>
       
        <th  ><font color="#0E2C3C"><b>Closed</b></font></th>
         
		</tr>
	</thead>
	<tbody>

  
	<?php 
	$i=1;
    while ($row = mysql_fetch_array($rs)) {
		if($row[IP_Box]=="") $ip_box="Not Required";  else $ip_box="$row[IP_Box]"; 
	
    ?>  

	<!-- <tr align="Center" <? if(($row['reason'] && $row['time']) ||  $row['back_reason']) { ?> style="background:#CCCCCC" <? }?>> -->
	<tr align="Center" <? if($row['reason'] && $row['time'])  {  ?> style="background:#CCCCCC;" <? }?>>
	<td><?php echo $i ?></td>
 
  
        
        <td>&nbsp;<?php echo $row['request_by'];?></td><td>&nbsp;<?php echo $row['req_date'];?></td>
		<td >&nbsp;<?php echo $row['name'];?></td>
		<td >&nbsp;<?php echo "$row[veh_reg] <br/><br/>($ip_box/ "; if($row['required']=='urgent') {?> <span style="color:#F00; font-size:14px;"> <?php } echo "$row[required] )"?></span></td>
		
		<td  >&nbsp;<?php echo  $row['Notwoking'] ?></td>
		<td  >&nbsp;<?php echo $row['location'];?></td>
		<td  >&nbsp;<?php echo $row['atime'] ;?></td><!--
		<td >&nbsp;<?php echo $row['pname'];?></td>
		<td  >&nbsp;<?php echo $row['cnumber'];?></td>
		<td  >&nbsp;<?php echo $row['inst_name'];?></td>
		<td  >&nbsp;<?php echo $row['inst_cur_location'];?></td>
		<td >&nbsp;<?php echo $row['reason'];?></td>
		<td  >&nbsp;<?php echo $row['time'];?></td>
        <td >&nbsp;<?php echo "$row[payment]/$row[billing]";?></td>
        <td >&nbsp;<?php echo $row['datapullingtime'];?></td>
		-->
         <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'services','popup1'); " class="topopup">View Detail</a>
</td>
        
          <td > Closed </td>
		
        
      
		 
		
	</tr><?php   }?>
</table>
     
    <div id="toPopup"> 
    	
        <div class="close">close</div>
       	<span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
		<div id="popup1"> <!--your content start-->
            

 
        </div> <!--your content end-->
    
    </div> <!--toPopup end-->
    
	<div class="loader"></div>
   	<div id="backgroundPopup"></div>
</div>
  
 
 
<?
include("../include/footer.inc.php");

?>


 
