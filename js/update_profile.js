// Call asynchronously PHP script
fetch('./../fetch/user_info_loading.php')
    .then(response => {
        if (!response.ok){
            throw new Error('The network response went wrong');;
        }
        return response.json();
    }).then(data => {
    if (data.error) {
        alert('Something went wrong');
    } else {
        fill_inputs(data.row);
    }
}).catch(error => {
    console.error('Error during fetch:', error);
    alert('Something went wrong');
});

function fill_inputs(jsObject) {
    document.getElementById('firstname').value = jsObject.first_name;
    document.getElementById('lastname').value = jsObject.last_name;
    document.getElementById('email').value = jsObject.email;
    document.getElementById('birth').value = jsObject.birth;
    document.getElementById('mobile').value = jsObject.mobile;
    document.getElementById('bio').value = jsObject.bio;
    document.getElementById('newsletter').checked = jsObject.newsletter === '1';

    document.querySelector('.backButton').addEventListener('click', goBack);
    document.querySelector('.backButton').addEventListener('mouseover', enlarge);
    document.querySelector('.backButton').addEventListener('mouseout', shrink);
}

function goBack() {
    window.history.back();
}

function enlarge() {
    document.getElementById('arrow').style.transform = 'scale(1.2)';
}

function shrink() {
    document.getElementById('arrow').style.transform = 'scale(1)';
}

document.getElementById('newPassword').addEventListener('input', checkPasswordLength);
document.getElementById('confirmNewPassword').addEventListener('input', checkConfirmPass);

// Function to check the length of the password
// that has to be at least 8 characters
function checkPasswordLength() {
    const inputPass = document.getElementById('newPassword').value;
    const submitButton = document.getElementById('changeButton');
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
    const password = document.getElementById('newPassword').value;
    const confirm = document.getElementById('confirmNewPassword').value;
    const submitButton = document.getElementById('changeButton');
    if (password === confirm) {
        error("");
        submitButton.disabled = false;
    } else {
        error("Password doesn't match!");
        submitButton.disabled = true;
    }
}