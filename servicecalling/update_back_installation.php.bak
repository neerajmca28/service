<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/ 

?>
<link  href="../css/auto_dropdown.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

/*Start auto ajax value load code*/

$(document).ready(function(){
	$(document).click(function(){
		$("#ajax_response").fadeOut('slow');
	});
	$("#Zone_area").focus();
	var offset = $("#Zone_area").offset();
	var width = $("#Zone_area").width()-2;
	$("#ajax_response").css("left",offset); 
	$("#ajax_response").css("width","15%");
	$("#ajax_response").css("z-index","1");
	$("#Zone_area").keyup(function(event){
		 //alert(event.keyCode);
		 var keyword = $("#Zone_area").val();
		 if(keyword.length)
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13)
			 {
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: "load_zone_area.php",
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
					$("#Zone_area").val($("li[class='selected'] a").text());
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
			  $("#Zone_area").val($(this).text());
			  $("#ajax_response").fadeOut("slow");
		});
	});
});
/* End auto ajax value load code*/
</script>

<?
$Header="Edit Installation";
$account_manager=$_SESSION['username'];
$action=$_GET['action'];
$id=$_GET['id'];
$page=$_POST['page'];
if($action=='edit' or $action=='editp')
	{
		$Header="Edit Installation";
		$result=mysql_fetch_array(mysql_query("select * from installation where id=$id and branch_id=".$_SESSION['BranchId']));	
		
		$Zone_area = $result["Zone_area"];
		$area = mysql_fetch_array(mysql_query("SELECT id,`name` FROM re_city_spr_1 WHERE id='".$Zone_area."'"));
	}?>

<div class="top-bar">
<h1><? echo $Header;?></h1>
</div>
<div class="table"> 
<?
if(isset($_POST['submit']))
{
	//$location=$_POST['location'];
	$cnumber=$_POST['cnumber'];
	$contact_person=$_POST['contact_person'];
	$atime_status=$_POST['atime_status'];
	//$Area=$_POST['Zone_area'];
	$Zone_id = mysql_query("SELECT id,`name` FROM re_city_spr_1 WHERE `name`='".$_POST['Zone_area']."'");
	$zone_count = mysql_num_rows($Zone_id);
	$Zone_data = mysql_fetch_array($Zone_id);
	if($zone_count > 0)
	{
		$Area = $Zone_data["id"];
		$errorMsg = "";
	}
	else
	{
		$errorMsg = "Please Select Type View Listed Area. Not Fill Your Self.";
	}
	$back_comment = $_POST['back_comment'];
	$comment = $_POST['comment'];
	
	if($_POST['location'] == "")
	{
		$location="";
	}
	else
	{
		$location=$_POST['location'];
	}
	


	if($errorMsg=="")	
	{	
		if($atime_status=="Till")
		{
			$time=$_POST['time'];
			
			$sql="update installation set time='".$time."', atime_status='".$atime_status."', contact_number='".$cnumber."' , contact_person='".$contact_person."',Zone_area='".$Area."', location='".$location."', installation_status='1', comment='".$back_comment."<br/>".date("Y-m-d H:i:s")." - ".$comment."' where id=$id";		
			$execute=mysql_query($sql);
						
			echo "<script>document.location.href ='running_installation.php'</script>";
		 }
		 if($atime_status=="Between")
		 {
			$time=$_POST['time1'];
			$totime=$_POST['totime'];
			
			$sql="update installation set time='".$time."',totime='".$totime."',atime_status='".$atime_status."',contact_number='".$cnumber."' ,contact_person='".$contact_person."',Zone_area='".$Area."', location='".$location."', installation_status='1', comment='".$back_comment."<br/>".date("Y-m-d H:i:s")." - ".$comment."' where id=$id";		
			$execute=mysql_query($sql);
						
			echo "<script>document.location.href ='running_installation.php'</script>";
		  }
	}
	
}

?>
<script type="text/javascript">

function req_info()
{
 
 
	if(document.form1.Zone_area.value=="")
	{
	alert("Please Select Area") ;
	document.form1.Zone_area.focus();
	return false;
	} 
  	
	/*var location=document.forms["form1"]["location"].value;
	if (location==null || location=="")
	{
		alert("Please Enter location");
		document.form1.location.focus();
		return false;
	}*/
	   
	var timestatus=document.forms["form1"]["atime_status"].value;
	if (timestatus==null || timestatus=="")
	  {
		  alert("Please select Availbale Time");
		  document.form1.atime_status.focus();
		  return false;
	  }
   
	var tilltime=document.forms["form1"]["datetimepicker"].value;
	if(timestatus == "Till" && (tilltime==null || tilltime==""))
	{
		alert("Please select Time");
		document.form1.datetimepicker.focus();
		return false;
	}
	
	var betweentime=document.forms["form1"]["datetimepicker1"].value;
	var betweentime2=document.forms["form1"]["datetimepicker2"].value;
	if(timestatus == "Between" && (betweentime==null || betweentime==""))
	{
		alert("Please select From Time");
		document.form1.datetimepicker1.focus();
		return false;
	}
	
	if(timestatus == "Between" && (betweentime2==null || betweentime2==""))
	{
		alert("Please select To Time");
		document.form1.datetimepicker2.focus();
		return false;
	}
   
  if(document.form1.cnumber.value=="")
  {
  alert("Please Enter Contact No.") ;
  document.form1.cnumber.focus();
  return false;
  }
  var cnumber=document.form1.cnumber.value;
  if(cnumber!="")
        {
	var lenth=cnumber.length;
	
        if(lenth < 10 || lenth > 12 || cnumber.search(/[^0-9\-()+]/g) != -1 )
        {
        alert('Please enter valid mobile number');
        document.form1.cnumber.focus();
        document.form1.cnumber.value="";
        return false;
        }
     }
if(document.form1.contact_person.value=="")
  {
  alert("Please Enter Contact Persion") ;
  document.form1.contact_person.focus();
  return false;
  }
	
} 
/*function setVisibility(id, visibility) {
document.getElementById(id).style.display = visibility;
}*/

function TillBetweenTime(radioValue)
{
 if(radioValue=="Till")
	{
	document.getElementById('TillTime').style.display = "block";
	document.getElementById('BetweenTime').style.display = "none";
	}
	else if(radioValue=="Between")
	{
	document.getElementById('TillTime').style.display = "none";
	document.getElementById('BetweenTime').style.display = "block";
	}
	else
	{
	document.getElementById('TillTime').style.display = "none";
	document.getElementById('BetweenTime').style.display = "none";
	} 
	
}

function TillBetweenTime12(radioValue)
{
 if(radioValue=="Till")
	{
	document.getElementById('TillTime').style.display = "block";
	document.getElementById('BetweenTime').style.display = "none";
	}
	else if(radioValue=="Between")
	{
	document.getElementById('TillTime').style.display = "none";
	document.getElementById('BetweenTime').style.display = "block";
	}
	else
	{
	document.getElementById('TillTime').style.display = "none";
	document.getElementById('BetweenTime').style.display = "none";
	} 
	
}

function StatusBranch12(radioValue)
{
   if(radioValue=="Samebranch")
	{
		document.getElementById('samebranchid').style.display = "block";
	}
	else
	{
		document.getElementById('samebranchid').style.display = "none";
	} 
	
}	
</script>
 <script type="text/javascript">

    	$(function () {
    		 
    		$("#datetimepicker").datetimepicker({});
			$("#datetimepicker1").datetimepicker({});
			$("#datetimepicker2").datetimepicker({});
			$("#datetimepicker3").datetimepicker({});
			
    	});

    </script>
	
<?php echo "<p align='left' style='padding-left: 250px;width: 500px;' class='message'>" .$errorMsg. "</p>" ; ?>

<style type="text/css" >
.errorMsg{border:1px solid red; }
.message{color: red; font-weight:bold; }
</style>

<form method="post" action="" name="form1" onSubmit="return req_info();">

    <table style="width: 900px;" cellspacing="2" cellpadding="3" border="1">
        <tr>
            <td width="100px"  align="right"><label  id="lbDlClient"><strong>Request By: * </strong></label></td>
            <td width="100px" align="center"> <label><strong><? echo $account_manager?></strong> </label>
            </td>
            
            <td width="100px"  align="right"><strong>Company Name*:</strong></td>
            <td width="200px" align="center"><label><strong><?=$result['company_name']?></strong> </label></td>
                
            <td width="100px"  align="right"><strong>No. Of Vehicles:*</strong></td>
            <td width="100px" align="center"><label><strong><?=$result['no_of_vehicals']?></strong> </label></td>
        </tr>
        
    </table>    
   
    <table style=" padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">

		<tr>
            <td >&nbsp; </td>
            <td><input type="hidden" name="back_comment" id="back_comment" value="<?=$result['comment']?>"/></td>
        </tr>
        
        <tr>
            <td  align="right"> Area:*</td>
            <td> <input type="text" name="Zone_area" id="Zone_area" value="<?php echo $area["name"];?>" /> <div id="ajax_response"></div></td>
        </tr> 
        <!--<tr>
            <td  align="right">
           		 Area:*</td>
            <td> 
                <select name="Zone_area" id="Zone_area" >
                <option value="" >-- Select One --</option>
                <?php
                /*$main_city=mysql_query(" select id,name from re_city_spr_1 order by name asc");
                while($data=mysql_fetch_assoc($main_city))
                {
                    if($data['id']==$result['Zone_area'])
                    {
                        $selected="selected";
                    }
                    else
                    {
                        $selected="";
                    }*/
                ?>
                
                <option value ="<?php //echo $data['id'] ?>"  <?echo $selected;?>>
                <?php //echo $data['name']; ?>
                </option>
                <?php 
               // } 
                
                ?>
                </select>
            
            </td>
        </tr>-->


	   <tr>
          <td colspan="2">
                
                <table  id="samebranchid"  align="left"  style="padding-left:65px; width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>		
                        <td  align="right">Location:*</td>
                        <td  ><input type="text" name="location"  id="location"   style="width:147px" value="<?=$result['location']?>"/></td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <td align="right">Availbale Time status:*</td><td colspan="2">
                <select name="atime_status" id="atime_status" style="width:150px" onchange="TillBetweenTime(this.value)">
                	<option value="">Select Status</option>
                    <option value="Till" <? if($result['atime_status']=='Till') {?> selected="selected" <? } ?> >Till</option>
                    <option value="Between" <? if($result['atime_status']=='Between') {?> selected="selected" <? } ?> >Between</option>
                 </select>
            </td>
        </tr>

		<tr>
        	<td colspan="2" align="right">
		        
            <table  id="TillTime" align="left" style="padding-left: 80px;width: 240px;display:none;border:1"  cellspacing="5" cellpadding="5">
                    <tr>
                        <td height="32" align="right">Time:*</td>
                        <td>
                             <input type="text" name="time" id="datetimepicker" value="<?=$result['time']?>" style="width:147px"/>
                               
                         </td>
                    </tr>
            </table>
         </td>
    </tr>

	<tr>
    	<td colspan="2" align="right">
		        
       		 <table  id="BetweenTime" align="left" style="padding-left: 80px;width: 240px;display:none;border:1"  cellspacing="5" cellpadding="5">
                <tr>
                    <td height="32" align="right">From Time:*</td>
                    <td>
                         <input type="text" name="time1" id="datetimepicker1" value="<?=$result['time']?>" style="width:147px"/>
                           
                         </td>
                </tr>
                <tr>
                    <td height="32" align="right">To Time:*</td>
                    <td>
                         <input type="text" name="totime" id="datetimepicker2" value="<?=$result['totime']?>" style="width:147px"/>
                           
                         </td>
                </tr>
            </table>
         </td>
    </tr>

    <tr>
        <td height="32" align="right">Contact No.:*</td>
        <td><input type="text" name="cnumber" value="<?=$result['contact_number']?>" style="width:147px"/></td>
    </tr>
    <tr>
        <td height="32" align="right">Contact Person:*</td>
        <td><input type="text" name="contact_person" value="<?=$result['contact_person']?>" style="width:147px"/></td>
    </tr>

    <tr>
        <td height="32" align="right">Comment:</td>
        <td><textarea rows="5" cols="25"  type="text" name="comment" id="TxtComment" ><?=$result['comment']?></textarea></td>
    </tr>
    <tr>
            <td height="32" align="right"><input type="submit" name="submit" value="Update" align="right" /></td>
            <td><input type="button" name="Cancel" value="Cancel" onClick="window.location = 'installation.php' " /></td>
     </tr>
  </table>
  
	</form>
   </div>


<?
include("../include/footer.inc.php");

?>
 
<script>StatusBranch12("<?=$result['branch_type'];?>");TillBetweenTime12("<?=$result['atime_status'];?>");</script> 
 
 
 