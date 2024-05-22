<?php
/**
 * Author: Nikul Ram
 * This script displays the admin menu page where the admin can view, add, and delete menu items.
 * It fetches menu items from the database and groups them by category.
 */

session_start();

// Check if the user is an admin and logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}

// Connect to the database
require 'db.php';

// Fetch menu items from the database grouped by category
$query = "SELECT * FROM menu_items ORDER BY category";
$result = $conn->query($query);

// Group items by category
$menu_items = [];
while ($row = $result->fetch_assoc()) {
    $menu_items[$row['category']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Menu</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Admin Menu</h1>
        <nav>
            <a href="../index.html">Home</a>
            <a href="admin.php">Admin Panel</a>
            <a href="admin-orders.php">Admin Orders</a>
            <a href="admin-users.php">Admin Users</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Menu Items</h2>
        <?php if (isset($_SESSION['success'])): ?>
            <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <form id="addMenuItemForm" action="admin-add-menu-item.php" method="post">
            <h3>Add New Menu Item</h3>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required>
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" required>
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>
            <label for="image_url">Image URL:</label>
            <input type="text" id="image_url" name="image_url" required>
            <button type="submit">Add Item</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="menuItems">
                <?php foreach ($menu_items as $category => $items): ?>
                    <tr class="category-row">
                        <td colspan="6" class="category-title"><?php echo $category; ?></td>
                    </tr>
                    <?php foreach ($items as $item): ?>
                        <tr class="menu-item-row">
                            <td><?php echo $category; ?></td>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['description']; ?></td>
                            <td><?php echo $item['price']; ?></td>
                            <td>
                                <img src="../<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" onerror="this.onerror=null; this.src='../images/placeholder.jpg';" width="50">
                            </td>
                            <td><a href="admin-delete-menu-item.php?id=<?php echo $item['id']; ?>">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
