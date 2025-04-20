document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("signup-form");
    const popup = document.getElementById("popup-error");
    const popupMessage = document.getElementById("popup-message");
    const popupClose = document.getElementById("popup-close");

    // Always hide popup on load
    popup.classList.add("popup-hidden");

    form.addEventListener("submit", function (e) {
        const companyName = document.getElementById("company-name").value.trim();
        const email = document.getElementById("email").value.trim();
        const contact = document.getElementById("contact").value.trim();
        const blklot = document.getElementById("blklot").value.trim();
        const street = document.getElementById("street").value.trim();
        const brgy = document.getElementById("brgy").value.trim();
        const city = document.getElementById("city").value;
        const province = document.getElementById("province").value;
        const zip = document.getElementById("zip").value.trim();
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm-password").value;
        const terms = document.getElementById("terms").checked;

        const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        const contactPattern = /^09\d{9}$/;

        if (companyName === "") {
            e.preventDefault();
            showPopup("Please enter your company name.");
            return;
        }

        if (!emailPattern.test(email)) {
            e.preventDefault();
            showPopup("Please enter a valid email address.");
            return;
        }

        if (!contactPattern.test(contact)) {
            e.preventDefault();
            showPopup("Please enter a valid PH mobile number (e.g., 09xxxxxxxxx).");
            return;
        }

        if (blklot === "") {
            e.preventDefault();
            showPopup("Please enter your block/lot.");
            return;
        }

        if (street === "") {
            e.preventDefault();
            showPopup("Please enter your street.");
            return;
        }

        if (brgy === "") {
            e.preventDefault();
            showPopup("Please enter your barangay.");
            return;
        }

        if (city === "") {
            e.preventDefault();
            showPopup("Please select your city.");
            return;
        }

        if (province === "") {
            e.preventDefault();
            showPopup("Please select your province.");
            return;
        }

        if (zip === "" || isNaN(zip)) {
            e.preventDefault();
            showPopup("Please enter a valid zip code.");
            return;
        }

        if (password.length < 6) {
            e.preventDefault();
            showPopup("Password should be at least 6 characters.");
            return;
        }

        if (password !== confirmPassword) {
            e.preventDefault();
            showPopup("Passwords do not match.");
            return;
        }

        if (!terms) {
            e.preventDefault();
            showPopup("Please agree to the Terms and Policy.");
            return;
        }
    });

    function showPopup(message) {
        popupMessage.innerText = message;
        popup.classList.remove("popup-hidden");
    }

    popupClose.addEventListener("click", function () {
        popup.classList.add("popup-hidden");
    });
});
