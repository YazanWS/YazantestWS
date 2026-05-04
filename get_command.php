<?php
$cmd = @file_get_contents('/tmp/command.txt');
file_put_contents('/tmp/command.txt', ''); // clear after read
echo $cmd ? trim($cmd) : '';
?>