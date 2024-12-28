<?php
$servername = "localhost";
$username = "6312231017";
$password = "P@ss1017";

try {
  $conn = new PDO("mysql:host=$servername;dbname=6312231017", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

date_default_timezone_set('Asia/Bangkok');
?>