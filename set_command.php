<?php
$file = __DIR__ . '/command.txt';
$light = $_GET['light'] ?? '';

if ($light === 'ON') {
    file_put_contents($file, 'LIGHT_ON');
} elseif ($light === 'OFF') {
    file_put_contents($file, 'LIGHT_OFF');
}
echo "OK";
?>