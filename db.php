<?php
include "db.php";

// Read raw POST body
$json = file_get_contents("php://input");
$data = json_decode($json, true);

$speed = $data["speed"];
$tempC = $data["tempC"];
$voltage = $data["voltage"];
$crash = $data["crash"];
$timestamp = time();

// Save latest values for live display
file_put_contents("latest.json", json_encode([
  "speed" => $speed,
  "tempC" => $tempC,
  "voltage" => $voltage,
  "crash" => $crash,
  "timestamp" => $timestamp
]));

// Check logging state
$res = $conn->query("SELECT logging FROM state WHERE id=1");
$row = $res->fetch_assoc();

if ($row["logging"] == 1) {
  $conn->query("INSERT INTO logs (timestamp, speed, tempC, voltage, crash)
                VALUES ($timestamp, $speed, $tempC, $voltage, $crash)");
}

echo "OK";
?>
