<?php
    session_start();
    require_once "db-config.php";
    include("functions/applicant-login-check.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home-style.css">
</head>
<body>
    <script src="javascript/page-scripts.js"></script>
    <div class="navbar">
        <div class="navbar-contents">
            <div class="navbar-links">
                <ul>
                    <li><a href="#" id="logo">ToniFowler</a></li>
                    <li><a href="home-page.php">Jobs</a></li>
                    <li><a href="#">About Us</a></li>
                </ul>
            </div>
            <div class="los">
                <li><a id="los" href="login.php">Log In</a></li>
                <li><a id="los" href="sign-up-choice.php">Sign Up</a></li>
            </div>
        </div>
    </div>
    <div class="search-outer">
        <h1>Navigate to success.</h1>
        <div class="search-bar">
            <img src="assets/images/search-interface-symbol.png">
            <input type="text" placeholder="Search by job, company, or skills" id="search-query">
            <img src="assets/images/location-pin.png">
            <input type="checkbox" id="location-dropdown">
            <label for="location-dropdown" id="location-dropdown-label">Location</label>
            <div class="location-container"></div>
            <img src="assets/images/skill-development.png">
            <input type="checkbox" id="skills-dropdown">
            <label for="skills-dropdown">Skills</label>
            <div class="skills-container">
            <label><input type="checkbox" name="skill" value="html"> HTML</label>
                <label><input type="checkbox" name="skill" value="css"> CSS</label>
                <label><input type="checkbox" name="skill" value="javascript"> JavaScript</label>
                <label><input type="checkbox" name="skill" value="php"> PHP</label>
                <label><input type="checkbox" name="skill" value="python"> Python</label>
            </div>
        </div>
        <button>SEARCH</button>
    </div>
    <div class="job-selection">
        <div class="job-categories">
            <h1>Categories</h1>
        </div>
        <div class="job-vacancies">
            <h1>Find Job Vacancies</h1>
            <div class="job-vacancy-categories">
                <ul>
                    <li><a href="#">Skills</a></li>
                    <li><a href="#">Location</a></li>
                    <li><a href="#">Roles</a></li>
                    <li><a href="#">Company</a></li>
                </ul>
                <div class="job-vacancy-blocks">

                </div>
            </div>
        </div>
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