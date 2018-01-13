 <?
 include($_SERVER['DOCUMENT_ROOT'].'/inc/sessionstart.php');
include($_SERVER['DOCUMENT_ROOT'].'/inc/connect.php');
include($_SERVER['DOCUMENT_ROOT'].'/inc/functions.php');
//include('../../inc/check_user.php');
include($_SERVER['DOCUMENT_ROOT'].'/inc/date_function.php');
include($_SERVER['DOCUMENT_ROOT'].'/user/classes/cls_db_manager.php');
include($_SERVER['DOCUMENT_ROOT'].'/user/classes/cls_VehicleJourneySummary.php');
include($_SERVER['DOCUMENT_ROOT'].'/user/classes/cls_calculate_distance.php');
/****************************************/
 
 
include($_SERVER['DOCUMENT_ROOT'].'/user/classes/cls_debug.php');
include($_SERVER['DOCUMENT_ROOT'].'/user/classes/master.php');
include($_SERVER['DOCUMENT_ROOT'].'/user/classes/cls_geoChecker.php');  
include($_SERVER['DOCUMENT_ROOT'].'/user/classes/cls_get_location.php');

 

if(isset($_POST["submit"])){
             
        $userid=4679;
           
					 
					$dispatch_poi_name=$_POST["txt_dispatch_from"];
					$txt_journey_hour=$_POST["txt_journey_hour"];
					$Destination = $_POST["txt_dispatch_to"];
					 
					$DispatchPoi_ID=select_query("select id from pois where   sys_user_id=".$userid." and name ='".$Destination."'");
					$DispatchFromPoi_ID=select_query("select id from pois where  sys_user_id=".$userid." and name ='".$dispatch_poi_name."'");


if($_GET["ADD"]==true)
	{

					  
					     $insert_query = "INSERT INTO `rajvi_journeyhour` (sys_user_id, `from_poiid`, `from_poi`, `to_poiid`, `to_poi`, `journey_hour`) VALUES (4679, ".$DispatchFromPoi_ID[0]["id"].", '".$dispatch_poi_name."',  ".$DispatchPoi_ID[0]["id"].",'".$Destination."', ".$txt_journey_hour.")";
					 mysql_query($insert_query);

					 
   
	}
	else
	{
		  $qry="update `rajvi_journeyhour` set `from_poiid` ='".$DispatchFromPoi_ID[0]["id"]."',`from_poi`='".$dispatch_poi_name."',`to_poiid`='".$DispatchPoi_ID[0]["id"]."',`to_poi`='".$Destination."',`journey_hour`='".$txt_journey_hour."'   where   `rajvi_journeyhour`.id=".$_GET["id"];
				 mysql_query($qry);
	}

					  
 
  gotopage("rajvi_masterjourney_hour.php");
					  
 
		 
 }

 

 
?>
 
  
 
 
 <link id="masterStyleSheet" rel="stylesheet" href="Skins/Default/StyleSheet.css" />
<link rel="stylesheet" href="http://trackingexperts.com/user/js/Matrix/aqtree3clickable.css" />

  <link rel="stylesheet" type="text/css" media="all" href="http://trackingexperts.com/user/js/calendar/calendar-win2k-1.css" title="win2k-1" />
  <link rel="stylesheet" type="text/css" media="all" href="http://trackingexperts.com/user/js/calendar/calendar-win2k-1.css" title="win2k-1" />
 
   	<!-- calendar stylesheet -->
  <link rel="stylesheet" type="text/css" media="all" href="http://trackingexperts.com/user/js/calendar/calendar-win2k-1.css" title="win2k-1" />

  <!-- main calendar program -->
  <script type="text/javascript" src="http://trackingexperts.com/user/js/calendar/calendar.js"></script>
    <script type="text/javascript" src="http://trackingexperts.com/user/js/jquery.js"></script>
	<link href="http://trackingexperts.com/user/js/style.css" rel="stylesheet" type="text/css">

  <!-- language for the calendar -->
  <script type="text/javascript" src="http://trackingexperts.com/user/js/calendar/calendar-en.js"></script>

  <!-- the following script defines the Calendar.setup helper function, which makes
       adding a calendar a matter of 1 or 2 lines of code. -->
  <script type="text/javascript" src="http://trackingexperts.com/user/js/calendar/calendar-setup.js"></script>

<?if($_GET["edit"]==true)
	{
		$EditEntry=select_query("select * from rajvi_journeyhour where   rajvi_journeyhour.id=".$_GET["id"]);
		 
		$_POST["txt_dispatch_to"]=$EditEntry[0]["to_poi"];
		 
		$_POST["txt_journey_hour"]=$EditEntry[0]["journey_hour"];
		$_POST["txt_dispatch_from"]=$EditEntry[0]["from_poi"];
		

 
	}?>
	
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
 
<form name="mahaveera_report" method="post" action="">
 <table class="std border coll thback"  width="100%">
  <tr>
	<td colspan="2" align='center'><b>MANUAL JOURNEY HOUR ENTRY</b></td>
	</tr>
	 
 
 
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$(document).click(function(){
		$("#ajax_response_from").fadeOut('slow');
	});
	 
	var offset = $("#txt_dispatch_from").offset();
	var width = $("#txt_dispatch_from").width()-2;
	$("#ajax_response_from").css("left",offset.left); 
	$("#ajax_response_from").css("width",width);
	$("#txt_dispatch_from").keyup(function(event){
		 //alert(event.keyCode);
		 var keyword = $("#txt_dispatch_from").val();
		 if(keyword.length)
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13)
			 {
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: "poiname_fetch.php",
				   data: "data="+keyword,
				   success: function(msg){	
					if(msg != 0)
					  $("#ajax_response_from").fadeIn("slow").html(msg);
					else
					{
					  $("#ajax_response_from").fadeIn("slow");	
					  $("#ajax_response_from").html('<div style="text-align:left;">No Matches Found</div>');
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
					$("#ajax_response_from").fadeOut("slow");
					$("#txt_dispatch_from").val($("li[class='selected'] a").text());
				 break;
				}
			 }
		 }
		 else
			$("#ajax_response_from").fadeOut("slow");
	});
	$("#ajax_response_from").mouseover(function(){
		$(this).find("li a:first-child").mouseover(function () {
			  $(this).addClass("selected");
		});
		$(this).find("li a:first-child").mouseout(function () {
			  $(this).removeClass("selected");
		});
		$(this).find("li a:first-child").click(function () {
			  $("#txt_dispatch_from").val($(this).text());
			  $("#ajax_response_from").fadeOut("slow");
		});
	});
});


</script>
<tr>
	<td>DISPATCH FROM</td>
	<td> <input name="txt_dispatch_from" type="text"  id="txt_dispatch_from"  value="<?=$_POST["txt_dispatch_from"]?>" size="35">
              <div id="ajax_response_from"></div></td>
</tr>
 
 
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$(document).click(function(){
		$("#ajax_response").fadeOut('slow');
	});
	 
	var offset = $("#txt_dispatch_to").offset();
	var width = $("#txt_dispatch_to").width()-2;
	$("#ajax_response").css("left",offset.left); 
	$("#ajax_response").css("width",width);
	$("#txt_dispatch_to").keyup(function(event){
		 //alert(event.keyCode);
		 var keyword = $("#txt_dispatch_to").val();
		 if(keyword.length)
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13)
			 {
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: "poiname_fetch.php",
				   data: "data="+keyword,
				   success: function(msg){	
					if(msg != 0)
					  $("#ajax_response").fadeIn("slow").html(msg);
					else
					{
					  $("#ajax_response").fadeIn("slow");	
					  $("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
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
					$("#ajax_response").fadeOut("slow");
					$("#txt_dispatch_to").val($("li[class='selected'] a").text());
				 break;
				}
			 }
		 }
		 else
			$("#ajax_response").fadeOut("slow");
	});
	$("#ajax_response").mouseover(function(){
		$(this).find("li a:first-child").mouseover(function () {
			  $(this).addClass("selected");
		});
		$(this).find("li a:first-child").mouseout(function () {
			  $(this).removeClass("selected");
		});
		$(this).find("li a:first-child").click(function () {
			  $("#txt_dispatch_to").val($(this).text());
			  $("#ajax_response").fadeOut("slow");
		});
	});
});

</script>
<tr>
	<td>DESTINATION</td>
	<td> <input name="txt_dispatch_to" type="text"  id="txt_dispatch_to"  value="<?=$_POST["txt_dispatch_to"]?>" size="35">
              <div id="ajax_response"></div></td>
</tr> 

<tr>
	<td>HOURS</td>
	<td> <input name="txt_journey_hour" type="text"  id="txt_journey_hour"  value="<?=$_POST["txt_journey_hour"]?>" size="35">
             </td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" name="submit" value="submit"/></td>
</tr> 
 
 </table>
 	</form>

  
  