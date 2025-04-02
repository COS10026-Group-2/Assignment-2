<?php
  // Start session
  session_start();
  
  // Retrieve error messages from GET parameters if any
  $errors_from_url = [];
  foreach ($_GET as $key => $value) {
    if (strpos($key, 'error_') === 0) {
      // Extract field name from 'error_fieldname'
      $field = substr($key, 6); // Remove 'error_' prefix
      $errors_from_url[$field] = htmlspecialchars(urldecode($value));
    }
  }

  // Store session data temporarily if it exists, then clear it
  $form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : null;
  unset($_SESSION['form_data']);

  // Helper function to display error for a field
  function display_error($field, $errors_from_url) {
    if ( !empty($errors_from_url[$field])) {
      echo '<p class="form-error">' . $errors_from_url[$field] . '</p>';
    }
  }

  // Helper function to repopulating (value, textarea, checkbox, radio, select)
  function repopulate_value($field, $form_data) {
    if (isset($form_data[$field])) {
      echo htmlspecialchars($form_data[$field]);
    }
  }
  function repopulate_textarea($field, $form_data) {
    if (isset($form_data[$field])) {
      echo htmlspecialchars($form_data[$field]);
    }
  }
  function repopulate_checkbox($field, $value, $form_data) {
    if (isset($form_data[$field]) && is_array($form_data[$field]) && in_array($value, $form_data[$field])) {
      echo 'checked';
    }
    elseif (isset($form_data[$field]) && !is_array($form_data[$field]) && $form_data[$field] == $value) {
      echo 'checked';
    }
  }
  function repopulate_radio($field, $value, $form_data) {
    if (isset($form_data[$field]) && $form_data[$field] == $value) {
      echo 'checked';
    }
  }
  function repopulate_select($field, $value, $form_data) {
    if (isset($form_data[$field]) && $form_data[$field] == $value) {
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
      <label for="jobRef">Job Reference</label>
      <input type="text" id="jobRef" name="jobRef" pattern="[A-Za-z0-9]{5}" placeholder="exactly 5 alphanumeric characters" title="Must be exactly 5 alphanumeric characters" required value='<?php repopulate_value('jobRef',$form_data)?>'>
      <?php display_error('jobRef', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="firstName">First name</label>
      <input type="text" id="firstName" name="firstName" maxlength="20" placeholder="max 20 alpha characters" required value='<?php repopulate_value('firstName', $form_data)?>'>
      <?php display_error('firstName', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="lastName">Last name</label>
      <input type="text" id="lastName" name="lastName" maxlength="20" placeholder="max 20 alpha characters" required value='<?php repopulate_value('lastName', $form_data)?>'>
      <?php display_error('lastName', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="dob">Date of birth</label>
      <input type="date" id="dob" name="dob" required value='<?php repopulate_value('dob', $form_data)?>'>
      <?php display_error('dob', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <div class="gender-container">
        <label for="gender">Gender:</label>
        <div class="radio-group">
          <div>
            <input type="radio" id="male" name="gender" value="male" <?php repopulate_radio('gender', 'male', $form_data)?>>
            <label for="male">Male</label>
          </div>
          <div>
            <input type="radio" id="female" name="gender" value="female" <?php repopulate_radio('gender', 'female', $form_data)?>>
            <label for="female">Female</label>
          </div>
          <div>
            <input type="radio" id="other" name="gender" value="other" <?php repopulate_radio('gender', 'other', $form_data)?>>
            <label for="other">Other</label>
          </div>
        </div>
        <?php display_error('gender', $errors_from_url)?>
      </div>
    </div>
    
    <div class="form-label">
      <label for="street">Street Address</label>
      <input type="text" id="street" name="street" maxlength="40" placeholder="max 40 characters" required value='<?php repopulate_value('street', $form_data)?>'>
      <?php display_error('street', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="suburb">Suburb/Town</label>
      <input type="text" id="suburb" name="suburb" maxlength="40" placeholder="max 40 characters" required value='<?php repopulate_value('suburb', $form_data)?>'>
      <?php display_error('suburb', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="state">State</label>
      <select id="state" name="state" required>
        <option value="">Select an option</option>
        <option value="VIC" <?php repopulate_select('state', 'VIC', $form_data)?>>VIC</option>
        <option value="NSW" <?php repopulate_select('state', 'NSW', $form_data)?>>NSW</option>
        <option value="QLD" <?php repopulate_select('state', 'QLD', $form_data)?>>QLD</option>
        <option value="NT" <?php repopulate_select('state', 'NT', $form_data)?>>NT</option>
        <option value="WA" <?php repopulate_select('state', 'WA', $form_data)?>>WA</option>
        <option value="SA" <?php repopulate_select('state', 'SA', $form_data)?>>SA</option>
        <option value="TAS" <?php repopulate_select('state', 'TAS', $form_data)?>>TAS</option>
        <option value="ACT" <?php repopulate_select('state', 'ACT', $form_data)?>>ACT</option>
      </select>
      <?php display_error('state', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="postcode">Postcode</label>
      <input type="text" id="postcode" name="postcode" pattern="\d{4}" title="Must be exactly 4 digits" placeholder="1234" required value='<?php repopulate_value('postcode', $form_data)?>'>
      <?php display_error('postcode', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="email">Email address</label>
      <input type="email" id="email" name="email" placeholder="camapbonchan@gmail.com" required value='<?php repopulate_value('email', $form_data)?>'>
      <?php display_error('email', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <label for="phone">Phone number</label>
      <input type="text" id="phone" name="phone" pattern="[0-9 ]{8,12}" title="Phone number must be 8 to 12 digits" placeholder="1234567890" required value='<?php repopulate_value('phone', $form_data)?>'>
      <?php display_error('phone', $errors_from_url)?>
    </div>
    
    <div class="form-label">
      <div class="skill-tickbox-container">
        <label>Skills:</label>
        <div class="checkbox-group">
          <div>
            <input type="checkbox" id="skill1" name="skills[]" value="HTML" <?php repopulate_checkbox('skills', 'HTML', $form_data)?>>
            <label for="skill1">HTML</label>
          </div>
          <div>
            <input type="checkbox" id="skill2" name="skills[]" value="CSS" <?php repopulate_checkbox('skills', 'CSS', $form_data)?>>
            <label for="skill2">CSS</label>
          </div>
          <div>
            <input type="checkbox" id="skill3" name="skills[]" value="JavaScript" <?php repopulate_checkbox('skills', 'JavaScript', $form_data)?>>
            <label for="skill3">JavaScript</label>
          </div>
          <?php display_error('skills', $errors_from_url)?>
          <div>
            <input type="checkbox" id="otherskills_checkbox" name="otherskills_checkbox" value="otherskills_checked" <?php repopulate_checkbox('otherskills_checkbox', 'otherskills_checked', $form_data)?>>
            <label for="otherskills_checkbox">Other Skills</label>
          </div>
          <?php display_error('otherskills_checkbox', $errors_from_url)?>
        </div>
      </div>
    </div>
    
    <div class="form-label">
      <div class="form-row">
        <label for="otherskills_input">Other skills</label>
        <textarea id="otherskills_input" name="otherskills_input" rows="4" cols="40"><?php repopulate_textarea('otherskills_input', $form_data)?> </textarea>
      </div>
      <?php display_error('otherskills_input', $errors_from_url)?>
    </div>
    
    <div class="form-buttons">
      <input type="submit" value="Apply">
      <input type="reset" value="Reset Form">
    </div>
  </div>
</form>

</div>

<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d774766.4983746263!2d145.053135!3d-37.972566!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad646b5d2ba4df7%3A0x4045675218ccd90!2sMelbourne%20VIC%2C%20Australia!5e1!3m2!1sen!2sus!4v1743563071367!5m2!1sen!2sus" 
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