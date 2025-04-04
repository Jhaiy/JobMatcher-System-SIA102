<?php 
    function fetch_job_categories($link) {
        $job_categories = [];
        $sql = "SELECT * FROM jobcategories";
        $result = mysqli_query($link, $sql);

        if (!$result) {
            error_log("Database error: " . mysqli_error($link));
            return $job_categories;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $job_categories[] = $row;
        }
        return $job_categories;
    }

    function fetch_job_vacancies($link) {
        $job_vacancies = [];
        $sql = "SELECT * FROM skills";
        $result = mysqli_query($link, $sql);

        if (!$result) {
            error_log("Database error: " . mysqli_error($link));
            return $job_vacancies;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $job_vacancies[] = $row;
        }
        return $job_vacancies;
    }

    function fetch_job_roles($link) {
        $job_roles = [];
        $sql = "SELECT * FROM jobroles";
        $result = mysqli_query($link, $sql);

        if (!$result) {
            error_log("Database error: " . mysqli_error($link));
            return $job_roles;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $job_roles[] = $row;
        }
        return $job_roles;
    }


    function fetch_skills($link) {
        $applicant_skills = [];
        $sql = "SELECT * FROM skills";
        $result = mysqli_query($link, $sql);

        if (!$result) {
            error_log("Database error: " . mysqli_error($link));
            return $applicant_skills;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $applicant_skills[] = $row;
        }
        return $applicant_skills;
    }

    function fetch_profile_picture($link, $applicant_id) {
        $query = "SELECT ApplicantPic FROM applicantprofiles WHERE ApplicantProfileID = ?";
        $stmt = mysqli_prepare($link, $query);
    
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $applicant_id); // Bind the parameter
            mysqli_stmt_execute($stmt); // Execute the statement
            $result = mysqli_stmt_get_result($stmt); // Get the result
    
            if ($row = mysqli_fetch_assoc($result)) {
                return $row['ApplicantPic']; // Return the profile picture
            } else {
                return null; // Return null if no result is found
            }
        } else {
            error_log("Database error: " . mysqli_error($link)); // Log any errors
            return null;
        }
    }
?>