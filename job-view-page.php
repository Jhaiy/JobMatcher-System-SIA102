<?php
    session_start();
    require_once 'db-config.php';
    include("functions/applicant-login-check.php");
    include_once("functions/password-hash.php");
    include("functions/home-page-categories.php");

    $user_data = isset($_SESSION['ApplicantID']) ? check_login($link) : null;
    $applicant_id = isset($_SESSION['ApplicantID']) ? $_SESSION['ApplicantID'] : null;
    $applicant_picture = fetch_profile_picture($link, $applicant_id);

    if (isset($_GET['job_id'])) {
        $job_id = intval($_GET['job_id']);
        $fetch_job_details = "
                SELECT joblistings.JobTitle, joblistings.JobType, joblistings.JobDescription, company.CompanyName, 
                companydetails.CompanyLogo, jobcategories.CategoryDescription, jobroles.RoleDescription,
                joblistings.JobBlockLot, joblistings.JobStreet, joblistings.JobBarangay, joblistings.JobCity, joblistings.JobProvince,
                joblistings.SalaryRange, joblistings.JobType, joblistings.ExpiryDate, joblistings.PostDate,
                joblistings.EducationAttainment, joblistings.WorkExperience, joblistings.ProgrammingLanguage, joblistings.AdditionalRequirements,
                company.CompanyEmail, company.CompanyContact, company.CompanyAccountStatus,
                company.CompanyBlockLot, company.CompanyStreet, company.CompanyBarangay, company.CompanyCity, company.CompanyProvince, companydetails.CompanyDescription
                FROM joblistings
                INNER JOIN company ON joblistings.CompanyID = company.CompanyID
                INNER JOIN companydetails ON company.CompanyDetailsID = companydetails.CompanyDetailsID
                INNER JOIN jobcategories ON joblistings.JobCategoryID = jobcategories.JobCategoryID
                INNER JOIN jobroles ON joblistings.JobRoleID = jobroles.JobRoleID
                WHERE joblistings.JobListingID = ?
            ";
            $stmt = mysqli_prepare($link, $fetch_job_details);
            mysqli_stmt_bind_param($stmt, "i", $job_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $job_details = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
    }

    $encrypted_contact = 'aFcyZ3JpNkFrYkVrWnBa'; // Replace with the actual value from the database
    $decrypted_contact = decryption($encrypted_contact);

    if ($decrypted_contact === false) {
        echo 'Decryption failed';
    } else {
        echo 'Decrypted Contact: ' . $decrypted_contact;
    }

    $encrypted = 'c3hvaDlaWVk2RnVkZVRSeUs3bFczdz09';
    $encrypted2 = 'QWFHWkxvTjJrb004b2oyeVBlSHlQUT09';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home-style.css">
    <link rel="stylesheet" href="job-view-page.css">
    <title>Document</title>
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
    <div class="company-header-container">
        <div class="company-header">
            <div class="company-logo-wrapper">
                <img src="assets/profile-uploads/<?php echo htmlspecialchars($job_details['CompanyLogo']) ?>" alt="Company Logo" id="company-logo">
            </div>
            <div class="company-header-text">
                <div class="company-header-subtext">
                    <h1><?php echo htmlspecialchars($job_details['JobTitle']) ?></h1>
                    <div class="subtext-group">
                        <img src="assets/images/building.png" id="header-icon">
                        <p><?php echo htmlspecialchars(decryption($job_details['CompanyName'])) ?></p>
                    </div>
                    <div class="subtext-group">
                        <img src="assets/images/map.png" id="header-icon">
                        <p>
                            <?php 
                                echo htmlspecialchars(
                                $job_details['JobBlockLot'] . ', ' .
                                    $job_details['JobBarangay'] . ', ' .
                                    $job_details['JobStreet'] . ', ' .
                                    $job_details['JobCity'] . ', ' .
                                    $job_details['JobProvince']
                                );
                            ?>
                        </p>
                    </div>
                    <button id="submit-application">Submit Application</button>
                </div>
                <div class="company-header-subtext">
                    <br>
                    <p>Closing: <?php echo htmlspecialchars($job_details['ExpiryDate']) ?></p>
                    <p>Salary Range: <?php echo htmlspecialchars($job_details['SalaryRange']) ?></p>
                    <p>Published: <?php echo htmlspecialchars($job_details['PostDate']) ?></p>
                    <p>Job Type: <?php echo htmlspecialchars($job_details['JobType']) ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="job-information-container">
        <div class="job-information">
            <div class="job-information-blocks">
                <div class="job-information-block">
                    <h1>Description</h1>
                    <p><?php echo htmlspecialchars($job_details['JobDescription']); ?></p>
                </div>
                <div class="job-information-block">
                    <h1>Requirements</h1>
                    <p>Education: <?php htmlspecialchars($job_details['EducationAttainment']); ?></p>
                    <p>Work Experience: <?php htmlspecialchars($job_details['WorkExperience']); ?></p>
                    <p>Programming Language: <?php htmlspecialchars($job_details['ProgrammingLanguage']); ?></p>
                </div>
                <div class="job-information-block">
                    <h1>About the Job</h1>
                    <h3>Category:</h3>
                    <p><?php echo htmlspecialchars($job_details['CategoryDescription']); ?></p>
                    <h3>Role:</h3>
                    <p><?php echo htmlspecialchars($job_details['RoleDescription']); ?></p>
                </div>
                <div class="job-information-block">
                    <h1>Additional Requirements</h1>
                    <p><?php echo htmlspecialchars($job_details['AdditionalRequirements']); ?></p>
                </div>
            </div>
        </div>
        <div class="company-information">
            <div class="company-information-block">
                <h1>Company Information</h1>
                <div class="company-information-section">
                    <div class="subtext-group">
                        <h2><?php echo htmlspecialchars(decryption($job_details['CompanyName'])); ?></h2>
                        <p>Statuts: <?php echo htmlspecialchars($job_details['CompanyAccountStatus']); ?></p>
                    </div>
                    <p><?php echo htmlspecialchars(decryption($job_details['CompanyEmail'])); ?></p>
                    <p><?php echo htmlspecialchars(decryption($job_details['CompanyContact'])); ?></p>
                    <p>
                        <?php 
                            echo htmlspecialchars(
                                decryption($job_details['CompanyBlockLot']) . ', ' .
                                decryption($job_details['CompanyBarangay']) . ', ' .
                                decryption($job_details['CompanyStreet']) . ', ' .
                                decryption($job_details['CompanyCity']) . ', ' .
                                decryption($job_details['CompanyProvince'])
                            );
                        ?>
                    </p>
                    <h3>Company Description:</h3>
                    <p><?php echo htmlspecialchars($job_details['CompanyDescription']); ?></p>
                </div>
            </div>
        </div>
    </div>
    login:

    <?php echo decryption($encrypted) ?>
    <?php echo decryption($encrypted2) ?>
</body>
</html>