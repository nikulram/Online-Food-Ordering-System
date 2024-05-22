<?php
/**
 * Author: Nikul Ram
 * This script retrieves menu items from the database, grouped by category.
 * It returns the menu items as a JSON response.
 */

require 'db.php';

// Query to fetch all menu items ordered by category
$query = "SELECT * FROM menu_items ORDER BY category";
$result = $conn->query($query);

// Initialize an array to hold the menu items grouped by category
$menu_items = [];
while ($row = $result->fetch_assoc()) {
    $menu_items[$row['category']][] = $row;
}

// Set the content type to JSON and output the menu items
header('Content-Type: application/json');
echo json_encode($menu_items);
?>
