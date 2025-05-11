<?php
session_start();
require_once "db-config.php";
include("functions/applicant-login-check.php");
include_once("functions/password-hash.php");
include("functions/home-page-categories.php");
require_once 'vendor/autoload.php';


// Logout logic (optional) - if user logs out, we can clear the session and cookies
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    setcookie('g_state', '', time() - 3600, '/'); // Clear cookie by setting expiration to a past date
    setcookie('g_csrf_token', '', time() - 3600, '/'); // Clear CSRF token cookie
    header("Location: sign-in-page.php"); // Redirect to sign-in page or home
    exit;
}


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
    <title>Applicant - Sign Up</title>
    <script src="javascript/page-scripts.js"></script>
    <script src="javascript/validation-signup-applicant.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>


<body>
    <div class="spacer3 layer3">
        <div class="image-container">
            <img id="image-left" src="assets/images/company-logo-tempo.png">
        </div>
        <div class="container">
            <form action="sign-up-page.php" method="post" novalidate>
                <div class="sign-up-container">
                    <h1>SIGN UP</h1>
                    <p id="sign-up-type">(Applicant)</p>
                    <div class="sign-up-inputs">
                        <div id="g_id_onload"
                            data-client_id="312170454729-cj9bh2se02pbgn92e8rjtsbufv0pb9b0.apps.googleusercontent.com"
                            data-context="signup"
                            data-ux_mode="popup"
                            data-callback="handleGoogleSignIn"
                            data-auto_prompt="false"
                            data-auto_select="false"> <!-- Explicitly disable auto-select -->
                        </div>


                        <div class="g_id_signin"
                            data-type="standard"
                            data-size="large"
                            data-theme="outline"
                            data-text="signup_with"
                            data-shape="rectangular"
                            data-logo_alignment="left">
                        </div>


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
    </div>


    <script>
        function parseJwt(token) {
            var base64Url = token.split('.')[1];
            var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));


            return JSON.parse(jsonPayload);
        }


        function handleGoogleSignIn(response) {
            const userData = parseJwt(response.credential);


            // Auto-fill the form fields with Google account data
            document.getElementById('first-name').value = userData.given_name || "";
            document.getElementById('last-name').value = userData.family_name || "";
            document.getElementById('email').value = userData.email || "";


            // Optional: Send the credential to the server for backend processing
            fetch("google-signup-handler.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        credential: response.credential
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Redirect if desired
                        // window.location.href = "home-page.php";
                    } else {
                        alert("Google Sign-In failed: " + data.message);
                    }
                });
        }


        function googleSignOut() {
            // Disables Google auto sign-in
            google.accounts.id.disableAutoSelect();

            // Clear Google-related cookies to prevent auto sign-in after refresh/close
            document.cookie = "g_state=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
            document.cookie = "g_csrf_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";

            // Sign out from Google
            google.accounts.id.remove();
        }
    </script>
</body>


</html>