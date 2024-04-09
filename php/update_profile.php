<?php
    require_once("./../include/session_control.php");
    require_once ('./../include/tools.php');
?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update profile</title>
    <link rel="stylesheet" href="./../contents/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/form.css">
    <link rel="stylesheet" href="./../css/update_profile.css">

    <script src="./../js/utility.js"></script>
</head>
<body class="defaultSettings defaultFlex backColor1">
    <div class="defaultFlex updateBackSize formBackGround" id="formContainer">
        <div class="backButton" role="button">
            <i class="fa fa-arrow-left" id="arrow" aria-hidden="true"></i>
        </div>
        <a href="./home.php" class="homeButton" aria-label="Home">
            <i class="fa fa-home" id="home" aria-hidden="true"></i>
        </a>
        <h1 id="title"><span class="wiki">wiki</span><span class="saw">SAW</span></h1>
        <h2 id="subtitle">Modify</h2>
        <img src="../contents/images/anonim_user.jpg" alt="default user image" srcset="">
        <form class="defaultFlex updateFormSize generalForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="defaultFlex">
                <div class="labeledInput upLabeledInput">
                    <label for="firstname">First name</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Type your name" required>
                </div>
                <div class="labeledInput upLabeledInput">
                    <label for="lastname">Last name</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Type your surname" required>
                </div>
            </div>
            <div class="defaultFlex fullwidth">
                <div class="labeledInput upLabeledInput">
                    <label for="birth">Birth</label>
                    <input type="date" id="birth" name="birth" required>
                </div>
                <div class="defaultFlex fullwidth">
                    <label for="newsletter">Newsletter</label>
                    <input type="checkbox" id="newsletter" name="newsletter">
                </div>
            </div>
            <div class="labeledInput upLabeledInput">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Type your email" required>
            </div>
            <div class="labeledInput upLabeledInput">
                <label for="mobile">Mobile</label>
                <input type="tel" id="mobile" name="mobile" placeholder="Type your telephone number" required>
            </div>
            <div class="labeledInput upLabeledInput">
                <label for="bio">Biography</label>
                <textarea id="bio" name="bio" placeholder="Write here a for description of yourself"></textarea>
            </div>
            <div class="labeledInput upLabeledInput">
                <label for="oldPassword">Old password</label>
                <input type="password" id="oldPassword" name="oldPassword" placeholder="Type your old password">
            </div>
            <div class="labeledInput upLabeledInput">
                <label for="newPassword">New password</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="Type your new password">
            </div>
            <div class="labeledInput upLabeledInput">
                <label for="confirmNewPassword">Confirm new password</label>
                <input type="password" id="confirmNewPassword" name="confirmNewPassword" placeholder="Type your new password">
            </div>
            <button type="submit" class="backColor2 formButton" id="changeButton">Save changes</button>
        </form>
    </div>

<script src="./../js/update_profile.js"></script>

</body>
</html>

<?php
    require_once ('./../include/db_connection.php');

    // Change personal information

    // Checking if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Database connection
        $mysql = db_connection();
        if (!$mysql) {
            error_log('update_profile.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../error_log.txt');
            exit();
        }
        else {
            $email = $_SESSION['email'];
            // SELECT query to select only the password from the DB
            $query = "SELECT password FROM users WHERE email = ?";
            $stmt = $mysql->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows != 1) {
                error_log('update_profile.php - SELECT query went wrong', 3, './../../error_log.txt');
                header('Location: ./show_profile.php?message=5');
                $stmt->close();
                $mysql->close();
                exit();
            }
            $realPsw = $res->fetch_assoc()['password'];
            $res->free();

            // First name, last name and email can't be empty
            if (empty(test_input(($_POST['firstname']))) || empty(test_input(($_POST['lastname']))) || empty(test_email(($_POST['email'])))) {
                error_log($_POST['firstname'] . $_POST['lastname'] . $_POST['email'] . $_POST['birth'] . $_POST['mobile'] . "\n", 3, './../../error_log.txt');
                header('Location: ./show_profile.php?message=6');
                exit();
            }

            // Birth and mobile can't be empty too, because they have a
            // regex to respect for the DB
            // Bypass test
            if (empty($_POST['birth'])) {
                $_POST['birth'] = '0001-01-01';
            }
            if (empty($_POST['mobile'])) {
                $_POST['mobile'] = '000-0000000';
            }

            // Set all the variables
            $newFirstname = test_input($_POST['firstname']);
            $newLastname = test_input($_POST['lastname']);
            $newBirth = isRealDate($_POST['birth']);
            $newMobile = isMobile($_POST['mobile']);
            $newEmail = test_email($_POST['email']);
            $newBio = test_input($_POST['bio']);

            if (isset($_POST['newsletter'])) $newsletter = 1;
            else $newsletter = 0;

            $hashedNewPsw = '';

            // isPasswordChanged function
            $isPasswordChanged = isPasswordChanged($_POST['oldPassword'], $_POST['newPassword'], $_POST['confirmNewPassword'], $realPsw);

            // Checking if something is changed
            if ($_SESSION['f_name'] != $newFirstname ||
                $_SESSION['l_name'] != $newLastname ||
                $_SESSION['birth'] != $newBirth ||
                $_SESSION['email'] != $newEmail ||
                $_SESSION['mobile'] != $newMobile ||
                $_SESSION['bio'] != $newBio ||
                (isset($newsletter) && ($_SESSION['newsletter'] != $newsletter)) ||
                $isPasswordChanged)
            {
                // If the user wants to change the password
                if (!empty($hashedNewPsw)){
                    try {
                        // Try UPDATE query with password field
                        $query = "UPDATE users SET first_name = ?, last_name = ?, birth = ?, newsletter = ?, email = ?, mobile = ?, bio = ?, password = ? WHERE email = ?";
                        $stmt = $mysql->prepare($query);
                        $stmt->bind_param('sssisssss', $newFirstname, $newLastname, $newBirth, $newsletter, $newEmail, $newMobile, $newBio, $hashedNewPsw, $email);
                        $stmt->execute();
                        if ($stmt->affected_rows != 1) {
                            error_log('update_profile.php - the UPDATE went wrong',3, './../../error_log.txt');
                            header('Location: ./show_profile.php?message=5');
                            exit();
                        }
                        if ($mysql->errno) {
                            header('Location: ./show_profile.php?message=4');
                            exit();
                        }
                        $stmt->close();
                    // Catching all the exceptions
                    } catch (mysqli_sql_exception $e) {
                        // Code 1062 stands for duplicated primary key
                        if ($e->getCode() == 1062) {
                            header('Location: ./show_profile.php?message=4');
                        } else {
                            header('Location: ./show_profile.php?message=5');
                        }
                        exit();
                    }
                    // If the user doesn't want to change the password
                } else {
                    try {
                        // Try UPDATE query without password field
                        $query = "UPDATE users SET first_name = ?, last_name = ?, birth = ?, newsletter = ?, email = ?, mobile = ?, bio = ? WHERE email = ?";
                        $stmt = $mysql->prepare($query);
                        $stmt->bind_param('sssissss', $newFirstname, $newLastname, $newBirth, $newsletter, $newEmail, $newMobile, $newBio, $email);
                        $stmt->execute();
                        if ($stmt->affected_rows != 1) {
                            error_log('update_profile.php - the UPDATE went wrong', 3, './../../error_log.txt');
                            header('Location: ./show_profile.php?message=5');
                            exit();
                        }
                        if ($mysql->errno) {
                            header('Location: ./show_profile.php?message=4');
                            exit();
                        }
                        $stmt->close();
                        // Catching all the exceptions
                    } catch (mysqli_sql_exception $e) {
                        // Code 1062 stands for duplicated primary key
                        if ($e->getCode() == 1062) {
                            header('Location: ./show_profile.php?message=4');
                        } else {
                            header('Location: ./show_profile.php?message=5');
                        }
                        exit();
                    }
            }

                // Profile updated successfully!
                $mysql->close();
                // Update all the SESSION variables
                $_SESSION['f_name'] = $newFirstname;
                $_SESSION['l_name'] = $newLastname;
                $_SESSION['email'] = $newEmail;
                $_SESSION['newsletter'] = $newsletter;
                $_SESSION['mobile'] = $newMobile;
                $_SESSION['birth'] = $newBirth;
                $_SESSION['bio'] = $newBio;
                header('Location: ./show_profile.php?message=1');
            } else {
                // Nothing has changed!
                header('Location: ./show_profile.php?message=2');
            }
            exit();
        }
    }

    /*
     * Return 1: all the password inputs are filled, the old password is right and
     * the new password is the same as the "confirm" one
     * Return 0: if some inputs are empty
     * Other: redirect to show profile with a message
     */
function isPasswordChanged($oldPsw, $newPsw, $confirm, $realPsw)
{
    if (!empty(trim($oldPsw)) && !empty(trim($newPsw)) && !empty(trim($confirm))) {
        if ($newPsw != $confirm) {
            header('Location: ./show_profile.php?success=3');
            exit();
        }
        if (password_verify($oldPsw, $realPsw)) {
            global $hashedNewPsw;
            $hashedNewPsw = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
            return 1;
        } else {
            header('Location: ./show_profile.php?message=3');
            exit();
        }
    }
    return 0;
}

?>
