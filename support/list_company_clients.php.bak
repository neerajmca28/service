<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/private/master.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");
include($_SERVER['DOCUMENT_ROOT']."/service/private/master.php");*/

$masterObj = new master();

?>
<div class="top-bar">
        <h1>Users Account List</h1>
</div>
 <div class="top-bar">
                    
    <div align="center">
     <form name="myformlisting" method="post" action="">
      <select name="Showrequest" id="Showrequest" onchange="form.submit();" >
      <option value="0" <? if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>
            <!--<option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending</option>-->
            <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Delhi</option>
            <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>Mumbai </option>
            <option value="3" <?if($_POST['Showrequest']==3){ echo "Selected" ;}?>>Jaipur </option>
            <option value="4" <?if($_POST['Showrequest']==4){ echo "Selected" ;}?>>Sonipath </option>
            <option value="5" <?if($_POST['Showrequest']==5){ echo "Selected" ;}?>>Ahmedabad </option>
            <option value="6" <?if($_POST['Showrequest']==6){ echo "Selected" ;}?>>Kolkata </option>
            <option value="" <?if($_POST['Showrequest']==''){ echo "Selected" ;}?>>All </option>
            
            </select>
            </form>
        </div>              
     </div>
        
      <div class="top-bar">
      <div style="float:right";><font style="color:#D462FF;font-weight:bold;">Purple:</font> Deactivate Users</div>
        <br/>
        <div style="float:right";><font style="color:#8BFF61;font-weight:bold;">Green:</font> Activate Users.</div>			  
    </div>
    <div class="table">
<?php 

if($_POST["Showrequest"]==1)
 {
	  $WhereQuery=" where branch_id=1";
 }
 else if($_POST["Showrequest"]==2)
 {
	 $WhereQuery="where branch_id=2 ";
 }
 else if($_POST["Showrequest"]==3)
 {
	 $WhereQuery="where branch_id=3 ";
 }
 else if($_POST["Showrequest"]==4)
 {
	 $WhereQuery="where branch_id=4 ";
 }
 else if($_POST["Showrequest"]==5)
 {
	 $WhereQuery="where branch_id=6 ";
 }
 else if($_POST["Showrequest"]==6)
 {
	 $WhereQuery="where branch_id=7 ";
 }
 else
 { 
	 $WhereQuery=" ";
 }
 

$query = select_query_live_con("SELECT *, case when sys_active=true then true else false end as active FROM matrix.users ". $WhereQuery." order by sys_username ASC");   

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
        <tr>
            <th>SL No.</th>
            <th>Company Name</th>
            <th>Phone No</th>
            <th>Address</th>
            <th>Branch</th>
            <th>Creation Date</th>
            <th>SSE</th>
            <?php if($_SESSION['username']=="dservicehead"){?>
            <th>Add SSE</th> 
            <?php } ?>
        </tr>
	</thead>
	<tbody>
 
<?php 

//while($row=mysql_fetch_array($query))
for($i=0;$i<count($query);$i++)
{
	if($query[$i]["branch_id"]==1){ $branch = "Delhi";}
	elseif($query[$i]["branch_id"]==2){ $branch = "Mumbai";}
	elseif($query[$i]["branch_id"]==3){ $branch = "Jaipur";}
	elseif($query[$i]["branch_id"]==4){ $branch = "Sonipath";}
	elseif($query[$i]["branch_id"]==6){ $branch = "Ahmedabad";}
	elseif($query[$i]["branch_id"]==7){ $branch = "Kolkata";}
?>

<tr align="center" <? if( $query[$i]["active"]==false){ echo 'style="background-color:#D462FF"';} elseif($query[$i]["active"]==true){ echo 'style="background-color:#8BFF61"';}?> >
  <td><?php echo $i+1;?></td>
  <td><?php echo $query[$i]["company"].'<br/>('.$query[$i]["sys_username"].')';?></td>
  <td><?php echo $query[$i]["mobile_number"];?></td>
  <td><?php echo wordwrap($query[$i]["address"],30, "<br />");?></td>
  <td><?php echo $branch;?></td>
  <td><?php echo $query[$i]["sys_added_date"];?></td>
  <td><?php echo $query[$i]["telecaller"];?> </td>
  <?php if($_SESSION['username']=="dservicehead"){?>
  <td><a href="telecaller-iframe.php?id=<?= $query[$i]["id"]?>&req_id=25&height=220&width=330" class="thickbox" >Add SSE</a>  </td>
  <?php } ?>
  
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



 