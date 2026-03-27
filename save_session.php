<?php
$host = "localhost";
$dbname = "sensor_dash";
$user = "yazan";
$pass = "Mango3990";

if (!file_exists("latest.json")) {
    header("Location: index.php");
    exit;
}

$data = json_decode(file_get_contents("latest.json"), true);

if (!$data) {
    header("Location: index.php");
    exit;
}

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO sensor_data (speed, tempC, voltage, crash, distance, light)
                        VALUES (?, ?, ?, ?, ?, ?)");

$speed    = floatval($data['speed']);
$tempC    = floatval($data['tempC']);
$voltage  = floatval($data['voltage']);
$crash    = intval($data['crash']);
$distance = floatval($data['distance']);
$light    = intval($data['light']);

$stmt->bind_param("dddidi", $speed, $tempC, $voltage, $crash, $distance, $light);
$stmt->execute();

$stmt->close();
$conn->close();

unlink("latest.json");

header("Location: index.php");
exit;
?>