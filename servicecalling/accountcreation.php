<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");*/

/*if(isset($_GET["for"]) && $_GET["for"]=='formatrequest')
 {	
 	$pagefor="for=formatrequest";
	 include($_SERVER['DOCUMENT_ROOT']."/format/include/leftmenu.php");

 
 }
 else
 { $pagefor="";
	 include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); 
 }*/

if($_GET["rowid"])
{
	echo $_GET["rowid"];
}

?>

 
 <div class="top-bar">
                    <a href="NewAccountCreation.php" class="button">ADD NEW </a>
                    <h1>New Account Creation</h1>
					  
                </div>
                   
        <div class="top-bar">
                    
                  <div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font> Approved</div>
<br/>
                  <div style="float:right";><font style="color:#FF3333;font-weight:bold;">Red:</font> Back from support</div>
<br/>
<div style="float:right";><font style="color:#99FF66;font-weight:bold;">Green:</font> Completed your requsest.</div>			  
                </div>
                
                
                <div class="table">
<?php
 
 
 	$query = mysql_query("SELECT * FROM new_account_creation  order by id DESC ");



?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
	<th>SL.No</th>
	<th>Date</th>
	<th>Account Manager</th>
	<th>Company</th>
   <th>Potential</th>  
	<th>Contact Person</th>
	<th>Contact Number</th>
	<!--  <th>Billing Name</th>
	<th>Billing Address</th>
	<th>price</th>
	<th>vat</th>
	<th>total</th>
	<th>rent</th>
	<th>service tax</th>
	<th>mode of payment</th> -->
	<!-- <th>Vehicle type</th> -->
	<!--  <th>Immobilizer (Y/N)</th>
	<th>AC (ON/OFF)</th>
	<th>E_mail ID</th>  
	<th>User Name</th>
	<th>Password</th>-->
	<th>View Detail</th>
	<th>Edit</th>
	</tr>
	</thead>
	<tbody>


 
<?php 
$i=1;
while($row=mysql_fetch_array($query))
{
?>
<tr align="center" <? if( $row["support_comment"]!="" && $row["final_status"]!=1 ){ echo 'style="background-color:#FF3333"';} elseif($row["final_status"]==1){ echo 'style="background-color:#99FF66"';}elseif($row["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';}?> >
 
 
<td><?php echo $i?></td>
  <td><?php echo $row["date"];?></td>
  <td><?php echo $row["account_manager"];?></td>
  <td><?php echo $row["company"];?></td>
  <td><?php echo $row["potential"];?></td>
  <td><?php echo $row["contact_person"];?></td>
  <td><?php echo $row["contact_number"];?></td>
 <!--  <td><?php echo $row["billing_name"];?></td>
  <td><?php echo $row["billing_address"];?></td>
  <td><?php echo $row["device_rent_price"];?></td>
  <td><?php echo $row["device_rent_vat"];?></td>
  <td><?php echo $row["device_rent_total"];?></td>
  <td><?php echo $row["device_rent_rent"];?></td>
  <td><?php echo $row["device_rent_service_tax"];?></td>
  <td><?php echo $row["mode_of_payment"];?></td> -->
  <!-- <td><?php echo $row["vehicle_type"];?></td> -->
  <!-- <td><?php echo $row["immobilizer"];?></td>
  <td><?php echo $row["ac_on_off"];?></td>
  <td><?php echo $row["email_id"];?></td> 
  <td><?php echo $row["user_name"];?></td>
  <td><?php echo $row["user_password"];?></td> -->
  
  <td><a href="#" onclick="Show_record_sales(<?php echo $row["id"];?>,'new_account_creation','popup1'); " class="topopup">View Detail</a>
</td> <td>
   <? if($row["final_status"]!=1 )
	{?>
	<a href='NewAccountCreation.php?rowid=<?= $row["id"]?>'>Edit</a>

	<?}?>
 </td>
</tr> <?php $i++; }?>
</table>
     
  
 

<div id="toPopup" > 
    	
        <div class="close">close</div>
       	<span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
		<div id="popup1" > <!--your content start-->
            

 
        </div> <!--your content end-->
    
    </div> <!--toPopup end-->
    
	<div class="loader"></div>
   	<div id="backgroundPopup"></div>
</div>
 

 
<?php
include("../include/footer.php"); ?>
 