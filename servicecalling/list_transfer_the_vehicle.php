<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

if($_SESSION['BranchId'] == 1)
{

	$telecaller = select_query("select * from internalsoftware.telecaller_users where login_name='".$_SESSION['username']."' and `status`='1'  
								and branch_id='".$_SESSION['BranchId']."'");
	
	$assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
					branch_id='".$_SESSION['BranchId']."' and sys_active='1' and telecaller_id='".$telecaller[0]['id']."' AND Userid NOT IN(1,2143)
					order by client_type"); 

}else {
	
	$assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
					branch_id='".$_SESSION['BranchId']."' and sys_active='1' AND Userid NOT IN(1,2143) order by client_type"); 

}

for($i=0;$i<count($assign_client);$i++)
    {
        $client_id.= $assign_client[$i]['Userid'].",";
    }
	
    $user = substr($client_id,0,strlen($client_id)-1);
?>

<div class="top-bar">
  <div align="center">
    <form name="myformlisting" method="post" action="">
      <select name="Showrequest" id="Showrequest" onchange="form.submit();" >
        <option value="0" <? if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>
        <option value=3 <?if($_POST['Showrequest']==3){ echo "Selected"; }?>>Admin Approved</option>
        <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending</option>
        <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Closed</option>
        <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
      </select>
    </form>
  </div>
  <h1>Transfer Vehicle</h1>
</div>
<div class="top-bar">
  <div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font>Admin Approved</div>
  <br/>
  <div style="float:right";><font style="color:#D462FF;font-weight:bold;">Purple:</font> Back from support</div>
  <br/>
  <div style="float:right";><font style="color:#FF0000;font-weight:bold;">Red:</font> Back from Account</div>
  <br/>
  <div style="float:right";><font style="color:#8BFF61;font-weight:bold;">Green:</font> Completed your requsest.</div>
</div>
<div class="table">
  <?php
  
if($_POST["Showrequest"]==1)
 {
	  $WhereQuery="where transfer_from_user IN($user) and approve_status=1 and final_status=1";
 }
 else if($_POST["Showrequest"]==2)
 {
	 $WhereQuery="where transfer_from_user IN($user)";
 }
  else if($_POST["Showrequest"]==3)
 {
	 $WhereQuery="where transfer_from_user IN($user) and approve_status=1 and final_status!=1";
 }
 else
 { 
	 $WhereQuery="where transfer_from_user IN($user) and (approve_status=0 or approve_status=1) and final_status!=1";   
 }
 
$query = select_query("SELECT * FROM transfer_the_vehicle ". $WhereQuery." order by id desc ");   

?>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      <tr>
        <th>SL.No</th>
        <th>Date</th>
        <th>Account Manager</th>
        <th>Transfer From Client</th>
        <th>Transfer To Client</th>
        <th>Transfer To Billing</th>
        <th>Reason</th>
        <th>Status</th>
        <th>View Detail</th>
      </tr>
    </thead>
    <tbody>
      <?php 

for($i=0;$i<count($query);$i++)
{
?>
      <tr align="center" <? if( $query[$i]["support_comment"]!="" && $query[$i]["final_status"]!=1 ){ echo 'style="background-color:#D462FF"';}elseif($query[$i]["total_pending"]!="" && $query[$i]["account_comment"]=="" && $query[$i]["sales_comment"]==""){ echo 'style="background-color:#FF0000"';}elseif($query[$i]["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';} elseif($query[$i]["final_status"]==1){ echo 'style="background-color:#8BFF61"';}?> >
        <td><?php echo $i+1;?></td>
        <td><?php echo $query[$i]["date"];?></td>
        <? if($query[$i]["acc_manager"]=='saleslogin') {
			$account_manager=$query[$i]["sales_manager"]; 
			}
			else {
			$account_manager=$query[$i]["acc_manager"]; 
			}

		?>
        <td><?php echo $account_manager;?></td>
        <td><?php echo $query[$i]["transfer_from_company"];?></td>
        
        <td><?php echo $query[$i]["transfer_to_company"];?></td>
        <td><?php echo $query[$i]["transfer_to_billing"];?></td>
        <td><?php echo $query[$i]["reason"];?></td>
        <td><?  if($query[$i]["transfer_veh_status"]==2 || (($query[$i]["support_comment"]!="" || $query[$i]["admin_comment"]!="") && $query[$i]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($query[$i]["account_comment"]=="" && $query[$i]["total_pending"]=="" && $query[$i]["approve_status"]==0 && $query[$i]["final_status"]==0){echo "Pending at Accounts";}
    elseif($query[$i]["approve_status"]==0 && ($query[$i]["account_comment"]!="" || $query[$i]["total_pending"]!="") && $query[$i]["final_status"]==0 && $query[$i]["transfer_veh_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($query[$i]["approve_status"]==1 && $query[$i]["transfer_veh_status"]==1 && $query[$i]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($query[$i]["final_status"]==1){echo "Process Done";}?></td>
        <td><a href="#" onclick="Show_record(<?php echo $query[$i]["id"];?>,'transfer_the_vehicle','popup1'); " class="topopup">View Detail</a></td>
      </tr>
      <?php }?>
  </table>
  <div id="toPopup">
    <div class="close">close</div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup1"> <!--your content start--> 
      
    </div>
    <!--your content end--> 
    
  </div>
  <!--toPopup end-->
  
  <div class="loader"></div>
  <div id="backgroundPopup"></div>
</div>
<?php
include("../include/footer.inc.php"); ?>
