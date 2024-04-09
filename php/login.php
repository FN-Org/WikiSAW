<?php
    require_once('./../include/remember_me_control.php');
    require_once ('./../include/tools.php');

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // If you are logged in you can directly see your profile
    if(isset($_SESSION['login'])){
            header('Location: ./show_profile.php');
    }
?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="./../contents/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/form.css">
    <link rel="stylesheet" href="./../css/login.css">

    <script src="./../js/utility.js"></script>
</head>
<body class="defaultSettings defaultFlex backColor1">
    <div class="defaultFlex loginBackSize formBackGround" id="formContainer">
        <div class="backButton" aria-label="Back" role="button">
            <i class="fa fa-arrow-left" id="arrow" aria-hidden="true"></i>
        </div>
        <a href="./../index.php" class="homeButton" aria-label="Home" role="button">
            <i class="fa fa-home" id="home" aria-hidden="true"></i>
        </a>
        <h1 style="margin-top: 3rem;"><span class="wiki">wiki</span><span class="saw">SAW</span></h1>
        <h2 style="margin-top: 0;">Login</h2>
        <form class="defaultFlex loginFormSize generalForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="labeledInput">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Type your email" required>
            </div>
            <div class="labeledInput">
                <label for="pass">Password</label>
                <input type="password" id="pass" name="pass" placeholder="Type your password" required>
            </div>
            <div class="defaultFlex fullwidth">
                <label for="rememberme" style="margin-right: 0.5rem">Remember me</label>
                <input type="checkbox" id="rememberme" name="rememberme" value="choice">
            </div>
            <button type="submit" class="backColor2 formButton" id="loginButton">LOGIN</button>
        </form>
        <p class="sentence">You don't have an account yet</p>
        <a href="./registration.php" class="formRedirect">SIGN UP</a>
    </div>

<script src="./../js/login.js"></script>

</body>
</html>

<?php
    $email = $password = "";

    // Checking if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $email = test_email($_POST['email']);
        $password = $_POST['pass'];
        if(isset($_POST['rememberme'])) $remember = $_POST['rememberme'];

        // login function
        $authentication = login($email, $password);

        // If the login function returns 1, it means
        // that the user can log in
        // else if, manage the errors
        if ($authentication === 1) {
            $_SESSION['login'] = true;

            // Remember me
            if ($remember) {
                rememberMeSet($email);
            }
            header('Location: ./show_profile.php');
        }
        else if ($authentication === 2)
        {
            echo '<script type="text/javascript"> error("Email or password not correct.") </script>';
            session_destroy();
        }
        else if ($authentication === 3)
        {
            echo '<script type="text/javascript"> error("You are banned!") </script>';
            session_destroy();
        }
        else {
            echo '<script type="text/javascript"> error("Something went wrong, try again.") </script>';
            session_destroy();
        }
    }

    /*
    * Function for checking if the email and password passed on POST
    * are in the database:
    * Return 1: if everything goes right and set all the SESSION variables
    * Return 2: if the credentials are wrong
    * Return 3: if you are banned
    * Return -1: for database errors
    */
    function login($us,$pw) {
        // Database connection
        $mysql = db_connection();
        if (!$mysql) {
            error_log('Login.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
            return 0;
        }
        else {
            $query = 'SELECT * from users where email=?';
            $stmt = $mysql->prepare($query);
            $stmt->bind_param('s',$us);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows > 1) {
                error_log("Login.php - the selection, during login, went wrong on query: $query\n", 3, './../../error_log.txt');
                return -1;
            }
            else if ($res->num_rows < 1){
                return 2;
            }
            $row = $res->fetch_assoc();
            $stmt->close();
            $mysql->close();

            // If the password passed in POST matches the hashed password
            // in the database, and if you are not banned,
            // set all the SESSION variables
            if (password_verify($pw,$row['password'])) {
                if ($row['ban']) return 3;
                $_SESSION['f_name'] = $row['first_name'];
                $_SESSION['l_name'] = $row['last_name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['birth'] = $row['birth'];
                $_SESSION['mobile'] = $row['mobile'];
                $_SESSION['bio'] = $row['bio'];
                $_SESSION['teacher'] = $row['teacher'];
                $_SESSION['admin'] = $row['admin'];
                $_SESSION['newsletter'] = $row['newsletter'];
                return 1;
            }
            else {
                return 2;
            }
        }
    }

    /*
     * Create a cookie for the remember me using
     * the hashed email of the user. Set the cookie's time
     * expiration in one week.
     */
    function rememberMeSet($email) {
        $cookie_value = password_hash($email, PASSWORD_DEFAULT);
        $time = time() + (86400 * 7);
        setcookie('rememberme', $cookie_value, $time, "/");
        $mysql = db_connection();
        if (!$mysql) {
            error_log('Login.php - unable to access to the database during the cookie registration: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
        }

        // Query UPDATE to update the cookie id and expiration date
        $query = 'UPDATE users SET cookie_id=?, cookie_exp_date=? WHERE email=?';
        $stmt = $mysql->prepare($query);
        $stmt->bind_param('sis', $cookie_value, $time, $email);
        $stmt->execute();
        if ($stmt->affected_rows !== 1){
            error_log('Login.php - cookie update went wrong.', 3, './../../error_log.txt');
        }
        $stmt->close();
        $mysql->close();
    }
?>