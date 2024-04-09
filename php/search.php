<?php
    require_once('./../include/remember_me_control.php');
    require_once ('./../include/tools.php');
    // Checking if the query in GET is set
    // Create a json element
    if (isset($_GET['query'])) {
        $query = test_input($_GET['query']);
        $json = json_encode($query);
    }
?>
<!DOCTYPE html>
<html class="defaultSettings" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>search</title>
    <link rel="stylesheet" href="./../css/general.css">
    <link rel="stylesheet" href="./../css/catalog.css">
    <link rel="stylesheet" href="./../css/cart.css">

    <script src="./../js/utility.js"></script>
</head>
<body class="defaultSettings">
<?php require_once "./../include/navbar.php"; ?>
<main class="totalMargin">
    <div class="start_space"></div>
    <h1 id="results" class="text-center">Results</h1>
    <hr>
    <div id="courses">

    </div>
</main>
<?php require_once ("./../include/footer.php");?>

<!-- Parsing the json element -->
<script> let request = JSON.parse('<?php echo str_replace("'", "\'", $json) ?>'); </script>
<script src="./../js/search.js"></script>

</body>
</html>
