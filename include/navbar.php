<!-- Website navigation bar -->
<link rel="stylesheet" href="./../contents/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="./../css/general.css">
<link rel="stylesheet" href="./../css/navbar.css">
<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    } 
?>
<nav class="defaultGrid">
    <div id="leftNav" class="defaultFlex">
        <!-- Responsive navigation button -->
        <div class="dropdown">
            <button id="dropButton" aria-label="drop down menu"><i class="fa fa-bars" aria-hidden="true"></i></button>
            <div id="dropdownContent">
                <a class="navA" href="./home.php">HOME</a>
                <a class="navA" href="./catalog.php">CATALOG</a>
                <a class="navA" href="./about.php">ABOUT</a>
                <a class="navA" href="./contact.php">CONTACT</a>
                <?php
                // If you are logged in, you will see this link
                if (isset($_SESSION['login'])) {
                    // If you are not a teacher you will be redirected to the teacher rules
                    if ($_SESSION['teacher'] === 0) {
                        echo '<a class="navA" href="./teacher_rules.php">TEACHER</a>';
                    // If you are a teacher you will be redirected to you dashboard
                    } else if ($_SESSION['teacher'] === 1) {
                        echo '<a class="navA" href="./teacher_dashboard.php">TEACHER</a>';
                    }
                }
                ?>
            </div>
        </div>
        <a href="./home.php"><span class="wiki">wiki</span><span class="saw">SAW</span></a>
    </div>
    <div class="defaultFlex" id="middleNav">
        <a class="navA" href="./home.php">HOME</a>
        <a class="navA" href="./catalog.php">CATALOG</a>
        <a class="navA" href="./about.php">ABOUT</a>
        <a class="navA" href="./contact.php">CONTACT</a>
        <?php
        // If you are logged in, you will see this link
        if (isset($_SESSION['login'])) {
            // If you are not a teacher you will be redirected to the teacher rules
            if ($_SESSION['teacher'] === 0) {
                echo '<a class="navA" href="./teacher_rules.php">TEACHER</a>';
            // If you are a teacher you will be redirected to you dashboard
            } else if ($_SESSION['teacher'] === 1) {
                echo '<a class="navA" href="./teacher_dashboard.php">TEACHER</a>';
            }
        }
        ?>
    </div>
    <div class="defaultFlex" id="rightNav">
            <form class="defaultFlex" id="searchContainer" action="./search.php" method="get">
                <input type="text" id="searchBar" name="query" placeholder="Search..." aria-label="searchBar">
                <button type="submit" id="searchButton" class="searchButton" aria-label="Search"><i class="fa fa-search fa-lg" aria-hidden="true" id="searchIcon"></i></button>
            </form>
        <div class="defaultFlex" id="logContainer">
            <?php
                // If you are logged in you will see different icons
                if (isset($_SESSION['login'])) {
                    // If you are an admin you will see the icon for your dashboard
                    if ($_SESSION['admin'] == 1) {
                        echo '<a class="defaultFlex navIcon" href="./admin.php" aria-label="admin"><i class="fa fa-table fa-lg" aria-hidden="true"></i></a>'.
                            '<a class="defaultFlex navIcon" href="./show_profile.php"aria-label="show profile"><i class="fa fa-user-circle fa-lg" aria-hidden="true"></i></a>'.
                            '<a class="defaultFlex navButton" href="./../php/logout.php">Logout</a>';
                    // If you are a normal user/teacher you will see the cart
                    } else {
                        echo '<a class="defaultFlex navIcon" href="./cart.php" aria-label="cart"><i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i></a>'.
                            '<a class="defaultFlex navIcon" href="./show_profile.php" aria-label="admin"><i class="fa fa-user-circle fa-lg" aria-hidden="true"></i></a>'.
                            '<a class="defaultFlex navButton" href="./../php/logout.php">Logout</a>';
                    }
                // If you are not logged in you will see the buttons for sign up and sign in
                } else {
                        echo '<a class="defaultFlex navButton" href="./login.php">Sign In</a>'.
                            '<a class="defaultFlex navButton" href="./registration.php">Sign Up</a>';
                }
            ?>
        </div>
    </div>
</nav>

<script src="./../js/navbar.js"> </script>