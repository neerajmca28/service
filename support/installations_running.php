<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */ 
?> 


<div class="top-bar">
                     
                    <h1>Installation List</h1>
					  
                </div>
                        
                <div class="top-bar">
                    
                  <div style="float:right";><font style="color:#ADFF2F;font-weight:bold;">GreenYellow:</font> Urgent Installation</div>
<br/>
<div style="float:right";><font style="color:#CCCCCC;font-weight:bold;">Grey:</font> Closed Installation</div>			  
               
                </div> 
                <div class="table">
<? $mode=$_GET['mode'];
if($mode=='') { $mode="new"; }
	
  if($mode=='close')
	{
	 
	$rs = mysql_query("SELECT * FROM installation where (installation_status='5' or installation_status='6') and  (branch_id=".$_SESSION['BranchId']."  or inter_branch=".$_SESSION['BranchId'].") order by id desc limit 500");
	}
	else if($mode=='new')
	{
	
	$rs = mysql_query("SELECT * FROM installation  where installation_status=2  and (branch_id=".$_SESSION['BranchId'] ." or inter_branch=".$_SESSION['BranchId'].") order by id desc");
	}	

	?>
    
    <div style="float:right"><a href="installations_running.php?mode=new">New</a> | <a href="installations_running.php?mode=close">Closed</a></div>
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
		<th width="10%" align="center"><font color="#0E2C3C"><b>Time</b></font></th>
		<th width="10%" align="center"><font color="#0E2C3C"><b>Client Contact No.</b></font></th>
		<th width="10%" align="center"><font color="#0E2C3C"><b>Installer Name</b></font></th>
		 <th>View Detail</th>
      
		</tr>
	</thead>
	<tbody>

  
	<?php 
	$i=1;
    while ($row = mysql_fetch_array($rs)) {
		if($row[IP_Box]=="") $ip_box="Not Required";  else $ip_box="$row[IP_Box]"; 
	
    ?>  

	<tr align="Center"  <? if($row['installation_status']==5 or $row['installation_status']==6 )  {  ?> style="background:#CCCCCC;" <? }
	else  if($row['required']=='urgent'){ ?>style="background:#ADFF2F" <? }?>>
	<td><?php echo $i ?></td>
 
  
        
        <td>&nbsp;<?php echo $row['request_by'];?></td><td>&nbsp;<?php echo $row['req_date'];?></td>
		<td width="10%" align="center">&nbsp;<?php $sales=mysql_fetch_array(mysql_query("select name from sales_person where id='$row[sales_person]' ")); echo $sales['name'];?></td> <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row["user_id"];
	$rowuser=select_query($sql);
	?>
  <td><?echo $rowuser[0]["sys_username"];?></td> 
		
		<td width="10%" align="center">&nbsp;<?php echo "$row[no_of_vehicals] <br/><br/>/$ip_box";?></td>
        
        <?php if($row['location']!=""){?>
		<td width="9%" align="center">&nbsp;<?php echo $row['location'];?></td>
        <?php }else{ $city= mysql_fetch_array(mysql_query("select * from tbl_city_name where branch_id='".$row['inter_branch']."'"));?>
        <td width="9%" align="center">&nbsp;<?php echo $city['city'];?></td>
        <?php }?>
        
		<td width="10%" align="center">&nbsp;<?php echo $row['model'];?></td>
		<td width="10%" align="center">&nbsp;<?php echo $row['time'];?></td>
		<td width="10%" align="center" style="font-size:12px">&nbsp;<?php echo $row['contact_number'];?></td>
		<td width="10%" align="center" style="font-size:12px">&nbsp;<?php echo $row['inst_name'];?></td>
        <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'installation','popup1'); " class="topopup">View Detail</a></td>
     
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
