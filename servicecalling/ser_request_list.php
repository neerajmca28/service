<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */ 


?> 

    <div class="top-bar">
    <a href="crack_device_service.php" class="button">Crack Request </a>
    <h1>Service List</h1>
    </div>

    <div class="top-bar">
    
    <div style="float:right";><font style="color:#68C5CA;font-weight:bold;">Blue:</font> Urgent Service</div>
    <br/>
    <div style="float:right";><font style="color:#CCCCCC;font-weight:bold;">Grey:</font> Closed Service</div>              
    <br/>
    <div style="float:right";><font style="color:#FFC184;font-weight:bold;">Light Orange:</font> Back From Repair</div> 
    </div>
    <div style="float:right";><a href=<?="excel/".$_SESSION['username']."-service_list.xls"?>>Create Excel</a><br/></div>

<div class="table">
<?php
 $fromdateof_service="";
$todaydate = date("Y-m-d  H:i:s");
$newdate = strtotime ( '-15 day' , strtotime ( $todaydate ) ) ;
$fromdateof_service = date ( 'Y-m-j H:i:s' , $newdate );
$mode=$_GET['mode'];

if($mode=='') { $mode="new"; }
    
  if($mode=='close')
    {
      
     $rs = mysql_query("SELECT *,DATE_FORMAT(req_date,'%d %b %Y %h:%i %p') as req_date,DATE_FORMAT(atime,'%d %b %Y %h:%i %p') as atime FROM services_crack where (service_status='1' or service_status='3') and req_date>'".$fromdateof_service."' and branch_id=".$_SESSION['BranchId'] ."  and request_by='".$_SESSION['username']."' order by id desc  limit 500",$dblink2);
    }
    else if($mode=='new')
    {
         
      
        $rs = mysql_query("SELECT *,DATE_FORMAT(req_date,'%d %b %Y %h:%i %p') as req_date,DATE_FORMAT(atime,'%d %b %Y %h:%i %p') as atime FROM services_crack where (service_status='1'  or  service_status='3') and  branch_id=".$_SESSION['BranchId'] ."  and request_by='".$_SESSION['username']."' order by id desc  limit 500",$dblink2);
    } 
   // $tt=mysql_fetch_array($rs);
  // echo '<pre>'; print_r($tt); die;   
 


?>
<div style="float:right"><a href="services.php?mode=new">New</a> | <a href="services.php?mode=close">Closed</a></div>

 


 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
        
          <!--   <th>Sl. No</th> -->
            <th>Request By </th>
            <th>Request Date </th>
            <th>ClientName</th> 
            <th>Branch Location  </th>
            <th>LandMark</th>
            <th>Vehicle No</th>
             <th>Device IMEI</th>
             <th>Device Type</th>
            <th>Available Time</th>
            <th>Contact</th> 
           <!--  <th>Installer name</th> -->
           <th>Current Status</th>
            <th>View Detail</th>
       <? if($mode=='close')
       {?>
        <th>Closed</th>
        <?}
        else
        {?>
        
        <th>Edit</th>
        <?}?>
        </tr>
    </thead>
    <tbody>


 
<?php  

$excel_data.='<table cellpadding="5" cellspacing="5" border="1"><thead><tr><td colspan="12" align="center"><strong>Service List</strong></td></tr><tr><td colspan="12"></td></tr><tr><th width="5%">Sl. No</th><th width="10%">Request By</th><th width="10%">Request Date </th><th width="10%">ClientName</th><th width="10%">Vehicle No</th><th width="10%">Notworking </th><th width="10%">Location</th><th width="10%">Available Time</th><th width="10%">Person Name</th><th width="10%">Contact No</th><th width="10%">Installer name</th></tr></thead><tbody>';

    $i=1;
    while ($row = mysql_fetch_array($rs)) {
     
    ?>  
        <tr   <? if($row['service_status']==3) {  ?> style="background:#FFC184;" <? }
        else  if($row['required']=='urgent'){ ?>style="background:#68C5CA" <? }

          ?> >
      <!--   <td><?php echo $i;?></td> -->
        <td>&nbsp;<?php echo $row['request_by'];?></td>
        <td>&nbsp;<?php echo $row['req_date'];?></td>
        <td>&nbsp;<?php echo $row['name'];?></td>
        <?php
        if($row['inter_branch']="1")
        {
            $city_code=1;
        }
        else
        {
            $city_code=$row['inter_branch'];
        }

            $city= select_query("select * from internalsoftware.tbl_city_name where branch_id='".$city_code."'");
             $sql2 = select_query("SELECT name FROM re_city_spr_1 WHERE id='".$row['Zone_area']."'");

         // echo $sql2[0]['name'];

          ?>
                  <td >&nbsp;<?php echo $sql2[0]['name']." ".$city[0]['city'];?></td>
      <!--   <td >&nbsp;<?php echo $sql2[0]['name'];?></td> -->


          <td >&nbsp;<?php echo $row['location'];?></td>
        <td>&nbsp;<?php echo $row['veh_reg'];?></td>
        <td>&nbsp;<?php echo $row['device_imei'];?></td>
        <td> 
        <?php
               $sqlDevice=select_query("SELECT item_name FROM item_master where item_id='".$row["device_type"]."'");

                     $sqlModel=select_query("select im.* from installation_request ir left join item_master im on ir.model=im.item_id where ir.model='".$row["device_model"]."'");
                     echo $sqlModel[0]['item_name']."</br>";
                     echo $sqlDevice[0]['item_name'];
                     ?>
        </td>
   
        
        <td>&nbsp;<?php echo $row['atime'];?></td>
            <td>
                <?php echo $row['pname']."<br>";
                echo $row['cnumber']."<br>";
                echo $row['designation']."<br>";
                ?>
        </td>
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
  <!--        <td>&nbsp;<?php echo $row['inst_name'];?></td> -->
        <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'services_crack','popup1'); " class="topopup">View Detail</a>
        </td>

    <? if($mode=='close')
       {?>
       <td >&nbsp; Closed</td>
        <? 
       }
        else
        { 
            if($row['service_status']==3)
            {

            
              
          ?>
              <td >&nbsp;  <a href="update_crack_device_service.php?rowid=<?=$row['id'];?>&edit=true">Edit</a></td>
          <? 
            }
            else
            {
              ?>
              <td>Edit</td>
            <?
             }
         } ?>
</tr> 
    <?php
        $excel_data.="<tr><td width='5%'>".$i."</td><td width='10%'>".$row['request_by']."</td><td width='10%'>".$row['req_date']."</td><td width='10%'>".$row['name']."</td><td width='10%'>".$row['veh_reg']."</td><td width='10%'>".$row['Notwoking']."</td><td width='10%'>".$location."</td><td width='10%'>".$row['atime']."</td><td width='10%'>".$row['pname']."</td><td width='10%'>".$row['cnumber']."</td><td width='10%'>".$row['inst_name']."</td></tr>"; 
        
        $i++; 
        }
        
        $excel_data.='</tbody></table>';
    ?>
</table>
     
   <div id="toPopup"> 
        
        <div class="close">close</div>
           <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
        <div id="popup1" style ="height:100%;width:100%"> <!--your content start-->
            

 
        </div> <!--your content end-->
    
    </div> <!--toPopup end-->
    
    <div class="loader"></div>
       <div id="backgroundPopup"></div>
</div>
 
 
 
<?
unlink(__DOCUMENT_ROOT.'/servicecalling/excel/'.$_SESSION["username"].'-service_list.xls');
$filepointer=fopen(__DOCUMENT_ROOT.'/servicecalling/excel/'.$_SESSION["username"].'-service_list.xls','w');
fwrite($filepointer,$excel_data);
fclose($filepointer);

include("../include/footer.inc.php");

?>