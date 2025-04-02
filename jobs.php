<?php
  require_once "settings.php"; // Include database settings

  $pageTitle = "Jobs";
  include "header.inc";
  include "menu.inc";

// Function to safely display text and convert newlines to HTML list items
function display_list($text) {
    if (empty($text)) {
        return '<p>N/A</p>'; // Changed to paragraph for consistency
    }
    // Trim input text first
    $trimmed_text = trim($text);
    if (empty($trimmed_text)) {
        return '<p>N/A</p>';
    }
    // Escape the entire block once, then explode
    $escaped_text = htmlspecialchars($trimmed_text);
    $items = explode("\n", $escaped_text);

    $output = '<ul>';
    $item_count = 0;
    foreach ($items as $item) {
        $item = trim($item); // Trim each line
        if (!empty($item)) {
            $output .= '<li>' . $item . '</li>';
            $item_count++;
        }
    }
    $output .= '</ul>';
    // Return N/A if list ended up empty after trimming lines
    return ($item_count > 0) ? $output : '<p>N/A</p>';
}

// Database connection
$dbconn = @mysqli_connect($host, $user, $pwd, $sql_db);

// Check connection
if ($dbconn->connect_error) {
    error_log("Database Connection Error: " . $conn->connect_error); // Log the error
    die("Sorry, we're experiencing technical difficulties. Please try again later."); // User-friendly message
}

// Fetch job listings - Updated columns
$sql = "SELECT JobID, JobReference, Title, Description, ClosingDate, SalaryRange, ReportingTo, Responsibilities, EssentialReqs, PreferableReqs
        FROM jobs
        ORDER BY ClosingDate ASC, Title ASC"; // Added secondary sort by Title
$result = $dbconn->query($sql);

// Check if query execution was successful
if (!$result) {
    error_log("Database Query Error: " . $conn->error); // Log the error
    die("Sorry, could not retrieve job listings. Please try again later."); // User-friendly message
}

?>


<body>



<main> 
    <div class="joblist">
        <h1>Current Job Openings</h1>
    </div>

    <?php
    // Display data for each job
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<section class='job-listing' id='job_" . htmlspecialchars($row['JobID']) . "'>"; // Added section and unique ID
            echo "<h3>" . htmlspecialchars($row['Title']) . "</h3>";
            echo "<dl>"; // Using definition list for better structure
            echo "<dt>Reference:</dt><dd>" . htmlspecialchars($row['JobReference']) . "</dd>";
            echo "<dt>Closing Date:</dt><dd>" . (empty($row['ClosingDate']) ? 'N/A' : htmlspecialchars(date("d M Y", strtotime($row['ClosingDate'])))) . "</dd>"; // Format date
            echo "<dt>Salary:</dt><dd>" . (empty($row['SalaryRange']) ? 'Not specified' : htmlspecialchars($row['SalaryRange'])) . "</dd>";
            echo "<dt>Reports To:</dt><dd>" . (empty($row['ReportingTo']) ? 'N/A' : htmlspecialchars($row['ReportingTo'])) . "</dd>";
            echo "</dl>";

            echo "<h4>Description</h4>";
            echo "<p>" . (empty($row['Description']) ? 'No description available.' : nl2br(htmlspecialchars($row['Description']))) . "</p>"; // nl2br converts newlines

            echo "<h4>Key Responsibilities</h4>";
            echo display_list($row['Responsibilities']);

            echo "<h4>Essential Requirements</h4>";
            echo display_list($row['EssentialReqs']);

            echo "<h4>Preferable Requirements</h4>";
            echo display_list($row['PreferableReqs']);

            // Add Apply button linking to apply.php (pass JobReference)
            echo "<p><a href='apply.php?jobref=" . urlencode($row['JobReference']) . "' class='button'>Apply Now for " . htmlspecialchars($row['JobReference']) . "</a></p>"; // Made link text clearer

            echo "</section>"; // End job-listing section
        }
        // Free result set
        $result->free();
    } else {
        echo "<p>No current job openings found.</p>";
    }
    ?>
</main>

<?php
// Close the database connection
$dbconn->close();
?>

<?php include 'footer.inc'; // Assuming footer outputs closing body tags if needed ?>

</body>
</html>