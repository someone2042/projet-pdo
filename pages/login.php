<?php
include '../includes/config.php';
session_start();
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h2>Connexion</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit">Se connecter</button>
    </form>
    <p><?php echo $message; ?></p>
    <p><a href="forgot_password.php">Mot de passe oublié ?</a></p>
</body>

</html>