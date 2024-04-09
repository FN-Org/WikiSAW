<?php require_once('./../include/remember_me_control.php'); ?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>about</title>
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/about.css">
</head>
<body class="defaultSettings">
    <?php require_once "./../include/navbar.php"; ?>
    <main class="defaultFlex viewport" id="aboutPage">
        <div class="start_space"></div>
        <img id="teamImage" src="../contents/images/team.png" alt="">
        <div class="defaultFlex" id="aboutInfo">
            <h1>About Us</h1>
            <p id="description">Welcome to wikiSAW, where learning knows no bounds! We are a small but passionate team dedicated to providing online courses that cover a diverse range of subjects, from sciences and automation to coding and photography. Committed to fostering a space for acquiring valuable hard skills, we believe in the transformative power of education. Our team has created this platform with a shared vision – to empower individuals on their learning journey. At wikiSAW, we stand by strong values, aiming to make quality education accessible to all and building a community driven by knowledge and growth.</p>
            <a class="defaultFlex backColor2" id="contactButton" href="./contact.php">CONTACT US</a>
        </div>
    </main>
    <?php require_once ("./../include/footer.php");?>
</body>
</html>
