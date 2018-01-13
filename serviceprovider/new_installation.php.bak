<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */
$mode=$_GET['mode']; 
?> 


<div class="top-bar">
                  
                    <h1>Open Installation : <?=$mode; ?></h1>
					<div style="float:center">
					  <div align="center"><br/>
	 	                <a href="new_installation.php?mode=installation">Installation
	 	                <? $sql_pending = select_query("SELECT COUNT(*)  as total FROM installation where installation_status='1' and instal_reinstall='installation' and inter_branch=0 and branch_id=".$_SESSION['BranchId']);
        				?>
	 	                ( <?=$sql_pending[0]['total']?> )</a> 
                        
                        | <a href="new_installation.php?mode=re_install">Re-Install
	 	                <? $sql_pending2 = select_query("SELECT COUNT(*)  as total FROM installation WHERE  installation_status='1' and instal_reinstall='re_install' and inter_branch=0 and branch_id=".$_SESSION['BranchId']);
        				?>
	 	                ( <?=$sql_pending2[0]['total']?> )</a>
                        
                        | <a href="new_installation.php?mode=inter_branch">Inter Branch
	 	                <? $sql_pending3 = select_query("SELECT COUNT(*)  as total FROM installation where installation_status='1' and inter_branch=".$_SESSION['BranchId']);
        					?>
	 	                ( <?=$sql_pending3[0]['total']?> )</a>
 

                        <br/>
                      </div>
  				</div>  	  
                </div>
                   <div class="top-bar">
                    
                  <div style="float:right";><font style="color:#F6F;font-weight:bold;">Purple:</font> Urgent Installation</div>
              
                </div>      
                
                <div class="table">
                <?php
 
	
$id=$_GET['id'];
//$sql="DELETE FROM services WHERE id='$id'";
//$result=mysql_query($sql);

$status=$_GET['status']	;
if($mode=='') { $mode="installation"; }
$day=$_GET['day'];


if($mode=='installation')
{
	if($day=='tomorrow')
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=0 and instal_reinstall='installation' and branch_id=".$_SESSION['BranchId']."  and time like '%".date("Y-m-d", strtotime( '+1 days' ))."%' order by id desc");
	}
	else if($day=='aftertomorrow')
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=0 and instal_reinstall='installation' and branch_id=".$_SESSION['BranchId']."  and time like '%".date("Y-m-d", strtotime( '+2 days' ))."%' order by id desc");
	}
	else
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=0 and instal_reinstall='installation' and branch_id=".$_SESSION['BranchId']." and time <= '".date("Y-m-d 23:59")."' order by id desc");
	}

	/*if($status=='back_to')
	{
		//$rs = mysql_query("SELECT * FROM installation WHERE (newpending='1' and newstatus='0') or (pending=1 and status=0 and newpending=0) and branch_id=".$_SESSION['BranchId']);
		$rs = mysql_query("SELECT * FROM installation WHERE installation_status=3 and branch_id=".$_SESSION['BranchId']);
	}
	else
	{
		//$rs = mysql_query("SELECT * FROM installation where status='1' and branch_id=".$_SESSION['BranchId']." order by id desc");
		$rs = mysql_query("SELECT * FROM installation where installation_status='1' and inter_branch=0 and branch_id=".$_SESSION['BranchId']." order by id desc");

	}*/
}
elseif($mode=='inter_branch')
{
	
	if($day=='tomorrow')
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=".$_SESSION['BranchId']."  and time like '%".date("Y-m-d", strtotime( '+1 days' ))."%' order by id desc");
	}
	else if($day=='aftertomorrow')
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=".$_SESSION['BranchId']."  and time like '%".date("Y-m-d", strtotime( '+2 days' ))."%' order by id desc");
	}
	else
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=".$_SESSION['BranchId']."  and time <= '".date("Y-m-d 23:59")."' order by id desc");
	}

	/*if($status=='back_to')
	{
		$rs = mysql_query("SELECT * FROM installation WHERE installation_status=3 and inter_branch=".$_SESSION['BranchId']);
	}
	else
	{
	$rs = mysql_query("SELECT * FROM installation where installation_status='1' and inter_branch=".$_SESSION['BranchId']." order by id desc");

	}*/
}
else
{

	if($day=='tomorrow')
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=0 and instal_reinstall='re_install' and branch_id=".$_SESSION['BranchId']."  and time like '%".date("Y-m-d", strtotime( '+1 days' ))."%' order by id desc");
	}
	else if($day=='aftertomorrow')
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=0 and instal_reinstall='re_install' and branch_id=".$_SESSION['BranchId']."  and time like '%".date("Y-m-d", strtotime( '+2 days' ))."%' order by id desc");
	}
	else
	{
	$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=0 and instal_reinstall='re_install' and branch_id=".$_SESSION['BranchId']."  and time <= '".date("Y-m-d 23:59")."' order by id desc");

	}
 
}

if(($mode=='installation' && ($day=='today' || $day=='tomorrow' || $day=='aftertomorrow')) || ($mode=='installation' || $mode=='') )
{				
	$today = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch=0 and instal_reinstall='installation' AND branch_id='".$_SESSION['BranchId']."' and time <= '".date("Y-m-d 23:59")."'"); 
	
	$tomorrow = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch=0 and instal_reinstall='installation' AND branch_id='".$_SESSION['BranchId']."' and time like '%".date("Y-m-d", strtotime( '+1 days' ))."%'"); 
	
	$aftertomorrow = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch=0 and instal_reinstall='installation' AND branch_id='".$_SESSION['BranchId']."' and time like '%".date("Y-m-d", strtotime( '+2 days' ))."%'");

}
	
if(($mode=='inter_branch' && ($day=='today' || $day=='tomorrow' || $day=='aftertomorrow')) || ($mode=='inter_branch'))
{
		
	$today = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch='".$_SESSION['BranchId']."' and time <= '".date("Y-m-d 23:59")."'");
	
	$tomorrow = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch='".$_SESSION['BranchId']."' and time like '%".date("Y-m-d", strtotime( '+1 days' ))."%'"); 
	
	$aftertomorrow = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch='".$_SESSION['BranchId']."' and time like '%".date("Y-m-d", strtotime( '+2 days' ))."%'");

}
	
if(($mode=='re_install' && ($day=='today' || $day=='tomorrow' || $day=='aftertomorrow')) || ($mode=='re_install'))
{
		
	$today = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch=0 and instal_reinstall='re_install' AND branch_id='".$_SESSION['BranchId']."' and time <= '".date("Y-m-d 23:59")."'"); 
	
	$tomorrow = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch=0 and instal_reinstall='re_install' AND branch_id='".$_SESSION['BranchId']."' and time like '%".date("Y-m-d", strtotime( '+1 days' ))."%'"); 
	
	$aftertomorrow = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch=0 and instal_reinstall='re_install' AND branch_id='".$_SESSION['BranchId']."' and time like '%".date("Y-m-d", strtotime( '+2 days' ))."%'");

}
	
?>

<div style="float:right"><a href="new_installation.php?day=today&mode=<?= $_GET['mode']?>">Today(<?php echo $today[0]["total"];?>)</a> | <a href="new_installation.php?day=tomorrow&mode=<?= $_GET['mode']?>"> Tomorrow(<?php echo $tomorrow[0]["total"];?>)</a>| <a href="new_installation.php?day=aftertomorrow&mode=<?= $_GET['mode']?>">Day After Tomorrow(<?php echo $aftertomorrow[0]["total"];?>)</a></div>
<p>&nbsp;</p>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
        <th>Sl. No</th>
        <th>Request By </th>
         <th>Request Date </th>
      	<th><font color="#0E2C3C"><b>Client Name </b></font></th>
		<th><font color="#0E2C3C"><b>Number of Installation <br/>(IP Box/ Required)</b></font></th>
		<th  ><font color="#0E2C3C"><b>Location</b></font></th>
		<th  ><font color="#0E2C3C"><b>Available Time</b></font></th>
		<th > <font color="#0E2C3C"><b>Person Name</b></font></th>
		<th  ><font color="#0E2C3C"><b>Contact No</b></font></th>
		<!--<th ><font color="#0E2C3C"><b>Installer Name</b></font></th>
		<th  ><font color="#0E2C3C"><b>Installer Current Location</b></font></th>
		<th  ><font color="#0E2C3C"><b>Reason</b></font></th>-->
	    <th  ><font color="#0E2C3C"><b>Payment/ Billing</b></font></th>
        <th  ><font color="#0E2C3C"><b>Data Pulling Time</b></font></th>
		 <th>View Detail</th>
    	<th ><b>Edit</b></font></th>
        <th ><b>Back to service</b></font></th>
		</tr>
	</thead>
	<tbody>

  
	<?php 
	
    //while ($row = mysql_fetch_array($rs)) 
	for($i=0;$i<count($rs);$i++)
	{
		if($rs[$i][IP_Box]=="") $ip_box="Not Required";  else $ip_box="$rs[$i][IP_Box]"; 
	
    ?>  

	<!-- <tr align="Center" <? if(($rs[$i]['reason'] && $rs[$i]['time']) ||  $rs[$i]['back_reason']) { ?> style="background:#CCCCCC" <? }?>> -->
	<tr align="Center" <?  if($rs[$i]['required']=='urgent'){ ?>style="background:#F6F" <? }?>>
	<td><?php echo $i+1; ?></td>
 
  
        
        <td>&nbsp;<?php echo $rs[$i]['request_by'];?></td><td>&nbsp;<?php echo $rs[$i]['req_date'];?></td>
		 <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$rs[$i]["user_id"];
	$rowuser=select_query($sql);
	?>
 		<td><?php echo $rowuser[0]["sys_username"];?></td> 
        
		<?php if($_SESSION['BranchId']==1 && $rs[$i]['instal_reinstall']=='installation'){?>
        
        <td >&nbsp;<?php $no_of_inst = $rs[$i]['installation_approve'] - $rs[$i]['installation_made']; echo $no_of_inst." <br/><br/>".($ip_box)?></span></td>
        
		 <?php } else {?>
         
        <td >&nbsp;<?php $no_of_inst = $rs[$i]['no_of_vehicals'] - $rs[$i]['installation_made']; echo $no_of_inst." <br/><br/>".($ip_box)?></span></td>
        
		<?php } ?>
        
		<!--<td  >&nbsp;<?php echo $rs[$i]['location'];?></td>-->
        <?php if($rs[$i]['location']!=""){?>
		<td >&nbsp;<?php echo $rs[$i]['location'];?></td>
        <?php }else{ $city= select_query("select * from tbl_city_name where branch_id='".$rs[$i]['inter_branch']."'");?>
        <td >&nbsp;<?php echo $city[0]['city'];?></td>
        <?php }?>
		<td>&nbsp;<?php echo $rs[$i]['time'];?></td>
		<td >&nbsp;<?php echo $rs[$i]['contact_person'];?></td>
		<td  >&nbsp;<?php echo $rs[$i]['contact_number'];?></td>
		<!--<td  >&nbsp;<?php echo $rs[$i]['inst_name'];?></td>
		<td  >&nbsp;<?php echo $rs[$i]['inst_cur_location'];?></td>
		<td >&nbsp;<?php echo $rs[$i]['reason'];?></td> -->
        <td >&nbsp;<?php echo $rs[$i]['payment']."/<br/>".$rs[$i]['billing'];?></td>
        <td >&nbsp;<?php echo $rs[$i]['datapullingtime'];?></td>
		 <td><a href="#" onclick="Show_record(<?php echo $rs[$i]["id"];?>,'installation','popup1'); " class="topopup">View Detail</a>
</td>
      <td  >&nbsp;<a href="edit_installation.php?id=<?=$rs[$i]['id'];?>&action=edit&show=edit">Edit</a></td><td><a href="edit_installation.php?id=<?=$rs[$i]['id'];?>&action=edit&show=backtoservice">Back to service</a></td>
        
        
		 
		
	</tr>
		<?php  
   // $i++;
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



 