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

$lines = file("session_log.jsonl", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$stmt = $conn->prepare("INSERT INTO sensor_data (speed, tempC, voltage, crash, distance, light)
                        VALUES (?, ?, ?, ?, ?, ?)");

foreach ($lines as $line) {
    $data = json_decode($line, true);

    $stmt->bind_param(
        "dddidi",
        $data['speed'],
        $data['tempC'],
        $data['voltage'],
        $data['crash'],
        $data['distance'],
        $data['light']
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