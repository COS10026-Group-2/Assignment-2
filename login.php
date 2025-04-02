<?php
// Start the session at the very top to access session variables
session_start();

$pageTitle = 'Manager Login';
include('header.inc');
?>

    <h1>Manager Login</h1>

    <?php
        /* Display Login Error Message */
        // Check if an error message was stored in the session by process_login.php
        if (isset($_SESSION['login_error_msg'])) {
            // Display the error message (using htmlspecialchars to prevent XSS)
            echo "<p>" . htmlspecialchars($_SESSION['login_error_msg']) . "</p>";

            // Unset the session variable so the message doesn't reappear on refresh
            unset($_SESSION['login_error_msg']);
        }
        /* End Error Message Display */
    ?>

    <form method="post" action="process_login.php">  <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>

    <p><a href="register_manager.php">Register as a new manager</a></p>
</body>
</html>