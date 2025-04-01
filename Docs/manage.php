<?php
  $pageTitle = "HR Manage";
  include "header.inc";
  include "menu.inc";
?>
<?php
// Include the database connection settings
include('settings.php');

// Connect to the database
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$action = isset($_POST['action']) ? $_POST['action'] : '';
$result = null;
$error = '';

// Process form actions based on the 'action' parameter
switch ($action) {
    case 'list_all':
        // List all EOIs
        $query = "SELECT * FROM eoi";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            $error = "Error listing EOIs: " . mysqli_error($conn);
        }
        break;
        
    case 'filter_by_jobref':
        // Filter EOIs by Job Reference
        $jobref = mysqli_real_escape_string($conn, $_POST['jobref']);
        $query = "SELECT * FROM eoi WHERE JobReference = '$jobref'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            $error = "Error filtering EOIs: " . mysqli_error($conn);
        }
        break;
        
    case 'search_by_name':
        // Search EOIs by Name
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $query = "SELECT * FROM eoi WHERE FirstName LIKE '%$name%' OR LastName LIKE '%$name%'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            $error = "Error searching EOIs: " . mysqli_error($conn);
        }
        break;
        
    case 'delete_eois':
        // Delete EOIs by Job Reference
        $jobref = mysqli_real_escape_string($conn, $_POST['jobref']);
        $query = "DELETE FROM eoi WHERE JobReference = '$jobref'";
        if (mysqli_query($conn, $query)) {
            $message = "EOIs with Job Reference '$jobref' deleted successfully.";
        } else {
            $error = "Error deleting EOIs: " . mysqli_error($conn);
        }
        break;
        
    case 'update_status':
        // Update EOI Status
        $eoi_id = intval($_POST['eoi_id']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $query = "UPDATE eoi SET Status = '$status' WHERE EOInumber = $eoi_id";
        if (mysqli_query($conn, $query)) {
            $message = "Status updated successfully.";
        } else {
            $error = "Error updating status: " . mysqli_error($conn);
        }
        break;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage EOIs</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header>
        <h1>Manage EOIs</h1>
    </header>
    
    <nav>
        <!-- Navigation menu can be included here -->
    </nav>
    
    <main>
        <div class="container">
            <h2>EOI Management</h2>
            
            <?php if (!empty($message)): ?>
                <div class="success-message">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="manage.php">
                <div class="form-group">
                    <label for="action">Action:</label>
                    <select name="action" id="action" onchange="showFormFields()">
                        <option value="">Select an action</option>
                        <option value="list_all">List All EOIs</option>
                        <option value="filter_by_jobref">Filter by Job Reference</option>
                        <option value="search_by_name">Search by Name</option>
                        <option value="delete_eois">Delete EOIs</option>
                        <option value="update_status">Update Status</option>
                    </select>
                </div>
                
                <div id="jobref-form" style="display: none;">
                    <div class="form-group">
                        <label for="jobref">Job Reference:</label>
                        <input type="text" name="jobref" id="jobref" required>
                    </div>
                </div>
                
                <div id="name-form" style="display: none;">
                    <div class="form-group">
                        <label for="name">Name (First or Last):</label>
                        <input type="text" name="name" id="name" required>
                    </div>
                </div>
                
                <div id="status-form" style="display: none;">
                    <div class="form-group">
                        <label for="eoi_id">EOI ID:</label>
                        <input type="number" name="eoi_id" id="eoi_id" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="status" id="status" required>
                            <option value="New">New</option>
                            <option value="Current">Current</option>
                            <option value="Final">Final</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn">Submit</button>
                </div>
            </form>
            
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <h3>Results</h3>
                <table class="eoi-table">
                    <thead>
                        <tr>
                            <th>EOI Number</th>
                            <th>Job Reference</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['EOInumber']; ?></td>
                                <td><?php echo $row['JobReference']; ?></td>
                                <td><?php echo $row['FirstName']; ?></td>
                                <td><?php echo $row['LastName']; ?></td>
                                <td><?php echo $row['Email']; ?></td>
                                <td><?php echo $row['Status']; ?></td>
                                <td>
                                    <form method="post" action="manage.php" style="display: inline;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="eoi_id" value="<?php echo $row['EOInumber']; ?>">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="New" <?php echo ($row['Status'] == 'New') ? 'selected' : ''; ?>>New</option>
                                            <option value="Current" <?php echo ($row['Status'] == 'Current') ? 'selected' : ''; ?>>Current</option>
                                            <option value="Final" <?php echo ($row['Status'] == 'Final') ? 'selected' : ''; ?>>Final</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php elseif ($result && mysqli_num_rows($result) == 0): ?>
                <div class="no-results">
                    <p>No results found.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <footer>
        <!-- Footer content -->
    </footer>
    
    <script>
        function showFormFields() {
            const action = document.getElementById('action').value;
            const jobrefForm = document.getElementById('jobref-form');
            const nameForm = document.getElementById('name-form');
            const statusForm = document.getElementById('status-form');
            
            // Hide all form fields
            jobrefForm.style.display = 'none';
            nameForm.style.display = 'none';
            statusForm.style.display = 'none';
            
            // Show relevant form fields based on the selected action
            if (action === 'filter_by_jobref' || action === 'delete_eois') {
                jobrefForm.style.display = 'block';
            } else if (action === 'search_by_name') {
                nameForm.style.display = 'block';
            } else if (action === 'update_status') {
                statusForm.style.display = 'block';
            }
        }
    </script>
</body>
</html>

