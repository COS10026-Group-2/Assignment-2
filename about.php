<?php
  $pageTitle = "About Us";
  include "header.inc";
  include "menu.inc";
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

<?php
  include "footer.inc";
?>
