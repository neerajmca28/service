<?php
session_start();
ini_set('max_execution_time', 1500);
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php");*/
 
?> 


<div class="top-bar">
                    
        <h1>User & Sub-user Client details</h1>
  </div>
    <div class="top-bar">
        <div style="float:right";><a href="reportfiles/user_subuser_details.xls">Create Excel</a><br/></div>
        <br/>
    </div>    
    <div class="table">
<?php
 
    $query = select_query_live_con("select id,sys_username,sys_parent_user,sys_added_date,sys_active from matrix.users where sys_active=true and sys_parent_user=1");
?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>SL.No</th>
            <th>User Name</th>
			 <th>Sub-User Name</th>
            <th>Company Name</th>
            <th>Creatin Date</th>
        </tr>
    </thead>
    <tbody>

<?php 
                    
        $html.= '<table  cellspacing="1" cellpadding="2" border="1" width="100%">
                        <thead>
                                <tr>
                                        <th width="10%">SL.No</th>
										<th width="20%">User Name</th>
										<th width="20%">Sub-User Name</th>
										<th width="20%">Company Name</th>
										<th width="20%">Creatin Date</th>
                                </tr>
                     </thead>
                     <tbody>';


//echo "<pre>";print_r($query);die;

for($i=0;$i<count($query);$i++)
{ 
    $user_id = $query[$i]["id"];
    
    	
    $getcompany_name = select_query_live_con("select name from matrix.group_users left join matrix.group on group_users.sys_group_id=`group`.id where group_users.sys_user_id='".$user_id."'");
    $company_name =  $getcompany_name[0]['name'];
    
	$sno=$i+1;;
	
?>

<tr align="center">

    <td><?php echo $i+1;?></td>
    <td><?php echo $query[$i]["sys_username"];?></td>
	<td>&nbsp;</td>
	<td><?php echo $company_name;?></td>
    <td><?php echo $query[$i]["sys_added_date"];?></td>

</tr> 
<?php 
    
    $html.='<tr>
					 <td align="left">'.$sno.'</td>
					 <td align="left">'.$query[$i]["sys_username"].'</td>
					 <td align="left"></td>
					  <td align="left">'.$company_name.'</td>
					 <td align="left">'.$query[$i]["sys_added_date"].'</td>
					 
			 </tr>';
			 
			 
		    $getSub_user = select_query_live_con("select id,sys_username,sys_parent_user,sys_added_date,sys_active from matrix.users where sys_parent_user='".$user_id."' and sys_active=true");
   
			for($s=0;$s<count($getSub_user);$s++)
			{
				
				$sub_user_id = $getSub_user[$s]["id"];
				
					
				$getsub_company_name = select_query_live_con("select name from matrix.group_users left join matrix.group on group_users.sys_group_id=`group`.id where group_users.sys_user_id='".$sub_user_id."'");
				$sub_company_name =  $getsub_company_name[0]['name'];
				
				?>
				
				<tr align="center">
				
					<td><? echo $sno;?></td>
					<td>&nbsp;</td>
					<td><?php echo $getSub_user[$s]["sys_username"];?></td>
					<td><?php echo $sub_company_name;?></td>
					<td><?php echo $getSub_user[$s]["sys_added_date"];?></td>
				
				</tr> 
				<?php 
				
					$html.='<tr>
									 <td align="left">'.$sno.'</td>
									 <td align="left"></td>
									 <td align="left">'.$getSub_user[$s]["sys_username"].'</td>
									  <td align="left">'.$sub_company_name.'</td>
									 <td align="left">'.$getSub_user[$s]["sys_added_date"].'</td>
									 
							 </tr>';
				
			}
	 
			 
    
        }
   
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

unlink(__DOCUMENT_ROOT.'/support/reportfiles/user_subuser_details.xls');               
$mainHeader = '<table><tr><td colspan="4"><h2>Client User and Sub-user Details</h2></td></tr></table>';
 
$finalHtml= $mainHeader.$html;                

if(substr($finalHtml,(strlen($finalHtml)-8),strlen($finalHtml))!="</table>")
{
                $finalHtml=$finalHtml."</table>";
}

$filepointer=fopen(__DOCUMENT_ROOT.'/support/reportfiles/user_subuser_details.xls','w');
fwrite($filepointer,$finalHtml);
fclose($filepointer);


include("../include/footer.inc.php"); ?>