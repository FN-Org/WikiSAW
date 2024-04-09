<?php
require_once('./../include/remember_me_control.php');
require_once('./../include/session_control.php');
require_once ("./../include/tools.php");
?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart</title>
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/catalog.css">
    <link rel="stylesheet" href="./../css/cart.css">

    <script src="./../js/utility.js"></script>
</head>
<body class="defaultSettings">
    <?php require_once "./../include/navbar.php"; ?>
    <main class="totalMargin">
        <div class="start_space"></div>
        <h2 class="fullwidth text-center"> Cart:</h2>
        <hr>
        <div id="courses">
            <div id="default_course_div" class="course">
                <a id="default_course" href="./catalog.php">
                    <img class = "courseImage" src="./../contents/images/DefSearch.png" alt="loading image">
                </a>
                <div class="courseInfo">
                    <h4 class="courseTitle">You have no element in the cart</h4>
                    <p class="courseDescription">Add some element from the catalog to see them here</p>
                    <div class="rating hidden">
                    <?php for ($i=0;$i<5;$i++){
                            echo '<i class="fa fa-star-o yellow" aria-hidden="true"></i>';
                    };?>
                    </div>
                    <span class="coursePrice hidden">€</span>
                    <button class="cartButton removebutton backColor2 hidden">Remove</button>
                </div>
            </div>
        </div>
        <hr>
        <div class="flex-space-around fullwidth">
            <h2 id="total">Total: 0€</h2>
            <button class="cartButton backColor2 hidden" id="buy"> Buy </button>
        </div>
    </main>
    <?php require_once ('./../include/footer.php');?>
    <script src="./../js/cart.js"></script>
</body>
</html>