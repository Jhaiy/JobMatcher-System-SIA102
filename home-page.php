<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home-style.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-contents">
            <div class="navbar-links">
                <ul>
                    <li><a href="#" id="logo">ToniFowler</a></li>
                    <li><a href="#">Jobs</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Status</a></li>
                    <li><a href="#">About Us</a></li>
                </ul>
            </div>
            <div class="los">
                <li><a id="los" href="#">Log In</a></li>
                <li><a id="los" href="#">Sign Up</a></li>
            </div>
        </div>
    </div>
    <div class="search-outer">
        <h1>Navigate to success.</h1>
        <div class="search-bar">
            <img src="assets/images/search-interface-symbol.png">
            <input type="text" placeholder="Search by job, company, or skills" id="search-query">
            <img src="assets/images/location-pin.png">
            <input type="checkbox" id="location-dropdown">
            <label for="location-dropdown" id="location-dropdown-label">Location</label>
            <div class="location-container"></div>
            <img src="assets/images/skill-development.png">
            <input type="checkbox" id="skills-dropdown">
            <label for="skills-dropdown">Skills</label>
            <div class="skills-container">
            <label><input type="checkbox" name="skill" value="html"> HTML</label>
                <label><input type="checkbox" name="skill" value="css"> CSS</label>
                <label><input type="checkbox" name="skill" value="javascript"> JavaScript</label>
                <label><input type="checkbox" name="skill" value="php"> PHP</label>
                <label><input type="checkbox" name="skill" value="python"> Python</label>
            </div>
        </div>
        <button>SEARCH</button>
    </div>
</body>
</html>