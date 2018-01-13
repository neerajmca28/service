<?php
ob_start();
error_reporting(0);
ini_set('max_execution_time', 50000);
include("D:/xampp/htdocs/service/connection.php");
include_once(__DOCUMENT_ROOT.'/private/master.php');

include("D:/xampp/htdocs/send_alert/class.phpmailer.php");
include("D:/xampp/htdocs/send_alert/class.smtp.php");

//Vikrant Logins

  $openServices = select_query("SELECT request_by,name,company_name,veh_reg,DATEDIFF(NOW(),atime) as HourDiff FROM internalsoftware.services 
                WHERE service_status='1' and ((inter_branch=0 and branch_id='1') or (inter_branch='1' and branch_id!='1')) 
                and atime <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

  $services = select_query("SELECT request_by,name,company_name,veh_reg,DATEDIFF(now(),atime) as HourDiff FROM internalsoftware.services 
                WHERE service_status='1' and inter_branch=0 and service_reinstall='service' and branch_id='1' and 
                atime <= '".date("Y-m-d", strtotime( '-1 days' ))."' order by id desc"); 

  $reInstall = select_query("SELECT request_by,name,company_name,veh_reg,DATEDIFF(now(),atime) as HourDiff FROM internalsoftware.services WHERE 
                service_status='1' and inter_branch=0 and service_reinstall='re_install' and branch_id='1'  and 
                atime <= '".date("Y-m-d", strtotime( '-1 days' ))."' order by id desc");
  
  $openServicesInterBranch = select_query("SELECT request_by,name,company_name,veh_reg,DATEDIFF(now(),atime) as HourDiff FROM 
               internalsoftware.services WHERE service_status='1' and inter_branch='1'  and 
               atime <= '".date("Y-m-d", strtotime( '-1 days' ))."' order by id desc");

  $runningServices = select_query("SELECT request_by,name,company_name,veh_reg,DATEDIFF(now(),atime) as HourDiff FROM internalsoftware.services  
               where service_status IN ('2','17','18')  and (branch_id='1' or inter_branch='1') and 
               atime <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
  
  $serviceForward = select_query("SELECT request_by,name,company_name,veh_reg,DATEDIFF(now(),atime) as HourDiff FROM internalsoftware.services  
              where (service_status='11' and ((fwd_tech_rm_id is NOT NULL and fwd_reason is NOT NULL) or 
              (fwd_repair_id is NOT NULL or fwd_serv_to_repair is NOT NULL))) and (branch_id='1' or inter_branch='1') and 
              atime <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
 
  $openInstallation = select_query("SELECT inst.request_by, ad.UserName,inst.company_name,inst.no_of_vehicals as veh_reg, 
              DATEDIFF(now(),inst.time) as HourDiff FROM internalsoftware.installation as inst left join addclient as ad on 
              inst.user_id=ad.Userid WHERE inst.installation_status='1' and ((inst.inter_branch=0 and inst.branch_id='1') or 
              (inst.inter_branch='1' and inst.branch_id!='1')) and inst.time <='".date("Y-m-d", strtotime('-1 days'))."' ");
  
  $newInstallation = select_query("SELECT inst.request_by, ad.UserName,inst.company_name,inst.no_of_vehicals as veh_reg, 
              DATEDIFF(now(),inst.time) as HourDiff FROM internalsoftware.installation as inst left join addclient as ad on 
              inst.user_id=ad.Userid WHERE inst.installation_status='1' and inst.inter_branch=0 and inst.instal_reinstall='installation' 
              and inst.branch_id='1' and inst.time <= '".date("Y-m-d", strtotime( '-1 days' ))."' order by inst.id desc");

  $reInstallation = select_query("SELECT inst.request_by, ad.UserName,inst.company_name,inst.no_of_vehicals as veh_reg, 
              DATEDIFF(now(),inst.time) as HourDiff FROM internalsoftware.installation as inst left join addclient as ad on 
              inst.user_id=ad.Userid WHERE inst.installation_status='1' and inst.inter_branch=0 and inst.instal_reinstall='re_install' and 
              inst.branch_id='1' and inst.time <= '".date("Y-m-d", strtotime( '-1 days' ))."' order by inst.id desc");

  $openInstallationInterBranch = select_query("SELECT inst.request_by, ad.UserName,inst.company_name,inst.no_of_vehicals as veh_reg, 
              DATEDIFF(now(),inst.time) as HourDiff FROM internalsoftware.installation as inst left join addclient as ad on 
              inst.user_id=ad.Userid WHERE inst.installation_status='1' and inst.inter_branch='1'  and 
              inst.time <= '".date("Y-m-d", strtotime( '-1 days' ))."' order by inst.id desc");
  
  $runningInstallation = select_query("SELECT inst.request_by, ad.UserName,inst.company_name,inst.no_of_vehicals as veh_reg, 
              DATEDIFF(now(),inst.time) as HourDiff FROM internalsoftware.installation as inst left join addclient as ad on 
              inst.user_id=ad.Userid where inst.installation_status IN ('2','17','18')  and (inst.branch_id='1' or inst.inter_branch='1') 
              and inst.time <= '".date("Y-m-d", strtotime( '-1 days' ))."' ");

  $installationForwardRepair = select_query("SELECT inst.request_by, ad.UserName,inst.company_name,inst.no_of_vehicals as veh_reg, 
              DATEDIFF(now(),inst.time) as HourDiff FROM internalsoftware.installation as inst left join addclient as ad on 
              inst.user_id=ad.Userid  where (inst.installation_status='11' and ((inst.fwd_tech_rm_id is NOT NULL and 
              inst.fwd_reason is NOT NULL) or (inst.fwd_repair_id is NOT NULL or inst.fwd_install_to_repair is NOT NULL))) and 
              (inst.branch_id='1' or inst.inter_branch='1') and inst.time <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

  $deviceChange = select_query("SELECT dc.acc_manager,ad.UserName,dc.client,dc.reg_no,DATEDIFF(now(),dc.date) as HourDiff 
             FROM device_change as dc left join addclient as ad on dc.user_id=ad.Userid where dc.final_status!=1 and 
             (dc.acc_manager='triloki' or (dc.forward_req_user='triloki' and (dc.forward_back_comment is null or 
             dc.forward_back_comment=''))) and dc.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

  $newDeviceAddition = select_query("SELECT nda.acc_manager,ad.UserName,nda.`client`,nda.vehicle_no,DATEDIFF(now(),nda.date) as HourDiff FROM 
             new_device_addition as nda left join addclient as ad on nda.user_id=ad.Userid  where nda.final_status!=1 and 
             nda.new_device_status=2 and (nda.acc_manager='triloki' or (nda.forward_req_user='triloki' and (nda.forward_back_comment is 
             null or nda.forward_back_comment=''))) and nda.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

  $vehicleNumberChange = select_query("SELECT vnc.acc_manager,ad.UserName,vnc.client,vnc.new_reg_no,DATEDIFF(now(),vnc.date) as HourDiff 
             FROM vehicle_no_change as vnc left join addclient as ad on vnc.user_id=ad.Userid where (((vnc.approve_status=0) or 
             (vnc.approve_status=1)) and vnc.final_status!=1) and vnc.vehicle_status=2 and (vnc.acc_manager='triloki' 
             or (vnc.forward_req_user='triloki' and (vnc.forward_back_comment is null or vnc.forward_back_comment=''))) and 
             vnc.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

  $simChange = select_query("SELECT sim.acc_manager,ad.UserName,sim.client,sim.reg_no,DATEDIFF(now(),sim.date) as HourDiff 
            FROM sim_change as sim left join addclient as ad on sim.user_id=ad.Userid where ((sim.approve_status=0 and sim.final_status!=1)                     
            or (sim.approve_status=1 and sim.final_status!=1 and sim.support_comment!='')) and sim.sim_change_status=2 and 
            (sim.acc_manager='triloki' or (sim.forward_req_user='triloki' and (sim.forward_back_comment is null or 
            sim.forward_back_comment=''))) and sim.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
  
  // End Vikrant Login 

  // Rakhi Login

    $user_id = "";
    $user_query = select_query("SELECT Userid FROM internalsoftware.addclient WHERE GroupId IN ('1','9') and sys_active='1'");
    
    for($u=0;$u<count($user_query);$u++){
    
      $user_id.= $user_query[$u]['Userid'].",";
    
    }
     
    $user1 = substr($user_id,0,-1);

    /*$forwardRunningServices = select_query("SELECT request_by,name,company_name,veh_reg,HOUR(TIMEDIFF(now(),atime)) as HourDiff FROM services where service_status='11' and fwd_tech_rm_id='17' and fwd_reason is NOT NULL and fwd_repair_id is NULL and fwd_serv_to_repair is NULL
    and fwd_repair_to_serv is NULL");

    $forwardRunningInstallation = select_query("SELECT request_by,company_name,veh_reg,HOUR(TIMEDIFF(now(),time)) as HourDiff FROM installation where installation_status='11' and fwd_tech_rm_id='17' and fwd_reason is NOT NULL and fwd_repair_id is NULL and
    fwd_install_to_repair is null and fwd_repair_to_install is null");*/

    $deviceChangeLogin = select_query("SELECT dc.acc_manager,ad.UserName,dc.client,dc.reg_no,DATEDIFF(now(),dc.approve_date) as HourDiff 
             FROM device_change as dc left join addclient as ad on dc.user_id=ad.Userid where (dc.user_id IN($user1) and 
             (dc.approve_status=1 and dc.final_status!=1 and dc.device_change_status=1 and (dc.support_comment is null or 
             dc.service_comment!=''))) or (dc.forward_req_user='rakhi' and (dc.forward_back_comment is null or dc.forward_back_comment=''))                                   and dc.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

    $newDeviceAdditionLogin = select_query("SELECT nda.acc_manager,ad.UserName,nda.`client`,nda.vehicle_no,DATEDIFF(now(),nda.date) as HourDiff  
             FROM new_device_addition as nda left join addclient as ad on nda.user_id=ad.Userid where (nda.user_id IN($user1) and 
             (nda.approve_status=0 and nda.final_status!=1)) and nda.new_device_status=1 and (nda.support_comment is null or
             nda.service_comment!='') or (nda.forward_req_user='rakhi' and (nda.forward_back_comment is null or 
             nda.forward_back_comment='')) and nda.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

    $vehicleNumberChangeLogin = select_query("SELECT vnc.acc_manager,ad.UserName,vnc.client,vnc.new_reg_no,DATEDIFF(now(),vnc.date) as HourDiff,
             DATEDIFF(now(),vnc.approve_date) as HourDiff_new FROM vehicle_no_change as vnc left join addclient as ad on 
             vnc.user_id=ad.Userid where (vnc.user_id IN($user1) and ((vnc.approve_status=0 and 
             vnc.reason IN('Temperory no to Permanent no','Personal no to Commercial no','Commercial no to Personal no',
             'For Warranty Renuwal Purpose')) or vnc.approve_status=1) and vnc.final_status!=1 and 
             (vnc.support_comment is null or vnc.service_comment!='') and vnc.vehicle_status=1 ) or (vnc.forward_req_user='rakhi' and 
             (vnc.forward_back_comment is null or vnc.forward_back_comment='')) and vnc.date <='".date("Y-m-d", strtotime('-1 days'))."'");

    //echo "<pre>";print_r($vehicleNumberChangeLogin);die;

    $simChangeLogin = select_query("SELECT sim.acc_manager,ad.UserName,sim.client,sim.reg_no,DATEDIFF(now(),sim.date) as HourDiff 
            FROM sim_change as sim left join addclient as ad on sim.user_id=ad.Userid where (sim.user_id IN($user1) and 
            sim.approve_status=0 and sim.final_status!=1 and sim.sim_change_status=1 and (sim.support_comment is null or 
            sim.service_comment!='')) or (sim.forward_req_user='rakhi' and (sim.forward_back_comment is null or 
            sim.forward_back_comment='')) and sim.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

    $deleteVehicle = select_query("SELECT del.acc_manager,ad.UserName,del.client,del.reg_no,DATEDIFF(now(),del.approve_date) as HourDiff FROM 
            deletion as del left join addclient as ad on del.user_id=ad.Userid where (del.user_id IN($user1) and (del.approve_status=1 and 
            del.final_status!=1 and del.delete_veh_status=1 and (del.support_comment is null or del.service_comment!=''))) or 
            (del.forward_req_user='rakhi' and (del.forward_back_comment is null or del.forward_back_comment='')) 
            and del.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

    $accountCreation = select_query("SELECT account_manager,user_name,company,potential,DATEDIFF(now(),approve_date) as HourDiff FROM 
            new_account_creation where (branch_id=1 and approve_status=1 and final_status!=1 and (support_comment is null or 
            sales_comment!='') and acc_creation_status=1) or (forward_req_user='rakhi' and (forward_back_comment is null or 
            forward_back_comment='')) and date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

    $stopGPS = select_query("SELECT sg.acc_manager,ad.UserName,sg.company,sg.reg_no,DATEDIFF(now(),sg.approve_date) as HourDiff FROM 
            stop_gps as sg left join addclient as ad on sg.`client`=ad.Userid where (sg.client IN($user1) and (sg.approve_status=1 and 
            sg.final_status!=1 and sg.stop_gps_status=1 and (sg.support_comment is null or sg.sales_comment!=''))) or (sg.forward_req_user='rakhi' 
            and (sg.forward_back_comment is null or sg.forward_back_comment='')) and sg.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

    $startGPS = select_query("SELECT sg.acc_manager,ad.UserName,sg.company,sg.reg_no,DATEDIFF(now(),sg.approve_date) as HourDiff FROM 
            start_gps as sg left join addclient as ad on sg.`client`=ad.Userid where (sg.client IN($user1) and (sg.approve_status=1 and 
            sg.final_status!=1 and sg.start_gps_status=1 and (sg.support_comment is null or sg.sales_comment!='')) or (sg.forward_req_user='rakhi' 
            and (sg.forward_back_comment is null or sg.forward_back_comment=''))) and sg.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

    $subUserCreation = select_query("SELECT suc.acc_manager,ad.UserName,suc.company,suc.reg_no_of_vehicle_to_move,
            DATEDIFF(now(),suc.approve_date) as HourDiff FROM sub_user_creation as suc left join addclient as ad on suc.main_user_id=ad.Userid 
            where (suc.main_user_id IN($user1) and (suc.approve_status=1 and suc.final_status!=1 and suc.sub_user_status=1 and 
            (suc.support_comment is null or suc.sales_comment!=''))) or (suc.forward_req_user='rakhi' and (suc.forward_back_comment is null                     
            or suc.forward_back_comment='')) and suc.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

    $deactivationAccount = select_query("SELECT da.acc_manager, ad.UserName, da.company, da.deactivate_temp, 
            DATEDIFF(now(),da.approve_date) as HourDiff FROM deactivation_of_account as da left join addclient as ad on da.user_id=ad.Userid 
            where (da.user_id IN($user1) and (da.approve_status=1 and da.final_status!=1 and da.deactivation_status=1 and 
            (da.support_comment is null or da.sales_comment!=''))) or (da.forward_req_user='rakhi' and (da.forward_back_comment is null or 
            da.forward_back_comment='')) and  da.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

    $reactivationAccount = select_query("SELECT ra.acc_manager, ad.UserName, ra.company, ra.deactivate_temp, 
            DATEDIFF(now(),ra.approve_date) as HourDiff  FROM reactivation_of_account as ra left join addclient as ad on ra.user_id=ad.Userid 
            where (ra.user_id IN($user1) and (ra.approve_status=1 and ra.final_status!=1 and ra.reactivation_status=1 and 
            (ra.support_comment is null or ra.sales_comment!=''))) or (ra.forward_req_user='rakhi' and (ra.forward_back_comment is null or 
            ra.forward_back_comment='')) and ra.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

    $discounting = select_query("SELECT dd.acc_manager,ad.UserName,dd.client,dd.reg_no,DATEDIFF(now(),dd.date) as HourDiff 
            FROM discount_details as dd left join addclient as ad on dd.user=ad.Userid where (dd.user IN($user1) and (dd.approve_status=0 
            and dd.discount_issue='Software Issue' and dd.discount_status=1 and (dd.support_comment is null or dd.sales_comment!='') and 
            dd.software_comment is null) and (dd.forward_req_user is null or dd.forward_back_comment!='')) or (dd.forward_req_user='rakhi' 
            and (dd.forward_back_comment is null or dd.forward_back_comment='')) and dd.date <='".date("Y-m-d",strtotime('-1 days' ))."'");

    $softwareRequest = select_query("SELECT sr.acc_manager,ad.UserName,sr.company,DATEDIFF(now(),sr.approve_date) as HourDiff 
            FROM software_request as sr left join addclient as ad on sr.main_user_id=ad.Userid where (sr.main_user_id IN($user1) and 
            (sr.approve_status=1 and sr.final_status!=1 and sr.software_status=1 and (sr.support_comment is null or sr.sales_comment!='')))                     
            or (sr.forward_req_user='rakhi' and (sr.forward_back_comment is null or sr.forward_back_comment='')) and 
            sr.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");

    $transferVehicle = select_query("SELECT ttv.acc_manager, ad.UserName, ttv.transfer_from_company, ttv.transfer_from_reg_no, 
            DATEDIFF(now(),ttv.approve_date) as HourDiff FROM transfer_the_vehicle as ttv left join addclient as ad on 
            ttv.transfer_from_user=ad.Userid where (ttv.transfer_from_user IN($user1) and (ttv.approve_status=1 and ttv.final_status!=1 
            and ttv.transfer_veh_status=1 and (ttv.support_comment is null or ttv.sales_comment!=''))) or (ttv.forward_req_user='rakhi' 
            and (ttv.forward_back_comment is null or ttv.forward_back_comment='')) and ttv.date<='".date("Y-m-d",strtotime('-1 days'))."'");
    
    // End Rakhi Login
    
    // Ankur Login
    
    $user_id = "";
    $user_query = select_query("SELECT Userid FROM internalsoftware.addclient WHERE GroupId='10' and sys_active='1'");
    
    for($u=0;$u<count($user_query);$u++)
    {
      $user_id.= $user_query[$u]['Userid'].",";
    }
    
     $user2 = substr($user_id,0,-1);
     
     /*$forwardRunningServicesl2 = select_query("SELECT request_by,name,company_name,veh_reg,HOUR(TIMEDIFF(now(),atime)) as HourDiff  FROM services where service_status='11' and fwd_tech_rm_id='41' and fwd_reason is NOT NULL and fwd_repair_id is NULL and fwd_serv_to_repair is NULL and fwd_repair_to_serv is NULL");
    
     $forwardRunningInstallationl2 = select_query("SELECT request_by,company_name,veh_reg,HOUR(TIMEDIFF(now(),time)) as HourDiff   FROM installation where installation_status='11' and fwd_tech_rm_id='41' and fwd_reason is NOT NULL and fwd_repair_id is NULL and fwd_install_to_repair is null and fwd_repair_to_install is null");*/

    $deviceChangel2 = select_query("SELECT dc.acc_manager,ad.UserName,dc.client,dc.reg_no,DATEDIFF(now(),dc.approve_date) as HourDiff 
             FROM device_change as dc left join addclient as ad on dc.user_id=ad.Userid where (dc.user_id IN($user2) and 
             (dc.approve_status=1 and dc.final_status!=1 and dc.device_change_status=1 and (dc.support_comment is null or 
             dc.service_comment!=''))) or (dc.forward_req_user='ankur' and (dc.forward_back_comment is null or dc.forward_back_comment=''))                                   and dc.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
    
    $newDeviceAdditionl2 = select_query("SELECT nda.acc_manager,ad.UserName,nda.`client`,nda.vehicle_no,DATEDIFF(now(),nda.date) as HourDiff  
             FROM new_device_addition as nda left join addclient as ad on nda.user_id=ad.Userid where (nda.user_id IN($user2) and 
             (nda.approve_status=0 and nda.final_status!=1)) and nda.new_device_status=1 and (nda.support_comment is null or
             nda.service_comment!='') or (nda.forward_req_user='ankur' and (nda.forward_back_comment is null or 
             nda.forward_back_comment='')) and nda.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
                                      
    $vehicleNumberChangel2 = select_query("SELECT vnc.acc_manager,ad.UserName,vnc.client,vnc.new_reg_no,DATEDIFF(now(),vnc.date) as HourDiff,
             DATEDIFF(now(),vnc.approve_date) as HourDiff_new FROM vehicle_no_change as vnc left join addclient as ad on     
             vnc.user_id=ad.Userid where (vnc.user_id IN($user2) and ((vnc.approve_status=0 and 
             vnc.reason IN('Temperory no to Permanent no','Personal no to Commercial no','Commercial no to Personal no',
             'For Warranty Renuwal Purpose')) or vnc.approve_status=1) and vnc.final_status!=1 and 
             (vnc.support_comment is null or vnc.service_comment!='') and vnc.vehicle_status=1 ) or (vnc.forward_req_user='ankur' and 
             (vnc.forward_back_comment is null or vnc.forward_back_comment='')) and vnc.date <='".date("Y-m-d", strtotime('-1 days'))."'");
                     
    $simChangel2 = select_query("SELECT sim.acc_manager,ad.UserName,sim.client,sim.reg_no,DATEDIFF(now(),sim.date) as HourDiff 
            FROM sim_change as sim left join addclient as ad on sim.user_id=ad.Userid where (sim.user_id IN($user2) and 
            sim.approve_status=0 and sim.final_status!=1 and sim.sim_change_status=1 and (sim.support_comment is null or 
            sim.service_comment!='')) or (sim.forward_req_user='ankur' and (sim.forward_back_comment is null or 
            sim.forward_back_comment='')) and sim.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
                    
    $deleteVehiclel2 = select_query("SELECT del.acc_manager,ad.UserName,del.client,del.reg_no,DATEDIFF(now(),del.approve_date) as HourDiff FROM 
            deletion as del left join addclient as ad on del.user_id=ad.Userid where (del.user_id IN($user2) and (del.approve_status=1 and 
            del.final_status!=1 and del.delete_veh_status=1 and (del.support_comment is null or del.service_comment!=''))) or 
            (del.forward_req_user='ankur' and (del.forward_back_comment is null or del.forward_back_comment='')) 
            and del.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
                    
    $accountCreationl2=select_query("SELECT account_manager,user_name,company,potential,DATEDIFF(now(),approve_date) as HourDiff FROM 
            new_account_creation where (branch_id IN ('2','3','4','7') and approve_status=1 and final_status!=1 and 
            (support_comment is null or sales_comment!='') and acc_creation_status=1) or (forward_req_user='ankur' and 
            (forward_back_comment is null or forward_back_comment='')) and date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
    
    $stopGPSl2 = select_query("SELECT sg.acc_manager, ad.UserName, sg.company, sg.reg_no, DATEDIFF(now(),sg.approve_date) as HourDiff FROM 
            stop_gps as sg left join addclient as ad on sg.`client`=ad.Userid where (sg.client IN($user2) and (sg.approve_status=1 and 
            sg.final_status!=1 and sg.stop_gps_status=1 and (sg.support_comment is null or sg.sales_comment!=''))) or (sg.forward_req_user='ankur' 
            and (sg.forward_back_comment is null or sg.forward_back_comment='')) and sg.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
                                    
    $startGPSl2 = select_query("SELECT sg.acc_manager,ad.UserName,sg.company,sg.reg_no,DATEDIFF(now(),sg.approve_date) as HourDiff FROM 
            start_gps as sg left join addclient as ad on sg.`client`=ad.Userid where (sg.client IN($user2) and (sg.approve_status=1 and 
            sg.final_status!=1 and sg.start_gps_status=1 and (sg.support_comment is null or sg.sales_comment!='')) or (sg.forward_req_user='ankur' 
            and (sg.forward_back_comment is null or sg.forward_back_comment=''))) and sg.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
    
    $subUserCreationl2 = select_query("SELECT suc.acc_manager,ad.UserName,suc.company,suc.reg_no_of_vehicle_to_move,
            DATEDIFF(now(),suc.approve_date) as HourDiff FROM sub_user_creation as suc left join addclient as ad on suc.main_user_id=ad.Userid 
            where (suc.main_user_id IN($user2) and (suc.approve_status=1 and suc.final_status!=1 and suc.sub_user_status=1 and 
            (suc.support_comment is null or suc.sales_comment!=''))) or (suc.forward_req_user='ankur' and (suc.forward_back_comment is null                     
            or suc.forward_back_comment='')) and suc.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
    
    $deactivationAccountl2 = select_query("SELECT da.acc_manager, ad.UserName, da.company, da.deactivate_temp, 
            DATEDIFF(now(),da.approve_date) as HourDiff FROM deactivation_of_account as da left join addclient as ad on da.user_id=ad.Userid 
            where (da.user_id IN($user2) and (da.approve_status=1 and da.final_status!=1 and da.deactivation_status=1 and 
            (da.support_comment is null or da.sales_comment!=''))) or (da.forward_req_user='ankur' and (da.forward_back_comment is null or 
            da.forward_back_comment='')) and  da.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
    
    $reactivationAccountl2 = select_query("SELECT ra.acc_manager, ad.UserName, ra.company, ra.deactivate_temp, 
            DATEDIFF(now(),ra.approve_date) as HourDiff FROM reactivation_of_account as ra left join addclient as ad on ra.user_id=ad.Userid 
            where (ra.user_id IN($user2) and (ra.approve_status=1 and ra.final_status!=1 and ra.reactivation_status=1 and 
            (ra.support_comment is null or ra.sales_comment!=''))) or (ra.forward_req_user='ankur' and (ra.forward_back_comment is null or 
            ra.forward_back_comment='')) and ra.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
                                                                     
    $discountingl2 = select_query("SELECT dd.acc_manager,ad.UserName,dd.client,dd.reg_no,DATEDIFF(now(),dd.date) as HourDiff 
            FROM discount_details as dd left join addclient as ad on dd.user=ad.Userid where (dd.user IN($user2) and (dd.approve_status=0 
            and dd.discount_issue='Software Issue' and dd.discount_status=1 and (dd.support_comment is null or dd.sales_comment!='') and 
            dd.software_comment is null) and (dd.forward_req_user is null or dd.forward_back_comment!='')) or (dd.forward_req_user='ankur' 
            and (dd.forward_back_comment is null or dd.forward_back_comment='')) and dd.date <='".date("Y-m-d",strtotime('-1 days' ))."'");
    
    $softwareRequestl2 = select_query("SELECT sr.acc_manager,ad.UserName,sr.company,DATEDIFF(now(),sr.approve_date) as HourDiff 
            FROM software_request as sr left join addclient as ad on sr.main_user_id=ad.Userid where (sr.main_user_id IN($user2) and 
            (sr.approve_status=1 and sr.final_status!=1 and sr.software_status=1 and (sr.support_comment is null or sr.sales_comment!='')))                     
            or (sr.forward_req_user='ankur' and (sr.forward_back_comment is null or sr.forward_back_comment='')) and 
            sr.date <='".date("Y-m-d", strtotime( '-1 days' ))."' ");
                                     
    $transferVehiclel2 = select_query("SELECT ttv.acc_manager, ad.UserName, ttv.transfer_from_company, ttv.transfer_from_reg_no, 
            DATEDIFF(now(),ttv.approve_date) as HourDiff FROM transfer_the_vehicle as ttv left join addclient as ad on 
            ttv.transfer_from_user=ad.Userid where (ttv.transfer_from_user IN($user2) and (ttv.approve_status=1 and ttv.final_status!=1 
            and ttv.transfer_veh_status=1 and (ttv.support_comment is null or ttv.sales_comment!=''))) or (ttv.forward_req_user='ankur' 
            and (ttv.forward_back_comment is null or ttv.forward_back_comment='')) and ttv.date<='".date("Y-m-d",strtotime('-1 days'))."'");
                    
    // End Ankur Login

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
    
    $textTosend='Dear Dispatch Team,<br>';
    $textTosend.='<br>It is the mail to inform your Today Pending Task list. All Pending Task list mention below:';
    $textTosend.='<br><br>
            <table id="resultsTable" border="1" cellspacing="0" cellpadding="0" width="844">               
              <tr>
                <td colspan="6" style="text-align:center"><h2>VIKRANT LOGIN</h2></td>
              </tr>
              <tr>               
                <td width="114" valign="middle"><p align="center"><strong>Request By.</strong></p></td>
                <td width="100" valign="middle"><p align="center"><strong>Name</strong></p></td>
                <td width="200" valign="middle"><p align="center"><strong>Company Name</strong></p></td>
                <td width="150" valign="middle"><p align="center"><strong>Vehicle Number</strong></p></td>
                <td width="150" valign="middle"><p align="center"><strong>Process</strong></p></td>
                <td width="130" valign="middle"><p align="center"><strong>Pending</strong></p></td>
              </tr>';
    
    
    for($s=0;$s<count($openServices);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$openServices[$s]["request_by"].' </td>
                          <td> '.$openServices[$s]["name"].' </td>
                          <td> '.$openServices[$s]["company_name"].' </td>
                          <td> '.$openServices[$s]["veh_reg"].' </td>
                          <td> Open Services </td>
                          <td> '.$openServices[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($reInstall);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$reInstall[$s]["request_by"].' </td>
                          <td> '.$reInstall[$s]["name"].' </td>
                          <td> '.$reInstall[$s]["company_name"].' </td>
                          <td> '.$reInstall[$s]["veh_reg"].' </td>
                          <td> ReInstall </td>
                          <td> '.$reInstall[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($openServicesinterBranch);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$openServicesinterBranch[$s]["request_by"].' </td>
                          <td> '.$openServicesinterBranch[$s]["name"].' </td>
                          <td> '.$openServicesinterBranch[$s]["company_name"].' </td>
                          <td> '.$openServicesinterBranch[$s]["veh_reg"].' </td>
                          <td> Open Services Inter Branch </td>
                          <td> '.$openServicesinterBranch[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($runningServices);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$runningServices[$s]["request_by"].' </td>
                          <td> '.$runningServices[$s]["name"].' </td>
                          <td> '.$runningServices[$s]["company_name"].' </td>
                          <td> '.$runningServices[$s]["veh_reg"].' </td>
                          <td> Running Services</td>
                          <td> '.$runningServices[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($serviceForward);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$serviceForward[$s]["request_by"].' </td>
                          <td> '.$serviceForward[$s]["name"].' </td>
                          <td> '.$serviceForward[$s]["company_name"].' </td>
                          <td> '.$serviceForward[$s]["veh_reg"].' </td>
                          <td> Service Forward</td>
                          <td> '.$serviceForward[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($openInstallation);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$openInstallation[$s]["request_by"].' </td>
                          <td> '.$openInstallation[$s]["UserName"].' </td>
                          <td> '.$openInstallation[$s]["company_name"].' </td>
                          <td> '.$openInstallation[$s]["veh_reg"].' </td>
                          <td> Open Installation</td>
                          <td> '.$openInstallation[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($newInstallation);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$newInstallation[$s]["request_by"].' </td>
                          <td> '.$newInstallation[$s]["UserName"].' </td>
                          <td> '.$newInstallation[$s]["company_name"].' </td>
                          <td> '.$newInstallation[$s]["veh_reg"].' </td>
                          <td> New Installation</td>
                          <td> '.$newInstallation[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($reInstallation);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$reInstallation[$s]["request_by"].' </td>
                          <td> '.$reInstallation[$s]["UserName"].' </td>
                          <td> '.$reInstallation[$s]["company_name"].' </td>
                          <td> '.$reInstallation[$s]["veh_reg"].' </td>
                          <td> New Installation</td>
                          <td> '.$reInstallation[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($openInstallationInterBranch);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$openInstallationInterBranch[$s]["request_by"].' </td>
                          <td> '.$openInstallationInterBranch[$s]["UserName"].' </td>
                          <td> '.$openInstallationInterBranch[$s]["company_name"].' </td>
                          <td> '.$openInstallationInterBranch[$s]["veh_reg"].' </td>
                          <td> Open Installation Inter Branch</td>
                          <td> '.$openInstallationInterBranch[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($runningInstallation);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$runningInstallation[$s]["request_by"].' </td>
                          <td> '.$runningInstallation[$s]["UserName"].' </td>
                          <td> '.$runningInstallation[$s]["company_name"].' </td>
                          <td> '.$runningInstallation[$s]["veh_reg"].' </td>
                          <td> Running Installation </td>
                          <td> '.$runningInstallation[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($installationForwardRepair);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$installationForwardRepair[$s]["request_by"].' </td>
                          <td> '.$installationForwardRepair[$s]["UserName"].' </td>
                          <td> '.$installationForwardRepair[$s]["company_name"].' </td>
                          <td> '.$installationForwardRepair[$s]["veh_reg"].' </td>
                          <td> Installation Forward Repair </td>
                          <td> '.$installationForwardRepair[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($deviceChange);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$deviceChange[$s]["acc_manager"].' </td>
                          <td> '.$deviceChange[$s]["UserName"].' </td>
                          <td> '.$deviceChange[$s]["client"].' </td>
                          <td> '.$deviceChange[$s]["reg_no"].' </td>
                          <td> Device Change </td>
                          <td> '.$deviceChange[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($newDeviceAddition);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$newDeviceAddition[$s]["acc_manager"].' </td>
                          <td> '.$newDeviceAddition[$s]["UserName"].' </td>
                          <td> '.$newDeviceAddition[$s]["client"].' </td>
                          <td> '.$newDeviceAddition[$s]["vehicle_no"].' </td>
                          <td> New Device Addition </td>
                          <td> '.$newDeviceAddition[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($vehicleNumberChange);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$vehicleNumberChange[$s]["acc_manager"].' </td>
                          <td> '.$vehicleNumberChange[$s]["UserName"].' </td>
                          <td> '.$vehicleNumberChange[$s]["client"].' </td>
                          <td> '.$vehicleNumberChange[$s]["new_reg_no"].' </td>
                          <td> Vehicle Number Change </td>
                          <td> '.$vehicleNumberChange[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    
    for($s=0;$s<count($simChange);$s++) 
    {
       $textTosend.='<tr>
                          <td> '.$simChange[$s]["acc_manager"].' </td>
                          <td> '.$simChange[$s]["UserName"].' </td>
                          <td> '.$simChange[$s]["client"].' </td>
                          <td> '.$simChange[$s]["reg_no"].' </td>
                          <td> Sim Change </td>
                          <td> '.$simChange[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    $textTosend.='</table><br>'; 

    $textTosend.='Thanks & Regards<br>
                    G-Trac <br>';
                    
    
    $textTosend2='Dear Tech Support Team,<br>';
    $textTosend2.='<br>It is the mail to inform your Today Pending Task list. All Pending Task list mention below:';
    $textTosend2.='<br><br>
            <table id="resultsTable" border="1" cellspacing="0" cellpadding="0" width="844">               
              <tr>
                <td colspan="6" style="text-align:center"><h2>RAKHI LOGIN</h2></td>
              </tr>
              <tr>               
                <td width="114" valign="middle"><p align="center"><strong>Request By.</strong></p></td>
                <td width="100" valign="middle"><p align="center"><strong>Name</strong></p></td>
                <td width="200" valign="middle"><p align="center"><strong>Company Name</strong></p></td>
                <td width="150" valign="middle"><p align="center"><strong>Vehicle Number</strong></p></td>
                <td width="150" valign="middle"><p align="center"><strong>Process</strong></p></td>
                <td width="130" valign="middle"><p align="center"><strong>Pending</strong></p></td>
              </tr>';
        
    for($s=0;$s<count($deviceChangeLogin);$s++) 
    {
       $textTosend2.='<tr>
                          <td> '.$deviceChangeLogin[$s]["acc_manager"].' </td>
                          <td> '.$deviceChangeLogin[$s]["UserName"].' </td>
                          <td> '.$deviceChangeLogin[$s]["client"].' </td>
                          <td> '.$deviceChangeLogin[$s]["reg_no"].' </td>
                          <td> Device Change </td>
                          <td> '.$deviceChangeLogin[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($newDeviceAdditionLogin);$s++) 
    {
       $textTosend2.='<tr>
                          <td> '.$newDeviceAdditionLogin[$s]["acc_manager"].' </td>
                          <td> '.$newDeviceAdditionLogin[$s]["UserName"].' </td>
                          <td> '.$newDeviceAdditionLogin[$s]["client"].' </td>
                          <td> '.$newDeviceAdditionLogin[$s]["vehicle_no"].' </td>
                          <td> New Device Addition </td>
                          <td> '.$newDeviceAdditionLogin[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($vehicleNumberChangeLogin);$s++) 
    {
       if($vehicleNumberChangeLogin[$s]["HourDiff_new"] != "")
       {
        $textTosend2.='<tr>
                          <td> '.$vehicleNumberChangeLogin[$s]["acc_manager"].' </td>
                          <td> '.$vehicleNumberChangeLogin[$s]["UserName"].' </td>
                          <td> '.$vehicleNumberChangeLogin[$s]["client"].' </td>
                          <td> '.$vehicleNumberChangeLogin[$s]["new_reg_no"].' </td>
                          <td> Vehicle Number Change </td>
                          <td> '.$vehicleNumberChangeLogin[$s]["HourDiff_new"].' Day </td>
                    </tr> ';
    
       } else {
           
       $textTosend2.='<tr>
                          <td> '.$vehicleNumberChangeLogin[$s]["acc_manager"].' </td>
                          <td> '.$vehicleNumberChangeLogin[$s]["UserName"].' </td>
                          <td> '.$vehicleNumberChangeLogin[$s]["client"].' </td>
                          <td> '.$vehicleNumberChangeLogin[$s]["new_reg_no"].' </td>
                          <td> Vehicle Number Change </td>
                          <td> '.$vehicleNumberChangeLogin[$s]["HourDiff"].' Day </td>
                    </tr> ';
       }
       
    }
    
    for($s=0;$s<count($simChangeLogin);$s++) 
    {
       $textTosend2.='<tr>
                          <td> '.$simChangeLogin[$s]["acc_manager"].' </td>
                          <td> '.$simChangeLogin[$s]["UserName"].' </td>
                          <td> '.$simChangeLogin[$s]["client"].' </td>
                          <td> '.$simChangeLogin[$s]["reg_no"].' </td>
                          <td> SIM Change </td>
                          <td> '.$simChangeLogin[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($deleteVehicle);$s++) 
    {
       $textTosend2.='<tr>
                          <td> '.$deleteVehicle[$s]["acc_manager"].' </td>
                          <td> '.$deleteVehicle[$s]["UserName"].' </td>
                          <td> '.$deleteVehicle[$s]["client"].' </td>
                          <td> '.$deleteVehicle[$s]["reg_no"].' </td>
                          <td> Delete Vehicle </td>
                          <td> '.$deleteVehicle[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($accountCreation);$s++) 
    {
       $textTosend2.='<tr>
                          <td> '.$accountCreation[$s]["account_manager"].' </td>
                          <td> '.$accountCreation[$s]["user_name"].' </td>
                          <td> '.$accountCreation[$s]["company"].' </td>
                          <td> '.$accountCreation[$s]["potential"].' </td>
                          <td> Account Creation </td>
                          <td> '.$accountCreation[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($stopGPS);$s++) 
    {
       $textTosend2.='<tr>
                          <td> '.$stopGPS[$s]["acc_manager"].' </td>
                          <td> '.$stopGPS[$s]["UserName"].' </td>
                          <td> '.$stopGPS[$s]["company"].' </td>
                          <td> '.$stopGPS[$s]["veh_reg"].' </td>
                          <td> Stop GPS </td>
                          <td> '.$stopGPS[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($startGPS);$s++) 
    {
       $textTosend2.='<tr>
                          <td> '.$startGPS[$s]["acc_manager"].' </td>
                          <td> '.$startGPS[$s]["UserName"].' </td>
                          <td> '.$startGPS[$s]["company"].' </td>
                          <td> '.$startGPS[$s]["reg_no"].' </td>
                          <td> Start GPS </td>
                          <td> '.$startGPS[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($subUserCreation);$s++) 
    {
       $textTosend2.='<tr>
                          <td> '.$subUserCreation[$s]["acc_manager"].' </td>
                          <td> '.$subUserCreation[$s]["UserName"].' </td>
                          <td> '.$subUserCreation[$s]["company"].' </td>
                          <td> '.$subUserCreation[$s]["reg_no_of_vehicle_to_move"].' </td>
                          <td> Sub User Creation </td>
                          <td> '.$subUserCreation[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($deactivationAccount);$s++) 
    {
       $textTosend2.='<tr>
                          <td> '.$deactivationAccount[$s]["acc_manager"].' </td>
                          <td> '.$deactivationAccount[$s]["UserName"].' </td>
                          <td> '.$deactivationAccount[$s]["company"].' </td>
                          <td> '.$deactivationAccount[$s]["deactivate_temp"].' </td>
                          <td> Deactivation Account </td>
                          <td> '.$deactivationAccount[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($reactivationAccount);$s++) 
    {
       $textTosend2.='<tr>
                          <td> '.$reactivationAccount[$s]["acc_manager"].' </td>
                          <td> '.$reactivationAccount[$s]["UserName"].' </td>
                          <td> '.$reactivationAccount[$s]["company"].' </td>
                          <td> '.$reactivationAccount[$s]["deactivate_temp"].' </td>
                          <td> Reaactivation Account </td>
                          <td> '.$reactivationAccount[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($discounting);$s++) 
    {
       $textTosend2.='<tr>
                          <td> '.$discounting[$s]["acc_manager"].' </td>
                          <td> '.$discounting[$s]["UserName"].' </td>
                          <td> '.$discounting[$s]["client"].' </td>
                          <td> '.$discounting[$s]["reg_no"].' </td>
                          <td> Dicounting </td>
                          <td> '.$discounting[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($softwareRequest);$s++) 
    {
       $textTosend2.='<tr>
                          <td> '.$softwareRequest[$s]["acc_manager"].' </td>
                          <td> '.$softwareRequest[$s]["UserName"].' </td>
                          <td> '.$softwareRequest[$s]["company"].' </td>
                          <td>  </td>
                          <td> Software Request </td>
                          <td> '.$softwareRequest[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($transferVehicle);$s++) 
    {    
       $vechile_no = explode(",",$transferVehicle[$s]["transfer_from_reg_no"]); 
       for($i=0;$i<=count($vechile_no);$i++){ if($i%3!=0){ $vehicle = $vechile_no[$i]." ";} else { $vehicle =  "<br/>".$vechile_no[$i]." ";} }
                 
       $textTosend2.='<tr>
                          <td> '.$transferVehicle[$s]["acc_manager"].' </td>
                          <td> '.$transferVehicle[$s]["UserName"].' </td>
                          <td> '.$transferVehicle[$s]["transfer_from_company"].' </td>
                          <td> '.$vehicle.' </td>
                          <td> Transfer of Vehicle </td>
                          <td> '.$transferVehicle[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    $textTosend2.='</table><br>'; 

    $textTosend2.='Thanks & Regards<br>
                    G-Trac <br>';
    
    
    $textTosend3='Dear Tech Support Team,<br>';
    $textTosend3.='<br>It is the mail to inform your Today Pending Task list. All Pending Task list mention below:';
    $textTosend3.='<br><br>
            <table id="resultsTable" border="1" cellspacing="0" cellpadding="0" width="844">               
              <tr>
                <td colspan="6" style="text-align:center"><h2>ANKUR LOGIN</h2></td>
              </tr>
              <tr>               
                <td width="114" valign="middle"><p align="center"><strong>Request By.</strong></p></td>
                <td width="100" valign="middle"><p align="center"><strong>Name</strong></p></td>
                <td width="200" valign="middle"><p align="center"><strong>Company Name</strong></p></td>
                <td width="150" valign="middle"><p align="center"><strong>Vehicle Number</strong></p></td>
                <td width="150" valign="middle"><p align="center"><strong>Process</strong></p></td>
                <td width="130" valign="middle"><p align="center"><strong>Pending</strong></p></td>
              </tr>';
              
    for($s=0;$s<count($deviceChangel2);$s++) 
    {
       $textTosend3.='<tr>
                          <td> '.$deviceChangel2[$s]["acc_manager"].' </td>
                          <td> '.$deviceChangel2[$s]["UserName"].' </td>
                          <td> '.$deviceChangel2[$s]["client"].' </td>
                          <td> '.$deviceChangel2[$s]["reg_no"].' </td>
                          <td> Device Change </td>
                          <td> '.$deviceChangel2[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($newDeviceAdditionl2);$s++) 
    {
       $textTosend3.='<tr>
                          <td> '.$newDeviceAdditionl2[$s]["acc_manager"].' </td>
                          <td> '.$newDeviceAdditionl2[$s]["UserName"].' </td>
                          <td> '.$newDeviceAdditionl2[$s]["client"].' </td>
                          <td> '.$newDeviceAdditionl2[$s]["vehicle_no"].' </td>
                          <td> New Device Addition </td>
                          <td> '.$newDeviceAdditionl2[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($vehicleNumberChangel2);$s++) 
    {
       if($vehicleNumberChangel2[$s]["HourDiff_new"] != "")
       {
        $textTosend3.='<tr>
                          <td> '.$vehicleNumberChangel2[$s]["acc_manager"].' </td>
                          <td> '.$vehicleNumberChangel2[$s]["UserName"].' </td>
                          <td> '.$vehicleNumberChangel2[$s]["client"].' </td>
                          <td> '.$vehicleNumberChangel2[$s]["new_reg_no"].' </td>
                          <td> Vehicle Number Change </td>
                          <td> '.$vehicleNumberChangel2[$s]["HourDiff_new"].' Day </td>
                    </tr> ';
    
       } else {
           
       $textTosend3.='<tr>
                          <td> '.$vehicleNumberChangel2[$s]["acc_manager"].' </td>
                          <td> '.$vehicleNumberChangel2[$s]["UserName"].' </td>
                          <td> '.$vehicleNumberChangel2[$s]["client"].' </td>
                          <td> '.$vehicleNumberChangel2[$s]["new_reg_no"].' </td>
                          <td> Vehicle Number Change </td>
                          <td> '.$vehicleNumberChangel2[$s]["HourDiff"].' Day </td>
                    </tr> ';
       }
       
       
       
    }
    
    for($s=0;$s<count($simChangel2);$s++) 
    {
       $textTosend3.='<tr>
                          <td> '.$simChangel2[$s]["acc_manager"].' </td>
                          <td> '.$simChangel2[$s]["UserName"].' </td>
                          <td> '.$simChangel2[$s]["client"].' </td>
                          <td> '.$simChangel2[$s]["reg_no"].' </td>
                          <td> SIM Change </td>
                          <td> '.$simChangel2[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($deleteVehiclel2);$s++) 
    {
       $textTosend3.='<tr>
                          <td> '.$deleteVehiclel2[$s]["acc_manager"].' </td>
                          <td> '.$deleteVehiclel2[$s]["UserName"].' </td>
                          <td> '.$deleteVehiclel2[$s]["client"].' </td>
                          <td> '.$deleteVehiclel2[$s]["reg_no"].' </td>
                          <td> Delete Vehicle </td>
                          <td> '.$deleteVehiclel2[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($accountCreationl2);$s++) 
    {
       $textTosend3.='<tr>
                          <td> '.$accountCreationl2[$s]["account_manager"].' </td>
                          <td> '.$accountCreationl2[$s]["UserName"].' </td>
                          <td> '.$accountCreationl2[$s]["company"].' </td>
                          <td> '.$accountCreationl2[$s]["potential"].' </td>
                          <td> Account Creation </td>
                          <td> '.$accountCreationl2[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($stopGPSl2);$s++) 
    {
       $textTosend3.='<tr>
                          <td> '.$stopGPSl2[$s]["acc_manager"].' </td>
                          <td> '.$stopGPSl2[$s]["UserName"].' </td>
                          <td> '.$stopGPSl2[$s]["company"].' </td>
                          <td> '.$stopGPSl2[$s]["veh_reg"].' </td>
                          <td> Stop GPS </td>
                          <td> '.$stopGPSl2[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($startGPSl2);$s++) 
    {
       $textTosend3.='<tr>
                          <td> '.$startGPSl2[$s]["acc_manager"].' </td>
                          <td> '.$startGPSl2[$s]["UserName"].' </td>
                          <td> '.$startGPSl2[$s]["company"].' </td>
                          <td> '.$startGPSl2[$s]["reg_no"].' </td>
                          <td> Start GPS </td>
                          <td> '.$startGPSl2[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($subUserCreationl2);$s++) 
    {
       $textTosend3.='<tr>
                          <td> '.$subUserCreationl2[$s]["acc_manager"].' </td>
                          <td> '.$subUserCreationl2[$s]["UserName"].' </td>
                          <td> '.$subUserCreationl2[$s]["company"].' </td>
                          <td> '.$subUserCreationl2[$s]["reg_no_of_vehicle_to_move"].' </td>
                          <td> Sub User Creation </td>
                          <td> '.$subUserCreationl2[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($deactivationAccountl2);$s++) 
    {
       $textTosend3.='<tr>
                          <td> '.$deactivationAccountl2[$s]["acc_manager"].' </td>
                          <td> '.$deactivationAccountl2[$s]["UserName"].' </td>
                          <td> '.$deactivationAccountl2[$s]["company"].' </td>
                          <td> '.$deactivationAccountl2[$s]["deactivate_temp"].' </td>
                          <td> Deactivation Account </td>
                          <td> '.$deactivationAccountl2[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($reactivationAccountl2);$s++) 
    {
       $textTosend3.='<tr>
                          <td> '.$reactivationAccountl2[$s]["acc_manager"].' </td>
                          <td> '.$reactivationAccountl2[$s]["UserName"].' </td>
                          <td> '.$reactivationAccountl2[$s]["company"].' </td>
                          <td> '.$reactivationAccountl2[$s]["deactivate_temp"].' </td>
                          <td> Reaactivation Account </td>
                          <td> '.$reactivationAccountl2[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($discounting12);$s++) 
    {
       $textTosend3.='<tr>
                          <td> '.$discounting12[$s]["acc_manager"].' </td>
                          <td> '.$discounting12[$s]["UserName"].' </td>
                          <td> '.$discounting12[$s]["client"].' </td>
                          <td> '.$discounting12[$s]["reg_no"].' </td>
                          <td> Dicounting </td>
                          <td> '.$discounting12[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($softwareRequest12);$s++) 
    {
       $textTosend3.='<tr>
                          <td> '.$softwareRequest12[$s]["acc_manager"].' </td>
                          <td> '.$softwareRequest12[$s]["UserName"].' </td>
                          <td> '.$softwareRequest12[$s]["company"].' </td>
                          <td>  </td>
                          <td> Software Request </td>
                          <td> '.$softwareRequest12[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
    
    for($s=0;$s<count($transferVehicle12);$s++) 
    {    
       $vechile_no2 = explode(",",$transferVehicle12[$s]["transfer_from_reg_no"]); 
       for($i=0;$i<=count($vechile_no2);$i++){ if($i%3!=0){ $vehicle2 = $vechile_no2[$i]." ";} else { $vehicle2 =  "<br/>".$vechile_no2[$i]." ";} }
                 
       $textTosend3.='<tr>
                          <td> '.$transferVehicle12[$s]["acc_manager"].' </td>
                          <td> '.$transferVehicle12[$s]["UserName"].' </td>
                          <td> '.$transferVehicle12[$s]["transfer_from_company"].' </td>
                          <td> '.$vehicle2.' </td>
                          <td> Transfer of Vehicle </td>
                          <td> '.$transferVehicle12[$s]["HourDiff"].' Day </td>
                    </tr> ';
    }
              
     $textTosend3.='</table><br>'; 

     $textTosend3.='Thanks & Regards<br>
                    G-Trac <br>';
    
    
    //$mail->AddAddress("harish@g-trac.in","harish");
    $mail->AddAddress("vikrant@g-trac.in","Vikrant");
    $mail->AddAddress("radhika@g-trac.in","Radhika");
    $mail->AddAddress("kuldeep@g-trac.in","Kuldeep");
    $mail->AddReplyTo("info@g-trac.in","G-trac"); 
    $mail->IsHTML(true);     
    $mail->Body = $textTosend;    
    if(!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    }

	$mail->ClearAddresses();
    
    //$mail->AddAddress("harish@g-trac.in","harish");
    $mail->AddAddress("kuldeep@g-trac.in","Kuldeep");
    $mail->AddAddress("rakhi@g-trac.in","Rakhi");
    $mail->AddAddress("radhika@g-trac.in","Radhika");
    $mail->AddReplyTo("info@g-trac.in","G-trac"); 
    $mail->IsHTML(true);     
    $mail->Body = $textTosend2;    
    if(!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    }

	$mail->ClearAddresses();
    
    //$mail->AddAddress("harish@g-trac.in","harish");
    $mail->AddAddress("kuldeep@g-trac.in","Kuldeep");
    $mail->AddAddress("ankur@g-trac.in","Ankur");
    $mail->AddAddress("radhika@g-trac.in","Radhika");
    $mail->AddReplyTo("info@g-trac.in","G-trac"); 
    $mail->IsHTML(true);     
    $mail->Body = $textTosend3;    
    if(!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    }

	$mail->ClearAddresses();
        
     //echo  $textTosend; echo  $textTosend2; echo  $textTosend3;
    //$successmsg= "Mail Successfully Sent";


?>