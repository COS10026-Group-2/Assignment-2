<?php

$pageTitle = 'EOI Management';
include('header.inc');
include('menu.inc');

require_once('settings.php');
$dbconn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$dbconn){
  echo "<p>Unable to connect to the database</p>";
  exit; // Stop further execution if the connection fails
} 
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

<form method='post' action='manage.php'>
  <input type='hidden' name='action' value='delete_by_ref_confirm'>
  <input type='hidden' name='job_ref_to_delete' value='<?php echo htmlspecialchars($job_ref_from_request); ?>'>
  <p>Are you sure you want to delete all EOIs for Job Reference: <?php echo htmlspecialchars($job_ref_from_request); ?>?</p>
  <button type='submit'>Confirm Delete</button>
  <a href='manage.php'>Cancel</a>
</form>

<?php
include('footer.inc');

?>