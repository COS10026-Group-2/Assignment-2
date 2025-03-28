<?php
  require_once "settings.php"; // Database connection details

  $dbconn = @mysqli_connect($host, $user, $pwd, $sql_db);
  if (!$dbconn){
    echo "<p>Unable to connect to the database</p>";
    exit; // Stop further execution if the connection fails
  } 
  
  if (empty($_POST)) {
    header('Location: apply.php');
    exit;
  }

  function sanitizeInput($input) {
    return trim(stripslashes(htmlspecialchars($input)));
  }

  // Check if the job reference is exactly 5 alphanumeric characters
  if (!preg_match("/^[A-Za-z0-9]{5}$/", $_POST['jobRef'])) {
    header('Location: apply.php?error=1');
    exit();
  }

  // Check if the state and postcode match
  $statePostcodes = [
    'VIC' => '/^3|^8/',
    'NSW' => '/^1|^2/',
    'QLD' => '/^4|^9/',
    'NT'  => '/^0[89]/',
    'WA'  => '/^6/',
    'SA'  => '/^5/',
    'TAS' => '/^7[0-4]|^7[8-9]/',
    'ACT' => '/^02|^26/'
  ];  

  if (!preg_match($statePostcodes[$_POST['state']], $_POST['postcode'])) {
    header('Location: apply.php?error=1');
    exit();
  }
  
  // Check if skills are entered when checkbox is checked
  if (isset($_POST['otherskills_checkbox']) && empty($_POST['otherskills_input'])) {
    header('Location: apply.php?error=1');
    exit();
  }

  $jobref = sanitizeInput($_POST['jobref']);
  $firstname = sanitizeInput($_POST['firstName']);
  $lastname = sanitizeInput($_POST['lastName']);
  $dob = sanitizeInput($_POST['dob']);
  $gender = sanitizeInput($_POST['gender']);
  $address = sanitizeInput($_POST['address']);    
  $suburb = sanitizeInput($_POST['suburb']);      
  $state = sanitizeInput($_POST['state']);        
  $postcode = sanitizeInput($_POST['postcode']);  
  $email = sanitizeInput($_POST['email']);        
  $phone = sanitizeInput($_POST['phone']);    
  $skill1 = isset($skills[0]) ? sanitizeInput($skills[0]) : '';
  $skill2 = isset($skills[1]) ? sanitizeInput($skills[1]) : '';
  $skill3 = isset($skills[1]) ? sanitizeInput($skills[1]) : '';
  $otherSkillInput = sanitizeInput($_POST['otherskills_input']);

  $query = 'INSERT INTO eoi (jobReference, FirstName, LastName, Street, Suburb, State, Postcode, Email, Phone, Skill1, Skill2, OtherSkills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
  $stmt = mysqli_prepare($dbconn, $query);
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'ssssssssssss', $jobref, $firstname, $lastname, $dob, $gender, $address, $suburb, $state, $postcode, $email, $phone, $skills, $otherSkillInput);
  
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