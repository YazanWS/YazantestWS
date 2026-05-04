<?php
$cmd = @file_get_contents('/tmp/command.txt');
echo $cmd ? trim($cmd) : '';
?>