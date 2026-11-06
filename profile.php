<?php
/**
 * User Profile Page
 * Based on CENY6103 Module 2 - Session Management and Form Handling
 */

session_start();

$page_title = "Profile - Pizza Lovers Club";
$page_description = "Edit your Pizza Lovers Club profile";

// Check if logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Update last activity
$_SESSION['last_activity'] = time();

$errors = [];
$success = false;
$username = $_SESSION['username'] ?? '';
$email = $_SESSION['email'] ?? '';
$phone = '';

// Find current user's phone from registered users
if (isset($_SESSION['registered_users'])) {
    foreach ($_SESSION['registered_users'] as $user) {
        if ($user['user_id'] === $_SESSION['user_id']) {
            $phone = $user['phone'] ?? '';
            break;
        }
    }
}

// Process profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $new_phone = trim($_POST['phone'] ?? '');

    // Validate phone if provided
    if (!empty($new_phone)) {
        $phone_clean = preg_replace('/[^0-9]/', '', $new_phone);
        if (strlen($phone_clean) < 10) {
            $errors['phone'] = "Please enter a valid phone number";
        } else {
            $phone = $phone_clean;
            // Update phone in session
            if (isset($_SESSION['registered_users'])) {
                foreach ($_SESSION['registered_users'] as &$user) {
                    if ($user['user_id'] === $_SESSION['user_id']) {
                        $user['phone'] = $phone;
                        break;
                    }
                }
            }
            $success = true;
        }
    } else {
        $success = true;
    }
}

include 'includes/header.php';
?>

<div class="form-container">
    <div class="page-header">
        <h1>Your Profile</h1>
        <p>Manage your Pizza Lovers Club account</p>
    </div>

    <?php if ($success): ?>
        <div class="success-alert">
            <div>
                <strong>Profile Updated</strong>
                <p>Your profile has been updated successfully.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error-alert">
            <div>
                <strong>Update Failed</strong>
                <ul style="margin-left: 20px; margin-top: 8px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Profile Information -->
    <div class="card">
        <h2>Account Information</h2>
        
        <div class="user-info">
            <p>
                <strong>Username:</strong>
                <span><?php echo htmlspecialchars($username); ?></span>
            </p>
            <p>
                <strong>Email:</strong>
                <span><?php echo htmlspecialchars($email); ?></span>
            </p>
        </div>

        <p style="font-size: 14px; color: var(--text-secondary); margin-top: 16px;">
            Username and email cannot be changed. To use a different email, please contact support.
        </p>
    </div>

    <!-- Edit Phone Number -->
    <form method="POST" action="" class="card" style="margin-top: 24px;" novalidate>
        <h2>Edit Phone Number</h2>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" 
                   id="phone" 
                   name="phone" 
                   placeholder="(555) 123-4567"
                   value="<?php echo htmlspecialchars($phone); ?>"
                   class="<?php echo isset($errors['phone']) ? 'error' : ''; ?>">
            <?php if (isset($errors['phone'])): ?>
                <span class="error-message"><?php echo $errors['phone']; ?></span>
            <?php endif; ?>
            <p class="helper-text">Leave blank to remove phone number</p>
        </div>

        <button type="submit" name="update_profile" class="btn btn-primary">
            Save Changes
        </button>
    </form>

    <!-- Actions -->
    <div class="card" style="margin-top: 24px;">
        <h2>Account Actions</h2>
        <div class="flex">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>