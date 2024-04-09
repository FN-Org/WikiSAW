<?php
require_once("./../include/session_control.php");
require_once ('./../include/tools.php');
?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>create course</title>
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
    <a href="./home.php" class="homeButton" role="button">
        <i class="fa fa-home" id="home" aria-hidden="true"></i>
    </a>
    <h1 id="title"><span class="wiki">wiki</span><span class="saw">SAW</span></h1>
    <h2 id="subtitle">Create Course</h2>
    <form class="defaultFlex updateFormSize generalForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        <div class="labeledInput upLabeledInput" id="image_container">
            <label for="image">Select an image as a course cover <br> (notice that you will no longer be able to change it):</label>
            <input type="file" name="image" id="image" accept="image/*" required>
        </div>
        <div class="defaultFlex fullwidth">
            <div class="labeledInput upLabeledInput">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Insert a title" required>
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
        <button type="submit" class="backColor2 formButton" id="changeButton">Publish Course</button>
    </form>
</div>

<script src="./../js/create_course.js"></script>

</body>
</html>

<?php
require_once ('./../include/db_connection.php');
// Creation of a course

    // Checking if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Checking if a file is sent
        if (isset($_FILES['image'])) {
            // Directory where the file course cover has to be uploaded
            $uploadDirectory = './../contents/images/course_covers/';

            // Name of the file uploaded by the user
            $uploadedFile = $_FILES['image'];

            // Checking if the upload went wrong
            if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
                $originalFileName = basename($uploadedFile['name']);
                $tempFilePath = $uploadedFile['tmp_name'];

                // Rename the name of the file adding a prefixed unique
                // identifier based on the current time in microseconds
                $newFileName = uniqid() . '_' . $originalFileName;
                $destinationPath = $uploadDirectory . $newFileName;

                // Move the image to the right destination and check the errors
                if (!move_uploaded_file($tempFilePath, $destinationPath)) {
                    error_log('create_course.php - Image upload failed', 3, './../../error_log.txt');
                    header('Location: ./teacher_dashboard.php?message=5');
                    exit();
                }
            } else {
                error_log('create_course.php - Image upload failed', 3, './../../error_log.txt');
                header('Location: ./teacher_dashboard.php?message=5');
                exit();
            }
        } else {
            error_log("create_course.php - An image was not loaded\n", 3, './../../error_log.txt');
            header('Location: ./teacher_dashboard.php?message=7');
            exit();
        }

        // All the inputs has to be set, checking if they are empty
        if (empty($_POST['title']) || empty($_POST['category']) || empty($_POST['difficulty']) || empty($_POST['price']) || empty($_POST['description']) || empty($_POST['video'])) {
            error_log("create_course.php - Some inputs are empty\n", 3, './../../error_log.txt');
            header('Location: ./teacher_dashboard.php?message=6');
            exit();
        }

        // Defining variables
        $title = test_input($_POST['title']);
        $category = isCategory(test_input($_POST['category']));
        $difficulty = isDifficulty(test_int($_POST['difficulty']));
        $price = isPrice(test_input($_POST['price']));
        $description = test_input($_POST['description']);
        $video = test_input($_POST['video']);
        $creator = $_SESSION['email'];

        // Database connection
        $mysql = db_connection();

        // Prepared statement
        // Query INSERT new course in COURSES table
        $query = 'INSERT INTO courses (name, category, description, difficulty, price, rating, image, video, creator) VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?)';
        $stmt = $mysql->prepare($query);
        if ($stmt === false) {
            error_log('create_course.php - Prepare statement failed: ' . $mysql->error, 3, './../../error_log.txt');
            header('Location: ./teacher_dashboard.php?message=3');
            exit();
        }
        $stmt->bind_param('ssssssss', $title, $category, $description, $difficulty, $price, $newFileName, $video, $creator);
        $stmt->execute();
        if ($mysql->affected_rows != 1) {
            error_log('create_course.php - INSERT query failed', 3, './../../error_log.txt');
            header('Location: ./teacher_dashboard.php?message=3');
            exit();
        }

        // Course created successfully!
        $stmt->close();
        $mysql->close();
        header('Location: ./teacher_dashboard.php?message=1');
}
?>