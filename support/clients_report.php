<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */
$tablename="";
 if($_POST["submit"])
 {
	 if($_POST["job"]=="Installation")
	 {
	 $tablename="installation";
		 if($_POST["FromDate"]=="")
		 {
			 $queryRes = mysql_query("select installation.id,req_date,request_by,company_name,inst_id,inst_name,rtime,re_city_spr_1.name as zonearea from installation
left join re_city_spr_1   on installation.Zone_area = re_city_spr_1.id where user_id= ". $_POST["main_user_id"]);
		 }
		 else
		 {
			 $queryRes = mysql_query("select installation.id,req_date,request_by,company_name,inst_id,inst_name,rtime,re_city_spr_1.name as zonearea from installation
left join re_city_spr_1   on installation.Zone_area = re_city_spr_1.id where user_id= ". $_POST["main_user_id"]." and req_date>='". $_POST["FromDate"]."' and req_date<='" . $_POST["ToDate"]. " 23:59:59". "'");
		 }
	 
 
	

 	 }
	 else
	 {
		$tablename="services";
 if($_POST["FromDate"]=="")
 {
	 $queryRes = mysql_query("select services.id,req_date,request_by,company_name,inst_id,inst_name,close_date,veh_reg,device_imei,reason,date_of_installation,re_city_spr_1.name as zonearea from services
left join re_city_spr_1   on services.Zone_area = re_city_spr_1.id  where user_id= ". $_POST["main_user_id"]);
	 }
	 else
 {
	$queryRes = mysql_query("select services.id,req_date,request_by,company_name,inst_id,inst_name,close_date,veh_reg,device_imei,reason,date_of_installation,re_city_spr_1.name as zonearea from services
left join re_city_spr_1   on services.Zone_area = re_city_spr_1.id  where user_id= ". $_POST["main_user_id"]." and req_date>='". $_POST["FromDate"]."' and req_date<='" . $_POST["ToDate"]. " 23:59:59". "'");
 
	 }	
	 }
  
 }
?> 


<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
var j = jQuery.noConflict();
j(function() 
{
j( "#FromDate" ).datepicker({ dateFormat: "yy-mm-dd" });

j( "#ToDate" ).datepicker({ dateFormat: "yy-mm-dd" });

});

</script>
 
 <form name="myForm" action=""   method="post">


<table cellspacing="5" cellpadding="5">

<tr>

<td >Client Name</td>
<td>
 
 <select name="main_user_id" id="main_user_id">
        <option value="" >-- Select One --</option>
        <?php
        $main_user_iddata=mysql_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE sys_active=1 ORDER BY `name` ASC");
        while ($data=mysql_fetch_assoc($main_user_iddata))
        {
			if($data['user_id']==$_POST['main_user_id'])
			{
				$selected="selected";
			}
			else
			{
				$selected="";
			}
        ?>
        
        <option   value ="<?php echo $data['user_id'] ?>"  <?echo $selected;?>>
        <?php echo $data['name']; ?>
        </option>
        <?php 
        } 
        
        ?>
        </select>


</td>
<td >From Date</td>
<td>
<input type="text" name="FromDate" id="FromDate" value="<?echo  $_POST["FromDate"]?>"/></td>

<td>To Date</td>
<td>
<input type="text" name="ToDate" id="ToDate"  value="<?echo  $_POST["ToDate"]?>" /></td>
<td>
<input type="radio" name="job" id="Service" value="Service" <? if($_POST["job"]=="Service") echo "checked"?>/>Service <input type="radio" name="job" id="Installation" value="Installation" <? if($_POST["job"]=="Installation") echo "checked"?>/>Installation
</td>
<td align="center"> <input type="submit" name="submit" value="submit"  /></td>
</tr>
 
</table>
</form>
 <div class="top-bar">
                    
                    <h1>Client's Report</h1>
					  
                </div>

                <div class="table">

 
   
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
        <th>Sl. No</th>
        <th>Request By </th>
         <th>Request Date </th>
      
		<th width="10%" align="center"><font color="#0E2C3C"><b>Company Name </b></font></th>
        <th width="10%" align="center"><font color="#0E2C3C"><b>Vehicle Number </b></font></th>
		 <th width="10%" align="center"><font color="#0E2C3C"><b>Device IMEI </b></font></th>
		 <th width="10%" align="center"><font color="#0E2C3C"><b>Installer</b></font></th>
		 
		 
		<th width="10%" align="center"><font color="#0E2C3C"><b>Area</b></font></th>
		<?    if($_POST["job"]=="Service"){ ?>
		<th width="10%" align="center"><font color="#0E2C3C"><b>Reason</b></font></th>
		 <? } ?>
		
		<th width="9%" align="center"><font color="#0E2C3C"><b>Close date</b></font></th>
		
		 
		 <th>View Detail</th>
       
        
		</tr>
	</thead>
	<tbody>
   
	<?php 
	$i=1;
	 
   while($row=mysql_fetch_array($queryRes))
{
	 
	
    ?>  

	<!-- <tr align="Center" <? if(($row['reason'] && $row['time']) ||  $row['back_reason']) { ?> style="background:#CCCCCC" <? }?>> -->
	<tr align="Center" >
	<td><?php echo $i ?></td>
 
    
        
        <td>&nbsp;<?php echo $row['request_by'];?></td>
		<td>&nbsp;<?php echo $row['req_date'];?></td>

	 
  <td><?php echo $row['company_name'];?></td> 
  <td>&nbsp;<?php echo $row['veh_reg'];?></td>
		  <td>&nbsp;<?php echo $row['device_imei'];?></td>
	<td>&nbsp;<?php echo $row['inst_name'];?></td>
		 <td width="10%" align="center">&nbsp;<?php echo $row['zonearea'];?></td>
		 <?    if($_POST["job"]=="Service"){ ?>
  <td>&nbsp;<?php echo $row['reason'];?></td>		 <? } ?>
		 
		 <?  if($_POST["job"]=="Installation"){ 
		 $close_date=$row['rtime'];
		 }
		 else {
		 $close_date=$row['close_date'];
		 }
		 ?>
		<td width="9%" align="center">&nbsp;<?php echo $close_date;?></td>
		
 
         <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'<?echo $tablename ?>','popup1'); " class="topopup">View Detail</a>
</td>
           
      
		  
     
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

 