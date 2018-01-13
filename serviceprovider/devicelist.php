<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
//include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

/* include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");  
include($_SERVER['DOCUMENT_ROOT']."/service/sqlconnection.php");*/

?> 


<div class="top-bar">
     
    <h1>Device List</h1>
      
</div>
        

<div class="table">
<?  
     
    $result = select_query_inventory("select device.device_id, device.device_imei, device.itgc_id, sim.sim_no, device.dispatch_date, installerid 
                from inventory.ChallanDetail left join inventory.device on ChallanDetail.deviceid=device.device_id left join inventory.sim on 
                device.sim_id=sim.sim_id where  device.device_status=64 and ChallanDetail.branchid=".$_SESSION['BranchId']." and 
                ChallanDetail.CurrentRecord=1");
     
     ?>
    
    <!--<div style="float:right"><a href="installations.php?mode=new">New</a> | <a href="installations.php?mode=close">Closed</a></div>-->
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <!--<th>Sl. No</th>-->
            <th>Device Id </th>
            <th>Device IMEI </th>
            <th  ><font color="#0E2C3C"><b>Itgc ID </b></font></th>
            <th  ><font color="#0E2C3C"><b>Mobile Num </b></font></th>
            <th  ><font color="#0E2C3C"><b>Dispatch Date</b></font></th>
            <th ><font color="#0E2C3C"><b>Installer Name</b></font></th>
 
        </tr>
    </thead>
    <tbody>

  
    <?php 
    //$i=1;
   for($i=0;$i<count($result);$i++)
   { 
    ?>  
     <tr align="Center">
        <!--<td><?php echo $i ?></td>-->
     
     
            
            <td>&nbsp;<?php echo $result[$i]["device_id"];?></td>
            <td>&nbsp;<?php echo $result[$i]["device_imei"]?></td>
            <td  align="center"><?php echo $result[$i]["itgc_id"]?> </td>
            <td  align="center">&nbsp;<?php echo $result[$i]["sim_no"]?></td>
            
            <td  align="center">&nbsp;<?php echo $result[$i]["dispatch_date"]?></td>
            <td   align="center">
            <? $sql="select * from installer  where inst_id=". $result[$i]["installerid"];
                $rowinst=select_query($sql);
            ?>
            
            &nbsp;<?php echo $rowinst[0]["inst_name"]?></td> 
             
         
        </tr>
        <?php  
    //$i++;
    
    }
     
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
include("../include/footer.inc.php");

?>