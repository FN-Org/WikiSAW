<?php
require_once('./../include/remember_me_control.php');
require_once('./../include/session_control.php');
if ($_SESSION['admin']!= 1) {
    header("Location: ./home.php");
    exit();
}
?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin_dashboard</title>
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/datatable.css">

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
        <h1 class="text-center">Admin dashboard</h1>
        <h2 class="text-center">Users</h2>
        <div class="defaultFlex">
        <table id="users" class="display responsive nowrap stripe" style="width:100%">
            <thead>
            <tr>
                <th>Ban</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Email</th>
                <th>Teacher</th>
                <th>Admin</th>
                <th>Newsletter</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Ban</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Email</th>
                <th>Teacher</th>
                <th>Admin</th>
                <th>Newsletter</th>
            </tr>
            </tfoot>
        </table>
        </div>
        <h2 class="text-center">Courses</h2>
        <div class="defaultFlex">
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
        </div>
    </main>
    <?php require_once ("./../include/footer.php");?>
    <script src="./../js/admin.js"></script>
</body>
</html>

