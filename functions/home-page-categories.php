<?php 
    function fetch_job_categories($link) {
        $job_categories = [];
        $sql = "SELECT * FROM jobcategories";
        $result = mysqli_query($link, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $job_categories[] = $row;
            }
        }
        return $job_categories;
    }

    function fetch_job_vacancies($link) {
        $job_vacancies = [];
        $sql = "SELECT * FROM skills";
        $result = mysqli_query($link, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $job_vacancies[] = $row;
            }
        }
        return $job_vacancies;
    }

    function fetch_job_roles($link) {
        $job_roles = [];
        $sql = "SELECT * FROM jobroles";
        $result = mysqli_query($link, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $job_roles[] = $row;
            }
        }
        return $job_roles;
    }

    function fetch_companies($link) {
        $companies = [];
        $sql = "SELECT * FROM company";
        $result = mysqli_query($link, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $companies[] = $row;
            }
        }
        return $companies;
    }

    function fetch_skills($link) {
        $applicant_skills = [];
        $sql = "SELECT * FROM skills";
        $result = mysqli_query($link, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $applicant_skills[] = $row;
            }
        }
        return $applicant_skills;
    }

    function fetch_profile_picture($link, $applicant_id) {
        $applicant_picture = null; // Initialize as null to handle cases where no picture is found
        $sql = "SELECT ApplicantPic FROM applicantprofiles WHERE ApplicantProfileID = ?";
        $stmt = mysqli_prepare($link, $sql);
    
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $applicant_id); // Bind the applicant ID as an integer
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
    
            if ($result && $row = mysqli_fetch_assoc($result)) {
                $applicant_picture = $row['ApplicantPic']; // Fetch the profile picture
            }
    
            mysqli_stmt_close($stmt);
        }
    
        return $applicant_picture; // Return the profile picture or null if not found
    }
?>