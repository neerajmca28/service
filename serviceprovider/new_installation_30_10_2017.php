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
	 	                <? $sql_pending = select_query("SELECT COUNT(*)  as total FROM installation where installation_status='1' and (instal_reinstall='installation' or instal_reinstall='crack') and inter_branch=0 and branch_id=".$_SESSION['BranchId']);
        				?>
	 	                ( <?=$sql_pending[0]['total']?> )</a> 
                        
                        | <a href="new_installation.php?mode=re_install">Re-Install
	 	                <? $sql_pending2 = select_query("SELECT COUNT(*)  as total FROM installation WHERE  installation_status='1' and instal_reinstall='re_install' and inter_branch=0 and branch_id=".$_SESSION['BranchId']);
        				?>
	 	                ( <?=$sql_pending2[0]['total']?> )</a>
                        | <a href="new_installation.php?mode=online_crack">Online Crack
	 	                <? $sql_pending4 = select_query("SELECT COUNT(*)  as total FROM installation where installation_status='1' and inter_branch=".$_SESSION['BranchId']);
        					?>
	 	                ( <?=$sql_pending4[0]['total']?> )</a>
 
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
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=0 and (instal_reinstall='installation' or instal_reinstall='crack') and branch_id=".$_SESSION['BranchId']."  and time like '%".date("Y-m-d", strtotime( '+1 days' ))."%' order by id desc");
	}
	else if($day=='aftertomorrow')
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=0 and (instal_reinstall='installation'  or instal_reinstall='crack') and branch_id=".$_SESSION['BranchId']."  and time like '%".date("Y-m-d", strtotime( '+2 days' ))."%' order by id desc");
	}
	else
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=0 and (instal_reinstall='installation'  or instal_reinstall='crack') and branch_id=".$_SESSION['BranchId']." and time <= '".date("Y-m-d 23:59")."' order by id desc");
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
else if($mode=='online_crack')
{
	if($day=='tomorrow')
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=0 and instal_reinstall='online_crack' and branch_id=".$_SESSION['BranchId']."  and time like '%".date("Y-m-d", strtotime( '+1 days' ))."%' order by id desc");
	}
	else if($day=='aftertomorrow')
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=0 and instal_reinstall='online_crack' and branch_id=".$_SESSION['BranchId']."  and time like '%".date("Y-m-d", strtotime( '+2 days' ))."%' order by id desc");
	}
	else
	{
		$rs = select_query("SELECT * FROM installation WHERE installation_status='1' and inter_branch=0 and instal_reinstall='online_crack' and branch_id=".$_SESSION['BranchId']." and time <= '".date("Y-m-d 23:59")."' order by id desc");
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
	//echo '<pre>'; print_r($rs); die;
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
	$today = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch=0 and (instal_reinstall='installation' or instal_reinstall='crack') AND branch_id='".$_SESSION['BranchId']."' and time <= '".date("Y-m-d 23:59")."'"); 
	
	$tomorrow = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch=0 and (instal_reinstall='installation' or instal_reinstall='crack') AND branch_id='".$_SESSION['BranchId']."' and time like '%".date("Y-m-d", strtotime( '+1 days' ))."%'"); 
	
	$aftertomorrow = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch=0 and (instal_reinstall='installation' or instal_reinstall='crack') AND branch_id='".$_SESSION['BranchId']."' and time like '%".date("Y-m-d", strtotime( '+2 days' ))."%'");

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

if(($mode=='online_crack' && ($day=='today' || $day=='tomorrow' || $day=='aftertomorrow')) || ($mode=='online_crack'))
{
		
	$today = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch=0 and instal_reinstall='online_crack' AND branch_id='".$_SESSION['BranchId']."' and time <= '".date("Y-m-d 23:59")."'"); 
	
	$tomorrow = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch=0 and instal_reinstall='online_crack' AND branch_id='".$_SESSION['BranchId']."' and time like '%".date("Y-m-d", strtotime( '+1 days' ))."%'"); 
	
	$aftertomorrow = select_query("SELECT COUNT(*) AS total FROM installation WHERE  installation_status='1' AND inter_branch=0 and instal_reinstall='online_crack' AND branch_id='".$_SESSION['BranchId']."' and time like '%".date("Y-m-d", strtotime( '+2 days' ))."%'");

}
	
?>

<div style="float:right"><a href="new_installation.php?day=today&mode=<?= $_GET['mode']?>">Today(<?php echo $today[0]["total"];?>)</a> | <a href="new_installation.php?day=tomorrow&mode=<?= $_GET['mode']?>"> Tomorrow(<?php echo $tomorrow[0]["total"];?>)</a>| <a href="new_installation.php?day=aftertomorrow&mode=<?= $_GET['mode']?>">Day After Tomorrow(<?php echo $aftertomorrow[0]["total"];?>)</a></div>
<p>&nbsp;</p>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
        <th>Job Type</th>
        <th>Request By </th>
         <th>Request Date </th>
      	<th><font color="#0E2C3C"><b>Client Name </b></font></th>
		<th><font color="#0E2C3C"><b>Number of Installation <br/>(IP Box/ Required)</b></font></th>
		<th><font color="#0E2C3C"><b>Branch Location</b></font></th>
		<th><font color="#0E2C3C"><b>Landmark</b></font></th>
		<th><font color="#0E2C3C"><b>Device Type</b></font></th>
        <th><font color="#0E2C3C"><b>Accessories</b></font></th>
        <th><font color="#0E2C3C"><b>Available Time<b></font></th>
		<th><font color="#0E2C3C"><b>Contact Details</b></font></th>
		<th>View Detail</th>
    	<th><b>Edit</b></font></th>
        <th><b>Back to service</b></font></th>
        <th><b>Forward to R&D</b></font></th>
        
		</tr>
	</thead>
	<tbody>

  
	<?php 
	
    for($i=0;$i<count($rs);$i++)
	{
		if($rs[$i][IP_Box]=="") $ip_box="Not Required";  else $ip_box="$rs[$i][IP_Box]"; 
	
    ?>  

	<tr align="Center" <?  if($rs[$i]['required']=='urgent'){ ?>style="background:#F6F" <? }?>>
	<td><?php 
       
            $sql1 = select_query("select instal_reinstall from installation_request WHERE id='".$rs[$i]['id']."'");
      
            if($rs[$i]['instal_reinstall'] == "installation"){ echo "I";} 
            elseif($rs[$i]['instal_reinstall'] == "re_install"){ echo "Re-Add";}
            elseif($rs[$i]['instal_reinstall'] == "online_crack"){ echo "OC";}
            elseif($rs[$i]['instal_reinstall'] == "crack"){ echo "C";}
          
          ?>
        </td></td>
 
  
        
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
        
        
        
		<td>
        <?php 
       
          $sql1 = select_query("select Zone_area from installation WHERE id='".$rs[$i]['id']."'");
		  $sql2 = select_query("SELECT name FROM re_city_spr_1 WHERE id='".$sql1[0]['Zone_area']."'");

          echo $sql2[0]['name'];
          
         ?>
        </td>
       <!--  <td>
        	<?php 
        	$sql_InstRqst2 = select_query("select inst_req_id from installation WHERE id='".$rs[$i]['id']."'");
            $sql_Inst2 = select_query("select landmark from installation_request WHERE id='".$sql_InstRqst2[0]['inst_req_id']."'");
              	
              	echo $sql_Inst2[0]['landmark']."<br>";
            ?>  	
        </td> -->
          <td align="">
         <?php 
        	$sql_InstRqst2 = select_query("select inst_req_id from installation WHERE id='".$rs[$i]['id']."'");
            $sql_Inst2 = select_query("select landmark from installation_request WHERE id='".$sql_InstRqst2[0]['inst_req_id']."'");
              	
              	echo $sql_Inst2[0]['landmark']."<br>";
            ?>  	
          

        </td>
     
        <td align="center" nowrap>

          <?php 
       
             $sqlDevice=select_query("SELECT device_type FROM device_type where id='".$rs[$i]["device_type"]."' "); 
             $sqlModel=select_query("SELECT device_model FROM device_model where id='".$rs[$i]["model"]."' "); 
         
            echo $sqlModel[0]['device_model']."</br>";
            echo $sqlDevice[0]['device_type'];
        
          ?>

          
        </td>
        <td>
        	<?php
        		echo $rs[$i]['acess_selection'];
        	?>
        </td>
        <td>
			<?php 
				echo date("Y-m-d",strtotime($rs[$i]['time']))."<br>";
	           	echo date("G:i",strtotime($rs[$i]['time']))."<br>";
            	echo $rs[$i]['atime_status'];
			?>
		</td>		
        <td>
			<?php
		
				$sql_InstRqst = select_query("select inst_req_id from installation WHERE id='".$rs[$i]['id']."'");
              	$sql_Inst = select_query("select contact_person,contact_number,designation from installation_request WHERE id='".$sql_InstRqst[0]['inst_req_id']."'");
              	
              	echo $sql_Inst[0]['contact_person']."<br>";
              	echo $sql_Inst[0]['contact_number']."<br>";
              	echo $sql_Inst[0]['designation']."<br>";
          	?>
		</td>
		<td><a href="#" onclick="Show_record(<?php echo $rs[$i]["id"];?>,'installation','popup1'); " class="topopup">View Detail</a>
</td>
      <td  >&nbsp;<a href="edit_installation.php?id=<?=$rs[$i]['id'];?>&action=edit&show=edit">Edit</a></td><td><a href="edit_installation.php?id=<?=$rs[$i]['id'];?>&action=edit&show=backtoservice">Back to service</a></td>
      <?php if($rs[$i]['instal_reinstall']=='online_crack' )
      { ?>
     <td><a href="#" onclick="sendToRepairOnlineCrack(<?php echo $rs[$i]["id"];?>);">Send to Repair</a>
</td>
     <?php } 
     else { 
     	echo "<td>Send to Repair</td>"; 
    	 }
     ?> 

		
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
<script>
 function sendToRepairOnlineCrack(RowId)
{
	//var txt;
    if (confirm("Are You Sure to Send to R&D") == true) {
      onlineCrackUpdate(RowId);
    } 
    //alert(RowId);
    //return false;
}
function onlineCrackUpdate(RowId)
{
	//alert(RowId);
	$.ajax({
	        type:"GET",
	        url:Path +"userInfo.php?action=onlineCrackRND",
	        //data:"category="+category+"&expairy="+expairy1+"&duration="+durationlist+"&sortby="+sortby+"&price="+price+"&search="+search1,
	         data:"RowId="+RowId,
	        success:function(msg){
	            //alert(msg);
	          
	       document.location.href = 'new_installation.php?mode=online_crack';
	                        
	        }
	    });
}
</script>

 