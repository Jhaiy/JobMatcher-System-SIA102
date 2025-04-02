<?php
    session_start();
    require_once "db-config.php";
    include("functions/applicant-login-check.php");
    include("functions/password-hash.php");
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $user_fname = $_POST['first-name'];
        $user_lname = $_POST['last-name'];
        $user_email = $_POST['email'];
        $user_password = $_POST['password'];
        $user_city = $_POST['city'];
        $user_province = $_POST['province'];
        $encryptedfname = encryption($user_fname);
        $encryptedlname = encryption($user_lname);
        $encryptedemail = encryption($user_email);
        $encryptedcity = encryption($user_city);
        $encryptedprovince = encryption($user_province);
        $encryptedPassword = encryption($user_password);

        $check_email_query = "SELECT * FROM applicants WHERE ApplicantEmail = '$encryptedemail'";
        $email_result = mysqli_query($link, $check_email_query);
        if (mysqli_num_rows($email_result) > 0) {
            echo "Account already exists";
        } else {
            if (!empty($user_email) && !empty($user_password) && !empty($user_fname) && !empty($user_lname) && !empty($user_city) && !empty($user_province)) {
                $profile_query = "INSERT INTO applicantprofiles () VALUES ()";
                $profile_result = mysqli_query($link, $profile_query);
                if ($profile_result) {
                    $applicant_id = mysqli_insert_id($link);
                    $sql_query = "INSERT INTO applicants (ApplicantID, ApplicantFName, ApplicantLName, ApplicantEmail, ApplicantPass, ApplicantCity, ApplicantProvince, ApplicantProfileID) VALUES ('$applicant_id', '$encryptedfname', '$encryptedlname', '$encryptedemail', '$encryptedPassword', '$encryptedcity', '$encryptedprovince', '$applicant_id')";
                    $result = mysqli_query($link, $sql_query);

                    if ($result) {
                        $_SESSION['ApplicantID'] = $applicant_id;
                        header("Location: home-page.php");
                        exit;
                    } else {
                        echo "Error: " . mysqli_error($link);
                    }
                } else {
                    echo "Error: " . mysqli_error($link);
                }
            } else {
                echo "Error: " . mysqli_error($link); 
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
    <script src="javascript/page-scripts.js"></script>
</head>
<body>
    <form action="sign-up-page.php" method="post">
        <div class="container">
            <form action="login.php" method="post">
                <div class="sign-up-container">
                    <h1>SIGN UP</h1>
                    <p id="sign-up-type">(Applicant)</p>
                    <div class="sign-up-inputs">
                        <div class="input-group">
                            <input type="text" name="first-name" id="first-name" placeholder="First Name" required>
                            <input type="text" name="last-name" id="last-name" placeholder="Last Name" required>
                        </div>
                        <input type="email" name="email" id="email" placeholder="Email" required>
                        <div class="input-group">
                            <select name="city" id="city" required>
                                <option value="" disabled selected hidden>City</option>
                                <option value="Caloocan">Caloocan</option>
                                <option value="Makati">Makati</option>
                                <option value="Manila">Manila</option>
                                <option value="Marikina">Marikina</option>
                                <option value="Muntinlupa">Muntinlupa</option>
                                <option value="Quezon City">Quezon City</option>
                            </select>
                            <select name="province" id="province"required>
                                <option value="" disabled selected hidden>Province</option>
                                <option value="Metro Manila">Metro Manila</option>
                                <option value="Rizal">Rizal</option>
                                <option value="Cavite">Cavite</option>
                                <option value="Laguna">Laguna</option>
                                <option value="Bulacan">Bulacan</option>
                                <option value="Pampanga">Pampanga</option>
                            </select>
                        </div>
                        <hr>
                        <input type="password" name="password" id="password" placeholder="Password" required>
                        <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password" required>
                    </div>
                    <div class="signup-actions">
                        <input type="checkbox" name="terms" id="terms" required>
                        <label for="terms"><p>Yes, I understand the Terms and Aggreement, Privacy & Policy.</p></label>
                    </div>
                    <div class="sign-up-choices">
                        <a id="cancel-button" href="sign-up-choice.php">CANCEL</a>
                        <input type="submit" name="proceed" id="proceed" value="PROCEED">
                    </div>
                </div>
            </form>
        </div>
    </form>
    <script src="javascript/page-scripts.js"></script>
</body>
</html>