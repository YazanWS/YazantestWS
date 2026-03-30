<?php
if (file_exists("session_log.jsonl")) unlink("session_log.jsonl");
if (file_exists("latest.json")) unlink("latest.json");

header("Location: index.php");
exit;
?>
