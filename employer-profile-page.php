<?php
    session_start();
    require_once "db-config.php";
    include("functions/company-login-check.php");
    include_once("functions/password-hash.php");
    include("functions/home-page-categories.php");

    $user_data = isset($_SESSION['CompanyID']) ? check_login_company($link) : null;
    $company_id = isset($_SESSION['CompanyID']) ? $_SESSION['CompanyID'] : null;
    $company_picture = fetch_company_profile_picture($link, $company_id); 

    if (!isset($_SESSION['CompanyID'])) {
        header("Location: login-company.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        die;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save-changes'])) {
        $company_id = $_SESSION['CompanyID'];
        $company_name = encryption($_POST['company-name']);
        $company_email = encryption($_POST['email']);
        $company_contact = encryption($_POST['contact-number']);
        $company_blklot = encryption($_POST['blklot']);
        $company_street = encryption($_POST['street']);
        $company_barangay = encryption($_POST['barangay']);
        $company_city = encryption($_POST['city']);
        $company_province = encryption($_POST['province']);

        $check_email_query = "SELECT * FROM company WHERE CompanyEmail = '$company_email'";
        $email_result = mysqli_query($link, $check_email_query);
        if (mysqli_num_rows($email_result) > 0) {
            echo "Another account already exists with this email";
        } else {
            if (!empty($company_name) && !empty($company_email) && !empty($company_contact) && !empty($company_blklot) && !empty($company_street) && !empty($company_barangay) && !empty($company_city) && !empty($company_province)) {
                $update_profile = "UPDATE company SET CompanyID = '$company_id', CompanyName = '$company_name', CompanyEmail = '$company_email', CompanyContact = '$company_contact', CompanyBlockLot = '$company_blklot', CompanyStreet = '$company_street', CompanyBarangay = '$company_barangay', CompanyCity = '$company_city', CompanyProvince = '$company_province', CompanyDetailsID = '$company_id' WHERE CompanyID = '$company_id'";
                $update_profile_result = mysqli_query($link, $update_profile);
            } else {
                echo "Error: " . mysqli_error($link); 
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload-picture'])) {
        $company_id = $_SESSION['CompanyID'];
    
        if (isset($_FILES['company_profile-picture']) && $_FILES['company_profile-picture']['error'] == 0) {
            $target_dir = "assets/profile-uploads/";
            $file_name = basename($_FILES['company_profile-picture']['name']);
            $target_file = $target_dir . $file_name;
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            $allowed_types = ['jpg', 'jpeg', 'png'];
            if (!in_array($file_type, $allowed_types)) {
                echo "Only JPG, JPEG, PNG files are allowed.";
                exit;
            }
    
            if (move_uploaded_file($_FILES['company_profile-picture']['tmp_name'], $target_file)) {
                // Update the database with the file name
                $query = "UPDATE companydetails SET CompanyLogo = '$file_name' WHERE CompanyDetailsID = '$company_id'";
                if (mysqli_query($link, $query)) {
                    echo "Profile picture uploaded successfully!";
                    // Refresh the page to show the updated profile picture
                    header("Location: employer-profile-page.php");
                    exit;
                } else {
                    echo "Error updating database: " . mysqli_error($link);
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "No file uploaded or an error occurred.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home-style.css">
    <link rel="stylesheet" href="category-style.css">
    <link rel="stylesheet" href="applicant-profile-style.css">
    <link rel="stylesheet" href="company-profile-picture-style.css">
    <script src="javascript/page-scripts.js"></script>
</head>
<body>
    <form method="post" action="employer-profile-page.php">
        <div class="navbar">
            <div class="navbar-contents">
                <div class="navbar-links">
                    <ul>
                        <li><a href="#" id="logo">TechSync</a></li>
                        <li><a href="employer-dashboard-page.php">Dashboard</a></li>
                        <?php if ($user_data): ?>
                            <li><a href="#">Job Listing</a></li>
                            <li><a href="#">Company Profile</a></li>
                            <li><a href="#">Applicants</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="los">
                    <?php if ($user_data && isset($user_data['CompanyName'])): ?>
                        <li><p id="los-name"><?php echo htmlspecialchars($user_data['CompanyName']); ?></p></li>
                        <?php if (!empty($company_picture)): ?>
                            <img id="navbar-picture" src="assets/profile-uploads/<?php echo htmlspecialchars($company_picture); ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img id="navbar-picture" src="assets/profile-uploads/user.png" alt="Default Profile Picture">
                        <?php endif; ?>
                        <form method="post" action="home-page.php">
                            <input type="submit" name="logout" value="Log Out">
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="company-banner">
            <?php if ($user_data && isset($user_data['CompanyEmail'])): ?>
                <div class="company-banner-container">
                    <h2 id="company-name">
                        <?php 
                            echo htmlspecialchars(
                            $user_data['CompanyName']
                            );
                        ?>
                    </h2>
                    <h3 id="company-address" contenteditable="true">
                        <img id="company-banner-house-icon" src="assets/images/home.png">
                        <?php
                            echo htmlspecialchars(
                            decryption($user_data['CompanyBlockLot']) . ' ' .
                                    decryption($user_data['CompanyStreet']) . ' ' .
                                decryption($user_data['CompanyBarangay']) . ' ' .
                                decryption($user_data['CompanyCity'])
                            )
                        ?>
                    </h3>
                    <h3 id="company-email" contenteditable="true"><img id="company-banner-mail-icon" src="assets/images/email.png"><?php echo htmlspecialchars(decryption($user_data['CompanyEmail'])); ?></h3>
                </div>
                <div class="company-banner-container">
                    <div class="company-profile-picture">
                        <form method="post" action="employer-profile-page.php" enctype="multipart/form-data">
                            <div class="company_profile-picture-wrapper">
                                <label for="company_profile-picture">
                                    <?php if (!empty($company_picture)): ?>
                                        <img src="assets/profile-uploads/<?php echo htmlspecialchars($company_picture); ?>" alt="Profile Picture" id="company_profile-picture-preview">
                                    <?php else: ?>
                                        <img src="assets/profile-uploads/user.png" alt="Default Profile Picture" id="company_profile-picture-preview">
                                    <?php endif; ?>
                                </label>
                            </div>
                            <input type="file" name="company_profile-picture" id="company_profile-picture" accept="image/*" style="display: none;">
                            <button type="submit" id="company-update-picture-button" name="upload-picture">Change Profile Picture</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="applicant-profile">
            <div class="applicant-credentials-wrapper">
                <h1>Personal Information</h1>
                <form method="post" action="employer-profile-page.php">
                <div class="applicant-credentials">
                    <div class="applicant-personal-information">
                        <form method="post" action="applicant-profile.php">
                            <div class="applicant-profile-input">
                                <div class="applicant-input-group">
                                    <label for="first-name">Company Name<br></label>
                                    <input type="text" id="first-name" name="company-name" value="<?php echo htmlspecialchars($user_data['CompanyName']); ?>" required><br>
                                </div>
                                <div class="applicant-input-group">
                                    <label for="email">Email <br></label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars(decryption($user_data['CompanyEmail'])); ?>" required><br>
                                </div>
                                <div class="applicant-input-group">
                                    <label for="contact-number">Contact Number <br></label>
                                    <input type="text" id="contact-number" name="contact-number" value="<?php echo htmlspecialchars(decryption($user_data['CompanyContact'])); ?>" required><br>
                                </div>
                                <div class="applicant-input-group">
                                    <label for="blklot">Block/Lot <br></label>
                                    <input type="text" id="blklot" name="blklot" value="<?php echo htmlspecialchars(decryption($user_data['CompanyBlockLot'])); ?>" required><br>
                                </div>
                                <div class="applicant-input-group">
                                    <label for="street">Street <br></label>
                                    <input type="text" id="street" name="street" value="<?php echo htmlspecialchars(decryption($user_data['CompanyStreet'])); ?>" required><br>
                                </div>
                                <div class="applicant-input-group">
                                    <label for="barangay">Barangay <br></label>
                                    <input type="text" id="barangay" name="barangay" value="<?php echo htmlspecialchars(decryption($user_data['CompanyBarangay'])); ?>" required><br>
                                </div>
                                <div class="applicant-input-group">
                                    <label for="city">City <br></label>
                                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars(decryption($user_data['CompanyCity'])); ?>" required><br>
                                </div>
                                <div class="applicant-input-group">
                                    <label for="province">Province <br></label>
                                    <input type="text" id="province" name="province" value="<?php echo htmlspecialchars(decryption($user_data['CompanyProvince'])); ?>" required><br>
                                </div>
                            </div>
                            <div class="button-container">
                                <input type="submit" id="update-profile" name="save-changes" value="Save Changes">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>