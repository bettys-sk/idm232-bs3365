<?php

require_once 'config.php'

$sql = 'SELECT id, name, email, age FROM users'
$result = $connection->query($sql);

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php 
        if ($result->num_rows > 0) :
?>

        <table>
            <tr>

                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>

            </tr>

            <?php
                while ($row = $result->fetch_assoc()) ?>

            <tr>
                <td><?php echo $row['id'] ?></td>
                <td>Name</td>
                <td>Email</td>
                <td>Age</td>
            </tr>
            <?php endwhile; ?>
        </table>
</body>
</html>