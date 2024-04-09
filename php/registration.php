<?php
require_once('./../include/remember_me_control.php');
require_once ('./../include/tools.php');
?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registration</title>
    <link rel="stylesheet" href="./../contents/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/form.css">
    <link rel="stylesheet" href="./../css/registration.css">

    <script src="./../js/utility.js"></script>
</head>
<body class="defaultSettings defaultFlex backColor1">
    <div class="defaultFlex registrationBackSize formBackGround" id="formContainer">
        <div class="backButton" role="button">
            <i class="fa fa-arrow-left" id="arrow" aria-hidden="true"></i>
        </div>
        <a href="./home.php" class="homeButton" aria-label="home">
            <i class="fa fa-home" id="home" aria-hidden="true"></i>
        </a>
        <h1 style="margin-top: 3rem;"><span class="wiki">wiki</span><span class="saw">SAW</span></h1>
        <h2 style="margin-top: 0;">Registration</h2>
        <form class="defaultFlex registrationFormSize generalForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="labeledInput">
                <label for="firstname">First name</label>
                <input type="text" id="firstname" name="firstname" placeholder="Type your name" required>
            </div>
            <div class="labeledInput">
                <label for="lastname">Last name</label>
                <input type="text" id="lastname" name="lastname" placeholder="Type your surname" required>
            </div>
            <div class="labeledInput">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Type your email" required>
            </div>
            <div class="labeledInput">
                <label for="pass">Password</label>
                <input type="password" id="pass" name="pass" placeholder="Type your password" required>
            </div>
            <div class="labeledInput">
                <label for="confirm">Confirm password</label>
                <input type="password" id="confirm" name="confirm" placeholder="Confirm your password" required>
            </div>
            <button type="submit" class="backColor2 formButton" id="registrationButton">CREATE ACCOUNT</button>
        </form>
        <p class="sentence">I already have an account</p>
        <a href="login.php" class="formRedirect">SIGN IN</a>
    </div>

<script src="./../js/registration.js"></script>

</body>
</html>

<?php
// Checking if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo register();
}
    
// register function: test the input and return a message for errors or success
function register()
{
    if (empty(test_input($_POST["firstname"])) || empty(test_input(($_POST["lastname"]))) || empty(test_email($_POST["email"]))) {
        return '<script type="text/javascript"> error("Missing input") </script>';
    } else {
        $fn = test_input($_POST["firstname"]);
        $ln = test_input($_POST["lastname"]);
        $e = test_email($_POST["email"]);
    }
    if (empty($_POST['pass']) || empty($_POST['confirm'])) {
        return '<script type="text/javascript"> error("Missing password") </script>';
    }
    $p = $_POST['pass'];
    // The password must have at least 8 characters
    if (strlen($p) < 8) {
        return '<script type="text/javascript"> error("Password must have at least 8 characters") </script>';
    }

    $c = $_POST['confirm'];
    if ($p != $c) {
        return '<script type="text/javascript"> error("Passwords doesn\'t match") </script>';
    }
    $hashedpw = password_hash($p, PASSWORD_DEFAULT);

    // Database connection
    $mysql = db_connection();
    if (!$mysql) {
        error_log('registration.php - unable to access to the database: ' . mysqli_connect_error() . '\n', 3, './../../error_log.txt');
    } else {
        // Try INSERT query
        try {
            $query = "INSERT INTO users (first_name,last_name,email,password,birth,mobile,bio,admin,newsletter,ban,cookie_id,cookie_exp_date) VALUES (?,?,?,?,'0001-01-01','000-0000000','',0,0,0,0,0);";
            $stmt = $mysql->prepare($query);
            $stmt->bind_param('ssss', $fn, $ln, $e, $hashedpw);
            $stmt->execute();
            if ($stmt->affected_rows != 1) {
                error_log('registration.php - the INSERT went wrong', 3, './../../error_log.txt');
                return '<script type="text/javascript"> error("Something went wrong, please try again") </script>';
            }
            if ($mysql->errno) {
                return '<script type="text/javascript"> error("Email already used, try with a different one") </script>';
            }
            $stmt->close();
            // Catch all the exceptions
        } catch (mysqli_sql_exception $e) {
            // Code 1062 stands for duplicated primary key
            if ($e->getCode() == 1062) {
                return '<script type="text/javascript"> error("Email already used, try with a different one") </script>';
            } else {
                return '<script type="text/javascript"> error("Something is broken, sorry") </script>';
            }
        }
    }
    $mysql->close();
    return '<script type="text/javascript"> 
            const successMessage = document.createElement(\'h6\');
            successMessage.classList.add(\'success\');
            successMessage.textContent = "Success!";
            const el = document.getElementById(\'formContainer\');
            el.insertBefore(successMessage, document.querySelector(\'h2\').nextSibling)
            </script>';
}
?>