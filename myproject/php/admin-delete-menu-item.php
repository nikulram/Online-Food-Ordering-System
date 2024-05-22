<?php
/**
 * Author: Nikul Ram
 * This script handles deleting menu items from the database.
 * It also deletes associated records in the 'order_items' table.
 */

session_start();

// Check if the user is an admin and logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}

// Connect to the database
require 'db.php';

// Check if the menu item ID is specified
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete associated records in order_items
        $stmt = $conn->prepare("DELETE FROM order_items WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Delete the menu item
        $stmt = $conn->prepare("DELETE FROM menu_items WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $conn->commit();

        $_SESSION['success'] = "Menu item deleted successfully.";
    } catch (mysqli_sql_exception $exception) {
        // Rollback the transaction on error
        $conn->rollback();

        $_SESSION['error'] = "Failed to delete menu item: " . $exception->getMessage();
    }

    $conn->close();
} else {
    $_SESSION['error'] = "No menu item ID specified.";
}

// Redirect back to the admin menu page
header('Location: admin-menu.php');
exit();
?>
