<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h2>Bienvenue, <?php echo $_SESSION['user']; ?> !</h2>

    <?php if ($isAdmin): ?>
        <h3>Espace Administrateur</h3>
        <p>Vous avez accès aux outils d'administration.</p>
        <a href="admin_users.php">Gérer les utilisateurs</a> <br>
    <?php else: ?>
        <h3>Tableau de Bord Utilisateur</h3>
        <p>Bienvenue sur votre tableau de bord utilisateur.</p>
        <!-- Add user-specific content here -->
    <?php endif; ?>

    <a href="../logout.php">Déconnexion</a>
</body>

</html>