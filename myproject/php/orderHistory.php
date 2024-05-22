<?php
/**
 * Author: Nikul Ram
 * This script handles fetching the order history for a logged-in user.
 * It retrieves the order history from the database and returns it as a JSON response.
 */

session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
require 'db.php';

// Fetch order history for the logged-in user
$query = "SELECT id, total_price, status, order_date FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orderHistory = [];
while ($row = $result->fetch_assoc()) {
    $orderHistory[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($orderHistory);
?>
