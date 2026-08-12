<?php
/**
 * Professional Logout Handler
 * Securely destroys session variables and redirects the user with optional flash feedback.
 */

// Initialize session to access existing variables if needed
session_start();

// Unset all session variables
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session
session_destroy();

// Redirect to login page with a clean query parameter for UX feedback
header("Location: login.php?logged_out=true");
exit();
?>