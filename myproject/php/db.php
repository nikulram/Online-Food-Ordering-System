<?php
/**
 * Author: Nikul Ram
 * This script establishes a connection to the MySQL database.
 * It sets the character set to UTF-8 for handling special characters.
 */

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myprojectdb";

// Create connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection and handle any errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to utf8 for proper handling of special characters
$conn->set_charset("utf8");
?>
