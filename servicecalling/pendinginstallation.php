<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */

$pagefor="";

$id=$_GET['id'];
if($_GET['action']=="close")
{
	//1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
$query1="UPDATE installation SET pending_closed='1',installation_status=6  WHERE id='$id'";
mysql_query($query1);
}

?> 

 <div class="top-bar">
                   
                    <h1>Pending Installation</h1>
					  
                </div>
                
         
                
                <div class="table">
<?php
 
	
	//$rs = mysql_query("SELECT * FROM installation WHERE (pending='1' or newpending='1')  and (pending_closed='0') and branch_id=".$_SESSION['BranchId'] ." order by id desc");
	//1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
	$rs = mysql_query("SELECT *,DATE_FORMAT(req_date,'%d %b %Y %h:%i %p') as req_date,DATE_FORMAT(time,'%d %b %Y %h:%i %p') as time FROM installation WHERE installation_status=3 and branch_id=".$_SESSION['BranchId'] ."  and request_by='".$_SESSION['username']."' order by id desc");
 

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr> <th>Sl. No</th>
			  <th width="11%" align="center"><font color="#0E2C3C"><b>Sales Person </b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>ClientName </b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>No.Of Vehicle </b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Location</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Model</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>time </b></font></th><!--
		<th width="11%" align="center"><font color="#0E2C3C"><b>Vehicle Type</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Immobilzer</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>DIMTS </b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Demo </b></font></th>-->
		<th width="11%" align="center"><font color="#0E2C3C"><b>Amount </b></font></th>
		
		<th width="11%" align="center"><font color="#0E2C3C"><b>Contact No</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Contact Person</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Contact Person No</b></font></th>
		  <th width="11%" align="center"><font color="#0E2C3C"><b>View Detail</b></font></th>
		  
		<th width="11%" align="center"><font color="#0E2C3C"><b>Edit</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Close</b></font></th>
		<!--<td width="11%" align="center"><font color="#0E2C3C"><b>Delete</b></font></td>-->
		
		</tr>
	</thead>
	<tbody>
 
 
 
<?php 
 
$i=1;
while($row=mysql_fetch_array($rs))
{
?>
<tr align="center" <? if( $row["support_comment"]!="" && $row["final_status"]!=1 ){ echo 'style="background-color:#ADFF2F"';} elseif($row["final_status"]==1){ echo 'style="background-color:#99FF66"';}elseif($row["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';}?> >
<td><?php echo $i ?></td>
  <td width="11%" align="center">&nbsp;<?php $sales=mysql_fetch_array(mysql_query("select name from sales_person where id='$row[sales_person]' ")); echo $sales['name'];?></td>
		  <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row["user_id"];
	$rowuser=select_query($sql);
	?>
  <td><?echo $rowuser[0]["sys_username"];?></td> 
		<td width="11%" align="center">&nbsp;<?php echo $row['no_of_vehicals'];?></td>
		
		
		<?php if($row['location']!=""){?>
		<td width="10%" align="center">&nbsp;<?php echo $row['location'];?></td>
        <?php }else{ $city= mysql_fetch_array(mysql_query("select * from tbl_city_name where branch_id='".$row['inter_branch']."'"));?>
        <td width="10%" align="center">&nbsp;<?php echo $city['city'];?></td>
        <?php }?>
		<td width="10%" align="center">&nbsp;<?php echo $row['model'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['time'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['veh_type'];?></td><!--
		<td width="12%" align="center">&nbsp;<?php echo $row['immoblizer_type'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['dimts'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['demo'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['payment_req'];?></td>-->
		
        <td width="12%" align="center">&nbsp;<?php echo $row['contact_number'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['contact_person'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['contact_person_no'];?></td>
		<td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'installation','popup1'); " class="topopup">View Detail</a>
</td>
		<td width="10%" align="center">&nbsp;<a href="add_installation.php?id=<?=$row['id'];?>&pending=1&action=editp">Edit</a></td>
				<td width="10%" align="center">&nbsp;<a href="?id=<?=$row['id'];?>&pending=1&action=close&pg=<? echo $pg;?>">Close</a></td>
</tr> <?php  $i++;  }?>
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


 






 


 



 

 









<?php

include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");  */
?> 



 
 <div class="top-bar">
                
                    <h1>Pending installation</h1>
					  
                </div>
                        
                
                <div class="table">
<?php


$id=$_GET['id'];
if($_GET['action']=="close")
{
$query1="UPDATE installation SET pending_closed='1'  WHERE id='$id'";
mysql_query($query1);
}


	$rs = mysql_query("SELECT * FROM installation WHERE (pending='1' or newpending='1')  and (pending_closed='0') and branch_id=".$_SESSION['BranchId'] ." order by id desc");	

?>


 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
        
      <th width="11%" align="center"><font color="#0E2C3C"><b>Sales Person </b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>ClientName </b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>No.Of Vehicle </b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Location</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Model</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>time </b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Vehicle Type</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Immobilzer</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>DIMTS </b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Demo </b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Amount </b></font></th>
		
		<th width="11%" align="center"><font color="#0E2C3C"><b>Contact No</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Contact Person</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Contact Person No</b></font></th>
		<td width="11%" align="center"><font color="#0E2C3C"><b>Edit</b></font></td>
		<td width="11%" align="center"><font color="#0E2C3C"><b>Close</b></font></td>
		<!--<td width="11%" align="center"><font color="#0E2C3C"><b>Delete</b></font></td>-->
		
 
		</tr>
	</thead>
	<tbody>


 
<?php  
    while ($row = mysql_fetch_array($rs)) {
	
    ?>  
	<tr align="Center">
		<td width="11%" align="center">&nbsp;<?php $sales=mysql_fetch_array(mysql_query("select name from sales_person where id='$row[sales_person]' ")); echo $sales['name'];?></td>
		<td width="11%" align="center">&nbsp;<?php echo $row['client'];?></td>
		<td width="11%" align="center">&nbsp;<?php echo $row['no_of_vehicals'];?></td>
		
		
		<?php if($row['location']!=""){?>
		<td width="10%" align="center">&nbsp;<?php echo $row['location'];?></td>
        <?php }else{ $city= mysql_fetch_array(mysql_query("select * from tbl_city_name where branch_id='".$row['inter_branch']."'"));?>
        <td width="10%" align="center">&nbsp;<?php echo $city['city'];?></td>
        <?php }?>
		<td width="10%" align="center">&nbsp;<?php echo $row['model'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['time'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['veh_type'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['immoblizer_type'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['dimts'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['demo'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['payment_req'];?></td>
		
        <td width="12%" align="center">&nbsp;<?php echo $row['contact_number'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['contact_person'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['contact_person_no'];?></td>
		<td width="10%" align="center">&nbsp;<a href="add_installation.php?id=<?=$row['id'];?>&pending=1&action=editp">Edit</a></td>
				<td width="10%" align="center">&nbsp;<a href="?id=<?=$row['id'];?>&pending=1&action=close&pg=<? echo $pg;?>">Close</a></td>

		<!--<td width="12%" align="center">&nbsp;<a href="services.php?id=<?php echo $row['id'];?>">Delete</a></td>-->
		
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


 