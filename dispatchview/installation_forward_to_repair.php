<?php 
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu_dispatch.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu_dispatch.php");*/  
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
  <?php
 
	$rs = select_query("SELECT * FROM installation  where (installation_status='11' and fwd_install_to_repair is NOT NULL) and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."') order by id desc");


?>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      <tr>
        <th>Sl. No</th>
        <th>Request By </th>
        <th>Request Date </th>
        <th width="10%" align="center"><font color="#0E2C3C"><b>Sales Person </b></font></th>
        <th width="10%" align="center"><font color="#0E2C3C"><b>Client Name </b></font></th>
        <?php if($_SESSION['BranchId']==1){?>
        <th width="7%" align="center"><font color="#0E2C3C"><b>Approve Vehicle <br/>
          /IP Box</b></font></th>
        <?php } else{?>
        <th width="7%" align="center"><font color="#0E2C3C"><b>No.Of Vehicle <br/>
          /IP Box</b></font></th>
        <?php }?>
        <th width="9%" align="center"><font color="#0E2C3C"><b>Location</b></font></th>
        <th width="10%" align="center"><font color="#0E2C3C"><b>Model</b></font></th>
        <th width="10%" align="center"><font color="#0E2C3C"><b>Time</b></font></th>
        <th width="10%" align="center"><font color="#0E2C3C"><b>Client Contact No.</b></font></th>
        <th width="10%" align="center"><font color="#0E2C3C"><b>Installer Name</b></font></th>
        <th  ><font color="#0E2C3C"><b>Comment</b></font></th>
        <th>View Detail</th>
      </tr>
    </thead>
    <tbody>
      <?php 
	
    //while ($row = mysql_fetch_array($rs)) 
	for($i=0;$i<count($rs);$i++)
	{
		if($rs[$i][IP_Box]=="") $ip_box="Not Required";  else $ip_box="$rs[$i][IP_Box]"; 
	
    ?>
      <tr align="Center"  <? if($rs[$i]['installation_status']==5 or $rs[$i]['installation_status']==6 )  {  ?> style="background:#CCCCCC;" <? }
	else  if($rs[$i]['required']=='urgent'){ ?>style="background:#ADFF2F" <? }?>>
        <td><?php echo $i+1; ?></td>
        <td>&nbsp;<?php echo $rs[$i]['request_by'];?></td>
        <td>&nbsp;<?php echo $rs[$i]['req_date'];?></td>
        <td width="10%" align="center">&nbsp;
          <?php $sales=select_query("select name from sales_person where id='$rs[$i][sales_person]' "); echo $sales[0]['name'];?></td>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$rs[$i]["user_id"];
			$rowuser=select_query($sql);
		?>
        <td><?php echo $rowuser[0]["sys_username"];?></td>
        <?php if($_SESSION['BranchId']==1){?>
        <td width="10%" align="center">&nbsp;<?php echo $rs[$i]['installation_approve']." <br/><br/>/".$ip_box;?></td>
        <?php } else {?>
        <td width="10%" align="center">&nbsp;<?php echo $rs[$i]['no_of_vehicals']." <br/><br/>/".$ip_box;?></td>
        <?php } ?>
        <?php if($rs[$i]['location']!=""){?>
        <td width="9%" align="center">&nbsp;<?php echo $rs[$i]['location'];?></td>
        <?php }else{ $city= select_query("select * from tbl_city_name where branch_id='".$rs[$i]['inter_branch']."'");?>
        <td width="9%" align="center">&nbsp;<?php echo $city[0]['city'];?></td>
        <?php }?>
        <td width="10%" align="center">&nbsp;<?php echo $rs[$i]['model'];?></td>
        <td width="10%" align="center">&nbsp;<?php echo $rs[$i]['time'];?></td>
        <td width="10%" align="center" style="font-size:12px">&nbsp;<?php echo $rs[$i]['contact_number'];?></td>
        <td width="10%" align="center" style="font-size:12px">&nbsp;<?php echo $rs[$i]['inst_name'];?></td>
        <td>&nbsp;<?php echo $rs[$i]['fwd_install_to_repair'] ;?></td>
        <td><a href="#" onclick="Show_record(<?php echo $rs[$i]["id"];?>,'installation','popup1'); " class="topopup">View Detail</a></td>
      </tr>
      <?php  
    }
	 
    ?>
  </table>
  <div id="toPopup">
    <div class="close">close</div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup1" style ="height:100%;width:100%"> <!--your content start--> 
      
    </div>
    <!--your content end--> 
    
  </div>
  <!--toPopup end-->
  
  <div class="loader"></div>
  <div id="backgroundPopup"></div>
</div>
<?
include("../include/footer.inc.php");

?>
