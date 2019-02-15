<?php
$servername = "localhost";
$username = "blacthrr";
$password = "7S9Yl4ih9MjV";
$dbname = "blacthrr_demo_3d";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function rand_string( $length ) {

    $chars = "HMOIJ";

    $size = strlen( $chars );

    for( $i = 0; $i < $length; $i++ ) {

        $str= $chars[ rand( 0, $size - 1 ) ];

        return $str;

    }

}
/*$sql = "SELECT id,  lat, lon  FROM bus_status";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $a=$row['lon'];
        $b=$row['lat'];
echo "[$a,$b],";
    }}*/
?>