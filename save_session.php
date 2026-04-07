<?php
date_default_timezone_set('America/Montreal');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$dbname = "sensor_dash";
$user = "yazan";
$pass = "Mango3990";

$success = true;

if (!file_exists("session_log.jsonl")) {
    $success = false;
}

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    $success = false;
}

if ($success) {
    $table = "session_" . date("Y_m_d_H_i_s");

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
        $success = false;
    }

    $lines = file("session_log.jsonl", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $stmt = $conn->prepare("INSERT INTO `$table`
    (speed, tempC, voltage, crash, distance, light_val, lap_time)
    VALUES (?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        $success = false;
    }

    if ($success) {
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if (!$data) continue;

            $speed = (float)($data['speed'] ?? 0);
            $tempC = (float)($data['tempC'] ?? 0);
            $voltage = (float)($data['voltage'] ?? 0);
            $crash = (int)($data['crash'] ?? 0);
            $distance = (float)($data['distance'] ?? 0);
            $light_val = (int)($data['light'] ?? 0);
            $lap_time = (float)($data['lap_time'] ?? 0);

            $stmt->bind_param("dddidid",
                $speed,
                $tempC,
                $voltage,
                $crash,
                $distance,
                $light_val,
                $lap_time
            );

            if (!$stmt->execute()) {
                $success = false;
                break;
            }
        }

        $stmt->close();
    }

    $conn->close();

    if ($success) {
        unlink("session_log.jsonl");
        if (file_exists("latest.json")) unlink("latest.json");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Saving Session</title>
<meta http-equiv="refresh" content="2;url=index.php">

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
:root {
    --bg-main: #0a0a0c;
    --panel: rgba(25,25,28,0.9);
    --border: rgba(255,255,255,0.08);
    --text-main: #ffffff;
    --text-soft: #a1a1aa;
    --red: #ff2d2d;
    --green: #22c55e;
    --shadow: 0 12px 40px rgba(0,0,0,0.6);
    --glow-red: 0 0 18px rgba(255,45,45,0.35);
    --glow-green: 0 0 18px rgba(34,197,94,0.35);
}

body {
    margin:0;
    font-family:'Inter', sans-serif;
    color:var(--text-main);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    background:
        radial-gradient(circle at top, rgba(255,45,45,0.12), transparent 30%),
        linear-gradient(160deg, #050505, #0d0d10 50%, #050505);
}

.box {
    background:var(--panel);
    padding:40px 50px;
    border-radius:24px;
    border:1px solid var(--border);
    box-shadow:var(--shadow);
    text-align:center;
    position:relative;
}

.box::before {
    content:"";
    position:absolute;
    top:0;
    left:20px;
    right:20px;
    height:1px;
    background:linear-gradient(90deg, transparent, var(--red), transparent);
}

.title {
    font-family:'Orbitron';
    letter-spacing:2px;
    font-size:26px;
    margin-bottom:15px;
    text-transform:uppercase;
}

.success {
    color:var(--green);
    font-size:22px;
    margin-bottom:10px;
    text-shadow:0 0 10px rgba(34,197,94,0.4);
}

.error {
    color:var(--red);
    font-size:22px;
    margin-bottom:10px;
    text-shadow:0 0 10px rgba(255,45,45,0.4);
}

.msg {
    color:var(--text-soft);
    font-size:14px;
}

/* subtle loading dots */
.dots::after {
    content: "...";
    animation: dots 1.2s infinite;
}

@keyframes dots {
    0% { content: "."; }
    33% { content: ".."; }
    66% { content: "..."; }
}
</style>
</head>

<body>

<div class="box">
    <div class="title">Saving Session</div>

    <?php if ($success): ?>
        <div class="success">Session Saved</div>
    <?php else: ?>
        <div class="error">Save Failed</div>
    <?php endif; ?>

    <div class="msg dots">Redirecting</div>
</div>

</body>
</html>