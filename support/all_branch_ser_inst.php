<?php 
session_start();
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); */

$tablename="";
$data=array();
$mode=$_GET['zone_id'];

        if($mode != "")
        {
            $from_date = $_GET['from'];
            $to_date = $_GET['to'];
             
             $zone_service = mysql_query("SELECT services.*,re_city_spr_1.name as zonearea FROM services  left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id where service_status NOT IN (3,4,5,6) and id_region = ".$mode." and atime>='".$from_date." 00:00:00"."' and atime<='".$to_date." 23:59:59"."' and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");
            
            while($zone_row_ser = mysql_fetch_array($zone_service))
                {
                    $notworking = date("d-m-y h:i:s",strtotime($zone_row_ser['Notwoking']));
                    $arr=array (
                            'id' => $zone_row_ser["id"],
                            'req_date'=> $zone_row_ser["req_date"],
                            'request_by' => $zone_row_ser["request_by"],
                            'company_name' => $zone_row_ser["company_name"],
                            'inst_id' => $zone_row_ser["inst_id"],
                            'inst_name' => $zone_row_ser["inst_name"],
                            'close_date' => $zone_row_ser["time"],
                            'back_reason' => $zone_row_ser["back_reason"],
                            'veh_reg' => $zone_row_ser["veh_reg"],
                            'imei_done' => $zone_row_ser["device_imei"],
                            'reason' => $zone_row_ser["reason"],
                            'date_of_installation' => $zone_row_ser["date_of_installation"],
                            'notwoking_make' => $notworking,
                            'zonearea' => $zone_row_ser["zonearea"],
                            'available_time' => $zone_row_ser["atime"],
                            'user_id' => $zone_row_ser["user_id"],
                            'service_inst' => "S",
                            
                            ) ;
                    array_push($data,$arr);
                }
            
            $zone_inst = mysql_query("SELECT installation.*,re_city_spr_1.name as zonearea FROM installation  left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id where installation_status NOT IN (3,4,5,6,15) and id_region = ".$mode." and time>='".$from_date." 00:00:00"."' and time<='".$to_date." 23:59:59"."' and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");
            
            while($zone_row_inst = mysql_fetch_array($zone_inst))
                {
                    $arr=array (
                            'id' => $zone_row_inst["id"],
                            'req_date'=> $zone_row_inst["req_date"],
                            'request_by' => $zone_row_inst["request_by"],
                            'company_name' => $zone_row_inst["company_name"],
                            'inst_id' => $zone_row_inst["inst_id"],
                            'inst_name' => $zone_row_inst["inst_name"],
                            'veh_reg' => $zone_row_inst["no_of_vehicals"],
                            'imei_done' => $zone_row_inst["installation_made"],
                            'close_date' => $zone_row_inst["rtime"],
                            'back_reason' => $zone_row_inst["back_reason"],
                            'zonearea' => $zone_row_inst["zonearea"],
                            'available_time' => $zone_row_inst["time"],
                            'notwoking_make' => $zone_row_inst["model"],
                            'user_id' => $zone_row_inst["user_id"],
                            'service_inst' => "I",
                            
                            ) ;
                    array_push($data,$arr);
                }

        }
        else
        {    
            $from_date = date("Y-m-d");
            $to_date = date("Y-m-d");
            
            $query_inst = mysql_query("select installation.id,req_date,request_by,company_name,user_id,inst_id,inst_name,rtime,re_city_spr_1.name as zonearea, time, no_of_vehicals,installation_made,model from installation left join re_city_spr_1 on installation.Zone_area = re_city_spr_1.id where time>='".$from_date." 00:00:00"."' and time<='".$to_date." 23:59:59"."' and installation_status NOT IN(3,4,5,6,15) and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");
        
            while($row_inst = mysql_fetch_array($query_inst))
                {
                    $arr=array (
                            'id' => $row_inst["id"],
                            'req_date'=> $row_inst["req_date"],
                            'request_by' => $row_inst["request_by"],
                            'company_name' => $row_inst["company_name"],
                            'inst_id' => $row_inst["inst_id"],
                            'inst_name' => $row_inst["inst_name"],
                            'veh_reg' => $row_inst["no_of_vehicals"],
                            'imei_done' => $row_inst["installation_made"],
                            'close_date' => $row_inst["rtime"],
                            'back_reason' => '',
                            'zonearea' => $row_inst["zonearea"],
                            'available_time' => $row_inst["time"],
                            'notwoking_make' => $row_inst["model"],
                            'user_id' => $row_inst["user_id"],
                            'service_inst' => "I",
                            
                            ) ;
                    array_push($data,$arr);
                }
                
            $query_service = mysql_query("select services.id, req_date, request_by, company_name,user_id, inst_id, inst_name, close_date, time, veh_reg, device_imei, reason, date_of_installation, re_city_spr_1.name as zonearea, Notwoking, atime from services left join re_city_spr_1 on services.Zone_area = re_city_spr_1.id  where atime>='".$from_date." 00:00:00"."' and atime<='".$to_date." 23:59:59". "' and service_status NOT IN(3,4,5,6) and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");

            while($row_service = mysql_fetch_array($query_service))
                {
                    $notworking = date("d-m-y h:i:s",strtotime($row_service['Notwoking']));
                    $arr=array (
                            'id' => $row_service["id"],
                            'req_date'=> $row_service["req_date"],
                            'request_by' => $row_service["request_by"],
                            'company_name' => $row_service["company_name"],
                            'inst_id' => $row_service["inst_id"],
                            'inst_name' => $row_service["inst_name"],
                            'close_date' => $row_service["time"],
                            'veh_reg' => $row_service["veh_reg"],
                            'imei_done' => $row_service["device_imei"],
                            'reason' => $row_service["reason"],
                            'back_reason' => '',
                            'date_of_installation' => $row_service["date_of_installation"],
                            'notwoking_make' => $notworking,
                            'zonearea' => $row_service["zonearea"],
                            'available_time' => $row_service["atime"],
                            'user_id' => $row_service["user_id"],
                            'service_inst' => "S",
                            
                            
                            ) ;
                    array_push($data,$arr);
                }
        }
$rslt_data = $data;

if(isset($_POST["submit"]))
 {
    $from_date = $_POST["FromDate"];
    $to_date = $_POST["ToDate"];
    $branch = $_POST["branch"];
    $installer_name = $_POST["Installer_name"];
    $client_name = $_POST["main_user_id"];
    $mode = $_POST["mode"];
    if($branch==1){$branch_name="Delhi";}
    elseif($branch==2){$branch_name="Mumbai";}
    elseif($branch==3){$branch_name="Jaipur";}
    elseif($branch==6){$branch_name="Ahmedabad";}
    elseif($branch==7){$branch_name="Kolkata";}
    
    $data=array();
    
    if($branch != "" && $installer_name != "" && $client_name != "")
    {
        $errorMsg = "Please select one option between Installer and Client.";
    }
    
    elseif($branch != "" && $installer_name != "" && $client_name == "")
    {
        if($mode=="New")
        {
            $query_inst = mysql_query("select installation.id,req_date,request_by,company_name,user_id,inst_id,inst_name,rtime,re_city_spr_1.name as zonearea, time, no_of_vehicals,installation_made,model from installation left join re_city_spr_1 on installation.Zone_area = re_city_spr_1.id where time>='". $from_date." 00:00:00"."' and time<='".$to_date." 23:59:59"."' and installation_status NOT IN(3,4,5,6,15) and (branch_id='".$branch."' or inter_branch='".$branch."') and inst_id='".$installer_name."'");
        
            while($row_inst = mysql_fetch_array($query_inst))
                {
                    $arr=array (
                            'id' => $row_inst["id"],
                            'req_date'=> $row_inst["req_date"],
                            'request_by' => $row_inst["request_by"],
                            'company_name' => $row_inst["company_name"],
                            'inst_id' => $row_inst["inst_id"],
                            'inst_name' => $row_inst["inst_name"],
                            'veh_reg' => $row_inst["no_of_vehicals"],
                            'imei_done' => $row_inst["installation_made"],
                            'close_date' => $row_inst["rtime"],
                            'back_reason' => '',
                            'zonearea' => $row_inst["zonearea"],
                            'available_time' => $row_inst["time"],
                            'notwoking_make' => $row_inst["model"],
                            'user_id' => $row_inst["user_id"],
                            'service_inst' => "I",
                            
                            ) ;
                    array_push($data,$arr);
                }
                
            $query_service = mysql_query("select services.id, req_date, request_by, company_name,user_id, inst_id, inst_name, close_date, time, veh_reg, device_imei, reason, date_of_installation, re_city_spr_1.name as zonearea, Notwoking, atime from services left join re_city_spr_1 on services.Zone_area = re_city_spr_1.id  where atime>='". $from_date." 00:00:00"."' and atime<='" . $to_date." 23:59:59". "' and service_status NOT IN(3,4,5,6) and (branch_id='".$branch."' or inter_branch='".$branch."') and inst_id='".$installer_name."'");

            while($row_service = mysql_fetch_array($query_service))
                {
                    $notworking = date("d-m-y h:i:s",strtotime($row_service['Notwoking']));
                    $arr=array (
                            'id' => $row_service["id"],
                            'req_date'=> $row_service["req_date"],
                            'request_by' => $row_service["request_by"],
                            'company_name' => $row_service["company_name"],
                            'inst_id' => $row_service["inst_id"],
                            'inst_name' => $row_service["inst_name"],
                            'close_date' => $row_service["time"],
                            'veh_reg' => $row_service["veh_reg"],
                            'imei_done' => $row_service["device_imei"],
                            'reason' => $row_service["reason"],
                            'back_reason' => '',
                            'date_of_installation' => $row_service["date_of_installation"],
                            'notwoking_make' => $notworking,
                            'zonearea' => $row_service["zonearea"],
                            'available_time' => $row_service["atime"],
                            'user_id' => $row_service["user_id"],
                            'service_inst' => "S",
                            
                            ) ;
                    array_push($data,$arr);
                }
        }
        else
         {
            $query_inst = mysql_query("select installation.id,req_date,request_by,company_name,user_id,inst_id,inst_name,back_reason,rtime,re_city_spr_1.name as zonearea, time, no_of_vehicals,installation_made,model from installation left join re_city_spr_1 on installation.Zone_area = re_city_spr_1.id where time>='". $from_date." 00:00:00"."' and time<='".$to_date." 23:59:59"."' and (installation_status=3 or installation_status=4 or installation_status=5 or installation_status=6 or installation_status=15) and (branch_id='".$branch."' or inter_branch='".$branch."') and inst_id='".$installer_name."'");
            
            while($row_inst = mysql_fetch_array($query_inst))
                {
                    $arr=array (
                            'id' => $row_inst["id"],
                            'req_date'=> $row_inst["req_date"],
                            'request_by' => $row_inst["request_by"],
                            'company_name' => $row_inst["company_name"],
                            'inst_id' => $row_inst["inst_id"],
                            'inst_name' => $row_inst["inst_name"],
                            'veh_reg' => $row_inst["no_of_vehicals"],
                            'imei_done' => $row_inst["installation_made"],
                            'close_date' => $row_inst["rtime"],
                            'back_reason' => $row_inst["back_reason"],
                            'zonearea' => $row_inst["zonearea"],
                            'available_time' => $row_inst["time"],
                            'notwoking_make' => $row_inst["model"],
                            'user_id' => $row_inst["user_id"],
                            'service_inst' => "I",
                            
                            ) ;
                    array_push($data,$arr);
                }

            $query_service = mysql_query("select services.id, req_date, request_by, company_name,user_id, inst_id, inst_name, close_date, back_reason, time, veh_reg, device_imei, reason, date_of_installation, re_city_spr_1.name as zonearea, Notwoking, atime from services left join re_city_spr_1 on services.Zone_area = re_city_spr_1.id  where atime>='". $from_date." 00:00:00"."' and atime<='" . $to_date." 23:59:59". "' and (service_status=3 or service_status=4 or service_status=5 or service_status=6) and (branch_id='".$branch."' or inter_branch='".$branch."') and inst_id='".$installer_name."'");
            
            while($row_service = mysql_fetch_array($query_service))
                {
                    $notworking = date("d-m-y h:i:s",strtotime($row_service['Notwoking']));
                    $arr=array (
                            'id' => $row_service["id"],
                            'req_date'=> $row_service["req_date"],
                            'request_by' => $row_service["request_by"],
                            'company_name' => $row_service["company_name"],
                            'inst_id' => $row_service["inst_id"],
                            'inst_name' => $row_service["inst_name"],
                            'close_date' => $row_service["time"],
                            'veh_reg' => $row_service["veh_reg"],
                            'imei_done' => $row_service["device_imei"],
                            'reason' => $row_service["reason"],
                            'back_reason' => $row_service["back_reason"],
                            'date_of_installation' => $row_service["date_of_installation"],
                            'notwoking_make' => $notworking,
                            'zonearea' => $row_service["zonearea"],
                            'available_time' => $row_service["atime"],
                            'user_id' => $row_service["user_id"],
                            'service_inst' => "S",
                            
                            
                            ) ;
                    array_push($data,$arr);
                }

        }
        
        /*else if($mode=="Back")
         {
            $query_inst = mysql_query("select installation.id,req_date,request_by,company_name,user_id,inst_id,inst_name,rtime,re_city_spr_1.name as zonearea, time, no_of_vehicals,installation_made,model from installation left join re_city_spr_1 on installation.Zone_area = re_city_spr_1.id where time>='". $from_date." 00:00:00"."' and time<='".$to_date." 23:59:59"."' and (installation_status=3 or installation_status=4  or installation_status=6 or (newstatus=0 and newpending=1)) and (branch_id='".$branch."' or inter_branch='".$branch."') and inst_id='".$installer_name."'");
            
            while($row_inst = mysql_fetch_array($query_inst))
                {
                    $arr=array (
                            'id' => $row_inst["id"],
                            'req_date'=> $row_inst["req_date"],
                            'request_by' => $row_inst["request_by"],
                            'company_name' => $row_inst["company_name"],
                            'inst_id' => $row_inst["inst_id"],
                            'inst_name' => $row_inst["inst_name"],
                            'veh_reg' => $row_inst["no_of_vehicals"],
                            'imei_done' => $row_inst["installation_made"],
                            'close_date' => $row_inst["rtime"],
                            'zonearea' => $row_inst["zonearea"],
                            'available_time' => $row_inst["time"],
                            'notwoking_make' => $row_inst["model"],
                            'user_id' => $row_inst["user_id"],
                            'service_inst' => "I",
                            
                            ) ;
                    array_push($data,$arr);
                }

            $query_service = mysql_query("select services.id, req_date, request_by, company_name,user_id, inst_id, inst_name, close_date, time, veh_reg, device_imei, reason, date_of_installation, re_city_spr_1.name as zonearea, Notwoking, atime from services left join re_city_spr_1 on services.Zone_area = re_city_spr_1.id  where atime>='". $from_date." 00:00:00"."' and atime<='" . $to_date." 23:59:59". "' and (service_status=3 or service_status=4 or service_status=6 or (newstatus=0 and newpending=1)) and (branch_id='".$branch."' or inter_branch='".$branch."') and inst_id='".$installer_name."'");
            
            while($row_service = mysql_fetch_array($query_service))
                {
                    $notworking = date("d-m-y h:i:s",strtotime($row_service['Notwoking']));
                    $arr=array (
                            'id' => $row_service["id"],
                            'req_date'=> $row_service["req_date"],
                            'request_by' => $row_service["request_by"],
                            'company_name' => $row_service["company_name"],
                            'inst_id' => $row_service["inst_id"],
                            'inst_name' => $row_service["inst_name"],
                            'close_date' => $row_service["time"],
                            'veh_reg' => $row_service["veh_reg"],
                            'imei_done' => $row_service["device_imei"],
                            'reason' => $row_service["reason"],
                            'date_of_installation' => $row_service["date_of_installation"],
                            'notwoking_make' => $notworking,
                            'zonearea' => $row_service["zonearea"],
                            'available_time' => $row_service["atime"],
                            'user_id' => $row_service["user_id"],
                            'service_inst' => "S",
                            
                            
                            ) ;
                    array_push($data,$arr);
                }

        }
        */
    }
    elseif($branch != "" && $installer_name== "" && $client_name != "")
    {
        if($mode=="Close")
         {
            $query_inst = mysql_query("select inst.id, inst.req_date, inst.request_by, inst.company_name, inst.user_id, inst.inst_id, inst.inst_name, inst.rtime, rcs.name as zonearea, inst.time, nda.device_imei, nda.vehicle_no, nda.device_model from installation as inst left join re_city_spr_1 as rcs   on inst.Zone_area = rcs.id left join new_device_addition as nda on inst.id=nda.inst_id where inst.time>='". $from_date." 00:00:00"."' and inst.time<='".$to_date." 23:59:59"."' and (inst.installation_status=5 or inst.installation_status=15) and (inst.branch_id='".$branch."' or inst.inter_branch='".$branch."') and inst.user_id='".$client_name."'");
            
            
            while($row_inst = mysql_fetch_array($query_inst))
                {
                    $arr=array (
                            'id' => $row_inst["id"],
                            'req_date'=> $row_inst["req_date"],
                            'request_by' => $row_inst["request_by"],
                            'company_name' => $row_inst["company_name"],
                            'inst_id' => $row_inst["inst_id"],
                            'inst_name' => $row_inst["inst_name"],
                            'veh_reg' => $row_inst["vehicle_no"],
                            'imei_done' => $row_inst["device_imei"],
                            'close_date' => $row_inst["rtime"],
                            'back_reason' => '',
                            'zonearea' => $row_inst["zonearea"],
                            'available_time' => $row_inst["time"],
                            'notwoking_make' => $row_inst["device_model"],
                            'user_id' => $row_inst["user_id"],
                            'service_inst' => "I",
                            
                            ) ;
                    array_push($data,$arr);
                }

            $query_service = mysql_query("select services.id, req_date, request_by, company_name,user_id, inst_id, inst_name, close_date, time, veh_reg, device_imei, reason, date_of_installation, re_city_spr_1.name as zonearea, Notwoking, atime from services left join re_city_spr_1   on services.Zone_area = re_city_spr_1.id  where atime>='". $from_date." 00:00:00"."' and atime<='" . $to_date." 23:59:59". "' and service_status=5 and (branch_id='".$branch."' or inter_branch='".$branch."') and user_id='".$client_name."'");
            
            while($row_service = mysql_fetch_array($query_service))
                {
                    $notworking = date("d-m-y h:i:s",strtotime($row_service['Notwoking']));
                    $arr=array (
                            'id' => $row_service["id"],
                            'req_date'=> $row_service["req_date"],
                            'request_by' => $row_service["request_by"],
                            'company_name' => $row_service["company_name"],
                            'inst_id' => $row_service["inst_id"],
                            'inst_name' => $row_service["inst_name"],
                            'close_date' => $row_service["time"],
                            'veh_reg' => $row_service["veh_reg"],
                            'imei_done' => $row_service["device_imei"],
                            'reason' => $row_service["reason"],
                            'back_reason' => '',
                            'date_of_installation' => $row_service["date_of_installation"],
                            'notwoking_make' => $notworking,
                            'zonearea' => $row_service["zonearea"],
                            'available_time' => $row_service["atime"],
                            'user_id' => $row_service["user_id"],
                            'service_inst' => "S",
                            
                            
                            ) ;
                    array_push($data,$arr);
                }

        }
        else if($mode=="Back")
         {
            $query_inst = mysql_query("select installation.id,req_date,request_by,company_name,user_id,inst_id,inst_name,back_reason,rtime,re_city_spr_1.name as zonearea, time, no_of_vehicals,installation_made,model from installation left join re_city_spr_1   on installation.Zone_area = re_city_spr_1.id where time>='". $from_date." 00:00:00"."' and time<='".$to_date." 23:59:59"."' and (installation_status=3 or installation_status=4 or installation_status=6 or (newstatus=0 and newpending=1)) and (branch_id='".$branch."' or inter_branch='".$branch."') and user_id='".$client_name."'");
            
            while($row_inst = mysql_fetch_array($query_inst))
                {
                    $arr=array (
                            'id' => $row_inst["id"],
                            'req_date'=> $row_inst["req_date"],
                            'request_by' => $row_inst["request_by"],
                            'company_name' => $row_inst["company_name"],
                            'inst_id' => $row_inst["inst_id"],
                            'inst_name' => $row_inst["inst_name"],
                            'veh_reg' => $row_inst["no_of_vehicals"],
                            'imei_done' => $row_inst["installation_made"],
                            'close_date' => $row_inst["rtime"],
                            'back_reason' => $row_inst["back_reason"],
                            'zonearea' => $row_inst["zonearea"],
                            'available_time' => $row_inst["time"],
                            'notwoking_make' => $row_inst["model"],
                            'user_id' => $row_inst["user_id"],
                            'service_inst' => "I",
                            
                            ) ;
                    array_push($data,$arr);
                }

            $query_service = mysql_query("select services.id, req_date, request_by, company_name,user_id, inst_id, inst_name, back_reason, close_date, time, veh_reg, device_imei, reason, date_of_installation, re_city_spr_1.name as zonearea, Notwoking, atime from services left join re_city_spr_1 on services.Zone_area = re_city_spr_1.id  where atime>='". $from_date." 00:00:00"."' and atime<='" . $to_date." 23:59:59". "' and (service_status=3 or service_status=4 or service_status=6 or (newstatus=0 and newpending=1)) and (branch_id='".$branch."' or inter_branch='".$branch."') and user_id='".$client_name."'");
            
            while($row_service = mysql_fetch_array($query_service))
                {
                    $notworking = date("d-m-y h:i:s",strtotime($row_service['Notwoking']));
                    $arr=array (
                            'id' => $row_service["id"],
                            'req_date'=> $row_service["req_date"],
                            'request_by' => $row_service["request_by"],
                            'company_name' => $row_service["company_name"],
                            'inst_id' => $row_service["inst_id"],
                            'inst_name' => $row_service["inst_name"],
                            'close_date' => $row_service["time"],
                            'veh_reg' => $row_service["veh_reg"],
                            'imei_done' => $row_service["device_imei"],
                            'reason' => $row_service["reason"],
                            'back_reason' => $row_service["back_reason"],
                            'date_of_installation' => $row_service["date_of_installation"],
                            'notwoking_make' => $notworking,
                            'zonearea' => $row_service["zonearea"],
                            'available_time' => $row_service["atime"],
                            'user_id' => $row_service["user_id"],
                            'service_inst' => "S",
                            
                            
                            ) ;
                    array_push($data,$arr);
                }

        }
        else
        {
            $query_inst = mysql_query("select installation.id,req_date,request_by,company_name,user_id,inst_id,inst_name,rtime,re_city_spr_1.name as zonearea, time, no_of_vehicals,installation_made,model from installation left join re_city_spr_1 on installation.Zone_area = re_city_spr_1.id where time>='". $from_date." 00:00:00"."' and time<='".$to_date." 23:59:59"."' and installation_status NOT IN(3,4,5,6,15) and (branch_id='".$branch."' or inter_branch='".$branch."') and user_id='".$client_name."'");
        
            while($row_inst = mysql_fetch_array($query_inst))
                {
                    $arr=array (
                            'id' => $row_inst["id"],
                            'req_date'=> $row_inst["req_date"],
                            'request_by' => $row_inst["request_by"],
                            'company_name' => $row_inst["company_name"],
                            'inst_id' => $row_inst["inst_id"],
                            'inst_name' => $row_inst["inst_name"],
                            'veh_reg' => $row_inst["no_of_vehicals"],
                            'imei_done' => $row_inst["installation_made"],
                            'close_date' => $row_inst["rtime"],
                            'back_reason' => '',
                            'zonearea' => $row_inst["zonearea"],
                            'available_time' => $row_inst["time"],
                            'notwoking_make' => $row_inst["model"],
                            'user_id' => $row_inst["user_id"],
                            'service_inst' => "I",
                            
                            ) ;
                    array_push($data,$arr);
                }
                
            $query_service = mysql_query("select services.id, req_date, request_by, company_name,user_id, inst_id, inst_name, close_date, time, veh_reg, device_imei, reason, date_of_installation, re_city_spr_1.name as zonearea, Notwoking, atime from services left join re_city_spr_1 on services.Zone_area = re_city_spr_1.id  where atime>='". $from_date." 00:00:00"."' and atime<='" . $to_date." 23:59:59". "' and service_status NOT IN(3,4,5,6) and (branch_id='".$branch."' or inter_branch='".$branch."') and user_id='".$client_name."'");

            while($row_service = mysql_fetch_array($query_service))
                {
                    $notworking = date("d-m-y h:i:s",strtotime($row_service['Notwoking']));
                    $arr=array (
                            'id' => $row_service["id"],
                            'req_date'=> $row_service["req_date"],
                            'request_by' => $row_service["request_by"],
                            'company_name' => $row_service["company_name"],
                            'inst_id' => $row_service["inst_id"],
                            'inst_name' => $row_service["inst_name"],
                            'close_date' => $row_service["time"],
                            'veh_reg' => $row_service["veh_reg"],
                            'imei_done' => $row_service["device_imei"],
                            'reason' => $row_service["reason"],
                            'back_reason' => '',
                            'date_of_installation' => $row_service["date_of_installation"],
                            'notwoking_make' => $notworking,
                            'zonearea' => $row_service["zonearea"],
                            'available_time' => $row_service["atime"],
                            'user_id' => $row_service["user_id"],
                            'service_inst' => "S",
                            
                            
                            ) ;
                    array_push($data,$arr);
                }
        }
    }
    elseif($branch != "" && $installer_name== "" && $client_name == "")
    {
        if($mode=="Close")
         {
            $query_inst = mysql_query("select inst.id, inst.req_date, inst.request_by, inst.company_name, inst.user_id, inst.inst_id, inst.inst_name, inst.rtime, rcs.name as zonearea, inst.time, nda.device_imei, nda.vehicle_no, nda.device_model from installation as inst left join re_city_spr_1 as rcs   on inst.Zone_area = rcs.id left join new_device_addition as nda on inst.id=nda.inst_id where inst.time>='". $from_date." 00:00:00"."' and inst.time<='".$to_date." 23:59:59"."' and (inst.installation_status=5 or inst.installation_status=15) and (inst.branch_id='".$branch."' or inst.inter_branch='".$branch."')");
            
            
            while($row_inst = mysql_fetch_array($query_inst))
                {
                    $arr=array (
                            'id' => $row_inst["id"],
                            'req_date'=> $row_inst["req_date"],
                            'request_by' => $row_inst["request_by"],
                            'company_name' => $row_inst["company_name"],
                            'inst_id' => $row_inst["inst_id"],
                            'inst_name' => $row_inst["inst_name"],
                            'veh_reg' => $row_inst["vehicle_no"],
                            'imei_done' => $row_inst["device_imei"],
                            'close_date' => $row_inst["rtime"],
                            'back_reason' => '',
                            'zonearea' => $row_inst["zonearea"],
                            'available_time' => $row_inst["time"],
                            'notwoking_make' => $row_inst["device_model"],
                            'user_id' => $row_inst["user_id"],
                            'service_inst' => "I",
                            
                            ) ;
                    array_push($data,$arr);
                }

            $query_service = mysql_query("select services.id, req_date, request_by, company_name,user_id, inst_id, inst_name, close_date, time, veh_reg, device_imei, reason, date_of_installation, re_city_spr_1.name as zonearea, Notwoking, atime from services left join re_city_spr_1   on services.Zone_area = re_city_spr_1.id  where atime>='". $from_date." 00:00:00"."' and atime<='" . $to_date." 23:59:59". "' and service_status=5 and (branch_id='".$branch."' or inter_branch='".$branch."')");
            
            while($row_service = mysql_fetch_array($query_service))
                {
                    $notworking = date("d-m-y h:i:s",strtotime($row_service['Notwoking']));
                    $arr=array (
                            'id' => $row_service["id"],
                            'req_date'=> $row_service["req_date"],
                            'request_by' => $row_service["request_by"],
                            'company_name' => $row_service["company_name"],
                            'inst_id' => $row_service["inst_id"],
                            'inst_name' => $row_service["inst_name"],
                            'close_date' => $row_service["time"],
                            'veh_reg' => $row_service["veh_reg"],
                            'imei_done' => $row_service["device_imei"],
                            'reason' => $row_service["reason"],
                            'back_reason' => '',
                            'date_of_installation' => $row_service["date_of_installation"],
                            'notwoking_make' => $notworking,
                            'zonearea' => $row_service["zonearea"],
                            'available_time' => $row_service["atime"],
                            'user_id' => $row_service["user_id"],
                            'service_inst' => "S",
                            
                            
                            ) ;
                    array_push($data,$arr);
                }

        }
        else if($mode=="Back")
         {
            $query_inst = mysql_query("select installation.id,req_date,request_by,company_name,user_id,inst_id,inst_name,back_reason,rtime,re_city_spr_1.name as zonearea, time, no_of_vehicals,installation_made,model from installation left join re_city_spr_1   on installation.Zone_area = re_city_spr_1.id where time>='". $from_date." 00:00:00"."' and time<='".$to_date." 23:59:59"."' and (installation_status=3 or installation_status=4 or installation_status=6 or (newstatus=0 and newpending=1)) and (branch_id='".$branch."' or inter_branch='".$branch."')");
            
            while($row_inst = mysql_fetch_array($query_inst))
                {
                    $arr=array (
                            'id' => $row_inst["id"],
                            'req_date'=> $row_inst["req_date"],
                            'request_by' => $row_inst["request_by"],
                            'company_name' => $row_inst["company_name"],
                            'inst_id' => $row_inst["inst_id"],
                            'inst_name' => $row_inst["inst_name"],
                            'veh_reg' => $row_inst["no_of_vehicals"],
                            'imei_done' => $row_inst["installation_made"],
                            'close_date' => $row_inst["rtime"],
                            'back_reason' => $row_inst["back_reason"],
                            'zonearea' => $row_inst["zonearea"],
                            'available_time' => $row_inst["time"],
                            'notwoking_make' => $row_inst["model"],
                            'user_id' => $row_inst["user_id"],
                            'service_inst' => "I",
                            
                            ) ;
                    array_push($data,$arr);
                }

            $query_service = mysql_query("select services.id, req_date, request_by, company_name,user_id, inst_id, inst_name, back_reason, close_date, time, veh_reg, device_imei, reason, date_of_installation, re_city_spr_1.name as zonearea, Notwoking, atime from services left join re_city_spr_1 on services.Zone_area = re_city_spr_1.id  where atime>='". $from_date." 00:00:00"."' and atime<='" . $to_date." 23:59:59". "' and (service_status=3 or service_status=4 or service_status=6 or (newstatus=0 and newpending=1)) and (branch_id='".$branch."' or inter_branch='".$branch."')");
            
            while($row_service = mysql_fetch_array($query_service))
                {
                    $notworking = date("d-m-y h:i:s",strtotime($row_service['Notwoking']));
                    $arr=array (
                            'id' => $row_service["id"],
                            'req_date'=> $row_service["req_date"],
                            'request_by' => $row_service["request_by"],
                            'company_name' => $row_service["company_name"],
                            'inst_id' => $row_service["inst_id"],
                            'inst_name' => $row_service["inst_name"],
                            'close_date' => $row_service["time"],
                            'veh_reg' => $row_service["veh_reg"],
                            'imei_done' => $row_service["device_imei"],
                            'reason' => $row_service["reason"],
                            'back_reason' => $row_service["back_reason"],
                            'date_of_installation' => $row_service["date_of_installation"],
                            'notwoking_make' => $notworking,
                            'zonearea' => $row_service["zonearea"],
                            'available_time' => $row_service["atime"],
                            'user_id' => $row_service["user_id"],
                            'service_inst' => "S",
                            
                            
                            ) ;
                    array_push($data,$arr);
                }

        }
        else
        {
            $query_inst = mysql_query("select installation.id,req_date,request_by,company_name,user_id,inst_id,inst_name,rtime,re_city_spr_1.name as zonearea, time, no_of_vehicals,installation_made,model from installation left join re_city_spr_1 on installation.Zone_area = re_city_spr_1.id where time>='". $from_date." 00:00:00"."' and time<='".$to_date." 23:59:59"."' and installation_status NOT IN(3,4,5,6,15) and (branch_id='".$branch."' or inter_branch='".$branch."')");
        
            while($row_inst = mysql_fetch_array($query_inst))
                {
                    $arr=array (
                            'id' => $row_inst["id"],
                            'req_date'=> $row_inst["req_date"],
                            'request_by' => $row_inst["request_by"],
                            'company_name' => $row_inst["company_name"],
                            'inst_id' => $row_inst["inst_id"],
                            'inst_name' => $row_inst["inst_name"],
                            'veh_reg' => $row_inst["no_of_vehicals"],
                            'imei_done' => $row_inst["installation_made"],
                            'close_date' => $row_inst["rtime"],
                            'back_reason' => '',
                            'zonearea' => $row_inst["zonearea"],
                            'available_time' => $row_inst["time"],
                            'notwoking_make' => $row_inst["model"],
                            'user_id' => $row_inst["user_id"],
                            'service_inst' => "I",
                            
                            ) ;
                    array_push($data,$arr);
                }
                
            $query_service = mysql_query("select services.id, req_date, request_by, company_name,user_id, inst_id, inst_name, close_date, time, veh_reg, device_imei, reason, date_of_installation, re_city_spr_1.name as zonearea, Notwoking, atime from services left join re_city_spr_1 on services.Zone_area = re_city_spr_1.id  where atime>='". $from_date." 00:00:00"."' and atime<='" . $to_date." 23:59:59". "' and service_status NOT IN(3,4,5,6) and (branch_id='".$branch."' or inter_branch='".$branch."')");

            while($row_service = mysql_fetch_array($query_service))
                {
                    $notworking = date("d-m-y h:i:s",strtotime($row_service['Notwoking']));
                    $arr=array (
                            'id' => $row_service["id"],
                            'req_date'=> $row_service["req_date"],
                            'request_by' => $row_service["request_by"],
                            'company_name' => $row_service["company_name"],
                            'inst_id' => $row_service["inst_id"],
                            'inst_name' => $row_service["inst_name"],
                            'close_date' => $row_service["time"],
                            'veh_reg' => $row_service["veh_reg"],
                            'imei_done' => $row_service["device_imei"],
                            'reason' => $row_service["reason"],
                            'back_reason' => '',
                            'date_of_installation' => $row_service["date_of_installation"],
                            'notwoking_make' => $notworking,
                            'zonearea' => $row_service["zonearea"],
                            'available_time' => $row_service["atime"],
                            'user_id' => $row_service["user_id"],
                            'service_inst' => "S",
                            
                            
                            ) ;
                    array_push($data,$arr);
                }
        }
    }
    
$rslt_data = $data;
/*echo "<pre>";
print_r($rslt_data);
die;*/
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
       if(document.myForm.branch_id.value ==""){
           alert("Please Select Branch");
           document.myForm.branch_id.focus();
           return false;
       }
}

var Path="<?php echo __SITE_URL;?>/"; 
//var Path="http://trackingexperts.com/service/";

function DetailClient(value)
{
    var rootdomain="http://"+window.location.hostname
    var loadstatustext="<img src='"+rootdomain+"/images/icons/other/waiting.gif' />"
    document.getElementById("DetailClient").innerHTML=loadstatustext; 
$.ajax({
        type:"GET",
        url:Path +"userInfo.php?action=DetailClient",
         data:"RowId="+value,
        success:function(msg){
             
        document.getElementById("DetailClient").innerHTML = msg;
                        
        }
    });
}
</script>

<style type="text/css" >
.errorMsg{border:1px solid red; }
.message{color: red; font-weight:bold; }
</style>

<div class="top-bar">
    
    <h1>Service & Installation Details: <?=$branch_name;?></h1>
    
<?php echo "<p align='left' style='padding-left: 250px;width: 500px;' class='message'>" .$errorMsg. "</p>" ; ?>

</div>
<div style="float:right"><br/><?
$rs = mysql_query("select * from zone where branch=".$_SESSION['BranchId']); 
 while ($row = mysql_fetch_array($rs))
 {     
     $Numberofinst = mysql_query("select installation.id from  installation left join re_city_spr_1 on installation.Zone_area=re_city_spr_1.id  where  installation.installation_status NOT IN (3,4,5,6,15) and id_region = ".$row["id"] ." and time>='".$from_date." 00:00:00"."' and time<='".$to_date." 23:59:59"."' and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");
     
      $Numberofservice = mysql_query("select services.id from  services left join re_city_spr_1 on services.Zone_area=re_city_spr_1.id  where  services.service_status NOT IN (3,4,5,6) and id_region = ".$row["id"] ." and atime>='".$from_date." 00:00:00"."' and atime<='".$to_date." 23:59:59"."' and (branch_id='".$_SESSION['BranchId']."' or inter_branch='".$_SESSION['BranchId']."')");

    $count1=mysql_num_rows($Numberofservice);

    $count2=mysql_num_rows($Numberofinst);
    
    $count_total =  $count1 + $count2;
     ?>
     
          <a href="all_branch_ser_inst.php?zone_id=<?=$row["id"];?>&from=<?=$from_date;?>&to=<?=$to_date;?>">
         
<?php     echo $row["name"]."(". $count_total.")";?>
        </a>&nbsp;&nbsp;  || &nbsp;&nbsp;&nbsp;
 
 <? } ?>


 <br/>
</div>


<div class="top-bar">
<form name="myForm" action=""   method="post">

<table cellspacing="5" cellpadding="5">

    <tr>
        <td>
            <table>
                <tr>
                    <td >From Date</td>
                    <td><input type="text" name="FromDate" id="FromDate" value="<? echo $from_date;?>"/></td>
                    
                    <td>To Date</td>
                    <td><input type="text" name="ToDate" id="ToDate"  value="<? echo $to_date;?>" /></td>
                    
                    <td>Branch</td>
                    <td><select name="branch" id="branch_id" onchange="DetailClient(this.value)">
                            <option value="">--Select--</option>
                            <?
                            $barnch_query=mysql_query("select id,branch_name from gtrac_branch where id not in(4,5) order by id asc");
                            
                            while($barnch_data=mysql_fetch_array($barnch_query)) {
                             ?>
                           <option value="<?=$barnch_data['id']?>"><?=$barnch_data['branch_name']?></option>
                            <!--<option value="<?=$barnch_data['id']?>" <? if($_POST['branch']==$barnch_data['id']) {?> selected="selected" <? } ?> ><?=$barnch_data['branch_name']?></option>-->
                            <? } ?>
                        </select></td>
                </tr>
                <tr>
                    <td colspan="6">&nbsp;</td>
                </tr>
                
                <tr>
                    <td colspan="6">
                    <div id="DetailClient">
                    
                     </div>
                    </td>
                    
                    <!--<td >Installer Name</td>
                    <td>
                      <select name="Installer_name" id="Installer_name" style="width:150px">
                        <option value="">Select Name</option>
                        <?
                        $query=mysql_query("select * from installer where branch_id=".$_SESSION['BranchId']." and is_delete=1 order by inst_name asc");
                        
                        while($data=mysql_fetch_array($query)) {
                         ?>
                        <option value="<?=$data['inst_id']?>" <? if($_POST['Installer_name']==$data['inst_id']) {?> selected="selected" <? } ?> ><?=$data['inst_name']?></option>
                        <? } ?>
                    </select></td>
                    <td >Client Name</td>
                    <td colspan="3">
                     
                     <select name="main_user_id" id="main_user_id">
                            <option value="" >-- Select One --</option>
                            <?php
                            $main_user_iddata=mysql_query("SELECT id as user_id, sys_username as name FROM matrix.users where sys_active=1");
                            while ($data=mysql_fetch_assoc($main_user_iddata))
                            {
                                if($data['user_id']==$_POST['main_user_id'])
                                {
                                    $selected="selected";
                                }
                                else
                                {
                                    $selected="";
                                }
                            ?>
                            
                            <option   value ="<?php echo $data['user_id'] ?>"  <?echo $selected;?>>
                            <?php echo $data['name']; ?>
                            </option>
                            <?php 
                            } 
                            
                            ?>
                            </select>
                    </td>-->
               </tr>
            </table>
        </td>
        <td>
        <input type="radio" name="mode" id="New" value="New" <? if($_POST["mode"]=="New") echo "checked"?> checked="checked"/>Open
        <input type="radio" name="mode" id="Back" value="Back" <? if($_POST["mode"]=="Back") echo "checked"?>/>Back
        <input type="radio" name="mode" id="Close" value="Close" <? if($_POST["mode"]=="Close") echo "checked"?>/>Close 
        </td>
        <td align="center"> <input type="submit" name="submit" value="submit" onClick="return req_validate(myForm)" /></td>
    </tr>
</table>
</form>
</div>
<div style="float:right";><a href="reportfiles/service_installation_excel.xls">Create Excel</a><br/></div>
<div class="table">
   
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>S/I</th>
            <th>Request By </th>
            <th width="10%" align="center"><font color="#0E2C3C"><b>Company Name </b></font></th>
            <th><font color="#0E2C3C"><b>Vehicle Number </b></font></th>
            <th><font color="#0E2C3C"><b>IMEI / Done </b></font></th>
            <th width="10%" align="center"><font color="#0E2C3C"><b>Notworking / Make </b></font></th>
            <th width="10%" align="center"><font color="#0E2C3C"><b>R Date</b></font> </th>
            <th width="10%" align="center"><font color="#0E2C3C"><b>A Time</b></font> </th>
            <th><font color="#0E2C3C"><b>Installer</b></font></th>
            <?php if($mode=="Back"){?>
            <th width="8%" align="center"><font color="#0E2C3C"><b>B Reason</b></font></th>
            <?php } 
             if($mode=="Close"){?>
            <th width="8%" align="center"><font color="#0E2C3C"><b>C date</b></font></th>
            <th width="8%" align="center"><font color="#0E2C3C"><b>Reason</b></font></th>
            <?php } ?>
            <th><font color="#0E2C3C"><b>Area</b></font></th>
            <th>View Detail</th>
       
        </tr>
    </thead>
    <tbody>
   
    <?php 
    $excel_data.='<table cellpadding="5" cellspacing="5" border="1"><thead><tr><td colspan="13" align="center"><strong>Service And Installation Report</strong></td></tr><tr><td colspan="12"></td></tr><tr><th width="5%">S/I</th><th width="7%">Request By </th><th width="10%">Company Name </th><th width="8%">Vehicle Number </th><th width="8%">IMEI / Done</th><th width="8%">Notworking / Make </th><th width="8%">Request Date </th><th width="8%">Available Time</th><th width="10%">Installer</th><th width="8%">Back Reason</th><th width="8%">Close date</th><th width="10%">Reason</th><th width="10%">Area</th></tr></thead><tbody>';
    
    for($i=0;$i<count($rslt_data);$i++)
    {
         if($rslt_data[$i]['service_inst']=="I"){$tablename="installation";}
         else if($rslt_data[$i]['service_inst']=="S"){$tablename="services";}
         
         if($rslt_data[$i]['company_name']!=""){$company = $rslt_data[$i]['company_name'];}
         else{
            $user_name = mysql_fetch_array(mysql_query("SELECT UserName FROM addclient WHERE Userid='".$rslt_data[$i]['user_id']."'"));    
            $company = $user_name['UserName'];
         }
         
         $req_date = date("d-m-y h:i:s",strtotime($rslt_data[$i]['req_date']));
         $available_time = date("d-m-y h:i:s",strtotime($rslt_data[$i]['available_time']));
         if($rslt_data[$i]['close_date'] != "")
         {
             $close_date = date("d-m-y h:i",strtotime($rslt_data[$i]['close_date']));
         }
         else
         {
             $close_date = "";
         }
         
         if($rslt_data[$i]['back_reason'] != "")
         {
             $back_reason = $rslt_data[$i]['back_reason'];
         }
         else
         {
             $back_reason = "";
         }
    ?>  

    <tr align="Center" >
        <!--<td><?php echo $i+1; ?></td>-->
         
        <td><?php echo $rslt_data[$i]['service_inst'];?></td>
        <td><?php echo $rslt_data[$i]['request_by'];?></td>
        <td><?php echo $company;?></td> 
        <td><?php echo $rslt_data[$i]['veh_reg'];?></td>
        <td><?php echo $rslt_data[$i]['imei_done'];?></td>
        <td><?php echo $rslt_data[$i]['notwoking_make'];?></td>
        <td><?php echo $req_date;?></td>
        <td><?php echo $available_time;?></td>
        <td><?php echo $rslt_data[$i]['inst_name'];?></td>
         <?php if($mode=="Back"){?>
        <td><?php echo $back_reason;?></td>
        <?php }  
            if($mode=="Close"){?>
        <td><?php echo $close_date;?></td>
        <td><?php echo $rslt_data[$i]['reason'];?></td>
        <?php } ?>
        <td><?php echo $rslt_data[$i]['zonearea'];?></td>
        <td><a href="#" onclick="Show_record(<?php echo $rslt_data[$i]["id"];?>,'<? echo $tablename ?>','popup1'); " class="topopup">View Detail</a></td>
           
    </tr>
    <?php 
    
    $excel_data.="<tr><td width='5%'>".$rslt_data[$i]['service_inst']."</td><td width='7%'>".$rslt_data[$i]['request_by']."</td><td width='10%'>".$company."</td><td width='8%'>".$rslt_data[$i]['veh_reg']."</td><td width='8%'>". $rslt_data[$i]['imei_done']."</td><td width='8%'>".$rslt_data[$i]['notwoking_make']."</td><td width='8%'>".$rslt_data[$i]['req_date']."</td><td width='8%'>".$rslt_data[$i]['available_time']."</td><td width='10%'>".$rslt_data[$i]['inst_name']."</td><td width='8%'>".$rslt_data[$i]['back_reason']."</td><td width='8%'>".$rslt_data[$i]['close_date']."</td><td width='10%'>".$rslt_data[$i]['reason']."</td><td width='10%'>".$rslt_data[$i]['zonearea']."</td></tr>";
     
    }
    $excel_data.='</tbody></table>';
    ?>
    </tbody>
</table>
     
   <div id="toPopup"> 
        
        <div class="close">close</div>
           <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
        <div id="popup1" style ="height:100%;width:100%"> <!--your content start-->
            

 
        </div> <!--your content end-->
    
    </div> <!--toPopup end-->
    
    <div class="loader"></div>
       <div id="backgroundPopup"></div>
</div>
 
 
 
<?
unlink(__DOCUMENT_ROOT.'/support/reportfiles/service_installation_excel.xls');
$filepointer=fopen(__DOCUMENT_ROOT.'/support/reportfiles/service_installation_excel.xls','w');
fwrite($filepointer,$excel_data);
fclose($filepointer);

include("../include/footer.inc.php");

?>