function showDiv(divId, event) {
    // Prevent the default form submission
    if (event) {
        event.preventDefault();
    }

    // Hide all hidden divs first
    document.querySelectorAll('.hidden-category-div, .hidden-role-div').forEach(div => {
        div.style.display = 'none';
    });

    // Show the target div
    const targetDiv = document.querySelector(`#${divId}`);
    if (targetDiv) {
        targetDiv.style.display = 'flex'; // Use flex to center content if needed
    }
}

function hideDiv(divId) {
    const targetDiv = document.querySelector(`#${divId}`);
    if (targetDiv) {
        targetDiv.style.display = 'none';
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector('form[action="applicant-profile.php"]');
    const inputs = form.querySelectorAll('input[type="text"], input[type="email"], select');

    form.addEventListener('submit', (event) => {
        let isValid = true;
        let hasChanges = false;

        inputs.forEach((input) => {
            const originalValue = input.getAttribute('data-original')?.trim() || '';
            const currentValue = input.value.trim();

            if (currentValue === '' || currentValue === originalValue) {
                isValid = false;
                input.classList.add('error');
            } else {
                input.classList.remove('error');
            }
        });

        if (!isValid) {
            event.preventDefault();
            alert('Please fill out all fields or make changes before submitting.');
        } else if (!hasChanges) {
            event.preventDefault();
            alert('No changes detected. Please make changes before submitting.');
        }
    });
});