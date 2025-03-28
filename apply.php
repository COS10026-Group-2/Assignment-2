<?php
  $pageTitle = "About Us";
  include "header.inc";
  include "menu.inc";
?>

     <!--Start Form-->
<form method="post" action="http://mercury.swin.edu.au/it000000/formtest.php">
    
          <form method="post" action="process_eoi.php">
            <div class="form-container-job-apply">
              <div class="form-label">
                <label for="jobRef">Job reference number</label>
                <input type="text" id="jobRef" name="jobRef" pattern="[A-Za-z0-9]{5}" placeholder="exactly 5 alpha characters" title="Must be exactly 5 alphanumeric characters" required>
              </div>
              
              <div class="form-label">
                <label for="firstName">First name</label>
                <input type="text" id="firstName" name="firstName" maxlength="20" placeholder="max 20 alpha characters" required>
              </div>
              
              <div class="form-label">
                <label for="lastName">Last name</label>
                <input type="text" id="lastName" name="lastName" maxlength="20" placeholder="max 20 alpha characters" required>
              </div>
              
              <div class="form-label">
                <label for="dob">Date of birth</label>
                <input type="date" id="dob" name="dob" required>
              </div>
              
              <div class="form-label">
                <div class="gender-container">
                  <label for="gender">Gender:</label>
                  <div class="radio-group">
                    <div>
                      <input type="radio" id="male" name="gender" value="male">
                      <label for="male">Male</label>
                    </div>
                    <div>
                      <input type="radio" id="female" name="gender" value="female">
                      <label for="female">Female</label>
                    </div>
                    <div>
                      <input type="radio" id="other" name="gender" value="other">
                      <label for="other">Other</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-label">
                <label for="address">Street Address</label>
                <input type="text" id="address" name="address" maxlength="40" placeholder="max 40 characters" required>
              </div>
              
              <div class="form-label">
                <label for="suburb">Suburb/Town</label>
                <input type="text" id="suburb" name="suburb" maxlength="40" placeholder="max 40 characters" required>
              </div>
              
              <div class="form-label">
                <label for="state">State</label>
                <select id="state" name="state" required>
                  <option value="">Select an option</option>
                  <option value="VIC">VIC</option>
                  <option value="NSW">NSW</option>
                  <option value="QLD">QLD</option>
                  <option value="NT">NT</option>
                  <option value="WA">WA</option>
                  <option value="SA">SA</option>
                  <option value="TAS">TAS</option>
                  <option value="ACT">ACT</option>
                </select>
              </div>
              
              <div class="form-label">
                <label for="postcode">Postcode</label>
                <input type="text" id="postcode" name="postcode" pattern="\d{4}" title="Must be exactly 4 digits" placeholder="1234" required>
              </div>
              
              <div class="form-label">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" placeholder="camapbonchan@gmail.com" required>
              </div>
              
              <div class="form-label">
                <label for="phone">Phone number</label>
                <input type="text" id="phone" name="phone" pattern="[0-9]{8,12}" title="Phone number must be 8 to 12 digits" placeholder="1234567890" required>
              </div>
              
              <div class="form-label">
                <div class="skill-tickbox-container">
                  <label>Skills:</label>
                  <div class="checkbox-group">
                    <div>
                      <input type="checkbox" id="skill1" name="skills" value="HTML">
                      <label for="skill1">HTML</label>
                    </div>
                    <div>
                      <input type="checkbox" id="skill2" name="skills" value="CSS">
                      <label for="skill2">CSS</label>
                    </div>
                    <div>
                      <input type="checkbox" id="skill3" name="skills" value="JavaScript">
                      <label for="skill3">JavaScript</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-label">
                <div class="form-row">
                  <label for="otherSkills">Other skills</label>
                  <textarea id="otherSkills" name="otherSkills" rows="4" cols="40"> </textarea>
                </div>
              </div>
              
              <div class="form-buttons">
                <input type="submit" value="Apply">
                <input type="reset" value="Reset Form">
              </div>
            </div>
          </form>
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