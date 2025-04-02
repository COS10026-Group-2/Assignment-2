<?php
// process_login.php

// --- Configuration ---
define('MAX_LOGIN_ATTEMPTS', 3);    // Max allowed failed attempts before lockout
define('LOCKOUT_DURATION', 10); // Lockout time in seconds (e.g. 900 = 15 minutes)

// Start session to manage login state and feedback messages
session_start();

// Include database settings
require_once('settings.php');

/* Input Handling */
// Check if form data was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validation: ensure inputs are not empty
    if (empty($username) || empty($password)) {
        $_SESSION['login_error_msg'] = "Username and password are required.";
        header('Location: login.php?login_error=1'); // Redirect back with error flag
        exit;
    }

    /* Database Connection */
    $dbconn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$dbconn) {
        // Log the connection error securely server-side
        error_log("Database connection failed: " . mysqli_connect_error());
        $_SESSION['login_error_msg'] = "Login service unavailable. Please try again later.";
        header('Location: login.php?login_error=1'); // Generic Error flag
        exit;
    }

    /* Fetch User Data and Check Attempts */
    // Prepare query to get user details including attempt tracking
    $query = "SELECT manager_id, password_hash, failed_login_attempts, last_attempt_time
              FROM managers
              WHERE username = ?";
    $stmt = mysqli_prepare($dbconn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            /* User Found */
            $manager_id = $row['manager_id'];
            $stored_hash = $row['password_hash'];
            $failed_attempts = $row['failed_login_attempts'];
            // Handle potential NULL value for timestamp
            $last_attempt_timestamp = $row['last_attempt_time'] ? strtotime($row['last_attempt_time']) : 0;
            $current_time = time();

            // 1. Check if account is currently locked
            if ($failed_attempts >= MAX_LOGIN_ATTEMPTS && ($current_time - $last_attempt_timestamp) < LOCKOUT_DURATION) {
                // Account is locked
                mysqli_stmt_close($stmt);
                mysqli_close($dbconn);
                $wait_time = LOCKOUT_DURATION - ($current_time - $last_attempt_timestamp);
                $_SESSION['login_error_msg'] = "Account locked due to too many failed attempts. Please try again in " . ceil($wait_time / 60) . " minutes.";
                header('Location: login.php?login_error=2'); // Specific error flag for locked account
                exit;
            }

            // 2. Verify Password
            if (password_verify($password, $stored_hash)) {
                /* Login Successful */

                // Reset failed attempts counter in the database
                $update_sql = "UPDATE managers SET failed_login_attempts = 0, last_attempt_time = NULL WHERE manager_id = ?";
                $stmt_update = mysqli_prepare($dbconn, $update_sql);
                if($stmt_update){
                    mysqli_stmt_bind_param($stmt_update, "i", $manager_id);
                    mysqli_stmt_execute($stmt_update);
                    mysqli_stmt_close($stmt_update);
                } else {
                    // Log error: Failed to prepare statement to reset attempts
                    error_log("Failed to prepare statement to reset login attempts for manager ID: $manager_id");
                }

                // Set session variables
                $_SESSION['user_logged_in'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['manager_id'] = $manager_id; 

                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);

                // Redirect to the protected manager page
                mysqli_stmt_close($stmt); // Close the initial select statement
                mysqli_close($dbconn);
                header('Location: manage.php');
                exit;

            } else {
                /* Password Incorrect */

                // Increment failed attempts counter
                $failed_attempts++;
                $update_sql = "UPDATE managers SET failed_login_attempts = ?, last_attempt_time = CURRENT_TIMESTAMP WHERE manager_id = ?";
                $stmt_update = mysqli_prepare($dbconn, $update_sql);
                 if($stmt_update){
                    mysqli_stmt_bind_param($stmt_update, "ii", $failed_attempts, $manager_id);
                    mysqli_stmt_execute($stmt_update);
                    mysqli_stmt_close($stmt_update);
                 } else {
                    // Log error: Failed to prepare statement to update attempts
                    error_log("Failed to prepare statement to update login attempts for manager ID: $manager_id");
                 }

                 // Redirect back to login page with appropriate error
                 mysqli_stmt_close($stmt); // Close the initial select statement
                 mysqli_close($dbconn);

                 if ($failed_attempts >= MAX_LOGIN_ATTEMPTS) {
                     // Account is now locked
                     $_SESSION['login_error_msg'] = "Invalid password. Account locked due to too many failed attempts. Please try again later.";
                     header('Location: login.php?login_error=2');
                 } else {
                     // Generic invalid login message
                     $_SESSION['login_error_msg'] = "Invalid username or password.";
                     header('Location: login.php?login_error=1');
                 }
                 exit;
            }
        } else {
             /* User Not Found */
             $_SESSION['login_error_msg'] = "Invalid username or password.";
             header('Location: login.php?login_error=1');
             exit;
        }
        // Close the initial select statement if it was prepared successfully
        mysqli_stmt_close($stmt);
    } else {
        /* Database Query Preparation Failed */
        // Log the error securely server-side
        error_log("Failed to prepare login statement: " . mysqli_error($dbconn));
        $_SESSION['login_error_msg'] = "Login service unavailable. Please try again later.";
        header('Location: login.php?login_error=1'); // Generic error flag
        exit;
    }

    // Close database connection if still open
    mysqli_close($dbconn);

} else {
    // Redirect if accessed directly without POST data
    header('Location: login.php');
    exit;
}
?>