<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/private/master.php');

$masterObj = new master();

?>
 
 <div class="top-bar">
          <div align="center">
             <form name="myformlisting" method="post" action="">
                <select name="Showrequest" id="Showrequest" onChange="form.submit();" >
                    <option value="" <? if($_POST['Showrequest']==''){ echo "Selected"; }?>>All</option>
                    <option value="1" <? if($_POST['Showrequest']==1){ echo "Selected"; }?>>A+</option>
                    <option value="2" <? if($_POST['Showrequest']==2){ echo "Selected" ;}?>>A</option>
                    <option value="3" <? if($_POST['Showrequest']==3){ echo "Selected"; }?>>B</option>
                    <option value="4" <? if($_POST['Showrequest']==4){ echo "Selected"; }?>>C</option>
                
                </select>
            </form>
        </div>       
<div align="center">
</div>
    
    <h1>Client List</h1>
      
</div>
<div class="table">
<?php

if($_POST["Showrequest"]==1)
 {
    $WhereQuery=" where sys_active='1' and sys_parent_user='1' and client_type='1'";   
 }
 else if($_POST["Showrequest"]==2)
 {
    $WhereQuery=" where sys_active='1' and sys_parent_user='1' and client_type='2'";
 }
 else if($_POST["Showrequest"]==3)
 {
    $WhereQuery=" where sys_active='1' and sys_parent_user='1' and client_type='3'";
 }
 else if($_POST["Showrequest"]==4)
 {
     $WhereQuery=" where sys_active='1' and sys_parent_user='1' and client_type='4'";
 }
 else
 { 
    $WhereQuery=" where sys_active='1' and sys_parent_user='1'";

 }
 
if($_SESSION['BranchId'] == 1)
{

    $telecaller = select_query("select * from internalsoftware.telecaller_users where login_name='".$_SESSION['username']."' and `status`='1'  
                                and branch_id='".$_SESSION['BranchId']."'");
    
    $assign_client = select_query("SELECT * FROM internalsoftware.addclient ". $WhereQuery." and telecaller_id='".$telecaller[0]['id']."'   order by client_type"); 

}else {
    
    $assign_client = select_query("SELECT * FROM internalsoftware.addclient ". $WhereQuery." and branch_id='".$_SESSION['BranchId']."' order by client_type"); 

}  

//echo "<pre>";print_r($assign_client);die;

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>SL No</th>
            <th>Users</th>
            <th>Company Name</th>
            <th>Phone No</th>
            <th>Category</th>
            <th>Branch</th>
        </tr>
    </thead>
    <tbody>
 
<?php 

for($k=0;$k<count($assign_client);$k++)
{
    if($assign_client[$k]["client_type"] == 1) { $category = 'A+'; }
    elseif($assign_client[$k]["client_type"] == 2) { $category = 'A'; }
    elseif($assign_client[$k]["client_type"] == 3) { $category = 'B'; }
    elseif($assign_client[$k]["client_type"] == 4) { $category = 'C'; }
    else { $category = 'Not Define'; }
        
    
    if($assign_client[$k]["Branch_id"]==1){ $branch = "Delhi";}
    elseif($assign_client[$k]["Branch_id"]==2){ $branch = "Mumbai";}
    elseif($assign_client[$k]["Branch_id"]==3){ $branch = "Jaipur";}
    elseif($assign_client[$k]["Branch_id"]==4){ $branch = "Sonipath";}
    elseif($assign_client[$k]["Branch_id"]==6){ $branch = "Ahmedabad";}
    elseif($assign_client[$k]["Branch_id"]==7){ $branch = "Kolkata";}
    
    
?>

<tr align="center">
 
  <td><?php echo $k+1; ?></td>
  <td><?php echo $assign_client[$k]["UserName"];?></td>
  <td><?php echo $assign_client[$k]["company"];?></td>
  <td><?php echo $assign_client[$k]["mobile_number"];?></td>
  <td><?php echo $category;?></td>
  <td><?php echo $branch;?></td>
</tr> 
<?php } ?>
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