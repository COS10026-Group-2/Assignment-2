<?php
  $pageTitle = "Job Portal";
  include "header.inc";
  include "menu.inc";
?>

  <div class="search-container">
    <h1>Find Your Dream Job Today!</h1>
    <p>Connecting Talent with Opportunity: Your Gateway to Career Success</p>
    
    <div class="search-wrapper">
      <input type="text" class="search-input" placeholder="Search for jobs...">
      <button type="submit" class="search-button">
        Search
      </button>
</div>

  </div>

                  <!--START Description 1-->
  <section class="company-section">
      <div class="img-container">
        <img src="images/office-worker-vui-ve.jpg">
      </div>

      <div class="text-container">
        <h2>Good Life Begins With A Good Company</h2>
        <p>A good life starts with a good company, where a positive work environment fosters growth, collaboration, and satisfaction.
        Being part of a supportive team enhances productivity, encourages innovation, and nurtures professional development.
        This synergy not only boosts individual well-being but also drives the company's success, creating a thriving workplace for all.</p>
        <button id="searchjob-button"><a href="">Search Job</a></button>
        <a href="" class="learn-more">Learn More</a>
      </div><!--text container div-->
  </section> <!--description 1 section-->
  
                 <!--END Description 1-->

                <!--START Description 2-->
  <section class="company-section-2">
    <div class="img-container2">
      <img src="images/high-five.png">
    </div>

    <div class="text-container2">
      <h2>Create A Better Future For Yourself</h2>
      <p>Apply now to unlock your potential, gain valuable experience, and achieve your
        professional goals. Don't wait-seize this opportunity to shape a brighter tomorrow with us.
        Your future starts here!</p>
      <button id="searchjob-button"><a href="">Search Job</a></button>
    </div>
  </section>
                <!---END Description 2-->
    <ul>
      <li><strong>Icons:</strong> RemixIcon library (https://remixicon.com/) for job tags and search icons.</li>
      <li>Group Demonstration: <a target="_blank" href="https://youtu.be/B8mWPl0qwA0">https://youtu.be/B8mWPl0qwA0</a></li>
    </ul>
                <!--Footer-->         
<?php
  include "footer.inc";
?>