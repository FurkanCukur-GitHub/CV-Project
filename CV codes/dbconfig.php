<?php
$servername = "20.26.237.253";
$dbUsername = "vmroot";
$dbPassword = "Abc123456789.";
$dbname = "cvdb";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}
?>
