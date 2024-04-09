// Check if the user has the required specifications
// to become a teacher
if (bool) {
    document.getElementById('becomeTeacher').addEventListener('click', function () {

        let confirmation = confirm('Do you really want to become a teacher?');

        if (confirmation) {
            // Call asynchronously PHP script
            fetch('./../fetch/become_teacher.php', {
                method: 'POST',
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text()
                // You are now a teacher, so you can access to you new dashboard
            }).then(data => {
                if (data === '1') {
                    window.location.href = 'teacher_dashboard.php';
                } else if (data === '-1') {
                    alert('Something went wrong.');
                } else {
                    console.error('Unknown response:', data);
                    alert('Something went wrong.');
                }
            }).catch(error => {
                console.error('Error during fetch: ', error)
                alert('Something went wrong');
            });
        }
    })
}