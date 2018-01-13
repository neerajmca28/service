<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/


?>

 
 <div class="top-bar">
            <div align="center">
                <form name="myformlisting" method="post" action="">
                    <select name="Showrequest" id="Showrequest" onchange="form.submit();" >
                        <option value="0" <? if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>
                        <option value=3 <?if($_POST['Showrequest']==3){ echo "Selected"; }?>>support Approved</option>
                        <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending</option>
                        <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Closed</option>
                        <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
                    
                    </select>
                </form>
            </div> 
                    <h1>Mobile Number Change</h1>
					  
                </div>
                 <div class="top-bar">
                <div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font>support Approved</div>
                <br/> 
                <div style="float:right";><font style="color:#D462FF;font-weight:bold;">Purple:</font> Back from support</div>
                <br/>
                <div style="float:right";><font style="color:#FF0000;font-weight:bold;">Red:</font> Back from Account</div>
                <br/>
                <div style="float:right";><font style="color:#8BFF61;font-weight:bold;">Green:</font> Completed your requsest.</div>			  
                </div>
                <div class="table">
<?php
if($_SESSION['username']=="dservicehead"){$request = "('triloki','jaipurrequest','ragini','asaleslogin','pankaj','ksaleslogin','saleslogin')";}
elseif($_SESSION['username']=="ruchi"){$request = "('triloki','saleslogin')";}
elseif($_SESSION['username']=="ragini"){$request = "('ragini','mumbai','pankaj','msaleslogin')";}
elseif($_SESSION['username']=="rajeshree"){$request = "('asaleslogin')";}
elseif($_SESSION['username']=="jaipursupport"){$request = "('jaipurrequest')";}

if($_POST["Showrequest"]==1)
 {
	  $WhereQuery=" where approve_status=1 and final_status=1 and acc_manager in ".$request;
 }
 else if($_POST["Showrequest"]==2)
 {
	 $WhereQuery="where acc_manager in ".$request;
 }
  else if($_POST["Showrequest"]==3)
 {
	 $WhereQuery=" where approve_status=1 and final_status!=1 and acc_manager in ".$request;
 }
 else
 { 
	  	
	 $WhereQuery=" where approve_status=0 and final_status!=1 and acc_manager in ".$request;  
  
 }
 
$query = select_query("SELECT * FROM sim_change  ". $WhereQuery."    order by id DESC ");


//$query = mysql_query("SELECT * FROM sim_change where final_status=1 order by id DESC ");


?>



 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
        <tr>
            <th>SL.No</th>
            <th>Date</th>
            <th>Account Manager</th>
            <th>Client</th>
            <th>Vehicles No</th>
            <th>Device Mobile Number</th> 
            <th>New SIM No</th> 
            <th>Reason</th> 
            <th>View Detail</th>
        </tr>
	</thead>
	<tbody>
 
 
 
<?php 

//while($row=mysql_fetch_array($query))
for($i=0;$i<count($query);$i++)
{
?>


<tr align="center" <? if( $query[$i]["support_comment"]!="" && $query[$i]["final_status"]!=1 ){ echo 'style="background-color:#D462FF"';}elseif($query[$i]["total_pending"]!="" && $query[$i]["account_comment"]=="" && $query[$i]["service_comment"]==""){ echo 'style="background-color:#FF0000"';}elseif($query[$i]["approve_status"]==1 && $query[$i]["final_status"]!=1){ echo 'style="background-color:#B6B6B4"';} elseif($query[$i]["final_status"]==1 && $query[$i]["close_date"]!=""){ echo 'style="background-color:#8BFF61"';}?> >
 

<td><?php echo $i+1;?></td>
<td><?php echo $query[$i]["date"];?></td>
<td><?php echo $query[$i]["acc_manager"];?></td>
<? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$query[$i]["user_id"];
$rowuser=select_query($sql);
?>
<td><?php echo $query[$i]["client"];?></td>
<td><?php echo $query[$i]["reg_no"];?></td> 
<td><?php echo $query[$i]["old_sim"];?></td> 
<td><?php echo $query[$i]["new_sim"];?></td>
<td><?php echo $query[$i]["reason"];?></td>
<td><a href="#" onclick="Show_record(<?php echo $query[$i]["id"];?>,'sim_change','popup1'); " class="topopup">View Detail</a>

</td>



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
include("../include/footer.inc.php"); ?>




