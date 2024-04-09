// Default form button for going home and back
document.querySelector('.backButton').addEventListener('click', goBack);
document.querySelector('.backButton').addEventListener('mouseover', enlarge);
document.querySelector('.backButton').addEventListener('mouseout', shrink)

function goBack() {
    window.history.back();
}

function enlarge() {
    document.getElementById('arrow').style.transform = 'scale(1.2)';
}

function shrink() {
    document.getElementById('arrow').style.transform = 'scale(1)';
}