<?php
/**
 * User Dashboard Page
 * Based on CENY6103 Module 2 - Session Management
 * 
 * Features:
 * - Display logged-in user information
 * - Session timeout check
 * - Navigation to profile and logout
 */

// Start session
session_start();

// Set page variables
$page_title = "Dashboard - Pizza Lovers Club";
$page_description = "Your Pizza Lovers Club dashboard";

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Check session timeout (30 minutes of inactivity)
$timeout = 30 * 60; // 30 minutes in seconds
if (isset($_SESSION['last_activity'])) {
    $session_life = time() - $_SESSION['last_activity'];
    if ($session_life > $timeout) {
        // Session expired
        session_unset();
        session_destroy();
        header('Location: login.php?expired=1');
        exit();
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Include header
include 'includes/header.php';

// Get user info from session
$username = $_SESSION['username'] ?? 'User';
$email = $_SESSION['email'] ?? '';
$login_time = isset($_SESSION['login_time']) ? date('F j, Y \a\t g:i A', $_SESSION['login_time']) : '';
$remember_me = $_SESSION['remember_me'] ?? false;
?>

<div class="form-container">
    <div class="page-header">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>! 🍕</h1>
        <p>You are now a member of Pizza Lovers Club</p>
    </div>

    <div class="success-alert">
        <div>
            <strong>Registration Successful!</strong>
            <p>Your account has been created and you are now logged in.</p>
        </div>
    </div>

    <!-- User Information Card -->
    <div class="card">
        <h2>Your Account Information</h2>
        
        <div class="user-info">
            <p>
                <strong>Username:</strong>
                <span><?php echo htmlspecialchars($username); ?></span>
            </p>
            <p>
                <strong>Email:</strong>
                <span><?php echo htmlspecialchars($email); ?></span>
            </p>
            <p>
                <strong>Logged In Since:</strong>
                <span><?php echo $login_time; ?></span>
            </p>
            <p>
                <strong>Session Status:</strong>
                <span>✓ Active (30 min timeout)</span>
            </p>
            <?php if ($remember_me): ?>
                <p>
                    <strong>Remember Me:</strong>
                    <span>✓ Enabled (30 days)</span>
                </p>
            <?php endif; ?>
        </div>

        <h3 style="margin-top: 24px;">Session Information</h3>
        <p>Your session is stored server-side and identified by a session ID. This keeps your login information secure.</p>
        
        <details style="margin-top: 16px;">
            <summary style="cursor: pointer; color: var(--primary); font-weight: 600;">View Session Details</summary>
            <pre style="background: var(--background); padding: 12px; border-radius: 8px; margin-top: 12px; overflow-x: auto; font-size: 12px;">
Session ID: <?php echo session_id(); ?>
User ID: <?php echo $_SESSION['user_id'] ?? 'N/A'; ?>
Login Time: <?php echo $_SESSION['login_time'] ?? 'N/A'; ?>
Last Activity: <?php echo $_SESSION['last_activity'] ?? 'N/A'; ?>
            </pre>
        </details>
    </div>

    <!-- Actions -->
    <div class="card" style="margin-top: 24px;">
        <h2>Actions</h2>
        <div class="flex">
            <a href="profile.php" class="btn btn-primary">Edit Profile</a>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>
    </div>

    <!-- Features Info -->
    <div class="card" style="margin-top: 24px;">
        <h2>About This System</h2>
        <p>This registration system demonstrates key concepts from CENY6103 Module 2:</p>
        <ul style="margin-left: 20px; color: var(--text-secondary);">
            <li><strong>Server-Side Validation:</strong> All form data is validated on the server</li>
            <li><strong>Session Management:</strong> User login state is stored in sessions ($_SESSION)</li>
            <li><strong>Cookie Integration:</strong> "Remember Me" uses cookies for persistent login</li>
            <li><strong>Security:</strong> Passwords are hashed, input is sanitized</li>
            <li><strong>Error Handling:</strong> Clear error messages for validation failures</li>
            <li><strong>PHP Templating:</strong> Reusable header and footer components</li>
        </ul>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
