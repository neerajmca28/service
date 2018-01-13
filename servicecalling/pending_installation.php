<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/ 

$id=$_GET['id'];
?> 
<script type="text/javascript">
function ConfirmReactivate(row_id)
{
   var retVal = prompt("Write Comment : ", "Comment");
  if (retVal)
  {
  addreasonComment(row_id,retVal);
      return ture;
  }
  else
    return false;
}

function addreasonComment(row_id,retVal)
{
	//alert(user_id);
	//return false;
$.ajax({
		type:"GET",
		url:"userInfo.php?action=InstallationClosed",
 		 
		 data:"row_id="+row_id+"&comment="+retVal,
		success:function(msg){
			// alert(msg);
		 
		location.reload(true);		
		}
	});
}
</script>

 <div class="top-bar">
                   
                    <h1>Pending Installation</h1>
					  
                </div>
                
         
                
                <div class="table">
<?php
 
	
	//1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose,7-New request,8-show admin,9-Again Confirmation,11-forwardtorepair,15-pending installation
	$rs = mysql_query("SELECT * FROM installation where installation_status='15' and branch_id=".$_SESSION['BranchId']." and request_by='".$_SESSION['username']."' order by id desc");
 

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
        <tr> 
            <th>Sl. No</th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Sales Person </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>ClientName </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>No.Of Vehicle </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Location</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Model</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>time </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Vehicle Type </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Contact No</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Contact Person</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Pending Installation</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>View Detail</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Edit</b></font></th>
            
		</tr>
	</thead>
	<tbody>
 
 
 
<?php 
 
  $i=1;
while($row=mysql_fetch_array($rs))
{
?>
<tr align="center" <? if($row["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';}?> >
<td><?php echo $i ?></td>
  <td width="11%" align="center">&nbsp;<?php $sales=mysql_fetch_array(mysql_query("select name from sales_person where id='$row[sales_person]' ")); echo $sales['name'];?></td>
		  <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row["user_id"];
	$rowuser=select_query($sql);
	?>
  <td><?echo $rowuser[0]["sys_username"];?></td> 
		<td width="11%" align="center">&nbsp;<?php echo $row['no_of_vehicals'];?></td>
		
		
		<td width="10%" align="center">&nbsp;<?php echo $row['location'];?></td>
		<td width="10%" align="center">&nbsp;<?php echo $row['model'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['time'];?></td>
		<td width="12%" align="center">&nbsp;<?php echo $row['veh_type'];?></td>
		
        <td width="12%" align="center">&nbsp;<?php echo $row['contact_number'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['contact_person'];?></td>
        <td width="12%" align="center">&nbsp;<?php $row_inst_made = $row['installation_made'];
			if(($row['branch_id']==1 && $row['inter_branch']==0) || ($row['inter_branch']==1 && $row['branch_id']!=1))
			{
				$inst_approve = $row['installation_approve'];
			}else{
				$inst_approve = $row['no_of_vehicals'];
			}
			echo $total = $inst_approve - $row_inst_made;?></td>
		
		<td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'installation','popup1'); " class="topopup">View Detail</a>
        | <a href="#" onclick="return ConfirmReactivate(<?php echo $row["id"];?>);" >Closed</a>
        </td>
		
		<td width="10%" align="center">&nbsp;<a href="update_installation.php?id=<?=$row['id'];?>&pending=1&action=edit">Edit</a></td>
				
</tr> <?php   $i++; }?>
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




 