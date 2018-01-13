<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/  
?> 


<div class="top-bar">
                  
                    <h1>New Installtion </h1>
					  
                </div>
                   <div class="top-bar">
                    
                  <div style="float:right";><font style="color:#F6F;font-weight:bold;">Purple:</font> Urgent Installation</div>
              
                </div>      
                
                <div class="table">
                <?php
 
	
$id=$_GET['id'];
$sql="DELETE FROM services WHERE id='$id'";
$result=mysql_query($sql);


	
$status=$_GET['status']	;
if($status=='back_to')
	{
	//$rs = mysql_query("SELECT * FROM installation WHERE (newpending='1' and newstatus='0') or (pending=1 and status=0 and newpending=0) and branch_id=".$_SESSION['BranchId']);
	$rs = mysql_query("SELECT * FROM installation WHERE installation_status=3 and branch_id=".$_SESSION['BranchId']);
	
	}
	else
	{
	//$rs = mysql_query("SELECT * FROM installation where status='1' and branch_id=".$_SESSION['BranchId']." order by id desc");
	$rs = mysql_query("SELECT * FROM installation where installation_status='1' and branch_id=".$_SESSION['BranchId']." order by id desc");

	}


?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
        <th>Sl. No</th>
        <th>Request By </th>
         <th>Request Date </th>
      <th  ><font color="#0E2C3C"><b>Client Name </b></font></th>
		<th  ><font color="#0E2C3C"><b>Number of Installation <br/>(IP Box/ Required)</b></font></th>
		 
		<th  ><font color="#0E2C3C"><b>Location</b></font></th>
		<th  ><font color="#0E2C3C"><b>Available Time</b></font></th>
		<th > <font color="#0E2C3C"><b>Person Name</b></font></th>
		<th  ><font color="#0E2C3C"><b>Contact No</b></font></th>
		<!--<th ><font color="#0E2C3C"><b>Installer Name</b></font></th>
		<th  ><font color="#0E2C3C"><b>Installer Current Location</b></font></th>
		<th  ><font color="#0E2C3C"><b>Reason</b></font></th>-->
		        <th  ><font color="#0E2C3C"><b>Payment/ Billing</b></font></th>
        <th  ><font color="#0E2C3C"><b>Data Pulling Time</b></font></th>
    	<th ><b>Edit</b></font></th>
        <th ><b>Back to service</b></font></th>
		</tr>
	</thead>
	<tbody>

  
	<?php 
	$i=1;
    while ($row = mysql_fetch_array($rs)) {
		if($row[IP_Box]=="") $ip_box="Not Required";  else $ip_box="$row[IP_Box]"; 
	
    ?>  

	<!-- <tr align="Center" <? if(($row['reason'] && $row['time']) ||  $row['back_reason']) { ?> style="background:#CCCCCC" <? }?>> -->
	<tr align="Center" <?  if($row['required']=='urgent'){ ?>style="background:#F6F" <? }?>>
	<td><?php echo $i ?></td>
 
  
        
        <td>&nbsp;<?php echo $row['request_by'];?></td><td>&nbsp;<?php echo $row['req_date'];?></td>
		 <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row["user_id"];
	$rowuser=select_query($sql);
	?>
  <td><?echo $rowuser[0]["sys_username"];?></td> 
		<td >&nbsp;<?php echo "$row[no_of_vehicals] <br/><br/>($ip_box)"?></span></td>
		
		 
		<td  >&nbsp;<?php echo $row['location'];?></td>
		<td>&nbsp;<?php echo $row['time'];?></td>
		<td >&nbsp;<?php echo $row['contact_person'];?></td>
		<td  >&nbsp;<?php echo $row['contact_number'];?></td>
		<!--<td  >&nbsp;<?php echo $row['inst_name'];?></td>
		<td  >&nbsp;<?php echo $row['inst_cur_location'];?></td>
		<td >&nbsp;<?php echo $row['reason'];?></td> -->
        <td >&nbsp;<?php echo "$row[payment]/$row[billing]";?></td>
        <td >&nbsp;<?php echo $row['datapullingtime'];?></td>
		
      <td  >&nbsp;<a href="edit_installation.php?id=<?=$row['id'];?>&action=edit&show=edit">Edit</a></td><td><a href="edit_installation.php?id=<?=$row['id'];?>&action=edit&show=backtoservice">Back to service</a></td>
        
        
		 
		
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



 