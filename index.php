<?php
/**
 * Home Page - Pizza Lovers Club
 */

session_start();

$page_title = "Pizza Lovers Club - Registration System";
$page_description = "Join the Pizza Lovers Club community";

$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$logged_out = isset($_GET['logged_out']) && $_GET['logged_out'] == '1';

include 'includes/header.php';
?>

<div style="max-width: 800px; margin: 0 auto;">
    <?php if ($logged_out): ?>
        <div class="success-alert">
            <div>
                <strong>Logged Out Successfully</strong>
                <p>You have been logged out. Your session has been destroyed.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($is_logged_in): ?>
        <!-- Logged In View -->
        <div class="page-header">
            <h1>Welcome Back, <?php echo htmlspecialchars($_SESSION['username']); ?>! 🍕</h1>
            <p>You are logged in to Pizza Lovers Club</p>
        </div>

        <div class="card">
            <h2>Your Dashboard</h2>
            <p>You are now a member of the Pizza Lovers Club community. Enjoy exclusive benefits and connect with fellow pizza enthusiasts!</p>
            
            <div class="flex" style="margin-top: 24px;">
                <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
                <a href="profile.php" class="btn btn-secondary">Edit Profile</a>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>

    <?php else: ?>
        <!-- Not Logged In View -->
        <div class="page-header">
            <h1>Welcome to Pizza Lovers Club 🍕</h1>
            <p>Join our community of passionate pizza enthusiasts</p>
        </div>

        <div class="card" style="margin-bottom: 24px;">
            <h2>Get Started</h2>
            <p>Welcome to Pizza Lovers Club! This is a demonstration of a user registration system built with PHP, HTML, and CSS, following the patterns from CENY6103 Module 2.</p>
            
            <div class="flex" style="margin-top: 24px;">
                <a href="register.php" class="btn btn-primary">Create Account</a>
                <a href="login.php" class="btn btn-secondary">Sign In</a>
            </div>
        </div>

        <div class="card">
            <h2>Features Demonstrated</h2>
            <ul style="margin-left: 20px; color: var(--text-secondary); line-height: 1.8;">
                <li><strong>Form Validation:</strong> Server-side validation with detailed error messages</li>
                <li><strong>Session Management:</strong> User login state stored in $_SESSION</li>
                <li><strong>Cookie Integration:</strong> "Remember Me" functionality using cookies</li>
                <li><strong>Password Security:</strong> Passwords hashed with PHP's password_hash()</li>
                <li><strong>Input Sanitization:</strong> All user input sanitized with htmlspecialchars() and filters</li>
                <li><strong>PHP Templating:</strong> Reusable header and footer components with include()</li>
                <li><strong>Session Timeout:</strong> Automatic logout after 30 minutes of inactivity</li>
                <li><strong>Error Handling:</strong> Comprehensive error messages and validation feedback</li>
            </ul>
        </div>

        <div class="card" style="margin-top: 24px;">
            <h2>How It Works</h2>
            <p><strong>Registration Flow:</strong></p>
            <ol style="margin-left: 20px; color: var(--text-secondary); line-height: 1.8;">
                <li>User fills out the registration form</li>
                <li>Form is submitted via POST to register.php</li>
                <li>Server-side validation checks all fields</li>
                <li>If valid, user data is stored in $_SESSION['registered_users']</li>
                <li>User session is created ($_SESSION['logged_in'] = true)</li>
                <li>Optional: "Remember Me" cookie is set for 30 days</li>
                <li>User is redirected to dashboard.php</li>
            </ol>

            <p style="margin-top: 16px;"><strong>Login Flow:</strong></p>
            <ol style="margin-left: 20px; color: var(--text-secondary); line-height: 1.8;">
                <li>User enters username and password</li>
                <li>Server searches registered users in session</li>
                <li>Password is verified using password_verify()</li>
                <li>If correct, user session is created</li>
                <li>User can check "Remember Me" for persistent login</li>
            </ol>
        </div>

        <div class="card" style="margin-top: 24px; background-color: var(--background);">
            <h2>Module 2 Concepts Used</h2>
            <p>This system demonstrates all major concepts from CENY6103 Module 2:</p>
            <ul style="margin-left: 20px; color: var(--text-secondary); line-height: 1.8;">
                <li><strong>PHP Syntax:</strong> Variables, arrays, control structures, functions</li>
                <li><strong>Form Handling:</strong> $_POST, $_GET, $_SERVER superglobals</li>
                <li><strong>Validation Functions:</strong> From includes/validation.php</li>
                <li><strong>Sessions:</strong> session_start(), $_SESSION array</li>
                <li><strong>Cookies:</strong> setcookie() for "Remember Me"</li>
                <li><strong>PHP Templating:</strong> include() and require_once()</li>
                <li><strong>Security:</strong> htmlspecialchars(), password_hash(), filter_var()</li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
