<?php

// same as yhteys.php

$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=pts24_hatch", $username, $password); // connect to the database in phpMyAdmin
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Don't know, don't care
    echo "Connected successfully"; // Print if connection is made
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage(); // Print if connection is not made
  }
?>
