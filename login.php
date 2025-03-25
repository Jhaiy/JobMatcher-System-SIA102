<?php
    session_start();
    require_once "db-config.php";
    include("functions/applicant-login-check.php");

    if (isset($_SESSION['applicant_id'])) {
        header("Location: applicant-logged-in-page.php");
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $user_email = $_POST['email'];
        $user_password = $_POST['password'];
        $login_as = $_POST['login-as'];
        if (!empty($user_email) && !empty($user_password)) {
            if ($login_as == 'applicant') {
                $sql_query = "SELECT * FROM applicant_db WHERE applicant_email = '$user_email'";
                $result = mysqli_query($link, $sql_query);
                if ($result) {
                    if($result && mysqli_num_rows($result) > 0) {
                        $user_data = mysqli_fetch_assoc($result);
                        if($user_data['applicant_password'] === $user_password) {
                            $_SESSION['applicant_id'] = $user_data['applicant_id'];
                            header("Location: applicant-logged-in-page.php");
                            exit;
                        } else {
                            echo "Invalid email or password";
                        }
                    } else {
                        echo "Invalid email or password";
                    }
                } else {
                    echo "Error: " . mysqli_error($link); 
                }
            } else {
                echo("Employer login is not yet implemented.");
            }
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
</head>
<body>
    <script src="javascript/page-scripts.js"></script>
    <div class="container">
        <form action="login.php" method="post">
            <div class="login-container">
                <h1>LOG IN</h1>
                <div class="credentials">
                    <div class="login-selection">
                        <label for="login-as">LOG IN AS:</label>
                        <select name="login-as" id="login-as">
                            <option name = "applicant" value="applicant">Applicant</option>
                            <option name = "employer" value="employer">Employer</option>
                        </select>
                    </div>
                    <div class="email-container">
                        <img id="email-icon" src="assets/images/profile.png"> 
                        <input type="text" name="email" id="email-input" class="form-control" placeholder="Email">
                    </div>
                    <div class="password-container">
                        <img id="password-icon" src="assets/images/padlock.png">
                        <input type="text" name="password" id="password-input" class="form-control" placeholder="Password">
                    </div>
                </div>
                <div class="login-actions">
                    <div class="password-forgot">
                        <a href="#">Forgot Password?</a>
                    </div>
                </div>
                <div class="login-button">
                    <button type="submit" name="login-button" class="btn-login">Log In</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>