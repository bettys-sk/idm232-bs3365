<?php
require "db.php";

$stmt = $pdo->query("SELECT * FROM idm232_recipe_sheet ORDER BY id ASC");
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$filter = $_GET['filter'] ?? null;
$search = $_GET['search'] ?? null;

$sql = "SELECT * FROM idm232_recipe_sheet WHERE 1=1";
$params = [];

// FILTER
if ($filter) {
    $sql .= " AND Filter = ?";
    $params[] = $filter;
}

// SEARCH
if ($search) {
    $sql .= " AND (Title LIKE ? OR Ingredients LIKE ? OR Bio LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$filterQuery = $pdo->query("SELECT DISTINCT Filter FROM idm232_recipe_sheet ORDER BY Filter");
$filters = $filterQuery->fetchAll(PDO::FETCH_COLUMN);
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
    
    <img class="index-cover" src="./images/indexcover.png" alt="">

<div class="header-row">
    <h1 class="index-header">Browse Recipes</h1>

    <form method="GET" class="search-bar">
        <input 
            type="text" 
            name="search" 
            placeholder="Search recipes..." 
            value="<?= $search ? htmlspecialchars($search) : '' ?>"
        >
        <button type="submit">Search</button>
    </form>
</div>

    <ul id="filters">

        <!-- Show ALL button -->
            <a class="filter-btn <?= $filter ? '' : 'active-filter' ?>" href="index.php">All</a>

        <!-- Dynamic filter buttons -->
        <?php foreach ($filters as $f): ?>
                <a class="filter-btn <?= ($filter === $f) ? 'active-filter' : '' ?>"
                href="index.php?filter=<?= urlencode($f) ?>">
                <?= htmlspecialchars($f) ?>
                </a>
        <?php endforeach; ?>

    </ul>

    <div class="grid-container">

        <?php if (count($recipes) === 0): ?>
            <p class="no-results">No results found...</p>
        <?php else: ?>
            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe-card">
                    <a href="recipe-page.php?id=<?= $recipe['id'] ?>">
                        <img class="recipe-cover" 
                            src="./images/<?= htmlspecialchars($recipe['Folder']) ?>/<?= htmlspecialchars($recipe['Recipe_Img']) ?>">
                        <h2 class="recipe-head"><?= htmlspecialchars($recipe['Title']) ?></h2>
                        <h4><?= htmlspecialchars($recipe['Bio']) ?></h4>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

</body>
</html>

<script>
    // Check if this is the user's first visit to the page
    if (!sessionStorage.getItem('visitedBefore')) {
        // Mark as visited
        sessionStorage.setItem('visitedBefore', 'true');
    } else {
        // User has visited before -> remove fade animation
        document.body.classList.add('no-animate');
    }
</script>