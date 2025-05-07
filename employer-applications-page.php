
<?php
session_start();
require_once "db-config.php";
include("functions/company-login-check.php");
include("functions/password-hash.php");

$user_data = isset($_SESSION['CompanyID']) ? check_login_company($link) : null;

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

// Accept or reject applicant logic
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['accept_applicant'])) {
        $application_id = intval($_POST['application_id']);
        $update_query = "UPDATE applications SET ApplicationStatus = 'Accepted' WHERE ApplicationID = ?";
        if ($stmt = mysqli_prepare($link, $update_query)) {
            mysqli_stmt_bind_param($stmt, "i", $application_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    if (isset($_POST['reject_applicant'])) {
        $application_id = intval($_POST['application_id']);
        $delete_query = "DELETE FROM applications WHERE ApplicationID = ?";
        if ($stmt = mysqli_prepare($link, $delete_query)) {
            mysqli_stmt_bind_param($stmt, "i", $application_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        header("Location: employer-applications-page.php");
        exit();
    }
}
if (isset($_POST['delete_hired_applicant'])) {
    $application_id = intval($_POST['application_id']);
    $delete_query = "DELETE FROM applications WHERE ApplicationID = ?";
    if ($stmt = mysqli_prepare($link, $delete_query)) {
        mysqli_stmt_bind_param($stmt, "i", $application_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: employer-applications-page.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home-style.css">
    <link rel="stylesheet" href="employer-dashboard-style.css">
    <link rel="stylesheet" href="employer-joblisting-style.css">
    <link rel="stylesheet" href="employer-applications-style.css">
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
                    <p><?php echo htmlspecialchars($user_data['CompanyName']); ?></p>
                <?php endif; ?>
            </div>
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
                <img id="company-icon-banner" src="assets/images/employer.png" alt="Employer Icon">
            </div>
        </div>
    </div>   

    <div class="applicants-section">
        <h2>Applicants</h2>
        <table class="applicants-table">
            <tr>
                <th>Name</th>
            </tr>
            <?php
            $company_id = $_SESSION['CompanyID'];
            $query = "SELECT a.ApplicationID, ap.ApplicantFName, pr.ApplicantPic, ap.ApplicantLName, a.ApplicationStatus
                      FROM applications a
                      INNER JOIN applicants ap ON a.ApplicantID = ap.ApplicantID
                      LEFT JOIN applicantprofiles pr ON ap.ApplicantProfileID = pr.ApplicantProfileID
                      INNER JOIN joblistings j ON a.JobListingID = j.JobListingID
                      WHERE j.CompanyID = ?";

            if ($stmt = mysqli_prepare($link, $query)) {
                mysqli_stmt_bind_param($stmt, "i", $company_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $application_id, $fname, $pic, $lname, $status);

                while (mysqli_stmt_fetch($stmt)) {
                    $profileSrc = (!empty($pic) && file_exists("assets/profile-uploads/" . $pic)) 
                    ? "assets/profile-uploads/" . htmlspecialchars($pic) 
                    : "assets/images/default-profile.png";                                    
                    $fullName = htmlspecialchars(decryption($fname)) . ' ' . htmlspecialchars(decryption($lname));
                    
                    echo '<tr>
                            <td>
                                <div class="applicant-row">
                                    <img src="' . $profileSrc . '" alt="Profile" class="profile-pic">
                                    <span>' . $fullName . '</span>
                                    <div class="applicant-actions">';
                    
                                    if ($status === 'Accepted') {
                                        echo '<form method="post" action="" style="display:inline-block;">
                                                <input type="hidden" name="application_id" value="' . $application_id . '">
                                                <button class="accept" disabled>Hired</button>
                                                <input type="submit" name="delete_hired_applicant" value="Delete" class="decline">
                                              </form>';
                                    } else {
                                        echo '<form method="post" action="" style="display:inline-block;">
                                                <input type="hidden" name="application_id" value="' . $application_id . '">
                                                <input type="submit" name="accept_applicant" value="Accept" class="accept">
                                              </form>
                                              <form method="post" action="" style="display:inline-block;">
                                                <input type="hidden" name="application_id" value="' . $application_id . '">
                                                <input type="submit" name="reject_applicant" value="Reject" class="decline">
                                              </form>';
                                    }
                                    

                    echo '          </div>
                                </div>
                            </td>
                          </tr>';
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "<tr><td>No applicants found.</td></tr>";
            }
            ?>
        </table>
    </div>

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
