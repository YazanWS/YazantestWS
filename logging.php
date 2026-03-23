<?php
include "db.php";

$state = $_GET["state"];

if ($state == "on") {
  $conn->query("UPDATE state SET logging=1 WHERE id=1");
  echo "Logging enabled";
} else {
  $conn->query("UPDATE state SET logging=0 WHERE id=1");
  echo "Logging disabled";
}
?>
