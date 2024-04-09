<?php
    require_once ('./../include/remember_me_control.php');
    require_once('./../include/session_control.php');

    if ($_SESSION['teacher'] != 1) {
        header("Location: ./teacher_rules.php");
    }
?>

<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>teacher</title>
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/datatable.css">
    <link rel="stylesheet" href="../css/teacher_dashboard.css">

    <!-- Link script for jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Link CSS for jQuery datatables -->
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css">

    <!-- Link script for jQuery datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
</head>
<body class="defaultSettings">
<?php require_once "./../include/navbar.php"; ?>
<div class="start_space"></div>
<main id="tablesContainer">
    <h1>Teacher dashboard</h1>
    <?php
    // Managing all the errors from create course and modify course
    $message = isset($_GET['message']) ? $_GET['message'] : null;

    // TODO: fare array errors e gestire gli errori con una funzione
    if ($message == 1) {
        echo '<h6 class="success">Course created successfully!</h6>';
    } else if ($message == 2) {
        echo '<h6 class="success">Nothing has changed</h6>';
    } else if ($message == 3) {
        echo '<h6 class="error">Creation of the course failed</h6>';
    } else if ($message == 4) {
        echo '<h6 class="error">Please use a real category</h6>';
    } else if ($message == 5) {
        echo '<h6 class="error">Something went wrong, try again</h6>';
    } else if ($message == 6) {
        echo '<h6 class="error">Please fill all the required input</h6>';
    } else if ($message == 7) {
        echo '<h6 class="error">Remember to upload an image</h6>';
    } else if ($message == 8) {
        echo '<h6 class="error">Please use a real price (notice: just $, £, € are accepted)</h6>';
    } else if ($message == 9) {
        echo '<h6 class="error">You are not the creator of this course!</h6>';
    } else if ($message == 10) {
        echo '<h6 class="error">Please for difficulty use a number from 1 to 10</h6>';
    } else if ($message == 11) {
        echo '<h6 class="success">Course updated successfully</h6>';
    }
    ?>
    <h2>Your courses</h2>
    <div class="defaultFlex-column">
    <table id="courses" class="display responsive nowrap stripe" style="width:100%">
        <thead>
        <tr>
            <th>Delete</th>
            <th>Id</th>
            <th>Title</th>
            <th>Category</th>
            <th>Difficulty</th>
            <th>Price</th>
            <th>Rating</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Delete</th>
            <th>Id</th>
            <th>Title</th>
            <th>Category</th>
            <th>Difficulty</th>
            <th>Price</th>
            <th>Rating</th>
        </tr>
        </tfoot>
    </table>
        <div class="defaultFlex" id="command">
            <button class="backColor2 bold commandButton" id="createButton">Create course</button>
            <button class="backColor2 bold commandButton" id="modifyButton">Modify course</button>
        </div>
    </div>
</main>
<?php require_once ("./../include/footer.php");?>

<script src="./../js/teacher_dashboard.js"></script>

</body>
</html>
