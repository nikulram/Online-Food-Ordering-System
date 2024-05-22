<?php
/**
 * Author: Nikul Ram
 * This script handles user signup.
 * It validates user input, checks for existing users, hashes the password, and inserts a new user into the database.
 * If successful, the user is redirected to the home page; otherwise, an error message is displayed.
 */

session_start();
require 'db.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username or email already exists
    $query = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Username or email already exists
        $_SESSION['error'] = 'Username or email already exists.';
        header('Location: signup.php');
        exit();
    } else {
        // Insert the new user into the database
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        if ($stmt->execute()) {
            // User registered successfully
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['is_admin'] = 0; // Default to non-admin
            header('Location: ../index.html');
            exit();
        } else {
            // Error inserting user
            $_SESSION['error'] = 'Error registering user.';
            header('Location: signup.php');
            exit();
        }
    }
}
?>
