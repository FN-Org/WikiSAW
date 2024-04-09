// Function to check the length of the password
// that has to be at least 8 characters
function checkPasswordLength() {
    const inputPass = document.getElementById('pass').value;
    const submitButton = document.getElementById('registrationButton');
    if (inputPass.length < 8) {
        error("Password must have at least 8 characters.");
        submitButton.disabled = true;
    } else {
        error("");
        submitButton.disabled = false;
    }
}

// Function to check if the "confirm" password
// is equal to the other password
function checkConfirmPass() {
    const password = document.getElementById('pass').value;
    const confirm = document.getElementById('confirm').value;
    const submitButton = document.getElementById('registrationButton');
    if (password === confirm) {
        error("");
        submitButton.disabled = false;
    } else {
        error("Password doesn't match!");
        submitButton.disabled = true;
    }
}

document.getElementById('pass').addEventListener('input', checkPasswordLength);
document.getElementById('confirm').addEventListener('input', checkConfirmPass);

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