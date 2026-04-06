<?php
date_default_timezone_set('America/Montreal');

$host = "localhost";
$dbname = "sensor_dash";
$user = "yazan";
$pass = "Mango3990";

$videoSessionFile = "session_video.json";
$recordingsDir = __DIR__ . "/recordings/saved";

if (!file_exists("session_log.jsonl")) {
    header("Location: index.php");
    exit;
}

if (!is_dir($recordingsDir)) {
    mkdir($recordingsDir, 0777, true);
}

/* -----------------------------
   STOP VIDEO RECORDING AND SAVE
----------------------------- */
if (file_exists($videoSessionFile)) {
    $videoSession = json_decode(file_get_contents($videoSessionFile), true);

    if (!empty($videoSession["pid"])) {
        $pid = (int)$videoSession["pid"];
        shell_exec("kill " . $pid);
        usleep(500000);
    }

    if (!empty($videoSession["temp_video"]) && file_exists($videoSession["temp_video"])) {
        $finalVideoName = "drive_" . date("Y_m_d_H_i_s") . ".mp4";
        $finalVideoPath = $recordingsDir . "/" . $finalVideoName;
        rename($videoSession["temp_video"], $finalVideoPath);
    }

    if (!empty($videoSession["log_file"]) && file_exists($videoSession["log_file"])) {
        unlink($videoSession["log_file"]);
    }

    unlink($videoSessionFile);
}

/* -----------------------------
   SAVE SENSOR DATA TO DATABASE
----------------------------- */
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

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

    $speed = isset($data['speed']) ? (float)$data['speed'] : 0;
    $tempC = isset($data['tempC']) ? (float)$data['tempC'] : 0;
    $voltage = isset($data['voltage']) ? (float)$data['voltage'] : 0;
    $crash = isset($data['crash']) ? (int)$data['crash'] : 0;
    $distance = isset($data['distance']) ? (float)$data['distance'] : 0;
    $light = isset($data['light']) ? (int)$data['light'] : 0;
    $lap_time = isset($data['lap_time']) ? (float)$data['lap_time'] : 0;

    $stmt->bind_param(
        "dddidid",
        $speed,
        $tempC,
        $voltage,
        $crash,
        $distance,
        $light,
        $lap_time
    );

    $stmt->execute();
}

$stmt->close();
$conn->close();

/* -----------------------------
   CLEAN UP TEMP SENSOR FILES
----------------------------- */
unlink("session_log.jsonl");

if (file_exists("latest.json")) {
    unlink("latest.json");
}

header("Location: index.php");
exit;
?>