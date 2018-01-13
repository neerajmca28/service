<?php
ob_start();
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/  

$date=date("Y-m-d H:i:s");
//$successmsg="";
if(isset($_POST['submit'])) 
{
	if($_POST['name'][0]!="")
	{
		 $request_by = $_SESSION['username'];
		 $branch_id = $_SESSION['BranchId'];
		
		for ($i = 0; $i < count($_POST['name']); ++$i) {
				
			$client_name = $_POST['name'][$i];
			$date =  $_POST['date'][$i];
			$expected_ammount =  $_POST['expected_ammount'][$i];
					
			$query = "INSERT INTO branch_account_report(request_date,request_by,clients,expected_ammount,branch_id) 
			VALUES('".$date."','".$request_by."','".$client_name."','".$expected_ammount."','".$branch_id."')";
			
			mysql_query($query);
				  
		 }
		 echo "<script>document.location.href ='list_branch_account_report.php'</script>";
	}
	 
  }
  
?> 

<div class="top-bar">
 
<h1>Payment Report</h1>
</div>


<div style="float:left;padding-left:5px;padding-top:5px"> 
 

 <style>
.remove { display; }
</style>  
<link rel="stylesheet" href="/resources/themes/master.css" type="text/css" />
<link  href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css"  rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
<script src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.js" type="text/javascript"></script> 
<script src="/resources/scripts/mysamplecode.js" type="text/javascript"></script>
  <script type="text/javascript">
        $(document).ready(function () {
         
        $('#btnAdd').click(function () {
              var count = parseInt($('#HowManyRows').val()), first_row = $('#FirstRow');
                while(count-- > +1)                    first_row.clone().appendTo('#MainTable');
    });  
           $('body').on('focus',".datepicker_recurring_start", function(){
    $(this).datepicker({ dateFormat: "yy-mm-dd" });
});

    
$('.form-fields').on('click', '.remove', function(){
        $(this).closest('tr').remove();
    });
        });
    </script>
	
</head>
<body>
<form name="payment_form" method="post" action="">

<table width="500" border="0">
    <tr>
      
	 <th width="138" scope="col"><div align="left">No of Clients:- </div></th>
      <th width="158" scope="col"> <p align="left">
	  <input type="text" name="HowManyRows" id="HowManyRows" style="width:110px;" />
	  </p></th>
	  <th width="263"><div align="justify">
    <input type="button" id="btnAdd" value="Add Rows"/>
  </div></th>
    </tr>
  </table>
  
  
    <div class="form-fields">
<table id="MainTable" border="1" cellspacing="0" cellpadding="0" >
   
  <tr>
   
    <td valign="middle"><p align="center"><strong>Client Name.</strong> </p></td>
    <td valign="middle"><p align="center"><strong>Date</strong></p></td>
    <td  valign="middle"><p align="center"><strong>Expected Ammount</strong></p></td>
        
	<th width="82"></th>
  </tr>
  
   
 <tr id="FirstRow">    
    <td width="173" height="81" valign="middle">
    <p align="center">
    	<?php 
			$query="SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE sys_active=1 ORDER BY `name` ASC";
			$result=mysql_query($query);
		?>
    	<select name="name[]" id="name">
            <option value="">Select Name</option>
            <? while($row=mysql_fetch_array($result)) { ?>
            <option value=<?=$row['name']?>>
            <?=$row['name']?>
            </option>
            <? } ?>
          </select>
    </p>
        
    </td>
    <td width="178" valign="middle"><p align="center">
   <!-- <input type="text" name="date[]" class="datepicker_recurring_start" />-->
    <input type="text" name="date[]" value="<?php echo $date;?>"/>
     </p></td>
    <p></p>
    <td width="143" valign="middle"><p align="center">
    <input type="text" name="expected_ammount[]" id="expected_ammount" value="">
    </p></td>
        
	<td><p align="center"><input type="button" class="remove" value="remove" /></p></td>
  </tr>
      </table>
         
  </div>
      
  
<p>&nbsp;</p>
<table width="429" border="0.5">
  <tr>
  <td>
  </td><td>
    <div align="center">
  <input type="submit" name="submit" id="submit" value="submit" />
  <input type="button" name="Cancel" value="Cancel" onClick="window.location = 'list_branch_account_report.php' " />
 </div></td>
  </tr>
</table>
  
</form>
 
 </div>
 
<?
include("../include/footer.inc.php");

?>

    
