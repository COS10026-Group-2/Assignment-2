<details>
  <summary>Table of content</summary>
  
  - [Assignment-2](#assignment-2)
    - [1. Specified Requirements](#1-specified-requirements)
      - [1.1: PHP Includes](#11-php-includes)
      - [1.2: settings.php](#12-settingsphp)
      - [1.3: EOI Table](#13-eoi-table)
      - [1.4: process_eoi.php](#14-process_eoiphp)
      - [1.5: manage.php (HR Queries)](#15-managephp-hr-queries)
      - [1.6: about.php Update](#16-aboutphp-update)
      - [1.7: Jobs Table](#17-jobs-table)
      - [1.8: Enhancements](#18-enhancements)
    - [2. Web Structure](#2-web-structure)
      - [2.1: Folder Hierarchy](#21-folder-hierarchy)
      - [2.2: Required Files](#22-required-files)
      - [2.3: Include Files](#23-include-files)
      - [2.4: Relative Links](#24-relative-links)
      - [2.5: Deployment on Mercury](#25-deployment-on-mercury)
      - [2.6: Validation](#26-validation)
      - [2.7: Critical Notes](#27-critical-notes)
    - [3. Group Presentation](#3-group-presentation)
      - [3.1: Introduction (Individual Contributions)](#31-introduction-individual-contributions)
      - [3.2: Development Process (Technology Focus)](#32-development-process-technology-focus)
      - [3.3: Database Interaction](#33-database-interaction)
      - [3.4: Conclusion (Individual Reflections)](#34-conclusion-individual-reflections)
      - [3.5: Presentation Logistics](#35-presentation-logistics)
      - [3.6: Common Pitfalls & Tips](#36-common-pitfalls--tips)
    - [4. Review & Feedback](#4-review--feedback)
      - [4.1: Individual Task Requirements](#41-individual-task-requirements)
      - [4.2: Constructive Feedback Guidelines](#42-constructive-feedback-guidelines)
      - [4.3: Submission Process](#43-submission-process)
      - [4.4: Impact on Assessment](#44-impact-on-assessment)
      - [4.5: Best Practices](#45-best-practices)
      - [4.6: Ethical Considerations](#46-ethical-considerations)
      - [4.7: Example Evaluation Form](#47-example-evaluation-form)
    - [5. Group Submission](#5-group-submission)
      - [5.1: Group Submission](#51-group-submission)
      - [5.2: Individual Submission](#52-individual-submission)
      - [5.3: Deadlines & Penalties](#53-deadlines--penalties)
      - [5.4: File Structure Compliance](#54-file-structure-compliance)
      - [5.5: Presentation Submission](#55-presentation-submission)
      - [5.6: Common Pitfalls](#56-common-pitfalls)
      - [5.7: Assessment Alignment](#57-assessment-alignment)
      - [5.8: Post-Submission Steps](#58-post-submission-steps)
    - [6. Validation & Security](#6-validation--security)
      - [6.1: Server-Side Validation](#61-server-side-validation)
      - [6.2: Sanitization](#62-sanitization)
      - [6.3: Database Security](#63-database-security)
      - [6.4: Form Security](#64-form-security)
      - [6.5: Error Handling](#65-error-handling)
      - [6.6: Session Security (Enhancements)](#66-session-security-enhancements)
      - [6.7: Security Headers](#67-security-headers)
    - [7. Database Design](#7-database-design)
      - [7.1: EOI Table Schema](#71-eoi-table-schema)
      - [7.2: Jobs Table Schema](#72-jobs-table-schema)
      - [7.3: Normalization](#73-normalization)
      - [7.4: Indexing & Performance](#74-indexing--performance)
      - [7.5: Foreign Keys (Enhancement)](#75-foreign-keys-enhancement)
      - [7.6: SQL Scripts & Data Population](#76-sql-scripts--data-population)
      - [7.7: Backup & Recovery](#77-backup--recovery)
      - [7.8: Security](#78-security)
      - [7.9: Validation at Database Level](#79-validation-at-database-level)
      - [7.10: Example Queries](#710-example-queries)
      - [7.11: Troubleshooting](#711-troubleshooting)
    - [8. Details for Enhancements](#8-details-for-enhancements)
      - [8.1: Sorting Functionality](#81-sorting-functionality)
      - [8.2: Manager Login System](#82-manager-login-system)
      - [8.3: Audit Logging](#83-audit-logging)
      - [8.4: Email Notifications](#84-email-notifications)
      - [8.5: CSV Export](#85-csv-export)
      - [8.6: Responsive Design](#86-responsive-design)
      - [8.7: Rate Limiting](#87-rate-limiting) 
</details>


## 1. Specified Requirements
### **1.1: PHP Includes**

- **Modularization**:
    - Split static HTML elements into reusable `.inc` files:
        - `header.inc`: Contains `<head>`, meta tags, and site-wide CSS links.
        - `menu.inc`: Navigation bar with links to `index.php`, `jobs.php`, `apply.php`, etc.
        - `footer.inc`: Copyright, contact info, and scripts.
    - Use `<?php include('header.inc'); ?>` in `.php` pages to inject these components.
- **Validation**:
    - Ensure pages renamed to `.php` (e.g., `index.php`, `apply.php`).
    - Test includes to confirm no broken links or missing elements.

---

### **1.2: settings.php**

- **Database Configuration**:
    - Define connection variables:
        
        ```php
        <?php  
        $host = "feenix-mariadb.swin.edu.au";  
        $user = "s1234567";  
        $pwd  = "password";  
        $sql_db = "s1234567_db";  
        ?>  
        ```
        
    - Use `mysqli_connect($host, $user, $pwd, $sql_db)` in scripts like `process_eoi.php`.
- **Security**:
    - Place `settings.php` outside the public web directory (if possible) or restrict access via `.htaccess`.

---

### **1.3: EOI Table**

- **MySQL Schema**:
    
    ```sql
    CREATE TABLE eoi (  
      EOInumber INT AUTO_INCREMENT PRIMARY KEY,  
      JobReference VARCHAR(5) NOT NULL,  
      FirstName VARCHAR(20) NOT NULL,  
      LastName VARCHAR(20) NOT NULL,  
      Street VARCHAR(40) NOT NULL,  
      Suburb VARCHAR(40) NOT NULL,  
      State ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,  
      Postcode CHAR(4) NOT NULL,  
      Email VARCHAR(50) NOT NULL,  
      Phone VARCHAR(12) NOT NULL,  
      Skill1 VARCHAR(20),  
      Skill2 VARCHAR(20),  
      OtherSkills TEXT,  
      Status ENUM('New','Current','Final') DEFAULT 'New'  
    );  
    ```
    
- **Key Features**:
    - `AUTO_INCREMENT` for `EOInumber`.
    - `ENUM` for `State` and `Status` to enforce valid values.

---

### **1.4: process_eoi.php**

- **Server-Side Validation**:
    - **Job Reference**:
        
        ```php
        if (!preg_match("/^[A-Za-z0-9]{5}$/", $_POST["jobref"])) {  
          die("Job Reference must be 5 alphanumeric characters.");  
        }  
        ```
        
    - **Postcode-State Match**:
        
        ```php
        $statePostcodes = [  
          'VIC' => '/^3|^8/',  
          'NSW' => '/^1|^2/',  
          'QLD' => '/^4|^9/',  
          // ... other states  
        ];  
        if (!preg_match($statePostcodes[$_POST["state"]], $_POST["postcode"])) {  
          die("Postcode invalid for selected state.");  
        }  
        ```
        
    - **Other Skills**:
        
        ```php
        if (isset($_POST["otherskills_checkbox"]) && empty($_POST["otherskills"])) {  
          die("Other skills description is required.");  
        }  
        ```
        
- **Sanitization**:
    - `trim()`, `stripslashes()`, `htmlspecialchars()` on all inputs.
- **Database Insertion**:
    - Use `mysqli_prepare()` and `bind_param()` to prevent SQL injection.
    - Redirect to `apply.php?error=1` if validation fails.

---

### **1.5: manage.php (HR Queries)**

- **Functionality**:
    - **List All EOIs**:
        
        ```php
        $query = "SELECT * FROM eoi";  
        $result = mysqli_query($conn, $query);  
        ```
        
    - **Filter by Job Reference**:
        
        ```php
        $query = "SELECT * FROM eoi WHERE JobReference = '$jobref'";  
        ```
        
    - **Search by Name**:
        
        ```php
        $query = "SELECT * FROM eoi WHERE FirstName LIKE '%$name%' OR LastName LIKE '%$name%'";  
        ```
        
    - **Delete EOIs**:
        
        ```php
        $query = "DELETE FROM eoi WHERE JobReference = '$jobref'";  
        ```
        
    - **Update Status**:
        
        ```php
        $query = "UPDATE eoi SET Status = '$status' WHERE EOInumber = $eoi_id";  
        ```
        
- **UI Design**:
    - Forms with dropdowns for status (New/Current/Final) and input fields for job reference/name.
    - Display results in an HTML table using `mysqli_fetch_assoc()`.

---

### **1.6: about.php Update**

- **Content**:
    - Add/update team member contributions (e.g., "John: Database design").
    - Include a development timetable (e.g., "Week 10: Implemented manage.php").
- **PHP Integration**:
    - Use includes for consistency (e.g., `<?php include('header.inc'); ?>`).

---

### **1.7: Jobs Table**

- **Schema**:
    
    ```sql
    CREATE TABLE jobs (  
      JobID INT AUTO_INCREMENT PRIMARY KEY,  
      JobReference VARCHAR(5) UNIQUE NOT NULL,  
      Title VARCHAR(50) NOT NULL,  
      Description TEXT,  
      Requirements TEXT,  
      ClosingDate DATE  
    );  
    ```
    
- **Dynamic Rendering**:
    - Fetch jobs from the database:
        
        ```php
        $query = "SELECT * FROM jobs";  
        $result = mysqli_query($conn, $query);  
        while ($row = mysqli_fetch_assoc($result)) {  
          echo "<h2>{$row['Title']}</h2>";  
          echo "<p>{$row['Description']}</p>";  
        }  
        ```
        

---

### **1.8: Enhancements**

- **Sorting Functionality**:
    
    ```php
    $sortField = $_GET["sort"] ?? "EOInumber"; // Default sort by EOInumber  
    $validFields = ["EOInumber", "LastName", "Status"]; // Whitelist allowed fields  
    if (!in_array($sortField, $validFields)) $sortField = "EOInumber";  
    $query = "SELECT * FROM eoi ORDER BY $sortField";  
    ```
    
    - Add a dropdown in `manage.php` to select the sort field.
- **Manager Login System**:
    - **Registration Page**:
        - Validate username uniqueness and password complexity (e.g., 8+ characters, mix of letters/numbers).
        - Store credentials in a `managers` table with `password_hash()`.
    - **Login Security**:
        - Track failed attempts in the database.
        - Lock account for 30 minutes after 3 failed attempts.
    - **Session Management**:
        
        ```php
        session_start();  
        if (!isset($_SESSION["manager"])) {  
          header("Location: login.php");  
          exit();  
        }  
        ```
        

---
## 2. Web Structure
### **2.1: Folder Hierarchy**

- **Root Directory**:
    - Named `project2/` (case-sensitive).
    - Contains all primary PHP/HTML files (e.g., `index.php`, `jobs.php`).
- **Subdirectories**:
    - `images/`: Stores images for page content (e.g., logos, banners).
        - Example: `<img src="images/logo.png" alt="Company Logo">`.
    - `styles/`: Holds CSS files (e.g., `style.css`, `forms.css`).
        - Linked via `<link rel="stylesheet" href="styles/style.css">`.
    - `styles/images/`: Stores images referenced in CSS (e.g., backgrounds, icons).
        - Example CSS: `background: url('styles/images/bg-pattern.png');`.

---

### **2.2: Required Files**

- **Core Pages**:
    - `index.php`: Homepage with job listings and navigation.
    - `jobs.php`: Displays detailed job descriptions (dynamic from `jobs` table).
    - `apply.php`: Job application form linked to `process_eoi.php`.
    - `about.php`: Team contributions and project timetable.
    - `manage.php`: HR interface for EOI management (password-protected).
    - `phpenhancements.php`: Documents optional features (e.g., sorting, login system).
- **Support Files**:
    - `settings.php`: Stores database credentials (e.g., `$host`, `$user`).
    - `header.inc`, `menu.inc`, `footer.inc`: Reusable components.
    - `process_eoi.php`: Handles form submission and database insertion.

---

### **2.3: Include Files**

- **Header (`header.inc`)**:
    - Contains `<head>` section:
        
        ```html
        <!DOCTYPE html>  
        <html lang="en">  
        <head>  
          <meta charset="UTF-8">  
          <title>Company XYZ</title>  
          <link rel="stylesheet" href="styles/style.css">  
        </head>  
        <body>  
        ```
        
- **Menu (`menu.inc`)**:
    - Navigation bar with relative links:
        
        ```html
        <nav>  
          <ul>  
            <li><a href="index.php">Home</a></li>  
            <li><a href="jobs.php">Jobs</a></li>  
            <li><a href="apply.php">Apply</a></li>  
          </ul>  
        </nav>  
        ```
        
- **Footer (`footer.inc`)**:
    - Copyright and scripts:
        
        ```html
        <footer>  
          <p>© 2024 Company XYZ. All rights reserved.</p>  
        </footer>  
        <script src="scripts/main.js"></script>  
        </body>  
        </html>  
        ```
        

---

### **2.4: Relative Links**

- **Image Paths**:
    - Correct: `src="images/banner.jpg"`.
    - Incorrect: `src="/var/www/project2/images/banner.jpg"` (absolute path).
- **CSS/JS References**:
    - Use `href="styles/style.css"` instead of `href="http://example.com/styles/style.css"`.
- **PHP Includes**:
    - Reference include files relative to the root:
        
        ```php
        <?php include('menu.inc'); ?>  
        ```
        

---

### **2.5: Deployment on Mercury**

- **Upload Process**:
    - Zip the `project2/` folder and upload to Mercury via SFTP.
    - Unzip on Mercury, ensuring the directory structure is preserved.
- **Server Configuration**:
    - Ensure PHP is enabled and `settings.php` has correct permissions (e.g., `chmod 600 settings.php`).
    - Test all pages on Mercury (e.g., `https://mercury.swin.edu.au/project2/index.php`).
- **Security Measures**:
    - Restrict access to `manage.php` via `.htaccess` if enhancements include login:
        
        ```apache
        <Files "manage.php">  
          AuthType Basic  
          AuthName "Restricted Access"  
          AuthUserFile /path/to/.htpasswd  
          Require valid-user  
        </Files>  
        ```
        

---

### **2.6: Validation**

- **Broken Link Check**:
    - Use tools like W3C Link Checker to verify relative paths.
- **Cross-Browser Testing**:
    - Ensure compatibility with Chrome, Firefox, and Safari.
- **Case Sensitivity**:
    - Confirm filenames match exactly (e.g., `Header.inc` vs. `header.inc` will fail on Unix servers).

---

### **2.7: Critical Notes**

- **Avoid Absolute Paths**:
    - Hardcoding paths like `C:\xampp\htdocs\project2\images\logo.png` will break on Mercury.
- **File Naming Conventions**:
    - Case-sensitive for Linux servers: `manage.php` ≠ `Manage.php`.
- **Submission Compliance**:
    - The ZIP file must retain the `project2/` folder structure.
    - All pages must run from `index.php` without manual file rearrangement.

---
## 3. Group Presentation
### **3.1: Introduction (Individual Contributions)**

- **Roles & Responsibilities**:
    - **Member 1 (Front-End Developer)**:
        - Designed HTML/CSS for `apply.php` and `jobs.php`.
        - Implemented client-side validation using HTML5 attributes.
    - **Member 2 (Back-End Developer)**:
        - Built PHP scripts (`process_eoi.php`, `manage.php`).
        - Integrated MySQL with mysqli for EOI/jobs tables.
    - **Member 3 (Database Architect)**:
        - Created schema for `eoi` and `jobs` tables.
        - Optimized queries for HR management features.
    - **Member 4 (Security & Enhancements)**:
        - Added login system for `manage.php`.
        - Implemented server-side validation and sanitization.
- **Project Objectives**:
    - Explain how your tasks align with the assignment goals (e.g., dynamic content, data integrity).

---

### **3.2: Development Process (Technology Focus)**

- **Front-End (HTML/CSS)**:
    - Demonstrated responsive design using Flexbox/Grid.
    - Example:
        
        ```html
        <!-- Responsive navigation in menu.inc -->  
        <nav class="flex-container">  
          <a href="index.php" class="flex-item">Home</a>  
          <a href="jobs.php" class="flex-item">Jobs</a>  
        </nav>  
        ```
        
- **PHP Scripting**:
    - Discussed server-side validation logic:
        
        ```php
        // Check job reference format  
        if (!preg_match("/^[A-Z0-9]{5}$/", $jobref)) {  
          header("Location: apply.php?error=invalid_jobref");  
          exit();  
        }  
        ```
        
- **Database Integration**:
    - Explained normalized table design to avoid redundancy.
    - Example query for HR interface:
        
        ```sql
        -- Retrieve EOIs for job reference 'SW123'  
        SELECT * FROM eoi WHERE JobReference = 'SW123';  
        ```
        
- **Security Enhancements**:
    - Demo hashed password storage:
        
        ```php
        $hashed_pwd = password_hash($_POST["password"], PASSWORD_DEFAULT);  
        ```
        

---

### **3.3: Database Interaction**

- **EOI Workflow**:
    - **Front-End to Back-End**: How form data from `apply.php` is sanitized and inserted into `eoi`.
    - **HR Management**: How `manage.php` uses `UPDATE`/`DELETE` queries.
- **Dynamic Job Listings**:
    - Demonstrated PHP loop to render jobs from the database:
        
        ```php
        $result = mysqli_query($conn, "SELECT * FROM jobs");  
        while ($row = mysqli_fetch_assoc($result)) {  
          echo "<div class='job'><h3>{$row['Title']}</h3></div>";  
        }  
        ```
        
- **Challenges & Solutions**:
    - **Postcode-State Validation**: Used regex arrays to match state rules.  
        **SQL Injection Prevention**: Utilized prepared statements:
        
        ```php
        $stmt = mysqli_prepare($conn, "INSERT INTO eoi (...) VALUES (?, ?, ...)");  
        mysqli_stmt_bind_param($stmt, "ssss...", $jobref, $firstname, ...);  
        ```
        

---

### **3.4: Conclusion (Individual Reflections)**

- **Key Learnings**:
    - **Member 1**: Mastered responsive design and form validation.
    - **Member 2**: Improved PHP/MySQL integration skills.
    - **Member 3**: Learned schema optimization for scalability.
    - **Member 4**: Explored security best practices (e.g., password hashing).
- **Collaboration Insights**:
    - Used Git for version control to merge code seamlessly.
    - Conducted peer code reviews to ensure quality.

---

### **3.5: Presentation Logistics**

- **Time Allocation**:
    - 3-5 minutes per member for a 4-person group.
    - Rehearse transitions between speakers.
- **Visual Aids**:
    - Slides with code snippets, ER diagrams, and screenshots.
    - Live demo of `manage.php` updating EOI status.
- **Submission Requirements**:
    - Upload slides to Canvas as part of group submission.
    - Ensure slides include team names and student IDs.

---

### **3.6: Common Pitfalls & Tips**

- **Avoid Overloading Slides**: Use bullet points, not paragraphs.
- **Technical Demo Prep**:
    - Test all features (e.g., form submission) on Mercury beforehand.
    - Have backup screenshots if live demo fails.
- **Q&A Preparation**:
    - Anticipate questions on validation logic or database design.
    - Assign topics to members (e.g., security, front-end).

---
## 4. Review & Feedback
### **4.1: Individual Task Requirements**

- **Peer Evaluation**:
    - Submit anonymous ratings for each team member via Canvas.
    - Criteria include:
        - **Contribution**: Quality and quantity of work (e.g., "Member X coded 80% of `process_eoi.php`").
        - **Communication**: Responsiveness in meetings and messaging platforms.
        - **Reliability**: Meeting deadlines (e.g., "Member Y submitted their tasks late twice").
        - **Collaboration**: Willingness to help others (e.g., "Member Z debugged SQL queries for the team").
    - Use a 5-point scale (1 = Poor, 5 = Excellent) or qualitative comments.
- **Self-Evaluation**:
    - Reflect on personal strengths (e.g., "I designed the database schema efficiently").
    - Identify areas for growth (e.g., "I could improve time management").

---

### **4.2: Constructive Feedback Guidelines**

- **Effective Feedback Examples**:
    - **Positive**:
        - "Member A’s validation logic in `process_eoi.php` ensured data integrity."
        - "Member B’s CSS made the site mobile-friendly."
    - **Constructive Criticism**:
        - "Member C could improve by documenting code for easier collaboration."
        - "Member D missed two meetings; better communication would help."
- **Avoid**:
    - Vague statements: "Member E did okay."
    - Personal attacks: "Member F is lazy."

---

### **4.3: Submission Process**

- **Canvas Form**:
    - Accessed via "Project Part 2" > "Peer & Self Evaluation."
    - Anonymous submission to protect privacy.
    - Deadline matches project submission (Week 12).
- **Post-Submission**:
    - Instructors compile feedback and share anonymized summaries.
    - Used to adjust individual marks if contribution discrepancies exist.

---

### **4.4: Impact on Assessment**

- **Weighting**:
    - 30% of Project Part 2 marks depend on peer/self-reviews.
- **Scenarios**:
    - **High Praise**: Member receives full 30% if peers confirm exceptional work.
    - **Negative Feedback**: Member’s score reduced if multiple peers report low contribution.
    - **Disputes**: Instructors mediate if a member contests evaluations.

---

### **4.5: Best Practices**

- **Document Contributions**:
    - Use shared tools (e.g., Trello, Google Sheets) to track tasks.
    - Example:
        
        |Task|Assigned To|Status|
        |---|---|---|
        |Design `eoi` table|Member A|Complete|
        
- **Address Issues Early**:
    - Report inactive members to the tutor during the project, not just in final feedback.
- **Be Objective**:
    - Focus on actions, not personalities (e.g., "Missed deadlines" vs. "Lazy").

---

### **4.6: Ethical Considerations**

- **Fairness**:
    - Avoid "revenge ratings" due to personal conflicts.
    - Base feedback on observable contributions.
- **Confidentiality**:
    - Do not share peer evaluations outside the Canvas system.

---

### **4.7: Example Evaluation Form**

1. **Rate Member X’s technical skills**:  
    ☐ 1 (Poor) ☐ 2 ☐ 3 ☐ 4 ☐ 5 (Excellent)
2. **Describe Member X’s key contribution**:
    
    > "Refactored the PHP includes to reduce redundancy."
    
3. **Suggest one improvement for Member X**:
    
    > "Could participate more in weekly standups."
    

---
## 5. Group Submission
### **5.1: Group Submission**

- **Compressed File**:
    - **Name**: `groupName_part2.zip` (e.g., `AlphaSquad_part2.zip`).
    - **Contents**:
        - All PHP/HTML files (`index.php`, `manage.php`, `apply.php`, etc.).
        - Include files (`header.inc`, `settings.php`).
        - `images/`, `styles/`, and `styles/images/` directories with assets.
        - SQL scripts for table creation (e.g., `eoi.sql`, `jobs.sql`).
        - Presentation slides (PDF/PPTX).
    - **Structure**:
        
        ```
        project2/  
        ├── index.php  
        ├── styles/  
        │   └── style.css  
        ├── images/  
        │   └── logo.png  
        └── presentation_slides.pptx  
        ```
        
- **Mercury Deployment**:
    - Include the live Mercury URL in the Canvas submission comment (e.g., `https://mercury.swin.edu.au/project2`).
    - Validate that the site runs directly from `index.php` without manual fixes.

---

### **5.2: Individual Submission**

- **Presentation**:
    - **Delivery**: Each member presents their section in Week 12 (10–20 minutes total).
    - **Absence Policy**: Zero marks for non-attendance without approved special consideration.
- **Peer Evaluation**:
    - Submit the Canvas form under "Project Part 2 > Peer & Self Evaluation."
    - Criteria include:
        - Technical contribution (e.g., "Coded `manage.php` queries").
        - Timeliness (e.g., "Submitted tasks 2 days late").
        - Team collaboration (e.g., "Helped debug SQL errors").

---

### **5.3: Deadlines & Penalties**

- **Due Date**: 11 PM Monday, Week 12.
- **Late Penalties**:
    - 10% deduction per day (e.g., 20% off after 2 days).
    - Applies to both group and individual submissions.
- **Resubmissions**:
    - Allowed multiple times on Canvas; only the last submission is graded.

---

### **5.4: File Structure Compliance**

- **Validation Checklist**:
    - No absolute paths (e.g., `C:\xampp\htdocs\project2\styles.css`).
    - Case-sensitive filenames (e.g., `Header.inc` ≠ `header.inc`).
    - All PHP includes functional on Mercury (test before submission).
- **Critical Files**:
    - `settings.php` must contain correct Feenix-MariaDB credentials.
    - `manage.php` must restrict unauthorized access (basic auth or enhancements).

---

### **5.5: Presentation Submission**

- **Slide Requirements**:
    - Title slide with group name and member names/IDs.
    - Sections for each member’s role and technical contributions.
    - Screenshots of key features (e.g., `manage.php` interface).
    - ER diagram of the `eoi` and `jobs` tables.
- **Live Demo Tips**:
    - Test `process_eoi.php` form submission during rehearsal.
    - Prepare backup screenshots in case of Mercury downtime.

---

### **5.6: Common Pitfalls**

- **Broken Links**:
    - Test all hyperlinks and asset paths after unzipping the submission.
- **Incomplete Enhancements**:
    - If implementing login, ensure `managers` table and session logic are included.
- **Missing Files**:
    - Double-check for `styles/images/` backgrounds and SQL scripts.

---

### **5.7: Assessment Alignment**

- **Group Marks (70%)**:
    - **Functionality**: Forms, validation, database integration.
    - **Code Quality**: Readability, comments, security.
    - **Documentation**: `phpenhancements.php` clarity.
- **Individual Marks (30%)**:
    - **Presentation**: Clarity, technical depth, time management.
    - **Peer Reviews**: Fairness, specificity, professionalism.

---

### **5.8: Post-Submission Steps**

1. **Verify Mercury Deployment**:
    - Test all pages logged in as a guest and HR manager (if enhancements include auth).
2. **Download & Test ZIP**:
    - Ensure unzipped files retain the `project2/` structure.
3. **Confirm Canvas Submission**:
    - Check timestamp and Mercury link in submission comments.

---
## 6. Validation & Security
### **6.1: Server-Side Validation**

- **Job Reference Number**:
    - **Rule**: Exactly 5 alphanumeric characters.
    - **PHP Code**:
        
        ```php
        if (!preg_match("/^[A-Za-z0-9]{5}$/", $_POST["jobref"])) {  
          header("Location: apply.php?error=jobref");  
          exit();  
        }  
        ```
        
- **Name Fields**:
    - **Rule**: Max 20 alphabetic characters (including hyphens for surnames).
    - **PHP Code**:
        
        ```php
        if (!preg_match("/^[A-Za-z- ]{1,20}$/", $_POST["firstname"])) {  
          die("Invalid first name.");  
        }  
        ```
        
- **Postcode-State Validation**:
    - **Rules**:
        - VIC: 3000–3999, 8000–8999
        - NSW: 1000–2599, 2619–2899
        - QLD: 4000–4999, 9000–9999
    - **PHP Code**:
        
        ```php
        $statePostcodes = [  
          'VIC' => '/^(3|8)\d{3}$/',  
          'NSW' => '/^(1|2)\d{3}$/',  
          'QLD' => '/^(4|9)\d{3}$/'  
        ];  
        if (!preg_match($statePostcodes[$_POST["state"]], $_POST["postcode"])) {  
          die("Postcode invalid for state.");  
        }  
        ```
        
- **Email Validation**:
    - **PHP Code**:
        
        ```php
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {  
          die("Invalid email format.");  
        }  
        ```
        
- **Phone Number**:
    - **Rule**: 8–12 digits, allowing spaces.
    - **PHP Code**:
        
        ```php
        $phone = preg_replace("/\s+/", "", $_POST["phone"]); // Remove spaces  
        if (!preg_match("/^\d{8,12}$/", $phone)) {  
          die("Phone number must be 8–12 digits.");  
        }  
        ```
        
- **Date of Birth**:
    - **Rule**: Age between 15–80 years.
    - **PHP Code**:
        
        ```php
        $dob = DateTime::createFromFormat('d/m/Y', $_POST["dob"]);  
        $today = new DateTime();  
        $age = $today->diff($dob)->y;  
        if ($age < 15 || $age > 80) {  
          die("Age must be 15–80 years.");  
        }  
        ```
        

---

### **6.2: Sanitization**

- **Input Cleaning**:
    
    ```php
    $jobref = htmlspecialchars(trim(stripslashes($_POST["jobref"])));  
    $firstname = htmlspecialchars(trim(stripslashes($_POST["firstname"])));  
    ```
    
    - `trim()`: Removes leading/trailing spaces.
    - `stripslashes()`: Removes backslashes (if magic quotes enabled).
    - `htmlspecialchars()`: Prevents XSS by escaping HTML characters.

---

### **6.3: Database Security**

- **Prepared Statements**:
    
    ```php
    $stmt = mysqli_prepare($conn, "  
      INSERT INTO eoi (JobReference, FirstName, LastName, ...)  
      VALUES (?, ?, ?, ...)  
    ");  
    mysqli_stmt_bind_param(  
      $stmt,  
      'sssssssssss',  
      $jobref, $firstname, $lastname, ...  
    );  
    mysqli_stmt_execute($stmt);  
    ```
    
    - Prevents SQL injection by separating data from SQL logic.

---

### **6.4: Form Security**

- **Disable Client-Side Validation**:
    
    ```html
    <form method="post" action="process_eoi.php" novalidate="novalidate">  
    ```
    
- **CSRF Protection (Enhancement)**:
    
    ```php
    session_start();  
    if (empty($_SESSION["token"]) || $_POST["token"] !== $_SESSION["token"]) {  
      die("Invalid CSRF token.");  
    }  
    ```
    

---

### **6.5: Error Handling**

- **Redirect with Errors**:
    
    ```php
    if (validation_fails) {  
      header("Location: apply.php?error=invalid_input");  
      exit();  
    }  
    ```
    
- **Preserve User Input**:
    
    - Repopulate form fields using `$_POST` values after redirect.
    
    ```php
    <input value="<?php echo htmlspecialchars($_POST['firstname'] ?? '') ?>">  
    ```
    

---

### **6.6: Session Security (Enhancements)**

- **Login Lockout**:
    
    ```php
    if ($failed_attempts >= 3) {  
      $lockout_time = 1800; // 30 minutes  
      if (time() - $last_attempt < $lockout_time) {  
        die("Account locked. Try again later.");  
      }  
    }  
    ```
    
- **Password Hashing**:
    
    ```php
    $hashed_pwd = password_hash($_POST["password"], PASSWORD_DEFAULT);  
    ```
    

---

### **6.7: Security Headers**

- **HTTP Headers**:
    
    ```php
    header("X-Content-Type-Options: nosniff");  
    header("X-Frame-Options: DENY");  
    ```
    

---
## 7. Database Design
### **7.1: EOI Table Schema**

- **Fields & Constraints**:
    
    ```sql
    CREATE TABLE eoi (  
      EOInumber INT AUTO_INCREMENT PRIMARY KEY,  
      JobReference VARCHAR(5) NOT NULL,  
      FirstName VARCHAR(20) NOT NULL,  
      LastName VARCHAR(20) NOT NULL,  
      Street VARCHAR(40) NOT NULL,  
      Suburb VARCHAR(40) NOT NULL,  
      State ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,  
      Postcode CHAR(4) NOT NULL,  
      Email VARCHAR(50) NOT NULL,  
      Phone VARCHAR(12) NOT NULL,  
      Skill1 VARCHAR(20),  
      Skill2 VARCHAR(20),  
      OtherSkills TEXT,  
      Status ENUM('New','Current','Final') DEFAULT 'New'  
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;  
    ```
    
- **Key Features**:
    - `AUTO_INCREMENT` ensures unique EOInumber generation.
    - `ENUM` restricts `State` and `Status` to valid values.
    - `DEFAULT 'New'` auto-sets initial status.

---

### **7.2: Jobs Table Schema**

- **Fields & Constraints**:
    
    ```sql
    CREATE TABLE jobs (  
      JobID INT AUTO_INCREMENT PRIMARY KEY,  
      JobReference VARCHAR(5) UNIQUE NOT NULL,  
      Title VARCHAR(50) NOT NULL,  
      Description TEXT NOT NULL,  
      Requirements TEXT NOT NULL,  
      ClosingDate DATE NOT NULL  
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;  
    ```
    
- **Dynamic Content**:
    - PHP renders job listings by querying this table:
        
        ```php
        $result = mysqli_query($conn, "SELECT * FROM jobs");  
        while ($row = mysqli_fetch_assoc($result)) {  
          echo "<h2>{$row['Title']}</h2>";  
          echo "<p>{$row['Description']}</p>";  
        }  
        ```
        

---

### **7.3: Normalization**

- **3NF Compliance**:
    - **EOI Table**: No redundant data (e.g., skills stored as atomic values).
    - **Jobs Table**: Separates job metadata from EOIs to avoid duplication.
- **Enhancement (Skills Table)**:
    
    ```sql
    CREATE TABLE skills (  
      SkillID INT AUTO_INCREMENT PRIMARY KEY,  
      SkillName VARCHAR(20) UNIQUE NOT NULL  
    );  
    CREATE TABLE eoi_skills (  
      EOInumber INT,  
      SkillID INT,  
      FOREIGN KEY (EOInumber) REFERENCES eoi(EOInumber),  
      FOREIGN KEY (SkillID) REFERENCES skills(SkillID)  
    );  
    ```
    

---

### **7.4: Indexing & Performance**

- **EOI Table Indexes**:
    
    ```sql
    CREATE INDEX idx_jobref ON eoi (JobReference); -- Speeds up HR queries  
    CREATE INDEX idx_status ON eoi (Status);  
    ```
    
- **Jobs Table Indexes**:
    
    ```sql
    CREATE INDEX idx_closingdate ON jobs (ClosingDate); -- For job expiration checks  
    ```
    

---

### **7.5: Foreign Keys (Enhancement)**

- **Referential Integrity**:
    
    ```sql
    ALTER TABLE eoi  
    ADD CONSTRAINT fk_jobref  
    FOREIGN KEY (JobReference) REFERENCES jobs(JobReference);  
    ```
    

---

### **7.6: SQL Scripts & Data Population**

- **Table Creation**:
    - Save `eoi.sql` and `jobs.sql` in the submission for marker use.
- **Sample Data**:
    
    ```sql
    INSERT INTO jobs (JobReference, Title, Description, ClosingDate)  
    VALUES ('SW123', 'Web Developer', 'PHP/MySQL experience required.', '2024-10-31');  
    ```
    

---

### **7.7: Backup & Recovery**

- **Export Script**:
    
    ```bash
    mysqldump -u username -p database_name > backup.sql  
    ```
    
    - Include `backup.sql` in the submission ZIP.

---

### **7.8: Security**

- **Database User Privileges**:
    
    - Grant minimal permissions (e.g., `SELECT, INSERT, UPDATE, DELETE`).
    
    ```sql
    CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'password';  
    GRANT SELECT, INSERT ON database_name.eoi TO 'app_user'@'localhost';  
    ```
    

---

### **7.9: Validation at Database Level**

- **Postcode-State Check**:
    
    ```sql
    ALTER TABLE eoi  
    ADD CONSTRAINT chk_postcode_state  
    CHECK (  
      (State = 'VIC' AND Postcode REGEXP '^(3|8)') OR  
      (State = 'NSW' AND Postcode REGEXP '^(1|2)') OR  
      ...  
    );  
    ```
    
    _Note: MySQL ignores CHECK constraints by default; use triggers or enforce via PHP._

---

### **7.10: Example Queries**

- **HR Manager Use-Cases**:
    - **Delete EOIs by Job Reference**:
        
        ```sql
        DELETE FROM eoi WHERE JobReference = 'SW123';  
        ```
        
    - **Update Status**:
        
        ```sql
        UPDATE eoi SET Status = 'Current' WHERE EOInumber = 101;  
        ```
        

---

### **7.11: Troubleshooting**

- **Common Errors**:
    - **Connection Issues**: Verify `settings.php` credentials match Feenix-MariaDB.
    - **Data Truncation**: Ensure input lengths match column definitions (e.g., 20-char limit for `FirstName`).
    - **SQL Syntax**: Test queries in phpMyAdmin before embedding in PHP.

---
## 8. Details for Enhancements
### **8.1: Sorting Functionality**

- **UI Implementation**:
    
    ```html
    <!-- Add to manage.php -->  
    <form method="get" action="manage.php">  
      <label>Sort by:  
        <select name="sort">  
          <option value="EOInumber">EOI Number</option>  
          <option value="LastName">Last Name</option>  
          <option value="Status">Status</option>  
        </select>  
      </label>  
      <input type="submit" value="Sort">  
    </form>  
    ```
    
- **PHP Logic**:
    
    ```php
    $validSortFields = ["EOInumber", "LastName", "Status"];  
    $sort = in_array($_GET["sort"], $validSortFields) ? $_GET["sort"] : "EOInumber";  
    $query = "SELECT * FROM eoi ORDER BY $sort";  
    ```
    
- **Security**: Use a whitelist to prevent SQL injection via `$_GET["sort"]`.

---

### **8.2: Manager Login System**

- **Managers Table Schema**:
    
    ```sql
    CREATE TABLE managers (  
      ManagerID INT AUTO_INCREMENT PRIMARY KEY,  
      Username VARCHAR(20) UNIQUE NOT NULL,  
      Password VARCHAR(255) NOT NULL,  
      LoginAttempts INT DEFAULT 0,  
      LockoutTime DATETIME  
    );  
    ```
    
- **Registration Page (`register.php`)**:
    - **Validation**:
        - Unique username:
            
            ```php
            $stmt = mysqli_prepare($conn, "SELECT * FROM managers WHERE Username = ?");  
            mysqli_stmt_bind_param($stmt, "s", $_POST["username"]);  
            mysqli_stmt_execute($stmt);  
            if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0) {  
              die("Username already exists.");  
            }  
            ```
            
        - Password rules (8+ chars, 1 number, 1 special character).
    - **Password Hashing**:
        
        ```php
        $hashed_pwd = password_hash($_POST["password"], PASSWORD_DEFAULT);  
        ```
        
- **Login Page (`login.php`)**:
    - **Lockout Logic**:
        
        ```php
        if ($manager["LoginAttempts"] >= 3 && time() < strtotime($manager["LockoutTime"])) {  
          die("Account locked. Try again after 30 minutes.");  
        }  
        ```
        
    - **Session Initialization**:
        
        ```php
        session_start();  
        $_SESSION["manager"] = $manager["Username"];  
        ```
        

---

### **8.3: Audit Logging**

- **Logs Table**:
    
    ```sql
    CREATE TABLE logs (  
      LogID INT AUTO_INCREMENT PRIMARY KEY,  
      ManagerID INT NOT NULL,  
      Action VARCHAR(50) NOT NULL,  
      Timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,  
      FOREIGN KEY (ManagerID) REFERENCES managers(ManagerID)  
    );  
    ```
    
- **Logging Actions**:
    
    ```php
    // After deleting EOIs  
    $action = "DELETE FROM eoi WHERE JobReference = 'SW123'";  
    $stmt = mysqli_prepare($conn, "INSERT INTO logs (ManagerID, Action) VALUES (?, ?)");  
    mysqli_stmt_bind_param($stmt, "is", $_SESSION["ManagerID"], $action);  
    ```
    

---

### **8.4: Email Notifications**

- **Status Change Alert**:
    
    ```php
    // After updating EOI status  
    $to = $applicant_email;  
    $subject = "Your Application Status Update";  
    $message = "Status changed to: " . $_POST["new_status"];  
    mail($to, $subject, $message);  
    ```
    

---

### **8.5: CSV Export**

- **PHP Export Script**:
    
    ```php
    header('Content-Type: text/csv');  
    header('Content-Disposition: attachment; filename="eoi.csv"');  
    $result = mysqli_query($conn, "SELECT * FROM eoi");  
    $out = fopen('php://output', 'w');  
    while ($row = mysqli_fetch_assoc($result)) {  
      fputcsv($out, $row);  
    }  
    fclose($out);  
    ```
    

---

### **8.6: Responsive Design**

- **Media Queries**:
    
    ```css
    @media (max-width: 768px) {  
      .menu { flex-direction: column; }  
      input[type="text"] { width: 100%; }  
    }  
    ```
    

---

### **8.7: Rate Limiting**

- **Prevent Brute Force Attacks**:
    
    ```php
    if ($login_failed) {  
      mysqli_query($conn, "  
        UPDATE managers  
        SET LoginAttempts = LoginAttempts + 1,  
            LockoutTime = IF(LoginAttempts + 1 >= 3, NOW() + INTERVAL 30 MINUTE, LockoutTime)  
        WHERE Username = '$username'  
      ");  
    }  
    ```
    

---

### **8.8: Documentation (`phpenhancements.php`)**

- **Enhancement Descriptions**:
    - **Sorting**: "Allows HR to sort EOIs by EOI number, name, or status."
    - **Login System**: "Secures `manage.php` with password hashing and lockout after 3 attempts."
    - **CSV Export**: "Exports EOI data to CSV for external analysis."

---

### **8.9: Security Best Practices**

- **Session Timeout**:
    
    ```php
    ini_set('session.gc_maxlifetime', 1800); // 30 minutes  
    session_set_cookie_params(1800);  
    ```
    
- **HTTPS Enforcement (if available)**:
    
    ```php
    if ($_SERVER["HTTPS"] != "on") {  
      header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);  
      exit();  
    }  
    ```
    

