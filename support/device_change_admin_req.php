<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/

if($_GET["rowid"])
{
	echo $_GET["rowid"];
}

?>

 <script>
 function adminreqbackComment(row_id)
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
		url:"userInfo.php?action=devicechangebackComment",
 		 
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
                <select name="Showrequest" id="Showrequest" onchange="form.submit();" >
                    <option value="0" <? if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>
                    <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending+Admin Forward</option>
                    <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Closed</option>
                    <option value="3" <?if($_POST['Showrequest']==3){ echo "Selected"; }?>>Action Taken</option>
                    <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
                
                </select>
            </form>
        </div> 
                    <h1>Device Change List</h1>
					  
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
	 $WhereQuery=" where forward_back_comment!='' and approve_status=0 and forward_req_user='".$_SESSION["username"]."'";
 }
 else
 { 
   $WhereQuery=" where approve_status=0 and (forward_back_comment is null or forward_back_comment='') and forward_req_user='".$_SESSION["username"]."'";
  }
  
$query = select_query("SELECT * FROM device_change ". $WhereQuery."   order by id desc ");  

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
            <th>SL.No</th>
            <th>Date</th>
            <th>Account Manager</th>
            <th>Client</th>
            
            <th>Device IMEI</th>
            <th>Mobile Number</th>
            <!-- <th>Total Payment Received</th>
            <th>Total Pending</th>
            <th>No Of Devices Removed</th>
            <th>Device Status</th> -->
            <th>Reason</th> 
            <!--  <th>IMEI Of Removed Device</th>  
            <th>Reg No</th> -->
            <th>View Detail</th>
            <th>Admin Back Comment </th>
		</tr>
	</thead>
	<tbody>


 
<?php 

//while($row=mysql_fetch_array($query))
for($i=0;$i<count($query);$i++)
{
?>
<tr align="center" <? if( $query[$i]["support_comment"]!="" && $query[$i]["final_status"]!=1 ){ echo 'style="background-color:#68C5CA"';}elseif($query[$i]["total_pending"]!="" && $query[$i]["account_comment"]==""){ echo 'style="background-color:#FF0000"';} elseif($query[$i]["final_status"]==1){ echo 'style="background-color:#99FF66"';}elseif($query[$i]["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';}elseif( $query[$i]["admin_comment"]!="" && $query[$i]["final_status"]!=1 ){ echo 'style="background-color:#F2F5A9"';}?>?> 

<td><?php echo $i+1; ?></td>
  <td><?php echo $query[$i]["date"];?></td>
  
<? if($query[$i]["acc_manager"]=='saleslogin') {
$account_manager=$query[$i]["sales_manager"]; 
}
else {
$account_manager=$query[$i]["acc_manager"]; 
}

?>
  
  <td><?php echo $account_manager;?></td>
  

 <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$query[$i]["user_id"];
	$rowuser=select_query($sql);

			?>
  <td><?php echo $rowuser[0]["sys_username"];?></td>
 
  <td><?php echo $query[$i]["device_imei"];?></td>
  
   <td><?php echo $query[$i]["mobile_no"];?></td>
   <!-- 
  <td><?php echo $query[$i]["total_no_of_vehicle"];?></td>
  <td><?php echo $query[$i]["total_pay_rec"];?></td>
  <td><?php echo $query[$i]["total_pending"];?></td>
  <td><?php echo $query[$i]["no_of_devices_removed"];?></td>
  <td><?php echo $query[$i]["device_status"];?></td> -->
  <td><?php echo $query[$i]["rdd_reason"];?></td><!-- 
  <td><?php echo $query[$i]["imei_of_removel_devices"];?></td>
  <td><?php echo $query[$i]["reg_no"];?></td>
 -->

  <td><a href="#" onclick="Show_record(<?php echo $query[$i]["id"];?>,'device_change','popup1'); " class="topopup">View Detail</a></td>
<? if($query[$i]["forward_comment"]=$_SESSION["user_name"] or $query[$i]["forward_req_user"]!="") {?> 
 <td><a href="#" onclick="return adminreqbackComment(<?php echo $query[$i]["id"];?>);"  >Admin Back Comment</a> </td>
 <? } ?>
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

