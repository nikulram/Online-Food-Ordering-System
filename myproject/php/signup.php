<?php
/**
 * Author: Nikul Ram
 * This script handles the signup page for new users.
 * It includes a form for users to enter their username, email, and password to create a new account.
 */

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- CSS file -->
</head>
<body>
    <header>
        <h1>Signup</h1>
        <nav>
            <a href="../index.html">Home</a>
            <a href="login.php">Login</a>
        </nav>
    </header>
    <main>
        <form action="signup_handler.php" method="post"> <!-- signup handling script -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Signup</button>
        </form>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
    </main>
</body>
</html>
