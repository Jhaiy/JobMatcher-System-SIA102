// Fade-in Animation on Load

document.addEventListener("DOMContentLoaded", () => {
    // Tab Switching Logic
    const tabs = document.querySelectorAll(".tab");
    const tabContents = document.querySelectorAll(".tab-content");

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            tabs.forEach((t) => t.classList.remove("active"));
            tabContents.forEach((content) => (content.style.display = "none"));

            tab.classList.add("active");

            const tabId = tab.getAttribute("data-tab");
            const targetContent = document.getElementById(tabId);
            if (targetContent) targetContent.style.display = "block";

            if (tabId === "about-us-tab") {
                document.getElementById("job-details-section").style.display =
                    "none";
            } else {
                document.getElementById("job-details-section").style.display =
                    "block";
            }
        });
    });
});

// Show/Hide Custom Divs
function showDiv(divId, event) {
    if (event) event.preventDefault();

    document
        .querySelectorAll(".hidden-category-div, .hidden-role-div")
        .forEach((div) => {
            div.style.display = "none";
        });

    const targetDiv = document.querySelector(`#${divId}`);
    if (targetDiv) {
        targetDiv.style.display = "flex";
    }
}

function hideDiv(divId) {
    const targetDiv = document.querySelector(`#${divId}`);
    if (targetDiv) {
        targetDiv.style.display = "none";
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const doneButton = document.getElementById("toggle-category-button");
    const categoryDiv = document.getElementById("hidden-category-div");
    const selectedCategoryList = document.getElementById(
        "selected-category-list"
    );
    const form = document.querySelector(
        'form[action="employer-joblisting-page.php"]'
    );

    doneButton.addEventListener("click", (event) => {
        event.preventDefault();
        const selectedCategory = document.querySelector(
            'input[name="category-types[]"]:checked'
        );

        if (selectedCategory) {
            selectedCategoryList.innerHTML = "";
            const listItem = document.createElement("li");
            listItem.textContent =
                selectedCategory.nextElementSibling.textContent;
            selectedCategoryList.appendChild(listItem);
            const existingCategoryInput = document.querySelector(
                'input[name="category-item"]'
            );
            if (existingCategoryInput) {
                existingCategoryInput.remove();
            }

            const hiddenCategoryInput = document.createElement("input");
            hiddenCategoryInput.type = "hidden";
            hiddenCategoryInput.name = "category-item";
            hiddenCategoryInput.value = selectedCategory.value;
            form.appendChild(hiddenCategoryInput);

            categoryDiv.style.display = "none";
        } else {
            alert("Please select a category before clicking Done.");
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const doneButton = document.getElementById("toggle-role-button");
    const roleDiv = document.getElementById("hidden-role-div");
    const selectedRoleList = document.getElementById("selected-role-list");
    const form = document.querySelector(
        'form[action="employer-joblisting-page.php"]'
    );

    doneButton.addEventListener("click", (event) => {
        event.preventDefault();
        const selectedRole = document.querySelector(
            'input[name="role-types[]"]:checked'
        );

        if (selectedRole) {
            selectedRoleList.innerHTML = "";
            const listItem = document.createElement("li");
            listItem.textContent = selectedRole.nextElementSibling.textContent;
            selectedRoleList.appendChild(listItem);
            const existingRoleInput = document.querySelector(
                'input[name="role-item"]'
            );
            if (existingRoleInput) {
                existingRoleInput.remove();
            }

            const hiddenRoleInput = document.createElement("input");
            hiddenRoleInput.type = "hidden";
            hiddenRoleInput.name = "role-item";
            hiddenRoleInput.value = selectedRole.value;

            form.appendChild(hiddenRoleInput);

            roleDiv.style.display = "none";
        } else {
            alert("Please select a category before clicking Done.");
        }
    });
});
