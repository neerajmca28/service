<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */

 
$action=$_GET['action'];
$id=$_GET['id'];
$page=$_POST['page'];
if($action=='edit' or $action=='editp')
	{
		$result=mysql_fetch_array(mysql_query("select * from users where id=$id"));	
	}
?> 

<div class="top-bar">
<h1>Client Assign</h1>
</div>
<div class="table"> 
<?php


if(isset($_POST["Update"]))
{ 
 
$tel_person=$_POST["tel_person"];
$from_date=$_POST["from_date"];
$to_date=$_POST["to_date"];

	 $query="update users set temp_telecaller='".$tel_person."',temp_from_date='".$from_date."',temp_to_date='".$to_date."' where id=$id";
	 mysql_query($query);
	
	echo "<script>document.location.href ='list_company_clients.php'</script>";

}

?>


 
    
	
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	  <script>
$(function() {
$( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });

$( "#datepickercheque" ).datepicker({ dateFormat: "yy-mm-dd" });

});

function validateForm()
{
 if(document.myForm.tel_person_id.value=="")
  {
  alert("Please Select Assign Person name") ;
  document.myForm.tel_person_id.focus();
  return false;
  }  
   
  if(document.myForm.from_date.value=="")
  {
  alert("please Enter From Date.") ;
  document.myForm.from_date.focus();
  return false;
  }
  
  if(document.myForm.to_date.value=="")
  {
  alert("please Enter To Date") ;
  document.myForm.to_date.focus();
  return false;
  }
  
} 
			
	

    </script>
 <form name="myForm" action="" onsubmit="return validateForm()" method="post">
 

   <table style=" padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">

        <tr>
            <td>
                 Client 
            </td>
            <td> 
             
                <select name="main_user_id" id="TxtMainUserId" >
                <option value=""  >-- Select One --</option>
                <?php
                $main_user_id=mysql_query("SELECT id as user_id, sys_username as name FROM users order by name asc");
                while ($data=mysql_fetch_assoc($main_user_id))
                {
                ?>
                
                 <option name="main_user_id" value="<?=$data['user_id']?>" <? if($result['id']==$data['user_id']) {?> selected="selected" <? } ?> >
                <?php echo $data['name']; ?>
                </option>
                <?php 
                } 
                
                ?>
                </select>
            
            </td>
        </tr>
        <tr>
            <td>Company Name</td>
            <td><input type="text" name="company" id="TxtCompany" value="<?=$result['company']?>" readonly /></td>
        </tr>
        <tr>
        	<td>Assign To</td>
            <td><select name="tel_person" id="tel_person_id">
            		<option value=""  >-- Select One --</option>
                     <?php
						$tel_call=mysql_query("SELECT login_name,name FROM telecaller_users where status=1 order by name asc");
						while ($tel_call_data=mysql_fetch_assoc($tel_call))
						{
					?>
					<option name="tel_call_id" value="<?=$tel_call_data['login_name']?>" >
					<?php echo $tel_call_data['name']; ?>
                    </option>
                    <?php 
                    } 
                    
                    ?>
                 </select>   
            </td>
        </tr>
        <tr>
        	<td width="176"> <label  id="lbDlDate">From Date</label></td>
        	<td width="287"> <input type="text" name="from_date" id="datepicker" value=""/></td>
        </tr>
        <tr>
        	<td width="176"> <label  id="lbDlDate">To Date</label></td>
        	<td width="287"> <input type="text" name="to_date" id="datepickercheque" value=""/></td>
        </tr>

   		 <tr>
         	<td colspan="2" align="center"> <input type="submit" name="Update" value="Update"  />		
	   			<input type="button" name="Cancel" value="Cancel" onClick="window.location = 'list_company_clients.php' " />
            </td>
        </tr>
</table>
     
	  </form>
   </div>
 
<?php
include("../include/footer.php"); ?>
