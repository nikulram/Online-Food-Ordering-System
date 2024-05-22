<?php
/**
 * Author: Nikul Ram
 * This script handles user authentication for both signup and login actions.
 * It validates inputs, hashes passwords, and manages user sessions.
 */

// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'signup') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $email = $_POST['email'] ?? '';

        if ($username && $password && $email) {
            // Validate password length and characters
            if (strlen($password) < 8 || !preg_match('/^[a-zA-Z0-9!@#$%^&*]{8,}$/', $password)) {
                $_SESSION['error'] = "Password must be at least 8 characters long and contain only letters, numbers, and special characters (!@#$%^&*).";
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                header("Location: ../php/signup.php");
                exit();
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $_SESSION['error'] = "Username or email already taken.";
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                header("Location: ../php/signup.php");
                exit();
            }

            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashedPassword, $email);

            if ($stmt->execute()) {
                // Clear session data after successful signup
                unset($_SESSION['username']);
                unset($_SESSION['email']);
                // Redirect to the login page after successful signup
                header("Location: ../php/login.php");
                exit();
            } else {
                $_SESSION['error'] = "Error: " . $stmt->error;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                header("Location: ../php/signup.php");
            }

            $stmt->close();
        } else {
            $_SESSION['error'] = "All fields are required.";
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            header("Location: ../php/signup.php");
        }
    } elseif ($action === 'login') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username && $password) {
            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare("SELECT id, password, username FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $hashedPassword, $username);
                $stmt->fetch();

                if (password_verify($password, $hashedPassword)) {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION['is_admin'] = ($username === 'admin'); // Set admin session variable
                    // Clear session data after successful login
                    unset($_SESSION['username']);
                    // Redirect to the appropriate page after successful login
                    if ($_SESSION['is_admin']) {
                        header("Location: ../admin.html");
                    } else {
                        header("Location: ../menu.html");
                    }
                    exit();
                } else {
                    $_SESSION['error'] = "Invalid username or password.";
                    $_SESSION['username'] = $username;
                    header("Location: ../php/login.php");
                }
            } else {
                $_SESSION['error'] = "Invalid username or password.";
                $_SESSION['username'] = $username;
                header("Location: ../php/login.php");
            }

            $stmt->close();
        } else {
            $_SESSION['error'] = "Username and password are required.";
            $_SESSION['username'] = $username;
            header("Location: ../php/login.php");
        }
    } else {
        $_SESSION['error'] = "Invalid action.";
        header("Location: ../php/login.php");
    }
}

// Close the database connection
$conn->close();
?>
