<?php
require "db.php";

if (!isset($_GET['id'])) {
    die("No recipe selected.");
}

$id = intval($_GET['id']);

// this fetches all the recipes
$query = "SELECT * FROM idm232_recipe_sheet WHERE id = $id";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}
$recipe = mysqli_fetch_assoc($result);
if (!$recipe) {
    die("Recipe not found.");
}

// parse all images
$folder = htmlspecialchars($recipe['Folder']);
$final_img = htmlspecialchars($recipe['Recipe_Img']);
$ingredients_img = htmlspecialchars($recipe['Ingredients_Img']);

$steps_img_list = preg_split("/\r\n|\n|\r/", trim($recipe['Steps_Img']), -1, PREG_SPLIT_NO_EMPTY);

$steps_text_list = preg_split("/\r\n|\n|\r/", trim($recipe['Recipe']), -1, PREG_SPLIT_NO_EMPTY);

// navigation button function in recipe page header
$maxQuery = "SELECT MAX(id) AS max_id FROM idm232_recipe_sheet";
$maxResult = mysqli_query($connection, $maxQuery);
$maxRow = mysqli_fetch_assoc($maxResult);
$maxId = $maxRow['max_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($recipe['Title']) ?></title>
    <link rel="stylesheet" href="./style.css">
</head>

<body class="recipe-page-margin">
    
    <div class="nav-bar">
        <div>
            <a class="nav-link" href="index.php">
                <img class="nav-icon" src="./images/home.png" alt="">
                <h3>Back to Recipe Room</h3>
            </a>
        </div>

        <!-- show previous/next navigation based on the id of the recipe -->
        <div class="nav-group">
        <?php if ($id > 1): ?>
            <a class="nav-link nav-enabled" href="recipe-page.php?id=<?= $id - 1 ?>">
                <img class="nav-icon" src="./images/left-arrow.png" alt="">
                <h3>Previous</h3>
            </a>
        <?php endif; ?>
        </div>

        <div>
            <?php if ($id < $maxId): ?>
                <a class="nav-link" href="recipe-page.php?id=<?= $id + 1 ?>">
                    <h3>Next</h3>
                    <img class="nav-icon" src="./images/right-arrow.png" alt="">
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- header -->
    <div class="recipe-section">
        <div class="recipe-text">
            <h1><?= htmlspecialchars($recipe['Title']) ?></h1>

            <div class="recipe-time">
                <img class="nav-icon" src="./images/time.png" alt="">
                <h5><?= htmlspecialchars($recipe['Cook_Time']) ?></h5>
            </div>

            <p class="recipe-des">
                <?= nl2br(htmlspecialchars($recipe['Bio'])) ?>
            </p>
        </div>

        <div class="recipe-image">
            <img class="recipe-img" src="./images/<?= $folder ?>/<?= $final_img ?>" alt="">
        </div>
    </div>

    <hr>

    <!-- ingredients-->
    <div class="recipe-section">
        <div class="recipe-text">
            <h2>Ingredients</h2>
            <p class="recipe-des">
                <?= nl2br(htmlspecialchars($recipe['Ingredients'])) ?>
            </p>
        </div>

        <div class="recipe-image">
            <img class="recipe-img" src="./images/<?= $folder ?>/<?= $ingredients_img ?>" alt="">
        </div>
    </div>

    <hr>

    <!-- steps -->
    <?php foreach ($steps_text_list as $index => $step_text): ?>
        <div class="recipe-section">
            <div class="recipe-text">
                <h2>Step <?= ($index + 1) ?></h2>
                <p class="recipe-des"><?= nl2br(htmlspecialchars(trim($step_text))) ?></p>
            </div>

            <div class="recipe-image">
                <?php if (!empty($steps_img_list[$index])): ?>
                    <img class="recipe-img" 
                        src="./images/<?= $folder ?>/<?= htmlspecialchars(trim($steps_img_list[$index])) ?>"
                        alt="">
                <?php endif; ?>
            </div>
        </div>

        <?php if ($index < count($steps_text_list) - 1): ?>
            <hr>
        <?php endif; ?>

    <?php endforeach; ?>

</body>
</html>
