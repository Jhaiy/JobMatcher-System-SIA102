<?php
function fetch_job_categories($link)
{
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

function fetch_job_vacancies($link)
{
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

function fetch_job_roles($link)
{
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


function fetch_skills($link)
{
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

function fetch_profile_picture($link, $applicant_id)
{
    $query = "SELECT ApplicantPic FROM applicantprofiles WHERE ApplicantProfileID = ?";
    $stmt = mysqli_prepare($link, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $applicant_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row['ApplicantPic'];
        } else {
            return null;
        }
    } else {
        error_log("Database error: " . mysqli_error($link));
        return null;
    }
}

function fetch_company_profile_picture($link, $company_id)
{
    $query = "SELECT CompanyLogo FROM companydetails WHERE CompanyDetailsID = ?";
    $stmt = mysqli_prepare($link, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $company_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row['CompanyLogo'];
        } else {
            return null;
        }
    } else {
        error_log("Database error: " . mysqli_error($link));
        return null;
    }
}

function fetch_companies($link)
{
    $companies = [];
    $sql = "SELECT * FROM company";
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

function fetch_applications($link)
{
    $applications = [];
    $sql = "SELECT * FROM applications";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        error_log("Database error: " . mysqli_error($link));
        return $applications;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $applications[] = $row;
    }
    return $applications;
}

function fetch_city($link)
{
    $cities = [];
    $sql = "SELECT * FROM cities";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        error_log("Database error: " . mysqli_error($link));
        return $cities;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $cities[] = $row;
    }
    return $cities;
}

function fetch_barangay($link)
{
    $barangays = [];
    $sql = "SELECT * FROM barangays";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        error_log("Database error: " . mysqli_error($link));
        return $barangays;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $barangays[] = $row;
    }

    return $barangays;
}

function fetch_street($link)
{
    $streets = [];
    $sql = "SELECT * FROM streets";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        error_log("Database error: " . mysqli_error($link));
        return $streets;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $streets[] = $row;
    }

    return $streets;
}

function fetch_job_listings($link)
{
    $job_listings = [];
    $sql = "SELECT joblistings.*, company.CompanyName, companydetails.CompanyLogo
    FROM joblistings
    INNER JOIN company ON joblistings.CompanyID = company.CompanyID
    INNER JOIN companydetails ON company.CompanyDetailsID = companydetails.CompanyDetailsID";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        error_log("Database error: " . mysqli_error($link));
        return $job_listings;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $job_listings[] = $row;
    }

    return $job_listings;
}

function fetch_distinct_job_listings($link, $company_id)
{
    $job_listings = [];
    $sql = "SELECT joblistings.*, company.CompanyName, companydetails.CompanyLogo
    FROM joblistings
    INNER JOIN company ON joblistings.CompanyID = company.CompanyID
    INNER JOIN companydetails ON company.CompanyDetailsID = companydetails.CompanyDetailsID
    WHERE company.CompanyID = '$company_id'";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        error_log("Database error: " . mysqli_error($link));
        return $job_listings;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $job_listings[] = $row;
    }

    return $job_listings;
}
