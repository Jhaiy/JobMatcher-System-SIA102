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

function view_listings($link)
{
    $sql = "SELECT hiredapplicants.*, applicants.ApplicantFName, applicants.ApplicantLName, joblistings.JobTitle, company.CompanyName 
    FROM hiredapplicants
    LEFT JOIN company ON hiredapplicants.CompanyID = company.CompanyID
    LEFT JOIN applicants ON hiredapplicants.ApplicantID = applicants.ApplicantID
    LEFT JOIN joblistings ON hiredapplicants.JobListingID = joblistings.JobListingID
    WHERE hiredapplicants.CompanyID = '" . $_SESSION['CompanyID'] . "'";
    $result = mysqli_query($link, $sql);
    $hired_applicants = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $hired_applicants[] = $row;
        }
    } else {
        echo "Error: " . mysqli_error($link);
    }

    return $hired_applicants;
}

$hired_personnel = view_listings($link);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>