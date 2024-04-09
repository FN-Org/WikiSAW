<?php
    require_once ('./../include/remember_me_control.php');
    require_once ('./../include/session_control.php');
?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user profile</title>
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/show_profile.css">
</head>
<body class="defaultSettings">
    <?php require_once ('./../include/navbar.php') ?>
    <main class="defaultFlex" id="mainContainer">
        <div class="start_space"></div>
        <h1>User Profile</h1>
        <?php
            // Managing all the different errors from the update_profile.php
            $message = isset($_GET['message']) ? $_GET['message'] : null;

            if ($message == 1) {
                echo '<h6 class="success">Profile updated successfully!</h6>';
            } else if ($message == 2) {
                echo '<h6 class="success">Nothing has changed</h6>';
            } else if ($message == 3) {
                echo '<h6 class="error">Update failed, check your password and try again</h6>';
            } else if ($message == 4) {
                echo '<h6 class="error">Sorry, email already used, try with a different one</h6>';
            } else if ($message == 5) {
                echo '<h6 class="error">Something went wrong, try again</h6>';
            } else if ($message == 6) {
                echo '<h6 class="error">Please fill all the required input</h6>';
            } else if ($message == 7) {
                echo '<h6 class="error">Check your telephone number and try again</h6>';
            } else if ($message == 8) {
                echo '<h6 class="error">You are not able to access this page!</h6>';
            }
        ?>
        <i id="user_icon" class="fa fa-graduation-cap fa-3x" aria-hidden="true"></i>
        <div class="defaultGrid table">
            <div id="f_name">
                <h4>First name</h4>
                <p id="profile_info"></p>
            </div>
            <div id="l_name">
                <h4>Last name</h4>
                <p id="profile_info"></p>
            </div>
            <div id="birth">
                <h4>Birth</h4>
                <p id="profile_info"></p>
            </div>
            <div id="newsletter">
                <h4>Newsletter</h4>
                <p id="profile_info"></p>
            </div>
            <div id="email">
                <h4>Email</h4>
                <p id="profile_info"></p>
            </div>
            <div id="mobile">
                <h4>Mobile</h4>
                <p id="profile_info"></p>
            </div>
            <div id="bio">
                <h4>Biography</h4>
                <p id="profile_info"></p>
            </div>
        </div>
        <br>
        <a href="./update_profile.php" class="defaultFlex backColor2 infoButton">Change your personal info</a>
        <div class ="defaultFlex" id="deleteProfileButton" role="button">Delete profile</div>
        <hr style="width: 95%">
        <h2 id="start">My Courses</h2>
    </main>
    <?php require_once ("./../include/footer.php");?>

    <?php
    // Fill all the user information
    // We use this code just for passing all the automatic tests
    // otherwise we would have used fetch with JS
    require_once ('./../include/session_ctr_include.php');
    require_once('./../include/db_connection.php');
    $response = array();
    // Database connection
    $mysql = db_connection();
    $response['error'] = false;
    if (!$mysql) {
        error_log('user_info_loading.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, 'error_log.txt');
        $response['error'] = true;
    }
    else {
        $email = $_SESSION['email'];
        // SELECT query to select all the user information
        $query = "SELECT first_name, last_name, email, birth, mobile, bio, newsletter, teacher, admin FROM users WHERE email= ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows != 1){
            error_log('user_info_loading.php - SELECT query went wrong', 3, './../../error_log.txt');
            $response['error'] = true;
        } else {
            $response['row'] = $res->fetch_assoc();
        }
        $stmt->close();
        $mysql->close();
    }
    // Create the json element
    $json_data = json_encode($response);
    ?>

<script src="./../js/show_profile.js"></script>
<script>
    // Parsing the json from PHP and using the show_profile function
    let jsObject = JSON.parse('<?php echo str_replace("'","\'",$json_data)?>');
    show_profile(jsObject.row);
</script>
</body>
</html>