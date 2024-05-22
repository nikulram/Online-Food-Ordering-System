<?php
/**
 * Author: Nikul Ram
 * This script handles user logout.
 * It destroys the session and redirects the user to the homepage.
 */

session_start();
session_destroy();
header("Location: ../index.html");
exit();
?>
