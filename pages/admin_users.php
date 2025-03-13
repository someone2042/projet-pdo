<?php
include '../includes/config.php';
session_start();

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: dashboard.php"); // Redirect to user dashboard if not admin
    exit();
}

// Delete user functionality
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    // Prevent admin from deleting themselves (important security measure)
    $stmt_check_admin = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt_check_admin->execute([$delete_id]);
    $user_to_delete = $stmt_check_admin->fetch(PDO::FETCH_ASSOC);

    if ($user_to_delete && $user_to_delete['role'] !== 'admin') { // Prevent deleting other admins
        $stmt_delete = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt_delete->execute([$delete_id]);
        $delete_message = "Utilisateur supprimé avec succès.";
    } else {
        $delete_message = "Impossible de supprimer cet utilisateur (administrateur ou vous-même).";
    }
}

// Fetch all users
$stmt_users = $pdo->prepare("SELECT id, name, email, role FROM users");
$stmt_users->execute();
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Gestion des Utilisateurs (Admin)</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2>Gestion des Utilisateurs (Admin)</h2>

    <?php if (isset($delete_message)) : ?>
        <p><?php echo $delete_message; ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <?php if ($user['role'] !== 'admin'): // Prevent deleting other admins 
                        ?>
                            <a href="admin_users.php?delete_id=<?php echo $user['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="dashboard.php">Retour au tableau de bord</a> | <a href="../logout.php">Déconnexion</a>
</body>

</html>