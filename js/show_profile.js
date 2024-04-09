// Fill profile information
/* We needed to avoid the using of the fetch to pass the automatic
    tests, otherwise, for a better implementation, we would have used it
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
            show_profile(data.row);

        }
}).catch(error => {
    console.error('Error during fetch:', error);
    alert('Something went wrong');
});*/

// Show profile information
function show_profile(jsObject) {
    // Visualize unset instead of the default patterns
    // of birth and mobile
    if (jsObject.birth === '0001-01-01') {
        jsObject.birth = 'Unset';
    }
    if (jsObject.mobile === '000-0000000') {
        jsObject.mobile = 'Unset';
    }

    // Show the user icon based ont the user role
    let icon=document.getElementById('user_icon')
    if (jsObject.admin == '1'){
        icon.classList.remove('fa-graduation-cap');
        icon.classList.add('fa-diamond');
    }
    else if (jsObject.teacher == '1'){
        icon.classList.remove('fa-graduation-cap');
        icon.classList.add('fa-book')
    }

    // Fill all the profile information
    document.getElementById('f_name').children[1].innerHTML = jsObject.first_name;
    document.getElementById('l_name').children[1].innerHTML = jsObject.last_name;
    document.getElementById('email').children[1].innerHTML = jsObject.email;
    document.getElementById('birth').children[1].innerHTML = jsObject.birth;
    document.getElementById('mobile').children[1].innerHTML = jsObject.mobile;
    document.getElementById('bio').children[1].innerHTML = jsObject.bio
    if (jsObject.newsletter == '1') {
        document.getElementById('newsletter').children[1].innerHTML = 'Sì';
    }
    else {
        document.getElementById('newsletter').children[1].innerHTML = 'No';
    }
}

// Delete profile function
document.getElementById("deleteProfileButton").addEventListener("click", function() {
    let confirmation = confirm("Are you sure you want to delete your profile?");

    if (confirmation) {
        // Call asynchronously the PHP script
        fetch('./../fetch/delete_profile.php')
            .then(function () {
                setTimeout(function () {
                    location.reload();
                }, 1);
            })
            .catch(function (error) {
                alert('Something went wrong')
            });
    }
})

// Call asynchronously the PHP script
fetch('./../fetch/my_courses.php')
    .then(response => {
        if (!response.ok){
            throw new Error('The network response went wrong');;
        }
        return response.json();
    }).then(data => {
    if (data.error) alert('Something went wrong');
    else showMyCourses(data);
}).catch(error => {
    console.error('Error during fetch:', error);
    alert('Something went wrong');
});

// Function to show all the owned courses
function showMyCourses(data) {
    const start = document.getElementById('start');

    // If the user has bought courses, show them
    if (data.courses.length > 0) {
        const courses_container = document.createElement('div');
        courses_container.classList.add('defaultGrid');
        courses_container.id = 'courses_container';

        data.courses.forEach(row => {
            const link = document.createElement('a');
            link.href = './course.php?id=' + row[0];
            link.classList.add('defaultFlex-column');

            const img = document.createElement('img');
            img.src = './../contents/images/course_covers/' + row[1];
            img.classList.add('course_img');
            img.alt = row[2];

            const title = document.createElement('p');
            title.innerHTML = row[2];

            start.insertAdjacentElement('afterend', courses_container);
            link.appendChild(img);
            link.appendChild(title);
            courses_container.appendChild(link);
        })
        // Otherwise show a button linked to the catalog
    } else {
        const courses_container = document.createElement('div');
        courses_container.classList.add('defaultFlex-column');
        courses_container.style.width = '100%';

        const nocourses = document.createElement('p');
        nocourses.innerHTML = 'You dont\' have any courses yet, go to the catalog and let yourself be inspire!';
        nocourses.style.width = '90%';
        nocourses.style.textAlign = 'center';

        const catalog = document.createElement('a');
        catalog.href = './catalog.php';
        catalog.classList.add('redirect');
        catalog.classList.add('defaultFlex');
        catalog.innerHTML = 'CATALOG';

        start.insertAdjacentElement('afterend', courses_container);
        courses_container.appendChild(nocourses);
        courses_container.appendChild(catalog);
    }
}