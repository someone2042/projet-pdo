<?php
include '../includes/config.php';
$message = "";
// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage sécurisé
    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $message = "Cet email est déjà utilisé.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?,
?)");
        if ($stmt->execute([$name, $email, $password])) {
            $message = "Inscription réussie ! <a href='login.php'>Connectez-vous</a>";
        } else {
            $message = "Erreur lors de l'inscription.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2>Inscription</h2>
    <form method="post">
        <input type="text" name="name" placeholder="Nom" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit">S'inscrire</button>
    </form>
    <p><?php echo $message; ?></p>
</body>

</html>