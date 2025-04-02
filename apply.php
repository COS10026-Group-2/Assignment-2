<?php
  // Retrieve error messages from GET parameters if any
  $errors_from_url = [];
  foreach ($_GET as $key => $value) {
    if (strpos($key, 'error_') === 0) {
      // Extract field name from 'error_fieldname'
      $field = substr($key, 6); // Remove 'error_' prefix
      $errors_from_url[$field] = htmlspecialchars(urldecode($value));
    }
  }

  // Helper function to display error for a field
  function display_error($field, $errors_from_url) {
    if ( !empty($errors_from_url[$field])) {
      echo '<p class="form-error">' . $errors_from_url[$field] . '</p>';
    }
  }

  // Helper function to repopulating (value, textarea, checkbox, radio, select)
  function repopulate_value($field) {
    if (isset($_POST[$field])) {
      echo htmlspecialchars($_POST[$field]);
    }
  }
  function repopulate_textarea($field) {
    if (isset($_POST[$field])) {
      echo htmlspecialchars($_POST[$field]);
    }
  }
  function repopulate_checkbox($field, $value) {
    if (isset($_POST[$field]) && is_array($_POST[$field]) && in_array($value, $_POST[$field])) {
      echo 'checked';
    }
    elseif (isset($_POST[$field]) && !is_array($_POST[$field]) && $_POST[$field] == $value) {
      echo 'checked';
    }
  }
  function repopulate_radio($field, $value) {
    if (isset($_POST[$field]) && $_POST[$field] == $value) {
      echo 'checked';
    }
  }
  function repopulate_select($field, $value) {
    if (isset($_POST[$field]) && $_POST[$field] == $value) {
      echo 'selected';
    }
  }

  // Include header/menu
  $pageTitle = "Apply";
  include "header.inc";
  include "menu.inc";
?>

     <!--Start Form-->

<form method="post" action="process_eoi.php" novalidate="novalidate">
  <div class="form-container-job-apply">
    <div class="form-label">
      <label for="jobRef">Job reference number</label>
      <input type="text" id="jobRef" name="jobRef" pattern="[A-Za-z0-9]{5}" placeholder="exactly 5 alphanumeric characters" title="Must be exactly 5 alphanumeric characters" required value='<?php repopulate_value('jobRef')?>'>
      <?php display_error('jobRef', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="firstName">First name</label>
      <input type="text" id="firstName" name="firstName" maxlength="20" placeholder="max 20 alpha characters" required value='<?php repopulate_value('firstName')?>'>
      <?php display_error('firstName', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="lastName">Last name</label>
      <input type="text" id="lastName" name="lastName" maxlength="20" placeholder="max 20 alpha characters" required value='<?php repopulate_value('lastName')?>'>
      <?php display_error('lastName', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="dob">Date of birth</label>
      <input type="date" id="dob" name="dob" required value='<?php repopulate_value('dob')?>'>
      <?php display_error('dob', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <div class="gender-container">
        <label for="gender">Gender:</label>
        <div class="radio-group">
          <div>
            <input type="radio" id="male" name="gender" value="male" <?php repopulate_radio('gender', 'male')?>>
            <label for="male">Male</label>
          </div>
          <div>
            <input type="radio" id="female" name="gender" value="female" <?php repopulate_radio('gender', 'female')?>>
            <label for="female">Female</label>
          </div>
          <div>
            <input type="radio" id="other" name="gender" value="other" <?php repopulate_radio('gender', 'other')?>>
            <label for="other">Other</label>
          </div>
        </div>
        <?php display_error('gender', $errors_from_url)?>
      </div>
    </div>
    
    <div class="form-label">
      <label for="street">Street Address</label>
      <input type="text" id="street" name="street" maxlength="40" placeholder="max 40 characters" required value='<?php repopulate_value('street')?>'>
      <?php display_error('street', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="suburb">Suburb/Town</label>
      <input type="text" id="suburb" name="suburb" maxlength="40" placeholder="max 40 characters" required value='<?php repopulate_value('suburb')?>'>
      <?php display_error('suburb', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="state">State</label>
      <select id="state" name="state" required>
        <option value="">Select an option</option>
        <option value="VIC" <?php repopulate_select('state', 'VIC')?>>VIC</option>
        <option value="NSW" <?php repopulate_select('state', 'NSW')?>>NSW</option>
        <option value="QLD" <?php repopulate_select('state', 'QLD')?>>QLD</option>
        <option value="NT" <?php repopulate_select('state', 'NT')?>>NT</option>
        <option value="WA" <?php repopulate_select('state', 'WA')?>>WA</option>
        <option value="SA" <?php repopulate_select('state', 'SA')?>>SA</option>
        <option value="TAS" <?php repopulate_select('state', 'TAS')?>>TAS</option>
        <option value="ACT" <?php repopulate_select('state', 'ACT')?>>ACT</option>
      </select>
      <?php display_error('state', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="postcode">Postcode</label>
      <input type="text" id="postcode" name="postcode" pattern="\d{4}" title="Must be exactly 4 digits" placeholder="1234" required value='<?php repopulate_value('postcode')?>'>
      <?php display_error('postcode', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="email">Email address</label>
      <input type="email" id="email" name="email" placeholder="camapbonchan@gmail.com" required value='<?php repopulate_value('email')?>'>
      <?php display_error('email', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="phone">Phone number</label>
      <input type="text" id="phone" name="phone" pattern="[0-9]{8,12}" title="Phone number must be 8 to 12 digits" placeholder="1234567890" required value='<?php repopulate_value('phone')?>'>
      <?php display_error('phone', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <div class="skill-tickbox-container">
        <label>Skills:</label>
        <div class="checkbox-group">
          <div>
            <input type="checkbox" id="skill1" name="skills[]" value="HTML" <?php repopulate_checkbox('skills', 'HTML')?>>
            <label for="skill1">HTML</label>
          </div>
          <div>
            <input type="checkbox" id="skill2" name="skills[]" value="CSS" <?php repopulate_checkbox('skills', 'CSS')?>>
            <label for="skill2">CSS</label>
          </div>
          <div>
            <input type="checkbox" id="skill3" name="skills[]" value="JavaScript" <?php repopulate_checkbox('skills', 'JavaScript')?>>
            <label for="skill3">JavaScript</label>
          </div>
          <?php display_error('skills', $errors_from_url)?>
          <div>
            <input type="checkbox" id="otherskills_checkbox" name="otherskills_checkbox" value="otherskills_checked" <?php repopulate_checkbox('otherskills_checkbox', 'otherskills_checked')?>>
            <label for="otherskills_checkbox">Other Skills</label>
          </div>
          <?php display_error('otherskills_checkbox', $errors_from_url)?>
        </div>
      </div>
    </div>
    
    <div class="form-label">
      <div class="form-row">
        <label for="otherskills_input">Other skills</label>
        <textarea id="otherskills_input" name="otherskills_input" rows="4" cols="40"><?php repopulate_textarea('otherskills_input')?> </textarea>
      </div>
      <?php display_error('otherSkills', $errors_from_url)?>
    </div>
    
    <div class="form-buttons">
      <input type="submit" value="Apply">
      <input type="reset" value="Reset Form">
    </div>
  </div>
</form>

</div>

<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d509004.415415665!2d144.7235038834284!3d-37.97156522388364!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad646b5d2ba4df7%3A0x4045675218ccd90!2sMelbourne%20VIC%2C%20Australia!5e1!3m2!1sen!2s!4v1740018193914!5m2!1sen!2s" 
width=100% 
height="450" 
style="border:0;" 
allowfullscreen="" 
loading="lazy" 
referrerpolicy="no-referrer-when-downgrade"
></iframe>

<?php
  include "footer.inc";
?>