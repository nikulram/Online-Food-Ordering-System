<?php
/**
 * Author: Nikul Ram
 * This script handles retrieving and managing menu items.
 * It supports both GET and POST requests to retrieve, add, and delete menu items.
 */

session_start();
include 'db.php';

header('Content-Type: application/json');

// Handle GET request to retrieve menu items
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $menuItems = getMenuItems();
    if ($menuItems !== null) {
        echo json_encode(categorizeMenuItems($menuItems));
    } else {
        echo json_encode(['error' => 'Failed to retrieve menu items']);
    }
}

// Handle POST request to add or delete menu items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'];

    if ($action == 'add') {
        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        $category = $data['category'];
        $image_url = $data['image_url'];
        $result = addMenuItem($name, $description, $price, $category, $image_url);
        echo json_encode(['success' => $result]);
    }

    if ($action == 'delete') {
        $id = $data['id'];
        $result = deleteMenuItem($id);
        echo json_encode(['success' => $result]);
    }
}

/**
 * Retrieves all menu items from the database.
 *
 * @return array|null Array of menu items or null if retrieval fails
 */
function getMenuItems() {
    global $conn;
    $result = $conn->query("SELECT * FROM menu_items");
    if ($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        error_log("Error retrieving menu items: " . $conn->error);
        return null;
    }
}

/**
 * Categorizes menu items by their category.
 *
 * @param array $items Array of menu items
 * @return array Categorized menu items
 */
function categorizeMenuItems($items) {
    $categorizedItems = [];
    foreach ($items as $item) {
        $category = $item['category'];
        if (!isset($categorizedItems[$category])) {
            $categorizedItems[$category] = [];
        }
        $categorizedItems[$category][] = $item;
    }
    return $categorizedItems;
}

/**
 * Adds a new menu item to the database.
 *
 * @param string $name Name of the menu item
 * @param string $description Description of the menu item
 * @param float $price Price of the menu item
 * @param string $category Category of the menu item
 * @param string $image_url URL of the menu item's image
 * @return bool True if the item was added successfully, false otherwise
 */
function addMenuItem($name, $description, $price, $category, $image_url) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO menu_items (name, description, price, category, image_url) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssdss", $name, $description, $price, $category, $image_url);
        return $stmt->execute();
    } else {
        error_log("Error preparing statement: " . $conn->error);
        return false;
    }
}

/**
 * Deletes a menu item from the database.
 *
 * @param int $id ID of the menu item to delete
 * @return bool True if the item was deleted successfully, false otherwise
 */
function deleteMenuItem($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM menu_items WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    } else {
        error_log("Error preparing statement: " . $conn->error);
        return false;
    }
}
?>
