<?php
require_once('./../include/remember_me_control.php');
require_once ('./../include/tools.php');
?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>catalog</title>
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/catalog.css">
</head>
<body class="defaultSettings">
    <?php require_once ('./../include/navbar.php'); ?>
    <main class="totalMargin">
        <div class="start_space"></div>

        <h1>Choose a subject and start learning</h1>
        <h2>Categories</h2>
        <div class="defaultGrid bold" id="allCourses">
            <a href="./catalog.php?category=Life">
                <div class="defaultFlex category">
                    Life
                </div>
            </a>
            <a href="./catalog.php?category=Coding">
                <div class="defaultFlex category">
                    Coding
                </div>
            </a>
            <a href="./catalog.php?category=Science">
                <div class="defaultFlex category">
                    Science
                </div>
            </a>
            <a href="./catalog.php?category=Automation">
                <div class="defaultFlex category">
                    Automation
                </div>
            </a>
            <a href="./catalog.php?category=Languages">
                <div class="defaultFlex category">
                    Languages
                </div>
            </a>
            <a href="./catalog.php?category=Photography">
                <div class="defaultFlex category">
                    Photography
                </div>
            </a>
            <a href="./catalog.php?category=Music">
                <div class="defaultFlex category">
                    Music
                </div>
            </a>
            <a href="./catalog.php?category=Business">
                <div class="defaultFlex category">
                    Business
                </div>
            </a>
        </div>

        <?php
        require_once ('./../include/db_connection.php');
        $mysql = db_connection();

        // Checking if the category is set or not in GET

        // If it is not set show all courses
        if (!isset($_GET['category'])) {
            $query = "SELECT * from courses ORDER BY category";
            $res = $mysql->query($query);
            $allrow = $res->fetch_all(MYSQLI_ASSOC);
            $last_category = '';
            foreach ( $allrow as $row) {
                $current_category = $row['category'];
                if ($current_category != $last_category){
                    echo '<h3 class="categoryTitle">'.$row['category'].'</h3>';
                    echo '<hr>';
                    $last_category = $current_category;
                }

                echo '<a href="./course.php?id='.$row['id'].'" class = "course">'.
                     '<img class = "courseImage" src="./../contents/images/course_covers/'.$row['image'].'" alt="">'.
                     '<div class="courseInfo">'.
                     '<h4 class="courseTitle">'.$row['name'].'</h4>'.
                     '<p class="courseDescription">'.$row['description'].'</p>'.
                     '<div class="rating">';
                    $n = floor($row['rating']/2);
                    $r = $row['rating']%2;
                    $e = 5 - $n - $r;
                    for ($i=0;$i<$n;$i++){
                        echo '<i class="fa fa-star yellow" aria-hidden="true"></i>';
                    }
                    if ($r != 0) echo '<i class="fa fa-star-half-o yellow" aria-hidden="true"></i>';
                    for ($i=0;$i<$e;$i++){
                        echo '<i class="fa fa-star-o yellow" aria-hidden="true"></i>';
                    }
                    echo '</div>'.
                    '<span class="coursePrice">'.
                    '€ '. $row['price'].
                    '</span>'.
                    '</div>'.
                    '</a>';
            }
            // If it is set show all the courses of this particular category
        } else {
            $category = isCategory(test_input($_GET['category']));
            if($category === false){ 
                echo '<script>alert("Please select a real category!")</script>';
                exit();
            } else {
                $query = 'SELECT * from courses where category=?';
                $stmt = $mysql->prepare($query);
                $stmt->bind_param('s',$category);
                $stmt->execute();
                $res = $stmt->get_result();
                $allrow = $res->fetch_all(MYSQLI_ASSOC);
                echo '<h3 class="categoryTitle">'.$category.'</h3>';
                echo '<hr>';
                foreach ( $allrow as $row) {

                    echo '<a href="./course.php?id='.$row['id'].'" class = "course">'.
                        '<img class = "courseImage" src="./../contents/images/course_covers/'.$row['image'].'" alt="">'.
                        '<div class="courseInfo">'.
                        '<h4 class="courseTitle">'.$row['name'].'</h4>'.
                        '<p class="courseDescription">'.$row['description'].'</p>'.
                        '<div class="rating">';
                    $n = floor($row['rating']/2);
                    $r = $row['rating']%2;
                    $e = 5 - $n - $r;
                    for ($i=0;$i<$n;$i++){
                        echo '<i class="fa fa-star yellow" aria-hidden="true"></i>';
                    }
                    if ($r != 0) echo '<i class="fa fa-star-half-o yellow" aria-hidden="true"></i>';
                    for ($i=0;$i<$e;$i++){
                        echo '<i class="fa fa-star-o yellow" aria-hidden="true"></i>';
                    }
                    echo '</div>'.
                        '<span class="coursePrice">'.
                        '€ '. $row['price'].
                        '</span>'.
                        '</div>'.
                        '</a>';
                }
            }
        }
        ?>

    </main>
    <?php require_once ("./../include/footer.php");?>
</body>
</html>
