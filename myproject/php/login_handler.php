<?php
/**
 * Author: Nikul Ram
 * This script handles user login.
 * It validates the username and password, sets session variables, and redirects the user.
 */

session_start();
require 'db.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Prepare the query to check the database for the user
    $query = "SELECT id, password, is_admin FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password, $is_admin);

    // Check if the user exists and verify the password
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['is_admin'] = $is_admin; // Set the is_admin session variable
            header('Location: ../index.html');
            exit();
        } else {
            // Invalid password
            $_SESSION['error'] = 'Invalid username or password.';
            header('Location: login.php');
            exit();
        }
    } else {
        // No user found
        $_SESSION['error'] = 'Invalid username or password.';
        header('Location: login.php');
        exit();
    }
}
?>
