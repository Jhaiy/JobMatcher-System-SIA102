<?php
require_once 'db-config.php';
require_once 'functions/applicant-login-check.php';
require_once 'functions/password-hash.php';
require_once 'functions/home-page-categories.php';

$user_data = isset($_SESSION['CompanyID']) ? check_login_company($link) : null;
$company_id = isset($_SESSION['CompanyID']) ? $_SESSION['CompanyID'] : null;
$company_picture = fetch_company_profile_picture($link, $company_id);
$job_listing_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : (isset($_POST['job_id']) ? intval($_POST['job_id']) : 0);

$fetch_job_listing = "SELECT joblistings.*, jobcategories.CategoryName, jobroles.RoleName, company.CompanyName, companydetails.CompanyLogo
FROM joblistings
LEFT JOIN jobcategories ON joblistings.JobCategoryID = jobcategories.JobCategoryID
LEFT JOIN jobroles ON joblistings.JobRoleID = jobroles.JobRoleID
LEFT JOIN company ON joblistings.CompanyID = company.CompanyID
LEFT JOIN companydetails ON company.CompanyID = companydetails.CompanyDetailsID
WHERE joblistings.JobListingID = '$job_listing_id'";
$result = mysqli_query($link, $fetch_job_listing);
$job_listing = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-job-listing'])) {
    if (isset($_POST['job_id']) && is_numeric($_POST['job_id'])) {
        $job_id = (int) $_POST['job_id'];
        $job_title = mysqli_real_escape_string($link, $_POST['job-title']);
        $job_status = mysqli_real_escape_string($link, $_POST['job-status']);
        $job_category_id = isset($_POST['category-types']) ? intval($_POST['category-types']) : 0;
        $job_role_id = isset($_POST['role-types']) ? intval($_POST['role-types']) : 0;
        $salary_range = mysqli_real_escape_string($link, $_POST['salary-range']);
        $job_type = mysqli_real_escape_string($link, $_POST['job-type']);
        $expiry_date = mysqli_real_escape_string($link, $_POST['job-closing-date']);
        $job_description = mysqli_real_escape_string($link, $_POST['job-description']);
        $additional_requirements = mysqli_real_escape_string($link, $_POST['additional-requirements']);
        $job_block_lot = mysqli_real_escape_string($link, $_POST['blklot']);
        $job_street = mysqli_real_escape_string($link, $_POST['jobstreet']);
        $job_barangay = mysqli_real_escape_string($link, $_POST['jobbaranggay']);
        $job_city = mysqli_real_escape_string($link, $_POST['jobcountry']);
        $job_province = mysqli_real_escape_string($link, $_POST['jobprovince']);
        $job_zip = mysqli_real_escape_string($link, $_POST['jobzip']);

        // Validate required fields
        if (empty($job_title) || empty($job_status) || empty($salary_range) || empty($job_type) || empty($expiry_date) || empty($job_description)) {
            echo "All required fields must be filled out.";
        }

        // Validate JobCategoryID
        $category_check_query = "SELECT COUNT(*) AS count FROM jobcategories WHERE JobCategoryID = '$job_category_id'";
        $category_check_result = mysqli_query($link, $category_check_query);
        $category_check_row = mysqli_fetch_assoc($category_check_result);

        if ($category_check_row['count'] == 0) {
            echo "Invalid Job Category selected.";
        }

        // Validate JobRoleID
        $role_check_query = "SELECT COUNT(*) AS count FROM jobroles WHERE JobRoleID = '$job_role_id'";
        $role_check_result = mysqli_query($link, $role_check_query);
        $role_check_row = mysqli_fetch_assoc($role_check_result);

        if ($role_check_row['count'] == 0) {
            echo "Invalid Job Role selected.";
        }

        // Update the job listing
        $update_query = "
        UPDATE joblistings
        SET
            JobTitle = '$job_title',
            JobStatus = '$job_status',
            JobBlockLot = '$job_block_lot',
            JobStreet = '$job_street',
            JobBarangay = '$job_barangay',
            JobCity = '$job_city',
            JobProvince = '$job_province',
            JobPostalCode = '$job_zip',
            JobCategoryID = '$job_category_id',
            JobRoleID = '$job_role_id',
            SalaryRange = '$salary_range',
            JobType = '$job_type',
            ExpiryDate = '$expiry_date',
            JobDescription = '$job_description',
            AdditionalRequirements = '$additional_requirements'
        WHERE JobListingID = '$job_listing_id'";

        if (mysqli_query($link, $update_query)) {
            header("Location: employer-joblisting-page.php?update=success");
            exit;
        } else {
            error_log("Error updating job listing: " . mysqli_error($link));
            echo "An error occurred while updating the job listing. Please try again later.";
        }
    } else {
        echo "Invalid Job ID";
    }
}
$cities = fetch_city($link);
$barangay = fetch_barangay($link);
$street = fetch_street($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home-style.css">
    <link rel="stylesheet" href="edit-style.css">
    <link rel="stylesheet" href="employer-joblisting-style.css">
    <script src="javascript/page-scripts-debug.js"></script>
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
    <div class="edit-job-container">
        <h1>Edit Job Listing</h1>
        <div class="company-logo">
            <form method="post" action="joblisting-edit.php">
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_listing_id); ?>">
                <div class="job-details-container">
                    <div class="left-side">
                        <div class="company-logo-container">
                            <?php if (!empty($job_listing['CompanyLogo'])): ?>
                                <img src="assets/profile-uploads/<?php echo htmlspecialchars($job_listing['CompanyLogo']); ?>" id="company-logo" alt="Company Logo">
                            <?php else: ?>
                                <img src="assets/profile-uploads/employer.png" id="company-logo" alt="Default Company Logo">
                            <?php endif; ?>
                        </div>
                        <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_listing_id); ?>">
                        <label for="job-title">Job Title</label>
                        <input type="text" name="job-title" id="job-title" value="<?php echo htmlspecialchars($job_listing['JobTitle']); ?>" data-original="<?php echo htmlspecialchars($job_listing['JobTitle']); ?>" placeholder="Job Title" required>
                        <label for="job-status">Status</label>
                        <select name="job-status" id="job-status" data-original="<?php echo htmlspecialchars($job_listing['JobStatus']); ?>" required>
                            <option value="Open" <?php echo $job_listing['JobStatus'] == "Open" ? "selected" : ""; ?>>Open</option>
                            <option value="Closed" <?php echo $job_listing['JobStatus'] == "Closed" ? "selected" : ""; ?>>Closed</option>
                        </select>
                        <hr>
                        <div class="job-specifications">
                            <div class="specfications-group">
                                <div class="label-group">
                                    <label for="category">Category</label>
                                    <button id="show-category" onclick="showDiv('hidden-category-div', event)"><img id="add-button" src="assets/images/plus.png"></button>
                                </div>
                                <div class="selected-job-specification">
                                    <ul id="selected-category-list">
                                        <?php
                                        if (!empty($job_listing['CategoryName'])) {
                                            echo '<input type="hidden" name="category-types[]" value="' . htmlspecialchars($job_listing['JobCategoryID']) . '">';
                                            echo '<li>' . htmlspecialchars($job_listing['CategoryName']) . '</li>';
                                        } else {
                                            echo '<li>No category selected</li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="specfications-group">
                                <div class="label-group">
                                    <label for="role">Role</label>
                                    <button id="show-category" onclick="showDiv('hidden-role-div', event)"><img id="add-button" src="assets/images/plus.png"></button>
                                </div>
                                <div class="selected-job-specification">
                                    <ul id="selected-role-list">
                                        <?php
                                        if (!empty($job_listing['RoleName'])) {
                                            echo '<input type="hidden" name="role-types[]" value="' . htmlspecialchars($job_listing['JobRoleID']) . '">';
                                            echo '<li>' . htmlspecialchars($job_listing['RoleName']) . '</li>';
                                        } else {
                                            echo '<li>No role selected</li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="right-side">
                        <h1>Job Details</h1>
                        <div class="input-group">
                            <div class="input-group-item">
                                <label for="salary-range">Salary Range</label>
                                <select name="salary-range" id="salary-range" data-original="<?php echo htmlspecialchars($job_listing['SalaryRange']); ?>" required>
                                    <option value="₱10,000 - ₱15,000" <?php echo $job_listing['SalaryRange'] == "₱10,000 - ₱15,000" ? "selected" : ""; ?>>₱10,000 - ₱15,000</option>
                                    <option value="₱16,000 - ₱30,000" <?php echo $job_listing['SalaryRange'] == "₱16,000 - ₱30,000" ? "selected" : ""; ?>>₱16,000 - ₱30,000</option>
                                    <option value="₱31,000 - ₱50,000" <?php echo $job_listing['SalaryRange'] == "₱31,000 - ₱50,000" ? "selected" : ""; ?>>₱31,000 - ₱50,000</option>
                                </select>
                            </div>

                            <div class="input-group-item">
                                <label for="job-type">Job Type</label>
                                <select name="job-type" id="job-type" data-original="<?php echo htmlspecialchars($job_listing['JobType']); ?>" required>
                                    <option value="Full-time" <?php echo $job_listing['JobType'] == "Full-time" ? "selected" : ""; ?>>Full-time</option>
                                    <option value="Part-time" <?php echo $job_listing['JobType'] == "Part-time" ? "selected" : ""; ?>>Part-time</option>
                                    <option value="Contract" <?php echo $job_listing['JobType'] == "Contract" ? "selected" : ""; ?>>Contract</option>
                                </select>
                            </div>
                            <div class="input-group-item">
                                <label for="job-closing-date">Job Closing Date</label>
                                <input type="date" name="job-closing-date" id="job-closing-date" value="<?php echo htmlspecialchars($job_listing['ExpiryDate']); ?>" data-original="<?php echo htmlspecialchars($job_listing['ExpiryDate']); ?>" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-item">
                                <label for="job-closing-date">Street</label>
                                <input type="text" name="blklot" id="blklot" value="<?php echo htmlspecialchars($job_listing['JobBlockLot']); ?>" data-original="<?php echo htmlspecialchars($job_listing['JobBlockLot']); ?>" placeholder="Blk/Lot" required>
                            </div>
                            <div class="input-group-item">
                                <label for="jobstreet">Street</label>
                                <select name="jobstreet" id="country" data-original="<?php echo htmlspecialchars($job_listing['JobStreet']); ?>" required>
                                    <option value="" disabled selected hidden>Street</option>
                                    <?php foreach ($street as $st) : ?>
                                        <option value="<?php echo htmlspecialchars($st['street_name']); ?>" <?php echo $job_listing['JobStreet'] == $st['street_name'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($st['street_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="input-group-item">
                                <label for="job-closing-date">Barangay</label>
                                <select name="jobbaranggay" id="country" data-original="<?php echo htmlspecialchars($job_listing['JobBarangay']); ?>" required>
                                    <option value="" disabled selected hidden>Baranggay</option>
                                    <?php foreach ($barangay as $brgy) : ?>
                                        <option value="<?php echo htmlspecialchars($brgy['barangay_name']); ?>" <?php echo $job_listing['JobBarangay'] == $brgy['barangay_name'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($brgy['barangay_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class=input-group-item>
                                <label for="jobcountry">City</label>
                                <select name="jobcountry" id="country" data-original="<?php echo htmlspecialchars($job_listing['JobCity']); ?>" required>
                                    <option value="" disabled selected hidden>City</option>
                                    <?php foreach ($cities as $city) : ?>
                                        <option value="<?php echo htmlspecialchars($city['city_name']); ?>" <?php echo $job_listing['JobCity'] == $city['city_name'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($city['city_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="input-group-item">
                                <label for="jobprovince">Province</label>
                                <input type="text" name="jobprovince" id="blklot" value="<?php echo htmlspecialchars($job_listing['JobProvince']); ?>" data-original="<?php echo htmlspecialchars($job_listing['JobProvince']); ?>" placeholder="Province" required>
                            </div>
                            <div class="input-group-item">
                                <label for="jobzip">Zip Code</label>
                                <input type="text" name="jobzip" id="blklot" value="<?php echo htmlspecialchars($job_listing['JobPostalCode']); ?>" data-original="<?php echo htmlspecialchars($job_listing['JobPostalCode']); ?>" placeholder="Zip Code" required>
                            </div>
                        </div>
                        <label for="job-description">Job Description</label>
                        <textarea name="job-description" id="job-description" rows="4" data-original="<?php echo htmlspecialchars($job_listing['JobDescription']); ?>" required><?php echo htmlspecialchars($job_listing['JobDescription']); ?></textarea>

                        <label for="additional-requirements">Additional Requirements</label>
                        <textarea name="additional-requirements" id="additional-requirements" rows="4" data-original="<?php echo htmlspecialchars($job_listing['AdditionalRequirements']); ?>" required><?php echo htmlspecialchars($job_listing['AdditionalRequirements']); ?></textarea>
                        <div class="button-container">
                            <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_listing_id); ?>">
                            <input type="submit" value="Update Job Listing" id="update-button" name="update-job-listing">
                        </div>
                    </div>
                </div>
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
        </div>
    </div>
</body>

</html>