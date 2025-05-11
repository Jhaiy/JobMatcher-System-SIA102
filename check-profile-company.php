<?php
session_start();
require_once "db-config.php";
include("functions/applicant-login-check.php");
include_once("functions/password-hash.php");
include("functions/home-page-categories.php");

$user_data = isset($_SESSION['ApplicantID']) ? check_login($link) : null;
$company_id = isset($_SESSION['ApplicantID']) ? $_SESSION['ApplicantID'] : null;

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    die;
}

if (isset($_GET['company_id'])) {
    $company_id = $_GET['company_id'];
    $query = "SELECT company.CompanyName, company.CompanyEmail, company.CompanyContact, company.CompanyBlockLot, 
    company.CompanyStreet, company.CompanyBarangay, company.CompanyCity, company.CompanyProvince, company.CompanyPostalCode,
    companydetails.CompanyDescription, companydetails.CompanyLogo, companydetails.CompanyBio, companydetails.CompanyAbout,
    joblistings.JobTitle, joblistings.JobDescription, companyDetails.CompanyDetailsID
    FROM company
    INNER JOIN companydetails ON company.CompanyDetailsID = companydetails.CompanyDetailsID
    INNER JOIN joblistings ON company.CompanyID = joblistings.CompanyID
    WHERE company.CompanyID = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $company_profile = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

$job_listings = fetch_distinct_job_listings($link, $company_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(decryption($company_profile['CompanyName'])); ?></title>
    <link rel="stylesheet" href="home-style.css">
    <link rel="stylesheet" href="check-profile-company.css">
    <link rel="stylesheet" href="company-job-card-listings.css">
    <script src="javascript/page-scripts-debug.js"></script>
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
    <div class="company-information-container">
        <div class="left-side">
            <div class="company-image">
                <?php if (!empty($company_profile['CompanyLogo'])): ?>
                    <img class="company-profile-pic" src="assets/profile-uploads/<?php echo htmlspecialchars($company_profile['CompanyLogo']); ?>" alt="Company Logo">
                <?php else: ?>
                    <img class="company-profile-pic" src="assets/profile-uploads/default-logo.png" alt="Default Company Logo">
                <?php endif; ?>
            </div>
            <div class="company-fun-section">
                <h2><?php echo htmlspecialchars(decryption($company_profile['CompanyName'])) ?></h2>
                <h3>Bio</h3>
                <p><?php echo htmlspecialchars($company_profile['CompanyBio']) ?></p>
            </div>
        </div>
        <div class="middle">
            <div class="company-info-section">
                <h1>Company Information</h1>
                <p><strong>Email:</strong> <?php echo htmlspecialchars(decryption($company_profile['CompanyEmail'])) ?></p>
                <p><strong>Contact:</strong> <?php echo htmlspecialchars(decryption($company_profile['CompanyContact'])) ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars(decryption($company_profile['CompanyBlockLot'])) . ', ' . htmlspecialchars(decryption($company_profile['CompanyStreet'])) . ', ' . htmlspecialchars(decryption($company_profile['CompanyBarangay'])) . ', ' . htmlspecialchars(decryption($company_profile['CompanyCity'])) . ', ' . htmlspecialchars(decryption($company_profile['CompanyProvince'])) . ', ' . htmlspecialchars(decryption($company_profile['CompanyPostalCode'])) ?></p>
            </div>
            <div class="company-about-section">
                <h1>About the Company</h1>
                <textarea id="company-about-textarea" readonly><?php echo htmlspecialchars($company_profile['CompanyAbout']) ?></textarea>
            </div>
            <div class="company-jobs-section">
                <h1>Company Job Listings</h1>
                <div class="company-job-section">
                    <div class="job-categories">
                        <?php foreach ($job_listings as $jobs): ?>
                            <form method="GET" action="job-view-page.php">
                                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($jobs['JobListingID']); ?>">
                                <button type="submit" class="job-listings-button">
                                    <div class="job-listings-container">
                                        <div class="job-listings-cards">
                                            <div class="job-listings-card">
                                                <div class="job-listings-card-header">
                                                    <div class="logo-container">
                                                        <img id="company-logo" src="assets/profile-uploads/<?php echo htmlspecialchars($jobs['CompanyLogo']); ?>" alt="Company Logo">
                                                    </div>
                                                    <h2 id="company-name"><?php echo htmlspecialchars(decryption($jobs['CompanyName'])); ?></h2>
                                                </div>
                                                <div class="job-listings-details">
                                                    <h3 id="job-title"><?php echo htmlspecialchars($jobs['JobTitle']); ?></h3>
                                                    <p id="job-description"><?php echo htmlspecialchars($jobs['JobDescription']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
</body>

</html>