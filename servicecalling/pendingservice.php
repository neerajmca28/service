<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */ 

$id=$_GET['id'];
if($_GET['action']=="close")
{
    //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
  $query1="UPDATE services SET pending_closed='1',service_status=6  WHERE id='$id'";
mysql_query($query1);
}

?> 

    <div class="top-bar">
    
    <h1>Pending Service</h1>
    
    </div>
    
    <div style="float:right";><a href=<?="excel/".$_SESSION['username']."-pending_services.xls"?>>Create Excel</a><br/></div>
    
    <div class="table">
<?php
 
    //$rs = mysql_query("SELECT * FROM services WHERE (pending='1' or newpending='1') and (pending_closed='0') and branch_id=".$_SESSION['BranchId'] ." order by id desc");
 
$rs = mysql_query("SELECT *,DATE_FORMAT(req_date,'%d %b %Y %h:%i %p') as req_date,DATE_FORMAT(atime,'%d %b %Y %h:%i %p') as atime  FROM services WHERE service_status=3 and branch_id=".$_SESSION['BranchId'] ."  and request_by='".$_SESSION['username']."' order by id desc");
 //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr> <th>Sl. No</th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Request Date </b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>ClientName </b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Vehicle No</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Notworking </b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Location</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Device Model</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Available Time</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Contact No</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Back Reason</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Current Status</b></font></th>
        <th >Edit </th>
        <th >Close </th>
        </tr>
    </thead>
    <tbody>
 
 
 
<?php 

$excel_data.='<table cellpadding="5" cellspacing="5" border="1"><thead><tr><td colspan="12" align="center"><strong>Pending Service</strong></td></tr><tr><td colspan="12"></td></tr><tr><th width="5%">Sl. No</th><th width="10%">Request Date</th><th width="10%">ClientName </th><th width="10%">Vehicle Number </th><th width="10%">Notworking</th><th width="10%">Location </th><th width="10%">Device Model </th><th width="10%">Available Time</th><th width="10%">Contact No</th><th width="10%">Back Reason</th></tr></thead><tbody>';

 
 $i=1;
while($row=mysql_fetch_array($rs))
{
?>
<tr align="center" <? if( $row["support_comment"]!="" && $row["final_status"]!=1 ){ echo 'style="background-color:#FF3333"';} elseif($row["final_status"]==1){ echo 'style="background-color:#99FF66"';}elseif($row["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';}?> >
<td><?php echo $i;?></td>
          <td width="11%" align="center">&nbsp;<?php echo $row['req_date'];?></td>
          <td width="11%" align="center">&nbsp;<?php echo $row['name'];?></td>
        <td width="11%" align="center">&nbsp;<?php echo $row['veh_reg'];?></td>
        
        <td width="12%" align="center">&nbsp;<?php echo $row['Notwoking'];?></td>
        <?php if($row['location']!=""){ $location = $row['location'];}
        else{ $city= mysql_fetch_array(mysql_query("select * from tbl_city_name where branch_id='".$row['inter_branch']."'")); $location = $city['city'];}?>
        
        <td width="10%" align="center">&nbsp;<?php echo $location;?></td>
        
        <td width="10%" align="center">&nbsp;<?php echo $row['device_model'];?></td>
        <td width="10%" align="center">&nbsp;<?php echo $row['atime'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['cnumber'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['back_reason'];?></td>
                <td><strong>
            <?  if($row[0]["service_status"]==7 && ($row[0]["admin_comment"]!="" || $row[0]["sales_comment"]=="")){echo "Reply Pending at Request Side";}
        elseif($row[0]["service_status"]==7 && $row[0]["admin_comment"]=="" ){echo "Pending Saleslogin for Information";}
        elseif($row[0]["approve_status"]==0 && $row[0]["service_status"]==8 ){echo "Pending Admin Approval";}
        elseif($row[0]["service_status"]==9 && $row[0]["approve_status"]==1 ){echo "Pending Confirmation at Request Person";}
        elseif($row[0]["service_status"]==1 ){echo "Pending Dispatch Team";}
        elseif($row[0]["service_status"]==2 ){echo "Assign To Installer";}
        elseif($row[0]["service_status"]==11 ){echo "Request Forward to Repair Team";}
        elseif($row[0]["service_status"]==3 ){echo "Back Service";}
        elseif($row[0]["service_status"]==15 ){echo "Pending Remaining Installation";}
        elseif($row[0]["service_status"]==5 || $row[0]["service_status"]==6){echo "Installation Close";}
         ?>
            </strong></td>
        <td ><a href="service_request.php?rowid=<?=$row['id'];?>&edit=true">Edit</a></td>
         <td ><a href="?id=<?=$row['id'];?>&pending=1&action=close&pg=<? echo $pg;?>">Close</a></td>
</tr> 
<?php  

$excel_data.="<tr><td width='5%'>".$i."</td><td width='10%'>".$row['req_date']."</td><td width='10%'>".$row['name']."</td><td width='10%'>".$row['veh_reg']."</td><td width='10%'>". $row['Notwoking']."</td><td width='10%'>".$location."</td><td width='10%'>".$row['device_model']."</td><td width='10%'>".$row['atime']."</td><td width='10%'>".$row['cnumber']."</td><td width='10%'>".$row['back_reason']."</td></tr>";

 $i++;
 }
 
  $excel_data.='</tbody></table>';
  
 ?>
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
  
 
 
<?

unlink(__DOCUMENT_ROOT.'/servicecalling/excel/'.$_SESSION["username"].'-pending_services.xls');
$filepointer=fopen(__DOCUMENT_ROOT.'/servicecalling/excel/'.$_SESSION["username"].'-pending_services.xls','w');
fwrite($filepointer,$excel_data);
fclose($filepointer);

include("../include/footer.inc.php");

?>