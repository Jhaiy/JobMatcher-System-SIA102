<?php
session_start();
require_once "db-config.php";
include("functions/company-login-check.php");
include("functions/home-page-categories.php");
include("functions/password-hash.php");

$user_data = isset($_SESSION['CompanyID']) ? check_login_company($link) : null;
$company_id = isset($_SESSION['CompanyID']) ? $_SESSION['CompanyID'] : null;
$company_picture = fetch_company_profile_picture($link, $company_id);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login-company.php");
    die;
}

if (!isset($_SESSION['CompanyID'])) {
    header("Location: login-company.php");
    exit();
}

if (isset($_GET['applicant_id'])) {
    $applicant_id = $_GET['applicant_id'];
    $query = "SELECT applicants.ApplicantFName, applicants.ApplicantLName, applicants.ApplicantEmail, applicants.ApplicantContact,
    applicants.ApplicantBday, applicants.ApplicantBlockLot, applicants.ApplicantStreet, applicants.ApplicantBarangay, applicants.ApplicantCity,
    applicants.ApplicantProvince, applicants.ApplicantProfileID, applicantprofiles.ApplicantPic, applicantprofiles.ApplicantBio, applicantprofiles.ApplicantBackground,
    applications.JobListingID, applications.ApplicationStatus, joblistings.JobTitle, joblistings.JobDescription, joblistings.CompanyID, company.CompanyName
    FROM applicants
    INNER JOIN applicantprofiles ON applicants.ApplicantID = applicantprofiles.ApplicantProfileID
    INNER JOIN applications ON applicants.ApplicantID = applications.ApplicantID
    INNER JOIN joblistings ON applications.JobListingID = joblistings.JobListingID
    INNER JOIN company ON joblistings.CompanyID = company.CompanyID
    WHERE applicants.ApplicantID = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $applicant_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $applicant_profile = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

$applicant_job = fetch_applicant_job($link, $applicant_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                    <li><a href="employer-dashboard-page.php">Dashboard</a></li>
                    <li><a href="employer-joblisting-page.php">Job Listing</a></li>
                    <li><a href="employer-profile-page.php">Company Profile</a></li>
                    <li><a href="employer-applications-page.php">Applicants</a></li>
                    <?php if ($user_data): ?>
                        <form method="post" action="welcome-techsync.php">
                            <input type="submit" id="logout-button" name="logout" value="Log Out">
                        </form>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="company-name">
                <?php if ($user_data && isset($user_data['CompanyName'])): ?>
                    <p>Welcome,<strong> <?php echo htmlspecialchars($user_data['CompanyName']); ?>!</strong></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="company-information-container">
        <div class="left-side">
            <div class="company-image">
                <?php if (!empty($applicant_profile['ApplicantPic'])): ?>
                    <img class="company-profile-pic" src="assets/profile-uploads/<?php echo htmlspecialchars($applicant_profile['ApplicantPic']); ?>" alt="Company Logo">
                <?php else: ?>
                    <img class="company-profile-pic" src="assets/images/profile.png" alt="Default Company Logo">
                <?php endif; ?>
            </div>
            <div class="company-fun-section">
                <h2><?php echo htmlspecialchars(decryption($applicant_profile['ApplicantFName'])) . ' ' . htmlspecialchars(decryption($applicant_profile['ApplicantLName'])) ?></h2>
                <h3>Bio</h3>
                <p><?php echo htmlspecialchars($applicant_profile['ApplicantBio']) ?></p>
            </div>
        </div>
        <div class="middle">
            <div class="company-info-section">
                <h1>My Information</h1>
                <p><strong>Email:</strong> <?php echo htmlspecialchars(decryption($applicant_profile['ApplicantEmail'])) ?></p>
                <p><strong>Contact:</strong> <?php echo htmlspecialchars(decryption($applicant_profile['ApplicantContact'])) ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars(decryption($applicant_profile['ApplicantBlockLot'])) . ' ' . htmlspecialchars(decryption($applicant_profile['ApplicantStreet'])) . ', ' . htmlspecialchars(decryption($applicant_profile['ApplicantBarangay'])) . ', ' . htmlspecialchars(decryption($applicant_profile['ApplicantCity'])) . ', ' . htmlspecialchars(decryption($applicant_profile['ApplicantProvince'])) ?></p>
            </div>
            <div class="company-about-section">
                <h1>About Me</h1>
                <textarea id="company-about-textarea" readonly><?php echo htmlspecialchars($applicant_profile['ApplicantBackground']) ?></textarea>
            </div>
            <div class="company-jobs-section">
                <h1>Currently Working At</h1>
                <div class="company-job-section">
                    <div class="job-categories">
                        <?php if (empty($applicant_job)): ?>
                            <p>Applicant is not hired.</p>
                        <?php else: ?>
                            <?php foreach ($applicant_job as $jobs): ?>
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
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
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