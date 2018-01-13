<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/
 
?> 



 
 <div class="top-bar">
                    <a href="installer_contact.php" class="button">ADD NEW </a>
                    <h1>Installer Contact List </h1>
					  
                </div>
                    
                
                <div class="table">
<?php
 
//$num=mysql_num_rows(mysql_query("SELECT * FROM new_account_creation order by id DESC"));
 
$query = select_query("SELECT * FROM installer where branch_id=".$_SESSION['BranchId']);

?>


 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
            <th>SL.No</th>
            <th>Installer Name</th>
            <th>Address</th>
            <th>Specialist</th>
            <th>Tool Kit</th>
            <th>Work Status</th>
            <th>Contact No.</th>
            <th> Branch </th>
            <th>View Detail</th>
            <!--<th>Edit</th>-->
		</tr>
	</thead>
	<tbody>


 
<?php 

//while($row=mysql_fetch_array($query))
for($i=0;$i<count($query);$i++)
{
?>
 
<td><?php echo $i+1;?></td>

<td><?php echo $query[$i]["inst_name"];?></td> 
<td><?php echo $query[$i]["address"];?></td> 
<td><?php echo $query[$i]["specialist"];?></td> 
<td><?php echo $query[$i]["toolkit"];?></td> 
<td><?php echo $query[$i]["work_status"];?></td> 
<td><?php echo $query[$i]["installer_mobile"];?></td> 

<? $sql="select * from gtrac_branch  where id=".$query[$i]["branch_id"];
$rowuser=select_query($sql);
?>

<td><?php echo $rowuser[0]["branch_name"];?></td> 

<td><a href="#" onClick="Show_record(<?php echo $query[$i]["inst_id"];?>,'installer','popup1'); " class="topopup">View Detail</a>
</td>
<!--<td>
<a href="installer_contact.php?id=<?=$query[$i]['inst_id'];?>&action=edit<? echo $pg;?>">Edit</a></td>-->
		<!--<td width="12%" align="center">&nbsp;<a href="services.php?id=<?php echo $query[$i]['inst_id'];?>&action=delete">Delete</a></td>-->


</td>
</tr> <?php }?>
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
 
<?php
include("../include/footer.inc.php"); ?>

