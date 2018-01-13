<?
session_start();
include($_SERVER['DOCUMENT_ROOT']."/service/connection.php");

$vehId=intval($_GET['name']);

 $query = select_query_live_con("select services.id,veh_reg from matrix.services where services.id in 
(select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (
select sys_group_id from matrix.group_users where sys_user_id=(".$vehId.")))");

//$result=mysql_query($query,$dblink);

?>
<select name="vehicle[]" id="vehicle">
<option>Select Vehicle</option>


<? for($k=0;$k<count($query);$k++) { ?>
<option value=<?=$query[$k]['veh_reg']?>><?=$query[$k]['veh_reg']?></option>
<? } ?>
</select>

