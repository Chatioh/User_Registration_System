<?php
/**
 * Validation Functions for Pizza Lovers Club Registration
 * Based on CENY6103 Module 2 - Form Validation and Sanitization
 * 
 * This file implements server-side validation patterns from the lecture notes
 */

/**
 * Validate and sanitize user registration input
 * Returns array with 'errors' and 'data' keys
 */
function validate_registration($data) {
    $errors = [];
    $cleaned_data = [];

    // 1. Username validation
    if (empty($data['username'])) {
        $errors['username'] = "Username is required";
    } else {
        $username = trim($data['username']);
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

        // Check length
        if (strlen($username) < 3 || strlen($username) > 20) {
            $errors['username'] = "Username must be 3-20 characters";
        }
        // Check format (alphanumeric and underscore only)
        elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors['username'] = "Only letters, numbers, and underscores allowed";
        } else {
            $cleaned_data['username'] = $username;
        }
    }

    // 2. Email validation
    if (empty($data['email'])) {
        $errors['email'] = "Email is required";
    } else {
        $email = trim($data['email']);
        // Remove illegal characters
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Please enter a valid email address";
        } else {
            $cleaned_data['email'] = $email;
        }
    }

    // 3. Password validation
    if (empty($data['password'])) {
        $errors['password'] = "Password is required";
    } else {
        $password = $data['password'];
        // Check password strength
        if (strlen($password) < 8) {
            $errors['password'] = "Password must be at least 8 characters";
        }
        elseif (!preg_match('/[A-Z]/', $password)) {
            $errors['password'] = "Password must contain at least one uppercase letter";
        }
        elseif (!preg_match('/[a-z]/', $password)) {
            $errors['password'] = "Password must contain at least one lowercase letter";
        }
        elseif (!preg_match('/[0-9]/', $password)) {
            $errors['password'] = "Password must contain at least one number";
        }
        elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors['password'] = "Password must contain at least one special character";
        } else {
            // Password is valid
            $cleaned_data['password'] = $password;
        }
    }

    // 4. Phone number validation (optional)
    if (!empty($data['phone'])) {
        $phone = preg_replace('/[^0-9]/', '', $data['phone']);
        if (strlen($phone) < 10) {
            $errors['phone'] = "Please enter a valid phone number";
        } else {
            $cleaned_data['phone'] = $phone;
        }
    }

    // 5. Terms agreement (checkbox)
    if (!isset($data['agree_terms']) || $data['agree_terms'] != 'on') {
        $errors['agree_terms'] = "You must agree to the terms and conditions";
    }

    return [
        'errors' => $errors,
        'data' => $cleaned_data
    ];
}

/**
 * Check if email already exists in session (simulating database check)
 */
function email_exists($email) {
    session_start();
    if (!isset($_SESSION['registered_users'])) {
        $_SESSION['registered_users'] = [];
    }
    
    foreach ($_SESSION['registered_users'] as $user) {
        if ($user['email'] === $email) {
            return true;
        }
    }
    return false;
}

/**
 * Check if username already exists in session (simulating database check)
 */
function username_exists($username) {
    session_start();
    if (!isset($_SESSION['registered_users'])) {
        $_SESSION['registered_users'] = [];
    }
    
    foreach ($_SESSION['registered_users'] as $user) {
        if ($user['username'] === $username) {
            return true;
        }
    }
    return false;
}

/**
 * Sanitize input - remove HTML tags and trim whitespace
 */
function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email format
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Check password strength
 */
function is_strong_password($password) {
    return (
        strlen($password) >= 8 &&
        preg_match('/[A-Z]/', $password) &&
        preg_match('/[a-z]/', $password) &&
        preg_match('/[0-9]/', $password) &&
        preg_match('/[^A-Za-z0-9]/', $password)
    );
}
?>
