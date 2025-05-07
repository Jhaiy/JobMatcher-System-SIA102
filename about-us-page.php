<?php
session_start();
require_once "db-config.php";
include("functions/applicant-login-check.php");
include("functions/home-page-categories.php");

$user_data = isset($_SESSION['ApplicantID']) ? check_login($link) : null;
$applicant_id = isset($_SESSION['ApplicantID']) ? $_SESSION['ApplicantID'] : null;
$applicant_picture = fetch_profile_picture($link, $applicant_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home-style.css">
    <link rel="stylesheet" href="applicant-about-us-style.css">
    <script src="javascript/page-scripts.js"></script>
</head>

<body>
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
                    <li><a href="about-us-page.php">About Us</a></li>
                    <?php if ($user_data): ?>
                        <form method="post" action="home-page.php">
                            <input type="submit" id="logout-button" name="logout" value="Log Out">
                        </form>
                    <?php else: ?>
                        <li><a href="login.php">Log In</a></li>
                        <li><a href="sign-up-choice.php">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="los">
                <?php if ($user_data && isset($user_data['ApplicantFName'])): ?>
                    <li>
                        <p id="los-name">Welcome, <?php echo htmlspecialchars($user_data['ApplicantFName']) . ' ' . htmlspecialchars(decryption($user_data['ApplicantLName'])); ?></p>
                    </li>
                    <?php if (!empty($applicant_picture)): ?>
                        <img id="navbar-picture" src="assets/profile-uploads/<?php echo htmlspecialchars($applicant_picture); ?>" alt="Profile Picture">
                    <?php else: ?>
                        <img id="navbar-picture" src="assets/profile-uploads/user.png" alt="Default Profile Picture">
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="about-page-content">
        <div class="about-us-banner">
            <div class="about-us-motto">
                <h1>Unlock Your Future.</h1>
                <h2>Find the right job for you.</h2>
                <button id="find-a-job">Find a Job!</button>
            </div>
            <div class="banner-icon">
                <img src="assets/images/businessman.png">
            </div>
        </div>
        <div class="about-text">
            <div class="about-us-text">
                <h1>About Us</h1>
                <p>JobMatcher is a cutting-edge job recommendation and applicant tracking system designed to
                    streamline the hiring process for both job seekers and employers. By leveraging the power of
                    content-based filtering algorithms, we match candidates with jobs based on their unique skills,
                    experiences, and preferences. Our platform empowers job seekers to find their perfect fit,
                    while helping employers quickly identify qualified candidates who align with their job
                    descriptions.
                </p>
            </div>
            <div class="about-us-text">
                <h1 id="empty-h1"></h1>
                <p>
                    At JobMatcher, we believe in simplifying the hiring process. We aim to provide
                    intelligent and personalized recommendations that save time, reduce recruitment
                    costs, and create better matches between employers and talent. Our system not only
                    focuses on finding jobs for applicants but also helps employers track and manage
                    applicants efficiently, ensuring a smooth recruitment journey from start to finish.
                </p>
            </div>
            <div class="about-us-text">
                <h1>Mission</h1>
                <p>
                    Our mission is to revolutionize the recruitment industry by offering a data-driven,
                    efficient, and user-friendly platform that connects talented individuals with job
                    opportunities tailored to their skills, preferences, and career goals. We strive to
                    enhance the hiring process for employers and job seekers by using advanced
                    content-based filtering algorithms that create personalized, accurate job
                    recommendations, making the process faster and more effective.
                </p>
            </div>
            <div class="about-us-text">
                <h1>Vision</h1>
                <p>Our vision is to become the leading job matching and applicant tracking platform,
                    helping individuals find meaningful employment while enabling organizations to
                    hire the best talent efficiently. We envision a future where job seekers and
                    employers are seamlessly connected through technology, reducing the barriers to
                    hiring and creating a more accessible, inclusive, and dynamic job market.
                </p>
            </div>
        </div>
        <div class="contact-us-container">
            <h1>Contact Us</h1>
            <form method="POST" action="submit-contact.php">
                <div class="form-row name-row">
                    <input type="text" id="first-name" name="first-name" placeholder="First Name" required>
                    <input type="text" id="last-name" name="last-name" placeholder="Last Name" required>
                </div>

                <div class="form-row">
                    <input type="email" id="email" name="email" placeholder="Work Email Address" required>
                </div>

                <div class="form-row">
                    <input type="tel" id="mobile" name="mobile" placeholder="Mobile Number" required>
                </div>

                <div class="form-row">
                    <textarea id="message" name="message" rows="5" placeholder="Message" required></textarea>
                </div>

                <div class="form-row">
                    <button type="submit" name="submit">SUBMIT</button>
                </div>
            </form>
        </div>
        <footer>
            <div class="footer-content">
                <p>CONTACT US:</p>
                <P>09##########</P>
                <div class="social-links">
                    <a href="#"><img src="assets/images/mail.png"></a>
                    <a href="#"><img src="assets/images/communication.png"></a>
                </div>
                <hr>
                <div class="idk-texts">
                    <p>WebTitle 2025</p>
                    <p>|</p>
                    <p>All Rights Reserved</p>
                </div>
            </div>
        </footer>
</body>

</html>