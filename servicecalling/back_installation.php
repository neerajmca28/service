<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */


$id=$_GET['id'];
$inst_req_id = $_GET['req_id'];
if($_GET['action']=="close")
{
    //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
    $query1="UPDATE installation SET pending_closed='1',installation_status=6  WHERE id='$id'";
    mysql_query($query1);
    
    $total_row = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_count FROM installation WHERE inst_req_id='".$inst_req_id."'"));
    
    $close_inst_row = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_row FROM installation WHERE inst_req_id='".$inst_req_id."' AND installation_status IN ('5','6')"));
    
    if($total_row['total_count'] == $close_inst_row['total_row'])
    {
        mysql_query("UPDATE installation_request SET close_date='".date("Y-m-d")."', installation_made='".$close_inst_row['total_row']."', installation_status=5 WHERE id='".$inst_req_id."'");
    }
    
    echo "<script>document.location.href ='back_installation.php'</script>";
}

?> 

<div class="top-bar">
   
    <h1>Back Installation</h1>
      
</div>

    <div class="top-bar">
    
        <div style="float:right";><font style="color:#CC6;font-weight:bold;">Brown:</font> Back Installation</div>
        <br/>
    </div>
    
    <div style="float:right";><a href=<?="excel/".$_SESSION['username']."-back_installation.xls"?>>Create Excel</a><br/></div>
    
<div class="table">
<?php
 
    
    //$rs = mysql_query("SELECT * FROM installation WHERE (pending='1' or newpending='1')  and (pending_closed='0') and branch_id=".$_SESSION['BranchId'] ." order by id desc");
    //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
    $rs = mysql_query("SELECT *,DATE_FORMAT(req_date,'%d %b %Y %h:%i %p') as req_date,DATE_FORMAT(time,'%d %b %Y %h:%i %p') as time FROM installation WHERE installation_status=3 and branch_id=".$_SESSION['BranchId'] ."  and request_by='".$_SESSION['username']."' order by id desc");
 

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr> 
            <th>Sl. No</th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Sales Person </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>ClientName </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>No.Of Vehicle </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Location</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Model</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>time </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Vehicle Type</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Contact No</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Contact Person</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Back Reason</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>View Detail</b></font></th>
            
            <th width="11%" align="center"><font color="#0E2C3C"><b>Edit</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Close</b></font></th>
            <!--<td width="11%" align="center"><font color="#0E2C3C"><b>Delete</b></font></td>-->
            
        </tr>
    </thead>
    <tbody>
 
 
 
<?php 
$excel_data.='<table cellpadding="5" cellspacing="5" border="1"><thead><tr><td colspan="12" align="center"><strong>Back Installation</strong></td></tr><tr><td colspan="12"></td></tr><tr><th width="5%">Sl. No</th><th width="10%">Sales Person</th><th width="10%">ClientName </th><th width="10%">No.Of Vehicle </th><th width="10%">Location</th><th width="10%">Device Model </th><th width="10%">Available Time</th><th width="10%">Vehicle Type</th><th width="10%">Contact No</th><th width="10%">Contact Person</th><th width="10%">Back Reason</th></tr></thead><tbody>';

 
  $i=1;
while($row=mysql_fetch_array($rs))
{
?>
<tr align="center" <? if( $row["installation_status"]=="3"){ echo 'style="background-color:#CC6"';} ?> >
       
        <td><?php echo $i;?></td>
        <td width="11%" align="center">&nbsp;
        <?php $sales=mysql_fetch_array(mysql_query("select name from sales_person where id='$row[sales_person]' ")); echo $sales['name'];?>
        </td>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row["user_id"];
        $rowuser=select_query($sql);
        ?>
         <td><? echo $rowuser[0]["sys_username"];?></td> 
        <td width="11%" align="center">&nbsp;<?php echo $row['no_of_vehicals'];?></td>
        
        
        <?php if($row['location']!="")
                { 
                    $location = $row['location'];
                }
                else{ 
                    $city= mysql_fetch_array(mysql_query("select * from tbl_city_name where branch_id='".$row['inter_branch']."'"));
                    $location = $city['city'];
                }?>
        
        <td align="center">&nbsp;<?php echo $location;?></td>
        
        <td width="10%" align="center">&nbsp;<?php echo $row['model'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['time'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['veh_type'];?></td>
        
        <td width="12%" align="center">&nbsp;<?php echo $row['contact_number'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['contact_person'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['back_reason'];?></td>
        
        <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'installation','popup1'); " class="topopup">View Detail</a></td>
        <!--<td width="10%" align="center">&nbsp;<a href="update_installation.php?id=<?=$row['id'];?>&pending=1&action=editp">Edit</a></td>
        <td width="10%" align="center">&nbsp;<a href="?id=<?=$row['id'];?>&pending=1&action=close&pg=<? echo $pg;?>">Close</a></td>-->
        
        <td width="10%" align="center">&nbsp;<a href="update_back_installation.php?id=<?=$row['id'];?>&action=edit">Edit</a></td>
        <td width="10%" align="center">&nbsp;<a href="?id=<?=$row['id'];?>&req_id=<?=$row['inst_req_id'];?>&action=close&pg=<? echo $pg;?>">Close</a></td>
                
  </tr> 
<?php   
    $excel_data.="<tr><td width='5%'>".$i."</td><td width='10%'>".$sales['name']."</td><td width='10%'>".$rowuser[0]["sys_username"]."</td><td width='10%'>".$row['no_of_vehicals']."</td><td width='10%'>".$location."</td><td width='10%'>".$row['model']."</td><td width='10%'>".$row['time']."</td><td width='10%'>".$row['veh_type']."</td><td width='10%'>".$row['contact_number']."</td><td width='10%'>".$row['contact_person']."</td><td width='10%'>".$row['back_reason']."</td></tr>";
    
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

unlink(__DOCUMENT_ROOT.'/servicecalling/excel/'.$_SESSION["username"].'-back_installation.xls');
$filepointer=fopen(__DOCUMENT_ROOT.'/servicecalling/excel/'.$_SESSION["username"].'-back_installation.xls','w');
fwrite($filepointer,$excel_data);
fclose($filepointer);

include("../include/footer.inc.php");

?>


 





<?php
/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/  
?> 



 
 <!--<div class="top-bar">
                
                    <h1>Back installation</h1>
                      
                </div>
                        
                
                <div class="table">-->
<?php


/*$id=$_GET['id'];
if($_GET['action']=="close")
{
$query1="UPDATE installation SET pending_closed='1'  WHERE id='$id'";
mysql_query($query1);
}


    $rs = mysql_query("SELECT * FROM installation WHERE (pending='1' or newpending='1')  and (pending_closed='0') and branch_id=".$_SESSION['BranchId'] ." order by id desc");    
*/
?>


 <!--<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
        
      <th width="11%" align="center"><font color="#0E2C3C"><b>Sales Person </b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>ClientName </b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>No.Of Vehicle </b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Location</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Model</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>time </b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Vehicle Type</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Immobilzer</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>DIMTS </b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Demo </b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Amount </b></font></th>
        
        <th width="11%" align="center"><font color="#0E2C3C"><b>Contact No</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Contact Person</b></font></th>
        <th width="11%" align="center"><font color="#0E2C3C"><b>Back Reason</b></font></th>
        <td width="11%" align="center"><font color="#0E2C3C"><b>Edit</b></font></td>
        <td width="11%" align="center"><font color="#0E2C3C"><b>Close</b></font></td>-->
        <!--<td width="11%" align="center"><font color="#0E2C3C"><b>Delete</b></font></td>-->
        
 
        <!--</tr>
    </thead>
    <tbody>-->


 
<?php  
    //while ($row = mysql_fetch_array($rs)) {
    
    ?>  
    <!--<tr align="Center">
        <td width="11%" align="center">&nbsp;<?php $sales=mysql_fetch_array(mysql_query("select name from sales_person where id='$row[sales_person]' ")); echo $sales['name'];?></td>
        <td width="11%" align="center">&nbsp;<?php echo $row['client'];?></td>
        <td width="11%" align="center">&nbsp;<?php echo $row['no_of_vehicals'];?></td>
        
        
        <td width="10%" align="center">&nbsp;<?php echo $row['location'];?></td>
        <td width="10%" align="center">&nbsp;<?php echo $row['model'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['time'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['veh_type'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['immoblizer_type'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['dimts'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['demo'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['payment_req'];?></td>
        
        <td width="12%" align="center">&nbsp;<?php echo $row['contact_number'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['contact_person'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $row['back_reason'];?></td>
        <td width="10%" align="center">&nbsp;<a href="add_installation.php?id=<?=$row['id'];?>&pending=1&action=editp">Edit</a></td>
                <td width="10%" align="center">&nbsp;<a href="?id=<?=$row['id'];?>&pending=1&action=close&pg=<? echo $pg;?>">Close</a></td>-->

        <!--<td width="12%" align="center">&nbsp;<a href="services.php?id=<?php echo $row['id'];?>">Delete</a></td>-->
        
    <!--</tr>-->
        <?php  
   // }
     
    ?>
<!--</table>
     
   <div id="toPopup"> 
        
        <div class="close">close</div>
           <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
        <div id="popup1" style ="height:100%;width:100%">--> <!--your content start-->
            

 
        <!--</div> --><!--your content end-->
    
    <!--</div>--> <!--toPopup end-->
    
    <!--<div class="loader"></div>
       <div id="backgroundPopup"></div>
</div>-->
 
 
 
<?
//include("../include/footer.inc.php");

?>