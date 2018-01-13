<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */

$id=$_GET['id'];
?> 
<script type="text/javascript">
/*function ConfirmReactivate(row_id)
{
   var retVal = prompt("Write Comment : ", "Comment");
  if (retVal)
  {
  addreasonComment(row_id,retVal);
      return ture;
  }
  else
    return false;
}

function addreasonComment(row_id,retVal)
{
    //alert(user_id);
    //return false;
$.ajax({
        type:"GET",
        url:"userInfo.php?action=InstallationClosed",
          
         data:"row_id="+row_id+"&comment="+retVal,
        success:function(msg){
            // alert(msg);
         
        location.reload(true);        
        }
    });
}*/
</script>

 <div class="top-bar">
   
    <h1>Running Installation</h1>
      
</div>
<div class="top-bar">
                    
<div style="float:right";><font style="color:#ADFF2F;font-weight:bold;">GreenYellow:</font> Urgent Installation</div>
<br/>
<div style="float:right";><font style="color:#EDA4FF;font-weight:bold;">LightBlue:</font> InterBranch Installation</div>             
<br/>
</div> 

<div style="float:right";><a href=<?="excel/".$_SESSION['username']."-running_installation.xls"?>>Create Excel</a><br/></div>

<div class="table">
<?php
 
    
    //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose,7-New request,8-show admin,9-Again Confirmation,11-forwardtorepair,15-pending installation
    $rs = mysql_query("SELECT * FROM installation where installation_status IN ('1','2','11') AND (inter_branch='".$_SESSION['BranchId']."' OR branch_id='".$_SESSION['BranchId']."') and request_by='".$_SESSION['username']."' order by id desc");
 

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr> 
            <th>Sl. No</th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Sales Person </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>ClientName </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>No.Of Vehicle </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Location</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Device Model</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Available Time </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Vehicle Type </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Contact No</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Contact Person</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Installer Name</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>View Detail</b></font></th>
            <!--<th width="11%" align="center"><font color="#0E2C3C"><b>Edit</b></font></th>-->
            
        </tr>
    </thead>
    <tbody>
 
 
 
<?php 

$excel_data.='<table cellpadding="5" cellspacing="5" border="1"><thead><tr><td colspan="12" align="center"><strong>Running Installation</strong></td></tr><tr><td colspan="12"></td></tr><tr><th width="5%">Sl. No</th><th width="10%">Sales Person</th><th width="10%">ClientName </th><th width="10%">No.Of Vehicle </th><th width="10%">Location</th><th width="10%">Device Model </th><th width="10%">Available Time</th><th width="10%">Vehicle Type</th><th width="10%">Contact No</th><th width="10%">Contact Person</th><th width="10%">Installer Name</th></tr></thead><tbody>';

 
  $i=1;
while($row=mysql_fetch_array($rs))
{
?>
<tr align="center" <? if($row['inter_branch']!=0) {?> style="background:#EDA4FF;" <? }else if($row['required']=='urgent'){ ?>style="background:#ADFF2F" <? }?>>
        
        <td><?php echo $i;?></td>
        <td width="11%" align="center">&nbsp;
        <?php $sales=mysql_fetch_array(mysql_query("select name from sales_person where id='$row[sales_person]' ")); echo $sales['name'];?></td>
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
        <td width="12%" align="center">&nbsp;<?php echo $row['inst_name'];?></td>
        
        <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'installation','popup1'); " class="topopup">View Detail</a>
        <!--| <a href="#" onclick="return ConfirmReactivate(<?php echo $row["id"];?>);" >Closed</a>-->
        </td>
        
        <!--<td width="10%" align="center">&nbsp;<a href="update_installation.php?id=<?=$row['id'];?>&pending=1&action=edit">Edit</a></td>-->
                
    </tr> 
<?php   
    $excel_data.="<tr><td width='5%'>".$i."</td><td width='10%'>".$sales['name']."</td><td width='10%'>".$rowuser[0]["sys_username"]."</td><td width='10%'>".$row['no_of_vehicals']."</td><td width='10%'>".$location."</td><td width='10%'>".$row['model']."</td><td width='10%'>".$row['time']."</td><td width='10%'>".$row['veh_type']."</td><td width='10%'>".$row['contact_number']."</td><td width='10%'>".$row['contact_person']."</td><td width='10%'>".$row['inst_name']."</td></tr>";
        
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
unlink(__DOCUMENT_ROOT.'/servicecalling/excel/'.$_SESSION["username"].'-running_installation.xls');
$filepointer=fopen(__DOCUMENT_ROOT.'/servicecalling/excel/'.$_SESSION["username"].'-running_installation.xls','w');
fwrite($filepointer,$excel_data);
fclose($filepointer);

include("../include/footer.inc.php");

?>