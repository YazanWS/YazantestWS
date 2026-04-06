<?php
date_default_timezone_set('America/Montreal');

$videoSessionFile = __DIR__ . "/session_video.json";

/* -----------------------------
   STOP AND DISCARD VIDEO
----------------------------- */
if (file_exists($videoSessionFile)) {
    $videoSession = json_decode(file_get_contents($videoSessionFile), true);

    if (!empty($videoSession["pid"])) {
        $pid = (int)$videoSession["pid"];
        shell_exec("kill " . $pid);
        usleep(500000);
    }

    if (!empty($videoSession["temp_video"]) && file_exists($videoSession["temp_video"])) {
        unlink($videoSession["temp_video"]);
    }

    if (!empty($videoSession["log_file"]) && file_exists($videoSession["log_file"])) {
        unlink($videoSession["log_file"]);
    }

    unlink($videoSessionFile);
}

/* -----------------------------
   DISCARD SENSOR FILES
----------------------------- */
if (file_exists("session_log.jsonl")) unlink("session_log.jsonl");
if (file_exists("latest.json")) unlink("latest.json");
if (file_exists("session_start.txt")) unlink("session_start.txt");

header("Location: index.php");
exit;
?>