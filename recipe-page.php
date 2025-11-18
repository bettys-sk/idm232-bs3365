<?php
require "db.php";

// Make sure an ID was provided
if (!isset($_GET['id'])) {
    die("No recipe selected.");
}

$id = intval($_GET['id']);

// Fetch recipe from database
$stmt = $pdo->prepare("SELECT * FROM idm232_recipe_sheet WHERE id = ?");
$stmt->execute([$id]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle missing recipe
if (!$recipe) {
    die("Recipe not found.");
}

// Parse images
$folder = htmlspecialchars($recipe['Folder']);
$final_img = htmlspecialchars($recipe['Recipe_Img']);
$ingredients_img = htmlspecialchars($recipe['Ingredients_Img']);

// Steps images
$steps_img_list = preg_split("/\r\n|\n|\r/", $recipe['Steps_Img'], -1, PREG_SPLIT_NO_EMPTY);

// Steps text
$steps_text_list = preg_split("/\r\n|\n|\r/", $recipe['Recipe'], -1, PREG_SPLIT_NO_EMPTY);

// Find last recipe ID
$maxIdQuery = $pdo->query("SELECT MAX(id) FROM idm232_recipe_sheet");
$maxId = $maxIdQuery->fetchColumn();
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

        <div class="nav-group">
        <?php if ($id > 2): ?>
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

    <!-- HEADER SECTION -->
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

    <!-- INGREDIENTS SECTION -->
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

    <!-- STEP SECTIONS -->
    <?php foreach ($steps_text_list as $index => $step_text): ?>
        <div class="recipe-section">
            <div class="recipe-text">
                <h2>Step <?= ($index + 1) ?></h2>
                <p class="recipe-des"><?= nl2br(htmlspecialchars($step_text)) ?></p>
            </div>

            <div class="recipe-image">
                <?php if (isset($steps_img_list[$index])): ?>
                    <img class="recipe-img" 
                        src="./images/<?= $folder ?>/<?= htmlspecialchars($steps_img_list[$index]) ?>"
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