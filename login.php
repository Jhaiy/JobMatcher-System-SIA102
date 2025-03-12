<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width= , initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <form action="login.php" method="post">
            <div class="login-container">
                <h1>LOG IN</h1>
                <div class="credentials">
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
                    <button type="submit" class="btn-login">Log In</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>