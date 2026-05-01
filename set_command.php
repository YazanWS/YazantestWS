<?php
if (isset($_GET['light'])) {
    $cmd = $_GET['light'];

    if ($cmd === "ON") {
        file_put_contents("command.txt", "LIGHT_ON");
    } 
    else if ($cmd === "OFF") {
        file_put_contents("command.txt", "LIGHT_OFF");
    }
}
?>