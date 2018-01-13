<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/
 
$action=$_GET['action'];
$id=$_GET['id'];
$page=$_POST['page'];
if($action=='edit' or $action=='editp')
	{
		$result=mysql_fetch_array(mysql_query("select * from installer where inst_id=$id"));	
	}
?> 

<div class="top-bar">
<h1>Installer Form</h1>
</div>
<div class="table"> 
<?php


if(isset($_POST["submit"]))
{ 
$date=$_POST["date"];
$installerName=$_POST["installer_name"];
$address=$_POST["TxtAddress"];
$specialist=$_POST["Txtspecialist"];
$toolkit=$_POST["TxtToolkit"];
$work_status=$_POST["Txtworkstatus"];

$Installermobile=$_POST["Installermobile"]; 
$Branch=$_POST["Branch"];

 if($action=='edit')
	{
	
 $query="update installer set inst_name='".$installerName."',address='".$address."',specialist='".$specialist."',toolkit='".$toolkit."',work_status='".$work_status."',installer_mobile='".$Installermobile."',branch_id='".$Branch."',created_date='".$date."' where inst_id=$id";
 
 
 
  mysql_query($query);
//echo "record saved";
 echo "<script>document.location.href ='list_installer.php'</script>";
}

 else {
 
  
 
   $query="INSERT INTO `installer` (`inst_name`, `address`, `specialist`,toolkit ,work_status ,`installer_mobile`, `branch_id`, `created_date`) VALUES('".$installerName."','".$address."','".$specialist."','".$toolkit."','".$work_status."','".$Installermobile."','".$Branch."','".$date."')";
 

 

 
 mysql_query($query);
//echo "record saved";
 echo "<script>document.location.href ='list_installer.php'</script>";
}
}

?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	  <script>

function validateForm()
{
 
 
  if(document.myForm.Txtinstallername.value=="")
  {
  alert("please Enter Installer Name") ;
  document.myForm.Txtinstallername.focus();
  return false;
  }  
   if(document.myForm.TxtAddress.value=="")
  {
  alert("please Enter Address") ;
  document.myForm.TxtAddress.focus();
  return false;
  }  
 if(document.myForm.TxtEmail.value=="")
  {
  alert("please Enter E-mail") ;
  document.myForm.TxtEmail.focus();
  return false;
  }
  if(document.myForm.Installermobile.value=="")
  {
  alert("please Enter Installer Number") ;
  document.myForm.Installermobile.focus();
  return false;
  }
   var Installermobile=document.myForm.Installermobile.value;
  if(Installermobile!="")
        {
	var length=Installermobile.length;
	
        if(length < 9 || length > 15 || Installermobile.search(/[^0-9\-()+]/g) != -1 )
        {
        alert('Please enter valid mobile number');
        document.myForm.Installermobile.focus();
        document.myForm.Installermobile.value="";
        return false;
        }
        }
  if(document.myForm.Branch.value=="")
  {
  alert("please Choose Branch") ;
  document.myForm.Branch.focus();
  return false;
  }
  
			} 

function ChkInstallerName(value)
{
	//alert(ChkImeiNo);
	var rootdomain="http://"+window.location.hostname
var loadstatustext="<img src='"+rootdomain+"/images/icons/other/waiting.gif' />"
document.getElementById("ChkInstallerName").innerHTML=loadstatustext; 
$.ajax({
		type:"GET",
		url:"installer_name_chk.php?action=ChkInstallerName",
     
		 data:"RowId="+value,
		success:function(msg){
			 
		document.getElementById("ChkInstallerName").innerHTML = msg;
		if(document.getElementById("ChkInstallerName").innerHTML!=""){
			document.getElementById("Txtinstallername").value =	"";
		}				
		}
	});
} 	

      </script> 
 <form name="myForm" action="" onsubmit="return validateForm()" method="post">
 

   <table style="padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">

         <p>&nbsp;</p>
         <tr>
            <td>Date</td>
            <td>
                <input type="text" name="date" id="datepicker1" readonly value="<?= date("Y-m-d H:i:s")?>" /></td>
        </tr>
		<tr>
            <td>
            Installer Name</td>
            <td>
              <input type="text" name="installer_name" id="Txtinstallername" value="<?=$result['inst_name']?>" onBlur="ChkInstallerName(this.value);"/>
            </td>
        </tr>
        <tr>
          <td colspan="2" align="center"><div id="ChkInstallerName"></div></td>
        </tr>
        
        <tr>
            <td>
           Address</td>
            <td><textarea rows="5" cols="20"  type="text" name="TxtAddress" id="TxtAddress" ><?=$result['address']?></textarea>
            </td>
        </tr>
       
        <tr>
            <td>
            Specialist</td>
            <td>
             <select name="Txtspecialist" id="Txtspecialist">
                <option value="" >-- Select One --</option>
                <option name="Txtspecialist" value="Car" <? if($result['specialist']=="Car") {?> selected="selected" <? } ?> >Car </option>      
                <option name="Txtspecialist" value="Bus/Truck" <? if($result['specialist']=="Bus/Truck") {?> selected="selected" <? } ?> >Bus/Truck  </option> 
                <option name="Txtspecialist" value="All" <? if($result['specialist']=="All") {?> selected="selected" <? } ?> >All       
            </option>
           </select>
              </td>
        </tr>
		<tr>
            <td>
            Tool Kit</td>
            <td>
             <select name="TxtToolkit" id="TxtToolkit">
                <option value="" >-- Select One --</option>
                <option name="TxtToolkit" value="Yes" <? if($result['toolkit']=="Yes") {?> selected="selected" <? } ?> >Yes</option>
                <option name="TxtToolkit" value="No" <? if($result['toolkit']=="No") {?> selected="selected" <? } ?> >No</option>       
            </select>
            </td>
        </tr>
        		
		<tr>
            <td>
            Work Status</td>
            <td>
             <select name="Txtworkstatus" id="Txtworkstatus">
                <option value="" >-- Select One --</option>
                <option name="Txtworkstatus" value="Complete" <? if($result['work_status']=="Complete") {?> selected="selected" <? } ?> >Complete</option>
                <option name="Txtworkstatus" value="Training" <? if($result['work_status']=="Training") {?> selected="selected" <? } ?> >Training       
            </option>
            </select>
            </td>
        </tr>
        
        <tr>
            <td>
            Installer Contact Number</td>
            <td>
            <input type="text" name="Installermobile" id="Installermobile" value="<?=$result['installer_mobile']?>"/></td>
        </tr>
       
        
            <tr>
                <td>
                <label for="Branch" id="Branch">Branch</label></td>
                <td>
                <select name="Branch" id="Branch">
                <option value="" >-- Select One --</option>
                <?php
                $main_user_id=mysql_query("SELECT * FROM gtrac_branch");
                while ($data=mysql_fetch_assoc($main_user_id))
                {
                ?>
                
                <option name="main_user_id" value="<?=$data['id']?>" <? if($result['branch_id']==$data['id']) {?> selected="selected" <? } ?> >
            <?php echo $data['branch_name']; ?>
            </option>
                <?php 
                } 
                
                ?>
                </select></td>
            </tr>
            
    <tr><td colspan="2" align="center"> <input type="submit" name="submit" value="submit"  /></td></tr>

     
  </form>
</div>
 
<?php
include("../include/footer.php"); ?>
