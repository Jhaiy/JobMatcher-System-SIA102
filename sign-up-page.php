<?php
session_start();
require_once "db-config.php";
include("functions/applicant-login-check.php");
include_once("functions/password-hash.php");
include("functions/home-page-categories.php");


function create_user_account($link)
{
    $user_fname = $_POST['first-name'];
    $user_lname = $_POST['last-name'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $user_city = $_POST['city'];
    $user_barangay = $_POST['barangay'];
    $user_street = $_POST['street'];
    $user_province = $_POST['province'];
    $encryptedfname = encryption($user_fname);
    $encryptedlname = encryption($user_lname);
    $encryptedemail = encryption($user_email);
    $encryptedcity = encryption($user_city);
    $encryptedbarangay = encryption($user_barangay);
    $encryptedstreet = encryption($user_street);
    $encryptedprovince = encryption($user_province);
    $encryptedPassword = encryption($user_password);

    $create_user_profile = "INSERT applicantprofiles () VALUES ()";
    $create_user_profile_result = mysqli_query($link, $create_user_profile);
    $applicant_id = mysqli_insert_id($link);
    $sql_query = "INSERT INTO applicants (ApplicantID, ApplicantFName, ApplicantLName, ApplicantEmail, ApplicantPass, ApplicantStreet, ApplicantBarangay, ApplicantCity, ApplicantProvince, 
                    ApplicantProfileID) VALUES ('$applicant_id', '$encryptedfname', '$encryptedlname', '$encryptedemail', '$encryptedPassword', '$encryptedstreet', '$encryptedbarangay', '$encryptedcity', 
                    '$encryptedprovince', '$applicant_id')";

    $result = mysqli_query($link, $sql_query);
    if (empty($user_email) || empty($user_password) || empty($user_fname) || empty($user_lname) || empty($user_city) || empty($user_province)) {
        echo "Please input all fields";
    }

    if ($result) {
        $_SESSION['ApplicantID'] = $applicant_id;
        header("Location: home-page.php");
        exit;
    } else {
        echo mysqli_error($link);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_email = $_POST["email"];
    $encryptedemail = encryption($user_email);
    $check_email_query = "SELECT * FROM applicants WHERE ApplicantEmail = '$encryptedemail'";
    $email_result = mysqli_query($link, $check_email_query);

    if (mysqli_num_rows($email_result) > 0) {
        echo "Account already exists";
    } else {
        create_user_account($link);
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
    <meta name="viewport" content="width= , initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
    <script src="javascript/page-scripts.js"></script>
</head>

<body>
    <div class="container">
        <form action="sign-up-page.php" method="post" novalidate>
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
                            <?php foreach ($cities as $city): ?>
                                <option value="" disabled selected hidden>City</option>
                                <option value="<?php echo htmlspecialchars($city['city_name']); ?>"><?php echo htmlspecialchars($city['city_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="barangay" id="barangay" required>
                            <?php foreach ($barangay as $barangay): ?>
                                <option value="" disabled selected hidden>Barangay</option>
                                <option value="<?php echo htmlspecialchars($barangay['barangay_name']); ?>"><?php echo htmlspecialchars($barangay['barangay_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <select name="street" id="street" required>
                            <?php foreach ($street as $street): ?>
                                <option value="" disabled selected hidden>Street</option>
                                <option value="<?php echo htmlspecialchars($street['street_name']); ?>"><?php echo htmlspecialchars($street['street_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="province" id="province" placeholder="Province" required>
                    </div>
                    <hr>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password" required>
                </div>
                <div class="signup-actions">
                    <input type="checkbox" name="terms" id="terms" required>
                    <label for="terms">
                        <p>Yes, I understand the Terms and Aggreement, Privacy & Policy.</p>
                    </label>
                </div>
                <div class="sign-up-choices">
                    <a id="cancel-button" href="sign-up-choice.php">CANCEL</a>
                    <input type="submit" name="proceed" id="proceed" value="PROCEED">
                </div>
            </div>
        </form>
    </div>
    <div id="popup-error" class="popup-hidden">
        <div class="popup-content">
            <p id="popup-message"></p>
            <button id="popup-close">OK</button>
        </div>
    </div>
    <script src="javascript/validation-signup-applicant.js"></script>
</body>

</html>