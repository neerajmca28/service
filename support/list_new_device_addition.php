<?php
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/

if($_SESSION['username']=="dservicehead" || $_SESSION['username']=="misexecutive"){
    $branch='Branch_id IN (1,2,3,4,6,7)';    
}
/*else if($_SESSION['username']=="misexecutive"){
    $branch='Branch_id IN (1)';
}*/
else if($_SESSION['username']=="ragini"){
    $branch='Branch_id IN (2)';
}
else if($_SESSION['username']=="rajeshree"){
    $branch='Branch_id IN (6)';
}
elseif($_SESSION['username']=="jaipursupport"){
    $branch='Branch_id IN (3)';
}

 $user_id = "";
 $user_query = select_query("SELECT Userid FROM internalsoftware.addclient WHERE $branch AND Userid NOT IN(1,2143)");
 for($j=0;$j<count($user_query);$j++){
     $user_id.= $user_query[$j]['Userid'].",";
 }
 $user = substr($user_id,0,-1);


/*if($_SESSION['username']=="dservicehead"){$request = "('triloki','jaipurrequest','ragini','asaleslogin','pankaj','ksaleslogin')";}
elseif($_SESSION['username']=="ruchi"){$request = "('triloki')";}
elseif($_SESSION['username']=="ragini"){$request = "('ragini','pankaj')";}
elseif($_SESSION['username']=="rajeshree"){$request = "('asaleslogin')";}
elseif($_SESSION['username']=="jaipursupport"){$request = "('jaipurrequest')";}*/


if(($_POST['submit'] == "submit") || ($_POST['FromDate'] != '' && $_POST['ToDate'] != ''))
{
    $fromdate = $_POST['FromDate'];
    $todate = $_POST['ToDate'];
    $time1 = date("Y-m-d H:i:s",strtotime($fromdate." 00:00:00"));
    $time2 = date("Y-m-d H:i:s",strtotime($todate." 23:59:59"));

    /*if($_POST["Showrequest"]==1)
    {
      $WhereQuery=" where user_id IN($user) and (billing='No' or billing_if_old_device='No') and final_status=1 and date>='".$time1."' 
      and date<='".$time2."' ";
    }
    else if($_POST["Showrequest"]==2)
    {*/
     $WhereQuery=" where user_id IN($user) and date>='".$time1."' and date<='".$time2."'";
    /*}
    else if($_POST["Showrequest"]==4)
    { 
     $WhereQuery=" where user_id IN($user) and (billing='Yes' or billing_if_old_device='Yes') and final_status=1 and date>='".$time1."' 
      and date<='".$time2."'";
    }
    else
    {
     $WhereQuery=" where user_id IN($user) and approve_status=0 and final_status!=1 and date>='".$time1."' and date<='".$time2."'";
    }*/
}
else
{
    $time1 = ''; $time2 = '';
    if($_POST["Showrequest"]==1)
    {
      $WhereQuery=" where user_id IN($user) and (billing='No' or billing_if_old_device='No') and final_status=1 order by date DESC limit 1000";
    }
    else if($_POST["Showrequest"]==2)
    {
      $WhereQuery=" where user_id IN($user) order by date DESC limit 1000";
     //$WhereQuery=" where final_status=1 ";
    }
    /*else if($_POST["Showrequest"]==3)
    {
     $WhereQuery=" where approve_status=1 and acc_manager in ".$request;
    }*/
    else if($_POST["Showrequest"]==4)
    { 
      $WhereQuery=" where user_id IN($user) and (billing='Yes' or billing_if_old_device='Yes') and final_status=1 order by date DESC limit 1000";
    }
    else
    {
      $WhereQuery=" where user_id IN($user) and approve_status=0 and final_status!=1 order by date DESC limit 1000";
    }
 
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

function req_validate(myForm)
{
       if(document.myForm.FromDate.value ==""){
           alert("Please Enter From Date");
           document.myForm.FromDate.focus();
           return false;
       }
       if(document.myForm.ToDate.value ==""){
           alert("Please Enter To Date");
           document.myForm.ToDate.focus();
           return false;
       }
}


function ConfirmDelete(row_id)
{
  var x = confirm("Are you sure you want to Approve?");
  if (x)
  {
  approve(row_id);
      return ture;
  }
  else
    return false;
}

function approve(row_id)
{
    //alert(user_id);
    //return false;
$.ajax({
        type:"GET",
        url:"userInfo.php?action=new_device_additionapprove",
         data:"row_id="+row_id,
        success:function(msg){
         
        location.reload(true);        
        }
    });
}
function backComment(row_id)
{
   var retVal = prompt("Write Comment : ", "Comment");
  if (retVal)
  {
  addComment(row_id,retVal);
      return ture;
  }
  else
    return false;
}

function addComment(row_id,retVal)
{
    //alert(user_id);
    //return false;
$.ajax({
        type:"GET",
        url:"userInfo.php?action=new_device_additionadminComment",
          
         data:"row_id="+row_id+"&comment="+retVal,
        success:function(msg){
             alert(msg);
             
         
        location.reload(true);        
        }
    });
}

</script>

 <div class="top-bar">
    <div align="center">
    <form name="myForm" method="post" action="">
            <select name="Showrequest" id="Showrequest" onChange="form.submit();" >
                <option value="0" <? if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>
                <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending</option>
                <!--<option value="3" <?if($_POST['Showrequest']=='3'){ echo "Selected"; }?>>Approved</option>-->
                <option value="4" <?if($_POST['Showrequest']=='4'){ echo "Selected"; }?>>Billing</option>
                <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>No Billing</option>
                <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
            </select>
      </form> 
    </div>
    <h1>New Device Addition List</h1>
      
</div>
    
<div class="top-bar">

<div style="float:right";><font style="color:#68C5CA;font-weight:bold;">Blue:</font> Back from support</div>
<br/>
<div style="float:right";><font style="color:#F2F5A9;font-weight:bold;">Yellow:</font> Back from Admin</div>


<br/>
<div style="float:right";><font style="color:#99FF66;font-weight:bold;">Green:</font> Completed your requsest.</div>              
 <form name="myForm" method="post" action="">
    <table cellspacing="5" cellpadding="5">
        <tr>
            <td >From Date</td>
            <td><input type="text" name="FromDate" id="FromDate" value="<?=$fromdate;?>"/></td>
            
            <td>To Date</td>
            <td><input type="text" name="ToDate" id="ToDate"  value="<?=$todate;?>" /></td>
            <td align="center"> <input type="submit" name="submit" value="submit" onClick="return req_validate(myForm)" /></td>   
        </tr>
    </table>
</form>
</div>
<div style="float:right";><a href="reportfiles/new_device_addition.xls">Create Excel</a><br/></div>
<div class="table">

<?php
 
$query = select_query("SELECT * FROM new_device_addition ".$WhereQuery);


$excel_data.='<table cellpadding="5" cellspacing="5" border="1"><thead><tr><td colspan="12" align="center"><strong>New Device Addition List</strong></td></tr><tr><td colspan="12"></td></tr><tr><th width="5%">SL.No</th><th width="7%">Date</th><th width="10%">Sales Manager</th><th width="8%">Client</th><th width="8%">Company Name</th><th width="8%">Vehicles No </th><th width="8%">IMEI</th><th width="8%">Model</th><th width="10%">Device Type</th><th width="8%">Billing</th><th width="8%">No Billing Reason</th></tr></thead><tbody>';

?>

 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>SL.No</th>
            <th>Date</th>
            <th>Sales Manager</th>
            <th>Client</th>
            <th>Company Name</th> 
            <th>Vehicles No</th>
            <th>IMEI</th>
             <th>Model</th>
            <th>Device Type</th>
            <th>Billing</th>
            <th>No Billing Reason</th>
            <th>View Detail</th>
        </tr>
    </thead>
    <tbody>

 
 
<?php 

//while($row=mysql_fetch_array($query))
for($i=0;$i<count($query);$i++)
{
    $sr = $i+1;
?>
<tr align="center" <? if( $query[$i]["support_comment"]!="" && $query[$i]["final_status"]!=1 ){ echo 'style="background-color:#68C5CA"';} elseif($query[$i]["final_status"]==1){ echo 'style="background-color:#99FF66"';}elseif($query[$i]["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';}elseif( $query[$i]["admin_comment"]!="" && $query[$i]["final_status"]!=1 && $query[$i]["service_comment"]==""){ echo 'style="background-color:#F2F5A9"';}?> >

    <td><?php echo $i+1;?></td>
    <td><?php echo $query[$i]["date"];?></td>
    <td><?php echo $query[$i]["sales_manager"];?></td>
    <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$query[$i]["user_id"];
    $rowuser=select_query($sql);
    ?>
    <td><?php echo $rowuser[0]["sys_username"];?></td>
    <td><?php echo $query[$i]["client"];?></td> 
    <td><?php echo $query[$i]["vehicle_no"];?></td> 
    <td><?php echo $query[$i]["device_imei"];?></td>
    <td><?php echo $query[$i]["device_model"];?></td>
    <td><?php echo $query[$i]["device_type"];?></td>  
    
    <? /*if($query[$i]["device_type"]=='New'){
    $billing_status=$query[$i]["billing"];
    }
    else{
    $billing_status=$query[$i]["billing_if_old_device"];
    }*/
        ?>
    <td><?php echo $query[$i]["billing"]; ?></td>  
    <td><?php echo $query[$i]["billing_if_no_reason"];?></td>  
    <td><a href="#" onClick="Show_record(<?php echo $query[$i]["id"];?>,'new_device_addition','popup1'); " class="topopup">View Detail</a>
    
    </td>



</tr> 
    <?php 
    
    $excel_data.="<tr><td width='5%'>".$sr."</td><td width='7%'>".$query[$i]["date"]."</td><td width='10%'>".$query[$i]["sales_manager"]."</td><td width='8%'>".$rowuser[0]["sys_username"]."</td><td width='8%'>". $query[$i]["client"]."</td><td width='8%'>".$query[$i]["vehicle_no"]."</td><td width='8%'>".$query[$i]["device_imei"]."</td><td width='8%'>".$query[$i]["device_model"]."</td><td width='10%'>".$query[$i]['device_type']."</td><td width='8%'>".$query[$i]["billing"]."</td><td width='8%'>".$query[$i]["billing_if_no_reason"]."</td></tr>";
     
    }
    $excel_data.='</tbody></table>';
    ?>
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
unlink(__DOCUMENT_ROOT.'/support/reportfiles/new_device_addition.xls');
$filepointer=fopen(__DOCUMENT_ROOT.'/support/reportfiles/new_device_addition.xls','w');
fwrite($filepointer,$excel_data);
fclose($filepointer);

include("../include/footer.inc.php"); 

?>