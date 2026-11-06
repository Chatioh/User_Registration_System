<?php
/**
 * User Registration Page for Pizza Lovers Club
 * Based on CENY6103 Module 2 - Form Validation and Sanitization
 * 
 * Features:
 * - Server-side form validation
 * - Error display and form re-population
 * - Session management for logged-in users
 * - Remember Me functionality with cookies
 */

// Start session
session_start();

// Include validation functions
require_once 'includes/validation.php';

// Set page variables for header
$page_title = "Register - Pizza Lovers Club";
$page_description = "Create your Pizza Lovers Club account";

// Initialize variables
$field_values = [];
$errors = [];
$show_form = true;

// Check if user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Validate input
    $validation_result = validate_registration($_POST);
    $errors = $validation_result['errors'];
    $cleaned_data = $validation_result['data'];

    // Store field values for re-display (except password)
    $field_values = array_merge($_POST, $cleaned_data);

    // Check for duplicate email or username
    if (empty($errors)) {
        if (email_exists($cleaned_data['email'])) {
            $errors['email'] = "This email is already registered";
        }
        if (username_exists($cleaned_data['username'])) {
            $errors['username'] = "This username is already taken";
        }
    }

    // If no errors, create session and redirect
    if (empty($errors)) {
        // Initialize registered users array if not exists
        if (!isset($_SESSION['registered_users'])) {
            $_SESSION['registered_users'] = [];
        }

        // Store user data in session (simulating database storage)
        $user_data = [
            'user_id' => uniqid('user_'),
            'username' => $cleaned_data['username'],
            'email' => $cleaned_data['email'],
            'phone' => $cleaned_data['phone'] ?? '',
            'registered_at' => date('Y-m-d H:i:s'),
            'password_hash' => password_hash($cleaned_data['password'], PASSWORD_DEFAULT)
        ];

        $_SESSION['registered_users'][] = $user_data;

        // Create login session
        $_SESSION['user_id'] = $user_data['user_id'];
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['email'] = $user_data['email'];
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();

        // Handle "Remember Me" with cookies
        if (isset($_POST['remember_me']) && $_POST['remember_me'] == 'on') {
            // Set cookie for 30 days
            setcookie('plc_username', $cleaned_data['username'], time() + (30 * 24 * 60 * 60), '/');
            setcookie('plc_email', $cleaned_data['email'], time() + (30 * 24 * 60 * 60), '/');
            $_SESSION['remember_me'] = true;
        }

        // Redirect to dashboard
        header('Location: dashboard.php');
        exit();
    }
}

// Include header template
include 'includes/header.php';
?>

<div class="form-container">
    <div class="page-header">
        <h1>Join Pizza Lovers Club</h1>
        <p>Create your account to become a member</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="error-alert">
            <div>
                <strong>Registration Failed</strong>
                <p>Please fix the errors below:</p>
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
                   placeholder="Choose a username"
                   value="<?php echo htmlspecialchars($field_values['username'] ?? ''); ?>"
                   class="<?php echo isset($errors['username']) ? 'error' : ''; ?>">
            <?php if (isset($errors['username'])): ?>
                <span class="error-message"><?php echo $errors['username']; ?></span>
            <?php endif; ?>
            <p class="helper-text">3-20 characters, letters, numbers, and underscores only</p>
        </div>

        <!-- Email Field -->
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   placeholder="your@email.com"
                   value="<?php echo htmlspecialchars($field_values['email'] ?? ''); ?>"
                   class="<?php echo isset($errors['email']) ? 'error' : ''; ?>">
            <?php if (isset($errors['email'])): ?>
                <span class="error-message"><?php echo $errors['email']; ?></span>
            <?php endif; ?>
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   placeholder="Create a strong password"
                   class="<?php echo isset($errors['password']) ? 'error' : ''; ?>">
            <?php if (isset($errors['password'])): ?>
                <span class="error-message"><?php echo $errors['password']; ?></span>
            <?php endif; ?>
            <p class="helper-text">Minimum 8 characters with uppercase, lowercase, number, and special character</p>
        </div>

        <!-- Phone Field (Optional) -->
        <div class="form-group">
            <label for="phone">Phone Number (Optional)</label>
            <input type="tel" 
                   id="phone" 
                   name="phone" 
                   placeholder="(555) 123-4567"
                   value="<?php echo htmlspecialchars($field_values['phone'] ?? ''); ?>"
                   class="<?php echo isset($errors['phone']) ? 'error' : ''; ?>">
            <?php if (isset($errors['phone'])): ?>
                <span class="error-message"><?php echo $errors['phone']; ?></span>
            <?php endif; ?>
        </div>

        <!-- Terms Checkbox -->
        <div class="checkbox-group">
            <input type="checkbox" 
                   id="agree_terms" 
                   name="agree_terms"
                   <?php echo (isset($field_values['agree_terms']) && $field_values['agree_terms'] == 'on') ? 'checked' : ''; ?>>
            <label for="agree_terms" class="checkbox-label">
                I agree to the <a href="#" target="_blank">Terms and Conditions</a>
            </label>
        </div>
        <?php if (isset($errors['agree_terms'])): ?>
            <span class="error-message" style="display: block; margin-top: -12px; margin-bottom: 16px;">
                <?php echo $errors['agree_terms']; ?>
            </span>
        <?php endif; ?>

        <!-- Remember Me Checkbox -->
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
        <button type="submit" name="register" class="btn btn-primary btn-block mt-lg">
            Create Account
        </button>

        <!-- Login Link -->
        <p class="text-center mt-lg">
            Already have an account? <a href="login.php">Sign in here</a>
        </p>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
