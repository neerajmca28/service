<?php
session_start(); 
include("../connection.php");

if($_SESSION['username']=="")
{

    echo "<script>document.location.href ='".__SITE_URL."/index.php'</script>";

}

$yearcal = date("Y");
$month   = (date("m")-1);
$year    = date("Y");
$monthto = (date("m")-1);
$yearto  = date("Y");

function getDays($year)
{

    $num_of_days = array();
    $total_month = 12;
    if($year == date('Y'))
        $total_month = date('m');
    else
        $total_month = 12;

    for($m=1; $m<=$total_month; $m++){
        $num_of_days[$m] = cal_days_in_month(CAL_GREGORIAN, $m, $year);
    }

    return $num_of_days;
}

function dateDifference($date1, $date2)
{ 

    $days1 = date('d', strtotime($date1));        

    $ts1 = strtotime($date1);        
    $ts2 = strtotime($date2);
    
    $year1 = date('Y', $ts1);
    $year2 = date('Y', $ts2);
    
    $month1 = date('m', $ts1);
    $month2 = date('m', $ts2);
    
    if($days1 > 15)        
    {
        $months = (($year2 - $year1) * 12) + ($month2 - $month1);
    }
    else if($days1 < 16)        
    {
        $months = ((($year2 - $year1) * 12) + ($month2 - $month1))+1;
    }
        
   return $months;

}

$GrandTotal  = 0;
$UnitPrice   = 0;

if(isset($_POST["submit"]))
{
    $GrandTotal  = 0;    
    $client_name = addslashes($_POST["user"]);
    $from_date   = $_POST["startYear"].'-'.$_POST["startMonth"].'-01';
    
    $num_of_days = getDays($_POST["endYear"]);
    
    $endMonth = $num_of_days[round($_POST["endMonth"])];
    
    $to_date = $_POST["endYear"].'-'.$_POST["endMonth"].'-'.$endMonth;
    
    $month   = $_POST["startMonth"];
    $year    = $_POST["startYear"];
    $monthto = $_POST["endMonth"];
    $yearto  = $_POST["endYear"];
    
    if($client_name != "" )
    {
        if ($from_date > $to_date)
        {
             $errorMsg = "Start date can't be greator than end date";
        }
        else
        {
             
         $monthdiff = dateDifference($from_date, $to_date);
         
         $user = select_query_live_con("select id, case when sys_active=true then true else false end as active, sys_username, phone_number, 
                     company, billing_address, price_per_unit, sys_payment_status_off from matrix.users where sys_username='".$client_name."'");
         
         if(count($user)>0)
         {
         
             if ($user[0]["sys_payment_status_off"] == 1)
             {
                $errorMsg = "User Account is Temporary DeActivated Due to Non Payement.";
             }
             elseif ($user[0]["active"] == false)
             {
                $errorMsg = "User Account is Permanent DeActivated.";
             }
             else
             {
                 $UserName  = $user[0]['sys_username'];
                 $Address   = $user[0]['billing_address'];
                 $UnitPrice = $user[0]['price_per_unit'];
                 $companyName = $user[0]['company'];
                 
                 $GroupId = select_query_live_con("SELECT sys_group_id FROM matrix.group_users where sys_user_id='".$user[0]['id']."'");
                
                 /*$get_company_data = select_query_live_con("select group.name as company_name from matrix.group where 
                            group.id='".$GroupId[0]['sys_group_id']."'");
                 
                 if($get_company_data[0]['company_name'] != '')
                 { $companyName = $get_company_data[0]['company_name']; }
                 else { $companyName = $user[0]['sys_username']; }*/
                 
                 $startYear = $_POST["startYear"];
                 $startMonth = $_POST["startMonth"];
                    
                 for($mn=0;$mn<$monthdiff;$mn++)
                 {
                    
                    $startMonth = $startMonth%12;                   
                     
                     if($startMonth == 0)
                     {
                        $startYear = $startYear;
                        $num_of_days = getDays($startYear);    
                        $MonthEndDate = $num_of_days[12];
                         
                        $from_date_new = $startYear.'-12-01';
                        $to_date_new   = $startYear.'-12-'.$MonthEndDate; 
                        
                        $startYear = $startYear + 1;
                        $startMonth = $startMonth + 1;
                        
                     }
                     else
                     {
                         $startYear = $startYear;
                         $num_of_days = getDays($startYear);    
                         $MonthEndDate = $num_of_days[round($startMonth)];
                         
                         if($startMonth == 10 || $startMonth == 11)
                         {
                             $from_date_new = $startYear.'-'.$startMonth.'-01';
                             $to_date_new   = $startYear.'-'.$startMonth.'-'.$MonthEndDate;
                         }
                         else
                         {
                             $from_date_new = $startYear.'-0'.$startMonth.'-01';
                             $to_date_new   = $startYear.'-0'.$startMonth.'-'.$MonthEndDate;
                         }
                         
                         $startMonth = $startMonth + 1;
                     }
                     
                              
                     $Total = 0;
                     $ActualDeviceCount = 0;
     
                     $dataResults = select_query_live_con("select sys_group_id,sys_service_id,add_date,remove_date,imei,veh_reg from 
                            matrix.tbl_history_devices left join matrix.services on tbl_history_devices.sys_service_id=services.id left join 
                            matrix.devices on devices.id=services.sys_device_id where 
                            tbl_history_devices.sys_group_id='".$GroupId[0]['sys_group_id']."' order by add_date asc");
                     
                     
                     //echo "<pre>";print_r($dataResults);die;
                     
                     $TempHtml.='<table border="1" width="80%" class="page">';
                     
                     $TempHtml.='<tr><td>Vehicle No.</td><td>Duration</td><td>Price (INR)</td></tr>';
                     
                     $TempHtml.='<tr><td bgcolor="yellow" colspan="3"><strong>'.date('F Y', strtotime($from_date_new)).'</strong></td></tr>';
            
                     for($rs=0;$rs<count($dataResults);$rs++)
                     {          
                        $AddDate = $dataResults[$rs]['add_date'];
                        $RemoveDate = $dataResults[$rs]['remove_date'];
                        $veh_id = $dataResults[$rs]['sys_service_id'];
                        $device_imei = $dataResults[$rs]['imei'];
                        
                        $timestamp = strtotime($from_date_new);
                        $startDate = date('Y-m-01', $timestamp);
                        $endDate  = date('Y-m-t', $timestamp);
                        
                        $PerDayPrice = $UnitPrice / $MonthEndDate; 
                        
                        if(date("Y-m-d",strtotime($AddDate)) <= $endDate)
                        {
                              if ($RemoveDate == "" || $RemoveDate == "0000-00-00 00:00:00")
                              {
                                  //////////////// Single row start //////////////////////////
                                  
                                  $veh_reg = $dataResults[$rs]['veh_reg'];
                                  $ActualDeviceCount = $ActualDeviceCount + 1;
                                 
                                  $TempHtml.='<tr><td>'.$veh_reg.'</td>';
                              
                                  if (date("Y-m-d",strtotime($AddDate)) <= $startDate)
                                  {                                                   
                                      //Full Month Bill              
                                      $duration = "1 Month";                                      
                                      $vehprice = $UnitPrice;    
                                      
                                      $TempHtml.='<td> '.$duration.' </td><td>'.$vehprice.'</td>';
                                                                         
                                      $Total = $Total + $UnitPrice;
                                  }
                                  else
                                  {
                                     // Number of Days Will be Calculated For Billing
                                     $days = (strtotime($endDate) - strtotime(date("Y-m-d",strtotime($AddDate)))) / (60 * 60 * 24)+1;    
                                     $duration = " Add Date ".date("d M Y H:i:s",strtotime($AddDate))." ( ".$days ." Days)";
                                     $vehprice = round(($PerDayPrice * $days), 2);
                                     
                                     $TempHtml.='<td> '.$duration.' </td><td>'.$vehprice.'</td>';
                                     
                                     $Total = $Total + round(($PerDayPrice * $days), 2);
                                     
                                  }
                                   
                                  $TempHtml.='</tr>';
                                                                       
                                 /////////////// Single Row End ////////////////
                                                          
                              }
                              else
                              {
                                  // It menas Device has Removed Date and Device has been Removed and Case 2 will be follwed
                                  // In this case wee will First Check if Remove Date is Greator than start Date of month
                                  // If Remove Date  less than start date of Month that means Device has been remove alreday so no Billing is required
                                  
                                  if (date("Y-m-d",strtotime($RemoveDate)) >= $startDate)
                                  {                                                                                    
                                      
                                      //////////////// Single row start //////////////////////////
                                      
                                      $veh_reg = $dataResults[$rs]['veh_reg'];
                                      
                                      $TempHtml.='<tr><td>'.$veh_reg.'</td>';
                                       
                                      $ActualDeviceCount = $ActualDeviceCount + 1;
                            
                                      if (date("Y-m-d",strtotime($RemoveDate)) <= $endDate)
                                      {                                      
                                           if (date("Y-m-d",strtotime($AddDate)) <= $startDate)
                                           {
                                               // Billing will be done from Start Date of Month to Remove Date                                           
                                               if(date("Y-m-d",strtotime($RemoveDate)) == $startDate)
                                               {
                                                   $days = 1;
                                               }
                                               else
                                               {
                                                    $days = round((strtotime($RemoveDate) - strtotime(date("Y-m-d",strtotime($startDate)))) / (60 * 60 * 24));    
                                               }
                                               
                                               $duration = " Add Date ".date("d M Y H:i:s",strtotime($AddDate))." Remove Date ".date("d M Y H:i:s",strtotime($RemoveDate))." ( ".$days ." Days) ";
                                               $vehprice = round(($PerDayPrice * $days), 2);
                                               
                                               $TempHtml.='<td> '.$duration.' </td><td>'.$vehprice.'</td>';
                                               
                                               $Total = $Total + round(($PerDayPrice * $days), 2);
                                                                                         
                                           }
                                           else
                                           {
                                               // Billing From Add Date to Remove Date
                                               $days = round((strtotime($RemoveDate) - strtotime(date("Y-m-d",strtotime($AddDate)))) / (60 * 60 * 24));
                                               $duration = " Add Date ".date("d M Y H:i:s",strtotime($AddDate))." Remove Date ".date("d M Y H:i:s",strtotime($RemoveDate))." ( ".$days ." Days) ";
                                               $vehprice = round(($PerDayPrice * $days), 2);
                                               
                                               $TempHtml.='<td> '.$duration.' </td><td>'.$vehprice.'</td>';
                                               
                                               $Total = $Total + round(($PerDayPrice * $days), 2);
                                               
                                           }
                                           
                                      }
                                      else
                                      {
                                          // No // Means Remove Date is greator than last date of month
                                          if (date("Y-m-d",strtotime($AddDate)) <= $startDate)
                                          {
                                              // Billing From Start Date of Month to End Date  as Add Date is Less than Start Date and Remove Date is Greator than Last date of month So that Mena sFull month billing Wil be done
                                              // Full Month Billing Will be Done
                                              $duration = " 1 Month ";
                                              $vehprice = $UnitPrice;
                                              
                                              $TempHtml.='<td> '.$duration.' </td><td>'.$vehprice.'</td>';
                                              
                                              $Total = $Total + $UnitPrice;
                                              
                                          }
                                          else
                                          {
                                            // billing From Added Date to End Date of month
                                            // As End Date is Greator than Last Date of Month so Last date will be End Date of month
                                            $days = round((strtotime($endDate) - strtotime(date("Y-m-d",strtotime($AddDate)))) / (60 * 60 * 24));
                                            $duration = " Add Date ".date("d M Y H:i:s",strtotime($AddDate))." Remove Date ".date("d M Y H:i:s",strtotime($RemoveDate))." ( ".$days ." Days) ";
                                            $vehprice = round(($PerDayPrice * $days), 2); 
                                            
                                            $TempHtml.='<td> '.$duration.' </td><td>'.$vehprice.'</td>';
                                            
                                            $Total = $Total + round(($PerDayPrice * $days), 2);
                                              
                                          }                                                    
                                          
                                      }    
                                      
                                      $TempHtml.='</tr>';    
                                      
                                     /////////////// Single Row End ////////////////                              
                                      
                                  }
                                  
                              }
                              
                        }
                        
                     } // End For Loop Single Month
                     
                    $GrandTotal = $GrandTotal + $Total;
                    
                    $TempHtml.='<tr><td colspan="3"> Total Device : '.$ActualDeviceCount.'</td></tr>';

                    $TempHtml.='<tr><td> Total Price :</td><td></td><td>'.$Total.'</td></tr>';
                
                 } // End Multiple Month loop
                 
                    $TempHtml.='<tr><td> Grand Total  :</td><td></td><td><b>'.number_format($GrandTotal,2).'</b></td></tr>';

                    $TempHtml.='</table>';
            
             } // End User Condition Case
             
         } // User loop End
         else
         {
             $errorMsg = "No User Exists!!";
         } // User Not Exits loop End
         
       }// End date condition case
        
    }
    else
    {
        $errorMsg = "User Name Can't be blank!";    
    }
    
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css" >
.errorMsg{border:1px red; }
.message{color: red; }

.page {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
}

table {
    display: table;
    border-collapse: separate;
    border-spacing: 1px;
    border-color: grey;
    border: 1px;
}

td, th {
    display: table-cell;
    vertical-align: inherit;
    width: 100px;
}
</style>
</head>

<body>
<form name="billing" action="" method="post">
<table width="100%" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>User: 
        <input name="user" type="text" id="user" value="<?=$client_name;?>" /><br />
            <select name="startMonth" id="startMonth">
              <option value="01" <? if($month=="01") {?> selected="selected" <? } ?> >Jan</option>
              <option value="02" <? if($month=="02") {?> selected="selected" <? } ?> >Feb</option>
              <option value="03" <? if($month=="03") {?> selected="selected" <? } ?> >Mar</option>
              <option value="04" <? if($month=="04") {?> selected="selected" <? } ?> >Apr</option>
              <option value="05" <? if($month=="05") {?> selected="selected" <? } ?> >May</option>
              <option value="06" <? if($month=="06") {?> selected="selected" <? } ?> >Jun</option>
              <option value="07" <? if($month=="07") {?> selected="selected" <? } ?> >Jul</option>
              <option value="08" <? if($month=="08") {?> selected="selected" <? } ?> >Aug</option>
              <option value="09" <? if($month=="09") {?> selected="selected" <? } ?> >Sep</option>
              <option value="10" <? if($month=="10") {?> selected="selected" <? } ?> >Oct</option>
              <option value="11" <? if($month=="11") {?> selected="selected" <? } ?> >Nov</option>
              <option value="12" <? if($month=="12") {?> selected="selected" <? } ?> >Dec</option>
            </select>
        
        <select name="startYear" id="startYear">
          <? for($y=$yearcal;$y>=2009;$y--) { ?>          
          <option value="<?=$y;?>" <? if($year==$y) {?> selected="selected" <? } ?> ><?=$y;?></option>          
          <? } ?>            
        </select>
        
        <select name="endMonth" id="endMonth">
          <option value="01" <? if($monthto=="01") {?> selected="selected" <? } ?> >Jan</option>
          <option value="02" <? if($monthto=="02") {?> selected="selected" <? } ?> >Feb</option>
          <option value="03" <? if($monthto=="03") {?> selected="selected" <? } ?> >Mar</option>
          <option value="04" <? if($monthto=="04") {?> selected="selected" <? } ?> >Apr</option>
          <option value="05" <? if($monthto=="05") {?> selected="selected" <? } ?> >May</option>
          <option value="06" <? if($monthto=="06") {?> selected="selected" <? } ?> >Jun</option>
          <option value="07" <? if($monthto=="07") {?> selected="selected" <? } ?> >Jul</option>
          <option value="08" <? if($monthto=="08") {?> selected="selected" <? } ?> >Aug</option>
          <option value="09" <? if($monthto=="09") {?> selected="selected" <? } ?> >Sep</option>
          <option value="10" <? if($monthto=="10") {?> selected="selected" <? } ?> >Oct</option>
          <option value="11" <? if($monthto=="11") {?> selected="selected" <? } ?> >Nov</option>
          <option value="12" <? if($monthto=="12") {?> selected="selected" <? } ?> >Dec</option>
        </select>
        
        <select name="endYear" id="endYear">               
          <? for($y=$yearcal;$y>=2009;$y--) { ?>          
          <option value="<?=$y;?>" <? if($yearto==$y) {?> selected="selected" <? } ?> ><?=$y;?></option>          
          <? } ?>             
        </select>
        
        <input type="submit" name="submit" value="Submit"  id="submit" /></td>
      <td valign="top"><img src="<?php echo __SITE_URL;?>/img/logo.png" width="200px" height="60px" /></td>
    </tr>
  </tbody>
</table>

</form>
            
<span class="message"> <?=$errorMsg;?></span><br />

<?

echo $html='<table border="0" cellpadding="0" cellspacing="0" width="60%" class="page">
          <tbody>
            <tr>
              <td colspan="3" align="left"><strong>Company Name:</strong> '.$companyName.' </td>
            </tr>
            <tr>
              <td colspan="3" align="left"><strong>Address:</strong> '.$Address.' </td>
            </tr>
            <tr>
              <td><strong>User Name :</strong> '.$UserName.' </td>
              <td><strong>Price Per Unit :</strong> '.$UnitPrice.' </td>
              <td align="right"><strong>Total Amount : </strong><strong>'.number_format($GrandTotal,2).'</strong></td>
            </tr>
          </tbody>
        </table>';
    

echo $TempHtml;
?>

</body>
</html>