<?php
    include("db-config.php");

    function check_login($link) {
        if (isset($_SESSION['applicant_id'])) {
            $id = $_SESSION['applicant_id'];
            $sql = "SELECT * FROM applicant_db WHERE applicant_id = '$id'";
            $result = mysqli_query($link, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
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