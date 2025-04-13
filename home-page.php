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

    if (isset($_SESSION['ApplicantID'])) {
        $applicant_id = $_SESSION['ApplicantID'];
        $api_url = "http://127.0.0.1:5000/?applicant_id=" . $applicant_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch);
        } else {
            $recommendations = json_decode($response, true);
        }
    }
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
                <?php if ($user_data): ?>
                    <?php 
                        if (!empty($recommendations['Recommendations'])) {
                            foreach ($recommendations['Recommendations'] as $recommendation) {
                                $jobSkillID = isset($recommendation['JobSkillID']) ? htmlspecialchars($recommendation['JobSkillID']) : 'N/A';
                                $skillDescription = isset($recommendation['SkillDescription']) ? htmlspecialchars($recommendation['SkillDescription']) : 'N/A';
                                $skillName = isset($recommendation['SkillName']) ? htmlspecialchars($recommendation['SkillName']) : 'N/A';
                                $similarityScore = isset($recommendation['SimilarityScore']) ? number_format((float)$recommendation['SimilarityScore'], 2) : 'N/A';
                
                                echo "<p><strong>Skill Name:</strong><br> $skillName</p>";
                                echo "<p><strong>Skill Description:</strong><br> $skillDescription</p>";
                                echo "<hr>";
                            }
                        }

                        if (empty($recommendations ['Recommendations'])) {
                            echo '<div class="skill-null">';
                            echo '<button><a id="edit-profile-button" href="applicant-profile.php">Add your skills!</a></button>';
                            echo '</div>';
                        }
                    ?>
                <?php else: ?>
                    <h1>Recommendations</h1>
                    <p>Please log in to see your recommendations.</p>
                <?php endif; ?>
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
        <div class="job-categories">
            <?php if ($user_data): ?>
                <h1>Categories</h1>
                <div class="job-recommendation-card">
                <?php if (!empty($job_categories)): ?>
                    <?php foreach ($job_categories as $category): ?>
                        <div class="card-contents">
                            <h2 id="category-name"><?php echo htmlspecialchars($category['CategoryName']); ?></h2>
                            <p id="category-description"><?php echo htmlspecialchars($category['CategoryDescription']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No job categories found</p>
                <?php endif; ?>
            <?php endif; ?>
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