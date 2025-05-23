<?php
session_start();
require_once "db-config.php";
include("functions/applicant-login-check.php");
include_once("functions/password-hash.php");
include("functions/home-page-categories.php");

$user_data = isset($_SESSION['ApplicantID']) ? check_login($link) : null;
$applicant_id = isset($_SESSION['ApplicantID']) ? $_SESSION['ApplicantID'] : null;
$applicant_picture = fetch_profile_picture($link, $applicant_id);

if (!isset($_SESSION['ApplicantID'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    die;
}

if (isset($_POST['work-type'])) {
    $selected_work_types = $_POST['work-type']; // Array of selected work type IDs
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['work-types']) && is_array($_POST['work-types'])) {
        $applicant_id = $_SESSION['ApplicantID'];
        $selected_categories = $_POST['work-types'];

        foreach ($selected_categories as $category_id) {
            $category_id = intval($category_id);

            $check_query = "SELECT * FROM applicantskills WHERE ApplicantID = ? AND SkillID = ?";
            $check_stmt = mysqli_prepare($link, $check_query);
            mysqli_stmt_bind_param($check_stmt, 'ii', $applicant_id, $category_id);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);

            if (mysqli_num_rows($check_result) == 0) {

                $add_skill_to_applicant = "INSERT INTO applicantskills (ApplicantID, SkillID) VALUES (?, ?)";
                $stmt = mysqli_prepare($link, $add_skill_to_applicant);
                mysqli_stmt_bind_param($stmt, 'ii', $applicant_id, $category_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }

            mysqli_stmt_close($check_stmt);
        }
    }
}
$applicant_id = $_SESSION['ApplicantID'];
$selected_skills = "
    SELECT SkillID
    FROM applicantskills
    WHERE ApplicantID = ?";
$selected_skills_stmt = mysqli_prepare($link, $selected_skills);
mysqli_stmt_bind_param($selected_skills_stmt, "i", $applicant_id);
mysqli_stmt_execute($selected_skills_stmt);
$selected_skills_result = mysqli_stmt_get_result($selected_skills_stmt);

$selected_skill_ids = [];
while ($row = mysqli_fetch_assoc($selected_skills_result)) {
    $selected_skill_ids[] = $row['SkillID'];
}
mysqli_stmt_close($selected_skills_stmt);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save-changes'])) {
    $applicant_id = $_SESSION['ApplicantID'];
    $applicant_fname = encryption($_POST['first-name']);
    $applicant_lname = encryption($_POST['last-name']);
    $applicant_sex = encryption($_POST['sex']);
    $applicant_bday = $_POST['applicant-birthdate'];
    $applicant_email = encryption($_POST['email']);
    $applicant_contact = encryption($_POST['contact-number']);
    $applicant_blklot = encryption($_POST['blklot']);
    $applicant_street = encryption($_POST['street']);
    $applicant_barangay = encryption($_POST['barangay']);
    $applicant_city = encryption($_POST['city']);
    $applicant_province = encryption($_POST['province']);

    $applicant_email = mysqli_real_escape_string($link, $applicant_email);
    $check_email_query = "SELECT * FROM applicants WHERE ApplicantEmail = '$applicant_email' AND ApplicantID != '$applicant_id'";
    $email_result = mysqli_query($link, $check_email_query);
    if (mysqli_num_rows($email_result) > 0) {
        echo "Another account already exists with this email";
    } else {
        if (!empty($applicant_fname) && !empty($applicant_lname) && !empty($applicant_sex) && !empty($applicant_bday) && !empty($applicant_email) && !empty($applicant_contact) && !empty($applicant_blklot) && !empty($applicant_street) && !empty($applicant_barangay) && !empty($applicant_city) && !empty($applicant_province)) {
            $update_profile = "UPDATE applicants SET ApplicantID = '$applicant_id', ApplicantFName = '$applicant_fname', ApplicantLName = '$applicant_lname', ApplicantSex = '$applicant_sex', ApplicantBday = '$applicant_bday', ApplicantEmail = '$applicant_email', ApplicantContact = '$applicant_contact', ApplicantBlockLot = '$applicant_blklot', ApplicantStreet = '$applicant_street', ApplicantBarangay = '$applicant_barangay', ApplicantCity = '$applicant_city', ApplicantProvince = '$applicant_province', ApplicantProfileID = '$applicant_id' WHERE ApplicantID = '$applicant_id'";
            $update_profile_result = mysqli_query($link, $update_profile);
            if ($update_profile_result) {
                echo "Profile updated successfully!";
                header("Location: applicant-profile.php");
                exit;
            } else {
                echo "Error updating profile: " . mysqli_error($link);
            }
            exit;
        } else {
            echo "Error: " . mysqli_error($link);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove-skill'])) {
    $skill_id = intval($_POST['skill-id']);
    $applicant_id = $_SESSION['ApplicantID'];

    // Delete the skill from the database
    $delete_skill_query = "DELETE FROM applicantskills WHERE ApplicantID = ? AND SkillID = ?";
    $stmt = mysqli_prepare($link, $delete_skill_query);
    mysqli_stmt_bind_param($stmt, "ii", $applicant_id, $skill_id);

    if (mysqli_stmt_execute($stmt)) {
        // Redirect to refresh the page and update the skills list
        header("Location: applicant-profile.php");
        exit;
    } else {
        echo "Error removing skill: " . mysqli_error($link);
    }

    mysqli_stmt_close($stmt);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save-about'])) {
    $applicant_id = $_SESSION['ApplicantID'];
    $applicant_bio = $_POST['my-bio'];
    $applicant_about = $_POST['about-me'];
    $applicant_bio = mysqli_real_escape_string($link, $applicant_bio);
    $applicant_about = mysqli_real_escape_string($link, $applicant_about);
    $update_bio = "UPDATE applicantprofiles SET ApplicantBio = '$applicant_bio', ApplicantBackground = '$applicant_about' WHERE ApplicantProfileID = '$applicant_id'";
    $update_bio_result = mysqli_query($link, $update_bio);
    if ($update_bio_result) {
        header("Location: applicant-profile.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload-picture'])) {
    $applicant_id = $_SESSION['ApplicantID'];

    if (isset($_FILES['profile-picture']) && $_FILES['profile-picture']['error'] == 0) {
        $target_dir = "assets/profile-uploads/";
        $file_name = basename($_FILES['profile-picture']['name']);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png'];
        if (!in_array($file_type, $allowed_types)) {
            echo "Only JPG, JPEG, PNG files are allowed.";
            exit;
        }

        if (move_uploaded_file($_FILES['profile-picture']['tmp_name'], $target_file)) {
            $query = "UPDATE applicantprofiles SET ApplicantPic = '$file_name' WHERE ApplicantProfileID = '$applicant_id'";
            if (mysqli_query($link, $query)) {
                echo "Profile picture uploaded successfully!";
                header("Location: applicant-profile.php");
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

$bio_query = "SELECT ApplicantBio, ApplicantBackground FROM applicantprofiles WHERE ApplicantProfileID = ?";
$stmt = mysqli_prepare($link, $bio_query);
mysqli_stmt_bind_param($stmt, "i", $applicant_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$bio_data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$bio = isset($bio_data['ApplicantBio']) ? $bio_data['ApplicantBio'] : '';
$background = isset($bio_data['ApplicantBackground']) ? $bio_data['ApplicantBackground'] : '';

$cities = fetch_city($link);
$barangay = fetch_barangay($link);
$street = fetch_street($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="home-style.css">
    <link rel="stylesheet" href="category-style.css">
    <link rel="stylesheet" href="applicant-profile-style.css">
    <script src="javascript/page-scripts-default.js"></script>
</head>

<body>
    <form method="post" action="applicant-profile.php">
        <div class="navbar">
            <div class="navbar-contents">
                <div class="navbar-links">
                    <ul>
                        <li><a href="#" id="logo">TechSync</a></li>
                        <li><a href="home-page.php">Jobs</a></li>
                        <?php if ($user_data): ?>
                            <li><a href="applicant-profile.php">Profile</a></li>
                            <li><a href="applicant-status.php">Status</a></li>
                        <?php endif; ?>
                        <li><a href="about-us-page.php">About Us</a></li>
                        <?php if ($user_data): ?>
                            <form method="post" action="home-page.php">
                                <input type="submit" id="logout-button" name="logout" value="Log Out">
                            </form>
                        <?php else: ?>
                            <li><a href="login.php">Log In</a></li>
                            <li><a href="sign-up-choice.php">Sign Up</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="los">
                    <?php if ($user_data && isset($user_data['ApplicantFName'])): ?>
                        <li>
                            <p id="los-name">Welcome, <?php echo htmlspecialchars($user_data['ApplicantFName']) . ' ' . htmlspecialchars(decryption($user_data['ApplicantLName'])); ?></p>
                        </li>
                        <?php if (!empty($applicant_picture)): ?>
                            <img id="navbar-picture" src="assets/profile-uploads/<?php echo htmlspecialchars($applicant_picture); ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img id="navbar-picture" src="assets/profile-uploads/user.png" alt="Default Profile Picture">
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="applicant-banner">
            <?php if ($user_data && isset($user_data['ApplicantEmail'])): ?>
                <h2 id="applicant-name">
                    <?php
                    echo htmlspecialchars(
                        $user_data['ApplicantFName'] . ' ' .
                            decryption($user_data['ApplicantLName'])
                    );
                    ?>
                </h2>
                <h3 id="applicant-address" contenteditable="true">
                    <img id="banner-house-icon" src="assets/images/home.png">
                    <?php
                    echo htmlspecialchars(
                        decryption($user_data['ApplicantBlockLot']) . ' ' .
                            decryption($user_data['ApplicantStreet']) . ' ' .
                            decryption($user_data['ApplicantBarangay']) . ' ' .
                            decryption($user_data['ApplicantCity'])
                    )
                    ?>
                </h3>
                <h3 id="applicant-email" contenteditable="true"><img id="banner-mail-icon" src="assets/images/email.png"><?php echo htmlspecialchars(decryption($user_data['ApplicantEmail'])); ?></h3>
            <?php endif; ?>
        </div>
        <div class="applicant-profile">
            <div class="applicant-credentials-wrapper">
                <h1>Personal Information</h1>
                <form method="post" action="applicant-profile.php">
                    <div class="applicant-credentials">
                        <div class="applicant-personal-information">
                            <form method="post" action="applicant-profile.php">
                                <div class="applicant-profile-input">
                                    <div class="applicant-input-group">
                                        <label for="first-name">First Name <br></label>
                                        <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($user_data['ApplicantFName']); ?>"
                                            data-original="<?php echo htmlspecialchars($user_data['ApplicantFName']); ?>" required><br>
                                    </div>
                                    <div class="applicant-input-group">
                                        <label for="last-name">Last Name <br></label>
                                        <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars(decryption($user_data['ApplicantLName'])); ?>"
                                            data-original="<?php echo htmlspecialchars($user_data['ApplicantLName']); ?>" required><br>
                                    </div>
                                    <div class="applicant-input-group">
                                        <label for="email">Email <br></label>
                                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars(decryption($user_data['ApplicantEmail'])); ?>"
                                            data-original="<?php echo htmlspecialchars($user_data['ApplicantEmail']); ?>" required><br>
                                    </div>
                                    <div class="applicant-input-group">
                                        <label for="contact-number">Contact Number <br></label>
                                        <input type="text" id="contact-number" name="contact-number" value="<?php echo htmlspecialchars(decryption($user_data['ApplicantContact'])); ?>"
                                            data-original="<?php echo htmlspecialchars($user_data['ApplicantContact']); ?>" required><br>
                                    </div>
                                    <div class="applicant-input-group">
                                        <label for="applicant-sex">Sex</label>
                                        <select name="sex" id="sex">
                                            <option value="" disabled selected hidden></option>
                                            <option value="Male" <?php echo (decryption($user_data['ApplicantSex']) == 'Male') ? 'selected' : ''; ?>
                                                data-original="<?php echo htmlspecialchars($user_data['ApplicantSex']); ?>">Male</option>
                                            <option value="Female" <?php echo (decryption($user_data['ApplicantSex']) == 'Female') ? 'selected' : ''; ?>
                                                data-original="<?php echo htmlspecialchars($user_data['ApplicantSex']); ?>">Female</option>
                                        </select>
                                    </div>
                                    <div class="applicant-input-group">
                                        <label for="applicant-birthdate">Birthdate <br></label>
                                        <input type="date" id="applicant-birthdate" name="applicant-birthdate" placeholder="Month/Date/Year" value="<?php echo htmlspecialchars($user_data['ApplicantBday']); ?>"
                                            data-original="<?php echo htmlspecialchars($user_data['ApplicantBday']); ?>" required><br>
                                    </div>
                                    <div class="applicant-input-group">
                                        <label for="blklot">Block/Lot <br></label>
                                        <input type="text" id="blklot" name="blklot" value="<?php echo htmlspecialchars(decryption($user_data['ApplicantBlockLot'])); ?>"
                                            data-original="<?php echo htmlspecialchars($user_data['ApplicantBlockLot']); ?>" required><br>
                                    </div>
                                    <div class="applicant-input-group">
                                        <label for="street">Street <br></label>
                                        <select id="street" name="street" data-original="<?php echo htmlspecialchars($user_data['ApplicantStreet']); ?>" required><br>
                                            <option value="" disabled selected hidden></option>
                                            <?php foreach ($street as $street): ?>
                                                <option value="<?php echo htmlspecialchars($street['street_name']); ?>" <?php echo (decryption($user_data['ApplicantStreet']) == $street['street_name']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($street['street_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="applicant-input-group">
                                        <label for="barangay">Barangay <br></label>
                                        <select type="text" id="barangay" name="barangay" data-original="<?php echo htmlspecialchars($user_data['ApplicantBarangay']); ?>" required><br>
                                            <option value="" disabled selected hidden></option>
                                            <?php foreach ($barangay as $barangay): ?>
                                                <option value="<?php echo htmlspecialchars($barangay['barangay_name']); ?>" <?php echo (decryption($user_data['ApplicantBarangay']) == $barangay['barangay_name']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($barangay['barangay_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="applicant-input-group">
                                        <label for="city">City <br></label>
                                        <select type="text" id="city" name="city" data-original="<?php echo htmlspecialchars($user_data['ApplicantCity']); ?>" required><br>
                                            <option value="" disabled selected hidden></option>
                                            <?php foreach ($cities as $city): ?>
                                                <option value="<?php echo htmlspecialchars($city['city_name']); ?>" <?php echo (decryption($user_data['ApplicantCity']) == $city['city_name']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($city['city_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="applicant-input-group">
                                        <label for="province">Province <br></label>
                                        <input type="text" id="province" name="province" value="<?php echo htmlspecialchars(decryption($user_data['ApplicantProvince'])); ?>"
                                            data-original="<?php echo htmlspecialchars($user_data['ApplicantProvince']); ?>" required><br>
                                    </div>
                                </div>
                                <div class="button-container">
                                    <input type="submit" id="update-profile" name="save-changes" value="Save Changes">
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
            <div class="about-applicant">
                <form method="post" action="applicant-profile.php">
                    <h1>About Me</h1>
                    <div class="about-applicant-wrapper">
                        <div class="applicant-bio">
                            <h2>Bio</h2>
                            <textarea id="applicant-bio" name="my-bio" rows="4" cols="50" placeholder="Bio"
                                data-original="<?php echo htmlspecialchars($bio); ?>"><?php echo htmlspecialchars($bio); ?></textarea>
                        </div>
                        <div class="applicant-about">
                            <h2>Background</h2>
                            <textarea id="about-me" name="about-me" rows="4" cols="50" placeholder="Tell us about yourself..."
                                data-original="<?php echo htmlspecialchars($background); ?>"><?php echo htmlspecialchars($background); ?></textarea>
                        </div>
                    </div>
                    <div class="button-container">
                        <input type="submit" id="update-about" name="save-about" value="Save Changes">
                    </div>
                </form>
            </div>
            <div class="applicant-picture-role-wrapper">
                <div class="applicant-profile-picture">
                    <h1>Profile Picture</h1>
                    <form method="post" action="applicant-profile.php" enctype="multipart/form-data">
                        <div class="profile-picture-wrapper">
                            <label for="profile-picture">
                                <?php if (!empty($applicant_picture)): ?>
                                    <img src="assets/profile-uploads/<?php echo htmlspecialchars($applicant_picture); ?>" alt="Profile Picture" id="profile-picture-preview">
                                <?php else: ?>
                                    <img src="assets/profile-uploads/user.png" alt="Default Profile Picture" id="profile-picture-preview">
                                <?php endif; ?>
                            </label>
                        </div>
                        <input type="file" name="profile-picture" id="profile-picture" accept="image/*" style="display: none;">
                        <button type="submit" id="update-picture-button" name="upload-picture">Upload Profile Picture</button>
                    </form>
                </div>
                <div class="applicant-role-editor-wrapper">
                    <h1>About your role</h1>
                    <div class="applicant-role-editor">
                        <form method="post" action="applicant-profile.php">
                            <div class="applicant-role-editor-input">
                                <div class="applicant-role-group">
                                    <label for="work-type">Your skills <br></label>
                                    </ul>
                                    <button id="show-category" onclick="showDiv('hidden-category-div', event)"><img id="add-button" src="assets/images/plus.png"></button>
                                </div>
                                <div class="applicant-skills">
                                    <ul id="skills-list">
                                        <?php
                                        $applicant_id = $_SESSION['ApplicantID'];
                                        $fetch_applicant_skills = "
                                            SELECT DISTINCT skills.SkillName, skills.SkillID
                                            FROM applicantskills 
                                            INNER JOIN skills ON applicantskills.SkillID = skills.SkillID 
                                            WHERE applicantskills.ApplicantID = ?";
                                        $stmt = mysqli_prepare($link, $fetch_applicant_skills);
                                        mysqli_stmt_bind_param($stmt, "i", $applicant_id);
                                        mysqli_stmt_execute($stmt);
                                        $result = mysqli_stmt_get_result($stmt);

                                        $displayed_skills = [];
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $skill_name = htmlspecialchars($row['SkillName']);
                                            $skill_id = htmlspecialchars($row['SkillID']);
                                            if (!in_array($skill_name, $displayed_skills)) {
                                                echo '<li class="skill-item">';
                                                echo '<form method="post" action="applicant-profile.php">';
                                                echo '<input type="hidden" name="skill-id" value="' . $skill_id . '">';
                                                echo '<button type="submit" name="remove-skill" class="remove-skill-button">' . $skill_name . '</button>';
                                                echo '</form>';
                                                echo '</li>';
                                                $displayed_skills[] = $skill_name;
                                            }
                                        }
                                        mysqli_stmt_close($stmt);
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <form method="post" action="applicant-profile.php">
            <div id="hidden-category-div" class="hidden-category-div">
                <div class="category-div-wrapper">
                    <?php
                    $applicant_skills = fetch_skills($link);
                    foreach ($applicant_skills as $skills) {
                        if (in_array($skills['SkillID'], $selected_skill_ids)) {
                            continue;
                        }

                        echo '<div>';
                        echo '<input type="checkbox" id="work-type-' . htmlspecialchars($skills['SkillID']) . '" name="work-types[]" value="' . htmlspecialchars($skills['SkillID']) . '">';
                        echo '<label for="' . htmlspecialchars($skills['SkillName']) . '">' . htmlspecialchars($skills['SkillName']) . '</label><br>';
                        echo '</div>';
                    }
                    ?>
                    <button id="toggle-category-button" onclick="hideDiv('hidden-category-div')">Done</button>
                </div>
            </div>
        </form>
    </form>
</body>