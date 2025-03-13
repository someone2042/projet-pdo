<?php
include '../includes/config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate unique token
            $token = uniqid();
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

            // Store token in database
            $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
            $stmt->execute([$token, $expiry, $email]);

            // Send email with reset link (simplified for demonstration)
            $resetLink = "http://localhost/projet-pdo/pages/reset_password.php?token=" . $token; // Replace yourdomain.com
            $subject = "Réinitialisation de votre mot de passe";
            $body = "Bonjour,\n\nPour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant:\n" . $resetLink . "\n\nCe lien expirera dans 1 heure.\n\nCordialement,\nVotre équipe";
            $headers = 'From: noreply@yourdomain.com' . "\r\n" . // Replace noreply@yourdomain.com
                'Reply-To: noreply@yourdomain.com' . "\r\n" . // Replace noreply@yourdomain.com
                'X-Mailer: PHP/' . phpversion();

            if (mail($email, $subject, $body, $headers)) {
                $message = "Un lien de réinitialisation de mot de passe a été envoyé à votre adresse email.";
            } else {
                $message = "Erreur lors de l'envoi de l'email. Veuillez réessayer plus tard.";
                // In a real application, log the email sending error.
            }
        } else {
            $message = "Aucun utilisateur trouvé avec cet email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2>Mot de passe oublié</h2>
    <p>Entrez votre email pour réinitialiser votre mot de passe.</p>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required><br>
        <button type="submit">Réinitialiser le mot de passe</button>
    </form>
    <p><?php echo $message; ?></p>
</body>

</html>