<?php
if (file_exists("session_log.jsonl")) unlink("session_log.jsonl");
if (file_exists("latest.json")) unlink("latest.json");
if (file_exists("session_start.txt")) unlink("session_start.txt");

header("Location: index.php");
exit;
?>