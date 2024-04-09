// Call asynchronously PHP script
fetch('./../fetch/course_info_loading.php?id=' + id, {
    // Pass the id with GET
    method: 'GET'
}).then(response => {
    if (!response.ok) {
        throw new Error('The network response went wrong');
    }
    return response.json();
}).then(data => {
    if (data.error) {
        alert('Something went wrong');
    } else fill_inputs(data.course);
}).catch(error => {
    console.error('Error during fetch:', error);
    alert('Something went wrong');
});

/*
Function to insert al the appropriate value into the appropriate containers
 */
function fill_inputs(jsObject) {
    document.getElementById('name').value = jsObject.name;
    document.getElementById('category').value = jsObject.category;
    document.getElementById('difficulty').value = jsObject.difficulty;
    document.getElementById('price').value = jsObject.price;
    document.getElementById('description').value = jsObject.description;
    document.getElementById('video').value = jsObject.video;

    // Default form button for going home and back
    document.querySelector('.backButton').addEventListener('click', goBack);
    document.querySelector('.backButton').addEventListener('mouseover', enlarge);
    document.querySelector('.backButton').addEventListener('mouseout', shrink)
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

function error(stringa) {
    if (document.getElementsByClassName('error').length > 0) {
        const ok = document.querySelector('.error');
        ok.textContent = stringa;
    }
    else {
        const errorMessage = document.createElement('h6');
        errorMessage.classList.add('error');
        errorMessage.textContent = stringa;
        const el = document.getElementById('formContainer');
        el.insertBefore(errorMessage, document.querySelector('img').nextSibling);
    }
}