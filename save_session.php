<?php
date_default_timezone_set('America/Montreal');

$host = "localhost";
$dbname = "sensor_dash";
$user = "yazan";
$pass = "Mango3990";

if (!file_exists("session_log.jsonl")) {
    header("Location: index.php");
    exit;
}

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("DB connection failed: " . $conn->connect_error);

$table = "session_" . date("Y_m_d_H_i_s");

$create = "
CREATE TABLE `$table` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    speed FLOAT,
    tempC FLOAT,
    voltage FLOAT,
    crash INT,
    distance FLOAT,
    light INT,
    lap_time FLOAT
)";
$conn->query($create);

$lines = file("session_log.jsonl", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$stmt = $conn->prepare("INSERT INTO `$table`
(speed, tempC, voltage, crash, distance, light, lap_time)
VALUES (?, ?, ?, ?, ?, ?, ?)");

foreach ($lines as $line) {
    $data = json_decode($line, true);

    $stmt->bind_param(
        "dddidi d",
        $data['speed'],
        $data['tempC'],
        $data['voltage'],
        $data['crash'],
        $data['distance'],
        $data['light'],
        $data['lap_time']
    );

    $stmt->execute();
}

$stmt->close();
$conn->close();

unlink("session_log.jsonl");
unlink("latest.json");

header("Location: index.php");
exit;
?>
