<link id="masterStyleSheet" rel="stylesheet" href="Skins/Default/StyleSheet.css" />
<link rel="stylesheet" href="js/Matrix/aqtree3clickable.css" />

  <link rel="stylesheet" type="text/css" media="all" href="js/calendar/calendar-win2k-1.css" title="win2k-1" />
  <link rel="stylesheet" type="text/css" media="all" href="js/calendar/calendar-win2k-1.css" title="win2k-1" />
<script src="../js/validation_new.js"></script>
   	<!-- calendar stylesheet -->
  <link rel="stylesheet" type="text/css" media="all" href="js/calendar/calendar-win2k-1.css" title="win2k-1" />

  <!-- main calendar program -->
  <script type="text/javascript" src="js/calendar/calendar.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
	<link href="js/style.css" rel="stylesheet" type="text/css">

  <!-- language for the calendar -->
  <script type="text/javascript" src="js/calendar/calendar-en.js"></script>

  <!-- the following script defines the Calendar.setup helper function, which makes
       adding a calendar a matter of 1 or 2 lines of code. -->
  <script type="text/javascript" src="js/calendar/calendar-setup.js"></script>

<?php
 date_default_timezone_set ("Asia/Calcutta");
include('C:/xampp/htdocs/inc/sessionstart.php');
include('C:/xampp/htdocs/inc/connect.php');
include('C:/xampp/htdocs/inc/functions.php');
include('C:/xampp/htdocs/inc/date_function.php');
//include('C:/xampp/htdocs/inc/check_user.php');
include('C:/xampp/htdocs/user/classes/cls_geoChecker.php');
include('C:/xampp/htdocs/user/classes/master.php');
include('C:/xampp/htdocs/user/classes/cls_VehicleJourneySummary.php');
include('C:/xampp/htdocs/user/classes/cls_db_manager.php');
include('C:/xampp/htdocs/user/classes/cls_calculate_distance.php');
include('C:/xampp/htdocs/user/classes/journey_report_logic.php');
include('C:/xampp/htdocs/user/classes/cls_get_location.php');
//$masterObj=new master;
 //include("C:/xampp/htdocs/service_dev/include/header_mahaveera.php");

function secondsToTime($inputSeconds) {

    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // extract days
    $days = floor($inputSeconds / $secondsInADay);

    // extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // return the final array
    $obj = array(
        'd' => (int) $days,
        'h' => (int) $hours,
        'm' => (int) $minutes,
        's' => (int) $seconds,
    );

$date=$days ." days " .$hours." Hours ".$minutes." Min ".$seconds;

    return $date;
}




/* Lang Check */
include('C:/xampp/htdocs/inc/lang_check.php');
include_once('C:/xampp/htdocs/lang/'.$lang.'/common.php');
include_once('C:/xampp/htdocs/lang/'.$lang.'/journey.php');
/* Lang Check */
         ?>
 <style>
table.std {
    margin: 1.2em;
	font-size:11px;
}
table.std td, table.std th {
    padding: 0.5em;
}
table.std th {
   color: white;
}
.bordered {
    border: 1px solid #CCCCCC;
}
.border td, .border th, .hilite td, .hilite th, .clickon td, .clickon th {
    border: 1px solid #CCCCCC;
}
.coll, .hilite, .clickon {
    border-collapse: collapse;
}
.thback thead, .hilite thead, .clickon thead {
    background: none repeat scroll 0 0 #777D6A;
}
.tbback tbody, .hilite tbody, .clickon tbody {
    background: none repeat scroll 0 0 #99CCFF;
}
#highlight tr.hilight {
    background: none repeat scroll 0 0 #CC99FF;
}
#clickme tr.clicked {
    background: none repeat scroll 0 0 #FFFF00;
}
.separate {
    margin-top: 400px;
}
</style>
         
<!--  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
 -->
 <form name="Serachby" action="" method="post">
 <table class="std coll thback" width="100%">
 <tr  align="right"><td><a  href="http://<?echo  $_SERVER['HTTP_HOST'];?>/user/manual_dispatch_entry.php" target="_blank"><b> Manul Entry </b></a></td><td>&nbsp;</td><td>Search by vehicle name
 <input type="text" name="searchby" value=<?=$searchby?> ></td><td align="left"> <input type="submit" name="submit" value="search"/></td>
 </tr>
</table>
</form>

 <table class="std border coll thback"  width="100%">

         <?
  function hoursToMinutes($hours)
{
 if (strstr($hours, ':'))
 {
  # Split hours and minutes.
  $separatedData = split(':', $hours);

  $minutesInHours    = $separatedData[0] * 60;
  $minutesInDecimals = $separatedData[1];

  $totalMinutes = $minutesInHours + $minutesInDecimals;
 }
 else
 {
  $totalMinutes = $hours * 60;
 }

 return $totalMinutes;
}
$today=date("Y-m-d");
  $datum = date("Y-m-d", strtotime("$today -4 day"));
 
$_SESSION['TimeZoneDiff']=330;
$_SESSION['UserId']=3151;
$masterObj=new master;
$masterObj->registerVehiclesinSession();
 $UserPoiData=select_query("SELECT id,name,gps_radius,gps_longitude  FROM pois where sys_user_id='3151'  ORDER BY name asc ");
  $Address="";
  if($_POST["submit"]=="search" && $_POST["searchby"]!="" )
  {

               /* $POISData=select_query("select poi_log_new.id,DATE_FORMAT(poi_log_new.poi_entry_time,'%H:%i'),TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time) as diff,TIME_TO_SEC(TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time)) as diff1,ADDDATE( poi_entry_time, INTERVAL 330 MINUTE) as poi_entry_time1,poi_entry_time,poi_exit_time, ADDDATE( poi_exit_time, INTERVAL 330 MINUTE) as poi_exit_time1,sys_service_id,pois.name as poi_name,branch,zone,poi_log_new.sys_service_id,Destination, invoice, diesel, cash, advance, remark, driver_name, driver_phone,veh_reg,is_loading,services.route_no,services.veh_status from poi_log_new
 left join pois on poi_log_new.poi_id = pois.id left join services on services.id=poi_log_new.sys_service_id
  where  poi_entry_time>' ".$datum." 18:30:00' and (TIME_TO_SEC(TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time)) is null or TIME_TO_SEC(TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time))>7200) and (poi_name like 'LD%' ) and veh_reg like '%".$_POST["searchby"]."%' GROUP BY poi_name  order by sys_service_id,poi_entry_time");*/


 $POISData=select_query("select poi_log_new.id,DATE_FORMAT(poi_log_new.poi_entry_time,'%H:%i'),TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time) as diff,TIME_TO_SEC(TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time)) as diff1,ADDDATE( poi_entry_time, INTERVAL 330 MINUTE) as poi_entry_time1,poi_entry_time,poi_exit_time, ADDDATE( poi_exit_time, INTERVAL 330 MINUTE) as poi_exit_time1,sys_service_id,poi_log_new.poi_id,poi_log_new.poi_name as poi_name,pois.name as name,branch,zone,poi_log_new.sys_service_id,Destination, invoice, diesel, cash, advance, remark, driver_name, driver_phone,veh_reg,is_loading,services.route_no,services.veh_status from poi_log_new
 left join pois on poi_log_new.poi_id = pois.id left join services on services.id=poi_log_new.sys_service_id
  where    (TIME_TO_SEC(TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time)) is null or TIME_TO_SEC(TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time))>10) and (poi_name like 'LD%') and (poi_exit_time>'".$datum." 18:30:00' or poi_exit_time='0000-00-00 00:00:00') and veh_reg like '%".$_POST["searchby"]."%' GROUP BY poi_name  order by sys_service_id,poi_entry_time");


  //or poi_name like '%UNLD%'
  }
  else
  {
	  /*$POISData=select_query("select poi_log_new.id,DATE_FORMAT(poi_log_new.poi_entry_time,'%H:%i'),TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time) as diff,TIME_TO_SEC(TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time)) as diff1,ADDDATE( poi_entry_time, INTERVAL 330 MINUTE) as poi_entry_time1,poi_entry_time,poi_exit_time, ADDDATE( poi_exit_time, INTERVAL 330 MINUTE) as poi_exit_time1,sys_service_id,poi_log_new.poi_name as poi_name,branch,zone,poi_log_new.sys_service_id,Destination, invoice, diesel, cash, advance, remark, driver_name, driver_phone,veh_reg,is_loading,services.route_no,services.veh_status from poi_log_new
 left join pois on poi_log_new.poi_id = pois.id left join services on services.id=poi_log_new.sys_service_id
  where  poi_entry_time>'".$datum." 18:30:00' and (TIME_TO_SEC(TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time)) is null or TIME_TO_SEC(TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time))>7200) and (poi_name like 'LD%') GROUP BY poi_name,sys_service_id order by sys_service_id,poi_entry_time");*/



  $POISData=select_query("select poi_log_new.id,poi_log_new.poi_id,DATE_FORMAT(poi_log_new.poi_entry_time,'%H:%i'),TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time) as diff,TIME_TO_SEC(TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time)) as diff1,ADDDATE( poi_entry_time, INTERVAL 330 MINUTE) as poi_entry_time1,poi_entry_time,poi_exit_time, ADDDATE( poi_exit_time, INTERVAL 330 MINUTE) as poi_exit_time1,sys_service_id,poi_log_new.poi_name as poi_name,pois.name as name,branch,zone,poi_log_new.sys_service_id,Destination, invoice, diesel, cash, advance, remark, driver_name, driver_phone,veh_reg,is_loading,services.route_no,services.veh_status from poi_log_new
 left join pois on poi_log_new.poi_id = pois.id left join services on services.id=poi_log_new.sys_service_id
  where    (TIME_TO_SEC(TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time)) is null or TIME_TO_SEC(TIMEDIFF(poi_log_new.poi_exit_time,poi_log_new.poi_entry_time))>3600) and (poi_name like 'LD%') and (poi_exit_time>'".$datum." 18:30:00' or poi_exit_time='0000-00-00 00:00:00') GROUP BY poi_name,sys_service_id order by sys_service_id,poi_entry_time");

  

 
  // or poi_name like '%UNLD%'

  
  }





  
 /*$reportHtml.='
 <thead><TR style="display:none"> <th></th> <th></th> <th></th> <th></th> <th></th> <th></th> <th></th>
 </TR></thead>';*/
  
 $reportHtml.='
 <TR> <td ><b> VEHICLE NO</b></th><td  ><b> VEHICLE PLACED DATE</b></td><td><b> POI NAME</b></td> <td  ><b> DISPATCH DATE TIME</b></td> <td ><b> INVOICE</b></td><td  ><b>DIESEL</b></td><td  ><b> CASH</b></td><td  ><b> REMARK</b></td>
<td><b> DESTINATION</b></td>
 </TR>';
for($i=0;$i<count($POISData);$i++)
{
 if($row['poi_exit_time']=="")
                {
         $row['poiexitdate']="&nbsp;";
                }


                //      INSERT INTO UnknownTable (DATE_FORMAT(poi_log_new.poi_entry_time,'%H:%i'), diff, diff1, poi_entry_time1, poi_entry_time, poi_exit_time, poi_exit_time1, sys_service_id, poi_name, branch, zone, sys_service_id, Destination, invoice, diesel, cash, advance, remark, driver_name, driver_phone, , , veh_status) VALUES ('23:10', '16:58:22', 61102, '2014-12-02 04:40:57', '2014-12-01 23:10:57', '2014-12-02 16:09:19', '2014-12-02 21:39:19', 27469, 'UNLD-DELHI-SAMSUNG', 'Delhi', 'NORTH', 27469, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'HR47B8233', 'CONTAINER', '32 FT SXL HQ');

//INVOICE, DIESEL,CASH ,REMARK ,

 

        //$POISData[$i]["Destination"]

		if ($POISData[$i]["is_loading"] == '1') 
			{
	 $checked="checked";
			}
			else
			{
				$checked="";
				
			}
                  if($POISData[$i]["veh_reg"]==$veh_reg)
        {
                           $poi_log_new_id=$POISData[$i]["id"];
						   $sys_service_id=$POISData[$i]["sys_service_id"];

						   $poi_id=$POISData[$i]["poi_id"];

 

                           $reportHtmlLoadingcontainer .=' 
<tr><td></td>  <td   align="right"><input type="text"  size="16" maxlength="20" name="Entry_time'.$poi_log_new_id.'" id="Entry_time'.$poi_log_new_id.'" value="'.$POISData[$i]["poi_entry_time1"].'" readonly="readonly"></td><td  >'.$POISData[$i]["name"].'<input type="checkbox" name="needLoc[]" value="'.$poi_log_new_id.'##'.$POISData[$i]["name"].'##'.$sys_service_id.'" '.$checked.'   >'.$POISData[$i]["is_loading"].' </td>  <td  > <input type="text"  size="16" maxlength="20" name="Exit_time'.$poi_log_new_id.'" id="Exit_time'.$poi_log_new_id.'" value="'.$POISData[$i]["poi_exit_time1"].'"   ></td> <td ><input type="text" name="invoice'.$poi_log_new_id.'" id="invoice'.$poi_log_new_id.'" size="15" maxlength="15"  value="'.$POISData[$i]["invoice"].'"></td> <td ><input type="text" size="7" maxlength="7" name="diesel'.$poi_log_new_id.'" id="diesel'.$poi_log_new_id.'" value="'.$POISData[$i]["diesel"].'"></td> <td ><input size="7" maxlength="7" type="text" name="cash'.$poi_log_new_id.'" id="cash'.$poi_log_new_id.'" value="'.$POISData[$i]["cash"].'"></td> <td ><input type="text" name="remark'.$poi_log_new_id.'" id="remark'.$poi_log_new_id.'"  size="16" maxlength="20" value="'.$POISData[$i]["remark"].'" ></td>';
 


  $reportHtmlLoadingcontainer .='<td > <input type="text" name="Destination'.$poi_log_new_id.'" id="Destination'.$poi_log_new_id.'" value="'.$POISData[$i]["Destination"].'"  >';

 $reportHtmlLoadingcontainer .='<div id="ajax_response'.$poi_log_new_id.'"></div></td></tr>';
 ?>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$(document).click(function(){
		$("#<?='ajax_response'.$poi_log_new_id?>").fadeOut('slow');
	});
	 
	var offset = $("#<?='Destination'.$poi_log_new_id?>").offset();
	var width = $("#<?='Destination'.$poi_log_new_id?>").width()-2;
	$("#<?='ajax_response'.$poi_log_new_id?>").css("left",offset.left); 
	$("#<?='ajax_response'.$poi_log_new_id?>").css("width",width);
	$("#<?='Destination'.$poi_log_new_id?>").keyup(function(event){
		 //alert(event.keyCode);
		 var keyword = $("#<?='Destination'.$poi_log_new_id?>").val();
		 if(keyword.length)
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13)
			 {
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: "name_fetch.php",
				   data: "data="+keyword,
				   success: function(msg){	
					if(msg != 0)
					  $("#<?='ajax_response'.$poi_log_new_id?>").fadeIn("slow").html(msg);
					else
					{
					  $("#<?='ajax_response'.$poi_log_new_id?>").fadeIn("slow");	
					  $("#<?='ajax_response'.$poi_log_new_id?>").html('<div style="text-align:left;">No Matches Found</div>');
					}
					$("#loading").css("visibility","hidden");
				   }
				 });
			 }
			 else
			 {
				switch (event.keyCode)
				{
				 case 40:
				 {
					  found = 0;
					  $("li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $("li[class='selected']");
						sel.next().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$("li:first").addClass("selected");
					 }
				 break;
				 case 38:
				 {
					  found = 0;
					  $("li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $("li[class='selected']");
						sel.prev().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$("li:last").addClass("selected");
				 }
				 break;
				 case 13:
					$("#<?='ajax_response'.$poi_log_new_id?>").fadeOut("slow");
					$("#<?='Destination'.$poi_log_new_id?>").val($("li[class='selected'] a").text());
				 break;
				}
			 }
		 }
		 else
			$("#<?='ajax_response'.$poi_log_new_id?>").fadeOut("slow");
	});
	$("#<?='ajax_response'.$poi_log_new_id?>").mouseover(function(){
		$(this).find("li a:first-child").mouseover(function () {
			  $(this).addClass("selected");
		});
		$(this).find("li a:first-child").mouseout(function () {
			  $(this).removeClass("selected");
		});
		$(this).find("li a:first-child").click(function () {
			  $("#<?='Destination'.$poi_log_new_id?>").val($(this).text());
			  $("#<?='ajax_response'.$poi_log_new_id?>").fadeOut("slow");
		});
	});
});

</script><?
                
        }
        else
        {


$veh_reg=$POISData[$i]["veh_reg"];
$sys_service_id=$POISData[$i]["sys_service_id"];
$poi_log_new_id=$POISData[$i]["id"];
 $poi_id=$POISData[$i]["poi_id"];
          $reportHtmlLoadingcontainer .='
<tr><td >'.$veh_reg.'</td><td  align="right" ><input type="text" size="16" maxlength="20" name="Entry_time'.$poi_log_new_id.'" id="Entry_time'.$poi_log_new_id.'" value="'.$POISData[$i]["poi_entry_time1"].'" readonly="readonly"></td><td >'.$POISData[$i]["name"].' <input type="checkbox" name="needLoc[]" value="'.$poi_log_new_id.'##'.$POISData[$i]["name"].'##'.$sys_service_id.'"   '.$checked.' >'.$POISData[$i]["is_loading"].' </td>  <td  > <input type="text"  size="16" maxlength="20" name="Exit_time'.$poi_log_new_id.'" id="Exit_time'.$poi_log_new_id.'" value="'.$POISData[$i]["poi_exit_time1"].'"   ></td> <td ><input type="text" name="invoice'.$poi_log_new_id.'" id="invoice'.$poi_log_new_id.'" size="15" maxlength="15" value="'.$POISData[$i]["invoice"].'"></td> <td ><input type="text" name="diesel'.$poi_log_new_id.'" id="diesel'.$poi_log_new_id.'" size="7" maxlength="7" value="'.$POISData[$i]["diesel"].'"></td> <td ><input type="text" name="cash'.$poi_log_new_id.'" size="7" maxlength="7" id="cash'.$poi_log_new_id.'" value="'.$POISData[$i]["cash"].'"></td> <td ><input type="text"  size="16" maxlength="20" name="remark'.$poi_log_new_id.'" id="remark'.$poi_log_new_id.'" value="'.$POISData[$i]["remark"].'" ></td>';
 

  $reportHtmlLoadingcontainer .='<td > <input type="text" name="Destination'.$poi_log_new_id.'" id="Destination'.$poi_log_new_id.'" value="'.$POISData[$i]["Destination"].'" >';

 $reportHtmlLoadingcontainer .='<div id="ajax_response'.$poi_log_new_id.'"></div></td></tr>';

					
					
					
?>


<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$(document).click(function(){
		$("#<?='ajax_response'.$poi_log_new_id?>").fadeOut('slow');
	});
	 
	var offset = $("#<?='Destination'.$poi_log_new_id?>").offset();
	var width = $("#<?='Destination'.$poi_log_new_id?>").width()-2;
	$("#<?='ajax_response'.$poi_log_new_id?>").css("left",offset.left); 
	$("#<?='ajax_response'.$poi_log_new_id?>").css("width",width);
	$("#<?='Destination'.$poi_log_new_id?>").keyup(function(event){
		 //alert(event.keyCode);
		 var keyword = $("#<?='Destination'.$poi_log_new_id?>").val();
		 if(keyword.length)
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13)
			 {
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: "name_fetch.php",
				   data: "data="+keyword,
				   success: function(msg){	
					if(msg != 0)
					  $("#<?='ajax_response'.$poi_log_new_id?>").fadeIn("slow").html(msg);
					else
					{
					  $("#<?='ajax_response'.$poi_log_new_id?>").fadeIn("slow");	
					  $("#<?='ajax_response'.$poi_log_new_id?>").html('<div style="text-align:left;">No Matches Found</div>');
					}
					$("#loading").css("visibility","hidden");
				   }
				 });
			 }
			 else
			 {
				switch (event.keyCode)
				{
				 case 40:
				 {
					  found = 0;
					  $("li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $("li[class='selected']");
						sel.next().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$("li:first").addClass("selected");
					 }
				 break;
				 case 38:
				 {
					  found = 0;
					  $("li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $("li[class='selected']");
						sel.prev().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$("li:last").addClass("selected");
				 }
				 break;
				 case 13:
					$("#<?='ajax_response'.$poi_log_new_id?>").fadeOut("slow");
					$("#<?='Destination'.$poi_log_new_id?>").val($("li[class='selected'] a").text());
				 break;
				}
			 }
		 }
		 else
			$("#<?='ajax_response'.$poi_log_new_id?>").fadeOut("slow");
	});
	$("#<?='ajax_response'.$poi_log_new_id?>").mouseover(function(){
		$(this).find("li a:first-child").mouseover(function () {
			  $(this).addClass("selected");
		});
		$(this).find("li a:first-child").mouseout(function () {
			  $(this).removeClass("selected");
		});
		$(this).find("li a:first-child").click(function () {
			  $("#<?='Destination'.$poi_log_new_id?>").val($(this).text());
			  $("#<?='ajax_response'.$poi_log_new_id?>").fadeOut("slow");
		});
	});
});

</script>
<?
        
        }


}

$report = $reportHtmlHEADING.$reportHtml.$reportHtmlLoadingcontainer.'</table>';



if(isset($_POST["submit"])){
             
        for ($i = 0; $i < count($_POST['needLoc']); ++$i) {
            
					$idArry =  explode("##",$_POST['needLoc'][$i]);
					$id=$idArry[0];
					$dispatch_poi_name=$idArry[1];
					$veh_id=$idArry[2];


					$name= 'Destination'.$id;
					$Entry_time= 'Entry_time'.$id;
					$Exit_time= 'Exit_time'.$id;
					$invoice= 'invoice'.$id;
					$diesel= 'diesel'.$id;
					$cash= 'cash'.$id;
					$remark= 'remark'.$id;
					

					$Destination = $_POST[$name];
					$Entry_time1 = $_POST[$Entry_time];
					$Exit_time1 = $_POST[$Exit_time];
					$invoice1 = $_POST[$invoice];
					$diesel1 = $_POST[$diesel];
					$cash1 = $_POST[$cash];
					$remark1 = $_POST[$remark];
					$advance = $diesel1+$cash1;
					if($Exit_time1=="")
			{
						$Exit_time1="0000-00-00 00:00:00";
			}
					
 
					$poiid=select_query("select id from pois where name ='".$dispatch_poi_name."'");
 
					    $data=select_query("select * from mahaveera_dispatch where `sys_service_id`='".$veh_id."' AND `poi_name` ='".$dispatch_poi_name."'  and `poi_entry_time`='".$Entry_time1."'"); 

				if(!empty($data))
					{ 

				      $qry="update mahaveera_dispatch set `poi_exit_time`='".$Exit_time1."',`Destination`='".$Destination."',`invoice`='".$invoice1."',`diesel`='".$diesel1."',`cash`='".$cash1."',`advance`='".$advance."',`remark`='".$remark1."'  where `sys_service_id`='".$veh_id."' AND `poi_name` ='".$dispatch_poi_name."' and `poi_entry_time`='".$Entry_time1."'";
				  
				 mysql_query($qry);
 
				
					}
					else
					{

					   $insert_query = "INSERT INTO  mahaveera_dispatch ( `sys_user_id`, `poi_id`,  `poi_name`, `sys_service_id`, `poi_entry_time`, `poi_exit_time`, `Destination`, `invoice`, `diesel`, `cash`, `advance`, `remark`) VALUES (3151,".$poiid[0]["id"].", '".$dispatch_poi_name."', '".$veh_id."','".$Entry_time1."','".$Exit_time1."','".$Destination."','".$invoice1."','".$diesel1."','".$cash1."','".$advance."','".$remark1."');";
					  
							mysql_query($insert_query);
						}
					
					   $update_query = "UPDATE poi_log_new set Destination='".$Destination."',`invoice`='".$invoice1."',`diesel`='".$diesel1."',`cash`='".$cash1."',`remark`='".$remark1."',is_loading=1 where poi_log_new.id='".$id."'";
					 
						mysql_query($update_query);

					//INSERT INTO  mahaveera_dispatch ( `sys_user_id`,   `poi_name`, `sys_service_id`, `poi_entry_time`, `poi_exit_time`, `last_updated_date`, `Destination`, `invoice`, `diesel`, `cash`, `advance`, `remark`, `driver_name`, `driver_phone`, `is_loading`) VALUES (3151, 77487, 'LD/UNLD-NOIDA-HONDA', 23838, '2014-12-18 05:04:24', '2014-12-18 18:36:31', '2014-12-20 00:36:06', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

        }

		 
    //echo "<script>document.location.href ='mahaveera_report_ld_ud.php'</script>";
}

 




 ?>
<form name="mahaveera_report" method="post" action="">
<?echo $report;?>
<input type="submit" name="submit" value="submit"/>

</form>