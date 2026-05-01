<?php
$file = "command.txt";

if (file_exists($file)) {
    $cmd = file_get_contents($file);

    // optional but recommended: clear after reading
    file_put_contents($file, "");

    echo $cmd;
}
?>