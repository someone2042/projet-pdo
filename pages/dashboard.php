<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h2>Bienvenue, <?php echo $_SESSION['user']; ?> !</h2>
    <a href="../logout.php">Déconnexion</a>
</body>

</html>