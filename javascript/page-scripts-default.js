function showDiv(divId, event) {
    if (event) {
        event.preventDefault();
    }

    document
        .querySelectorAll(".hidden-category-div, .hidden-role-div")
        .forEach((div) => {
            div.style.display = "none";
        });

    const targetDiv = document.querySelector(`#${divId}`);
    if (targetDiv) {
        targetDiv.style.display = "flex";
    }

    const modal = document.getElementById("job-modal");
    const inputs = modal.querySelectorAll("input, textarea, select");
    inputs.forEach((input) => {
        input.value = "";
        input.removeAttribute("data-original");
    });

    const categoryList = document.querySelector("#selected-category-list");
    const roleList = document.querySelector("#selected-role-list");
    if (categoryList) {
        categoryList.innerHTML = "";
        categoryList.removeAttribute("data-original");
    }
    if (roleList) {
        roleList.innerHTML = "";
        roleList.removeAttribute("data-original");
    }

    const submitButton = document.querySelector("#add-listing-btn");
    submitButton.textContent = "Add Listing";
}

function hideDiv(divId) {
    const targetDiv = document.querySelector(`#${divId}`);
    if (targetDiv) {
        targetDiv.style.display = "none";
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector('form[action="applicant-profile.php"]');
    const inputs = form.querySelectorAll(
        'input[type="text"], input[type="email"], textarea, select'
    );

    form.addEventListener("submit", (event) => {
        let isValid = true;
        let hasChanges = false;

        inputs.forEach((input) => {
            const originalValue =
                input.getAttribute("data-original")?.trim() || "";
            const currentValue = input.value.trim();

            if (currentValue === "" || currentValue === originalValue) {
                isValid = false;
                input.classList.add("error");
            } else {
                input.classList.remove("error");
            }
        });

        if (!isValid) {
            event.preventDefault();
            alert(
                "Please fill out all fields or make changes before submitting."
            );
        } else if (!hasChanges) {
            event.preventDefault();
            alert(
                "No changes detected. Please make changes before submitting."
            );
        }
    });
});

function editJobDetails(jobDetails) {
    console.log(jobDetails);
    const modal = document.getElementById("job-modal");
    modal.style.display = "block";

    const jobTitleInput = document.querySelector('input[name="job-title"]');
    jobTitleInput.value = jobDetails.JobTitle || "";
    jobTitleInput.setAttribute("data-original", jobDetails.JobTitle || "");

    const jobDescriptionTextarea = document.querySelector(
        'textarea[name="job-description"]'
    );
    jobDescriptionTextarea.value = jobDetails.JobDescription || "";
    jobDescriptionTextarea.setAttribute(
        "data-original",
        jobDetails.JobDescription || ""
    );

    const blockLotInput = document.querySelector('input[name="blocklot"]');
    blockLotInput.value = jobDetails.JobBlockLot || "";
    blockLotInput.setAttribute("data-original", jobDetails.JobBlockLot || "");

    const barangaySelect = document.querySelector(
        'select[name="jobbaranggay"]'
    );
    barangaySelect.value = jobDetails.JobBarangay || "";
    barangaySelect.setAttribute("data-original", jobDetails.JobBarangay || "");

    const streetSelect = document.querySelector('select[name="jobstreet"]');
    streetSelect.value = jobDetails.JobStreet || "";
    streetSelect.setAttribute("data-original", jobDetails.JobStreet || "");

    const citySelect = document.querySelector('select[name="jobcity"]');
    citySelect.value = jobDetails.JobCity || "";
    citySelect.setAttribute("data-original", jobDetails.JobCity || "");

    const provinceSelect = document.querySelector('select[name="jobprovince"]');
    provinceSelect.value = jobDetails.JobProvince || "";
    provinceSelect.setAttribute("data-original", jobDetails.JobProvince || "");

    const postalInput = document.querySelector('input[name="jobpostal"]');
    postalInput.value = jobDetails.JobPostalCode || "";
    postalInput.setAttribute("data-original", jobDetails.JobPostalCode || "");

    const closingDateInput = document.querySelector(
        'input[name="job-closing-date"]'
    );
    closingDateInput.value = jobDetails.ExpiryDate || "";
    closingDateInput.setAttribute("data-original", jobDetails.ExpiryDate || "");

    const salaryRangeSelect = document.querySelector(
        'select[name="salary-range"]'
    );
    salaryRangeSelect.value = jobDetails.SalaryRange || "";
    salaryRangeSelect.setAttribute(
        "data-original",
        jobDetails.SalaryRange || ""
    );

    const jobTypeSelect = document.querySelector('select[name="job-type"]');
    jobTypeSelect.value = jobDetails.JobType || "";
    jobTypeSelect.setAttribute("data-original", jobDetails.JobType || "");

    const additionalRequirementsTextarea = document.querySelector(
        'textarea[name="additional-requirements"]'
    );
    additionalRequirementsTextarea.value =
        jobDetails.AdditionalRequirements || "";
    additionalRequirementsTextarea.setAttribute(
        "data-original",
        jobDetails.AdditionalRequirements || ""
    );

    const educationInput = document.querySelector('input[name="education"]');
    educationInput.value = jobDetails.EducationAttainment || "";
    educationInput.setAttribute(
        "data-original",
        jobDetails.EducationAttainment || ""
    );

    const experienceInput = document.querySelector('input[name="experience"]');
    experienceInput.value = jobDetails.WorkExperience || "";
    experienceInput.setAttribute(
        "data-original",
        jobDetails.WorkExperience || ""
    );

    const categoryList = document.querySelector("#selected-category-list");
    categoryList.innerHTML = "";
    if (jobDetails.CategoryName) {
        const categoryItem = document.createElement("li");
        categoryItem.textContent = jobDetails.CategoryName;
        categoryList.appendChild(categoryItem);
        categoryList.setAttribute("data-original", jobDetails.CategoryName);
    }

    const roleList = document.querySelector("#selected-role-list");
    roleList.innerHTML = "";
    if (jobDetails.RoleName) {
        const roleItem = document.createElement("li");
        roleItem.textContent = jobDetails.RoleName;
        roleList.appendChild(roleItem);
        roleList.setAttribute("data-original", jobDetails.RoleName);
    }
    let jobIdInput = document.querySelector('input[name="job-id"]');
    if (!jobIdInput) {
        jobIdInput = document.createElement("input");
        jobIdInput.type = "hidden";
        jobIdInput.name = "job-id";
        document
            .querySelector('form[action="employer-joblisting-page.php"]')
            .appendChild(jobIdInput);
    }
    jobIdInput.value = jobDetails.JobListingID;

    const submitButton = document.querySelector("#add-listing-btn");
    submitButton.textContent = "Update Job Listing";
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(
        'form[action="employer-joblisting-page.php"]'
    );
    const inputs = form.querySelectorAll(
        'input[type="text"], input[type="date"], textarea, select'
    );

    const categoryList = document.querySelector("#selected-category-list");
    const roleList = document.querySelector("#selected-role-list");
    form.addEventListener("submit", (event) => {
        let hasChanges = false;

        inputs.forEach((input) => {
            const originalValue = input.getAttribute("data-original") || "";
            const currentValue = input.value || "";

            if (currentValue !== originalValue) {
                hasChanges = true;
            }
        });

        const originalRole = roleList.getAttribute("data-original") || "";
        const currentRole = roleList.textContent.trim();
        if (currentRole !== originalRole) {
            hasChanges = true;
        }

        const originalCategory =
            categoryList.getAttribute("data-original") || "";
        const currentCategory = categoryList.textContent.trim();
        if (currentCategory !== originalCategory) {
            hasChanges = true;
        }

        if (!hasChanges) {
            event.preventDefault();
            alert("No changes detected. Please make changes before updating.");
        }
    });
});
