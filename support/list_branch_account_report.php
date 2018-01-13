<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/  
 
 $user = $_SESSION["username"];
 
if(isset($_POST['submit']))
{
	$date_from = $_POST['FromDate']." 00:00";
	$date_to = $_POST['ToDate']." 23:59";
					
	$query = mysql_query("SELECT * FROM branch_account_report where request_by='".$user."' and request_date>='".$date_from."' and request_date<='".$date_to."' order by id DESC ");
	
	$excel_count = mysql_num_rows($query);
}
else{
 $query = mysql_query("SELECT * FROM branch_account_report where request_by='".$user."' order by id DESC ");
} 
?> 

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
var j = jQuery.noConflict();
j(function() 
{
j( "#FromDate" ).datepicker({ dateFormat: "yy-mm-dd" });

j( "#ToDate" ).datepicker({ dateFormat: "yy-mm-dd" });

});

</script>

<div class="top-bar">
	 <a href="payment_report.php" class="button">ADD NEW </a>
    <h1>Payment Report</h1>
    
</div>

<div class="top-bar">

<form name="myForm" action=""   method="post">

    <table cellspacing="5" cellpadding="5">
        <tr>
            <td >From Date</td>
            <td><input type="text" name="FromDate" id="FromDate" value="<?php echo $_POST["FromDate"];?>"/></td>
            
            <td>To Date</td>
            <td><input type="text" name="ToDate" id="ToDate"  value="<?php echo $_POST["ToDate"];?>" /></td>
            
            <td align="center"> <input type="submit" name="submit" value="submit"  /></td>
        </tr>
     
    </table>
</form>

</div>
          
<div class="top-bar">
                          
        <div class="table">

 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>SL No</th>
            <th>Date</th>
            <th>Client Name</th>
            <th>Expected Ammount</th>
            <th>Received Ammount</th>
            <th>Update Time</th>
            <th>Edit</th> 
        </tr>
    </thead>
    <tbody>
 
 
 
<?php 
$i=1;
while($row=mysql_fetch_array($query))
{
?>
    <tr align="center">
        <td><?php echo $i ?></td>
        <td><?php echo $row["request_date"];?></td>
        <td><?php echo $row["clients"];?></td>
        <td><?php echo $row["expected_ammount"];?></td>
        <td><?php echo $row["received_ammount"];?></td> 
        <td><?php echo $row["update_date"];?></td> 
        <td><a href="branch_account_report.php?id=<?=$row['id'];?>&action=edit<? echo $pg;?>">Edit</a></td> 
    </tr> <?php $i++; }?>
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


