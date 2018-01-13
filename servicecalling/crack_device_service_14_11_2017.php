<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/format/include/leftmenu_service.php");*/

?><!-- 
<link  href="<?php echo __SITE_URL;?>/css/auto_dropdown.css" rel="stylesheet" type="text/css" /> -->
<!-- <link href="<?php echo __SITE_URL;?>/js/Interbranchjquery.multiselect.css" rel="stylesheet" type="text/css"> -->
<!-- <link rel="stylesheet" type="text/css" href="<?php echo __SITE_URL;?>/build/jquery.datetimepicker.css"/>
<script src="<?php echo __SITE_URL;?>/build/jquery.datetimepicker.full.js"></script>

<script src="<?php echo __SITE_URL;?>/js/jquery.min.js"></script> -->
<!-- <script src="<?php echo __SITE_URL;?>/js/jquery.multiselect.js"></script> -->

<link  href="<?php echo __SITE_URL;?>/css/auto_dropdown.css" rel="stylesheet" type="text/css" />
<!-- <link href="<?php echo __SITE_URL;?>/js/Interbranchjquery.multiselect.css" rel="stylesheet" type="text/css"> -->
<link rel="stylesheet" type="text/css" href="<?php echo __SITE_URL;?>/build/jquery.datetimepicker.css"/>
<script src="<?php echo __SITE_URL;?>/build/jquery.datetimepicker.full.js"></script>
<script src="<?php echo __SITE_URL;?>/js/jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){

    $("#hide").click(function(){
        $("#acn").hide();
    });
    $("#show").click(function(){
        $("#acn").show();
    });

   // Designation Hide Show

  var counter=0;

  $("#delRowss").click(function(){
      $("#dataDesignation").hide();
      $("#designation2").val('');
      $("#contact_person2").val('');
      $("#contact_number2").val('');
      if($("#dataDesignation").hide()){ --counter; }
      //alert(counter)
      if(counter < 0){alert("Atleast ")}

  });
  $("#addRowss").click(function(){
      $("#dataDesignation").show();
      if($("#dataDesignation").show()){ ++counter; }
      if(counter > 1){alert("No More Add Contacts")}
  });

  // End Designation Hide Show

});
// function selectAllAccessory(source) {
//     var checkboxes = document.getElementsByName('accessories[]');
//     for(var i in checkboxes)
//       checkboxes[i].checked = source.checked;
//   }
  
  


/*Start auto ajax value load code*/
 var $s = jQuery.noConflict();
$s(document).ready(function(){
    $s(document).click(function(){
        $s("#ajax_response").fadeOut('slow');
    });



    $s("#required").focus();

    var offset = $s("#Zone_area").offset();
    var width = $s("#Zone_area").width()-2;
    $s("#ajax_response").css("left",offset);
    $s("#ajax_response").css("width","15%");
    $s("#ajax_response").css("z-index","1");
    $s("#Zone_area").keyup(function(event){
         //alert(event.keyCode);
         var keyword = $s("#Zone_area").val();
         var city_id= $s("#inter_branch_loc").val();
         var inter_branch= $s("#inter_branch").val();
         //alert(keyword); 
          //alert(inter_branch);
         if(city_id=='' || city_id==0)
         {
            city_id=1;
         }
         //alert(city_id);
         if(keyword.length)
         {
             if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13)
             {
                 $s("#loading").css("visibility","visible");
                 $s.ajax({
                   type: "POST",
                   url: "load_zone_area.php",
                   data: "data="+keyword+"&city_id="+city_id,
                   success: function(msg){  
                  // alert(msg); 
                    if(msg != 0)
                      $s("#ajax_response").fadeIn("slow").html(msg);
                    else
                    {
                      $s("#ajax_response").fadeIn("slow");   
                      $s("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
                    }
                    $s("#loading").css("visibility","hidden");
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
                      $s("li").each(function(){
                         if($s(this).attr("class") == "selected")
                            found = 1;
                      });
                      if(found == 1)
                      {
                        var sel = $s("li[class='selected']");
                        sel.next().addClass("selected");
                        sel.removeClass("selected");
                      }
                      else
                        $s("li:first").addClass("selected");
                     }
                 break;
                 case 38:
                 {
                      found = 0;
                      $s("li").each(function(){
                         if($s(this).attr("class") == "selected")
                            found = 1;
                      });
                      if(found == 1)
                      {
                        var sel = $s("li[class='selected']");
                        sel.prev().addClass("selected");
                        sel.removeClass("selected");
                      }
                      else
                        $s("li:last").addClass("selected");
                 }
                 break;
                 case 13:
                    $s("#ajax_response").fadeOut("slow");
                    $s("#Zone_area").val($s("li[class='selected'] a").text());
                 break;
                }
             }
         }
         else
            $s("#ajax_response").fadeOut("slow");
    });
    $s("#ajax_response").mouseover(function(){
        $s(this).find("li a:first-child").mouseover(function () {
              $s(this).addClass("selected");
        });
        $s(this).find("li a:first-child").mouseout(function () {
              $s(this).removeClass("selected");
        });
        $s(this).find("li a:first-child").click(function () {
              $s("#Zone_area").val($s(this).text());
              $s("#ajax_response").fadeOut("slow");
        });
    });

     $s('.checkbox1').on('change', function() {
     var bool = $s('.checkbox1:checked').length === $s('.checkbox1').length;  
      $s('#acess_all').prop('checked', bool);       
       }); 
       $s('#acess_all').on('change', function() {    
       $s('.checkbox1').prop('checked', this.checked);      
      });






});
/* End auto ajax value load code*/
</script>
<?php
$Header="Add Crack Request";

$date=date("Y-m-d H:i:s");
$account_manager=$_SESSION['username'];

   
?>

<div class="top-bar">
  <h1><? echo $Header;?></h1>
</div>
<div class="table">
<?


if(isset($_POST['submit']))
{ 
   //echo '<pre>'; print_r($_POST);die;
    
    $date=date("Y-m-d H:i:s");
    $account_manager=$_SESSION['username'];
    $sales_person=trim($_POST['sales_person']);
    $sales_manager = select_query("select id as sales_id from sales_person where name='".$sales_person."' limit 1");
    $sales_person_id=$sales_manager[0]['sales_id'];
    $main_user_id=$_POST['main_user_id'];
    $company=$_POST['company'];
    $no_of_vehicals=$_POST['no_of_vehicals'];
    //$location=$_POST['location'];
    //$model=$_POST['model'];
    $veh_reg=$_POST['veh_reg'];
    $designation=$_POST['designation'];
    $alt_designation=$_POST['designation2'];
    $contact_person=$_POST['contact_person'];
    $alt_cont_person=$_POST['contact_person2'];
    $contact_number=$_POST['contact_number'];
    $alt_cont_number=$_POST['contact_number2'];
    $atime_status=$_POST['atime_status'];
    $back_reason=$_POST['back_reason'];
    $instal_reinstall = $_POST['instal_reinstall'];
    $comment=$_POST['TxtComment'];
    $service_reinstall = 'crack';
    //$accessories_tollkit="";
    // $veh_type=$_POST['veh_type'];
    // $del_nodelux=$_POST['standard'];
    // $actype=$_POST['actype'];
    // $TruckType=$_POST['TruckType'];
    // $TrailerType=$_POST['TrailerType'];
    // $MachineType=$_POST['MachineType'];
    // $billing = $_POST['billing'];
    // //$delnoDelux = $_POST['delnoDelux'];
    //$luxury = $_POST['lux'];
	

  
   //  $acess_selection = $_POST['access_radio'];
   //  if($acess_selection='yes')
   //  {
   //    for($i=0;$i<count($_POST['accessories']);$i++)
   //    {
   //      $accessories_tollkit.=$_POST['accessories'][$i]."#";
   //      $accessories_tollkits=substr($accessories_tollkit,0,strlen($accessories_tollkit)-1);
   //    }
   //  }
   // else
   // {
   //    $accessories_tollkits="";
   // }

     $branch_type = $_POST['inter_branch'];
     $service_reinstall="crack";
    // if($instal_reinstall == "installation")
    // {
    //     $installation_status=8;
    // }
    // elseif($instal_reinstall == "crack")
    // {
    //     $installation_status=1;
    // }
    // elseif($instal_reinstall == "re_install")
    // {
    //     $installation_status=1;
    // }
    // else
    // {
    //     $installation_status=8;
    // }
    $sql=mysql_query("SELECT UserName AS sys_username FROM addclient  WHERE Userid='$main_user_id'",$dblink2);
	$row=mysql_fetch_array($sql);
	$username=$row['sys_username'];

    $Zone_data = select_query("SELECT id,`name` FROM re_city_spr_1 WHERE `name`='".$_POST['Zone_area']."'");
    $zone_count = count($Zone_data);
    if($zone_count > 0)
    {
        $Area = $Zone_data[0]["id"];
        $errorMsg = "";
    }
    else
    {
        $errorMsg = "Please Select Type View Listed Area. Not Fill Your Self.";
    }
    if($branch_type == "Interbranch"){
        $city=$_POST['inter_branch_loc'];
       // $location="";
    }else{
        $city=0;
        //$location=$_POST['location'];
    }
    
    $location=$_POST['location'];
    $location1=$_POST['inter_branch'];
    $interbranch = $_POST['inter_branch_loc'];

    if($location1 == 'Interbranch')
    {
      $query = select_query("select city from tbl_city_name where branch_id='".$interbranch."'");
      $branchLocation = $query[0]['city'];
    }
    else
    {
      $branchLocation = "Delhi";
    }

    $required=$_POST['required'];
    if($required=="") 
    {
        	 $required="normal"; 
    }
       
        $datapullingtime=$_POST['datapullingtime'];
       // echo $installation_status; die;
    if($errorMsg=="")   
    { 
      if($atime_status=="Till")
      {
      
               $time=date('Y-m-d H:i:s',strtotime($_POST['time']));  
          
             
                 $sql="INSERT INTO `services_crack` (`req_date`, `request_by`, `name`, `user_id`, `company_name`, `veh_reg`, `location`, `atime`, `pname`,atime_status, `cnumber`, `comment`,`required`,branch_id,service_status,Zone_area,service_reinstall,`inter_branch`,alt_cont_person,alt_designation,alter_contact_no,branch_type,designation,no_of_vehicals,sales_person) VALUES ('".$date."','".$account_manager."', '".$username."', '".$main_user_id."', '".$company."', '".$veh_reg."', '".$location."', '".$time."','".$contact_person."','".$atime_status."', '".$contact_number."', '".$comment."','".$required."','".$_SESSION['BranchId']."',1,'".$Area."','".$service_reinstall."','".$city."','".$alt_cont_person."','".$alt_designation."','".$alt_cont_number."','".$branch_type."','".$designation."','".$no_of_vehicals."','".$sales_person_id."');";
                 //echo $sql; die;
			
					$execute=mysql_query($sql);
					header("location:ser_request_list.php");
             
           
             
      }
           
        if($atime_status=="Between")
        {
              $time=date('Y-m-d H:i:s',strtotime($_POST['time1']));  
              $totime=date('Y-m-d H:i:s',strtotime($_POST['totime']));  
           
               $sql="INSERT INTO `services_crack` (`req_date`, `request_by`, `name`, `user_id`, `company_name`, `veh_reg`, `location`, `atime`,`atimeto`,`pname`,atime_status, `cnumber`, `comment`,`required`,branch_id,service_status,Zone_area,service_reinstall,`inter_branch`,alt_cont_person,alt_designation,alter_contact_no,branch_type,designation,no_of_vehicals,sales_person) VALUES ('".$date."','".$account_manager."', '".$username."', '".$main_user_id."', '".$company."', '".$veh_reg."', '".$location."', '".$time."','".$totime."','".$contact_person."','".$atime_status."', '".$contact_number."', '".$comment."','".$required."','".$_SESSION['BranchId']."',1,'".$Area."','".$service_reinstall."','".$city."','".$alt_cont_person."','".$alt_designation."','".$alt_cont_number."','".$branch_type."','".$designation."','".$no_of_vehicals."','".$sales_person_id."');";
                 //echo $sql; die;
			
					$execute=mysql_query($sql);
					header("location:ser_request_list.php");
             
                 
        }
      }
}
?>

<script type="text/javascript">
var mode;
function req_info()
{
  
  // var instal_reinstall=document.forms["form1"]["instal_reinstall"].value;
  // if (instal_reinstall==null || instal_reinstall=="")
  // {
  // alert("Please Select Job") ;
  // return false;
  // }

  if(document.form1.sales_person.value=="")
  {
  alert("Please Select Sales Person Name") ;
  document.form1.sales_person.focus();
  return false;
  }
 
  if(document.form1.main_user_id.value=="")
  {
  alert("Please Select Client Name") ;
  document.form1.main_user_id.focus();
  return false;
  }
  
  if(document.form1.no_of_vehicals.value=="")
  {
  alert("Please Select No Of Installation") ;
  document.form1.no_of_vehicals.focus();
  return false;
  }
  // if(document.form1.deviceType.value=="")
  // {
  //       alert("Please Enter Device") ;
  //       document.form1.deviceType.focus();
  //       return false;
  // }
  // if(document.form1.model.value=="")
  // {
  //       alert("Please Enter Model") ;
  //       document.form1.model.focus();
  //       return false;
  // }
  // var acc=document.forms["form1"]["acc"].value;
  // if (acc==null || acc=="")
  // {
  //     alert("Please Select Accessories Button") ;
  //     return false;
  // }
  // if(document.form1.access_radio.value == 'yes')
  // {

  // var accessories = document.getElementsByName("accessories[]");
  // var acc_len = $('[name="accessories[]"]:checked').length;
    
  //   if(acc_len == '' || acc_len == '0')
  //   {
  //     alert('Please Select Atleast One Accessories'); 
  //     //document.form1.accessories.focus();
  //     return false; 
      
  //   } 

  // }

    var inter_branch=document.forms["form1"]["inter_branch"].value;
    if (inter_branch==null || inter_branch=="")
    {
      alert("Please Select Branch Button") ;
      return false;
    }

    if(inter_branch=='Interbranch')
    {
      var interbranch=document.forms["form1"]["inter_branch_loc"].value;
      if(interbranch==null || interbranch=="")
      {
          alert("Please select branch location");
          document.form1.inter_branch_loc.focus();
          return false;
      }
    }
    
    if(document.form1.Zone_area.value=="")
    {
      alert("Please Select Area") ;
      document.form1.Zone_area.focus();
      return false;
    }
    var location=document.forms["form1"]["location"].value;
    if (location==null || location=="")
    {
        alert("Please Enter location");
        document.form1.location.focus();
        return false;
    }

    var timestatus=document.forms["form1"]["atime_status"].value;
    if (timestatus==null || timestatus=="")
      {
          alert("Please select Availbale Time");
          document.form1.atime_status.focus();
          return false;
      }

    var tilltime=document.forms["form1"]["datetimepicker"].value;  
      if(timestatus == "Till" && (tilltime==null || tilltime==""))  
      {     
           alert("Please select Time");    
            document.form1.datetimepicker.focus();  
            return false;   
      } 
      else
      {
        var inputTime = new Date(tilltime).getTime();  
        var time=(inputTime/(3600*1000));    
        var d = new Date(); 
        var n = d.getTime();       
        var currntTime4=(n/(3600*1000));     
        var diff=time-currntTime4;   
        if(timestatus=="Till")
        {       
          if(diff<=3.80)   
          {  
              alert('Please enter 4 hour difference for available time');  
              document.form1.datetimepicker.focus();   
              return false;    
          }
        }

      }  

    var betweentime=document.forms["form1"]["datetimepicker1"].value;
    var betweentime2=document.forms["form1"]["datetimepicker2"].value;
    if(timestatus == "Between" && (betweentime==null || betweentime==""))
    {
        alert("Please select From Time");
        document.form1.datetimepicker1.focus();
        return false;
    }
  
    if(timestatus == "Between" && (betweentime2==null || betweentime2==""))
    {
        alert("Please select To Time");
        document.form1.datetimepicker2.focus();
        return false;
    }
    else
    {
        var inputTime = new Date(betweentime).getTime();  
        var time=(inputTime/(3600*1000));    
        var d = new Date(); 
        var n = d.getTime();       
        var currntTime4=(n/(3600*1000));     
        var diff=time-currntTime4;  
        //alert(diff); 
        if(timestatus=="Between")
        {  
          //alert('tt');     
          if(diff<=3.80)   
          {  
              alert('Please enter 4 hour difference for available time');  
              document.form1.datetimepicker1.focus();   
              return false;    
          }
        }
    }
  

    var designation=document.forms["form1"]["designation"].value;
    if(designation=="")
    {
      alert("Please Select Designation.") ;
       document.form1.designation.focus();
      //document.getElementById(designation).focus();
      return false;
    }
    var contact_person=document.forms["form1"]["contact_person"].value;
    if(contact_person=="")
    {
      alert("Please Select Contact Person.") ;
       document.form1.contact_person.focus();
      //document.getElementById(designation).focus();
      return false;
    }
    var contact_number=document.forms["form1"]["contact_number"].value;
    if(contact_number=="")
    {
      alert("Please Select Contact Number.") ;
       document.form1.contact_number.focus();
      //document.getElementById(designation).focus();
      return false;
    }
    var veh_type=document.forms["form1"]["veh_type"].value;
    if(document.form1.veh_type.value=="")
    {
        alert("Please Select Vehicle Type") ;
        document.form1.veh_type.focus();
        return false;
    }
    // if(document.getElementById(contact_person).value=="")
    // {
    //   alert("Please Write Contact Person Name.") ;
    //   document.getElementById(contact_person).focus();
    //   return false;
    // }
    // if(document.getElementById(contact_number).value=="")
    // {
    //   alert("Please Write Contact Number") ;
    //   document.getElementById(contact_number).focus();
    //   return false;
    // }
 
    if(d.getHours() >= 18)
    {  
      if(d.getMinutes() > 00)      
      alert("Request raise only before 6 PM")   
      return false;  
    } 
 
   
  //var Dtable = document.getElementById('dataTable');
  //var DrowCount = Dtable.rows.length;
    //alert(DrowCount);
  // for(var m=0; m<DrowCount; m++)
  // {
  //   if(m == 0)
  //   {
  //     var fcounter = 0;
  //     var contact_person = 'contact_person';
  //     var contact_number = 'contact_number';
  //     var designation = 'designation';
  //   }
  //   else
  //   {
  //     var fTxtDeviceType = 'contact_person'+fcounter;
  //     var contact_number = 'contact_number'+fcounter;
  //     var designation = 'designation'+fcounter;
  //   }
  //   if(document.getElementById(designation).value=="")
  //   {
  //     alert("Please Select Designation.") ;
  //     document.getElementById(designation).focus();
  //     return false;
  //   }
  //   if(document.getElementById(contact_person).value=="")
  //   {
  //     alert("Please Write Contact Person Name.") ;
  //     document.getElementById(contact_person).focus();
  //     return false;
  //   }
  //   if(document.getElementById(contact_number).value=="")
  //   {
  //     alert("Please Write Contact Number") ;
  //     document.getElementById(contact_number).focus();
  //     return false;
  //   }
  // }

  
  
}
   
function setVisibility(id, visibility)
{
    document.getElementById(id).style.display = visibility;
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
  //alert(radioValue)
   if(radioValue=="Interbranch")
    {
        document.getElementById('branchlocation').style.display = "block";
    }
    else if(radioValue=="Samebranch")
    {
        document.getElementById('branchlocation').style.display = "none";
    }
    else
    {
        document.getElementById('branchlocation').style.display = "none";
    }
   
} 

// function showAccess(radioValue)
// {
//   //alert(radioValue)
//    if(radioValue=="yes")
//     {
//         document.getElementById('accessTable').style.display = "block";
//     }
//     else if(radioValue=="no")
//     {
//         document.getElementById('accessTable').style.display = "none";
//     }
//     else
//     {
//         document.getElementById('accessTable').style.display = "none";
//     }
   
// }
          

function StatusBranch12(radioValue)
{
  //alert(radioValue)
   if(radioValue=="Interbranch")
    {
        document.getElementById('branchlocation1').style.display = "block";
    }
    else if(radioValue=="Samebranch")
    {
        document.getElementById('branchlocation').style.display = "none";
    }
    else
    {
        document.getElementById('branchlocation').style.display = "none";
        //document.getElementById('samebranchid').style.display = "none";
    }
   
}  

</script> 
  <!-- <script type="text/javascript">

        $(function () {
             
            $("#datetimepicker").datetimepicker({});
            $("#datetimepicker1").datetimepicker({});
            $("#datetimepicker2").datetimepicker({});
            $("#datetimepicker3").datetimepicker({});

           

        });

    </script>  -->

<?php echo "<p align='left' style='padding-left: 250px;width: 500px;' class='message'>" .$errorMsg. "</p>" ; ?>
  
<style type="text/css" >
.errorMsg{border:1px solid red; }
.message{color: red; font-weight:bold; }
body { font-family:'Open Sans' Arial, Helvetica, sans-serif}
ul,li { margin:0; padding:0; list-style:none;}
.label { color:#000; font-size:16px;}
td{
  white-space: nowrap;

}
</style>

 <form method="post" action="" name="form1" autocomplete="off" onSubmit="return req_info(); ">
    <table style="padding-left: 100px;width: 500px;" cellspacing="5" cellpadding="5">
      <tr>
        <td colspan="2"><input type="hidden" name="back_reason" id="back_reason" value="<?php echo $result['back_reason']?>"/></td>
      </tr>
   <!--    
    <tr>
        <td  align="right">Job:<font color="red">* </font></td>

        <td><input type="radio" name="instal_reinstall"  value="installation" id="instal_reinstall" <?php if($result['instal_reinstall']=='installation') {echo "checked=\"checked\""; }?> />
          New
          <input type="radio" name="instal_reinstall" value="crack" id="instal_reinstall" <?php if($result['instal_reinstall']=='crack') {echo "checked=\"checked\""; }?> />
          Crack </td>
    </tr> -->
     <tr>
        <td align="right"> Client User Name:<font color="red">* </font> </td>
        <td nowrap>
          <select style="width:150px;" name="main_user_id" id="main_user_id" onchange="showUser(this.value,'ajaxdata'); getCompanyName(this.value,'TxtCompany'); getSalesPersonName(this.value,'TxtSalesPersonName');" />
            <option value="" >-- Select One --</option>
            <?php

              $main_user_iddata=select_query("SELECT Userid AS user_id,UserName AS `name` FROM addclient WHERE sys_active=1 ORDER BY `name` asc");
              
              for($u=0;$u<count($main_user_iddata);$u++)
              {
                if($main_user_iddata[$u]['user_id']==$result['user_id'])
                {
                  $selected="selected";
                }
                else
                {
                  $selected="";
                }
            ?>
            <option value ="<?php echo $main_user_iddata[$u]['user_id'] ?>"  <?echo $selected;?>> <?php echo $main_user_iddata[$u]['name']; ?> </option>
            <?php
              }
            ?>
          </select>
        </td>
      </tr>
       
      <tr>
        <td  align="right">Urgent: </td>
        <td>
       <input type="checkbox" name="required" id="required" value="urgent"/>
       </td>
          
      </tr>
       <tr>
        <td  align="right">Sales Person:<font color="red">* </font></td>
        <td><input type="text" name="sales_person" id="TxtSalesPersonName" readonly /></td>
      </tr>
      
      <tr>
        <td  align="right">Company Name:</td>
        <td><input type="text" name="company" id="TxtCompany" readonly value="<?=$result['company_name']?>"/></td>
      </tr>
      
      <tr>
        <td height="32" align="right">No. Of Devices:<font color="red">* </font></td>
        <td><select name="no_of_vehicals" id="no_of_vehicals" style="width:150px">
            <option value="">Select Device</option>
            <?             
      for($inlp=1;$inlp<=50;$inlp++){
             ?>
            <option value="<?=$inlp;?>" <? if($result['no_of_vehicals']==$inlp) {?> selected="selected" <? } ?> >
            <?=$inlp?>
            </option>
            <? } ?>
          </select>
        </td>
      </tr>


      <tr>
        <td  align="right"> Registration No:</td>
        <td><input type="text" name="veh_reg" id="Txtveh_reg" value="<?echo $veh_reg?>"/></td>
      </tr>
      

<!-- 
    <tr id="deviceTyp">
        <td height="32" align="right">Device Type:<font color="red">* </font></td>
        <td>
          <select name="deviceType" value="" id="deviceType" style="width:150px" onchange="getModelName(this.value,'modelName');">
          
          </select>
        </td>
      </tr> -->

<!--       <tr id="deviceMdl">
        <td height="32" align="right">Model:<font color="red">* </font></td>
        <td>
          <select name="model" id="modelName" style="width:150px">

          </select>
        </td>
    </tr> -->

<!-- 
    <tr>
        <td  align="right">Accessories:<font color="red">* </font></td>
        <td><?php $branch_data = select_query("select * from tbl_city_name where branch_id='".$_SESSION['BranchId']."'"); ?>
          <input type='radio' Name ='access_radio' id='acc' value= 'yes' <?php //if($result['branch_type']=='Samebranch'){echo "checked=\"checked\""; }?> onchange="showAccess(this.value);">
          Yes
          <input type='radio' Name ='access_radio' id='acc' value= 'no' <?php// if($result['branch_type']=='Interbranch'){echo "checked=\"checked\""; }?>
          onchange="showAccess(this.value);">
          No 
        </td>
    </tr> -->

<!-- 
      <tr>
        <td></td>
        <td>
          <table  id="accessTable"  align="right"  style="width: 100%;display:none;border: 1px solid #000" cellspacing="5" cellpadding="5">
        </td>
      </tr>
 -->
     <!-- <tr>
        <td height="32" align="right"></td>
        <td><select name="accessories[]" multiple id="accessories" style="width:200px" onchange="();" >
            <option value="">Select Accessories</option>
            <?
            $accessory_data=select_query("SELECT id,items AS `access_name` FROM toolkit_access   ORDER BY `access_name` asc");
            //while($data=mysql_fetch_array($query)) 
      for($v=0;$v<count($accessory_data);$v++)
      {
             ?>
            <option value="<?=$accessory_data[$v]['id']?>" ><?=$accessory_data[$v]['access_name']?>
            </option>
            <? } ?>
          </select></td>
      </tr>

      </table>
        </td>
      </tr> -->
 

      <tr>
        <td colspan="2">
          <table  id="accessTable"  align="right" style="margin-right:-20px;height: 20em; width: 30em; overflow: auto;display:none;border:1px solid #000;" cellspacing="2" cellpadding="2">
          <td> <input type="checkbox" name="acess_all[]" id="acess_all" onClick="selectAllAccessory(this)"  style="width:150px;" /> Select All</td>
          <?
            $accessory_data=select_query("SELECT id,items AS `access_name` FROM toolkit_access   ORDER BY `access_name` asc");
            //while($data=mysql_fetch_array($query)) 

          for($v=0;$v<count($accessory_data);$v++)
          {
            ?>
                <tr>

                
                  <td><input type="checkbox" name="accessories[]" id="accessories" class="checkbox1"  value="<?php echo $accessory_data[$v]['id']; ?>" style="width:150px;">
                  <?=$accessory_data[$v]['access_name']?></td>    
                </tr>
              <?php
               }
          ?>  
          
          </table>
        </td>
      </tr>



      <tr>
        <td  align="right">Branch:<font color="red">* </font></td>
        <td><?php $branch_data = select_query("select * from tbl_city_name where branch_id='".$_SESSION['BranchId']."'"); ?>
          <input type='radio' Name ='inter_branch' id='inter_branch' value= 'Samebranch' onchange="StatusBranch(this.value);" checked="checked">
          <?php echo $branch_data[0]["city"];?>
          <Input type='radio' Name ='inter_branch' id='inter_branch' value= 'Interbranch' 
        onchange="StatusBranch(this.value);">
          Inter Branch 
        </td>
      </tr>

      <tr>
        <td colspan="2">
          <table  id="branchlocation"  align="right"  style="width: 100%;display:none;margin-left:-6px;" cellspacing="5" cellpadding="5">
            <tr>
              <td align="right" style="width: 18%;margin-right:-1px;">Branch Location:<font color="red">* </font></td>
              <td><select name="inter_branch_loc" id="inter_branch_loc" style="width:150px;">
                  <option value="" >-- Select One --</option>
                  <?php
                      $city1=select_query("select * from tbl_city_name where branch_id!='".$_SESSION['BranchId']."'");
                      //while($data=mysql_fetch_assoc($city1))
                      for($i=0;$i<count($city1);$i++)
                      {
                          if($city1[$i]['branch_id']==$result['inter_branch'])
                          {
                              $selected="selected";
                          }
                          else
                          {
                              $selected="";
                          }
                      ?>
                      <option value ="<?php echo $city1[$i]['branch_id'] ?>"  <?echo $selected;?>> <?php echo $city1[$i]['city']; ?> </option>
                      <?php
                      }
                      ?>
                </select>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td  align="right"> Area:<font color="red">* </font></td>
        <td><input type="text" name="Zone_area" id="Zone_area" value="<?php echo $area["name"];?>" />
          <div id="ajax_response"></div>
        </td>
      </tr>
      
     <tr>
        <td align="right"> LandMark:<font color="red">* </font></td>
        <td><input type="text" name="location"  id="location" value="<?=$result['location']?>" minlength="15"/></td>
    </tr> 
      
      <tr>
        <td align="right">Availbale Time status:<font color="red">* </font></td>
        <td><select name="atime_status" id="atime_status" style="width:150px" onchange="TillBetweenTime(this.value)">
            <option value="">Select Status</option>
            <option value="Till" <? if($result['atime_status']=='Till') {?> selected="selected" <? } ?> >Till</option>
            <option value="Between" <? if($result['atime_status']=='Between') {?> selected="selected" <? } ?> >Between</option>
          </select>
        </td>
      </tr>


      <tr>
        <td colspan="2">
          <table  id="TillTime" align="left" style="width:100%;display:none;margin-left:78px;"  cellspacing="5" cellpadding="5">
            <tr>
              <td height="32" align="right">Time:<font color="red">* </font></td>
              <td><input type="text" name="time" id="datetimepicker" value="<?=$result['time']?>" style="width:147px"/></td>
            </tr>
          </table>
        
          <table  id="BetweenTime" align="left" style="width:100%;display:none;margin-left:58px;"  cellspacing="5" cellpadding="5">
            <tr>
              <td height="32" align="right">From Time:<font color="red">* </font></td>
              <td><input type="text" name="time1" id="datetimepicker1" value="<?=$result['time']?>" style="width:147px"/></td>
            </tr>
            <tr>
              <td height="32" align="right">To Time:<font color="red">* </font></td>
              <td><input type="text" name="totime" id="datetimepicker2" value="<?=$result['totime']?>" style="width:147px"/></td>
            </tr>
          </table>
        </td>
      </tr>
      
      <tr>
        <td align="right" valign="top">Contact Details</td>
        <td style="margin-left:20px;">
          <table cellspacing="0" cellpadding="0">
            <tr>
              <td>
                  <INPUT type="button" value="+" id='addRowss' />
              </td>
              <td>
                  <INPUT type="button" value="-" id='delRowss' />
              </td>
            </tr>
          </table>
          <table id="dataTable"  cellspacing="" cellpadding="">
           <tr>
                <td  height="32" align="right">
                  <select name="designation" id="designation" style="margin-left:-4px"
                onchange="designationChange(this.value)">
                    <option value="" disabled selected>-- Select Designation --</option>
                    <option value="driver" >Driver</option>
                    <option value="supervisor" >Supervisoer</option>
                    <option value="manager" >Manager</option>
                    <option value="senior manager" >Senior Manager</option>
                     <option value="owner">Owner</option>
                     <option value="sale person">Sale Person</option>
                     <option value="others">Others</option>
                  
                  </select>
                </td>
                <td>
                  <input type="text" name="contact_person" placeholder="Contact Person" id="contact_person" value="<?=$result['contact_person']?>" style="width:147px"/>
                </td>
                <td>
                  <input type="text" name="contact_number" placeholder="Contact Number" id="contact_number"  minlength="10" maxlength="10" value="<?=$result['contact_number']?>"  onkeypress='return event.charCode >= 48 && event.charCode <= 57'  style="width:147px"/>
                </td>       
           </tr>
           <tr id="dataDesignation" style="display:none">
                <td  height="32" align="right">
                  <select name="designation2" id="designation2" style="margin-left:-4px"
                onchange="designationChange(this.value)">
                    <option value="" disabled selected>-- Select Designation --</option>
                    <option value="driver" >Driver</option>
                    <option value="supervisor" >Supervisor</option>
                    <option value="manager" >Manager</option>
                    <option value="senior manager" >Senior Manager</option>
                     <option value="owner">Owner</option>
                     <option value="sale person">Sale Person</option>
                     <option value="others">Others</option>
                  
                  </select>
                </td>
                <td>
                  <input type="text" name="contact_person2" placeholder="Contact Person" id="contact_person2" value="<?=$result['contact_person']?>" style="width:147px"/>
                </td>
                <td>
                  <input type="text" name="contact_number2" placeholder="Contact Number" id="contact_number2"  minlength="10" maxlength="10" value="<?=$result['contact_number']?>"  onkeypress='return event.charCode >= 48 && event.charCode <= 57'  style="width:147px"/>
                </td>       
           </tr>
          </table>
           <tr>  <td  align="right">Comment:</td>
              <td> <textarea rows="5" cols="25"  type="text" name="TxtComment" id="TxtComment" ><?echo $comment?></textarea>
        	</td>
		</tr>
          
        </td>
      </tr>
      <tr>
        <td height="32" align="right"><input type="submit" name="submit" id="button1" value="submit" align="right" /></td>
        <td><input type="button" name="Cancel" value="Cancel" onClick="window.location = 'installation.php' " /></td>
      </tr>
    
 </form>
</div>

<?
include("../include/footer.php");

?>
<script type="text/javascript">
  
var logic = function( currentDateTime ){
  if (currentDateTime && currentDateTime.getDay() == 6){
    this.setOptions({
      minTime:'11:00'
    });
  }else
    this.setOptions({
      minTime:'8:00'
    });
};
$('#datetimepicker').datetimepicker({
  onChangeDateTime:logic,
  onShow:logic
});
$('#datetimepicker1').datetimepicker({
  onChangeDateTime:logic,
  onShow:logic
});

$('#datetimepicker2').datetimepicker({
  onChangeDateTime:logic,
  onShow:logic
});



</script>
<script>StatusBranch12("<?=$result['branch_type'];?>");
TillBetweenTime12("<?=$result['atime_status'];?>");</script>
