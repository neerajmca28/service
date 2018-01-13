 <?
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.inc.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');
include_once(__DOCUMENT_ROOT.'/private/master.php');

/*include($_SERVER['DOCUMENT_ROOT']."/service/include/header.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/service/include/leftmenu.php"); 
include($_SERVER['DOCUMENT_ROOT']."/service/private/master.php");*/

$masterObj = new master();

?>
<div class="top-bar">
<h1> Check Data By IMEI</h1>
 
                </div>


<div style="padding-left:5px;padding-top:5px"> 
<script src="../../js/ajax.js"></script>
<?php //include('top-header.php'); ?>
<script>
function filetime(file,service_id){

	ajaxpage_get('filetime.php?qry=testimonials&file='+file+'&service_id='+service_id,'filetime','');


}
</script>
<?php
$username=trim($_POST['TxtImei']);

$searchtype=$_POST['searchtype'];
if($searchtype==""){
	$userCheck='  checked="checked"';
}
elseif($searchtype=="imei"){
	$imeiCheck='  checked="checked"';
}
elseif($searchtype=="user"){
	$userCheck='  checked="checked"';
}
?>
<script>
function submitme(){
//alert("hi");
if(document.getElementById('searchtype1').checked==true){
document.form1.action='debug.php';
}
document.form1.submit();
//document.form1.action='debug.php';
return false;
}
</script>
<form method="post" action="" onsubmit="return submitme();" name="form1">
  <input type="text" name="TxtImei" id="TxtImei" value="<?=$_POST['TxtImei'];?>" onkeyup="return countChar(this.value);">
<input type="radio" name="searchtype" id="searchtype2" value="imei"   checked="checked" style="display:none" />
<input type="submit" name="submit" value="submit">&nbsp;&nbsp;<!-- <a href='data-error-log/26-07-13.txt' target='_blank'>Last Vehcile Erros</a> -->
</form>
<br/><br/><br/><br/>

<?php
						
							if($searchtype!="imei")
							{
									if($username!="")
									{
										//$userData=select_query("select id,phone_number from matrix_users where sys_username='".$username."'");
										$userData = $masterObj->getUserDetails($username);
										
										//echo "<pre>";print_r($userData);die;
										
										$userId=$userData[0]['id'];
										$userPhoneNumber=$userData[0]['phone_number'];
									
											if($userId==""){
												$userId="2143";
											}
		
												/* $qry="select matrix_services.id,veh_reg,adddate(latest_telemetry.gps_time,INTERVAL 19800 second) as lastcontact, 
												 		round(gps_speed*1.609,0) as speed, round(gps_orientation,1) as bearing,  
														case when tel_ignition=true then true else false end as aconoff , geo_street as street, geo_town as town,
														geo_country as country,veh_reg as reg, latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng ,
														devices.imei,mobile_simcards.mobile_no from matrix_services
														join latest_telemetry on latest_telemetry.sys_service_id=matrix_services.id
														join devices on devices.id=matrix_services.sys_device_id
														join mobile_simcards on mobile_simcards.id=devices.sys_simcard
														 where matrix_services.id in 
														(select distinct sys_service_id from group_services where active=true and sys_group_id in (
														select sys_group_id from group_users where sys_user_id=(".$userId.")))";*/
									
										$data = $masterObj->ImeiSearchDetails($userId);
									
										echo "UserId= ".$userId."<br>";
										echo "Phone Number= ".$userPhoneNumber."<br>";
										echo "Total Vehicles :". count($data);
										maketablefordata($data);
									}
							}
							else{

								$imei=$username;
								if($imei!=""){
									CheckForImei($imei);
								
								}
							
							}
			

					function DateMysqlToTimestamp($md) {
							 $v = mktime ( substr($md, 11, 2) , substr($md, 14, 2), substr($md, 17, 2) , substr($md, 5, 2) , substr($md, 8, 2) , 
							substr($md, 0, 4)); 
							 return $v; 
					} 



      
           
        
		
function maketablefordata($data){

echo "<table border='1' width='100%'>";
		echo "<tr>";
		$tempdata=$data[0];
		foreach($tempdata as $key=>$value){
			echo "<td>";
			echo $key;
			echo "</td>";
		}
		echo "</tr>";

echo "</table>";
echo "<div style='height:500px;overflow:scroll;'>";
echo "<table width='100%'>";

//echo "<pre>";print_r($data);die;

for($dataCount=0;$dataCount<count($data);$dataCount++){

	?>
	<?php
							$hourDiff= (DateMysqlToTimestamp(date('Y-m-d H:i:s'))-DateMysqlToTimestamp($data[$dataCount]['lastcontact']))/3600;
							if($hourDiff>2){
								$hourDiff="<div style='color:#ffffff'><b>$hourDiff</b></div>";

															echo "<tr bgcolor=#00CCFF>";
							}
							else{
									echo "<tr bgcolor=#ECECF0>";
							}

             

?>


	<td>
	<?=$data[$dataCount]['id'];?>
	</td>
	<td>
	<?=$data[$dataCount]['veh_reg'];?>
	</td>
	<td>
	<?php echo $hourDiff; ?>
	<?=$data[$dataCount]['lastcontact'];?>
	</td>
	<td>
	<?=$data[$dataCount]['speed'];?>
	</td>
	<td>
	<?=$data[$dataCount]['bearing'];?>
	</td>
	<td>
	<?=$data[$dataCount]['aconoff'];?>
	</td>
	<td>
	<?=$data[$dataCount]['street'];?>
	</td>
	<td>
	<?=$data[$dataCount]['town'];?>
	</td>
	<td>
	<?=$data[$dataCount]['country'];?>
	</td>
	<td>
	<?=$data[$dataCount]['reg'];?>
	</td>
	<td>
	<?=$data[$dataCount]['lat'];?>
	</td>
	<td>
	<?=$data[$dataCount]['lng'];?>
	</td>
<!-- 	<td onclick="filetime('<?php echo $data[$dataCount]['imei'].".mtx"; ?>',<?php echo $data[$dataCount]['id']; ?>)"> -->
	<td>
	<?=$data[$dataCount]['imei'];?>
	</td>
	<td>
	<?=$data[$dataCount]['mobile_no'];?>
	</td>
	</tr>
	<?php
	
}
	return;


echo "</table>";	
echo "</div>";
}
						function CheckForImei($imei){
									$file=$imei.".csv";
									$file1=$imei.".txt";
							


		
											$i=0;
											while($i<4){

//												$folder=((date('d')/1)-$i).(date('m')/1).date('Y');

												$timestamp=DateMysqlToTimestamp(date('Y-m-d H:i:s'))-(86400)*($i);
												$d= date('d',$timestamp)/1;
												$m= date('m',$timestamp)/1;
												$y= date('Y',$timestamp)/1;
												$folder= $d.$m.$y;

												if(strlen($imei)==15)
												{
													$path="C:\ProcessingTest\TelTonika\\".$folder."\\".$file."";
													$errorFileFolder="C:\ProcessingTest\TelTonika\\";
												}
												else
												{
													$path="C:\ProcessingTest\TrakM8\\".$folder."\\".$file."";
													$errorFileFolder="C:\ProcessingTest\TrakM8\\";
												}
												if(strlen($imei)==6 )
															{ 
														$path="C:\ProcessingTest\AVL\\".$folder."\\".$file1."";
														$errorFileFolder="C:\ProcessingTest\AVL\\";
														  }
												 


												
												/*if(strlen($imei)<=7 || strlen($imei)<=6)
												{
													$path="L:\back usb drive\CoreDataAfterMarch\Pointer\\".$folder."\\".$file."";
													$errorFileFolder="L:\back usb drive\CoreDataAfterMarch\Pointer\\";
												}*/

												if(strlen($imei)<=7 || strlen($imei)<=6)
												{
													$path="L:\back usb drive\CoreDataAfterMarch\Pointer\\".$folder."\\".$file."";
													$errorFileFolder="L:\back usb drive\CoreDataAfterMarch\Pointer\\";
													$time=@filemtime($path);
													if($time=="")
																{
															  $path="L:\back usb drive\CoreDataAfterMarch\Pointer\\".$folder."\\RawData_".$file1."";
													$errorFileFolder="L:\back usb drive\CoreDataAfterMarch\Pointer\\";
														  $time=@filemtime($path);
																}
												}

												

												
												$time=@filemtime($path);

												if($time==""){

															if(strlen($imei)==15)
															{											 
													 
														$path="C:\ProcessingTest\Atlanta\\".$folder."\\".$file."";
														$errorFileFolder="C:\ProcessingTest\Atlanta\\";
														$time=@filemtime($path);
															}

															




															if(strlen($imei)==10)
															{											 
													 
														$path="C:\ProcessingTest\Argus\\".$folder."\\".$file."";
														$errorFileFolder="C:\ProcessingTest\Argus\\";
														$time=@filemtime($path);
															}

															if(strlen($imei)==4)
															{											 
													 
														$path="C:\ProcessingTest\AEM\\".$folder."\\".$file."";
														$errorFileFolder="C:\ProcessingTest\AEM\\";
														$time=@filemtime($path);
															}
												}
														if($time==""){
															//echo "File Not Exists For ".((date('d')/1)-$i)."-".(date('m')/1)."-".date('Y');
															echo "File Not Exists For ".$d."-".$m."-".$y;
															echo "<br>";
															if($i==3){
																exit;
															}
														}
														else{
															 
															
															$errorFile=$errorFileFolder.$folder."\\InsertionFailedLog_".$imei.".txt"."";
															break;
														}
														$i++;

											}


//									$time=$time+19800;
									
									$time=date("Y/m/d H:i:s",$time);
									$tempHTML.= "File Modified Time=<b>".$time."</b><br>";

									$fp=fopen($path,'r');
 
									$filedatatext=fread($fp,filesize($path));

									$fp=@fopen($errorFile,'r');
									$errorFiledata=@fread($fp,filesize($path));
 
	
	$fp=fopen($path,'r');
 
     $filedata=fread($fp,filesize($path));

	 if(isset($_POST['submit']))
		{
			$masterObj = new master();

			/*$data=select_query("select id,sys_simcard from devices where imei='".$_POST['TxtImei']."'");*/
			$data = $masterObj->getDeviceId($_POST['TxtImei']);
            //echo "<pre>";print_r($data);
			
			/*$service_data=select_query('select id,log,veh_reg from matrix_services where sys_device_id =('.$data[0]['id'].')');*/
			$service_data = $masterObj->getVehicleNo($data[0]['id']);
			//echo "<pre>";print_r($service_data);die;
			
             $veh_reg=$service_data[0]['veh_reg'];
			 $sys_user_name=$service_data[0]['log'];
		
		list($a, $b, $c, $d, $e, $f , $g) = preg_split("/--------------------------------------/", $sys_user_name);
				 $logstr1 =  "$f<br />\n";
		
		 $strlength=strlen($_POST['TxtImei']);
		 switch($strlength)
		 {
		 
		  //pointer code
		 
		 	case 7:
			 $splittedstring=explode('ID-',$filedata);
			$datacount = count($splittedstring);
			
			$value=$splittedstring[ $datacount - 1];
			
			list($a, $b, $c, $d, $e, $f , $g , $h ,$i, $j, $k, $l, $m) = preg_split("/[\s,]/", $value);
			$pointerstr = "$d $e $f $g $j $k $l<br />\n"; 
			
			list($tr, $t) = preg_split('/Indiadate-/', $d);
			 $date = "$t<br />\n"; 
			
			list($tr) = preg_split('/ /', $e);
			 $time = "$tr<br />\n"; 
			
			list($tr, $t) = preg_split('/-/', $f);
			$lat = "$t<br />\n"; 
			
			list($tr, $t) = preg_split('/-/', $g);
			$long = "$t<br />\n";
			
			list($tr, $t) = preg_split('/-/', $j);
			$ign = "$t<br />\n";
			
			list($tr, $t) = preg_split('/-/', $k);
			$sat = "$t<br />\n";
		
			list($tr, $t) = preg_split('/-/', $l);
			$gps = "$t<br />\n";	?>
			 

			<div style="font-weight:bold;font-size:14px">
				<span class="style3">IMEI => <?=$_POST['TxtImei'];?></span><br/>
				<span class="style3">VEHICLE NO =><? echo $veh_reg;?></span><br/>
				<span class="style3"><? echo $logstr1;?></span>
				<span class="style3">LAST RECORD  =></span>
				<span class="style3">
				<?php
				print_r($pointerstr);
				
				?>
				</span>
				
				<br/><br/>
 		
				<table   border="1">
				<tr>
				<td  height="19"><span class="style3">GPS TIME STATUS </span></td>
				<td  ><span class="style3">LAT LONG STATUS </span></td>
				<td  ><span class="style3">AC STATUS</span></td>
				<td  > <span class="style3">SATELLITE STATUS</span></td>
				<td  ><span class="style3">GPS STATUS</span></td>
				</tr>
				<tr>
				<td height="22">
				<span class="style2"><?php
				$todaydate = @date('d-m-Y');
				$time_now=@mktime(@date('G'),@date('i'),@date('s'));
				$NowisTime=@date('G:i:s',$time_now);
				$endtime = @date('G:i:s', strtotime("-5 minutes", strtotime($NowisTime))); 
				
				/* echo $todaydate;
				echo $NowisTime;
				echo $endtime;*/
				
				if($NowisTime >= $time and $time >= $endtime and $date >= $todaydate) {
				$image = "green.jpg"; 
				echo "<img src=".$image." Style=width:35px;height:35px;>"."<br />";
				}  else {
				$image1 = "red.png";  
				echo "<img src=".$image1." Style=width:31px;height:31px;>"."<br />";
				} 
				?>
				</span></td>
				<td> <span class="style2">
				<?php
				
				if ($lat ==0 and $long ==0){
				$image = "red.png";
				echo "<img src=".$image." Style=width:31px;height:31px;>"."<br />";
				}  else {
				$image1 = "green.jpg";  
				echo "<img src=".$image1." Style=width:35px;height:35px;>"."<br />";
				} 
				?>
				</span></td>
				<td><span class="style2">
				<?php
				if ($ign ==0){
				$image = "red.png";
				echo "<img src=".$image." Style=width:31px;height:31px;>"."<br />";
				}  else {
				$image1 = "green.jpg";  
				echo "<img src=".$image1." Style=width:35px;height:35px;>"."<br />";
				} 
				?>
				</span></td>
				<td>
				<span class="style2">
				<?php
				if ($sat >=3){
				$image = "green.jpg";
				echo "<img src=".$image." Style=width:35px;height:35px;>"."<br />";
				}  else {
				$image1 = "red.png";  
				echo "<img src=".$image1." Style=width:31px;height:31px;>"."<br />";
				} 
				?>
				</span></td>
				<td>
				<span class="style2">
				<?php
				if ($gps ==0){
				$image = "red.png";
				echo "<img src=".$image." Style=width:31px;height:31px;>";
				}  else {
				$image1 = "green.jpg";  
				echo "<img src=".$image1." Style=width:35px;height:35px;>";
				} 
				?>
				</span></td>
				</tr>
              </table>
			  <br/>
			  <textarea cols="150" rows="15" wrap="hard"><?=$filedatatext?></textarea>
				
			<?php	
			
			//visiontek code
			
			 break;
			 case 6:
			 $splittedstring=explode('ID-',$filedata);
			$datacount = count($splittedstring);
			
			$value=$splittedstring[ $datacount - 1];
			
			list($a, $b, $c, $d, $e, $f , $g , $h ,$i, $j, $k, $l, $m) = preg_split("/[\s,]/", $value);
			$pointerstr = "$d $e $f $g $j $k $l<br />\n"; 
			
			list($tr, $t) = preg_split('/Indiadate-/', $d);
			  $date = "$t<br />\n"; 
			
			list($tr) = preg_split('/ /', $e);
			  $time = "$tr<br />\n"; 
			
			list($tr, $t) = preg_split('/-/', $f);
			$lat = "$t<br />\n"; 
			
			list($tr, $t) = preg_split('/-/', $g);
			$long = "$t<br />\n";
			
			list($tr, $t) = preg_split('/-/', $j);
			$ign = "$t<br />\n";
			
			list($tr, $t) = preg_split('/-/', $k);
			$sat = "$t<br />\n";
			
			list($tr, $t) = preg_split('/-/', $l);
			$gps = "$t<br />\n";	?> 
			 

			<div style="font-weight:bold;font-size:14px">
				<span class="style3">IMEI => <?=$_POST['TxtImei'];?></span><br/>
				<span class="style3">VEHICLE NO =><? echo $veh_reg;?></span><br/>
				<span class="style3"><? echo $logstr1;?></span>
				<span class="style3">LAST RECORD  =></span>
				<span class="style3">
				<?php
				print_r($pointerstr);
				
				?>
				</span>
				
				<br/><br/>
 		
				<table   border="1">
				<tr>
				<td  height="19"><span class="style3">GPS TIME STATUS </span></td>
				<td  ><span class="style3">LAT LONG STATUS </span></td>
				<td  ><span class="style3">AC STATUS</span></td>
				<td  > <span class="style3">SATELLITE STATUS</span></td>
				<td  ><span class="style3">GPS STATUS</span></td>
				</tr>
				<tr>
				<td height="22">
				<span class="style2"><?php
				 $todaydate = @date('Y-m-d');
				 $time_now=@mktime(@date('G'),@date('i'),@date('s'));
				 $NowisTime=@date('G:i:s',$time_now);
				 $endtime = @date('G:i:s', strtotime("-5 minutes", strtotime($NowisTime))); 
				
				/* echo $todaydate;
				echo $NowisTime;
				echo $endtime;*/
				
				if($NowisTime >= $time and $time >= $endtime and $date >= $todaydate) {
				$image = "green.jpg"; 
				echo "<img src=".$image." Style=width:35px;height:35px;>"."<br />";
				}  else {
				$image1 = "red.png";  
				echo "<img src=".$image1." Style=width:31px;height:31px;>"."<br />";
				} 
				?>
				</span></td>
				<td> <span class="style2">
				<?php
				
				if ($lat ==0 and $long ==0){
				$image = "red.png";
				echo "<img src=".$image." Style=width:31px;height:31px;>"."<br />";
				}  else {
				$image1 = "green.jpg";  
				echo "<img src=".$image1." Style=width:35px;height:35px;>"."<br />";
				} 
				?>
				</span></td>
				<td><span class="style2">
				<?php
				if ($ign ==0){
				$image = "red.png";
				echo "<img src=".$image." Style=width:31px;height:31px;>"."<br />";
				}  else {
				$image1 = "green.jpg";  
				echo "<img src=".$image1." Style=width:35px;height:35px;>"."<br />";
				} 
				?>
				</span></td>
				<td>
				<span class="style2">
				<?php
				if ($sat >=3){
				$image = "green.jpg";
				echo "<img src=".$image." Style=width:35px;height:35px;>"."<br />";
				}  else {
				$image1 = "red.png";  
				echo "<img src=".$image1." Style=width:31px;height:31px;>"."<br />";
				} 
				?>
				</span></td>
				<td>
				<span class="style2">
				<?php
				if ($gps ==0){
				$image = "red.png";
				echo "<img src=".$image." Style=width:31px;height:31px;>";
				}  else {
				$image1 = "green.jpg";  
				echo "<img src=".$image1." Style=width:35px;height:35px;>";
				} 
				?>
				</span></td>
				</tr>
              </table>
			  <br/>
			  <textarea cols="150" rows="15" wrap="hard"><?=$filedatatext?></textarea>
				
			<?php	
			
			//visiontek code
			
			 break;
		     case 10:
			$splittedstring=explode('$',$filedata);
			
				
			$datacount = count($splittedstring);
			
			$value=$splittedstring[ $datacount - 1]."<br />\n";
			
			list($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n) = preg_split("/[,#]/", $value);
			$str = "$c $d $e $f $g $h $i $j $n<br />\n"; 
			
			$date = "20$e-$d-$c";
			$oldtime = "$f:$g:$h";
			
			$oldtime = @strtotime("$f:$g:$h");
			$time = @date("H:i:s", strtotime('+330 minutes', $oldtime));
			
			$lat = "$i"; 
			
			$long = "$j"; 
			
			$gps = "$n";
			?>
			<div style="font-weight:bold;font-size:14px">
			<span class="style3">IMEI => <?=$_POST['TxtImei'];?></span><br/>
		    <span class="style3">VEHICLE NO =><? echo $veh_reg;?></span><br/>
				<span class="style3"><? echo $logstr1;?></span>
				<span class="style3">LAST RECORD  =></span>
			<span class="style3">
			<?php
			
			echo $data ="$date $time $lat $long $gps"; 
			
			?>
			</span> 
			</div>
			<br/><br/>
			<table  border="1">
			<tr>
			<td   height="19"><span class="style3">GPS TIME STATUS</span></td>
			<td ><span class="style3">LAT LONG STATUS</span></td>
			<td  ><span class="style3">AC STATUS</span></td>
			<td  ><span class="style3">GPS STATUS</span></td>
			</tr>
			<tr>
			<td height="22">
			<span class="style2"><?php
			$todaydate = @date('Y-m-d');
			$time_now=@mktime(@date('G'),@date('i'),@date('s'));
			$NowisTime=@date('G:i:s',$time_now);
			$endtime = @date('G:i:s', strtotime("-5 minutes", strtotime($NowisTime))); 
			
			
			if($NowisTime >= $time and $time >= $endtime and $date >= $todaydate) {
			$image = "green.jpg"; 
			echo "<img src=".$image." Style=width:35px;height:35px;>"."<br />";
			}  else {
			$image1 = "red.png";  
			echo "<img src=".$image1." Style=width:31px;height:31px;>"."<br />";
			} 
			?>
			</span></td>
			<td> <span class="style2">
			<?php
			
			if ($lat ==0 and $long ==0){
			$image = "red.png";
			echo "<img src=".$image." Style=width:31px;height:31px;>"."<br />";
			}  else {
			$image1 = "green.jpg";  
			echo "<img src=".$image1." Style=width:35px;height:35px;>"."<br />";
			} 
			?>
			</span></td>
			<td><span class="style2">
			<?php
			if ($ign ==0){
			$image = "red.png";
			echo "<img src=".$image." Style=width:31px;height:31px;>"."<br />";
			}  else {
			$image1 = "green.jpg";  
			echo "<img src=".$image1." Style=width:35px;height:35px;>"."<br />";
			} 
			?>
			</span></td>
			
			<td>
			<span class="style2">
			<?php
			if ($gps ==V){
			$image = "red.png";
			echo "<img src=".$image." Style=width:31px;height:31px;>";
			}  else {
			$image1 = "green.jpg";  
			echo "<img src=".$image1." Style=width:35px;height:35px;>";
			} 
			?>
			</span></td>
			</tr>
			</table>
			<br/>
			  <textarea cols="150" rows="15" wrap="hard"><?=$filedatatext?></textarea>
			<?php
			
			//teltonika code
			
			break;
			case 15:
			 $splittedstring=explode("\n",$filedata);
			
			$datacount = count($splittedstring);
			
			$value=$splittedstring[ $datacount - 2]."<br/>";
			
			list($a, $b, $c, $d, $e, $f , $g , $h ,$i, $j, $k, $l) = preg_split("/[\s,]+/", $value);
			 $str = "$c $d $j $k $l<br />\n"; 
			
			$date=$k."<br/>";
			$time=$l."<br/>";
			$ign=$j."<br/>";
			$lat=$c."<br/>";
			$long=$d."<br/>";
			?><div style="font-weight:bold;font-size:14px">
			<span class="style3">IMEI => <?=$_POST['TxtImei'];?></span><br/>
				<span class="style3">VEHICLE NO =><? echo $veh_reg;?></span><br/>
				<span class="style3"><? echo $logstr1;?></span>
				<span class="style3">LAST RECORD  =></span>
			<span class="style3">
			<?php
			
			print_r($str);
			
			?>
			</span>
			<br/>
			
			<table  border="1">
			<tr>
			<td height="19"><span class="style3">GPS TIME STATUS</span></td>
			<td  ><span class="style3">LAT LONG STATUS</span></td>
			<td ><span class="style3">AC STATUS</span></td>
			<td  ><span class="style3">GPS STATUS</span></td>
			</tr>
			<tr>
			<td height="22">
			<span class="style2"><?php
			$todaydate = @date('Y-m-d');
			$time_now=@mktime(@date('G'),@date('i'),@date('s'));
			$NowisTime=@date('G:i:s',$time_now);
			$endtime = @date('G:i:s', strtotime("-5 minutes", strtotime($NowisTime))); 
			
			if($NowisTime >= $time and $time >= $endtime and $date >= $todaydate) {
			$image = "green.jpg"; 
			echo "<img src=".$image." Style=width:35px;height:35px;>"."<br />";
			}  else {
			$image1 = "red.png";  
			echo "<img src=".$image1." Style=width:31px;height:31px;>"."<br />";
			} 
			?>
			</span></td>
			<td> <span class="style2">
			<?php
			
			if ($lat ==0 and $long ==0){
			$image = "red.png";
			echo "<img src=".$image." Style=width:31px;height:31px;>"."<br />";
			}  else {
			$image1 = "green.jpg";  
			echo "<img src=".$image1." Style=width:35px;height:35px;>"."<br />";
			} 
			?>
			</span></td>
			<td><span class="style2">
			<?php
			if ($ign ==0){
			$image = "red.png";
			echo "<img src=".$image." Style=width:31px;height:31px;>"."<br />";
			}  else {
			$image1 = "green.jpg";  
			echo "<img src=".$image1." Style=width:35px;height:35px;>"."<br />";
			} 
			?>
			</span></td>
			
			<td>
			<span class="style2">
			<?php
			if ($NowisTime >= $time and $time >= $endtime and $date >= $todaydate and $lat !=0 and $long !=0){
			$image = "green.jpg";
			echo "<img src=".$image." Style=width:35px;height:35px;>";
			}  else {
			$image1 = "red.png";  
			echo "<img src=".$image1." Style=width:31px;height:31px;>";
			} 
			?>
			</span></td>
			</tr>
  			</table>  <br/>
			  <textarea cols="150" rows="15" wrap="hard"><?=$filedatatext?></textarea>
			<?php
			
			break;
			
			}
			
			}
			 

 
						}?>
						

 


</form>
</body>
</html>