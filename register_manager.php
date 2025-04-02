<?php 
$pageTitle ='Manager Registration';
include('header.inc'); 
?>

    <h1>Register New Manager</h1>

    <?php
        // Display feedback messages (errors or success) if redirected back
        session_start(); // Need session to display flash messages
        if (isset($_SESSION['register_error'])) {
            echo "<p style='color:red;'>" . htmlspecialchars($_SESSION['register_error']) . "</p>";
            unset($_SESSION['register_error']); // Clear message after displaying
        }
        if (isset($_SESSION['register_success'])) {
            echo "<p style='color:green;'>" . htmlspecialchars($_SESSION['register_success']) . "</p>";
            unset($_SESSION['register_success']); // Clear message after displaying
        }
    ?>

    <form method="post" action="process_registration.php">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <small>Password must be at least 8 characters long, include uppercase, lowercase, number, and special character.</small>
        </div>
        <div>
            <label for="password_confirm">Confirm Password:</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
        </div>
        <div>
            <button type="submit">Register</button>
        </div>
    </form>
     <p><a href="login.php">Already registered? Login here</a></p>

</body>
</html>