<?php
require_once('./../include/session_control.php');
require_once ('./../include/tools.php');
?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update course</title>
    <link rel="stylesheet" href="./../contents/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/form.css">
    <link rel="stylesheet" href="./../css/create_course.css">
</head>
<body class="defaultSettings defaultFlex backColor1">
<div class="defaultFlex updateCourseBackSize formBackGround" id="formContainer">
    <div class="backButton" role="button">
        <i class="fa fa-arrow-left" id="arrow" aria-hidden="true"></i>
    </div>
    <a href="./home.php" class="homeButton" aria-label="Home">
        <i class="fa fa-home" id="home" aria-hidden="true"></i>
    </a>
    <h1 id="title"><span class="wiki">wiki</span><span class="saw">SAW</span></h1>
    <h2 id="subtitle">Update Course</h2>
    <form class="defaultFlex updateFormSize generalForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <div class="defaultFlex fullwidth">
            <div class="labeledInput upLabeledInput">
                <label for="name">Title</label>
                <input type="text" id="name" name="name" placeholder="Insert a title" required>
            </div>
            <div class="defaultFlex-column fullwidth">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="Life">Life</option>
                    <option value="Coding">Coding</option>
                    <option value="Science">Science</option>
                    <option value="Automation">Automation</option>
                    <option value="Languages">Languages</option>
                    <option value="Photography">Photography</option>
                    <option value="Music">Music</option>
                    <option value="Business">Business</option>
                </select>
            </div>
        </div>
        <div class="defaultFlex fullwidth">
            <div class="labeledInput upLabeledInput">
                <label for="difficulty">Difficulty</label>
                <select id="difficulty" name="difficulty" required>
                    <?php for ($i=1; $i<=10; $i=$i+1){
                        echo '<option value='.$i.'>'.$i.'</option>';
                    }?>
                </select>
            </div>
            <div class="defaultFlex-column">
                <label for="price">Price</label>
                <input type="text" id="price" name="price" placeholder="0.00" required>
            </div>
        </div>
        <div class="labeledInput upLabeledInput">
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Write the course description" required></textarea>
        </div>
        <div class="labeledInput upLabeledInput">
            <label for="video">Video course</label>
            <input id="video" name="video" placeholder="Insert the video's link" required>
        </div>
        <button type="submit" class="backColor2 formButton" id="changeButton">Save changes</button>
    </form>
</div>

<script> let id = <?php echo test_int($_GET['id']) ?>;</script>
<script src="./../js/modify_course.js"></script>
</body>
</html>

<?php
require_once ('./../include/db_connection.php');

// Database connection
$mysql = db_connection();
if (!$mysql) {
    error_log('modify_course.php - unable to access to the database: ' . mysqli_connect_error() . "\n", 3, './../../error_log.txt');
    header('Location: ./teacher_dashboard.php?message=5');
    exit();
}

// Checking if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // The SESSION id variable was set during JS fetch in modify_course.js
    // with the script course_info_loading.php
    // Checking if the SESSION id is set
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id']['id'];

        // All the inputs can't be empty, a course must have all the information set
        if (empty($_POST['name']) || empty($_POST['category']) || empty($_POST['difficulty']) || empty($_POST['price']) || empty($_POST['description'] || empty($_POST['video']))) {
            error_log("modify_courses.php - Some inputs missed\n", 3, './../../error_log.txt');
            header('Location: ./teacher_dashboard.php?message=6');
            exit();
        }

        // Setting variables
        $newTitle = test_input($_POST['name']);
        $newCategory = isCategory(test_input($_POST['category']));
        $newDifficulty = isDifficulty(test_int($_POST['difficulty']));
        $newPrice = isPrice(test_input($_POST['price']));
        $newDescription = test_input($_POST['description']);
        $newVideo = test_input($_POST['video']);

        // Checking if something is changed
        if ($_SESSION['id']['name'] != $newTitle ||
            $_SESSION['id']['category'] != $newCategory ||
            $_SESSION['id']['difficulty'] != $newDifficulty ||
            $_SESSION['id']['price'] != $newPrice ||
            $_SESSION['id']['description'] != $newDescription ||
            $_SESSION['id']['video'] != $newVideo) {
            // Try to do the UPDATE query
            try {
                $query = 'UPDATE courses SET name = ?, category = ?, difficulty = ?, price = ?, description = ?, video = ? WHERE id = ?';
                $stmt = $mysql->prepare($query);
                $stmt->bind_param('sssssss', $newTitle, $newCategory, $newDifficulty, $newPrice, $newDescription, $newVideo, $_SESSION['id']['id']);
                $stmt->execute();
                if ($mysql->affected_rows != 1) {
                    error_log("modify_courses.php - UPDATE query failed: $stmt->error\n", 3, './../../error_log.txt');
                    header('Location: ./teacher_dashboard.php?message=5');
                    exit();
                }
                if ($mysql->errno) {
                    header('Location: ./show_profile.php?message=4');
                    exit();
                }
                $stmt->close();
                $mysql->close();
                unset($_SESSION['id']);
                header('Location: ./teacher_dashboard.php?message=11');
                exit();
            // Catching all the exceptions
            } catch (mysqli_sql_exception $e) {
                header('Location: ./teacher_dashboard.php?message=5');
                exit();
            }
        }
        else {
            header('Location: ./teacher_dashboard.php?message=2');
            exit();
        }
    }
}

?>