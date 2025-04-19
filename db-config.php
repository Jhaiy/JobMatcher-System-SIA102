<?php
    $host = "sql12.freesqldatabase.com";
    $user= "sql12774029";
    $password= "WPIf4sUYbz";
    $db = "sql12774029";

    $link = mysqli_connect($host, $user, $password, $db);

    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
?>