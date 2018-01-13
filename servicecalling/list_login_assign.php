<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */

$userId = $_SESSION['userId'];
$login_time = date("Y-m-d H:i:s");
?>

 
 <div class="top-bar">
     <div align="center">
         <form name="myformlisting" method="post" action="">
          <select name="Showrequest" id="Showrequest" onchange="form.submit();" >
            <option value="0" <? if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>      
            <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Login Assign</option>
            <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Old Details</option>
            <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All Details</option>
       
        </select>
        </form>
        </div>
            <a href="login_assign.php" class="button">ADD NEW </a>        
            <h1>Login Assign</h1>
             
        </div>
                   
                <!--<div class="top-bar">
                <div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font> Approved</div>
                <br/>
               
                <div style="float:right";><font style="color:#D462FF;font-weight:bold;">Purple:</font> Back from support</div>
                <br/>
                <div style="float:right";><font style="color:#F2F5A9;font-weight:bold;">Yellow:</font> Back from Admin</div><br/>
               
                <div style="float:right";><font style="color:#8BFF61;font-weight:bold;">Green:</font> Completed your requsest.</div>             
                </div>-->
    <div class="table">
<?php

 if($_POST["Showrequest"]==1)
 {
      $WhereQuery=" WHERE servicelogin_id='".$userId."' AND from_date<='".$login_time."' AND to_date<='".$login_time."'";
 }
 else if($_POST["Showrequest"]==2)
 {
     $WhereQuery=" WHERE servicelogin_id='".$userId."' ";
 }
 else
 {
     
     $WhereQuery="WHERE servicelogin_id='".$userId."' AND ((from_date<='".$login_time."' AND to_date>='".$login_time."') OR (from_date>='".$login_time."'))";
      
 }
 
$query = mysql_query("SELECT * FROM servicelogin_user_temp ".$WhereQuery."  order by id DESC ");

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>SL No</th>         
            <th>User Name</th>
            <th>Password</th>
            <th>Assign To</th>
            <th>Email Id</th>
            <th>Reason</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>


 
<?php
$i=1;
while($row=mysql_fetch_array($query))
{
?>
<tr align="center" >
    <td><?php echo $i ?></td>
    <td><?php echo $row["user_name"];?></td>
    <td><?php echo $row["password"];?></td>
    <td><?php echo $row["assign_to"];?></td>
    <td><?php echo $row["email"];?></td>
    <td><?php echo $row["reason"];?></td>
    <td><?php echo $row["from_date"];?></td>
    <td><?php echo $row["to_date"];?></td>
    <td>
    <?php if($_POST["Showrequest"]!=1 && $_POST["Showrequest"]!=2){?>
    <a href="login_assign.php?action=edit&id=<?=$row["id"];?>"  >Edit</a>
    <?php } else { echo "No Edit";}?>
    </td>
   
</tr> <?php $i++; }?>
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