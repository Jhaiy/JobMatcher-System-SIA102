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
    $companies = fetch_companies($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home-style.css">
    <link rel="stylesheet" href="category-style.css">
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
                        <li><a href="#">Profile</a></li>
                        <li><a href="#">Status</a></li>
                    <?php endif; ?>
                    <li><a href="about-us-page.php">About Us</a></li>
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
    <div class="search-outer">
        <h1>Navigate to success.</h1>
        <div class="search-bar">
            <img src="assets/images/search-interface-symbol.png">
            <input type="text" placeholder="Search by job, company, or skills" id="search-query">
        </div>
        <button>SEARCH</button>
    </div>
    <div class="job-selection">
        <div class="job-categories">
            <?php if ($user_data): ?>
                <h1>Recommendations</h1>   
            <?php else: ?>
                <h1>Categories</h1>
                <div class="job-recommendation-blocks">
                    <?php if (!empty($job_categories)): ?>
                        <?php foreach ($job_categories as $category): ?>
                            <a href="sign-up-choice.php">
                                <div class="job-recommendation-block">
                                    <h2 id="category-name"><?php echo htmlspecialchars($category['CategoryName']); ?></h2>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No job categories found</p>
                    <?php endif; ?>
                </div> 
            <?php endif; ?>
        </div>
        <?php if (!$user_data): ?>
            <div class="job-vacancies">
                <h1>Find Job Vacancies</h1>
                <div class="job-vacancy-categories">
                    <div class="job-vacancy-wrapper">
                    <h1>SKILLS</h1>
                        <div class="job-vacancy-blocks">
                            <?php if (!empty($job_vacancies)): ?>
                                <?php foreach ($job_vacancies as $vacancy): ?>
                                    <a href="#">
                                        <div class="job-vacancy-block">
                                            <h3 id="vacancy-name"><?php echo htmlspecialchars($vacancy['SkillName']); ?></h3>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No job vacancies found</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="job-vacancy-wrapper">
                    <h1>ROLES</h1>
                        <div class="job-vacancy-blocks">
                            <?php if (!empty($job_roles)): ?>
                                <?php foreach (array_slice($job_roles, 0, 20) as $role): ?>
                                    <a href="#">
                                        <div class="job-vacancy-block">
                                            <h3 id="vacancy-name"><?php echo htmlspecialchars($role['RoleName']); ?></h3>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No job roles found</p>
                            <?php endif; ?>
                        </div>
                    <a id="view-more-button" href="#">View More</a>
                    </div>
                    <div class="job-vacancy-wrapper">
                    <h1>COMPANIES</h1>
                        <div class="job-vacancy-blocks">
                            <?php if (!empty($companies)): ?>
                                <?php foreach ($companies as $company): ?>
                                    <a href="#">
                                        <div class="job-vacancy-block">
                                            <h3 id="vacancy-name"><?php echo htmlspecialchars(decryption($company['CompanyName'])); ?></h3>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No companies found</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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