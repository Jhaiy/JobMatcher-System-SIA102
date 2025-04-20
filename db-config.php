<?php
    $host = "localhost";
    $user= "root";
    $password= "";
    $db = "techsync_db";

    $link = mysqli_connect($host, $user, $password, $db);

    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
?>