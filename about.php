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

                <dt>Course</dt> 
                <dd>COS10026 Computing Technology Inquiry Project</dd>
                
                <dt>Member 1 (Hoang Anh Dung)</dt>
                <dd>
                    Converted HTML pages to use PHP includes<br>
                    Created the database configuration file settings.php<br>
                    Implemented PHP Enhancements, including manager login/registration system.
                </dd>

                <dt>Member 2 (Le Doan Sang)</dt>
                <dd>
                    Focused on the Expression of Interest (EOI) functionality<br>
                    Designed and implemented the eoi database table schema<br> Developed the process_eoi.php script for server-side validation and database insertion.
                </dd>

                <dt>Member 3 (Ngo Viet Thang)</dt>
                <dd>
                    Developed Human Resources management page (manage.php)<br> Implemented required queries for viewing, filtering, deleting, and updating EOI records.<br>
                    Handled team organisation and presentation preparation.
                </dd>

                <dt>Member 4 (Hoang An Phong)</dt>
                <dd>
                    Responsible for the Jobs section, including the jobs database table setup and dynamically loading job descriptions onto jobs.php.<br>
                    Also managed updates to this about.php page content.
                </dd>
                 <?php /* --- End of Specific Contributions --- */ ?>

            </dl>

            <figure>
                <img src="images/group_image.jpg" alt="Group photo of BitSmiths team members" width="300" height="200">
                 <figcaption>BitSmiths Team Photo<br>From left to right: Sang, Phong, Dung, Thang</figcaption> <?php /* Keep or update image/caption */ ?>
            </figure>
          </div>

        <?php /* Keep the rest of the page (Timetable, Skills, Contact) as updated previously or modify as needed */ ?>
        <section class="timetable">
             <h2>Team Timetable (Project Part 2)</h2>
             <?php /* Use the timetable structure from the previous response, updated with your actual schedule */ ?>
             <table>
                 <thead>
                     <tr>
                         <th>Week</th>
                         <th>Task Focus / Activity</th>
                         <th>Key Dates / Meetings</th>
                     </tr>
                 </thead>
                 <tbody>
                     <tr>
                         <td>Week 7/8</td>
                         <td>Part 1 Review, Part 2 Planning, PHP Includes Setup, DB Schema Design</td>
                         <td>Team Meeting (Planning), Tutor Consultation</td>
                     </tr>
                     <tr>
                         <td>Week 9</td>
                         <td>PHP Form Processing (process_eoi.php), Server-Side Validation</td>
                         <td>Team Meeting (Progress Check)</td>
                     </tr>
                     <tr>
                         <td>Week 10</td>
                         <td>Dynamic jobs.php Page, manage.php Basic Queries (List/Filter)</td>
                         <td>Team Meeting (Integration)</td>
                     </tr>
                     <tr>
                         <td>Week 11</td>
                         <td>manage.php Advanced Queries (Delete/Update), Enhancement Implementation (Login System)</td>
                         <td>Team Meeting (Testing), Tutor Demo Prep</td>
                     </tr>
                     <tr>
                         <td>Week 12</td>
                         <td>Final Testing, Deployment, Documentation (phpenhancements.php), Presentation Prep</td>
                         <td>**Final Submission**, In-Class Presentation</td>
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
                 <li>PHP & MySQL (mysqli)</li>
                 <li>Python</li>
                 <li>Java</li>
             </ul>
             <h3>Team Interests</h3>
              <ul>
                  <li>Web Development</li>
                  <li>Database Management</li>
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