<?php
// Fetched by the search.js
include('./../include/db_connection.php');
include('./../include/tools.php');

// Get the id in GET from the fetch
$response = array();
$response['error'] = false;
if (isset($_GET['query']) && $_GET['query'] !== '') {
    $request = test_input($_GET['query']); // tools.php
    if (is_numeric($request)) $rating = $request * 2;
    else $rating = $request;

    $mysql = db_connection();
    if (!$mysql) {
        error_log('search_script.php - db failed to connect', 3, './../../error_log.txt');
        $response['error'] = true;
        exit();
    }
    // SELECT query to select, from all the courses,
    // some information LIKE the input of the user
    $query = "SELECT * FROM courses WHERE courses.name LIKE CONCAT('%', ?, '%') OR courses.rating LIKE CONCAT('%', ?, '%') OR courses.description LIKE CONCAT('%', ?, '%') OR courses.price LIKE CONCAT('%', ?, '%')";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param('ssss', $request, $rating, $request, $request);
    $stmt->execute();
    $res = $stmt->get_result();
    $response['courses'] = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $mysql->close();
} else {
    $response['error'] = true;
}
$json_courses = json_encode($response);
echo $json_courses;
?>
