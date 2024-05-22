<?php
/**
 * Author: Nikul Ram
 * This script handles the cart functionality for logged-in users.
 * It processes the cart data, creates orders, and adds order items to the database.
 */

session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

// Handle POST request for processing the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = json_decode(file_get_contents('php://input'), true);

    // Validate the cart data
    if (!$cart) {
        error_log("Cart data is empty or invalid JSON");
        echo json_encode(['error' => 'Invalid cart data']);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $total_price = array_reduce($cart, function($carry, $item) {
        return $carry + ($item['price'] * $item['quantity']);
    }, 0);

    // Create a new order
    $order_id = createOrder($user_id, $total_price);

    // Check if order creation was successful
    if (!$order_id) {
        error_log("Failed to create order for user $user_id");
        echo json_encode(['error' => 'Failed to create order']);
        exit();
    }

    // Add items to the order
    foreach ($cart as $item) {
        if (!addOrderItem($order_id, $item['id'], $item['quantity'], $item['price'])) {
            error_log("Failed to add item {$item['id']} to order $order_id");
            echo json_encode(['error' => 'Failed to add order item']);
            exit();
        }
    }

    echo json_encode(['success' => true]);
}

/**
 * Creates a new order in the database.
 *
 * @param int $user_id The ID of the user placing the order.
 * @param float $total_price The total price of the order.
 * @return int|false The ID of the new order, or false on failure.
 */
function createOrder($user_id, $total_price) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status, order_date) VALUES (?, ?, 'Pending', NOW())");
    if ($stmt) {
        $stmt->bind_param("id", $user_id, $total_price);
        if ($stmt->execute()) {
            return $conn->insert_id;
        } else {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }
    } else {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
}

/**
 * Adds an item to an order in the database.
 *
 * @param int $order_id The ID of the order.
 * @param int $menu_item_id The ID of the menu item.
 * @param int $quantity The quantity of the menu item.
 * @param float $price The price of the menu item.
 * @return bool True on success, false on failure.
 */
function addOrderItem($order_id, $menu_item_id, $quantity, $price) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("iiid", $order_id, $menu_item_id, $quantity, $price);
        return $stmt->execute();
    } else {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
}
?>
