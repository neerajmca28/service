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
                    <option value=3 <?if($_POST['Showrequest']==3){ echo "Selected"; }?>>Admin Approved</option>
                    <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending</option>
                    <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Closed</option>
                    <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
                
                </select>
            </form>
        </div>   
                    <h1>Deletion List</h1>
					  <div class="top-bar">
                        <div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font>Admin Approved</div>
                        <br/>
                        <div style="float:right";><font style="color:#D462FF;font-weight:bold;">Purple:</font> Back from support</div>
                        <br/>
                        <div style="float:right";><font style="color:#FF0000;font-weight:bold;">Red:</font> Back from Admin</div>
                        <br/>
                        <div style="float:right";><font style="color:#8BFF61;font-weight:bold;">Green:</font> Completed your requsest.</div>			  
                        </div> 
               		 </div>
                
                <div class="table">
<?php
if($_SESSION['username']=="dservicehead"){
 $request = "('triloki','jaipurrequest','ragini','asaleslogin','pankaj','ksaleslogin','saleslogin','lovely','swati','Mamta','himanshi','Himani','jyoti')";
 }
elseif($_SESSION['username']=="ruchi"){$request = "('triloki','saleslogin','lovely','swati','Mamta','himanshi','Himani','jyoti')";}
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
 
$query = select_query("SELECT * FROM deletion ". $WhereQuery. " order by id DESC ");

//$query = mysql_query("SELECT * FROM deletion where final_status=1 order by id DESC ");

?>


 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
            <th>SL.No</th>
            <th>Date</th>
            <th>Account Manager</th>
            <th>client</th>
            <th>Vehicle Number</th>
            <th>  Device IMEI</th>
            <th>  Sim No.</th>
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
<!--<tr align="center" <?// if( $query[$i]["support_comment"]!="" && $query[$i]["final_status"]!=1 ){ echo 'style="background-color:#D462FF"';} elseif($query[$i]["odd_paid_unpaid"]!="" && $query[$i]["odd_paid_unpaid"]!="Payment cleared" && $query[$i]["service_comment"]==""){ echo 'style="background-color:#FF0000"';}elseif($query[$i]["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';} elseif($query[$i]["final_status"]==1){ echo 'style="background-color:#8BFF61"';}?> >-->

 <tr align="center" <? if( $query[$i]["support_comment"]!="" && $query[$i]["final_status"]!=1 ){ echo 'style="background-color:#68C5CA"';} elseif($query[$i]["final_status"]==1){ echo 'style="background-color:#99FF66"';}elseif( $query[$i]["forward_comment"]!="" && $query[$i]["forward_req_user"]!="" ){ echo 'style="background-color:#CFBF7E"';}elseif($query[$i]["admin_comment"]!="" && $query[$i]["approve_status"]==0){ echo 'style="background-color:#FF0000"';}elseif($query[$i]["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';}elseif( $query[$i]["admin_comment"]!="" && $query[$i]["final_status"]!=1 ){ echo 'style="background-color:#F2F5A9"';}?> >
 
<td><?php echo $i+1;?></td>
<td><?php echo $query[$i]["date"];?></td>
<td><?php echo $query[$i]["acc_manager"];?></td>
<? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$query[$i]["user_id"];
$rowuser=select_query($sql);
?>
<td><?php echo $query[$i]["client"];?></td>
<td><?php echo $query[$i]["reg_no"];?></td> 
<td><?php echo $query[$i]["imei"];?></td>
<td><?php echo $query[$i]["device_sim_no"];?></td> 
<td><?php echo $query[$i]["reason"];?></td> 
<td style="width:140px"><a href="#" onclick="Show_record(<?php echo $query[$i]["id"];?>,'deletion','popup1'); " class="topopup">View Detail</a>
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

