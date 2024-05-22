<?php
/**
 * Author: Nikul Ram
 * This script checks the current session to determine if the user is logged in and if they are an admin.
 * It returns a JSON response with the login status and admin status.
 */

session_start();
header('Content-Type: application/json');

// Initialize the response array with default values
$response = [
    'loggedIn' => false,
    'is_admin' => false
];

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $response['loggedIn'] = true;
    // Check if the user is an admin
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
        $response['is_admin'] = true;
    }
}

// Return the response as JSON
echo json_encode($response);
?>
