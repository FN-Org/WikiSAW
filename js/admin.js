// Users table
// Create table with jQuery datatables
let table_users = new DataTable('#users', {
    // Ajax call to server_processing php script for the DB information
    ajax: './../contents/datatables/server_processing_a_u.php',
    processing: true,
    serverSide: true,
    responsive: true,
    columnDefs: [
        {
            targets: [4,5,6], // Columns with different content
            render: function (data) {
                if (data == 1) return 'yes';
                else return 'no';
            }
        },
        {
            targets: [0], // Column with different content
            render: function (data) {
                // Put a different button if the user is banned or not
                if (data == 1) return '<button class="datatableButton ban_profile" aria-label="ban profile"><i class="fa fa-ban fa-lg red" aria-hidden="true"></i></button>'
                else return'<button class="backColor2 datatableButton ban_profile"aria-label="unban profile"><i class="fa fa-ban fa-lg" aria-hidden="true"></i></button>';
            }
        }
    ],
    // Function used to insert all the EventListener each table reload
    drawCallback: function () {
        initializeEvents();
    }
});

// Courses table
// Create table with jQuery datatables
let table_courses = new DataTable('#courses', {
    // Ajax call to server_processing php script for the DB information
    ajax: './../contents/datatables/server_processing_a_c.php',
    processing: true,
    serverSide: true,
    responsive: true,
    columnDefs: [
        {
            targets: [0], // Column with different content
            render: function () {
                return '<button class="backColor2 datatableButton delete_course" aria-label="delete course"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></button>';
            },
        }
    ],
    // Function used to insert all the EventListener each table reload
    drawCallback: function () {
        initializeEvents();
    }
});

// Create all the Events Listener in the buttons
function initializeEvents() {
    // Event listener per i bottoni ban_profile
    let banButtons = document.getElementsByClassName('ban_profile');
    for (let i = 0; i < banButtons.length; i++) {
        banButtons[i].addEventListener('click', ban_profile);
    }

    // Event listener per i bottoni delete_course
    let deleteButtons = document.getElementsByClassName('delete_course');
    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener('click', delete_course);
    }
}

// ban_profile function
function ban_profile() {
    // Stop propagation to avoid the Event propagation
    // during the responsive datatables
    event.stopPropagation();

    let button = this;
    // Get all the data of the user in this row
    let row = table_users.row(button.closest('tr'));
    let data = row.data();

    // If the first data is 1 it means that the user is already banned
    // and viceversa
    if (data[0] == 1){
        question = "Do you want to UNBAN user: ";
    } else {
        question = "Do you want to BAN user: ";
    }

    // data[3] is the email
    let conferma = confirm(question + data[3] + '?');

    if (conferma) {
        // Call asynchronously the script ban_user.php
        fetch('./../fetch/ban_user.php', {
            // Pass in POST the data array
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            // Create json
            body: JSON.stringify(data),
        })
            .then(function() {
                // Reload to see all changes
                location.reload();
            })
            .catch(function(error) {
                alert('Something went wrong');
            });
    }
}

// delete_course function
function delete_course() {

    // Stop propagation to avoid the Event propagation
    // during the responsive datatables
    event.stopPropagation();

    let button = this;
    // Get the course's information from this row
    let row = table_courses.row(button.closest('tr'));
    let data = row.data();

    const question = "Do you want to delete: ";

    // data[2] is the title
    const conferma = confirm(question + data[2] + '?');

    if (conferma) {
        // Call asynchronously the script delete_course.php
        fetch('./../fetch/delete_course.php', {
            // Send the array data in POST
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            // Create json
            body: JSON.stringify(data),
        }).then(function () {
            // Reload to see all changes
            location.reload();
        })
            .catch(function (error) {
                alert('Something went wrong');
            });
    }
}