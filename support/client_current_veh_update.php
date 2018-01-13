<?php
ob_start();
ini_set('max_execution_time', 50000);
include("D:/xampp/htdocs/service/connection.php");
 

$query = select_query("select * from internalsoftware.addclient where sys_parent_user=1 and sys_active='1' order by Userid");

for($i=0;$i<count($query);$i++)
{   
    $user_id = $query[$i]["Userid"];
    
    $client=select_query_live_con("select *, case when sys_active=true then true else false end as active from matrix.users where id='$user_id'"); 
    
    if($client[0]["active"]==false)
    { $sys_active = 0; }
    else { $sys_active = 1; } 
    
    $address = trim($client[0]["address"]);
    $billing_address = trim($client[0]["billing_address"]);
    $email_address = trim($client[0]["email_address"]);
    $mobile_number = trim($client[0]["mobile_number"]);
    
    $group_user_id = select_query_live_con("SELECT sys_group_id,name FROM matrix.group_users left join matrix.group on 
                                            group_users.sys_group_id=`group`.id  where group_users.sys_user_id='".$user_id."'");

    $group_id =  $group_user_id[0]['sys_group_id'];
    $company_name =  trim($group_user_id[0]['name']);
    
    $active_query = select_query_live_con("SELECT sys_service_id FROM matrix.group_services WHERE active=1 AND sys_group_id='".$group_id."'");
    $total_vehicle = count($active_query);
    
    
    $Update_info = array('sys_group_id' => $group_id, 'sys_active' => $sys_active, 'company' =>  $company_name, 
    'current_vehicle' =>  $total_vehicle, 'address' =>  $address, 'billing_address' =>  $billing_address, 
    'email_address' =>  $email_address, 'mobile_number' =>  $mobile_number);
    $condition = array('Userid' => $user_id);        
    update_query('internalsoftware.addclient', $Update_info, $condition);
    
    
}
    
echo "Data Successfully Updated.";
?>