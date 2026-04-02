<?php
date_default_timezone_set('America/Montreal');

file_put_contents("session_start.txt", microtime(true));

if (file_exists("session_log.jsonl")) unlink("session_log.jsonl");
if (file_exists("latest.json")) unlink("latest.json");

header("Location: dashboard.php");
exit;
?>
