<?php
  $pageTitle = "About Us";
  include "header.inc";
  include "menu.inc";
?>
    
    <main class="container">
        <p class="center-text">Explore the advanced features and design improvements implemented in our Job Portal:</p>

        <!-- START Enhancement Cards -->
        <section class="enhancement-cards">
            <article class="enhancement-card">
                <h2>Figma Design Unification</h2>
                <div class="enhancement-icon">🎨</div>
                <p>We used Figma to create a centralized design system for all HTML pages, ensuring consistent color palettes, typography, and component libraries. This improved developer collaboration and user experience.</p>
                <a href="index.html" class="view-example-btn">View Example</a>
            </article>

            <article class="enhancement-card">
                <h2>Section Shadow Differentiation</h2>
                <div class="enhancement-icon">🔍</div>
                <p>Subtle box shadows were added to content sections for better visual hierarchy and readability. This enhances the professional appearance of the website.</p>
                <a href="jobs.html" class="view-example-btn">View Example</a>
            </article>

            <article class="enhancement-card">
                <h2>CSS Animation for Navigation</h2>
                <div class="enhancement-icon">✨</div>
                <p>Smooth CSS animations were implemented for section transitions on the Jobs page, improving user engagement and navigation clarity.</p>
                <a href="jobs.html" class="view-example-btn">View Example</a>
            </article>
        </section>
        <!-- END Enhancement Cards -->

        <section class="enhancement-details">
            <h2>Detailed Implementation</h2>
            <ul>
                <li><strong>Figma Design:</strong> Centralized design system with consistent UI components</li>
                <li><strong>Visual Hierarchy:</strong> Box shadows and spacing guidelines for better content separation</li>
                <li><strong>Animations:</strong> CSS ease-in/ease-out transitions for smoother navigation</li>
            </ul>
        </section>
    </main>

<?php
  include "footer.inc";
?>