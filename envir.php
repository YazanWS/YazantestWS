<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>BME280 Sensor</title>
</head>
<body>
<h1>BME280 Sensor Readings</h1>
<form method="post">
<button type="submit" name="read" value="1">Read Sensor</button>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
   $raw = /home/yazan/raspberry-pi-bme280/bme280;
   echo "<pre>Raw JSON:\n" . htmlspecialchars($raw) . "</pre>";
   $data = json_decode($raw, true);
   if ($data) {
       echo "<p>Temperature: " . htmlspecialchars($data['temperature']) . " °C</p>";
       echo "<p>Pressure: " . htmlspecialchars($data['pressure']) . " Pa</p>";
       echo "<p>Humidity: " . htmlspecialchars($data['humidity']) . " %</p>";
       echo "<p>Altitude: " . htmlspecialchars($data['altitude']) . " m</p>";
   } else {
       echo "<p>Error: Could not parse JSON.</p>";
   }
}
?>
</body>
</html>