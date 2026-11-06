<?php
/**
 * Logout Page
 * Based on CENY6103 Module 2 - Session Destruction
 */

session_start();

// Clear session variables
$_SESSION = [];

// Destroy session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Clear remember me cookies
setcookie('plc_username', '', time() - 42000, '/');
setcookie('plc_email', '', time() - 42000, '/');

session_destroy();

// Redirect to home
header('Location: index.php?logged_out=1');
exit();
?>