<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
  header('Location: login.php'); // Redirect to your login page
  exit; // Stop script execution
}

$pageTitle = 'EOI Management';
include('header.inc');
include('menu.inc');

require_once('settings.php');
$dbconn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$dbconn){
  echo "<p>Unable to connect to the database</p>";
  include('footer.inc');
  exit; 
} else {
  // Variables for storing results/messages
  $results = []; // Hold query results for display
  $message = ''; // Display success/error messages

  /* Main Logic - Handle Form Submission */
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
      $action = $_POST['action'];

      switch($action) {
        // Case 1. List All EOI
        case 'list_all':
          $query = 'SELECT * FROM eoi;';
          $stmt = mysqli_prepare($dbconn, $query);
          if(!$stmt) {
            $message = 'Error preparing query: ' . mysqli_error($dbconn);
          } else {
            mysqli_stmt_execute($stmt);
            $queryResult = mysqli_stmt_get_result($stmt);
            if(!$queryResult) {
              $message = 'Error executing query:' . mysqli_error($dbconn);
            } else {
              while ($row = mysqli_fetch_assoc($queryResult)) {
                $results[] = $row; // Add row to results array
              }
              if(empty($results)) {
                $message = 'No EOIs found.';
              }
            }
            mysqli_stmt_close($stmt);
          }
          break;
        
        // Case 2. Filter by Job Reference 
        case 'filter_by_ref':
          if (isset($_POST['job_ref_filter']) && !empty(trim($_POST['job_ref_filter']))) {
              $jobRef = trim($_POST['job_ref_filter']);
              // Basic validation/sanitization
              if (preg_match("/^[A-Za-z0-9]{5}$/", $jobRef)) {
                  $query = "SELECT EOInumber, JobReference, FirstName, LastName, Status FROM eoi WHERE JobReference = ?;";
                  $stmt = mysqli_prepare($dbconn, $query);
                  if (!$stmt) {
                      $message = "Error preparing query: " . mysqli_error($dbconn);
                  } else {
                      mysqli_stmt_bind_param($stmt, "s", $jobRef);
                      mysqli_stmt_execute($stmt);
                      $queryResult = mysqli_stmt_get_result($stmt);
                      if (!$queryResult) {
                          $message = "Error executing query: " . mysqli_error($dbconn);
                      } else {
                            while ($row = mysqli_fetch_assoc($queryResult)) {
                              $results[] = $row;
                          }
                          if (empty($results)) {
                              $message = "No EOIs found for Job Reference: " . htmlspecialchars($jobRef);
                          }
                      }
                      mysqli_stmt_close($stmt);
                  }
              } else {
                  $message = "Invalid Job Reference format.";
              }
          } else {
              $message = "Please enter a Job Reference to filter.";
          }
          break;
        
        // Case 3. Filter by Name 
        case 'filter_by_name':
          $firstName = isset($_POST['first_name_filter']) ? trim($_POST['first_name_filter']) : '';
          $lastName = isset($_POST['last_name_filter']) ? trim($_POST['last_name_filter']) : '';

          if (!empty($firstName) || !empty($lastName)) {
              // Basic validation/sanitization
              $sqlParts = [];
              $params = [];
              $types = "";

              if (!empty($firstName) && preg_match('/^[A-Za-z- ]{1,20}$/', $firstName)) {
                  $sqlParts[] = "FirstName LIKE ?";
                  $params[] = "%" . $firstName . "%";
                  $types .= "s";
              }
              if (!empty($lastName) && preg_match('/^[A-Za-z- ]{1,20}$/', $lastName)) {
                  $sqlParts[] = "LastName LIKE ?";
                  $params[] = "%" . $lastName . "%";
                  $types .= "s";
              }

              if (!empty($sqlParts)) {
                  $query = "SELECT EOInumber, JobReference, FirstName, LastName, Status FROM eoi WHERE " . implode(" OR ", $sqlParts) . ";";
                  $stmt = mysqli_prepare($dbconn, $query);
                    if (!$stmt) {
                      $message = "Error preparing query: " . mysqli_error($dbconn);
                  } else {
                      mysqli_stmt_bind_param($stmt, $types, ...$params); 
                      mysqli_stmt_execute($stmt);
                      $queryResult = mysqli_stmt_get_result($stmt);
                      if (!$queryResult) {
                          $message = "Error executing query: " . mysqli_error($dbconn);
                      } else {
                            while ($row = mysqli_fetch_assoc($queryResult)) {
                              $results[] = $row;
                          }
                            if (empty($results)) {
                              $message = "No EOIs found matching the name(s).";
                          }
                      }
                      mysqli_stmt_close($stmt);
                  }
              } else {
                    $message = "Invalid name format entered.";
              }
          } else {
              $message = "Please enter at least a first or last name to filter.";
          }
          break;

        // Case 4a. Delete Request (Step 1 - Show Confirmation
        case 'delete_by_ref_request':
          if (isset($_POST['job_ref_delete']) && !empty(trim($_POST['job_ref_delete']))) {
              $jobRefToDelete = trim($_POST['job_ref_delete']);
              if (preg_match("/^[A-Za-z0-9]{5}$/", $jobRefToDelete)) {
                  // Set a flag to display the confirmation form in the HTML part
                  $show_delete_confirmation = true;
                  $job_ref_for_confirmation = $jobRefToDelete;
                  $message = "Please confirm deletion below."; 
              } else {
                  $message = "Invalid Job Reference format for deletion.";
              }
          } else {
              $message = "Please enter a Job Reference to delete.";
          }
          break;

        // Case 4b. Delete Confirmation (Step 2 - Perform Deletion)
        case 'delete_by_ref_confirm':
          if (isset($_POST['job_ref_to_delete']) && !empty(trim($_POST['job_ref_to_delete']))) {
              $jobRefConfirmed = trim($_POST['job_ref_to_delete']);
                if (preg_match("/^[A-Za-z0-9]{5}$/", $jobRefConfirmed)) {
                  $query = "DELETE FROM eoi WHERE JobReference = ?;";
                  $stmt = mysqli_prepare($dbconn, $query);
                  if (!$stmt) {
                      $message = "Error preparing delete query: " . mysqli_error($dbconn);
                  } else {
                      mysqli_stmt_bind_param($stmt, "s", $jobRefConfirmed);
                      if (mysqli_stmt_execute($stmt)) {
                          $affected_rows = mysqli_stmt_affected_rows($stmt);
                          $message = "Successfully deleted " . $affected_rows . " EOI(s) for Job Reference: " . htmlspecialchars($jobRefConfirmed);
                      } else {
                          $message = "Error deleting EOIs: " . mysqli_stmt_error($stmt);
                      }
                      mysqli_stmt_close($stmt);
                  }
              } else {
                    $message = "Invalid Job Reference format during confirmation."; // Should not happen if logic is correct
              }
          } else {
              $message = "Deletion confirmation failed (missing job reference).";
          }
          break;

        // Case 5. Update Status        
        case 'update_status':
          if (isset($_POST['eoi_number'], $_POST['new_status'])) {
              $eoiNumber = filter_var($_POST['eoi_number'], FILTER_SANITIZE_NUMBER_INT);
              $newStatus = $_POST['new_status'];
              // Validate status
              $validStatuses = ['New', 'Current', 'Final'];
              if (in_array($newStatus, $validStatuses) && filter_var($eoiNumber, FILTER_VALIDATE_INT)) {
                  $query = "UPDATE eoi SET Status = ? WHERE EOInumber = ?;";
                  $stmt = mysqli_prepare($dbconn, $query);
                    if (!$stmt) {
                      $message = "Error preparing update query: " . mysqli_error($dbconn);
                  } else {
                      mysqli_stmt_bind_param($stmt, "si", $newStatus, $eoiNumber);
                      if (mysqli_stmt_execute($stmt)) {
                          $message = "Successfully updated status for EOI #" . $eoiNumber . " to " . htmlspecialchars($newStatus);
                          // Optional: Automatically re-run 'list_all' or the previous filter to refresh the view
                      } else {
                          $message = "Error updating status: " . mysqli_stmt_error($stmt);
                      }
                      mysqli_stmt_close($stmt);
                  }
              } else {
                  $message = "Invalid EOI number or status for update.";
              }
          } else {
              $message = "Missing data for status update.";
          }
          break;
        // Case 0. Default case for unknown actions
        default:
          $message = "Unknown action requested.";
          break;
      }
    } 
  }  // End of if ($_SERVER["REQUEST_METHOD"] == "POST")
  /* End of Main Logic */




} // End of else block for successful connection
?>

<form method='post' action='manage.php'>
  <input type='hidden' name='action' value='list_all'>
  <button type='submit'>List All EOIs</button>
</form>

<form method='post' action='manage.php'>
  <input type='hidden' name='action' value='filter_by_ref'>
  <label for='job_ref_filter'>Filter by Job Reference:</label>
  <input type='text' id='job_ref_filter' name='job_ref_filter' pattern='[A-Za-z0-9]{5}' title='5 alphanumberic characters'>
  <button type='submit'>Filter</button>
</form>

<form method='post' action='manage.php'>
  <input type='hidden' name='action' value='filter_by_name'>
  <label for='first_name_filter'>First Name:</label>
  <input type='text' id='first_name_filter' name='first_name_filter' maxlength='20'>
  <label for='last_name_filter'>Last Name:</label>
  <input type='text' id='last_name_filter' name='last_name_filter' maxlength='20'>
  <button type='submit'>Filter by Name</button>
</form>

<form method='post' action='manage.php'>
  <input type='hidden' name='action' value='delete_by_ref_request'>
  <label for='job_ref_delete'>Delete by Job Reference:</label>
  <input type='text' id='job_ref_delete' name='job_ref_delete' pattern='[A-Za-z0-9]{5}' title='5 alphanumeric characters' required>
  <button type='submit'>Delete</button>
</form>

<?php
// Display messages if any
if (!empty($message)) {
    echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>";
}

// Display Delete Confirmation Form if needed
if (isset($show_delete_confirmation) && $show_delete_confirmation === true && isset($job_ref_for_confirmation)) {
?>

  <form method="post" action="manage.php">
      <input type="hidden" name="action" value="delete_by_ref_confirm">
      <input type="hidden" name="job_ref_to_delete" value="<?php echo htmlspecialchars($job_ref_for_confirmation); ?>">
      <p>Are you sure you want to delete all EOIs for Job Reference: <strong><?php echo htmlspecialchars($job_ref_for_confirmation); ?></strong>?</p>
      <button type="submit">Confirm Deletion</button>
      <a href="manage.php">Cancel</a>
  </form>

<?php
}
// Check if there are results to display
if (!empty($results)) {
  echo "<h2>EOI Records</h2>";
  echo "<table border='1'>"; // Use CSS for styling instead of border='1' ideally

  // --- Table Header ---
  echo "<thead>";
  echo "<tr>";
  echo "<th>EOI Number</th>";
  echo "<th>Job Reference</th>";
  echo "<th>First Name</th>";
  echo "<th>Last Name</th>";
  // Add other relevant headers if you selected more fields in Branch 3 queries
  // e.g., echo "<th>Email</th>"; echo "<th>Phone</th>";
  echo "<th>Current Status</th>";
  echo "<th>Update Status</th>"; // Header for the update action form
  echo "</tr>";
  echo "</thead>";

  // --- Table Body ---
  echo "<tbody>";
  // Loop through the results array
  foreach ($results as $row) {
      echo "<tr>";
      // Display data for each column - used htmlspecialchars to prevent XSS
      echo "<td>" . htmlspecialchars($row['EOInumber']) . "</td>";
      echo "<td>" . htmlspecialchars($row['JobReference']) . "</td>";
      echo "<td>" . htmlspecialchars($row['FirstName']) . "</td>";
      echo "<td>" . htmlspecialchars($row['LastName']) . "</td>";
      // Other data cells can be added here if needed
      echo "<td>" . htmlspecialchars($row['Status']) . "</td>";

      /*  Status Update Form Cell */
      echo "<td>";
      echo "<form method='post' action='manage.php'>";
      // Hidden input to specify the action
      echo "<input type='hidden' name='action' value='update_status'>";
      // Hidden input to send the specific EOI number to update
      echo "<input type='hidden' name='eoi_number' value='" . htmlspecialchars($row['EOInumber']) . "'>";
      // Dropdown select for the new status
      echo "<select name='new_status'>";
      // Option 'New', selected if current status is 'New'
      echo "<option value='New'" . ($row['Status'] == 'New' ? ' selected' : '') . ">New</option>";
      // Option 'Current', selected if current status is 'Current'
      echo "<option value='Current'" . ($row['Status'] == 'Current' ? ' selected' : '') . ">Current</option>";
      // Option 'Final', selected if current status is 'Final'
      echo "<option value='Final'" . ($row['Status'] == 'Final' ? ' selected' : '') . ">Final</option>";
      echo "</select>";
      // Submit button for this specific row's update
      echo "<button type='submit'>Update</button>";
      echo "</form>";
      echo "</td>";
      /*  End of Status Update Form Cell */

      echo "</tr>";
  } // End of foreach loop
  echo "</tbody>";
  echo "</table>";

} elseif (empty($message)) {
  echo "<p>No EOI records to display.</p>";
}
?>

<?php
if ($dbconn) {
  mysqli_close($dbconn);
}
include('footer.inc');
?>