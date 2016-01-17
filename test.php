<?php
    // require("requests/server_connection.php");
    // require("requests/receive_information.php");

    // $sql="ALTER TABLE FA_RESTORANTS ADD Latitude int(10)";
    // if (mysqli_query($conn,$sql))
    // {
    //     echo "works";
    // }

 	  // mysqli_close($conn);


//   $sql="CREATE FUNCTION CalculateDistance(@lat1 float, @lon1 float, @lat2 float, @lon2 float)
// RETURNS float
// WITH EXECUTE AS CALLER
// AS
// BEGIN
//    DECLARE @Distance float
//    SET @Distance = 3959 * ACOS(SIN(RADIANS(@lat1)) * SIN(RADIANS(@lat2)) + COS(RADIANS(@lat1)) * COS(RADIANS(@lat2)) * COS((RADIANS(@lon2) - RADIANS(@lon1))));
//    RETURN(@Distance);
// END;"

$coord1=array();
$coord2=array();

$coord1=getCoordinates($_GET["address"]);
$coord2=getCoordinates($_GET["address2"]);//"3050 Woodroffe Avenue");


echo $_GET["address"]." - Lat: ".$coord1[0]." Long: ".$coord1[1]."<br />";
echo $_GET["address2"]." - Lat: ".$coord2[0]." Long: ".$coord2[1];
echo "<br /><br />";

//echo CalculateDistance($coord1[0],$coord1[1],$coord2[0],$coord2[1])."<br />";
echo "Distance: ".distance($coord1[0],$coord1[1],$coord2[0],$coord2[1]);

function getCoordinates($address)
{
  $address = $address; // Google HQ
  $prepAddr = str_replace(' ','+',$address);
  $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
  $output= json_decode($geocode);
  $latitude = $output->results[0]->geometry->location->lat;
  $longitude = $output->results[0]->geometry->location->lng;

  $coordinates=array();
  $coordinates[]=$latitude;
  $coordinates[]=$longitude;

  return $coordinates;
}

function CalculateDistance($lat1,$lon1,$lat2,$lon2)
{
  $distance=3959 * acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos((deg2rad($lon2) - deg2rad($lon1))));

   return $distance;
}

 function distance($lat1, $lon1, $lat2, $lon2)
 {
  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);


  $d=rad2deg(acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon1 - $lon2))));
  $d=1.609344*60*1.1515*$d;

  return $d;
}

?>
