<?php
$host = "localhost";
$dbname = "sensor_dash";
$user = "root";
$pass = "Mango3990";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed");
}

$speed    = $_POST['speed'] ?? 0;
$tempC    = $_POST['tempC'] ?? 0;
$voltage  = $_POST['voltage'] ?? 0;
$crash    = $_POST['crash'] ?? 0;
$distance = $_POST['distance'] ?? 0;
$light    = $_POST['light'] ?? 0;

$stmt = $conn->prepare("INSERT INTO sensor_data (speed, tempC, voltage, crash, distance, light)
                        VALUES (?, ?, ?, ?, ?, ?)");

$stmt->bind_param("dddidi", $speed, $tempC, $voltage, $crash, $distance, $light);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "ERROR";
}

$stmt->close();
$conn->close();
?>