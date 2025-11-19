<!doctype html>
<html>
<head><meta charset="utf-8"><title>LED control</title></head>
<body>
<h1>LED Control</h1>
<form method="post">
<button name="action" value="on" type="submit">Turn ON</button>
<button name="action" value="off" type="submit">Turn OFF</button>
<button name="action" value="toggle" type="submit">Toggle</button>
</form>
<?php
$pin = 4; 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
   $action = $_POST['action'];
   if ($action === 'on') {
       `gpio write $pin 1`;
   } elseif ($action === 'off') {
       `gpio write $pin 0`;
   } elseif ($action === 'toggle') {
       `gpio toggle $pin`;
   }
}
$val = trim(`gpio read $pin`);
echo "<p>Pin $pin value: $val</p>";
?>
</body>
</html>