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
    $companies = fetch_companies($link);

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

    $search_results = [];
    $is_search = false;
    $has_search_results = false;

    if (isset($_GET['search_query']) && !empty(trim($_GET['search_query']))) {
        $is_search = true;
        $search_query = '%' . trim($_GET['search_query']) . '%';
        $sql = "
        SELECT jl.*, c.CompanyName, cd.CompanyLogo
        FROM joblistings jl
        INNER JOIN company c ON jl.CompanyID = c.CompanyID
        INNER JOIN companydetails cd ON c.CompanyID = cd.CompanyDetailsID
        WHERE jl.JobTitle LIKE ? 
        OR jl.JobDescription LIKE ? 
        OR c.CompanyName LIKE ?
        ";

        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $search_query, $search_query, $search_query);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $search_results[] = $row;
        }

        $has_search_results = count($search_results) > 0;
        mysqli_stmt_close($stmt);
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
    <link rel="stylesheet" href="job-recommendation-cards.css">
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
                    <?php if ($user_data): ?>
                        <form method="post" action="home-page.php">
                            <input type="submit" id="logout-button" name="logout" value="Log Out">
                        </form>
                    <?php endif; ?>
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
        <form class="search-form" method="GET" action="home-page.php">
            <div class="search-bar">
                <img src="assets/images/search-interface-symbol.png">
                <input type="text" placeholder="Search by job, company, or skills" id="search-query" name="search_query">
            </div>
            <button type="submit">SEARCH</button>
        </form>
    </div>
    <div class="job-selection">
        <?php if ($user_data): ?>
            <?php if ($is_search): ?>
                <h1 id="customhead">Search Results</h1>
            <?php else: ?>
                <h1 id="customhead">Job Feed</h1>
            <?php endif; ?>
            <div class="job-categories">
                <?php
                $applicant_id = $_SESSION['ApplicantID'];
                $applicant_skills = [];
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
                    echo '<div class="skill-null">';
                    echo '<p>You have not added any skills yet.</p>';
                    echo '<button><a id="edit-profile-button" href="applicant-profile.php">Add your skills!</a></button>';
                    echo '</div>';
                }
                mysqli_stmt_close($stmt);
                ?>
                <?php if ($is_search): ?>
                    <?php if (!empty($search_results)): ?>
                        <?php foreach ($search_results as $job): ?>
                            <form method="GET" action="job-view-page.php">
                                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['JobListingID']); ?>">
                                <button type="submit" class="job-listings-button">
                                    <div class="job-listings-container">
                                        <div class="job-listings-cards">
                                            <div class="job-listings-card">
                                                <div class="job-listings-card-header">
                                                    <div class="logo-container">
                                                        <img id="company-logo" src="assets/profile-uploads/<?php echo htmlspecialchars($job['CompanyLogo']); ?>" alt="Company Logo">
                                                    </div>
                                                    <h2 id="company-name"><?php echo htmlspecialchars(decryption($job['CompanyName'])); ?></h2>
                                                </div>
                                                <div class="job-listings-details">
                                                    <h3 id="job-title"><?php echo htmlspecialchars($job['JobTitle']); ?></h3>
                                                    <p id="job-description"><?php echo htmlspecialchars($job['JobDescription']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="skill-null">
                            <p>No results found for your search.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (!empty($recommendations['Recommendations'])): ?>
                        <?php foreach ($recommendations['Recommendations'] as $recommendation): ?>
                            <form method="GET" action="job-view-page.php">
                                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($recommendation['JobListingID']); ?>">
                                <button type="submit" class="job-listings-button">
                                    <div class="job-listings-container">
                                        <div class="job-listings-cards">
                                            <div class="job-listings-card">
                                                <div class="job-listings-card-header">
                                                    <div class="logo-container">
                                                        <img id="company-logo" src="assets/profile-uploads/<?php echo htmlspecialchars($recommendation['CompanyLogo']); ?>" alt="Company Logo">
                                                    </div>
                                                    <h2 id="company-name"><?php echo htmlspecialchars(decryption($recommendation['CompanyName'])); ?></h2>
                                                </div>
                                                <div class="job-listings-details">
                                                    <h3 id="job-title"><?php echo htmlspecialchars($recommendation['JobTitle']); ?></h3>
                                                    <p id="job-description"><?php echo htmlspecialchars($recommendation['JobDescription']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="skill-null">
                            <p>No job recommendations available at the moment.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <h1>Recommendations</h1>
            <p>Please log in to see your recommendations.</p>
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