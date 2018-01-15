<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */

?> 

<script>
function backComment(row_id)
{
   var retVal = prompt("Write Comment : ", "Comment");
  if (retVal)
  {
  addComment(row_id,retVal);
      return ture;
  }
  else
    return false;
}

function addComment(row_id,retVal)
{
    //alert(user_id);
    //return false;
$.ajax({
        type:"GET",
        url:"userInfo.php?action=InstallationbackComment",
          
         data:"row_id="+row_id+"&comment="+retVal,
        success:function(msg){
              //alert(msg);
         
        location.reload(true);        
        }
    });
}

function doneConfirm(row_id)
{
  var x = confirm("Are you sure Client Confirm this installation?");
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
    
$.ajax({
        type:"GET",
        url:"userInfo.php?action=InstallationConfirm",
         data:"row_id="+row_id,
        success:function(msg){
        location.reload(true);        
        }
    });
}

</script>
 <div class="top-bar">
      
        <h1>Installation Request</h1>
          
    </div>
    <div class="top-bar">
    <a style="float:right" href="online_crack.php" > Online Crack </a><span style="float:right">||</span><a style="float:right" href="re_addition.php" >Re-Addition </a><span style="float:right">||</span><a style="float:right" href="add_installation.php" >Installation </a><br/>
    <div style="float:right";><font style="color:#ADFF2F;font-weight:bold;">GreenYellow:</font> Urgent Installation</div>
    <br/>
    <div style="float:right";><font style="color:#F2F5A9;font-weight:bold;">Yellow:</font> Back from Admin</div>
    <br/>
    <div style="float:right";><font style="color:#99FF66;font-weight:bold;">Green:</font> Closed Installation</div>              
    <br/>
    <div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font> Approved</div>
    <br/>
    <div style="float:right";><font style="color:#EDA4FF;font-weight:bold;">LightBlue:</font> InterBranch Installation</div>
    </div> 
    <div style="float:right";><a href=<?="excel/".$_SESSION['username']."-installation_request.xls"?>>Create Excel</a><br/></div>
    <div class="table">
<? 
$fromdateof_service="";
$todaydate = date("Y-m-d  H:i:s");
$newdate = strtotime ( '-5 day' , strtotime ( $todaydate ) ) ;
$fromdateof_service = date ( 'Y-m-j H:i:s' , $newdate );

$mode=$_GET['mode'];
if($mode=='') { $mode="new"; }
    
  if($mode=='close')
    {
    //$rs = mysql_query("SELECT * FROM installation where reason!='' or rtime!='' and branch_id=".$_SESSION['BranchId']."  order by id desc");
     
    $rs = mysql_query("SELECT *,DATE_FORMAT(req_date,'%d %b %Y %h:%i %p') as req_date,DATE_FORMAT(time,'%d %b %Y %h:%i %p') as time FROM installation where (installation_status='5' or installation_status='6') and time>'".$fromdateof_service."' and branch_id=".$_SESSION['BranchId']." and request_by='".$_SESSION['username']."' order by id desc");
    }
    else if($mode=='new')
    {
     
     $rs = mysql_query("SELECT *,DATE_FORMAT(req_date,'%d %b %Y %h:%i %p') as req_date,DATE_FORMAT(time,'%d %b %Y %h:%i %p') as time FROM installation_request where  (installation_status ='1' or installation_status='2' or installation_status='4' or installation_status='7' or installation_status='8' or installation_status='9')   and branch_id=".$_SESSION['BranchId']."  and request_by='".$_SESSION['username']."'  order by id desc");
    }    

    ?>
    
    <div style="float:right"><a href="installation.php?mode=new">New</a> | <a href="installation.php?mode=close">Closed</a></div>
<?php
 

    
 

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr> <th>Sl. No</th>
        <th>Request By </th>
         <th>Request Date </th>
        <th><font color="#0E2C3C"><b>Sales Person</b></font></th>
        <th><font color="#0E2C3C"><b>Client</b></font></th>
        <th><font color="#0E2C3C"><b>No. Of Vehicle</b></font></th>
        <th><font color="#0E2C3C"><b>Location</b></font></th>
        <th><font color="#0E2C3C"><b>Device Model</b></font></th>
        <th><font color="#0E2C3C"><b>Available Time</b></font></th> 
         <th><font color="#0E2C3C"><b>Installer Name</b></font></th> 

        <th>View Detail</th>
         <? if($mode=='close')
       {?>
        <th  ><font color="#0E2C3C"><b>Closed</b></font></th>
        <?}
        else
        {?>
        <th><font color="#0E2C3C"><b>Edit</b></font></th> 
        <?}?>
        
        </tr>
    </thead>
    <tbody>
 
 
 
<?php 
$excel_data.='<table cellpadding="5" cellspacing="5" border="1"><thead><tr><td colspan="12" align="center"><strong>Installation Request</strong></td></tr><tr><td colspan="12"></td></tr><tr><th width="5%">Sl. No</th><th width="10%">Request By</th><th width="10%">Request Date </th><th width="10%">Sales Person</th><th width="10%">Client</th><th width="10%">No. Of Vehicle </th><th width="10%">Location</th><th width="10%">Device Model</th><th width="10%">Available Time</th><th width="10%">Installer name</th></tr></thead><tbody>';

 
   $i=1;
while($row=mysql_fetch_array($rs))
{
    $sales=mysql_fetch_array(mysql_query("select name from sales_person where id='$row[sales_person]' "));
         
?>
<!--<tr  <? if( $row["support_comment"]!="" && $row["final_status"]!=1 ){ echo 'style="background-color:#FF3333"';} elseif($row["final_status"]==1){ echo 'style="background-color:#99FF66"';}elseif($row["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';}?> >-->


<tr <?  if($row["approve_status"]==1 && $row["installation_status"]==9){ echo 'style="background-color:#B6B6B4"';}elseif($row['installation_status']==5 or $row['installation_status']==6 )  {  ?> style="background:#99FF66;" <? }elseif( $row["admin_comment"]!="" && ($row["sales_comment"]=="" || $row["installation_status"]==7)){ echo 'style="background-color:#F2F5A9"';}elseif($row['required']=='urgent'){ ?>style="background:#ADFF2F" <? }elseif($row['inter_branch']!=0){ ?>style="background:#EDA4FF" <? }?>>

        <td align="center"><?php echo $i;?></td> 
        <td align="center">&nbsp;<?php echo $row['request_by'];?></td>
        <td>&nbsp;<?php echo $row['req_date'];?></td>
        <td align="center">&nbsp;<?php echo $sales['name'];?></td>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row["user_id"];
        $rowuser=select_query($sql);
        ?>
        <td align="center"><? echo $rowuser[0]["sys_username"];?></td> 
        
        <td align="center">&nbsp;<?php echo $row['no_of_vehicals'];?></td>
        
        <?php if($row['location']!="")
                { 
                    $location = $row['location'];
                }
                else{ 
                    $city= mysql_fetch_array(mysql_query("select * from tbl_city_name where branch_id='".$row['inter_branch']."'"));
                    $location = $city['city'];
                }?>
        
        <td align="center">&nbsp;<?php echo $location;?></td>
        
        <td align="center">&nbsp;<?php echo $row['model'];?></td>
        <td align="center">&nbsp;<?php echo $row['time'];?></td> 

         <td align="center">&nbsp;<?php echo $row['inst_name'];?></td> 
            <td align="center">
              <? if($mode=='close') {?>
                 <a href="#" onclick="Show_record(<?php echo $row["id"];?>,'installation','popup1'); " class="topopup">View Detail</a>
               <? } else {?>
              <a href="#" onclick="Show_record(<?php echo $row["id"];?>,'installation_request','popup1'); " class="topopup">View Detail</a>
               <? } ?>
                <?php if($row["admin_comment"]!="" && ($row["sales_comment"]=="" || $row["installation_status"]==7)){?>
                | <a href="#" onclick="return backComment(<?php echo $row["id"];?>);"  >Back Comment</a>
                <?php } 
                 if($row["installation_status"]==9 && $row["approve_status"]==1){?>
                | <a href="#" onclick="return doneConfirm(<?php echo $row["id"];?>);"  >Confirmation Done</a>
                <?php } ?>
            </td>

 <? if($mode=='close')
       {?>
        <td >Closed</td>
        <? }
        else
        {?>
        <td >&nbsp;
        <?php //if($row["approve_status"]!=1){?>
           <!-- <a href="add_installation.php?id=<?=$row['id'];?>&action=edit">Edit</a>-->
         <?php if($row["installation_status"]!= 2 ) {?>
             <a href="update_installation.php?id=<?=$row['id'];?>&action=edit">Edit</a>
          <?php }?>
         </td>
        <?}?>
        
        <!--<td >&nbsp;<a href="installation.php?id=<?php echo $row['id'];?>&action=delete">Delete</a></td>-->
        
</tr> 
    <?php 
    $excel_data.="<tr><td width='5%'>".$i."</td><td width='10%'>".$row['request_by']."</td><td width='10%'>".$row['req_date']."</td><td width='10%'>".$sales['name']."</td><td width='10%'>".$rowuser[0]["sys_username"]."</td><td width='10%'>".$row['no_of_vehicals']."</td><td width='10%'>".$location."</td><td width='10%'>".$row['model']."</td><td width='10%'>".$row['time']."</td><td width='10%'>".$row['inst_name']."</td></tr>"; 
    
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
unlink(__DOCUMENT_ROOT.'/servicecalling/excel/'.$_SESSION["username"].'-installation_request.xls');
$filepointer=fopen(__DOCUMENT_ROOT.'/servicecalling/excel/'.$_SESSION["username"].'-installation_request.xls','w');
fwrite($filepointer,$excel_data);
fclose($filepointer);

include("../include/footer.inc.php");

?>