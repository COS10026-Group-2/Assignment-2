<?php
  $pageTitle = "PHP Enhancements";
  include "header.inc"; // Include standard header
  include "menu.inc";   // Include standard menu
?>

<main class="container">
    <h1>PHP Enhancements Implemented</h1>
    <p class="center-text">This page details the PHP-based enhancement added to the Job Portal project beyond the core requirements.</p>

    <section class="enhancement-details">
        <h2>Enhancement 1: Secure Manager Authentication System</h2>
        <article class="enhancement-card">
            <div class="enhancement-icon">🔒</div>
            <h3>Description</h3>
            <p>
                A secure authentication system was developed to protect the EOI management page (`manage.php`). This system includes manager registration, login, secure session management, and protection against brute-force attacks. This goes beyond basic functionality by adding essential security layers required for handling sensitive application data.
            </p>

            <h3>Key Features & Implementation Details</h3>
            <ul>
                <li>
                    <strong>Manager Registration (`register_manager.php`, `process_registration.php`):</strong>
                    <ul>
                        <li>Allows new managers to create an account[cite: 38].</li>
                        <li><strong>Server-Side Validation:</strong> Ensures usernames are unique within the `managers` database table and enforces password complexity rules (minimum length, character types)[cite: 38].</li>
                        <li><strong>Secure Password Storage:</strong> Uses the `password_hash()` function to securely store manager passwords in the database, preventing plain-text storage[cite: 38].</li>
                    </ul>
                </li>
                <li>
                    <strong>Manager Login (`login.php`, `process_login.php`):</strong>
                    <ul>
                        <li>Provides a login interface for registered managers.</li>
                        <li><strong>Password Verification:</strong> Uses `password_verify()` to compare the submitted password against the stored hash securely[cite: 39].</li>
                        <li><strong>Session Management:</strong> Upon successful login, a PHP session is initiated (`session_start()`) to track the logged-in state (`$_SESSION['user_logged_in']`, `$_SESSION['username']`)[cite: 39]. Session ID is regenerated (`session_regenerate_id(true)`) for security.</li>
                    </ul>
                </li>
                <li>
                    <strong>Access Control (`manage.php`):</strong>
                    <ul>
                        <li>The `manage.php` page checks for a valid manager session at the beginning. If no valid session exists, the user is redirected to the `login.php` page, effectively protecting the EOI data[cite: 39].</li>
                    </ul>
                </li>
                 <li>
                    <strong>Logout (`logout.php`):</strong>
                    <ul>
                        <li>Provides a mechanism to securely end the manager's session by unsetting session variables and destroying the session, then redirecting to the login page.</li>
                    </ul>
                </li>
                <li>
                    <strong>Brute-Force Protection (`process_login.php`):</strong>
                    <ul>
                        <li>Tracks the number of failed login attempts per username in the `managers` table (`failed_login_attempts`, `last_attempt_time`)[cite: 39].</li>
                        <li>After a defined number of consecutive failed attempts (e.g., 3), the account is temporarily locked out for a specific duration (e.g., 15 minutes) to mitigate brute-force attacks[cite: 39].</li>
                    </ul>
                </li>
                 <li>
                    <strong>Database Interaction:</strong>
                    <ul>
                        <li>A dedicated `managers` table stores usernames, hashed passwords, and login attempt information.</li>
                        <li>All database interactions use `mysqli` and prepared statements where appropriate to prevent SQL injection vulnerabilities.</li>
                        <li>Database connection details are managed via `settings.php`.</li>
                    </ul>
                </li>
            </ul>

            <h3>Files Involved:</h3>
            <p>
                <code>register_manager.php</code>, <code>process_registration.php</code>, <code>login.php</code>, <code>process_login.php</code>, <code>manage.php</code>, <code>logout.php</code>, <code>settings.php</code>, <code>header.inc</code>
            </p>

            <h3>How it Differs from Basic Requirements:</h3>
            <p>
                The basic requirements included creating `manage.php` but did not mandate a secure login system. This enhancement adds a crucial security layer, ensuring that only authorized managers can access, view, modify, or delete EOI records, aligning with real-world application security practices. It incorporates password hashing and brute-force protection, which are standard security measures.
            </p>
        </article>
    </section>

</main>

<?php
  include "footer.inc"; // Include standard footer
?>