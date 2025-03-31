<?php

/*Initial Setup */

  // Database connection details
  require_once "settings.php"; 

  // Establish Database Connection
  $dbconn = @mysqli_connect($host, $user, $pwd, $sql_db);
  if (!$dbconn){
    echo "<p>Unable to connect to the database</p>";
    exit; // Stop further execution if the connection fails
  } 
  
  // Check for Direct Access
  if (empty($_POST)) {
    header('Location: apply.php');
    exit;
  }

/* END of Initial Setup */

/* Data Handling */

  // Sanitize Input          
  function sanitizeInput($input) {
    return trim(stripslashes(htmlspecialchars($input)));
  }

  // Retrieve Form Data
  $jobref = sanitizeInput($_POST['jobref']); // job reference
  $firstname = sanitizeInput($_POST['firstName']); // first name
  $lastname = sanitizeInput($_POST['lastName']); // last name
  $dob = $_POST['dob']; // date of birth (sanitized later during validation)
  $gender = isset($_POST['gender']) ? $_POST['gender'] : ''; // gender (sanitized later during validation)
  $street = sanitizeInput($_POST['street']); // street address
  $suburb = sanitizeInput($_POST['suburb']); // suburb
  $state = sanitizeInput($_POST['state']); // state
  $postcode = sanitizeInput($_POST['postcode']); // postcode
  $email = sanitizeInput($_POST['email']); // email
  $phone = sanitizeInput($_POST['phone']); // phone number
  $skills = isset($_POST[skills]) ? $_POST['skills'] : []; // skills array (sanitized later during validation)
  $otherSkills = sanitizeInput($_POST['otherskills_input']);  // other skills input

  // Prepare for Validation
  $error = [];
/* END of Data Handling */

/* Validation */

  // 1. Job Reference
  if (!preg_match("/^[A-Za-z0-9]{5}$/", $jobref)) {
    $error[] = 'Job reference must be exactly 5 alphanumeric characters';
  }

  // 2. First Name
  if (!preg_match('/^[A-Za-z]{1,20}$/', $firstname)){
    $error[] = 'First name must be no more than 20 alphabetic characters';
  }

  // 3. Last Name
  if (!preg_match('/^[A-Za-z]{1,20}$/', $lastname)){
    $error[] = 'Last name must be no more than 20 alphabetic charactersters';
  }

  // 4. Date of Birth
  list($day, $month, $year) = explode('/', $dob);
  if(!checkdate($month, $day, $year)) {
    $birthDate = new DateTime($year . '-' . $month . '-' . $day);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
  }
  if ($age < 15 || $age > 80) {
    $error[] = 'Age must be between 15 and 80 years';
  }

  // 5. Gender
  if (empty($gender)) {
    $error[] = 'Please select a gender.';
  }

  // 6. Street Address
  if (strlen($street) > 40) {
    $error[] = 'Street address must be no more than 40 characters';
  }

  // 7. Suburb/Town
  if (strlen($suburb) > 40) {
    $error[] = 'Suburb/Town must be no more than 40 characters';
  }

  // 8. State
  $validStates = ['VIC', 'NSW', 'QLD', 'WA', 'SA', 'TAS', 'ACT', 'NT'];
  if (!in_array($state, $validStates)) {
    $errorp[] = 'Invalid state selected';
  }

  // 9. Postcode
  $statePostcodes = array(
    'VIC' => '/^3[0-9]{3}$/',
    'NSW' => '/^1[0-9]{3}|2[0-9]{3}$/',
    'QLD' => '/^4[0-9]{3}|9[0-9]{3}$/',
    'WA' => '/^6[0-9]{3}$/',
    'SA' => '/^5[0-9]{3}$/',
    'TAS' => '/^7[0-4][0-9]{2}|^7[8-9][0-9]{2}$/',
    'ACT' => '/^0[2][0-9]{2}$/',
    'NT' => '/^0[8][0-9]{2}|^0[9][0-9]{2}$/',
  );

  if (!preg_match($statePostcodes[$state], $postcode)) {
    $error[] = 'Postcode does not match the selected state';
  }

  // 10. Email
  if (!filter_var($email, filter_validate_email)) {
    $error[] = 'Invalid email format';
  }

  // 11. Phone Number
  if (!preg_match('/^[0-9 ]{8-12}$/', $phone)) {
    $error[] = 'Phone number must be between 8 and 12 digits';
  }

  // 12. Other Skills
  if (isset($_POST['otherskills_checkbox']) && empty($otherSkills)) {
    $error[] = 'Please enter your other skills';
  }
  
  // Check if skills are entered when checkbox is checked
  if (isset($_POST['otherskills_checkbox']) && empty($_POST['otherskills_input'])) {
    header('Location: apply.php?error=1');
    exit();
  }

  $query = 'INSERT INTO eoi (jobReference, FirstName, LastName, Street, Suburb, State, Postcode, Email, Phone, Skill1, Skill2, OtherSkills) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
  $stmt = mysqli_prepare($dbconn, $query);
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'ssssssssssss', $jobref, $firstname, $lastname, $dob, $gender, $street, $suburb, $state, $postcode, $email, $phone, $skills, $otherSkills);
  
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
      echo "<p>Application submitted successfully</p>";
      echo "<p>Thank you for your interest in the position</p>";
    } else {
      echo '<p>Something went wrong with the query</p>';
    }
    mysqli_stmt_close($stmt);
  } else {
    echo 'Failed to prepare the SQL statement';
  }
  mysqli_close($dbconn);
?>