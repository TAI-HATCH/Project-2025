<?php

// same as yhteys.php

$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=pts24_hatch", $username, $password); // connect to the database in phpMyAdmin
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Don't know, don't care
    $connection_status = "Connected successfully"; // Save the connection status as a variable $connection_status, if connection is made in 
  } catch(PDOException $e) {

    $connection_status = "Connection failed: " . $e->getMessage(); // Save the connection status as a variable $connection_status, if connection is not made in
  }
?>
