<?php
    require_once('./../include/remember_me_control.php');
    require_once ('./../include/tools.php');
    // Checking the id passed with GET
    if (isset($_GET['id'])) $id = test_int($_GET['id']);
    else $id = -1;
?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>course</title>
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/course.css">
</head>
<body class="defaultSettings">
    <?php require_once ('./../include/navbar.php'); ?>
    <div class = "start_space"></div>
    <main class="defaultFlex-column">
        <h1 id ="title">Loading</h1>
        <img id="course_img" alt="" src="./../contents/images/DefSearch.png">
        <iframe id="video" class="hidden" width="560" height="315" src="" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        <div class="fullwidth flex-space-around">
            <div class="defaultFlex-column sameBase">
                <h3 class="column-responsive"> Rating:
                    <span>
                        <?php for ($i = 0; $i<5; $i++) echo '<i class="fa fa-star-o yellow rating_stars" aria-hidden="true"></i>';?>
                    </span>
                </h3>
                <h3 class="column-responsive">Difficulty: <span id="difficulty"></span></h3>
            </div>
            <div class="sameBase defaultFlex">
                <button id="addtocart" class="backColor2 courseButton"> Add to cart </button>
            </div>
            <div class="sameBase">
                <h3 class="column-responsive">Price: <span id="price"></span></h3>
            </div>
        </div>
        <div class= "flex-space-around courseInformations">
            <div class="defaultFlex-column">
                <h4 class="text-center">Teacher</h4>
                <img id="teacher_img" class= "teacherImg" src="../contents/images/anonim_user.jpg" alt="default user image">
                <h4 id="teacher_info" class="text-center"> Name and surname</h4>
            </div>
            <div class="defaultFlex-column">
                <h4> Description</h4>
                <p id="course_description">Loading</p>
            </div>
        </div>
        <hr id="hr_hidden" class="line hidden">
            <fieldset id="fieldset" class="field hidden">
                <legend> Leave a review </legend>
            <form class="flex-space-around review_form" action="./../include/submit_reviews.php" method="get">
            <input type="hidden" name="id" value="<?php echo $id;?>">
            <div class="defaultFlex rate_container">
                <label for="form_rating">Rating:</label>
                <select id="form_rating" name="rating">
                    <?php
                    for ($i=0.5; $i<=5; $i=$i+0.5){
                        echo '<option value='.$i.'>'.$i.'</option>';
                    };
                    ?>
                </select>     
            </div>
                <textarea class="text_review" placeholder="write something here.." name="comment" required aria-label="text area for review"></textarea>
                <div class="defaultFlex">
                    <button type="submit" class="backColor2 courseButton" id="submit_button">submit</button>
                </div>
            </form>
            </fieldset>
        <hr class="line">
        <div class="reviews flex-space-around">
            <h3 id="rating_num">Rating: </h3>
        </div>
        <div class="latest_review">
            <h4 id="l_r_label" class="latest_review_label"> Latest review: </h4>
            <div id="reviews_div">
                <hr class="review_line">
                <div class="defaultFlex fullwidth">
                    <div class="r_stars">
                        <?php for ($i = 0; $i<5; $i++) echo '<i class="fa fa-star-o yellow review_stars" aria-hidden="true"></i>';?>
                    </div>
                    <div class="defaultFlex-column comment">
                            <h5 class="comment_label">Comment:</h5>
                            <p id="comment">Amazing!</p>
                    </div>
                </div>
                <div class="flex-space-around fullwidth">
                    <h5 id="author">Author: </h5>
                    <h5 id="date">gg/mm/aaaa</h5>
                </div>
                
                <hr class="review_line">
            </div>
            <div class="defaultFlex">
                <button id="showallreviews" class="backColor2"> Show all reviews </button>
            </div>
        </div>
        <div class="start_space"></div>
    </main>
    <?php require_once('./../include/footer.php');?>
    <script> id = <?php echo $id; ?>; </script>
    <script src="./../js/course.js"></script>
</body>
</html>

<?php
    // If the message is set in GET, you have tried to do more than one review
    if (isset($_GET['message']))
        echo '<script>alert("You can only do one review for each course")</script>';
?>