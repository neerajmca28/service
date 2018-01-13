<?php
include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc_tracking.php"); 
 

 include($_SERVER['DOCUMENT_ROOT'].'/inc/sessionstart.php');
include($_SERVER['DOCUMENT_ROOT'].'/inc/connect.php'); 
?> 


 
                   <div class="top-bar">
	<a href="add_journeyhour.php?ADD=true" class="button">ADD NEW </a>
	<h1>Trip List</h1>
	</div>     
                
                <div class="table">
                  <p>
                     
 

                  <p>&nbsp;                  </p>
                  <table cellpadding="0" cellspacing="0" border="1" class="display" id="example">
	<thead>
		<tr>
       <th>Sl. No</th>
        <th>Origin </th>
         <th>Destination </th>
      <th ><b>Journy Hour </b></font></th>
		 
		 
		<th ><b>Edit</b></font></th>
        
		<!--<td width="6%" align="center"><font color="#0E2C3C"><b>Delete</b></font></td>-->
		
 
		</tr>
	</thead>
	<tbody>

  
	<?php  
	$i=1;
	 
	 $rs = mysql_query("select * from rajvi_journeyhour");
 
    while ($row = mysql_fetch_array($rs)) {
	 
		
		?>
	<td >&nbsp;<?php echo $i;?></td> 
	 
		<td >&nbsp;<?php echo $row['from_poi'];?></td> 
		<td >&nbsp;<?php echo $row['to_poi'];?></td>
		<td >&nbsp;<?php echo $row['journey_hour'];?></td>

		
		  
		<td  >&nbsp;<a href= "add_journeyhour.php?id=<?=$row['id'];?>&edit=true">Edit</a></td> 
        
        
       
		<!--<td width="11%" align="center">&nbsp;<a href="services_from_sales.php?id=<?php echo $row['id'];?>">Delete</a></td>-->
		
	</tr>
		 
  <?php $i++; }?>
	 
   
</table>
     
   
    
	<div class="loader"></div>
   	<div id="backgroundPopup"></div>
</div>
 
 
 
<?
include("../include/footer.inc.php");

?>





  