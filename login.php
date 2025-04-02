<?php
session_start(); // Start the session

// Include database connection settings
include('settings.php');
// Connect to the database
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$error = '';
$success = '';

// Check if the user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: manage.php');
    exit();
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Clean the input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query the database for the user
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];

            // Reset login attempts
            $updateQuery = "UPDATE users SET login_attempts = 0, account_locked = 0 WHERE id = " . $user['id'];
            mysqli_query($conn, $updateQuery);

            header('Location: manage.php');
            exit();
        } else {
            // Password is incorrect
            $error = "Invalid username or password.";

            // Update login attempts
            $updateAttemptsQuery = "UPDATE users SET login_attempts = login_attempts + 1 WHERE username = '$username'";
            mysqli_query($conn, $updateAttemptsQuery);

            // Check if the account should be locked
            $maxAttempts = 3;
            $checkAttemptsQuery = "SELECT login_attempts FROM users WHERE username = '$username'";
            $attemptsResult = mysqli_query($conn, $checkAttemptsQuery);
            if ($attemptsResult && mysqli_num_rows($attemptsResult) > 0) {
                $attemptsRow = mysqli_fetch_assoc($attemptsResult);
                if ($attemptsRow['login_attempts'] >= $maxAttempts) {
                    $lockAccountQuery = "UPDATE users SET account_locked = 1 WHERE username = '$username'";
                    mysqli_query($conn, $lockAccountQuery);
                    $error = "Account locked due to too many failed login attempts. Please contact an administrator.";
                }
            }
        }
    } else {
        // Username not found
        $error = "Invalid username or password.";
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HR Manager</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header>
        <h1>HR Manager Login</h1>
    </header>
    
    <main>
        <div class="container login-container">
            <h2>Login to Manage EOIs</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="login.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn">Login</button>
                </div>
            </form>
        </div>
    </main>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> HR Management System</p>
    </footer>
</body>
</html>