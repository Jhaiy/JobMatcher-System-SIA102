function applyFadeInAnimation() {
    const divs = document.querySelectorAll("div");
    divs.forEach((div, index) => {
        div.style.animationDelay = `${index * 0.1}s`;
        div.classList.add("fade-in");
    });
};

document.addEventListener('DOMContentLoaded', applyFadeInAnimation);

