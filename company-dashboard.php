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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="home-style.css">
        <script src="javascript/page-scripts.js"></script>
    </head>
    <body>
        <script src="javascript/page-scripts.js"></script>
        <div class="navbar">
            <div class="navbar-contents">
                <div class="navbar-links">
                    <ul>
                        <li><a href="#" id="logo">TechSync</a></li>
                        <li><a href="home-page.php">Jobs</a></li>
                        <?php if ($user_data): ?>
                            <li><a href="#">Profile</a></li>
                            <li><a href="#">Status</a></li>
                        <?php endif; ?>
                        <li><a href="about-us-page.php">About Us</a></li>
                    </ul>
                </div>
                <div class="los">
                    <?php if ($user_data && isset($user_data['CompanyName'])): ?>
                        <li><p id="los-name"><?php echo htmlspecialchars($user_data['CompanyName']); ?></p></li>
                        <form method="post" action="home-page.php">
                            <input type="submit" name="logout" value="Log Out">
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
</html>