<?php

/* I. Initial Setup */

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

  // Check table existence  
  $checkTableQuery = 'SHOW TABLES LIKE "eoi"';
  $tableExistResult = mysqli_query($dbconn, $checkTableQuery);

  if (!$tableExistResult) {
    echo '<p>Error checking for EOI table: '. mysqli_error($dbconn). '</p>';
    exit;
  }
  
  if (mysqli_num_rows($tableExistResult) == 0) { // if table not exist
    $createTableQuery = 'CREATE TABLE eoi (
      EOInumber INT AUTO_INCREMENT PRIMARY KEY,
      JobReference VARCHAR(5) NOT NULL,
      FirstName VARCHAR (20) NOT NULL,
      LastName VARCHAR (20) NOT NULL,
      dob DATE NOT NULL,
      gender ENUM("male", "female", "other") NOT NULL,
      street VARCHAR(40) NOT NULL,
      suburb VARCHAR(40) NOT NULL,
      state ENUM("VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT") NOT NULL,
      postcode CHAR(4) NOT NULL,
      email VARCHAR(255) NOT NULL,
      phone VARCHAR(12) NOT NULL,
      skills VARCHAR(255) NOT NULL,
      otherSkills TEXT,
      Status ENUM("New", "Current", "Final") DEFAULT "New" NOT NULL
    )';

    $createTableResult = mysqli_query($dbconn, $createTableQuery);

    if (!$createTableResult) {
      echo '<p>Error creating EOI table: ' . mysqli_error($dbconn) . '</p>';
      mysqli_close($dbconn); // Close the connection before exiting
      exit;
    } 
  }
  

/* END of Initial Setup */

/* II. Data Handling */

  // Sanitize Input Function          
  function sanitizeInput($input) {
    return trim(stripslashes(htmlspecialchars($input)));
  }

  // Retrieve Form Data
  $jobRef = sanitizeInput($_POST['jobref']); // job reference
  $firstName = sanitizeInput($_POST['firstName']); // first name
  $lastName = sanitizeInput($_POST['lastName']); // last name
  $dob = $_POST['dob']; // date of birth (sanitized later during validation)
  $gender = isset($_POST['gender']) ? $_POST['gender'] : ''; // gender (sanitized later during validation)
  $street = sanitizeInput($_POST['street']); // street address
  $suburb = sanitizeInput($_POST['suburb']); // suburb
  $state = sanitizeInput($_POST['state']); // state
  $postcode = sanitizeInput($_POST['postcode']); // postcode
  $email = sanitizeInput($_POST['email']); // email
  $phone = sanitizeInput($_POST['phone']); // phone number
  $skills = isset($_POST['skills']) ? $_POST['skills'] : []; // skills array (sanitized later during validation)
  $otherSkills = sanitizeInput($_POST['otherskills_input']);  // other skills input

/* END of Data Handling */

/* III. Validation */
  // Prepare for Validation
  $errors = [];

  // 1. Job Reference
  if (empty($jobRef)) {
    $errors['jobRef'] = 'Job reference is required';
  } elseif (!preg_match("/^[A-Za-z0-9]{5}$/", $jobRef)) {
    $errors['jobRef'] = 'Job reference must be exactly 5 alphanumeric characters';
  }

  // 2. First Name
  if (empty($firstName)) {
    $errors['firstName'] = 'First name is required';
  } elseif (!preg_match('/^[A-Za-z]{1,20}$/', $firstName)){
    $errors['firstName'] = 'First name must be no more than 20 alphabetic characters';
  }

  // 3. Last Name
  if (empty($lastName)) {
    $errors['lastName'] = 'Last name is required';
  } elseif (!preg_match('/^[A-Za-z]{1,20}$/', $lastName)){
    $errors['lastName'] = 'Last name must be no more than 20 alphabetic charactersters';
  }

  // 4. Date of Birth
  if (empty($dob)) {
    $errors['dob'] = 'Date of birth is required';
  } else {
    $birthDate = DateTime::createFromFormat('Y-m-d', $dob);

    // Check if date is valid and matches format
    if ($birthDate === false || $birthDate->format('Y-m-d') !== $dob) {
      $errors['dob'] = 'Invalid date format. Please use YYYY-MM-DD';
    } else {
      $today = new DateTime();

      // Check if date is in the future
      if ($birthDate > $today) {
        $errors['dob'] = 'Date of birth cannot be in the future';
      } else {
        $age = $today->diff($birthDate)->y;
        if ($age < 15 || $age > 80) {
          $errors['dob'] = 'Age must be between 15 and 80 years.';
        }
      }
    }

    $age = $today->diff($birthDate)->y;
  }

  // 5. Gender
  if (empty($gender)) {
    $errors['gender'] = 'Please select a gender.';
  }

  // 6. Street Address
  if (empty($street)) {
    $errors['street'] = 'Street address is required';
  } elseif (strlen($street) > 40) {
    $errors['street'] = 'Street address must be no more than 40 characters';
  }

  // 7. Suburb/Town
  if (empty($suburb)) {
    $errors['suburb'] = 'Suburb/Town is required';
  } elseif (strlen($suburb) > 40) {
    $errors['suburb'] = 'Suburb/Town must be no more than 40 characters';
  }

  // 8. State
  if (empty($state)) {
    $errors['state'] = 'Please select a state';
  } else {
    $validStates = ['VIC', 'NSW', 'QLD', 'WA', 'SA', 'TAS', 'ACT', 'NT'];
    if (!in_array($state, $validStates)) {
      $errors['state'] = 'Invalid state selected';
    }
  }

  // 9. Postcode
  if (empty($postcode)) {
    $errors['postcode'] = 'Postcode is required';
  } 
  elseif (empty($errors['state'])) { // Only check if state is valid
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
      $errors['postcode'] = 'Postcode does not match the selected state or format';
    }
  }
  elseif (!preg_match('/^\d{4}$/', $postcode)) { // Backup check if state invalid
    $errors['postcode'] = 'Postcode must be exactly 4 digits.'; 
  }


  // 10. Email
  if (empty($email)) {
    $errors['email'] = 'Email address is required.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid email format';
  }

  // 11. Phone Number
  if (empty($phone)) {
    $errors['phone'] = 'Phone number is required.';
  } elseif (!preg_match('/^[0-9 ]{8-12}$/', $phone)) {
    $errors['phone'] = 'Phone number must be between 8 and 12 digits (spaces allowed)';
  }

  // 12. Other Skills
  if (isset($_POST['otherskills_checkbox']) && empty($otherSkills)) {
    $errors['otherSkills'] = 'Please enter your other skills'; 
  }

  // 13. Error Handling (if exists)
  if (!empty($errors)) {
    // Prepare the error messages for redirection
    $errorParams = [];
    foreach ($errors as $field => $message) {
      // Create 'error_field=message' parameters
      $errorParams[] = 'error_' . urlencode($field) . '=' . urlencode($message);
    }
    $errorQueryString = implode('&', $errorParams);
    
    // Construct the redirect URL with error messages
    $redirectURL = 'apply.php?' . $errorQueryString;

    // Perform the redirect
    header('Location: ' . $redirectURL);
    exit;
  }
  
/* END of Validation */

/* IV. Database Operations */

  // 1. Prepare Data 
  // Convert skills array to string
  $skillsString = isset($_POST['skills']) && is_array($_POST['skills']) ? implode(', ', $_POST['skills']) : '';

  // 2. Construct SQL Query
  $query_template = "INSERT INTO eoi (jobRef, firstName, lastName, dob, gender, street, suburb, state, postcode, email, phone, skills, otherSkills, Status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'New')";

  // 3. Prepare Statement
  $stmt = mysqli_prepare($dbconn, $query_template);

  // 3a. Statement Preparation Succeeded
  if ($stmt) {
    // 4. Bind Parameters
    mysqli_stmt_bind_param(
      $stmt, 
      'sssssssssssss', 
      $jobRef, $firstName, $lastName, $dob, $gender, $street, $suburb, $state, $postcode, $email, $phone, $skills, $otherSkills
    );

    // 5. Execute statement
    $executeResult = mysqli_stmt_execute($stmt);

    if ($executeResult) {
      // 5a. Execution succeeded - Display Confirmation with EOInumber
      $newEOInumber = mysqli_insert_id($dbconn);
      echo '<h1>Application Submitted Successfully!</h1>';
      echo '<p>Thank you for your application.</p>';
      echo '<p>Your unique EOI Number is: <strong>' . $newEOInumber . '</strong></p>';
      echo '<p>Please keep this number for your records.</p>';
    } else {
      // 5b. Execution failed
      echo '<p>Error submitting application. (' . mysqli_stmt_errno($stmt) . ')' . mysqli_stmt_error($stmt) .'</p>';
    }

    // 6. Close Statement
    mysqli_stmt_close($stmt);

    // 3b. Statement Preparation Failed
  } else { 
    echo '<p>Error prepariing database operation(' . mysqli_errno($dbconn) . ')' . mysqli_error($dbconn) . '</p>';
  }



  // 7. Close Connection
  mysqli_close($dbconn);
/* END of Database Operations */
?>