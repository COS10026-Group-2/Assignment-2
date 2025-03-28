<?php
  require_once "settings.php";
  $dbconn = @mysqli_connect($host, $user, $pwd, $sql_db);
  if (!$dbconn){
    echo "<p>Unable to connect to the database</p>";
  } else {
    if (!preg_match("/^[A-Za-z0-9]{5}$/", $_POST['jobRef'])) {
      die('Job Reference must be 5 alphanumeric characters.');
    }

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
    
    if (!preg_match($statePostcodes[$_POST['state']], $_POST_['postcode'])); {
      die('Postcode invalid for selected state.');
    }

    $jobref = htmlspecialchars(trim($_POST["carmake"]));
    $model = htmlspecialchars(trim($_POST["carmodel"]));

    $price = preg_replace('/[^0-9.]/', '', htmlspecialchars(trim($_POST["price"])));
    // Keeps only numbers and decimal point

    $yom = htmlspecialchars(trim($_POST["yom"]));

    $query = "INSERT INTO cars(make, model, price, yom) VALUES ('$make', '$model', $price, '$yom')";
    // NOTE: `$price` no longer has quotes since it's now a pure number

    $result = mysqli_query($dbconn, $query);
    
    if(!$result){
      echo "<p class=\"wrong\">Something is wrong with ", $query, "</p>";
    } else {
      echo "<p class=\"ok\">Successfully added New Car records</p>";
    }
    mysqli_close($dbconn);
  }
?>