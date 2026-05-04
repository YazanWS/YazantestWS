<?php
$command = $_POST['command'] ?? '';
$allowed = ['LIGHT_ON', 'LIGHT_OFF', ''];
if (in_array($command, $allowed)) {
    file_put_contents('/tmp/command.txt', $command);
}
echo "OK";
?>