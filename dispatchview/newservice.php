<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu_dispatch.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu_dispatch.php");*/
$mode=$_GET['mode'];
?> 


<div class="top-bar">
                    
                    <h1>Open Service : <?=$mode; ?></h1>
					<div style="float:center">
					  <div align="center"><br/>
	 	                <a href="newservice.php?mode=service">Service
	 	                <? $sql_pending = select_query("SELECT COUNT(*) as total FROM internalsoftware.services WHERE  service_status='1' and  service_reinstall='service' and inter_branch=0 and branch_id=".$_SESSION['BranchId']);
        				?>
	 	                ( <?=$sql_pending[0]['total']?> )</a> 
                        
                        | <a href="newservice.php?mode=re_install">Re-Install
	 	                <? $sql_pending2 = select_query("SELECT COUNT(*) as total FROM internalsoftware.services WHERE  service_status='1' and service_reinstall='re_install' and inter_branch=0 and branch_id=".$_SESSION['BranchId']);
        				?>
	 	                ( <?=$sql_pending2[0]['total']?> )</a>
                        
                        | <a href="newservice.php?mode=inter_branch">Inter Branch
	 	                <? $sql_pending3 = select_query("SELECT COUNT(*) as total FROM internalsoftware.services WHERE  service_status='1' and inter_branch=".$_SESSION['BranchId']); ?>
	 	                ( <?=$sql_pending3[0]['total']?> )</a>
 

                        <br/>
                      </div>
 				  </div>  
                </div>
                        
                
                <div class="table">
                  <p>
                    <?php
 
	$id=$_GET['id'];
//$sql="DELETE FROM services WHERE id='$id'";
//$result=mysql_query($sql);


$day=$_GET['day'];
	
if($mode=='') { $mode="service"; }

if($mode=='service')
{
	 
	if($day=='tomorrow')
	{
		 
		$rs = select_query("SELECT * FROM internalsoftware.services WHERE service_status='1' and inter_branch=0 and service_reinstall='service' and branch_id=".$_SESSION['BranchId']."  and atime like '%".date("Y-m-d", strtotime( '+1 days' ))."%' order by id desc");
	}
	else if($day=='aftertomorrow')
	{
		$rs = select_query("SELECT * FROM internalsoftware.services WHERE service_status='1' and inter_branch=0 and service_reinstall='service' and branch_id=".$_SESSION['BranchId']."  and atime like '%".date("Y-m-d", strtotime( '+2 days' ))."%' order by id desc");
	}
	else
	{
		$rs = select_query("SELECT * FROM internalsoftware.services WHERE service_status='1' and inter_branch=0 and service_reinstall='service' and branch_id=".$_SESSION['BranchId']." and atime <= '".date("Y-m-d 23:59")."' order by id desc");
	}
 
	//echo "SELECT * FROM services WHERE service_status='1' and service_reinstall='service' and branch_id=".$_SESSION['BranchId']."  and atime like '%".date("Y-m-d")."%' order by id desc";

	//$rs = mysql_query("SELECT * FROM services WHERE (newpending='1' and newstatus='0') or (pending=1 and status=0 and newpending=0) and branch_id=".$_SESSION['BranchId'] );
}
elseif($mode=='inter_branch')
{
	 
	if($day=='tomorrow')
	{
		$rs = select_query("SELECT * FROM internalsoftware.services WHERE service_status='1' and inter_branch=".$_SESSION['BranchId']."  and atime like '%".date("Y-m-d", strtotime( '+1 days' ))."%' order by id desc");
	}
	else if($day=='aftertomorrow')
	{
		$rs = select_query("SELECT * FROM internalsoftware.services WHERE service_status='1' and inter_branch=".$_SESSION['BranchId']."  and atime like '%".date("Y-m-d", strtotime( '+2 days' ))."%' order by id desc");
	}
	else
	{
		$rs = select_query("SELECT * FROM internalsoftware.services WHERE service_status='1' and inter_branch=".$_SESSION['BranchId']."  and atime <= '".date("Y-m-d 23:59")."' order by id desc");
	}
 
}
else
{

if($day=='tomorrow')
	{
		 
		$rs = select_query("SELECT * FROM internalsoftware.services WHERE service_status='1' and inter_branch=0 and service_reinstall='re_install' and branch_id=".$_SESSION['BranchId']."  and atime like '%".date("Y-m-d", strtotime( '+1 days' ))."%' order by id desc");
	}
	else if($day=='aftertomorrow')
	{
		$rs = select_query("SELECT * FROM internalsoftware.services WHERE service_status='1' and inter_branch=0 and service_reinstall='re_install' and branch_id=".$_SESSION['BranchId']."  and atime like '%".date("Y-m-d", strtotime( '+2 days' ))."%' order by id desc");
	}
	else
	{
		$rs = select_query("SELECT * FROM internalsoftware.services WHERE service_status='1' and inter_branch=0 and service_reinstall='re_install' and branch_id=".$_SESSION['BranchId']."  and atime <= '".date("Y-m-d 23:59")."' order by id desc");

	}
 

	//$rs = mysql_query("SELECT * FROM services where status='1' and branch_id=".$_SESSION['BranchId'] ." order by id desc");
	
}

if(($mode=='service' && ($day=='today' || $day=='tomorrow' || $day=='aftertomorrow')) || ($mode=='service' || $mode=='') ){		
		
			$today = select_query("SELECT COUNT(*) AS total FROM internalsoftware.services WHERE  service_status='1' AND inter_branch=0 and service_reinstall='service' AND branch_id='".$_SESSION['BranchId']."' and atime <= '".date("Y-m-d 23:59")."'"); 

			$tomorrow = select_query("SELECT COUNT(*) AS total FROM internalsoftware.services WHERE  service_status='1' AND inter_branch=0 and service_reinstall='service' AND branch_id='".$_SESSION['BranchId']."' and atime like '%".date("Y-m-d", strtotime( '+1 days' ))."%'"); 

			$aftertomorrow = select_query("SELECT COUNT(*) AS total FROM internalsoftware.services WHERE  service_status='1' AND inter_branch=0 and service_reinstall='service' AND branch_id='".$_SESSION['BranchId']."' and atime like '%".date("Y-m-d", strtotime( '+2 days' ))."%'");

}

if(($mode=='inter_branch' && ($day=='today' || $day=='tomorrow' || $day=='aftertomorrow')) || ($mode=='inter_branch')){
		
		$today = select_query("SELECT COUNT(*) AS total FROM internalsoftware.services WHERE  service_status='1' AND inter_branch='".$_SESSION['BranchId']."' and atime <= '".date("Y-m-d 23:59")."'");

		$tomorrow = select_query("SELECT COUNT(*) AS total FROM internalsoftware.services WHERE  service_status='1' AND inter_branch='".$_SESSION['BranchId']."' and atime like '%".date("Y-m-d", strtotime( '+1 days' ))."%'"); 

		$aftertomorrow = select_query("SELECT COUNT(*) AS total FROM internalsoftware.services WHERE  service_status='1' AND inter_branch='".$_SESSION['BranchId']."' and atime like '%".date("Y-m-d", strtotime( '+2 days' ))."%'");
}

if(($mode=='re_install' && ($day=='today' || $day=='tomorrow' || $day=='aftertomorrow')) || ($mode=='re_install')){
		
		$today = select_query("SELECT COUNT(*) AS total FROM internalsoftware.services WHERE  service_status='1' AND inter_branch=0 and service_reinstall='re_install' AND branch_id='".$_SESSION['BranchId']."' and atime <= '".date("Y-m-d 23:59")."'"); 

		$tomorrow = select_query("SELECT COUNT(*) AS total FROM internalsoftware.services WHERE  service_status='1' AND inter_branch=0 and service_reinstall='re_install' AND branch_id='".$_SESSION['BranchId']."' and atime like '%".date("Y-m-d", strtotime( '+1 days' ))."%'"); 

		$aftertomorrow = select_query("SELECT COUNT(*) AS total FROM internalsoftware.services WHERE  service_status='1' AND inter_branch=0 and service_reinstall='re_install' AND branch_id='".$_SESSION['BranchId']."' and atime like '%".date("Y-m-d", strtotime( '+2 days' ))."%'");
}


?>

</p>
<div style="float:right"><a href="newservice.php?day=today&mode=<?= $_GET['mode']?>">Today(<?php echo $today[0]["total"];?>)</a> | <a href="newservice.php?day=tomorrow&mode=<?= $_GET['mode']?>">Tomorrow(<?php echo $tomorrow[0]["total"];?>)</a>| <a href="newservice.php?day=aftertomorrow&mode=<?= $_GET['mode']?>">Day After Tomorrow(<?php echo $aftertomorrow[0]["total"];?>)</a></div>

  <p>&nbsp;                  </p>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
            <th>Sl. No</th>
            <th>Request By </th>
            <th>Request Date </th>
            <th><b>ClientName </b></font></th>
            <th><b>Vehicle No<br/>IP Box</b></th>
            <th><b>Device IMEI</b></th>
            <th><b>Model</b></font></th>
            <th><b>Location</b></font></th>
            <th><b>Available Time</b></font></th>
            <th><b>Person Name</b></font></th>
            <th><b>Contact No</b></font></th>
            <th><b>Comment</b></font></th>
            <th>View Detail</th>
            <!--<th><b>Edit</b></font></th>
            <th><b>Back to service</b></font></th>-->
            <!--<td width="6%" align="center"><font color="#0E2C3C"><b>Delete</b></font></td>-->
		
 
		</tr>
	</thead>
	<tbody>

  
	<?php  
	
   // while ($row = mysql_fetch_array($rs)) 
	for($i=0;$i<count($rs);$i++)
	{
	if($rs[$i][IP_Box]=="") $ip_box="Not Required";  else $ip_box="$rs[$i][IP_Box]"; 
    ?>  
	<tr align="Center" <? if($rs[$i]['required']=='urgent'){ ?>style="background:#ADFF2F" <? }?>>
	
     
        <td><?php echo $i+1;?></td>
        <td>&nbsp;<?php echo $rs[$i]['request_by'];?></td><td>&nbsp;<?php echo $rs[$i]['req_date'];?></td>
        <td>&nbsp;<?php echo $rs[$i]['name'];?></td>
        <td>&nbsp;<?php echo $rs[$i]['veh_reg']." <br/>/".$ip_box;?></td>
        <td>&nbsp;<?php echo $rs[$i]['device_imei']; ?></td>
        <td >&nbsp;<?php echo $rs[$i]['device_model'];?></td>
        <?php if($rs[$i]['location']!=""){?>
        <td >&nbsp;<?php echo $rs[$i]['location'];?></td>
        <?php }else{ $city= select_query("select * from internalsoftware.tbl_city_name where branch_id='".$rs[$i]['inter_branch']."'");?>
        <td >&nbsp;<?php echo $city[0]['city'];?></td>
        <?php }?>
        <td >&nbsp;<?php echo $rs[$i]['atime'];?></td>
        <td >&nbsp;<?php echo $rs[$i]['pname'];?></td>
        <td >&nbsp;<?php echo $rs[$i]['cnumber'];?></td> 
        <td >&nbsp;<?php echo $rs[$i]['comment'];?></td> 
        <td><a href="#" onclick="Show_record(<?php echo $rs[$i]["id"];?>,'services','popup1'); " class="topopup">View Detail</a></td>
		<!--<td  >&nbsp;<a href="editnewservice.php?id=<?=$rs[$i]['id'];?>&action=edit&show=edit">Edit</a></td><td><a href="editnewservice.php?id=<?=$rs[$i]['id'];?>&action=edit&show=backtoservice">Back to service</a></td>-->
        
        
       
		<!--<td width="11%" align="center">&nbsp;<a href="services_from_sales.php?id=<?php echo $rs[$i]['id'];?>">Delete</a></td>-->
		
	</tr>
		 
  <?php }?>
	 
   
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





  