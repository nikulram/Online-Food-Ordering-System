<?php
/**
 * Author: Nikul Ram
 * This is the login page for the Online Food Ordering System.
 * It allows users to log in with their username and password.
 */

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- CSS file -->
</head>
<body>
    <header>
        <h1>Login</h1>
        <nav>
            <a href="../index.html">Home</a>
            <a href="signup.php">Signup</a> <!-- signup.php -->
        </nav>
    </header>
    <main>
        <form action="login_handler.php" method="post"> <!-- login handling script -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Login</button>
        </form>
        <?php
        // Display error message if set in the session
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
    </main>
</body>
</html>
