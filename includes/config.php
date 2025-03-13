<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";
try {
    $pdo = new PDO(
        "mysql:host=$servername;dbname=$dbname",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8");
} catch (PDOException $e) {
    file_put_contents('error_log.txt', date('Y-m-d H:i:s') . " - Erreur PDO : " . $e->getMessage()
        . "\n", FILE_APPEND);
    die("Une erreur est survenue. Veuillez contacter l'administrateur.");
}
