<?php
function LocationFromGoogle($latlng){
           
	$ch = curl_init();
	//$url[0]="http://dynamisenterprises.co.uk/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[0]="http://www.bid-4it.co.uk//tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//2$url[1]="http://www.adttransport.co.uk/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[2]="http://skwiix.com/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[3]="http://www.g-trac.in/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//3$url[2]="http://www.financeleadhouse.com/getaddress.php?latlng=".$latlng[0].",".$latlng[1];

	 echo $url[0]="http://maincontroller00.appspot.com/maincontroller?latlng=".$latlng[0].",".$latlng[1];
	 //$url[1]="http://54.251.149.59/getAddress?latlng=".$latlng[0].",".$latlng[1];
	//$url[2]="http://54.251.178.105/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	///$url[3]="http://54.251.189.51/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[4]="http://www.adttransport.co.uk/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[5]="http://www.financeleadhouse.com/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	curl_setopt($ch, CURLOPT_URL,$url[rand(0,count($url)-1)]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0 );
	//echo strstr($html,"delhi");
	$Location =curl_exec($ch);		
	curl_close($ch);
echo "sdf";
	if($Location=="")
	{
	 
	 $url1[0]="http://54.251.149.59/getAddress?latlng=".$latlng[0].",".$latlng[1];
	//$url[2]="http://54.251.178.105/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	///$url[3]="http://54.251.189.51/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[4]="http://www.adttransport.co.uk/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[5]="http://www.financeleadhouse.com/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	curl_setopt($ch, CURLOPT_URL,$url1[rand(0,count($url1)-1)]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0 );
	//echo strstr($html,"delhi");
	$Location =curl_exec($ch);		
	curl_close($ch);
	}
	
	return $Location;

}

$latlng=explode(",",$_GET["latlng"]);
				$latlng[0]=trim($latlng[0]);
				$latlng[1]=trim($latlng[1]);
				
				
echo LocationFromGoogle($latlng);
die();
error_reporting(0);
include('cls_xml2array.php');
class master{

	function getLatLong($cityId) {

		$q 			= 	$cityId; 		
		$q			=	urlencode($q);
		
		$myFile 	= "http://maps.google.com/maps/geo?&output=csv&key=ABQIAAAAwD1OcmHTblldNGP57EK8YxQ_l8EwNPU4bdnDEeXzK6YpAOnXfhRrQvVMZyjHiVEPm5Qm6QIN1sSFmw&q=".$q; 
		
		$xml		=	@file_get_contents($myFile);

	
		return $xml ; // 0=>lat 1=>lng 2=>extra parameter not for use.
	
	}


		function getAddress($latlng ) {

		$q 			= 	$latlng; 		
		$q			=	urlencode($q);
		
		$myFile 	= "http://maps.google.com/maps/geo?&output=xml&key=ABQIAAAAwD1OcmHTblldNGP57EK8YxQ_l8EwNPU4bdnDEeXzK6YpAOnXfhRrQvVMZyjHiVEPm5Qm6QIN1sSFmw&q=".$q; 
		
		$xml		=	@file_get_contents($myFile);
		$converter 	= new Xml2Array();
		$converter->setXml($xml);
		$xml_array = $converter->get_array();



		
		if($xml_array['kml']['Response']['Placemark'][0] ==''){
		
			$lat_lng 	=	($xml_array['kml']['Response']['Placemark']['address']['#text']);
		}else {
		
					$lat_lng 	=	($xml_array['kml']['Response']['Placemark'][0]['address']['#text']);
	
		}		
	
	
		return $lat_lng ; // 0=>lat 1=>lng 2=>extra parameter not for use.
	
	}

}




$obj=new master();


$latlng=$_GET['latlng'];
$address=$_GET['address'];

	if($latlng!=""){
		echo	$obj->getAddress($latlng);
	}
	else{
		echo	$obj->getLatLong($address);
	}
//

?>