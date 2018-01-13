<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/

?>
<script>
/*function ConfirmPaymentClear(row_id)
{
  var x = confirm("All payment cleared?");
  if (x)
  {
  PaymentClear(row_id);
      return ture;
  }
  else
    return false;
}

function PaymentClear(row_id)
{
    //alert(user_id);
    //return false;
$.ajax({
        type:"GET",
        url:"userInfo.php?action=NoBillPaymentClear",
         data:"row_id="+row_id,
        success:function(msg){
         
        location.reload(true);       
        }
    });
}

function ConfirmPaymentPending(row_id)
{
   var retVal = prompt("Pending Amount : ", "Pending Amount");
  if (retVal)
  {
  PaymentPending(row_id,retVal);
      return ture;
  }
  else
    return false;
}

function PaymentPending(row_id,retVal)
{
    //alert(user_id);
    //return false;
$.ajax({
        type:"GET",
        url:"userInfo.php?action=NoBillPaymentPending",
          
         data:"row_id="+row_id+"&comment="+retVal,
        success:function(msg){
             
        location.reload(true);       
        }
    });
}*/

function forwardbackComment(row_id)
{
   var retVal = prompt("Write Comment : ", "Comment");
  if (retVal)
  {
  addComment1(row_id,retVal);
      return ture;
  }
  else
    return false;
}

function addComment1(row_id,retVal)
{
    //alert(user_id);
    //return false;
$.ajax({
        type:"GET",
        url:"userInfo.php?action=nobillbackComment",
          
         data:"row_id="+row_id+"&comment="+retVal,
        success:function(msg){
             //alert(msg);
         
        location.reload(true);       
        }
    });
}

</script>
 
 <div class="top-bar">
                  
            <div align="center">
                <form name="myformlisting" method="post" action="">
                    <select name="Showrequest" id="Showrequest" onchange="form.submit();" >
                        <option value="0" <? if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>
                        <option value=3 <?if($_POST['Showrequest']==3){ echo "Selected"; }?>>Admin Approved</option>
                        <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending</option>
                        <option value="4" <?if($_POST['Showrequest']==4){ echo "Selected"; }?>>Action Taken</option>
                        <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Closed</option>
                        <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
                       
                    </select>
                </form>
            </div>
                    <h1>No Bill</h1>
                     
                </div>
                <div class="top-bar">
                <div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font>Admin Approved</div>
                <br/> 
                <div style="float:right";><font style="color:#D462FF;font-weight:bold;">Purple:</font> Back from support</div>
                <br/>
                <div style="float:right";><font style="color:#FF0000;font-weight:bold;">Red:</font> Back from Account</div>
                <br/>
                <div style="float:right";><font style="color:#8BFF61;font-weight:bold;">Green:</font> Completed your requsest.</div>             
                </div>
                <div class="table">
<?php
if($_SESSION['username']=="dservicehead"){$request = "('gaurav','kuldeep','naveen','basant','saleslogin')";}
elseif($_SESSION['username']=="ragini"){$request = "('sanjeeb')";}
elseif($_SESSION['username']=="rajeshree"){$request = "('asaleslogin')";}
elseif($_SESSION['username']=="jaipursupport"){$request = "('jaipursales','jaipurrequest')";}

if($_POST["Showrequest"]==1)
{
    $WhereQuery=" where approve_status=1 and final_status=1 and acc_manager in ".$request;
}
else if($_POST["Showrequest"]==2)
{
    $WhereQuery=" where (approve_status=1 or approve_status=0) and no_bill_issue in ('Service Issue','Client Side Issue') and acc_manager in ".$request;
}
else if($_POST["Showrequest"]==3)
{
    $WhereQuery=" where approve_status=1 and final_status!=1 and acc_manager in ".$request;
}
else if($_POST["Showrequest"]==4)
{
    $WhereQuery=" where  approve_status=0 and service_comment!=''";
}
else
{

    $WhereQuery=" where (approve_status=0 and no_bill_issue in ('Service Issue','Client Side Issue') and service_comment is null and acc_manager in ".$request.") or (forward_req_user='".$_SESSION["username"]."' and (forward_back_comment is null or forward_back_comment=''))";

}

$query = mysql_query("SELECT * FROM no_bills  ". $WhereQuery." order by id desc ");  

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>SL.No</th>
            <th>Date</th>
            <th>Account Manager</th>
            <th>Client</th>
            <th>Vehicle Number</th>
            <th>No Bill For</th>
            <th>Reason</th>
            <th>View Detail</th>
            <th>Add Comments</th>
           
        </tr>
    </thead>
    <tbody>


 
<?php

$i=1;
while($row=mysql_fetch_array($query))
{
?>
<tr align="center" <?php /*?><? if( $row["support_comment"]!="" && $row["final_status"]!=1 ){ echo 'style="background-color:#D462FF"';}elseif($row["total_pending"]!="" && $row["account_comment"]=="" && $row["sales_comment"]==""){ echo 'style="background-color:#FF0000"';}elseif($row["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';}elseif($row["final_status"]==1){ echo 'style="background-color:#8BFF61"';}?><?php */?> >
 
    <td><?php echo $i;?></td>
    <td><?php echo $row["date"];?></td>
    <? /*if($row["acc_manager"]=='saleslogin') {
    $account_manager=$row["sales_manager"];
    }
    else {
    $account_manager=$row["acc_manager"];
    }*/
   
    ?>
   
    <td><?php echo $row["sales_manager"];?></td>
    <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row["client"];
    $rowuser=select_query($sql);
   
        ?>
    <td><?php echo $row["company_name"];?></td>
    <td><?php echo $row["veh_no_bill"];?></td> 
    <td><?php echo $row["rent_device"];?></td> 
    <td><?php echo $row["reason"];?></td>
    <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'no_bills','popup1'); " class="topopup">View Detail</a>
   <?php if( $row["forward_comment"]!="" && $row["forward_req_user"]!="" ){ ?>
        |<a href="#" onclick="return forwardbackComment(<?php echo $row["id"];?>);"  >Forward Back Comment</a>
    <?php } ?>  
    </td>
    <td><?php
    if($_POST["Showrequest"]!=1 && $_POST["Showrequest"]!=2){
    if(($row["no_bill_issue"]=="Service Issue" || $row["no_bill_issue"]=="Client Side Issue") && $row["service_comment"]=="") {?> 
     <a href="no_bill.php?id=<?=$row['id'];?>&action=edit<? echo $pg;?>">Add Comments</a>
    <!--<td width="12%" align="center">&nbsp;<a href="services.php?id=<?php echo $row['id'];?>&action=delete">Delete</a></td>-->
   
    <?php }} ?>
    </td>

   
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