<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/
?>
 
<div class="top-bar">

<h1>Zone Wise Service</h1>

</div>
<div style="float:right"><br/><?
$rs = select_query("select * from zone where branch=".$_SESSION['BranchId']); 

// while ($row = mysql_fetch_array($rs))
 for($i=0;$i<count($rs);$i++)
 {
  	  $Numberofservice = select_query("select services.id from  services left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id  where  services.service_status !=5 and 
services.service_status !=6 and id_region = ".$rs[$i]["id"] ." and branch_id=".$_SESSION['BranchId']);

$total = count($rs);

	 ?>
	 	 <a href="servicebyzone.php?zone_id=<?= $rs[$i]["id"];?>">
<?echo $rs[$i]["name"]."(". $total.")";?>
</a>&nbsp;&nbsp;  || &nbsp;&nbsp;&nbsp;

 <?}
?>


 <br/>
</div>
 
 <br/>


                        
              <div class="top-bar">
                    
                  <div style="float:right";><font style="color:#F6F;font-weight:bold;">Purple:</font> Urgent Service</div>
<br/>
<div style="float:right";><font style="color:#CCCCCC;font-weight:bold;">Grey:</font> Closed Service</div>			  
               
                </div>
                <div class="table">
                <?php
 
	 $mode=$_GET['zone_id'];
if($mode=='') { $mode="1"; }
	
	
 $rs = select_query("SELECT services.* FROM services  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id where service_status!='5' and  service_status!='6' and id_region = ".$mode." and branch_id=".$_SESSION['BranchId']);

  
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
        <th>Sl. No</th>
        <th>Request By </th>
         <th>Request Date </th>
         <th><font color="#0E2C3C"><b>Client Name </b></font></th>
		<th  ><font color="#0E2C3C"><b>Vehicle No <br/>(IP Box/ Required)</b></font></th>
		<th  ><font color="#0E2C3C"><b>Notworking </b></font></th>
		<th  ><font color="#0E2C3C"><b>Location</b></font></th>
		<th  ><font color="#0E2C3C"><b>Available Time</b></font></th><!--
		<th > <font color="#0E2C3C"><b>Person Name</b></font></th>
		<th  ><font color="#0E2C3C"><b>Contact No</b></font></th>
		<th ><font color="#0E2C3C"><b>Installer Name</b></font></th>
		<th  ><font color="#0E2C3C"><b>Installer Current Location</b></font></th>
		<th  ><font color="#0E2C3C"><b>Reason</b></font></th>
		<th  ><font color="#0E2C3C"><b>Time</b></font></th>
        <th  ><font color="#0E2C3C"><b>Payment/ Billing</b></font></th>
        <th  ><font color="#0E2C3C"><b>Data Pulling Time</b></font></th>-->
         <th>View Detail</th>
       <? if($mode=='close')
	   {?>
        <th  ><font color="#0E2C3C"><b>Closed</b></font></th>
        <?}
        else
        {?>
        
		<th  ><font color="#0E2C3C"><b>Edit</b></font></th>
        	
            	<th  ><font color="#0E2C3C"><b>Back to service</b></font></th>
                <th  ><font color="#0E2C3C"><b>Close</b></font></th>
		<!--<td ><font color="#0E2C3C"><b>Delete</b></font></td>-->
		
 <?}?>
		</tr>
	</thead>
	<tbody>

  
	<?php 
	
    //while ($row = mysql_fetch_array($rs)) 
	for($j=0;$j<count($rs);$j++)
	{
		if($rs[$j][IP_Box]=="") $ip_box="Not Required";  else $ip_box="$rs[$j][IP_Box]"; 
	
    ?>  

	<!-- <tr align="Center" <? if(($rs[$j]['reason'] && $rs[$j]['time']) ||  $rs[$j]['back_reason']) { ?> style="background:#CCCCCC" <? }?>> -->
	<tr align="Center"   <? if($rs[$j]['service_status']==5 or $rs[$j]['service_status']==6 )  {  ?> style="background:#CCCCCC;" <? }
	else  if($rs[$j]['required']=='urgent'){ ?>style="background:#F6F" <? }?> >
 	<td><?php echo $j ?></td>
 
  
        
        <td>&nbsp;<?php echo $rs[$j]['request_by'];?></td><td>&nbsp;<?php echo $rs[$j]['req_date'];?></td>
		<td >&nbsp;<?php echo $rs[$j]['name'];?></td>
		<td >&nbsp;<?php echo "$rs[$j][veh_reg] <br/><br/>($ip_box)";?></span></td>
		
		<td  >&nbsp;<?php echo  $rs[$j]['Notwoking'] ?></td>
		<td  >&nbsp;<?php echo $rs[$j]['location'];?></td>
		<td  >&nbsp;<?php echo $rs[$j]['atime'] ;?></td> 
         <td><a href="#" onclick="Show_record(<?php echo $rs[$j]["id"];?>,'services','popup1'); " class="topopup">View Detail</a>
</td>
        <? if($rs[$j]['service_status']==5 or $rs[$j]['service_status']==6 ) { ?>
        
          <td >&nbsp; <span onClick="return editreason(<?php echo $rs[$j]['id'];?>);"> Closed</span> </td>
		
        <?}
        else
        {?>
        
        <td ><a href="editservice.php?id=<?=$rs[$j]['id'];?>&action=edit&show=edit">Edit</a>  </td>
        
        <td ><a href="editservice.php?id=<?=$rs[$j]['id'];?>&action=edit&show=backtoservice">Back to Service</a> </td>
        
        <td ><a href="editservice.php?id=<?=$rs[$j]['id'];?>&action=edit&show=close">Close</a>  </td>
        
       <?}?> 
      
		 
		
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





 