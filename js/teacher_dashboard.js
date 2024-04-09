// Courses table for teachers
// Create table with jQuery datatables
let table_courses = new DataTable('#courses', {
    // Ajax call to server_processing php script for the DB information
    ajax: './../contents/datatables/server_processing_t_c.php',
    processing: true,
    serverSide: true,
    responsive: true,
    columnDefs: [
        {
            targets: [0], // Column with different content
            render: function () {
                // Show a button based on the edit or normal mode
                return '<button class="backColor2 datatableButton delete_course" aria-label="delete course"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></button>' +
                    '<button class="backColor2 datatableButton select_button hidden" aria-label="modify course"><i class="fa fa-check" aria-hidden="true"></i></i></button>';
            },
        }
    ],
    // add the class clickable to the appropriate tag
    // (all except the first one)
    rowCallback: function (row) {
        $(row).find('td:gt(0)').addClass('clickable');
    }
});

// On click redirect to the course page with that particular id
$('#courses tbody').on('click', 'td.clickable', function () {
    let courseId = table_courses.cell($(this).closest('tr').find('td:eq(1)')).data();
    window.location.href = './course.php?id=' + courseId;
});

// On click to the button, delete course
$('#courses tbody').on('click', 'button.delete_course', function (event) {
    // Stop propagation to avoid the Event propagation
    // during the responsive datatables
    event.stopPropagation();

    let button = this;
    let row = table_courses.row(button.closest('tr'));
    let data = row.data();

    const question = "Do you want to delete: ";

    // data[2] is the title
    const confirmation = confirm(question + data[2] + '?');

    if (confirmation) {
        // Call asynchronously PHP script
        fetch('./../fetch/delete_course.php', {
            // Pass data with POST
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            // Create a json element
            body: JSON.stringify(data),
        })
            .then(function () {
                // Reload to see all changes
                location.reload();
            })
            .catch(function (error) {
                alert('Something went wrong');
            });
    }
});

// Add event listener to the command buttons
document.addEventListener('DOMContentLoaded', function () {

    // Add the event listener to the create course button
    document.getElementById('createButton').addEventListener('click', function() {
        window.location.href = './create_course.php';
    })

    let isEditMode = false;

    // Add the event listener to the modify course button
    document.getElementById('modifyButton').addEventListener('click', function () {
        if (isEditMode) {
            exitEditMode();
        } else {
            enterEditMode();
        }

        isEditMode = !isEditMode;
    });
});

// Function to redirect to the modify_course page with
// the right id in the URL
function modify_course() {
    event.stopPropagation();

    let button = this;
    let row = table_courses.row(button.closest('tr'));
    let data = row.data();

    const question = "Are you sure you want to modify: ";

    const conferma = confirm(question + data[2] + '?');

    if (conferma) {
        window.location.href = './modify_course.php' + '?id=' + data[1];
    }
}

// Function to enter on the edit mode,
// so where you can choose the course that you
// want to update
function enterEditMode() {
    document.body.style.backgroundColor = 'rgba(0,0,0,20%)';
    document.getElementById('createButton').disabled = true;
    document.getElementById('modifyButton').innerHTML = 'Exit';

    let deleteButtons = document.getElementsByClassName('delete_course');
    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].style.display = 'none';
    }

    let selectButtons = document.getElementsByClassName('select_button');
    for (let i = 0; i < selectButtons.length; i++) {
        selectButtons[i].style.display = 'block';
        selectButtons[i].addEventListener('click', modify_course);
    }
}

// Function to exit from the edit mode
function exitEditMode() {
    document.body.style.backgroundColor = '';
    document.getElementById('createButton').disabled = false;
    document.getElementById('modifyButton').innerHTML = 'Modify';

    let deleteButtons = document.getElementsByClassName('delete_course');
    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].style.display = 'block';
    }

    let selectButtons = document.getElementsByClassName('select_button');
    for (let i = 0; i < selectButtons.length; i++) {
        selectButtons[i].style.display = 'none';
        selectButtons[i].removeEventListener('click', modify_course);
    }
}