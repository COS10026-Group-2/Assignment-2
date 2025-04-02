<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
  <nav class="navbar">
    <div class="container">
      <div class="navdiv">
        <a href="index.php" class="nav-brand">Job Portal</a>
        <div class="navbar-div">
          <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="jobs.php">Jobs</a></li>
            <li><a href="apply.php">Apply</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="enhancements.php">Enhancements</a></li>

            <?php
            // Check if the user is logged in
            if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
                // --- User is Logged In ---
                // Display welcome message and Logout link
                echo "<li>"; 
                if (isset($_SESSION['username'])) { // Display username if stored in session
                   echo "Welcome, " . htmlspecialchars($_SESSION['username']) . "!";
                }
                echo "<a href='logout.php' class='login'>Logout</a>"; // Link to logout script
                echo "</li>";

            } else {
                // --- User is Not Logged In ---
                // Display Login link
                echo "<li><a href='login.php' class='login'>Login</a></li>"; // Link to your login page
            }
            ?>

            <li><button id="nav-button"><a href="register_manager.php">Register</a></button></li>

          </ul>
        </div>
      </div>
      <div class="pagename">
        <h1><?php echo $pageTitle; ?></h1>
      </div>
    </div>
  </nav>
</header>