<?php
require "db.php";

// filter and search
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$search_term = mysqli_real_escape_string($connection, $search);

$filters_query = "SELECT DISTINCT Filter FROM idm232_recipe_sheet ORDER BY Filter";
$filters_result = mysqli_query($connection, $filters_query);

if (!$filters_result) {
    die("Filter query failed: " . mysqli_error($connection));
}

$query = "SELECT * FROM idm232_recipe_sheet WHERE 1=1";

if ($filter !== '') {
    $safe_filter = mysqli_real_escape_string($connection, $filter);
    $query .= " AND Filter = '$safe_filter'";
}

if ($search !== '') {
    $query .= " AND (
        Title LIKE '%$search_term%' OR 
        Ingredients LIKE '%$search_term%' OR 
        Bio LIKE '%$search_term%'
    )";
}

$query .= " ORDER BY id ASC";

$result = mysqli_query($connection, $query);

if (!$result) {
    die("Recipe query failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Recipe Room</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <img class="index-cover" src="./images/indexcover.jpg" alt="Site Cover">

    <div class="header-row">
        <h1 class="index-header">Browse All Recipes</h1>

        <!-- search bar -->
        <form method="GET" class="search-bar">
            <input 
                type="text" 
                name="search" 
                placeholder="Search recipes..."
                value="<?= htmlspecialchars($search); ?>"
            >

            <?php if ($filter !== ''): ?>
                <input type="hidden" name="filter" value="<?= htmlspecialchars($filter); ?>">
            <?php endif; ?>

            <button type="submit">Search</button>
        </form>
    </div>

    <!-- filters -->
    <ul id="filters">
        <a 
            class="filter-btn <?= ($filter === '') ? 'active-filter' : '' ?>" 
            href="index.php"
        >
            All
        </a>

        <?php while ($row = mysqli_fetch_assoc($filters_result)): ?>
            <?php $f = $row['Filter']; ?>
            <a 
                class="filter-btn <?= ($filter === $f) ? 'active-filter' : '' ?>"
                href="index.php?filter=<?= urlencode($f); ?>"
            >
                <?= htmlspecialchars($f); ?>
            </a>
        <?php endwhile; ?>
    </ul>

    <!-- recipe cards -->
    <div class="grid-container">

        <?php 
        $found_any = false; 
        while ($recipe = mysqli_fetch_assoc($result)): 
            $found_any = true;
        ?>
            <div class="recipe-card">
                <a href="recipe-page.php?id=<?= $recipe['id']; ?>">
                    <img 
                        class="recipe-cover"
                        src="./images/<?= htmlspecialchars($recipe['Folder']); ?>/<?= htmlspecialchars($recipe['Recipe_Img']); ?>"
                        alt=""
                    >
                    <h2 class="recipe-head">
                        <?= htmlspecialchars($recipe['Title']); ?>
                    </h2>

                    <h4>
                        <?= htmlspecialchars($recipe['Bio']); ?>
                    </h4>
                </a>
            </div>

        <?php endwhile; ?>

        <!-- no results -->
        <?php if (!$found_any): ?>
            <p class="no-results">Sorry, no results found...</p>
        <?php endif; ?>

    </div>

</body>
</html>
