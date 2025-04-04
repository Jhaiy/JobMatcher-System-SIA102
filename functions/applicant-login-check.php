<?php
    include("db-config.php");
    include("password-hash.php");

    function check_login($link) {
        if (isset($_SESSION['ApplicantID'])) {
            $id = $_SESSION['ApplicantID'];
            $sql = "SELECT * FROM applicants WHERE ApplicantID = '$id'";
            $result = mysqli_query($link, $sql);

            if (mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                if (isset($user_data['ApplicantFName'])) {
                    $user_data['ApplicantFName'] = decryption($user_data['ApplicantFName']);
                }
                
                return $user_data;
            }
            else {
                return false;
            }
        }
        header("Location: login.php");
        die;
    }
?>