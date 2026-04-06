<?php
date_default_timezone_set('America/Montreal');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$dbname = "sensor_dash";
$user = "yazan";
$pass = "Mango3990";

if (!file_exists("session_log.jsonl")) {
    die("session_log.jsonl not found");
}

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

$table = "session_" . date("Y_m_d_H_i_s");
echo "Creating table: $table<br>";

$create = "CREATE TABLE `$table` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    speed FLOAT,
    tempC FLOAT,
    voltage FLOAT,
    crash INT,
    distance FLOAT,
    light_val INT,
    lap_time FLOAT
)";

if (!$conn->query($create)) {
    die("Create table failed: " . $conn->error);
}

echo "Table created successfully<br>";

$lines = file("session_log.jsonl", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$stmt = $conn->prepare("INSERT INTO `$table`
(speed, tempC, voltage, crash, distance, light_val, lap_time)
VALUES (?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

foreach ($lines as $line) {
    $data = json_decode($line, true);

    if (!$data) {
        continue;
    }

    $speed = isset($data['speed']) ? (float)$data['speed'] : 0;
    $tempC = isset($data['tempC']) ? (float)$data['tempC'] : 0;
    $voltage = isset($data['voltage']) ? (float)$data['voltage'] : 0;
    $crash = isset($data['crash']) ? (int)$data['crash'] : 0;
    $distance = isset($data['distance']) ? (float)$data['distance'] : 0;
    $light_val = isset($data['light']) ? (int)$data['light'] : 0;
    $lap_time = isset($data['lap_time']) ? (float)$data['lap_time'] : 0;

    $stmt->bind_param(
        "dddidid",
        $speed,
        $tempC,
        $voltage,
        $crash,
        $distance,
        $light_val,
        $lap_time
    );

    if (!$stmt->execute()) {
        die("Insert failed: " . $stmt->error);
    }
}

$stmt->close();
$conn->close();

unlink("session_log.jsonl");

if (file_exists("latest.json")) {
    unlink("latest.json");
}

echo "Done";
?>