<?php
$file = __DIR__ . '/command.txt';
$cmd = @file_get_contents($file);
echo $cmd ? trim($cmd) : '';
?>