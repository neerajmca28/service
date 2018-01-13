<?
session_start();
include("../connection.php");

/* include($_SERVER['DOCUMENT_ROOT']."/service/connection.php");*/

if(isset($_REQUEST["serviceid"]))
{
	 $service_id=$_REQUEST["serviceid"];

}


if(isset($_REQUEST["d"]) && $_REQUEST["d"]=="true")
{
	$CommentId=$_REQUEST["id"];
	mysql_query("delete from comment where id='".$CommentId."'",$dblink);
	//gotopage($homeurl."debug/debug/debug.php");
	//echo "delete from comment where id=".$CommentId;
	//die();

}
 
 $Comment_by=$_SESSION['username'];
	 
	 
 if(isset($_POST['submit']))
	 {
	 
		$Reason_debug=$_POST['Reason_debug'];
		
		if ($Reason_debug=="") {
		$Reason_debug_all=$_POST['Reason_debug_or'];
		}
		else  {
		$Reason_debug_all=$_POST['Reason_debug'];
		}
   
 
		// Insert in login attempt table for every attempt
		
		$insert_query = insert_query_live_con('matrix.comment', array('service_id' => $service_id ,'comment' =>$Reason_debug_all,'comment_by' =>$Comment_by));
		
		/*mysql_query("insert into matrix.comment(`service_id`,`comment`,`comment_by`) values('".$service_id."','".$Reason_debug_all."','".$Comment_by."')",$dblink) or die(mysql_error());*/
		
  		//$login_attempt_id=mysql_insert_id(); 
		echo "Comment Added successfully";
		//gotopage($homeurl."debug/debug/debug.php");
}

 
?>

 <html>
<head>
 
</head>
<body>


<? if(!isset($_REQUEST["view"]) && $_REQUEST["view"]!=true)
{?>

<form name="FrmComment" method="post" action="addcomment.php?serviceid=<?echo $_REQUEST["serviceid"]?>">



<table border="0" cellspacing="5" cellpadding="5" align="left">
 <tr><td>Add Comment</td></tr>
<tr>
 <td>
 
  <select name="Reason_debug" id="Reason_debug" width="150px">
        <option value="" >-- Select One --</option>
        <?php
        $main_city = select_query_live("select * from matrix.debug_reason");
		
        /*while($data=mysql_fetch_assoc($main_city))*/
		for($i=0;$i<count($main_city);$i++)
        {
			if($main_city[$i]['reason']==$_POST['Reason_debug'])
			{
				$selected="selected";
			}
			else
			{
				$selected="";
			}
        ?>
        
        <option value ="<?php echo $main_city[$i]['reason'] ?>"  <?echo $selected;?>>
        <?php echo $main_city[$i]['reason']; ?>
        </option>
        <?php 
        } 
        
        ?>
        </select>
        
        
 or
 </td>
 
</tr>

<tr> <td> <textarea cols="35" rows="1" type="text" name="Reason_debug_or" id="Reason_debug_or"> </textarea> </td></tr>

 <tr><td><input type="submit" name="submit" value="Submit"></td></tr>
</table>
</form>

<?  
}
 
?>
</body>
</html>
