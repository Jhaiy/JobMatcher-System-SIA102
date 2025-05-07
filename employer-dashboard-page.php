<?php
session_start();
require_once "db-config.php";
include("functions/company-login-check.php");
include("functions/home-page-categories.php");
include("functions/password-hash.php");

$user_data = isset($_SESSION['CompanyID']) ? check_login_company($link) : null;
$company_id = isset($_SESSION['CompanyID']) ? $_SESSION['CompanyID'] : null;
$company_picture = fetch_company_profile_picture($link, $company_id);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login-company.php");
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
</head>

<body>
    <div class="navbar">
        <div class="navbar">
            <div class="navbar-contents">
                <div class="navbar-links">
                    <ul>
                        <li><a href="#" id="logo">TechSync</a></li>
                        <li><a href="employer-dashboard-page.php">Dashboard</a></li>
                        <li><a href="employer-joblisting-page.php">Job Listing</a></li>
                        <li><a href="employer-profile-page.php">Company Profile</a></li>
                        <li><a href="employer-applications-page.php">Applicants</a></li>
                        <?php if ($user_data): ?>
                            <form method="post" action="welcome-techsync.php">
                                <input type="submit" id="logout-button" name="logout" value="Log Out">
                            </form>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="company-name">
                    <?php if ($user_data && isset($user_data['CompanyName'])): ?>
                        <p>Welcome,<strong> <?php echo htmlspecialchars($user_data['CompanyName']); ?>!</strong></p>
                    <?php endif; ?>
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
                <button name="add-listing" id="add-listing-button"><a id="listing-link" href="employer-joblisting-page.php">Add Listing Now!</a></button>
            </div>
            <div class="company-banner-icon">
                <?php if ($company_picture): ?>
                    <img id="company-icon-banner" src="assets/profile-uploads/<?php echo htmlspecialchars($company_picture); ?>" alt="Company Icon">
                <?php else: ?>
                    <img id="company-icon-banner" src="assets/images/employer.png" alt="Employer Icon">
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Dashboard Card Section -->
    <div class="dashboard-container">
        <div class="dashboard-cards">
            <div class="dashboard-card">
                <img src="assets/images/icon1.png" alt="Applicants Icon">
                <h2></h2>
                <p>Applicants</p>
            </div>

            <div class="dashboard-card">
                <img src="assets/images/icon2.png" alt="Jobs Icon">
                <table>
                    <tr class="font-semibold">
                        <td>Job</td>
                        <td>Applicants</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>

            <div class="dashboard-card">
                <img src="assets/images/icon3.png" alt="Hired Icon">
                <h2></h2>
                <p>Hired Personnel</p>
            </div>
        </div>
    </div>

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