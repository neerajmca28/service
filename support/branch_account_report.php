<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/  


$Header="Payment Report";

$date=date("Y-m-d H:i:s");
$req_persion=$_SESSION['username'];
$action=$_GET['action'];
$id=$_GET['id'];
$page=$_POST['page'];
if($action=='edit' or $action=='editp')
	{
		$Header="Edit Repair Report";
	$result=mysql_fetch_array(mysql_query("select * from branch_account_report where id='".$id."'"));	
	
	}
?>

<div class="top-bar">
<h1><? echo $Header;?></h1>
</div>
<div class="table"> 
<?
if(isset($_POST['submit']))
{
	$date=$_POST['date'];
	$req_persion=$_POST['req_persion'];
	$client_name=$_POST['client_name'];
	$expected_ammount=$_POST['expected_ammount'];
	$received_ammount=$_POST['received_ammount'];
	//$day_total=$_POST['day_total'];
	
	$update_query="UPDATE branch_account_report SET received_ammount='".$received_ammount."',update_date='".$date."' WHERE id='".$id."'";
	
	mysql_query($update_query);
	echo "<script>document.location.href ='list_branch_account_report.php'</script>";
	
}

?>
	

<form method="post" action="" name="form1" onSubmit="">

    <table style="padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">
	
        <tr>
            <td align="right">Request Date</td>
            <td>
              <input type="text" name="date" id="datepicker1" value="<?php echo $date;?>" readonly="readonly"/>
			  </td>
        </tr>

		<tr>
            <td align="right">Request By: </td>
            <td>
                <input type="text" name="req_persion" id="TxtAccManager" readonly value="<?php echo $req_persion;?>"/>
            </td>
        </tr>
        <tr>
            <td  align="right">Client Name:</td>
            <!--<td>
            	<input type="text" name="client_name" id="TxtClient_name"  value="<?php echo $result['clients'];?>"/>
            </td>-->
            <td>
                <select name="client_name" id="TxtClient_name" >
                <option value="" name="main_user_id" id="TxtMainUserId">-- Select One --</option>
                <?php
                $main_user_id=mysql_query("SELECT id as user_id, sys_username as name FROM matrix.users where sys_active=1 order by name asc");
                while ($data=mysql_fetch_assoc($main_user_id))
                {
                ?>                
                <option name="main_user_id" value="<?=$data['name']?>" <? if($result['clients']==$data['name']) {?> selected="selected" <? } ?> >
                <?php echo $data['name']; ?>
                </option>
                <?php 
                } 
                
                ?>
                </select>
            
            </td>
        </tr>
        <tr>
            <td  align="right">Expected Ammount:</td>
            <td>
            	<input type="text" name="expected_ammount" id="TxtExpectedAmmount"  value="<?php echo $result['expected_ammount'];?>" readonly="readonly"/>
            </td>
        </tr>
        <tr>
            <td  align="right">Received Ammount:</td>
            <td>
            	<input type="text" name="received_ammount" id="TxtReceivedAmmount"  value="<?php echo $result['received_ammount'];?>"/>
            </td>
        </tr>
        <!--<tr>
            <td  align="right">Day Total:</td>
            <td>
            	<input type="text" name="day_total" id="TxtDayTotal"  value="<?php echo $result['day_total'];?>"/>
            </td>
        </tr>-->
        <tr>
            <td height="32" align="right">
            	<input type="submit" name="submit" value="submit" align="right" />
             </td>
             <td>
             	<input type="button" name="Cancel" value="Cancel" onClick="window.location = 'list_branch_account_report.php' " />
             </td>
        </tr>


</table>


	</form>
   </div>

<?
include("../include/footer.inc.php");

?>
 

 
 