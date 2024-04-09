<?php require_once('./../include/remember_me_control.php'); ?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>homepage</title>
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="../css/home.css">
</head>
<body class="defaultSettings">
    <?php require_once "./../include/navbar.php"; ?>
    <main>
        <div id="home1">
            <img id="homeMainImage" src="../contents/images/index1.png" alt="">
            <div class="homeDescription" id="homeMainDescription">
                <h1 style="font-size: xxx-large">wikiSAW</h1>
                <p class="fontXL">All what you need to know, and everything you need for learning it.</p>
            </div>
        </div>
        <div class="defaultFlex" id="home4">
            <p class="fontXL" id="partnersDescription">Trusted by more than 15,000 companies and millions of students around the world.</p>
            <div id="partnersLogos">
                <img class="partner" src="../contents/images/google.png" alt="Google's logo">
                <img class="partner" src="../contents/images/apple-logo.png" alt="Apple's logo">
                <img class="partner" src="../contents/images/samsung.png" alt="Samsung's logo">
                <img class="partner" src="../contents/images/sony.png" alt="Sony's logo">
                <img class="partner" src="../contents/images/nintendo.png" alt="Nintendo's logo">
                <img class="partner" src="../contents/images/cisco.png" alt="Cisco's logo">
            </div>
        </div>
        <div class="defaultFlex" id="home2">
            <div class="homeSection">
                <div class="homeDescription">
                    <h1 class="responsiveTitle">Sign up for free and become a student</h1>
                    <p class="fontXL">With wikiSAW you can start now learning interesting subjects. Sign up and discover our catalog.</p>
                </div>
                <img class="homeImages" src="../contents/images/student.png" alt="">
            </div>
            <div class="homeSection">
                <img class="homeImages" src="../contents/images/teacher.png" alt="">
                <div class="homeDescription">
                    <h1 class="responsiveTitle">Become a teacher and help our student to grow</h1>
                    <p class="fontXL">With wikiSAW you can become a teacher and make money from your video-courses.</p>
                        <?php
                        // Only if you are logged in, you can become a teacher
                        if (isset($_SESSION['login'])) {
                            echo '<a href="./teacher_rules.php" class="bold backColor2 teacherButton becomeTeacher">Become a teacher</a>';
                        } else {
                            echo '<a href="./login.php" class="bold backColor2 teacherButton becomeTeacher">Become a teacher</a>';
                        }
                        ?>
                </div>
            </div>
        </div>
        <div class="defaultGrid" id="home3">
            <div class="defaultFlex category">
                <a href="./catalog.php?category=Life">
                    <img class="categoryImage" src="../contents/images/life.png" alt="an illustration of a life balance">
                </a>
                <p class="fontXL">Life</p>
            </div>
            <div class="defaultFlex category">
                <a href="./catalog.php?category=Coding">
                    <img class="categoryImage" src="../contents/images/coding.png" alt="an illustration of a person coding">
                </a>
                <p class="fontXL">Coding</p>
            </div>
            <div class="defaultFlex category">
                <a href="./catalog.php?category=Science">
                    <img class="categoryImage" src="../contents/images/science.png" alt="an illustration of a scientist">
                </a>
                <p class="fontXL">Science</p>
            </div>
            <div class="defaultFlex category">
                <a href="./catalog.php?category=Automation">
                    <img class="categoryImage" src="../contents/images/automation.png" alt="an illustration of a machine">
                </a>
                <p class="fontXL">Automation</p>
            </div>
            <div class="defaultFlex category">
                <a href="./catalog.php?category=Languages">
                    <img class="categoryImage" src="../contents/images/languages.png" alt="an illustration of a girl speaking different languages">
                </a>
                <p class="fontXL">Languages</p>
            </div>
            <div class="defaultFlex category">
                <a href="./catalog.php?category=Photography">
                    <img class="categoryImage" src="../contents/images/photo.png" alt="an illustration of a photographer taking a picture">
                </a>
                <p class="fontXL">Photography</p>
            </div>
            <div class="defaultFlex category">
                <a href="./catalog.php?category=Music">
                    <img class="categoryImage" src="../contents/images/musica.png" alt="an illustration of a music band">
                </a>
                <p class="fontXL">Music</p>
            </div>
            <div class="defaultFlex category">
                <a href="./catalog.php?category=Business">
                    <img class="categoryImage" src="../contents/images/business.png" alt="an illustration of a business man">
                </a>
                <p class="fontXL">Business</p>
            </div>
        </div>
    </main>
    <?php require_once ("./../include/footer.php");?>
</body>
</html>