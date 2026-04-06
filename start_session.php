<?php
date_default_timezone_set('America/Montreal');

$cameraStreamUrl = "http://192.168.137.150:81/stream"; // change to your camera stream URL
$ffmpegPath = "/usr/bin/ffmpeg";

$tmpDir = __DIR__ . "/recordings/tmp";
$savedDir = __DIR__ . "/recordings/saved";
$videoSessionFile = __DIR__ . "/session_video.json";

/* -----------------------------
   START SENSOR SESSION
----------------------------- */
file_put_contents("session_start.txt", microtime(true));

if (file_exists("session_log.jsonl")) unlink("session_log.jsonl");
if (file_exists("latest.json")) unlink("latest.json");

/* -----------------------------
   PREPARE VIDEO RECORDING FOLDERS
----------------------------- */
if (!is_dir($tmpDir)) {
    mkdir($tmpDir, 0777, true);
}

if (!is_dir($savedDir)) {
    mkdir($savedDir, 0777, true);
}

/* -----------------------------
   REMOVE OLD VIDEO SESSION FILE
----------------------------- */
if (file_exists($videoSessionFile)) {
    unlink($videoSessionFile);
}

/* -----------------------------
   START CAMERA RECORDING
----------------------------- */
$sessionId = date("Y_m_d_H_i_s");
$tempVideoPath = $tmpDir . "/session_" . $sessionId . ".mp4";
$logPath = $tmpDir . "/session_" . $sessionId . "_ffmpeg.log";

/*
   -nostdin prevents ffmpeg from waiting for terminal input
   -use_wallclock_as_timestamps helps with live streams
   libx264 + ultrafast is a good Pi-friendly starting point
*/
$cmd = "nohup " . escapeshellcmd($ffmpegPath)
    . " -nostdin -y -use_wallclock_as_timestamps 1"
    . " -i " . escapeshellarg($cameraStreamUrl)
    . " -c:v libx264 -preset ultrafast -pix_fmt yuv420p "
    . escapeshellarg($tempVideoPath)
    . " > " . escapeshellarg($logPath) . " 2>&1 & echo $!";

$pid = trim(shell_exec($cmd));

$videoSession = [
    "session_id" => $sessionId,
    "pid" => $pid,
    "temp_video" => $tempVideoPath,
    "log_file" => $logPath,
    "camera_url" => $cameraStreamUrl,
    "started_at" => date("Y-m-d H:i:s")
];

file_put_contents($videoSessionFile, json_encode($videoSession, JSON_PRETTY_PRINT));

header("Location: dashboard.php");
exit;
?>