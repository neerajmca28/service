<?php 
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");  
include($_SERVER['DOCUMENT_ROOT']."/service/sqlconnection.php");*/

?> 

<div class="top-bar">
     
    <h1>Manufacture Replace Device</h1>
      
</div>
        

<div class="table">
<?  
     
    /*$result = mssql_query("select * from device_replace_on_repair");*/
    $result = select_query_inventory("select * from inventory.device_replace_on_repair");
     
?>
    
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>Sl. No</th>
            <th>Client Name</th>
            <th>Vehicle No</th>
            <th>Device Id </th>
            <th>Device IMEI </th>            
            <th>Replace IMEI</th>
            <th>Replace date</th>
            <th>Old ITGC ID</th>
            <th>New ITGC ID</th>
 
        </tr>
    </thead>
    <tbody>

  
    <?php 
    for($i=0;$i<count($result);$i++)
   { 
    ?>  
     <tr align="Center">
            <td>&nbsp;<?php echo $i+1; ?></td>
            <td>&nbsp;<?php echo $result[$i]["old_client_name"];?></td>
            <td>&nbsp;<?php echo $result[$i]["old_veh_no"]?></td>
            <td>&nbsp;<?php echo $result[$i]["device_id"];?></td>
            <td>&nbsp;<?php echo $result[$i]["old_device_imei"]?></td>
            <td>&nbsp;<?php echo $result[$i]["new_device_imei"]?> </td>
            <td>&nbsp;<?php echo $result[$i]["replaced_date"]?></td>
            <td>&nbsp;<?php echo $result[$i]["old_itgc_id"]?></td>
            <td>&nbsp;<?php echo $result[$i]["new_itgc_id"]?></td> 
             
         
        </tr>
    <?php  
    
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