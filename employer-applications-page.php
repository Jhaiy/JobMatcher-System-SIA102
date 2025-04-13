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
    <link rel="stylesheet" href="employer-dashboard-style.css">
    <link rel="stylesheet" href="employer-joblisting-style.css">
    <link rel="stylesheet" href="employer-applications-style.css">

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
            </div>
            <div class="banner-icon">
                <img id="company-icon-banner" src="assets/images/employer.png" alt="Employer Icon">
            </div>
        </div>
    </div>   
</div>

<!--Application -->

<div class="applicants-section">
    <h2>Applicants</h2>
    <table class="applicants-table">
        <tr>
            <th>Name</th> <!-- Keep only the Name column -->
        </tr>
        <tr>
            <td>
                <div class="applicant-row">
                <img src="assets/images/profile1.png" alt="Profile" class="profile-pic">
                    <span>jobet</span>
                    <div class="applicant-actions">
                        <button class="view-resume">View Resume</button>
                        <button class="accept">Accept</button>
                        <button class="decline">Decline</button>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="applicant-row">
                <img src="assets/images/profile2.png" alt="Profile" class="profile-pic">
                    <span>charity</span>
                    <div class="applicant-actions">
                        <button class="view-resume">View Resume</button>
                        <button class="accept">Accept</button>
                        <button class="decline">Decline</button>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>


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
