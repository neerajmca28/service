<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");

include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/ 
?> 

<script>
function forwardbackComment(row_id)
{
   var retVal = prompt("Write Comment : ", "Comment");
  if (retVal)
  {
  addComment(row_id,retVal);
      return ture;
  }
  else
    return false;
}

function addComment(row_id,retVal)
{
	//alert(user_id);
	//return false;
$.ajax({
		type:"GET",
		url:"userInfo.php?action=deactivatesimbackComment",
 		 
		 data:"row_id="+row_id+"&comment="+retVal,
		success:function(msg){
			 alert(msg);
			 
		 
		location.reload(true);		
		}
	});
}


</script>

 
 <div class="top-bar">
        <div align="center">
            <form name="myformlisting" method="post" action="">
                <select name="Showrequest" id="Showrequest" onChange="form.submit();" >
                    <option value="0" <? if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>
                    <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending+Admin Forward</option>
                    <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Closed</option>
                    <option value="3" <?if($_POST['Showrequest']==3){ echo "Selected"; }?>>Action Taken</option>
                    <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
                    
                </select>
            </form>
        </div> 
                    <h1>Deactivate Sim List</h1>
					  
                </div>
                <div class="top-bar">
                    
                <div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font> Approved</div>
                <br/>
                <div style="float:right";><font style="color:#FF3333;font-weight:bold;">Red:</font> Back from support</div>
                <br/>
                <div style="float:right";><font style="color:#F2F5A9;font-weight:bold;">Yellow:</font> Back from Admin</div>
                
                
                <br/>
                <div style="float:right";><font style="color:#99FF66;font-weight:bold;">Green:</font> Completed your requsest.</div>			  
                </div>
                <div class="table">
<?php
 
if($_POST["Showrequest"]==1)
 {
	   $WhereQuery=" where approve_status=1 and final_status=1 and forward_req_user='".$_SESSION["username"]."'";
 }
 else if($_POST["Showrequest"]==2)
 {
	$WhereQuery=" where forward_req_user='".$_SESSION["username"]."'";
 }
 else if($_POST["Showrequest"]==3)
 {
	 $WhereQuery=" where forward_back_comment!='' and forward_req_user='".$_SESSION["username"]."'";
 }
 else
 { 
   $WhereQuery=" where approve_status=1 and (forward_back_comment is null or forward_back_comment='') and forward_req_user='".$_SESSION["username"]."'";
  
 }
 
$query = select_query("SELECT * FROM deactivate_sim  ". $WhereQuery." order by id DESC ");

?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
            <th>SL.No</th>
            <th>Date</th>
            <th>Account Manager</th>
            <th>User Name</th>
            <th>Vehicle Number</th>
            <th>Devcie IMEI</th>
            <th>SIM No</th>
            <th>Ownership</th>
            <th>View Detail</th>
            <th>Admin Back Comment</th>
		</tr>
	</thead>
	<tbody>



 
<?php 

//while($row=mysql_fetch_array($query))
for($i=0;$i<count($query);$i++)
{
?>
<tr align="center" <? if( $query[$i]["support_comment"]!="" && $query[$i]["final_status"]!=1 ){ echo 'style="background-color:#68C5CA"';} elseif($query[$i]["final_status"]==1){ echo 'style="background-color:#99FF66"';}elseif($query[$i]["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';}elseif( $query[$i]["admin_comment"]!="" && $query[$i]["final_status"]!=1 && $query[$i]["service_comment"]=="" ){ echo 'style="background-color:#F2F5A9"';}?> >

<td><?php echo $i+1;?></td>

<td><?php echo $query[$i]["date"];?></td>
<td><?php echo $query[$i]["sales_manager"];?></td>
<? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$query[$i]["user_id"];
$rowuser=select_query($sql);
?>
<td><?php echo $rowuser[0]["sys_username"];?></td>
<td><?php echo $query[$i]["vehicle"];?></td> 
<td><?php echo $query[$i]["device_imei"];?></td> 
<td><?php echo $query[$i]["device_sim"];?></td> 
<td><?php echo $query[$i]["ps_of_ownership"];?></td> 
<td>
	<a href="#" onClick="Show_record(<?php echo $query[$i]["id"];?>,'deactivate_sim','popup1'); " class="topopup">View Detail</a>
</td>
   <td> <a href="#" onclick="return forwardbackComment(<?php echo $query[$i]["id"];?>);"  >Forward Back Comment</a></td>




</tr> <?php }?>
</table>
     
   <div id="toPopup"> 
    	
        <div class="close">close</div>
       	<span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
		<div id="popup1"> <!--your content start-->
            

 
        </div> <!--your content end-->
    
    </div> <!--toPopup end-->
    
	<div class="loader"></div>
   	<div id="backgroundPopup"></div>
</div>
 
<?php
include("../include/footer.php"); ?>


