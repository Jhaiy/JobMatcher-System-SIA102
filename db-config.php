<?php
    $host = "localhost";
    $user= "root";
    $password= "";
    $db = "db_techsync";

    $link = mysqli_connect($host, $user, $password, $db);

    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
?>                                                                                      