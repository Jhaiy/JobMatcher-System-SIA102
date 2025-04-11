// Fade-in Animation on Load
function applyFadeInAnimation() {
    const divs = document.querySelectorAll("div");
    divs.forEach((div, index) => {
        div.style.animationDelay = `${index * 0.1}s`;
        div.classList.add("fade-in");
    });
}

document.addEventListener('DOMContentLoaded', () => {
    applyFadeInAnimation();

    // View Details Modal Setup
    const viewDetailsBtn = document.getElementById("viewDetailsBtn");
    const detailsModal = document.getElementById("detailsModal");
    const closeDetailsBtn = detailsModal?.querySelector(".close");

    if (viewDetailsBtn && detailsModal && closeDetailsBtn) {
        viewDetailsBtn.addEventListener("click", () => {
            // Populate modal with dynamic data (adjust as necessary)
            document.querySelector(".title").textContent = "Job Title";
            document.querySelector(".description").textContent = "Job Description";
            document.getElementById("general-requirements").textContent = "General Requirements for the Job";
            document.getElementById("roles").textContent = "Roles and Responsibilities";
            document.getElementById("company-overview").textContent = "Company Overview here";
            document.getElementById("sample").textContent = "Sample Info about the company";

            detailsModal.style.display = "block";
        });

        closeDetailsBtn.addEventListener("click", () => {
            detailsModal.style.display = "none";
        });

        window.addEventListener("click", function (event) {
            if (event.target === detailsModal) {
                detailsModal.style.display = "none";
            }
        });
    }

    // Tab Switching Logic
    const tabs = document.querySelectorAll(".tab");
    const tabContents = document.querySelectorAll(".tab-content");

    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("active"));
            tabContents.forEach(content => content.style.display = "none");

            tab.classList.add("active");

            const tabId = tab.getAttribute("data-tab");
            const targetContent = document.getElementById(tabId);
            if (targetContent) targetContent.style.display = "block";

            if (tabId === 'about-us-tab') {
                document.getElementById("job-details-section").style.display = "none";
            } else {
                document.getElementById("job-details-section").style.display = "block";
            }
        });
    });

    // Sign-up Form Logic
    const form = document.getElementById('signup-form');
    const proceedBtn = document.getElementById('proceed');

    if (form && proceedBtn) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const termsChecked = document.getElementById('terms').checked;
            if (!termsChecked) {
                alert("Please agree to the Terms and Conditions.");
                return;
            }

            window.location.href = "home-page.php"; // Redirect after form submission
        });
    }

    // Login & Signup Modal Triggers
    document.querySelector('#loginBtn')?.addEventListener('click', e => {
        e.preventDefault();
        openModal('loginModal');
    });

    document.querySelector('#signUpBtn')?.addEventListener('click', e => {
        e.preventDefault();
        openModal('signUpModal');
    });

    // Link inside Login Modal to open Sign Up Modal
    document.querySelector('#signUpLinkFromLogin')?.addEventListener('click', function (e) {
        e.preventDefault();
        closeModal('loginModal');
        openModal('signUpModal');
    });

    // Link inside Sign-up Modal to open Login Modal
    document.querySelector('#loginLinkFromSignUp')?.addEventListener('click', function (e) {
        e.preventDefault();
        closeModal('signUpModal');
        openModal('loginModal');
    });

    // Close buttons
    document.querySelector('#closeLogin')?.addEventListener('click', () => closeModal('loginModal'));
    document.querySelector('#closeSignUp')?.addEventListener('click', () => closeModal('signUpModal'));

    // Cancel Sign-up Modal
    document.querySelector('#cancel-button')?.addEventListener('click', () => closeModal('signUpModal'));
});

// Show/Hide Custom Divs
function showDiv(divId, event) {
    if (event) event.preventDefault();

    document.querySelectorAll('.hidden-category-div, .hidden-role-div').forEach(div => {
        div.style.display = 'none';
    });

    const targetDiv = document.querySelector(`#${divId}`);
    if (targetDiv) {
        targetDiv.style.display = 'flex';
    }
}

function hideDiv(divId) {
    const targetDiv = document.querySelector(`#${divId}`);
    if (targetDiv) {
        targetDiv.style.display = 'none';
    }
}

// JavaScript code to handle opening and closing of login and sign-up modals

// Function to open the modal
function openModal(modalId) {
    // Get the modal element by id
    var modal = document.getElementById(modalId);
    
    // Display the modal
    modal.style.display = "block";
}

// Function to close the modal
function closeModal(modalId) {
    // Get the modal element by id
    var modal = document.getElementById(modalId);
    
    // Hide the modal
    modal.style.display = "none";
}

// Add event listeners to close modals when clicking on the "X" close button
document.getElementById("closeLogin").addEventListener("click", function() {
    closeModal("loginModal");
});

document.getElementById("closeSignUp").addEventListener("click", function() {
    closeModal("signUpModal");
});

// Add event listeners to navigate between login and sign-up modals
document.getElementById("signUpLinkFromLogin").addEventListener("click", function() {
    closeModal("loginModal");
    openModal("signUpModal");
});

document.getElementById("loginLinkFromSignUp").addEventListener("click", function() {
    closeModal("signUpModal");
    openModal("loginModal");
});

// To close the modal if the user clicks outside the modal content
window.addEventListener("click", function(event) {
    var loginModal = document.getElementById("loginModal");
    var signUpModal = document.getElementById("signUpModal");

    if (event.target === loginModal) {
        closeModal("loginModal");
    } else if (event.target === signUpModal) {
        closeModal("signUpModal");
    }
});

// Optional: Add event listener for pressing the ESC key to close modals
document.addEventListener("keydown", function(event) {
    if (event.key === "Escape") {
        closeModal("loginModal");
        closeModal("signUpModal");
    }
});

