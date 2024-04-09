<?php require_once('./../include/remember_me_control.php'); ?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>contact</title>
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/contact.css">
</head>
<body class="defaultSettings">
    <?php require_once ('./../include/navbar.php'); ?>
    <main class="defaultFlex viewport" id="contactMain">
        <div class="start_space"></div>
        <div id="contactContainer">
            <div class="contactInfo">
                <i class="fa fa-map-marker fa-2x" aria-hidden="true"></i>
                <span class="information">Via 25 Aprile, 73, 16100 Genova</span>
            </div>
            <div class="contactInfo">
                <i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
                <a href="mailto:s5182167@studenti.unige.it" class="information">s5182167@studenti.unige.it</a>
            </div>
            <div class="contactInfo">
                <i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
                <a href="mailto:s5187919@studenti.unige.it" class="information">s5187919@studenti.unige.it</a>
            </div>
            <div class="contactInfo">
                <i class="fa fa-phone fa-2x" aria-hidden="true"></i>
                <a href="tel:+39 3396074567" class="information">+39 3396074567</a>
            </div>
            <div class="contactInfo">
                <i class="fa fa-phone fa-2x" aria-hidden="true"></i>
                <a href="tel:+39 3426225747" class="information">+39 3426225747</a>
            </div>
        </div>
    </main>
    <?php require_once ("./../include/footer.php");?>
</body>
</html>
