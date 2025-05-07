<?php
session_start();
require_once "db-config.php";
include_once "functions/password-hash.php";
require_once 'vendor/autoload.php';

$input = json_decode(file_get_contents("php://input"), true);
$token = $input['credential'];

$client = new Google_Client(['client_id' => 'YOUR_GOOGLE_CLIENT_ID']); // Replace this too
$payload = $client->verifyIdToken($token);

if ($payload) {
    $email = $payload['email'];
    $firstName = $payload['given_name'];
    $lastName = $payload['family_name'];

    $encryptedEmail = encryption($email);
    $encryptedFName = encryption($firstName);
    $encryptedLName = encryption($lastName);
    $defaultPass = encryption("google_oauth");

    $query = "SELECT * FROM applicants WHERE ApplicantEmail = '$encryptedEmail'";
    $result = mysqli_query($link, $query);


    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['ApplicantID'] = $user['ApplicantID'];
    } else {
        mysqli_query($link, "INSERT INTO applicantprofiles () VALUES ()");
        $profile_id = mysqli_insert_id($link);

        $insert = "INSERT INTO applicants (ApplicantID, ApplicantFName, ApplicantLName, ApplicantEmail, ApplicantPass,
            ApplicantStreet, ApplicantBarangay, ApplicantCity, ApplicantProvince, ApplicantProfileID)
            VALUES ('$profile_id', '$encryptedFName', '$encryptedLName', '$encryptedEmail', '$defaultPass',
            '', '', '', '', '$profile_id')";


        if (mysqli_query($link, $insert)) {
            $_SESSION['ApplicantID'] = $profile_id;
        } else {
            echo json_encode(["success" => false, "message" => mysqli_error($link)]);
            exit;
        }
    }


    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid token"]);
}
