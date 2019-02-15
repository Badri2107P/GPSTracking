<?php
require 'DB_Connect.php';
include 'header.php';


function getLocation($latitude,$longitude){
    $geocodeFromLatLong = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&sensor=false');
    $output = json_decode($geocodeFromLatLong);
    $status = $output->status;
    $address = ($status=="OK")?$output->results[3]->formatted_address:'';
    return $address;
}
function getLocation1($latitude,$longitude){
    $geocodeFromLatLong = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&sensor=false');
    $output = json_decode($geocodeFromLatLong);
    return $output;
    $status = $output->status;
    $address = ($status=="OK")? $output->results[2]->formatted_address:'';
   // return $address;

}

print_r(getLocation1(10.84904200,78.68974000));
function getAddress($latitude,$longitude){

    if(!empty($latitude) && !empty($longitude)){

        $address = getLocation($latitude,$longitude);
      //  while(empty($address)){
     //       $address = getLocation($latitude,$longitude);
    //    }
        return $address;
    }else{
        return false;
    }
}
echo '
<main class="mdl-layout__content mdl-color--grey-100">
<div class="mdl-grid demo-content">
<h1>Current Status Of Buses: </h1>
<br>
<table class=\"mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp\">
              <thead>
              <tr>
                <th class=\"mdl-data-table__cell--non-numeric\">Material</th>
                <th>Bus</th>
                <th>Location</th>
                <th>Speed</th>
              </tr>
              </thead>
              <tbody>';
$sql = "SELECT id, bus, lat, lon ,speed  FROM realtime";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

$location=getAddress($row['lat'],$row['lon']);
if($row['speed']>60)
{
    $color='red';
}else{
    $color='green';
}
        echo "
<tr>
                <td class=\'mdl-data-table__cell--non-numeric\'>Acrylic (Transparent)</td>
                <td>" .$row['bus']."</td>
                <td>" .$location."</td>
                 <td style='color: $color'>" .$row['speed']."</td>
              </tr></div></main>" ;
    }
} else {
    echo "0 results";
}
?>