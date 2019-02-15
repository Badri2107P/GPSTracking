<?php
function getLocation($latitude, $longitude)
{
$geocodeFromLatLong = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($latitude) . ',' . trim($longitude) . '&sensor=false');
$output = json_decode($geocodeFromLatLong);
//print_r($output);
$status = $output->status;
$address = ($status == "OK") ? $output->results[1]->formatted_address : '';
return $address;
}

function getAddress($latitude, $longitude)
{

if (!empty($latitude) && !empty($longitude)) {
$address = getLocation($latitude, $longitude);
while(empty($address)){
    $address = getLocation($latitude, $longitude);
}
return $address;
} else {
return false;
}
}
?>
