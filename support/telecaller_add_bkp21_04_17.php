<?php
session_start();
include("../connection.php");

/*include($_SERVER['DOCUMENT_ROOT']."/service/connection.php");*/

if(isset($_REQUEST["id"]))
{
	  $id=$_REQUEST["id"];
	  $req_id=$_REQUEST["req_id"];

}


 
 if(isset($_POST['submit']))
	 {
	 
  	 $requested_user=$_POST['requested_user'];
 
	  if($req_id=='25')
	 {
		/*$query1="update matrix.users set telecaller='".$requested_user."' where id=$id";
  		 mysql_query($query1,$dblink);*/
		 
		$query1 = array('telecaller' => $requested_user);
		$condition = array('id' => $id);
		
		update_query_live_con('matrix.users', $query1, $condition);
		 
		$matrix_query = select_query_live_con("Select * from matrix.users where id=$id");
		
		
		$inter_query = select_query("Select * from internalsoftware.users where id=$id");
		
		if($inter_query[0]['id'] == $matrix_query[0]['id'])
		{
			/*$update_query = mysql_query("update internalsoftware.users set telecaller='".$requested_user."' where id=$id",$dblink2);*/	
			$update_query = array('telecaller' => $requested_user);
			$condition = array('id' => $id);
			
			update_query('internalsoftware.users', $update_query, $condition);
		}
		else
		{
			/*$insert_query = "INSERT INTO internalsoftware.users(id, sys_active, sys_username, sys_password, fullname,company,address, email_address,mobile_number, client_type, branch_id, telecaller) VALUES('".$matrix_query['id']."','".$matrix_query['sys_active']."','".$matrix_query['sys_username']."','".$matrix_query['sys_password']."','".$matrix_query['fullname']."','".$matrix_query['company']."','".$matrix_query['address']."','".$matrix_query['email_address']."','".$matrix_query['mobile_number']."','".$matrix_query['client_type']."','".$matrix_query['branch_id']."','".$matrix_query['telecaller']."')";
	
			mysql_query($insert_query,$dblink2) or die(mysql_error());*/
			
			$insert_query = insert_query('internalsoftware.users', array('id' => $matrix_query[0]['id'] ,'sys_active' =>$matrix_query[0]['sys_active'],'sys_username' =>$matrix_query[0]['sys_username'],'sys_password' =>$matrix_query[0]['sys_password'],'fullname' =>$matrix_query[0]['fullname'],'company' =>$matrix_query[0]['company'],'address' =>$matrix_query[0]['address'],'mobile_number' =>$matrix_query[0]['mobile_number'],'client_type' =>$matrix_query[0]['client_type'],'branch_id' =>$matrix_query[0]['branch_id'],'telecaller' =>$matrix_query[0]['telecaller'],'email_address' =>$matrix_query[0]['email_address']));

		}
		
		echo "TeleCallers Name Added successfully";
		}

}

 
?>

 <html>
<head>
 
</head>
<body>


<? if(!isset($_REQUEST["view"]) && $_REQUEST["view"]!=true)
{?>

<form name="sse_add" method="post" action="telecaller_add.php?id=<?echo $_REQUEST["id"]?>&req_id=<?echo$_REQUEST["req_id"]?>">



<table border="0" cellspacing="5" cellpadding="5" align="left">
 <tr><td>Add TeleCallers Name</td></tr>
<tr>
 <td>
  <select name="requested_user" id="requested_user" width="150px">
        <option value="" >-- Select One --</option>
        <?php
        $main_city = select_query(" select name,login_name from telecaller_users where status=1 order by name asc");
        for($city=0;$city<count($main_city);$city++)
        {
			  ?>
        
        <option value ="<?php echo $main_city[$city]['login_name'] ?>"  <?echo $selected;?>>
        <?php echo $main_city[$city]['name']; ?>
        </option>
        <?php 
        } 
        
        ?>
        </select>
 </td>
 </tr>
 <tr><td><input type="submit" name="submit" value="Submit"></td></tr>
</table>
</form>

<?  
}
 
?>
</body>
</html>
