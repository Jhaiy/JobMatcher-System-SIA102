document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const proceedBtn = document.getElementById("proceed");

    // Always hide popup on load
    document.getElementById("popup-error").classList.add("popup-hidden");

    proceedBtn.addEventListener("click", function (event) {
        event.preventDefault();

        const fname = document.getElementById("first-name").value.trim();
        const lname = document.getElementById("last-name").value.trim();
        const email = document.getElementById("email").value.trim();
        const city = document.getElementById("city").value;
        const province = document.getElementById("province").value;
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm-password").value;
        const terms = document.getElementById("terms").checked;

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Validate inputs one by one
        if (!fname) return showPopup("First name is required.");
        if (!lname) return showPopup("Last name is required.");
        if (!email) return showPopup("Email is required.");
        if (!emailPattern.test(email)) return showPopup("Please enter a valid email address.");
        if (!city) return showPopup("Please select a city.");
        if (!province) return showPopup("Please select a province.");
        if (!password) return showPopup("Password is required.");
        if (password.length < 6) return showPopup("Password must be at least 6 characters.");
        if (confirmPassword !== password) return showPopup("Passwords do not match.");
        if (!terms) return showPopup("You must agree to the terms and policy.");

        form.submit();
    });

    function showPopup(message) {
        document.getElementById("popup-message").innerText = message;
        document.getElementById("popup-error").classList.remove("popup-hidden");
    }

    document.getElementById("popup-close").addEventListener("click", function () {
        document.getElementById("popup-error").classList.add("popup-hidden");
    });
});


