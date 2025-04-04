
<?php
    session_start();
    require_once "db-config.php";
    include("functions/applicant-login-check.php");
    include("functions/password-hash.php");
    include("functions/home-page-categories.php");

    $user_data = isset($_SESSION['ApplicantID']) ? check_login($link) : null;

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        die;
    }


    $job_categories = fetch_job_categories($link);
    $job_vacancies = fetch_job_vacancies($link);
    $job_roles = fetch_job_roles($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About US</title>
    <link rel="stylesheet" href="applicant-status-style.css">
    <link rel="stylesheet" href="home-style.css">

    <script src="javascript/page-scripts.js"></script>
</head>
<body>
    <script src="javascript/page-scripts.js"></script>
    <div class="navbar">
        <div class="navbar-contents">
            <div class="navbar-links">
                <ul>
                    <li><a href="#" id="logo">TechSync</a></li>
                    <li><a href="home-page.php">Jobs</a></li>
                    <?php if ($user_data): ?>
                        <li><a href="applicant-profile.php">Profile</a></li>
                        <li><a href="applicant-status.php">Status</a></li>
                    <?php endif; ?>
                    <li><a href="applicant-about-us.php">About Us</a></li>
                </ul>
            </div>
            <div class="los">
                <?php if ($user_data && isset($user_data['ApplicantFName'])): ?>
                    <li><p id="los-name"><?php echo htmlspecialchars($user_data['ApplicantFName']); ?></p></li>
                    <form method="post" action="home-page.php">
                        <input type="submit" name="logout" value="Log Out">
                    </form>
                <?php else: ?>
                    <li><a id="los" href="login.php">Log In</a></li>
                    <li><a id="los" href="sign-up-choice.php">Sign Up</a></li>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="applicant-banner">
            <?php if ($user_data && isset($user_data['ApplicantEmail'])): ?>
                <h2 id="applicant-name">
                    <?php 
                        echo htmlspecialchars(
                        $user_data['ApplicantFName'] . ' ' .
                            decryption($user_data['ApplicantLName'])
                        );
                    ?>
                </h2>
                <h3 id="applicant-address" contenteditable="true">
                    <img id="banner-house-icon" src="assets/images/home.png">
                    <?php
                        echo htmlspecialchars(
                        decryption($user_data['ApplicantBlockLot']) . ' ' .
                            decryption($user_data['ApplicantStreet']) . ' ' .
                            decryption($user_data['ApplicantBarangay']) . ' ' .
                            decryption($user_data['ApplicantCity'])
                        )
                    ?>
                </h3>
                <h3 id="applicant-email" contenteditable="true"><img id="banner-mail-icon" src="assets/images/email.png"><?php echo htmlspecialchars(decryption($user_data['ApplicantEmail'])); ?></h3>
            <?php endif; ?>
        </div>

        <div class="status-container">
        <h1>Your Application Status</h1>
        <?php if (!empty($applications)): ?>
            <?php foreach ($applications as $app): ?>
                <div class="status-card">
                    <div class="status-info">
                        <h2><?php echo htmlspecialchars($app['CompanyName']); ?></h2>
                        <p><?php echo htmlspecialchars($app['CompanyDescription']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($app['ApplicationStatus']); ?></p>
                    </div>
                    <div class="status-buttons">
                        <button>Status</button>
                        <button>Info</button>
                        <button>Cancel</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No applications found.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>© 2025 All Rights Reserved</p>
    </footer>
</body>
</html>

