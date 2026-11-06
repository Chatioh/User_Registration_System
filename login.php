<?php
/**
 * User Login Page
 * Based on CENY6103 Module 2 - Sessions and Cookies
 */

session_start();

$page_title = "Login - Pizza Lovers Club";
$page_description = "Login to your Pizza Lovers Club account";

// Check if already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}

// Check for remembered user (from cookies)
$remembered_username = '';
$remembered_email = '';
if (isset($_COOKIE['plc_username']) && isset($_COOKIE['plc_email'])) {
    $remembered_username = htmlspecialchars($_COOKIE['plc_username']);
    $remembered_email = htmlspecialchars($_COOKIE['plc_email']);
}

$errors = [];
$field_values = [];
$session_expired = isset($_GET['expired']) && $_GET['expired'] == '1';

// Process login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username)) {
        $errors['username'] = "Username is required";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    if (empty($errors)) {
        // Check if user exists in registered users
        $user_found = false;
        
        if (isset($_SESSION['registered_users'])) {
            foreach ($_SESSION['registered_users'] as $user) {
                if ($user['username'] === $username) {
                    // Verify password
                    if (password_verify($password, $user['password_hash'])) {
                        // Login successful
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['logged_in'] = true;
                        $_SESSION['login_time'] = time();
                        $_SESSION['last_activity'] = time();

                        // Handle remember me
                        if (isset($_POST['remember_me']) && $_POST['remember_me'] == 'on') {
                            setcookie('plc_username', $username, time() + (30 * 24 * 60 * 60), '/');
                            setcookie('plc_email', $user['email'], time() + (30 * 24 * 60 * 60), '/');
                            $_SESSION['remember_me'] = true;
                        }

                        header('Location: dashboard.php');
                        exit();
                    } else {
                        $errors['password'] = "Invalid password";
                    }
                    $user_found = true;
                    break;
                }
            }
        }

        if (!$user_found) {
            $errors['username'] = "Username not found. Please register first.";
        }
    }

    $field_values = $_POST;
}

include 'includes/header.php';
?>

<div class="form-container">
    <div class="page-header">
        <h1>Login to Your Account</h1>
        <p>Welcome back to Pizza Lovers Club</p>
    </div>

    <?php if ($session_expired): ?>
        <div class="error-alert">
            <div>
                <strong>Session Expired</strong>
                <p>Your session has expired due to inactivity. Please login again.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error-alert">
            <div>
                <strong>Login Failed</strong>
                <ul style="margin-left: 20px; margin-top: 8px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="card" novalidate>
        <!-- Username Field -->
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" 
                   id="username" 
                   name="username" 
                   placeholder="Enter your username"
                   value="<?php echo htmlspecialchars($field_values['username'] ?? ''); ?>"
                   class="<?php echo isset($errors['username']) ? 'error' : ''; ?>">
            <?php if (isset($errors['username'])): ?>
                <span class="error-message"><?php echo $errors['username']; ?></span>
            <?php endif; ?>
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   placeholder="Enter your password"
                   class="<?php echo isset($errors['password']) ? 'error' : ''; ?>">
            <?php if (isset($errors['password'])): ?>
                <span class="error-message"><?php echo $errors['password']; ?></span>
            <?php endif; ?>
        </div>

        <!-- Remember Me -->
        <div class="checkbox-group">
            <input type="checkbox" 
                   id="remember_me" 
                   name="remember_me"
                   <?php echo (isset($field_values['remember_me']) && $field_values['remember_me'] == 'on') ? 'checked' : ''; ?>>
            <label for="remember_me" class="checkbox-label">
                Remember me for 30 days
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" name="login" class="btn btn-primary btn-block mt-lg">
            Sign In
        </button>

        <!-- Register Link -->
        <p class="text-center mt-lg">
            Don't have an account? <a href="register.php">Create one here</a>
        </p>
    </form>

    <!-- Demo Info -->
    <div class="card" style="margin-top: 24px; background-color: var(--background);">
        <h3>Demo Credentials</h3>
        <p>To test the system, first <a href="register.php">create an account</a>, then login with those credentials.</p>
        <p style="font-size: 14px; color: var(--text-secondary);">
            The system stores registered users in the session for this demo. In a real application, users would be stored in a database (covered in Module 3).
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>