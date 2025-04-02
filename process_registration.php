<?php
session_start(); // Start session for feedback messages

require_once('settings.php'); // DB settings

// Check if form data was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $errors = [];

    /*  Server-Side Validation */

    // 1. Basic Checks
    if (empty($username)) { $errors[] = "Username is required."; }
    if (empty($password)) { $errors[] = "Password is required."; }
    if ($password !== $password_confirm) { $errors[] = "Passwords do not match."; }

    // 2. Password Rule 
    $password_regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if (!preg_match($password_regex, $password)) {
        $errors[] = "Password does not meet complexity requirements (min 8 chars, upper, lower, number, special).";
    }

    // 3. Check for Unique Username (if other validations passed so far)
    if (empty($errors)) {
        $dbconn = @mysqli_connect($host, $user, $pwd, $sql_db);
        if (!$dbconn) {
            $errors[] = "Database connection failed."; // Log detailed error server-side
        } else {
            $query_check = "SELECT manager_id FROM managers WHERE username = ?";
            $stmt_check = mysqli_prepare($dbconn, $query_check);
            if ($stmt_check) {
                mysqli_stmt_bind_param($stmt_check, "s", $username);
                mysqli_stmt_execute($stmt_check);
                $result_check = mysqli_stmt_get_result($stmt_check);
                if (mysqli_fetch_assoc($result_check)) {
                    $errors[] = "Username already exists. Please choose another.";
                }
                mysqli_stmt_close($stmt_check);
            } else {
                $errors[] = "Failed to check username uniqueness."; // Log detailed error server-side
            }
        }
    }

    /*  Process Registration or Redirect with Errors */

    if (!empty($errors)) {
        // Store errors in session and redirect back to registration form
        $_SESSION['register_error'] = implode("<br>", $errors);
        header('Location: register_manager.php');
        exit;
    } else {
        // All validations passed - Proceed with registration
        if ($dbconn) { // Ensure connection is still valid
            // Hash the password securely
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into database using prepared statement
            $query_insert = "INSERT INTO managers (username, password_hash) VALUES (?, ?)";
            $stmt_insert = mysqli_prepare($dbconn, $query_insert);
            if ($stmt_insert) {
                mysqli_stmt_bind_param($stmt_insert, "ss", $username, $hashed_password);
                if (mysqli_stmt_execute($stmt_insert)) {
                    // Success!
                    $_SESSION['register_success'] = "Registration successful! You can now login.";
                    mysqli_stmt_close($stmt_insert);
                    mysqli_close($dbconn);
                    header('Location: login.php'); // Redirect to login page
                    exit;
                } else {
                    $_SESSION['register_error'] = "Registration failed. Please try again."; // Log detailed error
                }
                mysqli_stmt_close($stmt_insert);
            } else {
                 $_SESSION['register_error'] = "Registration failed. Please try again."; // Log detailed error
            }
            mysqli_close($dbconn);
        } else {
             $_SESSION['register_error'] = "Database error during registration.";
        }
        // If registration failed after validation, redirect back
        header('Location: register_manager.php');
        exit;
    }

} else {
    // Redirect if accessed directly
    header('Location: register_manager.php');
    exit;
}
?>