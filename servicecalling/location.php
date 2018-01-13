<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/private/master.php');

$masterObj = new master();

function select_Procedure($query){
   
      try {
            $conn = new PDO("mysql:host=203.115.101.124;dbname=livetrack",
                            "from62", "123456From62");
             
   $sql = $query;
            $q = $conn->query($sql);
            $q->setFetchMode(PDO::FETCH_ASSOC);
        } catch (PDOException $pe) {
  die();
            //die("Error occurred:" . $pe->getMessage());
        }

   
    $r[] = $q->fetch();
               
 
   return $r;
}

if(isset($_GET['action']) && $_GET['action']=='get_location')
{

    $latitude = $_GET['latitude'];
    $longitude = $_GET['longitude'];   
    
    function GetLocationFrmGtracDB($gps_latitude,$gps_longitude)
    {
        $condition = "";
        
        $gps_latitude = str_replace($order,"",$gps_latitude);
        $gps_longitude = str_replace($order,"",$gps_longitude);
        
        if($gps_latitude == "" && $gps_longitude == ""){
            exit;
        }
        if($gps_latitude<1 && $gps_longitude<1){
            exit;
        } 
        
        $google_data_from_our_db = select_Procedure("CALL NearestGeoAddress(".$gps_latitude.", ".$gps_longitude.")");
        $latlongLoc="&nbsp;&nbsp;(".$gps_latitude.", ".$gps_longitude.")";

        if(count($google_data_from_our_db))
        {            
            /*$NearestCity = select_Procedure("CALL nearestdist(".$gps_latitude.", ".$gps_longitude.")");
        
            if(count($NearestCity))
            {
                if($NearestCity[0]['Districtname']!="")
                {
                    $city='&nbsp;&nbsp; District:-'.$NearestCity[0]['Districtname'];
                }
                else if($NearestCity[0]['state_name']!="")
                {
                    $city=' &nbsp;&nbsp; State:-'.$NearestCity[0]['state_name'];
                }
            } */

            if($google_data_from_our_db[0]['geo_street']!="")
            {
                $LocRemoveSpecialChar=$google_data_from_our_db[0]['geo_street']."  ". $google_data_from_our_db[0]['geo_town'];
                $LocRemoveSpecialChar=str_replace( array( '\'', '"', ',' , ';', '<', '>', '?' ), ' ', $LocRemoveSpecialChar);
                return $LocRemoveSpecialChar ;
            }
            else
            {
                $google_data_from_our_db = select_Procedure("CALL nearest_geo_adress_5(".$gps_latitude.", ".$gps_longitude.")");
                $latlongLoc="&nbsp;&nbsp;(".$gps_latitude.", ".$gps_longitude.").";
                
                $LocRemoveSpecialChar=$google_data_from_our_db[0]['geo_street']."  ". $google_data_from_our_db[0]['geo_town'];
                $LocRemoveSpecialChar=str_replace( array( '\'', '"', ',' , ';', '<', '>', '?' ), ' ', $LocRemoveSpecialChar);
                return  $LocRemoveSpecialChar ;
            }
        }
        else
        {
          
             return "&nbsp;&nbsp;(".$gps_latitude.", ".$gps_longitude.")";
        }


    }
    
    
    $location = GetLocationFrmGtracDB($latitude,$longitude);    
    
    echo $location;
}

exit;

?>