<?php
date_default_timezone_set('America/Montreal');

$data = [
    "speed"    => isset($_POST['speed']) ? floatval($_POST['speed']) : 0,
    "tempC"    => isset($_POST['tempC']) ? floatval($_POST['tempC']) : 0,
    "voltage"  => isset($_POST['voltage']) ? floatval($_POST['voltage']) : 0,
    "crash"    => isset($_POST['crash']) ? intval($_POST['crash']) : 0,
    "distance" => isset($_POST['distance']) ? floatval($_POST['distance']) : 0,
    "light"    => isset($_POST['light']) ? intval($_POST['light']) : 0,
    "updated_at" => date("Y-m-d H:i:s")
];

file_put_contents("latest.json", json_encode($data));

echo "OK";
?>
