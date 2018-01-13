<?php
session_start();
include("../connection.php");

/*include($_SERVER['DOCUMENT_ROOT']."/service/connection.php");*/

if(isset($_REQUEST["id"]))
{
      $id = $_REQUEST["id"];
      $req_id = $_REQUEST["req_id"];

}


 
 if(isset($_POST['submit']))
 {
     
       $requested_user = $_POST['requested_user'];
     $client_category = $_POST['client_category'];
      
    if(($requested_user == " " || $requested_user == NULL ) && ($client_category == " " || $client_category == NULL ))
    {       
        echo "Add caller Name, Category Fields Never Blank!";
    }    
    else
    {
    
          if($req_id=='25')
          {
             
             if($requested_user != '' && $client_category == '')
			 {
				$update_query = array('telecaller_id' => $requested_user);
			 }
			 elseif($requested_user == '' && $client_category != '')
			 {
				$update_query = array('client_type' => $client_category);
			 }
			 else
			 {
				$update_query = array('telecaller_id' => $requested_user, 'client_type' => $client_category);
			 }
			 
			 //$update_query = array('telecaller_id' => $requested_user, 'client_type' => $client_category);

             $condition2 = array('Userid' => $id);            
             update_query('internalsoftware.addclient', $update_query, $condition2);
             
            /*$query1 = array('telecaller' => $requested_user);
            $condition = array('id' => $id);            
            update_query_live_con('matrix.users', $query1, $condition);
             
            $matrix_query = select_query_live_con("Select * from matrix.users where id=$id");        
            
            $inter_query = select_query("Select * from internalsoftware.addclient where Userid=$id");
            
            if($inter_query[0]['Userid'] == $matrix_query[0]['id'])
            {
                $update_query = array('telecaller_id' => $requested_user, 'client_type' => $client_category);
                $condition2 = array('Userid' => $id);
                
                update_query('internalsoftware.addclient', $update_query, $condition2);
            }
            else
            {    
                if($matrix_query[0]['branch_id'] == 1)
                    { $GroupId = '9'; $LoginName = 'rakhi'; }
                else if($matrix_query[0]['branch_id'] == 2 || $matrix_query[0]['branch_id'] == 3 || $matrix_query[0]['branch_id'] == 7)
                    { $GroupId = '10'; $LoginName = 'ankur'; }
                else if($matrix_query[0]['branch_id'] == 6)
                    { $GroupId = '11'; $LoginName = 'Amit'; }
                
                $insert_query = insert_query('internalsoftware.addclient', array('Userid' => $matrix_query[0]['id'], 
                'UserName' => $matrix_query[0]['sys_username'], 'sys_parent_user' => $matrix_query[0]['sys_parent_user'], 
                'sys_active' => $matrix_query[0]['sys_active'], 'Branch_id' => $matrix_query[0]['branch_id'], 'client_type' => $client_category, 
                'telecaller_id' => $requested_user, 'GroupId' => $GroupId, 'LoginName' => $LoginName));
    
            }*/
            
            echo "Process successfully Done.";
            
        }
        
    
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
		<option value="0" >Deattach</option>
        <?php
        $main_city = select_query("select id,name,login_name from internalsoftware.telecaller_users where status=1 order by id asc");
        for($city=0;$city<count($main_city);$city++)
        {
        ?>
        
        <option value ="<?php echo $main_city[$city]['id'] ?>"  <?echo $selected;?>>
        <?php echo $main_city[$city]['name'];?>
        </option>
        <?php 
        } 
        
        ?>
        </select>
 </td>
 </tr>
 <tr><td>Client Category</td></tr>
<tr>
 <td>
  <select name="client_category" id="client_category" width="150px">
        <option value="" >-- Select One --</option>
		<option value="0" >Deattach</option>
        <?php
        $main_category = select_query("select * from internalsoftware.client_category order by id");
        for($ct=0;$ct<count($main_category);$ct++)
        {
        ?>
        
        <option value ="<?php echo $main_category[$ct]['id'] ?>"  <?echo $selected;?>>
        <?php echo $main_category[$ct]['client_type']."(".$main_category[$ct]['category_type'].")"; ?>
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