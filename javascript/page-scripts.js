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
});

// Show/Hide Custom Divs
function showDiv(divId, event) {
    if (event) event.preventDefault();

    document.querySelectorAll('.hidden-category-div, .hidden-role-div, .modal').forEach(div => {
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