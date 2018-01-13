<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/  
?> 

 <div class="top-bar">
                   
                    <h1>Closed Installation</h1>
					  
                </div>
                
         
                
                <div class="table">
<?php
 
//$num=mysql_num_rows(mysql_query("SELECT * FROM new_account_creation order by id DESC"));
  
 //installation_status

//$query = mysql_query("SELECT * FROM installation where flag=0 and reason!=''or rtime!=''  and branch_id=".$_SESSION['BranchId']."  order by id desc ");  

  //installation_status
////1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
$query = mysql_query("SELECT * FROM installation where (installation_status=5 or installation_status=6)   and branch_id=".$_SESSION['BranchId']."  order by id desc ");  

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
		<td width="10%" align="center">&nbsp;<?php $sales=mysql_fetch_array(mysql_query("select name from sales_person where id='$row[sales_person]' ")); echo $sales['name'];?></td>
		<td width="7%" align="center">&nbsp;<?php echo $row['client'];?></td>
		
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
           
          <td > Closed  </td>
		
         
      
		  
     
	</tr> <?php   }?>
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


 


 

 