<?php
/**
 * Author: Nikul Ram
 * This script retrieves the profile information of the logged-in user.
 * It returns the user's username and email as a JSON response.
 */

session_start();
header('Content-Type: application/json');

$response = [];

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    require 'db.php';

    $user_id = $_SESSION['user_id'];
    $query = "SELECT username, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user is found and fetch their profile information
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $response['username'] = $user['username'];
        $response['email'] = $user['email'];
    } else {
        $response['error'] = 'User not found';
    }

    $stmt->close();
} else {
    $response['error'] = 'Not logged in';
}

// Return the response as JSON
echo json_encode($response);
?>
