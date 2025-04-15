<?php
    session_start();
    require_once "db-config.php";
    include("functions/company-login-check.php");
    include("functions/password-hash.php");

    $user_data = isset($_SESSION['CompanyID']) ? check_login_company($link) : null;

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        die;
    }

    if (!isset($_SESSION['CompanyID'])) {
        header("Location: login-company.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home-style.css">
    <link rel="stylesheet" href="employer-joblisting-style.css">
    <link rel="stylesheet" href="employer-dashboard-style.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-contents">
            <div class="navbar-links">
                <ul>
                    <li><a href="#" id="logo">TechSync</a></li>
                    <li><a href="employer-dashboard-page.php">Dashboard</a></li>
                    <li><a href="employer-joblisting-page.php">Job Listing</a></li>
                    <li><a href="employer-applications-page.php">Applicants</a></li>
                </ul>
            </div>
            <div class="company-name">
                <?php if ($user_data && isset($user_data['CompanyName'])): ?>
                    <p><?php echo htmlspecialchars($user_data['CompanyName']); ?></p>
                    <form method="post" action="home-page.php">
                        <input type="submit" name="logout" value="Log Out">
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="job-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="hideDiv('job-modal', event)">&times;</span>
            <div class="modal-header">
                <div class="logo-placeholder"></div>
                <div class="job-meta">
                    <h1>JOB TITLE</h1>
                    <div class="details">
                        <span>address</span>
                        <span>qualification</span>
                        <span>salary</span>
                    </div>
                </div>
            </div>

            <div class="modal-body">
                <div class="tabs">
                    <span class="active-tab">Job Details</span>
                    <span class="tab">About Us</span>
                </div>
                <div class="job-details">
                    <strong>Requirements</strong>
                    <h1>

                    </h1>
                    <hr>
                    <strong>Roles</strong>
                    <h1>

                    </h1>
                    <hr>
                    <strong>Additional Requirements</strong>
                    <h1>

                    </h1>
                    <hr>
                    <strong>Information</strong>
                    <h1>
                        
                    </h1>
                    <hr>
                    <button class="submit-btn">Submit Resume</button>
                </div>
            </div>
        </div>
    </div>

    <div class="company-dashboard-banner">
        <div class="dashboard-banner">
            <div class="dashboard-company-name">
                <?php if ($user_data && isset($user_data['CompanyName'])); ?>
                <h1><?php echo htmlspecialchars($user_data['CompanyName']); ?></h1>
                <h2>Description</h2>
            </div>
            <div class="banner-icon">
                <img id="company-icon-banner" src="assets/images/employer.png" alt="Employer Icon">
            </div>
        </div>    
    </div>

      <!-- Job Listing Section -->
    <section class="job-listing">
        <div class="section-title">
            <h2>Job Listing</h2>
            <button class="add-button" onclick="showDiv('job-modal', event)">Add+</button>
        </div>

        <div class="job-card-container">
            <!-- Job Card 1 -->
            <div class="job-card">
                <div class="job-icon"></div>
                <div class="job-details">
                    <h3>Job Title</h3>
                    <p>Company</p>
                    <p>Info Info Info</p>
                    <p>Description</p>
                </div>
                <div class="job-actions">
                <button id="viewDetailsBtn">View Details</button>
                <button class="applicants">Applicants</button>
                </div>
            </div>


            <!-- Placeholder for Additional Job Listings -->
            <div class="job-card"></div>
            <div class="job-card"></div>
        </div>
    </section>


    <footer>
        <div class="footer-content">
            <p>CONTACT US:</p>
            <p>09##########</p>
            <div class="social-links">
                <a href="#"><img src="assets/images/mail.png" alt="Mail"></a>
                <a href="#"><img src="assets/images/communication.png" alt="Chat"></a>
            </div>
            <hr>
            <div class="idk-texts">
                <p>WebTitle 2025</p>
                <p>|</p>
                <p>All Rights Reserved</p>
            </div>
        </div>
    </footer>

  <!-- Modal Structure -->
  <div id="detailsModal" class="modal" style="display: none;">
  <div class="modal-content custom-modal">
    <span class="close">&times;</span>
    
    <!-- Header -->
    <div class="modal-header">
      <img src="assets/images/office-building.png">
      <div class="title-section">
        <h2 class="title">Title</h2>
        <p class="description">Description</p>
      </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
      <div class="tabs">
        <button class="tab active" data-tab="job-details-tab">Job Details</button>
        <button class="tab" data-tab="about-us-tab">About Us</button>
      </div>
    </div>

    <!-- Details Section (Visible by default) -->
    <div class="content-section" id="job-details-section">
      <p class="section-label">General Requirements</p>
      <h1></h1>
      <hr />
      <p class="section-label">Roles</p>
      <h1></h1>
      <hr />
    </div>

    <!-- About Us Section (Hidden by default) -->
    <div class="tab-content" id="about-us-tab" style="display: none;">
      <div class="content-section">
        <p class="section-label">Company Overview</p>
        <h1></h1>
        <hr />
        <p class="section-label">Sample</p>
        <h1></h1>
        <hr />
      </div>
    </div>

    <!-- Button -->
    <div class="button-container">
      <button class="edit-button">Edit Info</button>
    </div>
  </div>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="javascript/page-scripts.js"></script>
</body>
</html>
