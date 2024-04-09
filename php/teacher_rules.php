<?php
    require_once('./../include/session_control.php');
    if ($_SESSION['teacher'] == 1) {
        header("Location: ./teacher_dashboard.php");
    }
?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>teacher</title>
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="../css/teacher_rules.css">
</head>
<body class="defaultSettings">
    <?php require_once "./../include/navbar.php"; ?>
    <main class="defaultFlex">
        <div class="start_space"></div>

        <div class="backColor2 defaultFlex" id="container">
            <h1>Welcome to our Online Courses Platform!</h1>
            <h2>Become a teacher</h2>
            <p>Are you an expert in a particular field? Share your knowledge and passion by becoming a teacher on our platform. Reach a global audience, earn money, and make a difference in the lives of learners around the world.</p>

            <p>Join our community of educators and start creating and selling your online courses today!</p>
        </div>

        <div class="defaultFlex-column" id="to-do">
            <h2 id="title">What you need to do</h2>
            <?php
            // You must have all the profile information filled to be a teacher
            // all the user has to know everything about the teacher
            if ($_SESSION['mobile'] === '000-0000000' || $_SESSION['birth'] === '0001-01-01' || empty($_SESSION['bio'])) {
                $event = false;
                echo '<h3>Update your profile</h3>'.
                    '<p id="rule">To allow you to become a teacher we need more information about you, update your profile with more information such as you passions, your hobbies, your educations and skills. Click the button below and tell us more about you updating your profile!</p>'.
                    '<a id="teacherLink" href="./update_profile.php"><button class="teacherButton backColor2 bold">Update your profile</button></a>';
            } else {
                $event = true;
                echo '<h3>Easy!</h3>'.
                    '<p id="rule">Now you have just to push the button below, and finally you will become part of our platform as a new teacher</p>'.
                    '<button class="teacherButton backColor2 bold" id="becomeTeacher">Become a teacher</button>';
            }
            $json = json_encode($event);
            ?>
        </div>
    </main>
    <?php require_once ("./../include/footer.php");?>

<script> let bool = JSON.parse('<?php echo $json?>'); </script>
<script src="./../js/teacher_rules.js"></script>

</body>
</html>
