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

    function fetch_companies($link) {
        $companies = [];
        $sql = "SELECT CompanyID, CompanyName, LogoPath FROM company";
        $result = mysqli_query($link, $sql);

        if (!$result) {
            error_log("Database error: " . mysqli_error($link));
            return $companies;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $companies[] = $row;
        }
        return $companies;
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
        $applicant_picture = null;
        $sql = "SELECT ApplicantPic FROM applicantprofiles WHERE ApplicantProfileID = ?";
        $stmt = mysqli_prepare($link, $sql);
    
        if (!$stmt) {
            error_log("Prepare failed: " . mysqli_error($link));
            return null;
        }
    
        mysqli_stmt_bind_param($stmt, "i", $applicant_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $applicant_picture = $row['ApplicantPic'];
        }
    
        mysqli_stmt_close($stmt);
        return $applicant_picture;
    }

    // NEW FUNCTION: Fetch available jobs for logged-in users
    function fetch_available_jobs($link) {
        $jobs = [];
        $sql = "SELECT j.JobID, j.JobTitle, j.Location, j.Salary, 
                       c.CompanyID, c.CompanyName, c.LogoPath
                FROM jobs j
                JOIN company c ON j.CompanyID = c.CompanyID
                WHERE j.Status = 'Open'
                ORDER BY j.PostDate DESC
                LIMIT 6";
        
        $result = mysqli_query($link, $sql);

        if (!$result) {
            error_log("Database error: " . mysqli_error($link));
            return $jobs;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $jobs[] = $row;
        }
        return $jobs;
    }
?>