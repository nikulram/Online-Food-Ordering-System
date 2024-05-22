<?php
/**
 * Author: Nikul Ram
 * This script displays the admin orders page where the admin can view all orders.
 * It fetches orders from the database and displays them in a table.
 */

session_start();

// Check if the user is an admin and logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}

// Connect to the database
require 'db.php';

// Fetch orders from the database
$query = "SELECT o.id, u.username, o.total_price, o.status, o.order_date FROM orders o JOIN users u ON o.user_id = u.id";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Admin Orders</h1>
        <nav>
            <a href="../index.html">Home</a>
            <a href="admin.php">Admin Panel</a>
            <a href="admin-menu.php">Admin Menu</a>
            <a href="admin-users.php">Admin Users</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Orders List</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['total_price']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
