<?php
$host = "localhost";
$port = "8888";      // MAMP MySQL port
$user = "root";      // MAMP default
$pass = "root";      // MAMP default
$dbname = "idm232";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>