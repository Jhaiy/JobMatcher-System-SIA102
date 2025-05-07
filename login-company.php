<?php
session_start();
require_once "db-config.php";
include("functions/company-login-check.php");
include_once("functions/password-hash.php");

$user_data = isset($_SESSION['CompanyID']);

if (isset($_SESSION['CompanyID'])) {
    header("Location: employer-dashboard-page.php");
    exit;
}

function check_if_user_password_exists($link, $verifyEmailHash, $verifyPasswordHash, $user_data)
{
    $sql_query = "SELECT * FROM company WHERE CompanyEmail = '$verifyEmailHash'";
    $result = mysqli_query($link, $sql_query);
    $user_data = mysqli_fetch_assoc($result);

    if ($result && mysqli_num_rows($result) < 0) {
        echo mysqli_error($link);
    }

    if ($user_data['CompanyPass'] === $verifyPasswordHash) {
        $_SESSION['CompanyID'] = $user_data['CompanyID'];
        $_SESSION['CompanyName'] = $user_data['CompanyName'];
        header("Location: employer-dashboard-page.php");
        exit;
    } else {
        echo "Invalid email or password";
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verifyPasswordHash = encryption($password);
    $verifyEmailHash = encryption($email);

    if (!empty($email) && !empty($password)) {
        check_if_user_password_exists($link, $verifyEmailHash, $verifyPasswordHash, $user_data);
    } else {
        echo "Please fill in all fields";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width= , initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
    <script src="javascript/page-scripts.js"></script>
</head>

<body>
    <script src="javascript/page-scripts.js"></script>
    <div class="container">
        <form action="login-company.php" method="post">
            <div class="login-container">
                <h1>Company Sign In</h1>
                <div class="credentials">
                    <div class="email-container">
                        <img id="email-icon" src="assets/images/profile.png">
                        <input type="text" name="email" id="email-input" class="form-control" placeholder="Email">
                    </div>
                    <div class="password-container">
                        <img id="password-icon" src="assets/images/padlock.png">
                        <input type="password" name="password" id="password-input" class="form-control" placeholder="Password">
                    </div>
                </div>
                <div class="login-actions">
                    <div class="password-forgot">
                        <a href="#">Forgot Password?</a>
                    </div>
                </div>
                <div class="login-button">
                    <button type="submit" name="login-button" class="btn-login">Log In</button>
                    <a href="welcome-techsync.php" class="back-button">Back to Home</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>