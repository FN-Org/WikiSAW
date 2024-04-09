<?php
    // Fetched by admin.js and by teacher_dashboard.js
    require_once('./../include/tools.php');
    require_once('./../include/db_connection.php');
    require_once('./../include/session_ctr_include.php');


    //TODO controllo admin e teacher
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Decoding the json passed in POST with the fetch call
        $data = json_decode(file_get_contents('php://input'), false);

        $course_id = test_int($data[1]);
        if ($course_id===false) exit();

        $mysql = db_connection();

        if (!$mysql) {
            error_log('delete_course.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
        } else {
            // First of all we have to delete the image from the server,
            // so we select the image of the course from the courses table
            $query = 'SELECT image FROM courses WHERE id = ?';
            $stmt = $mysql->prepare($query);
            $stmt->bind_param('i', $course_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows != 1) {
                error_log("delete_course.php - the SELECT (for image path) went wrong: $stmt->error\n", 3, './../../error_log.txt');
            }
            $row = $res->fetch_assoc();

            if ($row) {
                // Path for the image
                $imagePath = './../contents/images/course_covers/' . $row['image'];
                // If the file exists, delete it
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                } else {
                    error_log("delete_course.php - File not found: $imagePath\n",3, './../../error_log.txt');
                }
            } else {
                error_log("delete_course.php - Image data not found for course id: $course_id\n",3, './../../error_log.txt');
            }

            // DELETE query to delete the course from the table
            $query = 'DELETE FROM courses WHERE id = ?';
            $stmt = $mysql->prepare($query);
            $stmt->bind_param('i', $course_id);
            $stmt->execute();
            if ($stmt->affected_rows != 1) {
                error_log('delete_course.php - the DELETE went wrong: ' . $stmt->error . "\n", 3, './../error_log.txt');
            }

            $stmt->close();
            $mysql->close();
        }
    }
?>