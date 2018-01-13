<?php
ob_start();
error_reporting(0);
ini_set('max_execution_time', 50000);
include("D:/xampp/htdocs/service/connection.php");
include_once(__DOCUMENT_ROOT.'/private/master.php');

include("D:/xampp/htdocs/send_alert/class.phpmailer.php");
include("D:/xampp/htdocs/send_alert/class.smtp.php");

$Subject = "Today (".date("Y-m-d").") All Pending Task Email";

$mail=new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port 
$mail->Username   = "info@g-trac.in";  // GMAIL username
$mail->Password   = "info@123456";            // GMAIL password
$mail->From       = "info@g-trac.in";
$mail->FromName   = "G-trac";
$mail->Subject    = $Subject;
//$mail->Body       = $message;                      //HTML Body
$mail->AltBody    = ""; //Text Body
$mail->WordWrap   = 50; // set word wrap

  
//Caller Logins

  $telecaller = select_query("select * from internalsoftware.telecaller_users where `status`='1' and branch_id='1' ");
 
  for($te=0;$te<count($telecaller);$te++)
  {
       $assign_client = select_query("SELECT Userid,UserName,Branch_id,sys_active,client_type,telecaller_id FROM internalsoftware.addclient where 
            branch_id='".$telecaller[$te]['branch_id']."' and sys_active='1' and telecaller_id='".$telecaller[$te]['id']."' order by client_type");
      $client_rslt=''; $client_id='';
      for($i=0;$i<count($assign_client);$i++)
        {
            $client_id.= $assign_client[$i]['Userid'].",";
        }
        
     $client_rslt = substr($client_id,0,strlen($client_id)-1);
                
  
      $notworking_vehdata = select_query("select user_id, sys_service_id as id, user_name, company_name, veh_no as veh_reg,device_imei as imei, 
             vehicle_service, device_service, date_of_installation as sys_created, notwoking as lastcontact, gps_latitude as lat, 
            gps_longitude as lng, gps_speed as speed, gps_fix, tel_voltage, case when tel_poweralert=true then true else false end as poweronoff, 
            case when tel_ignition=true then true else false end as aconoff, device_removed_service, 
            TIMESTAMPDIFF(HOUR, notwoking, NOW()) as hourdiff from internalsoftware.client_notworking_vehicle where  user_id IN (".$client_rslt.")
            and is_active='1' and is_service='0' and TIMESTAMPDIFF(HOUR, notwoking, NOW()) > 2 and device_removed_service!='1'
            and (tel_voltage>3.5 or tel_voltage<=0.0)");
    
    
    $backservices = select_query("SELECT request_by, name, company_name, veh_reg, DATEDIFF(NOW(),atime) as HourDiff FROM 
            internalsoftware.services WHERE service_status=3 and branch_id=".$telecaller[$te]['branch_id']."  
            and request_by='".$telecaller[$te]['login_name']."' order by id desc"); 
        
    $runningServices = select_query("SELECT request_by, name, company_name, veh_reg, DATEDIFF(NOW(),atime) as HourDiff FROM 
            internalsoftware.services where (service_status='1' or service_status='2' or service_status='11' or service_status='17' or 
            service_status='18') and branch_id=".$telecaller[$te]['branch_id']."  and  request_by='".$telecaller[$te]['login_name']."' 
            order by id desc ");

    $backInstallation = select_query("SELECT inst.request_by, ad.UserName, inst.company_name, inst.no_of_vehicals as veh_reg, 
            DATEDIFF(now(),inst.time) as HourDiff FROM internalsoftware.installation as inst left join addclient as ad on 
            inst.user_id=ad.Userid WHERE inst.installation_status=3 and inst.branch_id=".$telecaller[$te]['branch_id']." and 
            inst.request_by='".$telecaller[$te]['login_name']."' order by inst.id desc");
  
    $runningInstallation = select_query("SELECT inst.request_by, ad.UserName, inst.company_name, inst.no_of_vehicals as veh_reg, 
            DATEDIFF(now(),inst.time) as HourDiff FROM internalsoftware.installation as inst left join addclient as ad on 
            inst.user_id=ad.Userid where inst.installation_status IN ('1','2','11','17','18') AND 
            (inst.inter_branch='".$telecaller[$te]['branch_id']."' OR inst.branch_id='".$telecaller[$te]['branch_id']."') and 
            inst.request_by='".$telecaller[$te]['login_name']."' order by inst.id desc");
    
    $InstallationRequest = select_query("SELECT inst.request_by, ad.UserName, inst.company_name, inst.no_of_vehicals as veh_reg, 
            DATEDIFF(now(),inst.time) as HourDiff FROM internalsoftware.installation_request as inst left join addclient as ad on 
            inst.user_id=ad.Userid where (inst.installation_status ='1' or inst.installation_status='2' or inst.installation_status='4' or 
            inst.installation_status='7' or inst.installation_status='8' or inst.installation_status='9') and 
            inst.branch_id=".$telecaller[$te]['branch_id']." and inst.request_by='".$telecaller[$te]['login_name']."'  order by inst.id desc");
    
    
    $deleteVehicle = select_query("SELECT del.acc_manager,ad.UserName,del.client,del.reg_no,DATEDIFF(now(),del.date) as HourDiff FROM 
            deletion as del left join addclient as ad on del.user_id=ad.Userid where ((del.approve_status=0 or del.approve_status=1) and 
            del.final_status!=1) and (del.service_comment is null or del.delete_veh_status=2) and 
            (del.acc_manager='".$telecaller[$te]['login_name']."' or (del.forward_req_user='".$telecaller[$te]['login_name']."' and 
            (del.forward_back_comment is null or del.forward_back_comment=''))) ");

     //echo "<pre>";print_r($InstallationRequest);die;
    
    
    $textTosend[$te] = 'Dear '.$telecaller[$te]['name'].',<br>';
    $textTosend[$te].='<br>It is the mail to inform your Today Pending Task list. All Pending Task list mention below:';
    $textTosend[$te].='<br><br>
                        <table id="resultsTable" border="1" cellspacing="0" cellpadding="0" width="844">               
                          <tr>
                            <td colspan="6" style="text-align:center"><h2> '.$telecaller[$te]['name'].' Login</h2></td>
                          </tr>
                          <tr>               
                            <td width="114" valign="middle"><p align="center"><strong>Request By.</strong></p></td>
                            <td width="100" valign="middle"><p align="center"><strong>Name</strong></p></td>
                            <td width="200" valign="middle"><p align="center"><strong>Company Name</strong></p></td>
                            <td width="150" valign="middle"><p align="center"><strong>Vehicle Number</strong></p></td>
                            <td width="150" valign="middle"><p align="center"><strong>Process</strong></p></td>
                            <td width="130" valign="middle"><p align="center"><strong>Pending</strong></p></td>
                          </tr>';
              
    for($s=0;$s<count($notworking_vehdata);$s++) 
    {
       $diff = round($notworking_vehdata[$s]["hourdiff"]/24);
       
       $textTosend[$te].='<tr>
                          <td> '.$telecaller[$te]['name'].' </td>
                          <td> '.$notworking_vehdata[$s]["user_name"].' </td>
                          <td> '.$notworking_vehdata[$s]["company_name"].' </td>
                          <td> '.$notworking_vehdata[$s]["veh_reg"].' </td>
                          <td> Not Working Vehicle </td>
                          <td> '.$diff.' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($backservices);$s++) 
    {
       $textTosend[$te].='<tr>
                          <td> '.$telecaller[$te]['name'].' </td>
                          <td> '.$backservices[$s]["name"].' </td>
                          <td> '.$backservices[$s]["company_name"].' </td>
                          <td> '.$backservices[$s]["veh_reg"].' </td>
                          <td> Back Service </td>
                          <td> '.$backservices[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($runningServices);$s++) 
    {
       $textTosend[$te].='<tr>
                          <td> '.$telecaller[$te]['name'].' </td>
                          <td> '.$runningServices[$s]["name"].' </td>
                          <td> '.$runningServices[$s]["company_name"].' </td>
                          <td> '.$runningServices[$s]["veh_reg"].' </td>
                          <td> New Service </td>
                          <td> '.$runningServices[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
        
    for($s=0;$s<count($backInstallation);$s++) 
    {
       $textTosend[$te].='<tr>
                          <td> '.$telecaller[$te]['name'].' </td>
                          <td> '.$backInstallation[$s]["UserName"].' </td>
                          <td> '.$backInstallation[$s]["company_name"].' </td>
                          <td> '.$backInstallation[$s]["veh_reg"].' </td>
                          <td> Back Installation</td>
                          <td> '.$backInstallation[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($runningInstallation);$s++) 
    {
       $textTosend[$te].='<tr>
                          <td> '.$telecaller[$te]['name'].' </td>
                          <td> '.$runningInstallation[$s]["UserName"].' </td>
                          <td> '.$runningInstallation[$s]["company_name"].' </td>
                          <td> '.$runningInstallation[$s]["veh_reg"].' </td>
                          <td> New Installation </td>
                          <td> '.$runningInstallation[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($InstallationRequest);$s++) 
    {
       $textTosend[$te].='<tr>
                          <td> '.$telecaller[$te]['name'].' </td>
                          <td> '.$InstallationRequest[$s]["UserName"].' </td>
                          <td> '.$InstallationRequest[$s]["company_name"].' </td>
                          <td> '.$InstallationRequest[$s]["veh_reg"].' </td>
                          <td> New Installation Request</td>
                          <td> '.$InstallationRequest[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
        
    for($s=0;$s<count($deleteVehicle);$s++) 
    {
       $textTosend[$te].='<tr>
                          <td> '.$telecaller[$te]['name'].' </td>
                          <td> '.$deleteVehicle[$s]["UserName"].' </td>
                          <td> '.$deleteVehicle[$s]["client"].' </td>
                          <td> '.$deleteVehicle[$s]["reg_no"].' </td>
                          <td> Delete Vehicle </td>
                          <td> '.$deleteVehicle[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
     $textTosend[$te].='</table><br>'; 
     
     $textTosend[$te].='Thanks & Regards<br>
                    G-Trac <br>';
                    
    
    if($telecaller[$te]['name'] == "Swati")
    {
        $mail->AddAddress("swati@g-trac.in","Swati");    
    }
    elseif($telecaller[$te]['name'] == "Mamta")
    {
        $mail->AddAddress("mamta@g-trac.in","Mamta");    
    }
    elseif($telecaller[$te]['name'] == "Tusheeta")
    {
        $mail->AddAddress("tusheeta@gtrac.in","Tusheeta");    
    }
    elseif($telecaller[$te]['name'] == "Jyoti")
    {
        $mail->AddAddress("jyoti@g-trac.in","Jyoti");    
    }
    elseif($telecaller[$te]['name'] == "Himani")
    {
        $mail->AddAddress("himani@gtrac.in","Himani");    
    }
    
    
    //$mail->AddAddress("harish@g-trac.in","harish");
    $mail->AddAddress("radhika@g-trac.in","Radhika");
    $mail->AddAddress("kuldeep@g-trac.in","Kuldeep");    
     
    $mail->AddReplyTo("info@g-trac.in","G-trac"); 
    $mail->IsHTML(true); 
    
    $mail->Body = $textTosend[$te];
    
    if(!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    }
    
    $mail->ClearAddresses();

  }
  
  //Caller Logins End
  
  // Swati SalesLogin

    $vehicleNoChange = select_query("SELECT vnc.acc_manager,ad.UserName,vnc.client,vnc.new_reg_no,DATEDIFF(now(),vnc.date) as HourDiff 
             FROM vehicle_no_change as vnc left join addclient as ad on vnc.user_id=ad.Userid where (((vnc.approve_status=0) or 
             (vnc.approve_status=1)) and vnc.final_status!=1) and (vnc.service_comment is null or vnc.vehicle_status=2) and 
             (vnc.acc_manager='saleslogin' or (vnc.forward_req_user='saleslogin' and 
             (vnc.forward_back_comment is null or vnc.forward_back_comment=''))) order by vnc.id");
    
    $deleteVehicle = select_query("SELECT del.acc_manager,ad.UserName,del.client,del.reg_no,DATEDIFF(now(),del.date) as HourDiff FROM 
            deletion as del left join addclient as ad on del.user_id=ad.Userid where ((del.approve_status=0 or del.approve_status=1) and 
            del.final_status!=1) and (del.service_comment is null or del.delete_veh_status=2) and (del.acc_manager='saleslogin' or 
            (del.forward_req_user='saleslogin' and (del.forward_back_comment is null or del.forward_back_comment=''))) order by del.id");

    $accountCreation = select_query("SELECT account_manager,user_name,company,potential,DATEDIFF(now(),date) as HourDiff FROM new_account_creation 
            where (approve_status=0 or (approve_status=1 and support_comment!='' and final_status!=1)) and (sales_comment is null or 
            acc_creation_status=2) and (account_manager='saleslogin' or (forward_req_user='saleslogin' and 
            (forward_back_comment is null or forward_back_comment=''))) order by id  ");
        
    $InstallationRequest = select_query("SELECT inst.request_by, ad.UserName, inst.company_name, inst.no_of_vehicals as veh_reg, 
            DATEDIFF(now(),inst.time) as HourDiff FROM internalsoftware.installation_request as inst left join addclient as ad on 
            inst.user_id=ad.Userid where (inst.installation_status ='1' or inst.installation_status='2' or inst.installation_status='4' or 
            inst.installation_status='7' or inst.installation_status='8' or inst.installation_status='9') and 
            inst.branch_id='1' and inst.request_by='saleslogin'  order by inst.id ");
    
    $backInstallation = select_query("SELECT inst.request_by, ad.UserName, inst.company_name, inst.no_of_vehicals as veh_reg, 
            DATEDIFF(now(),inst.time) as HourDiff FROM internalsoftware.installation as inst left join addclient as ad on 
            inst.user_id=ad.Userid WHERE inst.installation_status=3 and inst.branch_id='1' and inst.request_by='saleslogin' order by inst.id");
  
    $runningInstallation = select_query("SELECT inst.request_by, ad.UserName, inst.company_name, inst.no_of_vehicals as veh_reg, 
            DATEDIFF(now(),inst.time) as HourDiff FROM internalsoftware.installation as inst left join addclient as ad on 
            inst.user_id=ad.Userid where inst.installation_status IN ('1','2','11','17','18') AND 
            (inst.inter_branch='1' OR inst.branch_id='1') and inst.request_by='saleslogin' order by inst.id desc");
    
    $stopGPS = select_query("SELECT sg.acc_manager,ad.UserName,sg.company,sg.reg_no,DATEDIFF(now(),sg.date) as HourDiff FROM stop_gps as sg 
            left join addclient as ad on sg.`client`=ad.Userid where (sg.approve_status=0 or (sg.approve_status=1 and sg.support_comment!='' and 
            sg.final_status!=1)) and (sg.sales_comment is null or sg.stop_gps_status=2) and (sg.acc_manager='saleslogin' or 
            (sg.forward_req_user='saleslogin' and (sg.forward_back_comment is null or sg.forward_back_comment=''))) order by sg.id");
    
    $startGPS = select_query("SELECT sg.acc_manager,ad.UserName,sg.company,sg.reg_no,DATEDIFF(now(),sg.date) as HourDiff FROM start_gps as sg 
            left join addclient as ad on sg.`client`=ad.Userid where (sg.approve_status=0 or (sg.approve_status=1 and sg.support_comment!='' and 
            sg.final_status!=1)) and (sg.sales_comment is null or sg.start_gps_status=2) and (sg.acc_manager='saleslogin' or 
            (sg.forward_req_user='saleslogin' and (sg.forward_back_comment is null or sg.forward_back_comment=''))) order by sg.id");

    $subUserCreation = select_query("SELECT suc.acc_manager,ad.UserName,suc.company,suc.reg_no_of_vehicle_to_move,
            DATEDIFF(now(),suc.date) as HourDiff FROM sub_user_creation as suc left join addclient as ad on suc.main_user_id=ad.Userid 
            where (suc.approve_status=0 or (suc.approve_status=1 and suc.support_comment!='' and suc.final_status!=1)) and 
            (suc.sales_comment is null or suc.sub_user_status=2) and (suc.acc_manager='saleslogin' or (suc.forward_req_user='saleslogin' and 
            (suc.forward_back_comment is null or suc.forward_back_comment=''))) order by suc.id");

    $deactivationAccount = select_query("SELECT da.acc_manager,ad.UserName,da.company,da.deactivate_temp,DATEDIFF(now(),da.date) as HourDiff 
            FROM deactivation_of_account as da left join addclient as ad on da.user_id=ad.Userid where (da.approve_status=0 or 
            (da.approve_status=1 and da.support_comment!='' and da.final_status!=1)) and (da.sales_comment is null or da.deactivation_status=2)  
            and (da.acc_manager='saleslogin' or (da.forward_req_user='saleslogin' and (da.forward_back_comment is null or 
            da.forward_back_comment=''))) order by da.id");

    $reactivationAccount = select_query("SELECT ra.acc_manager,ad.UserName,ra.company,ra.deactivate_temp,DATEDIFF(now(),ra.date) as HourDiff FROM 
            reactivation_of_account as ra left join addclient as ad on ra.user_id=ad.Userid where (ra.approve_status=0 or (ra.approve_status=1 
            and ra.support_comment!='' and ra.final_status!=1)) and (ra.sales_comment is null or ra.reactivation_status=2) and 
            (ra.acc_manager='saleslogin' or (ra.forward_req_user='saleslogin' and (ra.forward_back_comment is null or ra.forward_back_comment='')))             order by ra.id ");

    $discounting = select_query("SELECT dd.acc_manager,ad.UserName,dd.client,dd.reg_no,DATEDIFF(now(),dd.date) as HourDiff 
            FROM discount_details as dd left join addclient as ad on dd.user=ad.Userid where (((dd.approve_status=0) or (dd.approve_status=1 and 
            dd.support_comment!='')) and dd.final_status!=1) and (dd.sales_comment is null or dd.discount_status=2) and 
            (dd.acc_manager='saleslogin' or (dd.forward_req_user='saleslogin' and (dd.forward_back_comment is null or 
            dd.forward_back_comment=''))) order by dd.id");

    $softwareRequest = select_query("SELECT sr.acc_manager,ad.UserName,sr.company,DATEDIFF(now(),sr.date) as HourDiff 
            FROM software_request as sr left join addclient as ad on sr.main_user_id=ad.Userid where (sr.approve_status=0 or (sr.approve_status=1 
            and sr.support_comment!='' and sr.final_status!=1)) and (sr.sales_comment is null or sr.software_status=2) and 
            (sr.acc_manager='saleslogin' or (sr.forward_req_user='saleslogin' and (sr.forward_back_comment is null 
            or forward_back_comment=''))) order by sr.id");

    $transferVehicle = select_query("SELECT ttv.acc_manager, ad.UserName, ttv.transfer_from_company, ttv.transfer_from_reg_no, 
            DATEDIFF(now(),ttv.date) as HourDiff FROM transfer_the_vehicle as ttv left join addclient as ad on 
            ttv.transfer_from_user=ad.Userid where (ttv.approve_status=0 or (ttv.approve_status=1 and ttv.support_comment!='' and 
            ttv.final_status!=1)) and (ttv.sales_comment is null or ttv.transfer_veh_status=2) and (ttv.acc_manager='saleslogin' or 
            (ttv.forward_req_user='saleslogin' and (ttv.forward_back_comment is null or ttv.forward_back_comment=''))) order by ttv.id");
    
    
    $textTosend12='Dear SalesLogin,<br>';
    $textTosend12.='<br>It is the mail to inform your Today Pending Task list. All Pending Task list mention below:';
    $textTosend12.='<br><br>
            <table id="resultsTable" border="1" cellspacing="0" cellpadding="0" width="844">               
              <tr>
                <td colspan="6" style="text-align:center"><h2>SalesLogin Details</h2></td>
              </tr>
              <tr>               
                <td width="114" valign="middle"><p align="center"><strong>Request By.</strong></p></td>
                <td width="100" valign="middle"><p align="center"><strong>Name</strong></p></td>
                <td width="200" valign="middle"><p align="center"><strong>Company Name</strong></p></td>
                <td width="150" valign="middle"><p align="center"><strong>Vehicle Number</strong></p></td>
                <td width="150" valign="middle"><p align="center"><strong>Process</strong></p></td>
                <td width="130" valign="middle"><p align="center"><strong>Pending</strong></p></td>
              </tr>';
    
    for($st=0;$st<count($vehicleNoChange);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$vehicleNoChange[$st]["acc_manager"].' </td>
                          <td> '.$vehicleNoChange[$st]["UserName"].' </td>
                          <td> '.$vehicleNoChange[$st]["client"].' </td>
                          <td> '.$vehicleNoChange[$st]["new_reg_no"].' </td>
                          <td> Vehicle Number Change </td>
                          <td> '.$vehicleNoChange[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
        
    for($st=0;$st<count($deleteVehicle);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$deleteVehicle[$st]["acc_manager"].' </td>
                          <td> '.$deleteVehicle[$st]["UserName"].' </td>
                          <td> '.$deleteVehicle[$st]["client"].' </td>
                          <td> '.$deleteVehicle[$st]["reg_no"].' </td>
                          <td> Delete Vehicle </td>
                          <td> '.$deleteVehicle[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($st=0;$st<count($accountCreation);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$accountCreation[$st]["account_manager"].' </td>
                          <td> '.$accountCreation[$st]["user_name"].' </td>
                          <td> '.$accountCreation[$st]["company"].' </td>
                          <td> '.$accountCreation[$st]["potential"].' </td>
                          <td> Account Creation </td>
                          <td> '.$accountCreation[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($st=0;$st<count($InstallationRequest);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$InstallationRequest[$st]['request_by'].' </td>
                          <td> '.$InstallationRequest[$st]["UserName"].' </td>
                          <td> '.$InstallationRequest[$st]["company_name"].' </td>
                          <td> '.$InstallationRequest[$st]["veh_reg"].' </td>
                          <td> New Installation Request</td>
                          <td> '.$InstallationRequest[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($st=0;$st<count($backInstallation);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$backInstallation[$st]['request_by'].' </td>
                          <td> '.$backInstallation[$st]["UserName"].' </td>
                          <td> '.$backInstallation[$st]["company_name"].' </td>
                          <td> '.$backInstallation[$st]["veh_reg"].' </td>
                          <td> Back Installation</td>
                          <td> '.$backInstallation[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($st=0;$st<count($runningInstallation);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$runningInstallation[$st]['request_by'].' </td>
                          <td> '.$runningInstallation[$st]["UserName"].' </td>
                          <td> '.$runningInstallation[$st]["company_name"].' </td>
                          <td> '.$runningInstallation[$st]["veh_reg"].' </td>
                          <td> New Installation </td>
                          <td> '.$runningInstallation[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    
    for($st=0;$st<count($stopGPS);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$stopGPS[$st]["acc_manager"].' </td>
                          <td> '.$stopGPS[$st]["UserName"].' </td>
                          <td> '.$stopGPS[$st]["company"].' </td>
                          <td> '.$stopGPS[$st]["reg_no"].' </td>
                          <td> Stop GPS </td>
                          <td> '.$stopGPS[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($st=0;$st<count($startGPS);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$startGPS[$st]["acc_manager"].' </td>
                          <td> '.$startGPS[$st]["UserName"].' </td>
                          <td> '.$startGPS[$st]["company"].' </td>
                          <td> '.$startGPS[$st]["reg_no"].' </td>
                          <td> Start GPS </td>
                          <td> '.$startGPS[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($st=0;$st<count($subUserCreation);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$subUserCreation[$st]["acc_manager"].' </td>
                          <td> '.$subUserCreation[$st]["UserName"].' </td>
                          <td> '.$subUserCreation[$st]["company"].' </td>
                          <td> '.$subUserCreation[$st]["reg_no_of_vehicle_to_move"].' </td>
                          <td> Sub User Creation </td>
                          <td> '.$subUserCreation[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($st=0;$st<count($deactivationAccount);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$deactivationAccount[$st]["acc_manager"].' </td>
                          <td> '.$deactivationAccount[$st]["UserName"].' </td>
                          <td> '.$deactivationAccount[$st]["company"].' </td>
                          <td> '.$deactivationAccount[$st]["deactivate_temp"].' </td>
                          <td> Deactivation Account </td>
                          <td> '.$deactivationAccount[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($st=0;$st<count($reactivationAccount);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$reactivationAccount[$st]["acc_manager"].' </td>
                          <td> '.$reactivationAccount[$st]["UserName"].' </td>
                          <td> '.$reactivationAccount[$st]["company"].' </td>
                          <td> '.$reactivationAccount[$st]["deactivate_temp"].' </td>
                          <td> Reaactivation Account </td>
                          <td> '.$reactivationAccount[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($st=0;$st<count($discounting);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$discounting[$st]["acc_manager"].' </td>
                          <td> '.$discounting[$st]["UserName"].' </td>
                          <td> '.$discounting[$st]["client"].' </td>
                          <td> '.$discounting[$st]["reg_no"].' </td>
                          <td> Dicounting </td>
                          <td> '.$discounting[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($st=0;$st<count($softwareRequest);$st++) 
    {
       $textTosend12.='<tr>
                          <td> '.$softwareRequest[$st]["acc_manager"].' </td>
                          <td> '.$softwareRequest[$st]["UserName"].' </td>
                          <td> '.$softwareRequest[$st]["company"].' </td>
                          <td>  </td>
                          <td> Software Request </td>
                          <td> '.$softwareRequest[$st]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($transferVehicle);$s++) 
    {    
                 
       $textTosend12.='<tr>
                          <td> '.$transferVehicle[$s]["acc_manager"].' </td>
                          <td> '.$transferVehicle[$s]["UserName"].' </td>
                          <td> '.$transferVehicle[$s]["transfer_from_company"].' </td>
                          <td> '.$transferVehicle[$s]["transfer_from_reg_no"].' </td>
                          <td> Transfer of Vehicle </td>
                          <td> '.$transferVehicle[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    $textTosend12.='</table><br>'; 

    $textTosend12.='Thanks & Regards<br>
                    G-Trac <br>';
    
    //$mail->AddAddress("harish@g-trac.in","harish");
    $mail->AddAddress("swatijr@g-trac.in","Swati Singh");
    $mail->AddAddress("radhika@g-trac.in","Radhika");
    $mail->AddAddress("kuldeep@g-trac.in","Kuldeep");    
     
    $mail->AddReplyTo("info@g-trac.in","G-trac"); 
    $mail->IsHTML(true); 
    
    $mail->Body = $textTosend12;
    
    if(!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    }
    
	$mail->ClearAddresses();

    //echo $textTosend12;
                    
    // End Swati SalesLogin
      

?>