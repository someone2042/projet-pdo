<?php
include '../includes/config.php';
$message = "";

if (!isset($_GET['token'])) {
    $message = "Token de réinitialisation invalide.";
    $token = null; // To prevent errors later
} else {
    $token = $_GET['token'];

    // Verify token and expiry
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $message = "Token de réinitialisation invalide ou expiré.";
        $token = null; // To prevent errors later
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && $token !== null && $message === "") {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        $message = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 6) { // Example password strength check
        $message = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update password and clear token
        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
        $stmt->execute([$hashedPassword, $token]);

        $message = "Mot de passe réinitialisé avec succès ! <a href='login.php'>Connectez-vous</a>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Réinitialiser le mot de passe</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h2>Réinitialiser le mot de passe</h2>

    <?php if ($message && $token === null): ?>
        <p><?php echo $message; ?></p>
    <?php elseif ($token !== null && $message === ""): ?>
        <form method="post">
            <input type="password" name="password" placeholder="Nouveau mot de passe" required><br>
            <input type="password" name="confirm_password" placeholder="Confirmer le nouveau mot de passe" required><br>
            <button type="submit">Réinitialiser le mot de passe</button>
        </form>
    <?php elseif ($message && $token !== null): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if ($message && $token !== null) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</body>

</html>