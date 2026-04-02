<?php
date_default_timezone_set('America/Montreal');

$start = file_exists("session_start.txt") ? floatval(file_get_contents("session_start.txt")) : microtime(true);

$now = microtime(true);

$lap = $now - $start;

$data = [
    "speed"    => floatval($_POST['speed'] ?? 0),
    "tempC"    => floatval($_POST['tempC'] ?? 0),
    "voltage"  => floatval($_POST['voltage'] ?? 0),
    "crash"    => intval($_POST['crash'] ?? 0),
    "distance" => floatval($_POST['distance'] ?? 0),
    "light"    => intval($_POST['light'] ?? 0),

    
    "lap_time" => round($lap, 3)
];

file_put_contents("latest.json", json_encode($data));

file_put_contents("session_log.jsonl", json_encode($data) . "\n", FILE_APPEND);

echo "OK";
?>
