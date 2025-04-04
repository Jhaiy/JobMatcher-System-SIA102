<?php
    session_start();
    require_once "db-config.php";
    include("functions/applicant-login-check.php");
    include_once("functions/password-hash.php");
    include("functions/home-page-categories.php");

    $user_data = isset($_SESSION['ApplicantID']) ? check_login($link) : null;
    $applicant_id = isset($_SESSION['ApplicantID']) ? $_SESSION['ApplicantID'] : null;
    $applicant_picture = fetch_profile_picture($link, $applicant_id);

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
                        <li><a href="#">Status</a></li>
                    <?php endif; ?>
                    <li><a href="about-us-page.php">About Us</a></li>
                </ul>
            </div>
            <div class="los">
                <?php if ($user_data && isset($user_data['ApplicantFName'])): ?>
                    <li><p id="los-name"><?php echo htmlspecialchars($user_data['ApplicantFName']); ?></p></li>
                    <?php if (!empty($applicant_picture)): ?>
                        <img id="navbar-picture" src="assets/profile-uploads/<?php echo htmlspecialchars($applicant_picture); ?>" alt="Profile Picture">
                    <?php else: ?>
                        <img id="navbar-picture" src="assets/profile-uploads/user.png" alt="Default Profile Picture">
                    <?php endif; ?>
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
                <?php
                    $applicant_id = $_SESSION['ApplicantID'];
                    $fetch_applicant_skills = "
                    SELECT skills.SkillName
                    FROM applicantskills 
                    INNER JOIN skills ON applicantskills.SkillID = skills.SkillID 
                    WHERE applicantskills.ApplicantID = ?";
                    $stmt = mysqli_prepare($link, $fetch_applicant_skills);
                    mysqli_stmt_bind_param($stmt, "i", $applicant_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $applicant_skills[] = $row['SkillName'];
                        }
                    } else {
                        echo '<button><a id="edit-profile-button" href="applicant-profile.php">Add your skills!</a></button>';
                    }

                    mysqli_stmt_close($stmt);
                ?>
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