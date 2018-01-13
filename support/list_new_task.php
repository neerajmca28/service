<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');


?>

 
 <div class="top-bar">
                  
            <!--<div align="center">
                <form name="myformlisting" method="post" action="">
                    <select name="Showrequest" id="Showrequest" onchange="form.submit();" >
                        <option value="0" <? if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>
                        <option value=3 <?if($_POST['Showrequest']==3){ echo "Selected"; }?>>Admin Approved</option>
                        <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending</option>
                        <option value="4" <?if($_POST['Showrequest']==4){ echo "Selected"; }?>>Action Taken</option>
                        <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Closed</option>
                        <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
                       
                    </select>
                </form>
            </div>-->
    <h1>New Task</h1>
     
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


$query = select_query("SELECT * FROM tbl_new_task order by date_time desc");  

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>SL.No</th>
            <th>Request By</th>
            <th>Assigne To</th>
            <th>View Comment</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>


 
<?php

for($i=0;$i<count($query);$i++)
{
?>
  <tr align="center">
    <td><?php echo $i+1; ?></td>
    <td><?php echo $query[$i]["request_persion"];?></td>
    <td><?php echo $query[$i]["assign_to"];?></td>
    <td><?php echo $query[$i]["comment"];?></td> 
    <td><?php echo $query[$i]["date_time"];?></td>
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
include("../include/footer.inc.php"); ?>