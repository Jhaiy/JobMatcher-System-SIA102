<?php
    include("db-config.php");

    function check_login_company($link) {
        if (isset($_SESSION['CompanyID'])) {
            $id = $_SESSION['CompanyID'];
            $sql = "SELECT * FROM company WHERE CompanyID = '$id'";
            $result = mysqli_query($link, $sql);

            if (mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                if (isset($user_data['CompanyName'])) {
                    $user_data['CompanyName'] = decryption($user_data['CompanyName']);
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