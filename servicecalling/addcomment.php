<?php
session_start();
include("../connection.php");

if(isset($_REQUEST["serviceid"]))
{
     $service_id=$_REQUEST["serviceid"];

}
 
 $Comment_by=$_SESSION['username'];
     
     
 if(isset($_POST['submit']))
 {
     $Reason_debug = $_POST['Reason_debug'];
     if ($Reason_debug == "") {
        $Reason_debug_all = $_POST['Reason_debug_or'];
     }
     else{
        $Reason_debug_all = $_POST['Reason_debug'];
     }
     
    if($Reason_debug_all == " " || $Reason_debug_all == NULL )
    {       
        echo "Add Comment Fields Never Be Blank!";
    }    
    else
    {        
         
        // Insert in login attempt table for every attempt
        $insert_query = insert_query_live_con('matrix.comment', array('service_id' => $service_id , 'comment' =>$Reason_debug_all, 'comment_by' =>$Comment_by));
        
        $Update_query = array('is_service' => 1);
        $condition = array('sys_service_id' => $service_id,'is_active' => 1);        
        update_query('internalsoftware.client_notworking_vehicle', $Update_query, $condition);
        
        echo "Comment Added successfully";
        
    }    
    

}

 
?>

 <html>
<head>
 
</head>
<body>


<? if(!isset($_REQUEST["view"]) && $_REQUEST["view"]!=true)
{?>

<form name="FrmComment" method="post" action="addcomment.php?serviceid=<?echo $_REQUEST["serviceid"]?>">

<table border="0" cellspacing="5" cellpadding="5" align="left">
 <tr><td>Add Comment</td></tr>
 <tr>
 <td>
 
  <select name="Reason_debug" id="Reason_debug" width="150px">
        <option value="" >-- Select One --</option>
        <?php
        $main_city = select_query_live_con("select * from matrix.debug_reason");
        
        /*while($data=mysql_fetch_assoc($main_city))*/
        for($i=0;$i<count($main_city);$i++)
        {
            if($main_city[$i]['reason']==$_POST['Reason_debug'])
            {
                $selected="selected";
            }
            else
            {
                $selected="";
            }
        ?>
        
        <option value ="<?php echo $main_city[$i]['reason'] ?>"  <?echo $selected;?>>
        <?php echo $main_city[$i]['reason']; ?>
        </option>
        <?php 
        } 
        
        ?>
        </select>
        
        

 </td>
 
</tr>

<tr> <td> <textarea cols="35" rows="1" type="text" name="Reason_debug_or" id="Reason_debug_or"> </textarea> </td></tr>

 <tr><td><input type="submit" name="submit" value="Submit"></td></tr>
</table>
</form>

<?  
}
 
?>
</body>
</html>