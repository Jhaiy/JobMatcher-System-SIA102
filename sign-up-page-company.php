<?php
    session_start();
    require_once "db-config.php";
    include("functions/applicant-login-check.php");
    include_once("functions/password-hash.php");
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $company_name = $_POST['company-name'];
        $company_email = $_POST['email'];
        $company_contact = $_POST['contact'];
        $company_password = $_POST['password'];
        $company_blklot = $_POST['blklot'];
        $company_street = $_POST['street'];
        $company_brgy = $_POST['brgy'];
        $company_zip = $_POST['zip'];
        $company_city = $_POST['city'];
        $company_province = $_POST['province'];
        $encryptedname = encryption($company_name);
        $encryptedemail = encryption($company_email);
        $encryptedcontact = encryption($company_contact);
        $encryptedblklot = encryption($company_blklot);
        $encryptedstreet = encryption($company_street);
        $encryptedbrgy = encryption($company_brgy);
        $encryptedzip = encryption($company_zip);
        $encryptedcity = encryption($company_city);
        $encryptedprovince = encryption($company_province);
        $encryptedPassword = encryption($company_password);


        $check_email_query = "SELECT * FROM company WHERE CompanyEmail = '$encryptedemail'";
        $email_result = mysqli_query($link, $check_email_query);
        if (mysqli_num_rows($email_result) > 0) {
            echo "Account already exists";
        } else {
            if (!empty($company_email) && !empty($company_password) && !empty($company_name) && !empty($company_contact) && !empty($company_blklot) && !empty($company_street) && !empty($company_brgy) && !empty($company_zip) && !empty($company_city) && !empty($company_province)) {
                $profile_query = "INSERT INTO companydetails () VALUES ()";
                $profile_result = mysqli_query($link, $profile_query);
                if ($profile_result) {
                    $company_id = mysqli_insert_id($link);
                    $sql_query = "INSERT INTO company (CompanyID, CompanyName, CompanyEmail, CompanyContact, CompanyPass, CompanyBlockLot, CompanyStreet, CompanyBarangay, CompanyCity, CompanyProvince, CompanyPostalCode, CompanyDetailsID) VALUES ('$company_id', '$encryptedname', '$encryptedemail', '$encryptedcontact', '$encryptedPassword', '$encryptedblklot', '$encryptedstreet', '$encryptedbrgy', '$encryptedzip', '$encryptedcity', '$encryptedprovince', '$company_id')";
                    $result = mysqli_query($link, $sql_query);

                    if ($result) {
                        $_SESSION['CompanyID'] = $company_id;
                        header("Location: employer-dashboard-page.php");
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
    <form id="signup-form" action="sign-up-page-company.php" method="post" novalidate>
        <div class="container">
            <div class="sign-up-container-company">
                <h1>SIGN UP</h1>
                <p id="sign-up-type">(Company)</p>
                <div class="sign-up-inputs">
                    <div class="input-group">
                        <input type="text" name="company-name" id="company-name" placeholder="Company Name" required>
                    </div>
                    <input type="email" name="email" id="email" placeholder="Company Email" required>
                    <input type="contact" name="contact" id="contact" placeholder="Company Contact Number" required>
                    <div class="input-group">
                        <input type="text" name="blklot" id="blklot" placeholder="Blk/Lot" required>
                        <input type="text" name="street" id="street" placeholder="Street" required>
                    </div>
                    <input type="text" name="brgy" id="brgy" placeholder="Barangay" required>
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
                        <input type="text" name="zip" id="zip" placeholder="Zip Code" required>
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
        </div>
    </form>
    <div id="popup-error" class="popup-hidden">
        <div class="popup-content">
            <p id="popup-message"></p>
            <button id="popup-close">OK</button>
        </div>
    </div>
    <script src="javascript/validation-signup-company.js"></script>
</body>
</html>