<?php
/**
 * Author: Nikul Ram
 * This script handles adding new menu items to the database.
 * It validates inputs, sanitizes them, and inserts them into the 'menu_items' table.
 */

session_start();

// Check if the user is an admin and logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}

// Connect to the database
require 'db.php';

// Validate and sanitize inputs
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
$price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
$image_url = filter_input(INPUT_POST, 'image_url', FILTER_SANITIZE_URL);

// Validate image URL
if (!filter_var($image_url, FILTER_VALIDATE_URL) || !@getimagesize($image_url)) {
    $image_url = 'images/placeholder.jpg';
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO menu_items (name, description, price, category, image_url) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssdss", $name, $description, $price, $category, $image_url);

// Execute statement and set session messages
if ($stmt->execute()) {
    $_SESSION['success'] = 'Menu item added successfully!';
} else {
    $_SESSION['error'] = 'Failed to add menu item. Please try again.';
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Redirect back to the admin menu page
header('Location: admin-menu.php');
exit();
?>
