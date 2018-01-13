<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/private/master.php');

$masterObj = new master();
/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */ 
?> 

<link  href="../css/auto_dropdown.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

/*Start auto ajax value load code*/

$(document).ready(function(){
    $(document).click(function(){
        $("#ajax_response").fadeOut('slow');
    });
    $("#Zone_area").focus();
    var offset = $("#Zone_area").offset();
    var width = $("#Zone_area").width()-2;
    $("#ajax_response").css("left",offset); 
    $("#ajax_response").css("width","15%");
    $("#ajax_response").css("z-index","1");
    $("#Zone_area").keyup(function(event){
         //alert(event.keyCode);
         var keyword = $("#Zone_area").val();
         if(keyword.length)
         {
             if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13)
             {
                 $("#loading").css("visibility","visible");
                 $.ajax({
                   type: "POST",
                   url: "load_zone_area.php",
                   data: "data="+keyword,
                   success: function(msg){    
                    if(msg != 0)
                      $("#ajax_response").fadeIn("slow").html(msg);
                    else
                    {
                      $("#ajax_response").fadeIn("slow");    
                      $("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
                    }
                    $("#loading").css("visibility","hidden");
                   }
                 });
             }
             else
             {
                switch (event.keyCode)
                {
                 case 40:
                 {
                      found = 0;
                      $("li").each(function(){
                         if($(this).attr("class") == "selected")
                            found = 1;
                      });
                      if(found == 1)
                      {
                        var sel = $("li[class='selected']");
                        sel.next().addClass("selected");
                        sel.removeClass("selected");
                      }
                      else
                        $("li:first").addClass("selected");
                     }
                 break;
                 case 38:
                 {
                      found = 0;
                      $("li").each(function(){
                         if($(this).attr("class") == "selected")
                            found = 1;
                      });
                      if(found == 1)
                      {
                        var sel = $("li[class='selected']");
                        sel.prev().addClass("selected");
                        sel.removeClass("selected");
                      }
                      else
                        $("li:last").addClass("selected");
                 }
                 break;
                 case 13:
                    $("#ajax_response").fadeOut("slow");
                    $("#Zone_area").val($("li[class='selected'] a").text());
                 break;
                }
             }
         }
         else
            $("#ajax_response").fadeOut("slow");
    });
    $("#ajax_response").mouseover(function(){
        $(this).find("li a:first-child").mouseover(function () {
              $(this).addClass("selected");
        });
        $(this).find("li a:first-child").mouseout(function () {
              $(this).removeClass("selected");
        });
        $(this).find("li a:first-child").click(function () {
              $("#Zone_area").val($(this).text());
              $("#ajax_response").fadeOut("slow");
        });
    });
});
/* End auto ajax value load code*/
</script>
 
<div class="top-bar">
<h1>ADD Service</h1>
</div>
<div class="table"> 

<?

  
//$date=date("Y-m-d H:i:s");
//$account_manager=$_SESSION['username'];
if(isset($_GET["Addservice"]) && $_GET["Addservice"]="true" )
{
    $main_user_id = $_GET['u'];
    $company = $_GET['c'];
    $veh_reg = $_GET['v'];
    //$Device_model = $_GET['m'];
    $TxtDeviceIMEI = $_GET['i'];
    $date_of_install = $_GET['d'];
    $Notwoking = $_GET['n'];
}

if($_GET["edit"]==true && $_GET["rowid"]!='')
{
    $query=mysql_query("SELECT * FROM services WHERE id='".$_GET["rowid"]."'",$dblink2);
    $rows=mysql_fetch_array($query);
    
     
    //$date= $rows["req_date"]; 
    //$account_manager=$rows["request_by"]; 
    $main_user_id = $rows["user_id"]; 
    $company = $rows["company_name"]; 
    $veh_reg = $rows["veh_reg"]; 
    $city = $rows['inter_branch'];
    $Device_model = $rows["device_model"]; 
    $TxtDeviceIMEI = $rows["device_imei"]; 
    $date_of_install = $rows["date_of_installation"]; 
    $Notwoking = $rows["Notwoking"]; 
    
    $Zone_area = $rows["Zone_area"];
    $area = mysql_fetch_array(mysql_query("SELECT id,`name` FROM re_city_spr_1 WHERE id='".$Zone_area."'")); 
    $location = $rows["location"]; 
    $city = $rows["inter_branch"];
    $atime = $rows["atime"]; 
    $atimeto = $rows["datetimepickerto"]; 
    $pname = $rows["pname"]; 
    $cnumber = $rows["cnumber"]; 
    $required = $rows['required'];
    $IP_Box = $rows['IP_Box'];
    $atime_status = $rows['atime_status'];
    
    $datapullingtime = $rows["datapullingtime"]; 
    $comment = $rows["comment"]; 
    
    $sql = mysql_query("SELECT Userid AS id,UserName AS sys_username FROM addclient WHERE Userid='$main_user_id' and Branch_id='".$_SESSION['BranchId']."'",$dblink2);
    $row = mysql_fetch_array($sql);
    $username = $row['sys_username'];

}

if(isset($_POST['submit']))
{ 
    //echo "<pre>";print_r($_POST);die;
    $date = date("Y-m-d H:i:s");
    $account_manager = $_SESSION['username'];
    $main_user_id = $main_user_id;
    $company = $_POST['company'];
    $veh_reg = $veh_reg;
    $Device_model = $_POST['Device_model'];
    $TxtDeviceIMEI = $TxtDeviceIMEI;
    $date_of_install = $date_of_install;
    $Notwoking = $Notwoking;
    $branch_type = $_POST['inter_branch'];
    if($branch_type == "Interbranch"){
        $city=$_POST['inter_branch_loc'];
        $location="";
    }else{
        $city=0;
        $location=$_POST['location'];
    }
    
    $Zone_id = mysql_query("SELECT id,`name` FROM re_city_spr_1 WHERE `name`='".$_POST['Zone_area']."'");
    $zone_count = mysql_num_rows($Zone_id);
    $Zone_data = mysql_fetch_array($Zone_id);    
    if($zone_count > 0)
    {
        $Zone_area = $Zone_data["id"];
        $errorMsg = "";
    }
    else
    {
        $errorMsg = "Please Select Type View Listed Area. Not Fill Your Self.";
    }
    
    $pname=$_POST['pname'];
    $cnumber=$_POST['cnumber'];
    $status=$_POST['status'];
    $required=$_POST['required'];
    $IP_Box=$_POST['IP_Box'];
    $datapullingtime=$_POST['datapullingtime'];
    $comment=$_POST['TxtComment'];
    $atime_status=$_POST['atime_status'];
    $service_reinstall=$_POST['service_reinstall'];
    
    $sql=mysql_query("SELECT UserName AS sys_username FROM addclient  WHERE Userid='$main_user_id'",$dblink2);
    $row=mysql_fetch_array($sql);
    $username=$row['sys_username'];


    // `inst_name`, `inst_cur_location`, `inst_date`, `reason`, `time`, `payment_status`, `amount`, `paymode`, `back_reason`, `close_date`, `pending`, `newpending`, `status`, `newstatus`, `move_vehicles`, `billing`, `payment`, `required`, `datapullingtime`, `IP_Box`, `updated_date`, `pending_closed`, `branch_id`,
    
if($errorMsg=="")    
{
    if($_GET["edit"]==true && $_GET["rowid"]!='')
    {
        if($atime_status=="Till")
        {
            $time=(isset($_POST["time"])) ? trim($_POST["time"])  : "";
            
            /* $sql="update `services` set `request_by`='".$account_manager."', `name`= '".$username."', `user_id`= '".$main_user_id."', `company_name`='".$company."', `veh_reg`='".$veh_reg."', `device_imei`='".$TxtDeviceIMEI."', `date_of_installation`='".$date_of_install."',  `Notwoking`='".$Notwoking."', `location`='".$location."', `atime`='".$time."',`atime_status`='".$atime_status."', `pname`='".$pname."', `cnumber`= '".$cnumber."', `device_model`='".$Device_model."', `comment`='".$comment."',`required`='".$required."',`IP_Box`='".$IP_Box."',Zone_area='".$Zone_area."',service_reinstall='".$service_reinstall."',service_status=1,`inter_branch`='".$city."',branch_type='".$branch_type."' where id='".$_GET["rowid"]."'";*/
            
            $sql="update `services` set `location`='".$location."', `atime`='".$time."',`atime_status`='".$atime_status."', `pname`='".$pname."', `cnumber`= '".$cnumber."', `device_model`='".$Device_model."', `comment`='".$comment."',`required`='".$required."',`IP_Box`='".$IP_Box."',Zone_area='".$Zone_area."',service_reinstall='".$service_reinstall."',service_status=1,`inter_branch`='".$city."',branch_type='".$branch_type."' where id='".$_GET["rowid"]."'";
            
            
            $execute=mysql_query($sql,$dblink2);
            header("location:services.php");
        }
        if($atime_status=="Between")
        {
            $time=(isset($_POST["time1"])) ? trim($_POST["time1"])  : "";
            $totime=(isset($_POST["totime"])) ? trim($_POST["totime"])  : "";
            
            /*$sql="update `services` set `request_by`='".$account_manager."', `name`= '".$username."', `user_id`= '".$main_user_id."', `company_name`='".$company."', `veh_reg`='".$veh_reg."', `device_imei`='".$TxtDeviceIMEI."', `date_of_installation`='".$date_of_install."',  `Notwoking`='".$Notwoking."', `location`='".$location."', `atime`='".$time."', atimeto='".$totime."',`atime_status`='".$atime_status."', `pname`='".$pname."', `cnumber`= '".$cnumber."', `device_model`='".$Device_model."', `comment`='".$comment."',`required`='".$required."',`IP_Box`='".$IP_Box."',Zone_area='".$Zone_area."',service_reinstall='".$service_reinstall."',service_status=1,`inter_branch`='".$city."',branch_type='".$branch_type."' where id='".$_GET["rowid"]."'";*/
            
            $sql="update `services` set `location`='".$location."', `atime`='".$time."', atimeto='".$totime."',`atime_status`='".$atime_status."', `pname`='".$pname."', `cnumber`= '".$cnumber."', `device_model`='".$Device_model."', `comment`='".$comment."',`required`='".$required."',`IP_Box`='".$IP_Box."',Zone_area='".$Zone_area."',service_reinstall='".$service_reinstall."',service_status=1,`inter_branch`='".$city."',branch_type='".$branch_type."' where id='".$_GET["rowid"]."'";
        
            
            $execute=mysql_query($sql,$dblink2);
            header("location:services.php");
         }
         
    }
    else
    {
        if($atime_status=="Till")
        {
            $time=$_POST['time'];
    
            //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
              $sql="INSERT INTO `services` (`req_date`, `request_by`, `name`, `user_id`, `company_name`, `veh_reg`, `device_imei`, `date_of_installation`,  `Notwoking`, `location`, `atime`, `pname`,atime_status, `cnumber`,`device_model`, `comment`,`required`,`IP_Box`,branch_id,service_status,Zone_area,service_reinstall,`inter_branch`,branch_type) VALUES ('".$date."','".$account_manager."', '".$username."', '".$main_user_id."', '".$company."', '".$veh_reg."','".$TxtDeviceIMEI."','".$date_of_install."','".$Notwoking."', '".$location."', '".$time."','".$pname."','".$atime_status."', '".$cnumber."', '".$Device_model."', '".$comment."','".$required."','".$IP_Box."','".$_SESSION['BranchId']."',1,'".$Zone_area."','".$service_reinstall."','".$city."','".$branch_type."');";
            
            
            $execute=mysql_query($sql,$dblink2);
            header("location:services.php");
        }
        if($atime_status=="Between")
        {
            $time=$_POST['time1'];
            $totime=$_POST['totime'];
            
            //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
            $sql="INSERT INTO `services` (`req_date`, `request_by`, `name`, `user_id`, `company_name`, `veh_reg`, `device_imei`, `date_of_installation`,  `Notwoking`, `location`, `atime`, `atimeto`, `pname`,atime_status, `cnumber`,`device_model`, `comment`,`required`,`IP_Box`,branch_id,service_status,Zone_area,service_reinstall,`inter_branch`,branch_type) VALUES ('".$date."','".$account_manager."', '".$username."', '".$main_user_id."', '".$company."', '".$veh_reg."','".$TxtDeviceIMEI."','".$date_of_install."','".$Notwoking."', '".$location."', '".$time."','".$totime."','".$pname."','".$atime_status."', '".$cnumber."', '".$Device_model."', '".$comment."','".$required."','".$IP_Box."','".$_SESSION['BranchId']."',1,'".$Zone_area."','".$service_reinstall."','".$city."','".$branch_type."');";
    
            
            $execute=mysql_query($sql,$dblink2);
            header("location:services.php");  
        }
    }
  }
}
?>
<script type="text/javascript">

 
function validateForm()
{ 
    var job=document.forms["myForm"]["service"].value;
    if (job==null || job=="")
    {
        alert("Please Select the Job");
        return false;
    }

    var location=document.forms["myForm"]["Zone_area"].value;
    if (location=="")
    {
        alert("Please Select Area") ;
        return false;
    }
 
    var barnch=document.forms["myForm"]["inter_branch"].value;
    //alert(barnch);die;
    if (barnch==null || barnch=="")
    {
        alert("Please Select Branch") ;
        return false;
    }
    
    var location=document.forms["myForm"]["location"].value;
    //var branchchk=document.getElementById('inter_branch').checked;
    if ((location==null || location=="") && barnch=="Samebranch")
    {
        alert("Please Enter location");
        return false;
    }
    var interbranch=document.forms["myForm"]["inter_branch_loc"].value;
    if ((interbranch==null || interbranch=="") && barnch=="Interbranch")
    {
        alert("Please select branch location");
        return false;
    } 
     
    var device_model=document.forms["myForm"]["Device_model"].value;
    if (device_model==null || device_model=="")
    {
        alert("Please select Device Model");
        return false;
    }
    
    var timestatus=document.forms["myForm"]["atime_status"].value;
    if (timestatus==null || timestatus=="")
    {
        alert("Please select Availbale Time");
        return false;
    }
    
    var tilltime=document.forms["myForm"]["datetimepicker"].value;
    if(timestatus == "Till" && (tilltime==null || tilltime==""))
    {
        alert("Please select Time");
        return false;
    }
    
    var betweentime=document.forms["myForm"]["datetimepicker1"].value;
    var betweentime2=document.forms["myForm"]["datetimepicker2"].value;
    if(timestatus == "Between" && (betweentime==null || betweentime==""))
    {
        alert("Please select From Time");
        return false;
    }
    
    if(timestatus == "Between" && (betweentime2==null || betweentime2==""))
    {
        alert("Please select To Time");
        return false;
    }
      
    var pname=document.forms["myForm"]["pname"].value;
    if (pname==null || pname=="")
    {
        alert("Enter Person Name");
        return false;
    }
  
    var cnumber=document.forms["myForm"]["cnumber"].value;
    
    if (cnumber==null || cnumber=="")
    {
        alert("Enter Contact Number");
        return false;
    }
    if(cnumber!="")
    {               
        var charnumber=cnumber.length;
        if(charnumber < 10 || charnumber > 12 || cnumber.search(/[^0-9\-()+]/g) != -1) {
        alert("Please enter valid mobile number");
        document.myForm.cnumber.focus();
        document.myForm.cnumber.value="";
        return false;
        }
    }
  
}

function TillBetweenTime(radioValue)
{
 if(radioValue=="Till")
    {
        document.getElementById('TillTime').style.display = "block";
        document.getElementById('BetweenTime').style.display = "none";
    }
    else if(radioValue=="Between")
    {
        document.getElementById('TillTime').style.display = "none";
        document.getElementById('BetweenTime').style.display = "block";
    }
    else
    {
        document.getElementById('TillTime').style.display = "none";
        document.getElementById('BetweenTime').style.display = "none";
    } 
    
}

function TillBetweenTime12(radioValue)
{
 if(radioValue=="Till")
    {
        document.getElementById('TillTime').style.display = "block";
        document.getElementById('BetweenTime').style.display = "none";
    }
    else if(radioValue=="Between")
    {
        document.getElementById('TillTime').style.display = "none";
        document.getElementById('BetweenTime').style.display = "block";
    }
    else
    {
        document.getElementById('TillTime').style.display = "none";
        document.getElementById('BetweenTime').style.display = "none";
    } 
    
}

function StatusBranch(radioValue)
{
   if(radioValue=="Interbranch")
    {
        document.getElementById('branchlocation').style.display = "block";
        document.getElementById('samebranchid').style.display = "none";
    }
    else if(radioValue=="Samebranch")
    {
        document.getElementById('samebranchid').style.display = "block";
        document.getElementById('branchlocation').style.display = "none";
    }
    else
    {
        document.getElementById('branchlocation').style.display = "none";
        document.getElementById('samebranchid').style.display = "none";
    } 
    
}            

function StatusBranch12(radioValue)
{
   if(radioValue=="Interbranch")
    {
        document.getElementById('branchlocation').style.display = "block";
        document.getElementById('samebranchid').style.display = "none";
    }
    else if(radioValue=="Samebranch")
    {
        document.getElementById('samebranchid').style.display = "block";
        document.getElementById('branchlocation').style.display = "none";
    }
    else
    {
        document.getElementById('branchlocation').style.display = "none";
        document.getElementById('samebranchid').style.display = "none";
    } 
    
}

/*function enableDisable() { 
  if(document.myForm.inter_branch.checked){ 
     document.myForm.location.disabled = true; 
  } else { 
     document.myForm.location.disabled = false; 
  } 
} 
*/
    </script>


   <script type="text/javascript">

        $(function () {
             
            $("#datetimepicker").datetimepicker({});
            $("#datetimepicker1").datetimepicker({});
            $("#datetimepicker2").datetimepicker({});
            $("#datetimepicker3").datetimepicker({});
        });
   </script>      
<?php echo "<p align='left' style='padding-left: 250px;width: 500px;' class='message'>" .$errorMsg. "</p>" ; ?>

<style type="text/css" >
.errorMsg{border:1px solid red; }
.message{color: red; font-weight:bold; }
</style>
 
 <form name="myForm" action="" onsubmit="return validateForm()" method="post">
   <table style="width: 900px;" cellspacing="2" cellpadding="3" border="1">
        <tr>
            <td  align="right"><label  id="lbDlClient"><strong>Client User Name*:</strong></label></td>
            <td align="center"> 
            <?php
            $main_user_data = select_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE Userid='".$main_user_id."'");
            
            $commpany = $masterObj->getCompanyName($main_user_id);
               
            ?>
           <!-- <input type="hidden" name="main_user_id" id="TxtMainUserId" value="<?php echo $main_user_data['user_id']; ?>" readonly="readonly" />
            <input type="text" name="user_name" id="TxtMainUserId" value="<?php echo $main_user_data['name']; ?>" readonly="readonly" />-->
            <label><strong><?php echo $main_user_data[0]['name'];?></strong> </label>
            </td>
            
            <td  align="right"><strong>Company Name*:</strong></td>
            <td align="center"><input type="hidden" name="company" id="TxtCompany" readonly value="<?echo $commpany[0]["company"];?>"/>
                <label><strong><?php echo $commpany[0]["company"];;?></strong> </label></td>
                
            <td  align="right"><strong>Registration No*:</strong></td>
            <td align="center"> <!--<input type="text" name="Txtveh_reg" id="Txtveh_reg" value="<?echo $veh_reg?>" readonly="readonly"/> --> 
                    <label><strong><?php echo $veh_reg;?></strong> </label></td>
        </tr>
        
        <tr>
            
            <td  align="right"><strong>Device IMEI*:</strong></td>
            <td align="center"><!--<input type="text" name="TxtDeviceIMEI" id="TxtDeviceIMEI" value="<?echo $TxtDeviceIMEI?>" readonly="readonly"/>-->
                <label><strong><?php echo $TxtDeviceIMEI;?></strong> </label></td>

            <td  align="right"><strong>Date Of Installation*:</strong></td>
            <td align="center"><!--<input type="text" name="date_of_install" id="date_of_install" readonly value="<?echo $date_of_install?>"/>-->
                <label><strong><?php echo $date_of_install;?></strong> </label></td>
            
            <td  align="right"><strong>Not working*: </strong></td>
            <td align="center"><!--<input type="text" name="Notwoking" id="Notwoking" readonly value="<?echo $Notwoking?>"/>-->
                <label name="Notwoking" id="Notwoking"><strong><?php echo $Notwoking;?></strong> </label></td>
       </tr>
       <!--<tr>
          <td  align="right"><strong>Device Model*:</strong></td>
          <td align="center"> <label><strong><?php echo $Device_model;?></strong> </label></td>
          <td colspan="4"></td>
       </tr>-->
   </table>
   
   <table style=" padding-left: 100px;width: 600px;" cellspacing="2" cellpadding="3">

        <!-- <tr>
            <td width="112"  align="right">Date*:</td>
            <td width="167">
           <input type="text" name="date" id="datepicker1" readonly value="<?echo $date;?>" /></td>
        </tr>

        <tr>
            <td  align="right">Request By*:</td>
            <td>
                <input type="text" name="account_manager" id="TxtAccManager" readonly value="<?echo $account_manager?>"/></td>
        </tr>-->
        
       <tr>
               <td colspan="2">&nbsp;</td>
       </tr> 

        <tr>
            <td  align="right">Job:</td>
            <td><input type="radio" name="service_reinstall"  value="service" id="service" <?php if($rows['service_reinstall']=='service') {echo "checked=\"checked\""; }?> /> Service 
            <input type="radio" name="service_reinstall" value="re_install" id="service" <?php if($rows['service_reinstall']=='re_install') {echo "checked=\"checked\""; }?> /> Re-Install </td>
        </tr>
        
        <tr>
            <td  align="right">Required:</td>
            <td><input type="checkbox" name="required" id="required" value="urgent"  <?php if($required=='urgent') {?> checked="checked" <? }?>/> Urgent 
                <input type="checkbox" name="IP_Box" id="IP_Box" value="required" <?php if($IP_Box=='required') {?> checked="checked" <? }?> /> IP Box
            </td>
            
        </tr>

        <tr>
            <td  align="right"> Area:*</td>
            <td> <input type="text" name="Zone_area" id="Zone_area" value="<?php echo $area["name"];?>" /> <div id="ajax_response"></div></td>
        </tr> 
        
        <tr>
          <td  align="right">Branch </td>
          <td>
          <?php $branch = mysql_query("select * from tbl_city_name where branch_id='".$_SESSION['BranchId']."'",$dblink2);
                  $branch_data = mysql_fetch_array($branch);
          ?>
          <Input type='radio' Name ='inter_branch' id='inter_branch' value= 'Samebranch' <?php if($rows['branch_type']=='Samebranch'){echo "checked=\"checked\""; }?> onchange="StatusBranch(this.value);"> <?php echo $branch_data["city"];?>
          
          <Input type='radio' Name ='inter_branch' id='inter_branch' value= 'Interbranch' <?php if($rows['branch_type']=='Interbranch'){echo "checked=\"checked\""; }?>
        onchange="StatusBranch(this.value);"> Inter Branch
            </td>
            <td colspan="2">
                
                <table  id="samebranchid"  align="left"  style="width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>        
                        <td  align="right">Location:*</td>
                        <td  ><input type="text" name="location"  id="location"   style="width:147px" value="<?echo $location?>"/></td>
                    </tr>
                </table>
                
                <table  id="branchlocation"  align="left"  style="width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>
                        <td  align="right">Branch Location</td>
                        <td>
                          <select name="inter_branch_loc" id="inter_branch_loc">
                                <option value="" >-- Select One --</option>
                                <?php
                                $city1=mysql_query("select * from tbl_city_name where branch_id!='".$_SESSION['BranchId']."'",$dblink2);
                                while($data=mysql_fetch_assoc($city1))
                                {
                                    if($data['branch_id']==$city)
                                    {
                                        $selected="selected";
                                    }
                                    else
                                    {
                                        $selected="";
                                    }
                                ?>
                                
                                <option value ="<?php echo $data['branch_id'] ?>"  <?echo $selected;?>>
                                <?php echo $data['city']; ?>
                                </option>
                                <?php 
                                } 
                                
                                ?>
                          </select>
                         </td>
                     </tr>
                </table>
            </td>
        </tr>
        
       <!-- <tr>
            <td colspan="2">
                
                <table  id="samebranchid"  align="left"  style="padding-left: 65px;width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>        
                        <td  align="right">Location:*</td>
                        <td  ><input type="text" name="location"  id="location"   style="width:147px" value="<?echo $location?>"/></td>
                    </tr>
                </table>
                
                <table  id="branchlocation"  align="left"  style="padding-left: 40px;width: 300px;display:none;border:1" cellspacing="5" cellpadding="5">
                    <tr>
                        <td  align="right">Branch Location</td>
                        <td>
                          <select name="inter_branch_loc" id="inter_branch_loc">
                                <option value="" >-- Select One --</option>
                                <?php
                                $city1=mysql_query("select * from tbl_city_name where branch_id!='".$_SESSION['BranchId']."'",$dblink2);
                                while($data=mysql_fetch_assoc($city1))
                                {
                                    if($data['branch_id']==$city)
                                    {
                                        $selected="selected";
                                    }
                                    else
                                    {
                                        $selected="";
                                    }
                                ?>
                                
                                <option value ="<?php echo $data['branch_id'] ?>"  <?echo $selected;?>>
                                <?php echo $data['city']; ?>
                                </option>
                                <?php 
                                } 
                                
                                ?>
                          </select>
                         </td>
                     </tr>
                </table>
            </td>
        </tr>-->
        
        <!-- <tr>
        <td  align="right"> Area:*</td>
        <td> 
        
        <select name="Zone_area" id="Zone_area" >
        <option value="" >-- Select One --</option>
        <?php
       // $main_city=mysql_query(" select id,name from re_city_spr_1 where region_code='".$_SESSION['BranchId']."'");
        
        //$main_city=mysql_query(" select id,name from re_city_spr_1 ORDER BY name ASC",$dblink2);
        while($data=mysql_fetch_assoc($main_city))
        {
            if($data['id']==$Zone_area)
            {
                $selected="selected";
            }
            else
            {
                $selected="";
            }
        ?>
        
        <option value ="<?php echo $data['id'] ?>"  <?echo $selected;?>>
        <?php echo $data['name']; ?>
        </option>
        <?php 
        } 
        
        ?>
        </select>
        
        
        
        </td>
        </tr> -->
        
        <tr>
          <td  align="right">Device Model*:</label></td>
          <td>
            <select name="Device_model" id="Device_model">
            <option value="">-- Select One --</option>
            <?php
            
            $device_type=mysql_query("SELECT * FROM `device_type`",$dblink2);
            while ($data=mysql_fetch_assoc($device_type))
            {
                if($data['device_type']==$Device_model)
                {
                    $selected="selected";
                }
                else
                {
                    $selected="";
                }
            ?>
            
            <option name="Device_model" value="<?php echo $data['device_type'] ?>"  <?echo $selected?>  >
            <?php echo $data['device_type']; ?>
            </option>
            <?php 
            } 
            
            ?>
            </select></td>
        </tr>
        
        <tr>
            <td align="right">Availbale Time status:*</td>
            <td>
                <select name="atime_status" id="atime_status" style="width:150px" onchange="TillBetweenTime(this.value)">
                    <option value="">Select Status</option>
                    <option value="Till" <? if($atime_status=='Till') {?> selected="selected" <? } ?> >Till</option>
                    <option value="Between" <? if($atime_status=='Between') {?> selected="selected" <? } ?> >Between</option>
                </select>
            </td>
            <td colspan="2">
                
                <table  id="TillTime" align="left" style="width: 240px;display:none;border:1"  cellspacing="5" cellpadding="5">
                        <tr>
                            <td height="32" align="right">Time:*</td>
                            <td>
                                 <input type="text" name="time" id="datetimepicker" value="<?=$rows['atime']?>" style="width:147px"/>
                                   
                             </td>
                        </tr>
                </table>
                
                <table  id="BetweenTime" align="left" style="width: 240px;display:none;border:1"  cellspacing="5" cellpadding="5">
                        <tr>
                            <td height="32" align="right">From Time:*</td>
                            <td>
                                 <input type="text" name="time1" id="datetimepicker1" value="<?=$rows['atime']?>" style="width:147px"/>
                                   
                             </td>
                        </tr>
                        <tr>
                            <td height="32" align="right">To Time:*</td>
                            <td>
                             <input type="text" name="totime" id="datetimepicker2" value="<?=$rows['atimeto']?>" style="width:147px"/>
                               
                             </td>
                        </tr>
                </table>
             </td>
        </tr>
        
       <!-- <tr>
            <td colspan="2" align="right">
                
                <table  id="TillTime" align="left" style="padding-left: 80px;width: 240px;display:none;border:1"  cellspacing="5" cellpadding="5">
                        <tr>
                        <td height="32" align="right">Time:*</td>
                        <td>
                             <input type="text" name="time" id="datetimepicker" value="<?=$rows['atime']?>" style="width:147px"/>
                               
                             </td>
                        </tr>
                </table>
             </td>
        </tr>

        <tr>
            <td colspan="2" align="right">
                    
                <table  id="BetweenTime" align="left" style="padding-left: 80px;width: 240px;display:none;border:1"  cellspacing="5" cellpadding="5">
                        <tr>
                        <td height="32" align="right">From Time:*</td>
                        <td>
                             <input type="text" name="time1" id="datetimepicker1" value="<?=$rows['atime']?>" style="width:147px"/>
                               
                             </td>
                        </tr>
                        <tr>
                        <td height="32" align="right">To Time:*</td>
                        <td>
                             <input type="text" name="totime" id="datetimepicker2" value="<?=$rows['atimeto']?>" style="width:147px"/>
                               
                             </td>
                        </tr>
                </table>
             </td>
      </tr>-->

<!--<tr>
  <td  align="right">Available Time:*</td>
<td>
     <input type="text" name="datetimepicker" id="datetimepicker"   style="width:147px" autocomplete="off" readonly value="<?echo $atime?>"/> </td> </tr>
    -->

    <tr>
        <td  align="right">Person Name:*</td>
        <td>    <input type="text" name="pname" id="pname"   style="width:147px" value="<?echo $pname?>"/>  </td>
        
    </tr>
    <tr>
        <td  align="right">Contact No:*</td>
        <td ><input value="<?echo $cnumber?>" type="text" name="cnumber"   style="width:147px" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/></td>
    </tr>
    
    
<!--    <tr>
        <td  align="right">IP Box:</td>
        <td><input type="checkbox" name="IP_Box" id="IP_Box" value="required" <?php if($IP_Box=='required') {?> checked="checked" <? }?> /> Required </td>
    </tr>-->
    
    
    
    <tr>  
        <td  align="right">Comment</td>
        <td> <textarea rows="5" cols="25"  type="text" name="TxtComment" id="TxtComment" ><?echo $comment?></textarea>  </td>
  
    </tr>
    
    <tr>
         <td>&nbsp; </td>
         <td colspan="2"><input type="submit" name="submit" value="submit" align="right" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="Cancel" value="Cancel" onClick="window.location = 'services.php' " /> </td>
    </tr>

</table>
</form>
 
<?
include("../include/footer.inc.php");

?>
<script>StatusBranch12("<?=$rows['branch_type'];?>");TillBetweenTime12("<?=$rows['atime_status'];?>");</script>