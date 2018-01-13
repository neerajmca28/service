<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu_market.php');


?>

<script>
function unmappedVeh(row_id)
{
  var x = confirm("Are You Sure, You Want Unmapped Device?");
  if (x)
  {
  unmappedDone(row_id);
      return ture;
  }
  else
    return false;
}

function unmappedDone(row_id)
{
	//alert(user_id);
	//return false;
$.ajax({
		type:"GET",
		url:"userInfo.php?action=DeviceUnmapped",
 		data:"row_id="+row_id,
		success:function(msg){
		 
		location.reload(true);		
		}
	});
}

/*function ConfirmPaymentPending(row_id)
{
   var retVal = prompt("Pending Amount : ", "Pending Amount");
  if (retVal)
  {
  PaymentPending(row_id,retVal);
      return ture;
  }
  else
    return false;
}

function PaymentPending(row_id,retVal)
{
	//alert(user_id);
	//return false;
$.ajax({
		type:"GET",
		url:"userInfo.php?action=DiscountingPaymentPending",
 		 
		 data:"row_id="+row_id+"&comment="+retVal,
		success:function(msg){
			  
		location.reload(true);		
		}
	});
}*/
</script>
 <div class="top-bar">
                             
        <div align="center">
            <form name="myformlisting" method="post" action="">
                <select name="Showrequest" id="Showrequest" onchange="form.submit();" >
                    <option value="0" <? if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>
                    <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending</option>
                    <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Closed</option>
                    <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
                    
                </select>
            </form>
        </div>  
                    <h1>Trip Sheet List</h1>
					  
                </div>
                <!--<div class="top-bar">
                <div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font>Admin Approved</div>
                <br/>  
                <div style="float:right";><font style="color:#D462FF;font-weight:bold;">Purple:</font> Back from support</div>
                <br/>
                <div style="float:right";><font style="color:#FF0000;font-weight:bold;">Red:</font> Back from Account</div>
                <br/>
                <div style="float:right";><font style="color:#8BFF61;font-weight:bold;">Green:</font> Completed your requsest.</div>			  
                </div>-->
                <div class="table">
 
<?php

if($_SESSION['username']=="marketveh")
{
	$user_id=6843;
}
else
{
$user_id=7378;
}

 
if($_POST["Showrequest"]==1)
 {

	  $WhereQuery=" WHERE internal_status=5 and sys_user_id=".$user_id;
 }
 else if($_POST["Showrequest"]==2)
 {
	 $WhereQuery=" where  sys_user_id=".$user_id;
 }
 else
 { 
	  
    $WhereQuery=" WHERE (internal_status=1 or internal_status=2) and sys_user_id=".$user_id;
  
 }
 
 

$query = select_query_live_con("SELECT * FROM matrix.mapped_market_vehicle ". $WhereQuery." ORDER BY id desc ");   

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
            <th>SL.No</th>
            <th>Vehicle No</th>
            <th>Device Imei</th>
            <th>Recept No</th>
            <th>Mapped Time</th>
            <th>Source</th>
            <th>Destination</th>
            <th>Consignment No</th>
            <th>Driver Name</th> 
            <th>Driver Phone no</th> 
            <th>Transport Name</th> 
            <th>Action</th>            
		</tr>
	</thead>
	<tbody>
 
 
 
<?php 
//echo "<pre>"; print_r($query);die;
for($i=0;$i<count($query);$i++)
{
?>
    <tr align="center">
     
    
        <td><?php echo $i+1; ?></td>
        <td><?php echo $query[$i]["veh_no"]." - ". $query[$i]["sys_service_id"];?></td>        
        <td><?php echo $query[$i]["device_imei"];?></td>        
        <td><?php echo $query[$i]["recept_no"];?></td>
        <td><?php echo $query[$i]["mapped_time"];?></td>        
        <td><?php echo $query[$i]["source"];?></td>   
        <td><?php echo $query[$i]["destination"];?></td> 
		<td><?php echo $query[$i]["consignment_no"];?></td>
        <td><?php echo $query[$i]["driver_name"];?></td>
        <td><?php echo $query[$i]["driver_number"];?></td>
        <td><?php echo $query[$i]["tranport_name"];?></td>
        <td> <?php if($_POST["Showrequest"]== "") {?> 
        
		<?php if( $query[$i]["internal_status"]=="1" ) {?>   
        	<a href="edit_trip_info.php?id=<?=$query[$i]['id'];?>&action=edit">Edit</a>
        <?php } if( $query[$i]["internal_status"]=="2" ) {?>
        	<a href="edit_trip_info.php?id=<?=$query[$i]['id'];?>&action=edit">Edit</a> |
        	<a href="#" onclick="return unmappedVeh(<?php echo $query[$i]["id"];?>);" >Unmapped Veh</a>
        <?php }
		
		}
		?>        
        </td>
    </tr> 

<?php }?>
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
 




 