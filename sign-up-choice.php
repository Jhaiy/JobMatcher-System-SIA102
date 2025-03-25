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
            <div class="sign-up-container">
                <h1>Join as an Employer/Employee</h1>
                <div class="choice-container">
                    <div class="hiring-container">
                        <button type="submit" class="btn-choice">
                            <div class="choice-icons">
                                <img id="hiring-img" src="assets/images/profession.png">
                                <div class="cylinder"></div>
                            </div>
                            <h2>Employer, hiring for a job.</h2>
                        </button>
                    </div>
                    <div class="employee-container">
                        <button type="submit" class="btn-choice">
                            <div class="choice-icons">
                                <img id="employee-img" src="assets/images/teamwork.png">
                                <div class="cylinder"></div>
                            </div>
                            <h2>Employee, looking for a job.</h2>
                        </button>
                    </div>
                </div>
                <div class="signup-actions">
                    <p>Already have an account? <a href="login.php">Log In</a></p>
                </div>
            </div>
        </form>
    </div>
</body>
</html>