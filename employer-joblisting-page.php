<?php
    session_start();
    require_once "db-config.php";
    include("functions/company-login-check.php");
    include("functions/password-hash.php");
    include("functions/home-page-categories.php");

    $user_data = isset($_SESSION['CompanyID']) ? check_login_company($link) : null;
    $company_id = isset($_SESSION['CompanyID']) ? $_SESSION['CompanyID'] : null;
    $company_picture = fetch_company_profile_picture($link, $company_id);

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

    function add_listing($link) {
        $company_id = $_SESSION['CompanyID'];
        $job_title = mysqli_real_escape_string($link, trim($_POST['job-title']));
        $job_description = mysqli_real_escape_string($link, trim($_POST['job-description']));
        $job_blocklot = mysqli_real_escape_string($link, trim($_POST['blocklot']));
        $job_baranggay = mysqli_real_escape_string($link, trim($_POST['jobbaranggay']));
        $job_street = mysqli_real_escape_string($link, trim($_POST['jobstreet']));
        $job_city = mysqli_real_escape_string($link, trim($_POST['jobcity']));
        $job_province = mysqli_real_escape_string($link, trim($_POST['jobprovince']));
        $job_postal = mysqli_real_escape_string($link, trim($_POST['jobpostal']));
        $job_category_id = mysqli_real_escape_string($link, $_POST['category-item']);
        $job_role_id = mysqli_real_escape_string($link, $_POST['role-item']);
        $job_education = mysqli_real_escape_string($link, trim($_POST['education']));
        $job_experience = mysqli_real_escape_string($link, trim($_POST['experience']));
        $job_programming_language = mysqli_real_escape_string($link, trim($_POST['programminglanguage']));
        $job_closing_date = mysqli_real_escape_string($link, trim($_POST['job-closing-date']));
        $job_salary_range = mysqli_real_escape_string($link, trim($_POST['salary-range']));
        $job_type = mysqli_real_escape_string($link, trim($_POST['job-type']));
        $job_additional_requirements = mysqli_real_escape_string($link, trim($_POST['additional-requirements']));

        $category_check_query = "SELECT COUNT(*) AS count FROM jobcategories WHERE JobCategoryID = '$job_category_id'";
        $category_check_result = mysqli_query($link, $category_check_query);
        $category_exists = mysqli_fetch_assoc($category_check_result)['count'];

        if ($category_exists == 0) {
            echo "Invalid JobCategoryID.";
            return;
        }

        // Check if JobRoleID exists
        $role_check_query = "SELECT COUNT(*) AS count FROM jobroles WHERE JobRoleID = '$job_role_id'";
        $role_check_result = mysqli_query($link, $role_check_query);
        $role_exists = mysqli_fetch_assoc($role_check_result)['count'];

        if ($role_exists == 0) {
            echo "Invalid JobRoleID.";
            return;
        }

        // Insert the job listing into the database
        $sql_query = "INSERT INTO joblistings (CompanyID, JobTitle, JobDescription, SalaryRange, JobType, ExpiryDate, JobBlockLot, JobBarangay, JobStreet, JobCity, JobProvince, JobPostalCode, JobCategoryID, JobRoleID, EducationAttainment, WorkExperience, ProgrammingLanguage, AdditionalRequirements) 
                    VALUES ('$company_id', '$job_title', '$job_description', '$job_salary_range', '$job_type', '$job_closing_date', '$job_blocklot', '$job_baranggay', '$job_street', '$job_city', '$job_province', '$job_postal', '$job_category_id', '$job_role_id', '$job_education', '$job_experience', '$job_programming_language', '$job_additional_requirements')";

        $result = mysqli_query($link, $sql_query);

        if ($result) {
            header("Location: employer-joblisting-page.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($link);
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['job-title'])) {
        add_listing($link);
    }

    function view_listings($link) {
        $sql = "SELECT * FROM joblistings WHERE CompanyID = '" . $_SESSION['CompanyID'] . "'";
        $result = mysqli_query($link, $sql);
        $job_listings = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $job_listings[] = $row;
            }
        } else {
            echo "Error: " . mysqli_error($link);
        }

        return $job_listings;
    }

    $view_listings = view_listings($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home-style.css">
    <link rel="stylesheet" href="employer-joblisting-style.css">
    <link rel="stylesheet" href="employer-dashboard-style.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-contents">
            <div class="navbar-links">
                <ul>
                    <li><a href="#" id="logo">TechSync</a></li>
                    <li><a href="employer-dashboard-page.php">Dashboard</a></li>
                    <li><a href="employer-joblisting-page.php">Job Listing</a></li>
                    <li><a href="employer-profile-page.php">Company Profile</a></li>
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

    <div id="job-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="hideDiv('job-modal')">&times;</span>
            <form method="post" action="employer-joblisting-page.php">
                <div class="modal-wrapper">
                    <div class="modal-header">
                        <div class="logo-placeholder">
                            <?php if ($company_picture): ?>
                                <img id="company-icon" src="assets/profile-uploads/<?php echo htmlspecialchars($company_picture); ?>" alt="Employer Icon">
                            <?php endif; ?>
                        </div>
                        <div class="job-meta">
                            <input type="text" name="job-title" class="job-title" placeholder="Job Title">
                        </div>
                    </div>
                    <div class="modal-body-wrapper">
                        <div class="modal-body">
                            <div class="tabs">
                                <span class="active-tab">Job Details:</span>
                            </div>
                            <div class="job-details">
                                <strong>Job Requirements</strong>
                            </div>
                            <div class="experience-group">
                                <input type="text" name="education" class="job-education" placeholder="Education (e.g. Bachelor's Degree)">
                                <input type="text" name="experience" class="job-experience" placeholder="Experience (e.g. 2 years experience)">
                                <input type="text" name="programminglanguage" placeholder="Programming Language (e.g. Java, C++)">
                            </div>
                            <div class="job-details">
                                <strong>Job Location</strong>
                            </div>
                            <div class="job-location">
                                <input type="text" name="blocklot" class="job-location" placeholder="Block/Lot (Leave blank if default address)">
                            </div>
                            <div class="job-location">
                                <select name="jobbaranggay" id="country">
                                    <option value="" disabled selected hidden>Baranggay</option>
                                    <option value="baranggay1">Baranggay 1</option>
                                    <option value="baranggay2">Baranggay 2</option>
                                    <option value="baranggay3">Baranggay 3</option>
                                </select>
                                <select name="jobstreet" id="street">
                                    <option value="" disabled selected hidden>Street</option>
                                    <option value="street1">Street 1</option>
                                    <option value="street2">Street 2</option>
                                    <option value="street3">Street 3</option>
                                </select>
                            </div>
                            <div class="job-location">
                                <select name="jobprovince" id="province">
                                    <option value="" disabled selected hidden>Province</option>
                                    <option value="province1">Province 1</option>
                                    <option value="province2">Province 2</option>
                                    <option value="province3">Province 3</option>
                                </select>
                                <select name="jobcity" id="city">
                                    <option value="" disabled selected hidden>City</option>
                                    <option value="city1">City 1</option>
                                    <option value="city2">City 2</option>
                                    <option value="city3">City 3</option>
                                </select>
                            </div>
                            <div class="job-location">
                                <input type="text" name="jobpostal" class="job-location" placeholder="Postal Code">
                            </div>
                            <hr>
                            <div class="job-details">
                                <div class="job-specification-group">
                                    <strong>Category</strong>
                                    <button id="show-category" onclick="showDiv('hidden-category-div', event)"><img id="add-button" src="assets/images/plus.png"></button>
                                </div>
                                <div class="selected-job-specification">
                                    <ul id="selected-category-list">

                                    </ul>
                                </div>
                                <hr>
                                <div class="job-specification-group">
                                    <strong>Role</strong>
                                    <button id="show-category" onclick="showDiv('hidden-role-div', event)"><img id="add-button" src="assets/images/plus.png"></button>
                                </div>
                                <div class="selected-job-specification">
                                    <ul id="selected-role-list">

                                    </ul>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="job-details">
                                <div class="specifications">
                                    <strong>Job Closing</strong>
                                    <input type="date" name="job-closing-date" class="job-closing-date" placeholder="Closing Date">
                                </div>
                                <div class="salary-range">
                                    <strong>Salary Range</strong>
                                    <select name="salary-range" id="salary-range">
                                        <option value="" disabled selected hidden>Salary Range</option>
                                        <option value="₱10,000 - ₱15,000">₱10,000 - ₱15,000</option>
                                        <option value="₱16,000 - ₱30,000">₱16,000 - ₱30,000</option>
                                        <option value="₱31,000 - ₱50,000">₱31,000 - ₱50,000</option>
                                    </select>
                                </div>
                                <div class="job-type">
                                    <strong>Job Type</strong>
                                    <select name="job-type" id="job-type">
                                        <option value="" disabled selected hidden>Job Type</option>
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>
                                        <option value="Contract">Contract</option>
                                    </select>
                                </div>
                                <strong>Job Description</strong>
                                <h1>
                                    <textarea id="job-requirements" name="job-description" rows="4" placeholder="Job Description"></textarea>
                                </h1>
                                <hr>
                                <strong>Additional Requirements</strong>
                                <h1>
                                    <textarea id="job-requirements" name="additional-requirements" rows="4" placeholder="Enter additional requirements here..."></textarea>
                                </h1>
                            </div>
                        </div>
                        <button type="submit" id="add-listing-btn" class="submit-btn">Add Listing</button>
                    </div>
                </div>
            </form>
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
                <?php if ($company_picture): ?>
                    <img id="company-icon-banner" src="assets/profile-uploads/<?php echo htmlspecialchars($company_picture); ?>" alt="Company Icon">
                <?php else: ?>
                    <img id="company-icon-banner" src="assets/images/employer.png" alt="Employer Icon">
                <?php endif; ?>
            </div>
        </div>    
    </div>
    <!-- Job Listing Section -->
    <section class="job-listing">
        <div class="section-title">
            <h2>Job Listing</h2>
            <button class="add-button" onclick="showDiv('job-modal', event)">Add+</button>
        </div>
        <div class="job-card-container">
            <!-- Job Card 1 -->
            <?php if(!empty($view_listings)): ?>
                <?php foreach ($view_listings as $listings): ?>
                    <div class="job-card">
                        <div class="job-icon">
                            <?php if ($company_picture): ?>
                                <img id="company-listing-icon" src="assets/profile-uploads/<?php echo htmlspecialchars($company_picture); ?>" alt="Employer Icon">
                            <?php else: ?>
                                <img id="company-listing-icon" src="assets/images/employer.png" alt="Employer Icon">
                            <?php endif; ?>
                        </div>
                        <div class="job-details">
                            <h3><?php echo htmlspecialchars($listings['JobTitle']) ?></h3>
                            <p><?php echo htmlspecialchars($user_data['CompanyName']) ?></p>
                            <p><?php echo htmlspecialchars($listings['JobDescription']) ?></p>
                        </div>
                        <div class="job-actions">
                        <button class="view-details" id="viewDetailsBtn">View Details</button>
                        <button class="applicants">Applicants</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-job-listings">
                    <h1>Seems quiet in here...</h1>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <form method="post" action="employer-joblisting-page.php">
        <div id="hidden-category-div" class="hidden-category-div">
            <div class="category-div-wrapper">
                <?php 
                    $job_categories = fetch_job_categories($link);
                    foreach ($job_categories as $categories) {
                        echo '<div>';
                        echo '<input type="radio" id="category-type-' . htmlspecialchars($categories['JobCategoryID']) . '" name="category-types[]" value="' . htmlspecialchars($categories['JobCategoryID']) . '">';
                        echo '<label for="category-type-' . htmlspecialchars($categories['JobCategoryID']) . '">' . htmlspecialchars($categories['CategoryName']) . '</label><br>';
                        echo '</div>';
                    }
                ?>
                <button id="toggle-category-button">Done</button>
            </div>
        </div>
    </form>
    <form method="post" action="employer-joblisting-page.php">
        <div id="hidden-role-div" class="hidden-role-div">
            <div class="role-div-wrapper">
                <?php 
                    $job_roles = fetch_job_roles($link);
                    foreach ($job_roles as $role) {
                        echo '<div>';
                        echo '<input type="radio" id="role-type-' . htmlspecialchars($role['JobRoleID']) . '" name="role-types[]" value="' . htmlspecialchars($role['JobRoleID']) . '">';
                        echo '<label for="role-type-' . htmlspecialchars($role['JobRoleID']) . '">' . htmlspecialchars($role['RoleName']) . '</label><br>';
                        echo '</div>';
                    }
                ?>
                <button id="toggle-role-button">Done</button>
            </div>
        </div>
    </form>

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
