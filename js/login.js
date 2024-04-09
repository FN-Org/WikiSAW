// Default form button for going home and back
document.querySelector('.backButton').addEventListener('click', goBack);
document.querySelector('.backButton').addEventListener('mouseover', enlarge);
document.querySelector('.backButton').addEventListener('mouseout', shrink);

document.querySelector('.homeButton').addEventListener('mouseover', enlarge);
document.querySelector('.homeButton').addEventListener('mouseout', shrink);

function goBack() {
    window.history.back();
}

function enlarge() {
    event.target.style.transform = 'scale(1.2)';
}

function shrink() {
    event.target.style.transform = 'scale(1)';
}
