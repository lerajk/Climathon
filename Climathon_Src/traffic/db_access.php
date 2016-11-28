<?php


$lat = $_GET["latitude"];
$lng = $_GET["longitude"];

echo retrieve_carbon_level($lat,$lng);

    //retrieve_carbon_level($lat, $lng);
    // Send variables for the MySQL database class.
function retrieve_carbon_level($latitude,$longitude)
{
  $database = mysql_connect('localhost', 'root', '') or die('Could not connect: ' . mysql_error());
  mysql_select_db('testdb') or die('Could not select database');
  
  $sql_lat = (int)$latitude;
  $sql_lng = (int)$longitude;

 //SELECT * FROM table WHERE primary_key IN (value1, value2
  $query = "SELECT * FROM satellite_data_traffic_1 WHERE Lat_ID=$sql_lat AND Lng_ID=$sql_lng";
  $result = mysql_query($query) or die('Query failed: ' . mysql_error());
  
  $num_results = mysql_num_rows($result);

    //$closest_lat = 0;
    //$closest_lng = 0;

  $closest_distance = 999999;
  $carbon_level = -1;

  for($i = 0; $i < $num_results; $i++)
  {
   $row = mysql_fetch_array($result);
   
   $temp_lat = $row['Latitude'];
   $temp_lng = $row['Longitude'];

   $temp_distance = sqrt(($latitude - $temp_lat)^2 + ($longitude - $temp_lng)^2);

         //

   if($temp_distance < $closest_distance) 
   {
     $closest_distance = $temp_distance;
     $carbon_level = $row['8HrPedVol'];
   }


   
 }

 echo $carbon_level;
}
function getClosest($search, $arr) {
 $closest = null;
 foreach ($arr as $item) {
  if ($closest === null || abs($search - $closest) > abs($item - $search)) {
   $closest = $item;
 }
}
return $closest;
}

?>



