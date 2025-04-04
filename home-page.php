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
                        <li><a href="applicant-profile.php">Profile</a></li>
                        <li><a href="applicant-status.php">Status</a></li>
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
        <?php if ($user_data): ?>
            <!-- Logged-in View -->
            <div class="available-jobs-section">
                <h1>Available Jobs</h1>
                <div class="job-listings">
                    <?php $available_jobs = fetch_available_jobs($link); ?>
                    
                    <?php if (!empty($available_jobs)): ?>
                        <?php foreach ($available_jobs as $job): ?>
                            <div class="job-card">
                                <h3><?php echo htmlspecialchars($job['JobTitle']); ?></h3>
                                <p class="company-name"><?php echo htmlspecialchars($job['CompanyName']); ?></p>
                                <p class="job-location"><?php echo htmlspecialchars($job['Location']); ?></p>
                                <button class="apply-button">Apply Now</button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-jobs">No available jobs at this time</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="featured-companies-section">
                <h1>Featured Companies</h1>
                <div class="company-logos">
                    <?php if (!empty($companies)): ?>
                        <?php foreach (array_slice($companies, 0, 6) as $company): ?>
                            <div class="company-logo">
                                <?php if (!empty($company['LogoPath'])): ?>
                                    <img src="<?php echo htmlspecialchars($company['LogoPath']); ?>" 
                                         alt="<?php echo htmlspecialchars(decryption($company['CompanyName'])); ?>">
                                <?php else: ?>
                                    <div class="company-name-fallback">
                                        <?php echo htmlspecialchars(decryption($company['CompanyName'])); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-companies">No featured companies</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Non-logged-in View -->
            <div class="job-categories">
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