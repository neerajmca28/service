<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
//include_once(__DOCUMENT_ROOT.'/private/master.php');

//$masterObj = new master();

?>

<script>
function ConfirmDelete(row_id)
{
  var x = confirm("Are you sure you want to Close this?");
  if (x)
  {
  approve(row_id);
      return ture;
  }
  else
    return false;
}

function approve(row_id)
{
    //alert(user_id);
    //return false;
$.ajax({
        type:"GET",
        url:"userInfo.php?action=clientExtension",
         data:"row_id="+row_id,
        success:function(msg){
         
        location.reload(true);        
        }
    });
}

</script>

 <div class="top-bar">
          <div align="center">
             <form name="myformlisting" method="post" action="">
                <select name="Showrequest" id="Showrequest" onChange="form.submit();" >
                    <option value="" <? if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending</option>
                    <option value="1" <? if($_POST['Showrequest']==1){ echo "Selected"; }?>>Closed</option>
                    <option value="2" <? if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
                
                </select>
            </form>
        </div>       
<div align="center">
</div>
    <h1>Add Client List</h1>
      
</div>
<div class="top-bar">    
    <div style="float:right";><font style="color:#99FF66;font-weight:bold;">Green:</font> Completed your requsest.</div>              
</div>
<div class="table">
<?php

 if($_POST["Showrequest"]==1)
 {
    $WhereQuery=" where final_status='1'";   
 }
 else if($_POST["Showrequest"]==2)
 {
    $WhereQuery=" ";
 }
 else
 { 
    $WhereQuery=" where final_status='0'";

 }
    
$assign_client = select_query("SELECT * FROM internalsoftware.add_client_information ". $WhereQuery." order by user_name"); 

//echo "<pre>";print_r($assign_client);die;

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>SL No</th>
            <th>Users Name</th>
            <th>Company Name</th>
            <th>Phone No</th>
            <th>Executive Name</th>
            <th>Extension No</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
 
<?php 

for($k=0;$k<count($assign_client);$k++)
{    
    
?>

<tr align="center">
 
  <td><?php echo $k+1; ?></td>
  <td><?php echo $assign_client[$k]["user_name"];?></td>
  <td><?php echo $assign_client[$k]["company_name"];?></td>
  <td><?php echo $assign_client[$k]["contact_number"];?></td>
  <td><?php echo $assign_client[$k]["executive_name"];?></td>
  <td><?php echo $assign_client[$k]["extension_no"];?></td>
  <? if($_POST['Showrequest']!=1 && $_POST['Showrequest']!=2){ ?>
  <td><a href="#" onclick="return ConfirmDelete(<?php echo $assign_client[$k]["id"];?>);" >Done</a></td>
  <? } else { ?>
  <td>&nbsp;</td>
  <? } ?>
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