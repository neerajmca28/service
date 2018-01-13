<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/  
?> 


<div class="top-bar">
                    
                    <h1>New Service </h1>
					  
                </div>
                        
                
                <div class="table">
                <?php
 
	$id=$_GET['id'];
$sql="DELETE FROM services WHERE id='$id'";
$result=mysql_query($sql);


	
$status=$_GET['status']	;
if($status=='back_to')
	{
		$rs = mysql_query("SELECT * FROM services WHERE service_status=3 and branch_id=".$_SESSION['BranchId'] );
	//$rs = mysql_query("SELECT * FROM services WHERE (newpending='1' and newstatus='0') or (pending=1 and status=0 and newpending=0) and branch_id=".$_SESSION['BranchId'] );
	}
	else
	{
	//$rs = mysql_query("SELECT * FROM services where status='1' and branch_id=".$_SESSION['BranchId'] ." order by id desc");
	$rs = mysql_query("SELECT * FROM services where service_status='1' and branch_id=".$_SESSION['BranchId'] ." order by id desc");
	
	}

 


?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
       <th>Sl. No</th>
        <th>Request By </th>
         <th>Request Date </th>
      <th ><b>ClientName </b></font></th>
		<th ><b>Vehicle No<br/>IP Box</b></font></th>
		<th ><b>Notworking</b></font></th>
		<th ><b>Model</b></font></th>
		<th ><b>Location</b></font></th>
		<th ><b>Available Time</b></font></th>
		<th ><b>Person Name</b></font></th>
		<th ><b>Contact No</b></font></th>
		 <th>View Detail</th>
		<th ><b>Edit</b></font></th>
        <th ><b>Back to service</b></font></th>
		<!--<td width="6%" align="center"><font color="#0E2C3C"><b>Delete</b></font></td>-->
		
 
		</tr>
	</thead>
	<tbody>

  
	<?php  
	$i=1;
    while ($row = mysql_fetch_array($rs)) {
	if($row[IP_Box]=="") $ip_box="Not Required";  else $ip_box="$row[IP_Box]"; 
    ?>  
	<tr align="Center" <? if($row['required']=='urgent'){ ?>style="background:#F6F" <? }?>>
	
     
<td><?php echo $i ?></td>
 
  
        
        <td>&nbsp;<?php echo $row['request_by'];?></td><td>&nbsp;<?php echo $row['req_date'];?></td>
      
		<td  >&nbsp;<?php echo $row['name'];?></td>
		<td >&nbsp;<?php echo "$row[veh_reg] <br/>/$ip_box";?></td>
		
		<td >&nbsp;<?php echo $row['Notwoking'];?></td>
		<td >&nbsp;<?php echo $row['device_model'];?></td>
		<td >&nbsp;<?php echo $row['location'];?></td>
		<td >&nbsp;<?php echo $row['atime'];?></td>
		<td >&nbsp;<?php echo $row['pname'];?></td>
		<td >&nbsp;<?php echo $row['cnumber'];?></td> 
		 <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'services','popup1'); " class="topopup">View Detail</a>
</td>
		<td  >&nbsp;<a href="editnewservice.php?id=<?=$row['id'];?>&action=edit&show=edit">Edit</a></td><td><a href="editnewservice.php?id=<?=$row['id'];?>&action=edit&show=backtoservice">Back to service</a></td>
        
        
       
		<!--<td width="11%" align="center">&nbsp;<a href="services_from_sales.php?id=<?php echo $row['id'];?>">Delete</a></td>-->
		
	</tr>
		 
  <?php $i++; }?>
	 
   
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





  