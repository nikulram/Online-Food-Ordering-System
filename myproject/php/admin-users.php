<?php
/**
 * Author: Nikul Ram
 * This script displays the admin users page where the admin can view all users.
 * It fetches users from the database and displays them in a table.
 */

session_start();

// Check if the user is an admin and logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}

// Connect to the database
require 'db.php';

// Fetch users from the database
$query = "SELECT * FROM users";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Admin Users</h1>
        <nav>
            <a href="../index.html">Home</a>
            <a href="admin.php">Admin Panel</a>
            <a href="admin-orders.php">Admin Orders</a>
            <a href="admin-menu.php">Admin Menu</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Users List</h2>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Admin Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['is_admin'] ? 'Yes' : 'No'; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
