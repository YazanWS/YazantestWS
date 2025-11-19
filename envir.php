<!doctype html>
<html><head><meta charset="utf-8"><title>BME280</title></head><body>
<h1>BME280 readings</h1>
<form method="post"><button type="submit" name="read" value="1">Read sensor</button></form>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
   $raw = `./bme280`;
   echo "<p>Raw: " . htmlspecialchars($raw) . "</p>";
   $data = json_decode($raw, true);
   if ($data) {
       echo "<p>Temperature: " . htmlspecialchars($data['temperature']) . " °C</p>";
       echo "<p>Pressure: " . htmlspecialchars($data['pressure']) . " Pa</p>";
       echo "<p>Altitude: " . htmlspecialchars($data['altitude']) . " m</p>";
       echo "<p>Humidity: " . htmlspecialchars($data['humidity']) . " %</p>";
   } else {
       echo "<p>Failed to parse JSON. raw output:</p><pre>" . htmlspecialchars($raw) . "</pre>";
   }
}
?>
</body></html>