<?php
/**
 * Author: Nikul Ram
 * This script handles order processing.
 * It processes the order data received via POST, updates the database, and returns a JSON response.
 */

session_start();
require 'db.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decode the JSON order data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'error' => 'Invalid data received.']);
        exit();
    }

    $userId = $_SESSION['user_id'];
    $totalPrice = $data['finalPrice'];
    $cart = $data['cart'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'Completed')");
        $stmt->bind_param("id", $userId, $totalPrice);
        $stmt->execute();
        $orderId = $stmt->insert_id;

        // Insert into order_items table
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        foreach ($cart as $item) {
            $stmt->bind_param("iii", $orderId, $item['id'], $item['quantity']);
            $stmt->execute();
        }

        // Commit transaction
        $conn->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => 'Failed to place order.']);
    }
}
?>
