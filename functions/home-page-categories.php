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
?>