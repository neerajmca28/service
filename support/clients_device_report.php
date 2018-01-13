<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/private/master.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); 
include($_SERVER['DOCUMENT_ROOT']."/format/private/master.php");*/

$masterObj = new master();

$tablename="";

 if(isset($_POST))
 {
	 
	 
	if($_POST["main_user_id"]!="")
	{ 
	
	  $query = $masterObj->getClientDevice($_POST["main_user_id"]);
	  
	/*$querydevice="select latest_telemetry.gps_fix,latest_telemetry.tel_voltage,latest_telemetry.tel_poweralert,services.id as id,services.id,sys_created,veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact,round(gps_speed*1.609,0) as speed, case when tel_ignition=true then true else false end as aconoff , geo_street as street, latest_telemetry.gps_latitude as lat,tbl_history_devices.sys_group_id,
	latest_telemetry.gps_longitude as lng ,devices.imei,services.device_removed_service from services join latest_telemetry on latest_telemetry.sys_service_id=services.id join devices on devices.id=services.sys_device_id join mobile_simcards on mobile_simcards.id=devices.sys_simcard left join tbl_history_devices on tbl_history_devices.sys_service_id=services.id where services.id in (select distinct sys_service_id from group_services where active=true and sys_group_id in ( select sys_group_id from group_users where sys_user_id=(".$_POST["main_user_id"].")))  and tbl_history_devices.sys_group_id!=1998";*/
	
	  //$query=mysql_query($querydevice,$dblink);
	
	
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
      <td><select name="main_user_id" id="main_user_id">
          <option value="" >-- Select One --</option>
          <?php
        $main_user_data=select_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE sys_active=1 ORDER BY `name` ASC");
        
		//while ($data=mysql_fetch_assoc($main_user_data))
        for($j=0;$j<count($main_user_data);$j++)
		{
			if($main_user_data[$j]['user_id']==$_POST['main_user_id'])
			{
				$selected="selected";
			}
			else
			{
				$selected="";
			}
        ?>
          <option   value ="<?php echo $main_user_data[$j]['user_id'] ?>"  <?echo $selected;?>> <?php echo $main_user_data[$j]['name']; ?> </option>
          <?php 
        } 
        
        ?>
        </select></td>
      <td >From Date</td>
      <td><select id="startMonth" name="startMonth">
          <option value="1">Jan</option>
          <option value="2">Feb</option>
          <option value="3">Mar</option>
          <option value="4">Apr</option>
          <option value="5">May</option>
          <option value="6">Jun</option>
          <option value="7">Jul</option>
          <option value="8">Aug</option>
          <option value="9">Sep</option>
          <option value="10">Oct</option>
          <option value="11">Nov</option>
          <option value="12">Dec</option>
        </select>
        <select id="startYear" name="startYear">
          <option value="2014">2014</option>
          <option value="2013">2013</option>
          <option value="2012">2012</option>
          <option value="2011">2011</option>
          <option value="2010">2010</option>
          <option value="2009">2009</option>
        </select></td>
      <td>To Date</td>
      <td><select id="endMonth" name="endMonth">
          <option value="1">Jan</option>
          <option value="2">Feb</option>
          <option value="3">Mar</option>
          <option value="4">Apr</option>
          <option value="5">May</option>
          <option value="6">Jun</option>
          <option value="7">Jul</option>
          <option value="8" >Aug</option>
          <option value="9">Sep</option>
          <option value="10">Oct</option>
          <option value="11">Nov</option>
          <option value="12">Dec</option>
        </select>
        <select id="endYear" name="endYear">
          <option value="2014">2014</option>
          <option value="2013">2013</option>
          <option value="2012">2012</option>
          <option value="2011">2011</option>
          <option value="2010">2010</option>
          <option value="2009">2009</option>
        </select></td>
      <td align="center"><input type="submit" name="submit" value="submit"  /></td>
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
        <th>Imei </th>
        <th>Vehicle Number </th>
        <th>Last data</th>
        <th>Last comment</th>
      </tr>
    </thead>
    <tbody>
      <?php 
	   
  //while($row=mysql_fetch_array($query))
  for($i=0;$i<count($query);$i++)
  {
?>
      <tr align="Center" >
        <td><?php echo $i+1; ?></td>
        <td>&nbsp;<?php echo $query[$i]['imei'];?></td>
        <td>&nbsp;<?php echo $query[$i]['veh_reg'];?></td>
        <td><?php echo $query[$i]['lastcontact'];?></td>
        <? 
				$qrycom="select comment,comment_by,date from matrix.comment where service_id=".$query[$i]['id']." order by date desc";
				
				 $Commentquery = select_query_live_con($qrycom);
				 
				 ?>
        <td><?php echo $Commentquery[0]['comment'];?></td>
        <!--<td><a href="#" onclick="Show_record(<?php echo $query[$i]['imei'];?>,'imeidetail','popup1'); " class="topopup">View Detail</a>
</td> 
--></tr>
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
