<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

$action = $_GET['action'];
$id = $_GET['rowid'];
if($action == 'edit' && $id != '')
{
    $query_data = select_query("SELECT * FROM internalsoftware.add_client_information WHERE id='".$_GET["rowid"]."'");
         
    $main_user_id = $query_data[0]["user_id"]; 
    $company = $query_data[0]["company_name"]; 
    $contact_number = $query_data[0]["contact_number"]; 
    $executive_name = $query_data[0]["executive_name"]; 
    $extension_no = $query_data[0]["extension_no"]; 
}

?> 
 
<div class="top-bar">
<h1>ADD Client Information</h1>
</div>
<div class="table"> 

<?


if(isset($_POST['submit']))
{ 
    //echo "<pre>";print_r($_POST);die;
    
    $date = date("Y-m-d H:i:s");
    $request_by = $_POST['req_person'];    
    $main_user_id = $_POST['main_user_id'];
    $company = $_POST['company'];
    
    $contact_no = $_POST['contact_no'];
    $executive = $_POST['executive'];
    $extension_no = $_POST['extension_no'];
    
    $clientName = select_query("SELECT UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid='".$main_user_id."' LIMIT 0,1");
    $username = $clientName[0]['sys_username'];

    if($action == 'edit')
    {
        $sql = "UPDATE add_client_information set user_id='".$main_user_id."', user_name='".$username."', company_name='".$company."',
                contact_number='".$contact_no."', executive_name='".$executive."', extension_no='".$extension_no."', final_status='0', 
                close_date=null where id='".$_GET["rowid"]."'";
        $update_data = select_query($sql);
    
    }
    else
    {
        $sql = "INSERT INTO `add_client_information` (`request_by`, `user_id`, `user_name`, `company_name`, `contact_number`, `executive_name`, 
        `extension_no`) VALUES ('".$request_by."', '".$main_user_id."', '".$username."', '".$company."', '".$contact_no."', '".$executive."', 
        '".$extension_no."')";
        $insert_data = select_query($sql);
    
    }
    
    echo "<script>document.location.href ='list_add_clients.php'</script>";
    //header("location:list_add_clients.php");

}


?>
<script type="text/javascript">

 
 function validateForm()
 { 
        var main_user_id=document.forms["myForm"]["main_user_id"].value;
        if (main_user_id==null || main_user_id=="")
        {
          alert("Select Username");
          return false;
        }
        
        var contact_no=document.forms["myForm"]["contact_no"].value;
        if (contact_no==null || contact_no=="")
        {
            alert("Enter Client Number");
            return false;
        }
        if(contact_no!="")
        {               
            var charnumber=contact_no.length;
            if(charnumber < 10 || charnumber > 12 || contact_no.search(/[^0-9\-()+]/g) != -1) {
            alert("Please enter valid Phone number");
            document.myForm.contact_no.focus();
            document.myForm.contact_no.value="";
            return false;
            }
        }
        
        var executive=document.forms["myForm"]["executive"].value;
        if (executive==null || executive=="")
        {
          alert("Please Enter Executive Name");
          return false;
        }
         
        var extension=document.forms["myForm"]["extension_no"].value;
        if (extension==null || extension=="")
        {
          alert("Please Enter Extension No");
          return false;
        } 
          
}
</script>

<?php echo "<p align='left' style='padding-left: 250px;width: 500px;' class='message'>" .$errorMsg. "</p>" ; ?>

<style type="text/css" >
.errorMsg{border:1px solid red; }
.message{color: red; font-weight:bold; }
</style>

 <form name="myForm" action="" onsubmit="return validateForm()" method="post">

   <table style=" padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">

        <tr>
            <td  align="right"> Request Person:</td>
            <td><input type="text" name="req_person" id="req_person" value="<?=$_SESSION['username'];?>" readonly style="width:150px" /> </td>
        </tr> 
        <tr>
            <td align="right">Client Name:</td>
            <td> 
            <select name="main_user_id" id="TxtMainUserId"  onchange="getCompanyName(this.value,'TxtCompany');"  style="width:250px">
            <option value="" name="main_user_id" id="TxtMainUserId">-- Select One --</option>
            <?php
            $main_user_data = select_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE sys_active=1 ORDER BY `name` asc");
            //while ($data=mysql_fetch_assoc($main_user_iddata))
            for($u=0;$u<count($main_user_data);$u++)
            {
                if($main_user_data[$u]['user_id']==$main_user_id)
                {
                    $selected="selected";
                }
                else
                {
                    $selected="";
                }
            ?>
            <option value ="<?php echo $main_user_data[$u]['user_id'] ?>" <?echo $selected;?>><?=$main_user_data[$u]['name']; ?></option>            
            <?php 
            } 
            ?>
            </select>
            </td>
        </tr>
        
        <tr>
            <td  align="right">Company Name:</td>
            <td><input type="text" name="company" id="TxtCompany" readonly value="<?=$company;?>"  style="width:150px"/> </td>
        </tr>
        
        <tr>
            <td align="right">Client Number:</td>
            <td><input type="text" name="contact_no" id="contact_no" value="<?=$contact_number;?>"  style="width:150px" /></td>
        </tr>
        
        <tr>
            <td  align="right"> Executive Name:</td>
            <td> <input type="text" name="executive" id="executive" value="<?=$executive_name;?>" style="width:150px" /> </td>
        </tr>
         
        <tr>
          <td  align="right">Extension No:</td>
        <td  ><input type="text" name="extension_no"  id="extension_no"   style="width:150px" value="<?=$extension_no;?>"/></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="submit" id="button1" align="right" />&nbsp;&nbsp;
                <input type="button" name="Cancel" value="Cancel" onClick="window.location = 'list_add_clients.php' " /></td>
        </tr>

</table>
</form>
 
<?
include("../include/footer.inc.php");

?>