<?php

$dbUser = 'root';
$dbPass = 'root';
$dbName = 'cs601hw3';
$dbHost = 'localhost';
$dbPort = 8889;
$conn = "mysql:dbname=$dbName;host=$dbHost;port=$dbPort";

try {
    $db = new PDO($conn, $dbUser, $dbPass);
} catch(PDOException $e) {
    $message = "There was a problem and we could not authenticate you.";
}

?>
