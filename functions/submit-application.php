<?php 
    require_once __DIR__ . '/../db-config.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $job_id = intval($_POST['job_id']);
        $applicant_id = intval($_POST['applicant_id']);
        $default_status = "Pending";

        $check_application_to_database = "SELECT * FROM applications WHERE JobListingID = ? AND ApplicantID = ?";
        $stmt = mysqli_prepare($link, $check_application_to_database);
        mysqli_stmt_bind_param($stmt, "ii", $job_id, $applicant_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "<p> You have already applied for this job.</p>";
        } else {
            $insert_application_to_database = "INSERT INTO applications (ApplicantID, JobListingID, ApplicationStatus) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($link, $insert_application_to_database);
            mysqli_stmt_bind_param($stmt, "iis", $applicant_id, $job_id, $default_status);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../applicant-status.php");
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($link);
    } else {
        header("Location: ../home-page.php");
        exit();
    }
?>