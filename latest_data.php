<?php
header('Content-Type: application/json');

if (file_exists("latest.json")) {
    echo file_get_contents("latest.json");
} else {
    echo json_encode([
        "speed" => 0,
        "tempC" => 0,
        "voltage" => 0,
        "crash" => 0,
        "distance" => 0,
        "light" => 0,
        "updated_at" => null
    ]);
}
?>