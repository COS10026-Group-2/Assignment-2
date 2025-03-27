<?php
  $pageTitle = "About Us";
  include "header.inc";
?>
    <main>
        <div class="team-info" id="group-info-id">
            <dl>
                <dt>Group Name</dt>
                <dd>BitSmiths</dd>

                <dt>Group ID</dt>
                <dd>Group-2</dd>

                <dt>Tutor's Name</dt>
                <dd>Vu Ngoc Binh</dd>

                <dt>Member 1 Contribution</dt>
                <dd>Hoang Anh Dung - HTML Page Development & Content Creation</dd>

                <dt>Member 2 Contribution</dt>
                <dd>Hoang An Phong - CSS Styling & Design</dd>

                <dt>Member 3 Contribution</dt>
                <dd>Ngo Viet Thang - Form Development & Enhancements</dd>

                <dt>Member 4 Contribution</dt>
                <dd>Le Doan Sang - Deployment, Group Page, & Video</dd>
            </dl>

            <figure>
                <img src="images/group_image.jpg" alt="Group photo of BitSmiths team members" width="300" height="200">
                <figcaption>BitSmiths Team Photo<br>From left to right: Sang, Phong, Dung, Thang</figcaption>
            </figure>
          </div>

        <section class="timetable">
            <h2>Team Timetable</h2>
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>08:00-12:00</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>Lecture & Lab Sessions</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>14:00-16:00</td>
                        <td>-</td>
                        <td>Collaborative Work</td>
                        <td>-</td>
                        <td>-</td>
                        <td>Collaborative Work</td>
                        <td>Team Meeting</td>
                    </tr>
                    <tr>
                        <td>19:00-21:00</td>
                        <td>-</td>
                        <td>Team Meeting</td>
                        <td>-</td>
                        <td>-</td>
                        <td></td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="additional-info">
            <h2>Team Profile</h2>
            <h3>Programming Skills</h3>
            <ul>
                <li>HTML5 & CSS3</li>
                <li>JavaScript</li>
                <li>Python</li>
                <li>Java</li>
            </ul>

            <h3>Team Interests</h3>
            <ul>
                <li>Web Development</li>
                <li>Mobile App Development</li>
                <li>Artificial Intelligence</li>
                <li>Cybersecurity</li>
            </ul>
        </section>

        <section class="contact">
            <h2>Contact Us</h2>
            <p>Email: <a href="mailto:104212401@student.swin.edu.au">104212401@student.swin.edu.au</a></p>
        </section>
    </main>

    <section class="footer">
        <div class="container2">
          <div class="footer-section">
            <h3>Job</h3>
            <p>Join us to explore exciting opportunities, enhance your skills, and take the next step towards a fulfilling professional journey.</p>
          </div>
    
          <div class="footer-section">
            <h3>Company</h3>
            <ul>
              <li><a href="about.html">About Us</a></li>
              <li><a href="about.html#group-info-id">Our Team</a></li>
              <li><a href="">Partners</a></li>
              <li><a href="jobs.html">For Candidates</a></li>
              <li><a href="apply.html">For Employers</a></li>
            </ul>
          </div>

          <div class="footer-section">
            <h3>Job Categories</h3>
            <ul>
              <li><a href="">Telecommunications</a></li>
              <li><a href="">Hotels & Tourism</a></li>
              <li><a href="">Construction</a></li>
              <li><a href="">Education</a></li>
              <li><a href="">Financial Services</a></li>
            </ul>
          </div>
    
          <div class="footer-newsletter">
            <h3>Newsletter</h3>
            <p>Don't miss out on valuable resources that can help you achieve your professional goals.</p>
            <label for="newsletter-email" class="visually-hidden">Enter your email</label>
            <input type="email" id="newsletter-email" placeholder="Enter your email...">
            <button type="submit" class="subscribe">Subscribe now</button>
          </div>
        </div>
      </section>
</body>
</html>
