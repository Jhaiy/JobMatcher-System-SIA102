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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home-style.css">
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


    <div class="company-dashboard-banner">
        <div class="dashboard-banner">
            <div class="dashboard-company-name">
                <?php if ($user_data && isset($user_data['CompanyName'])); ?>
                <h1><?php echo htmlspecialchars($user_data['CompanyName']); ?></h1>
                <h2>Description</h2>
                <input type="submit" value="Add Listing" name="add-listing" id="add-listing-button">
            </div>
            <div class="banner-icon">
                <img id="company-icon-banner" src="assets/images/employer.png" alt="Employer Icon">
            </div>
        </div>
    </div>  
</div>

      <!-- Job Listing Section -->
    <section class="job-listing">
        <div class="section-title">
            <h2>Job Listing</h2>
            <button class="add-button">Add+</button>
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
                    <button class="view-details">View Details</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="javascript/page-scripts.js"></script>
</body>
</html>
