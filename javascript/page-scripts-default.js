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