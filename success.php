<?php
// Vérification des paramètres GET
$status = $_GET['status'] ?? '';
$nom = $_GET['nom'] ?? '';

// Si le lien est valide
if ($status === 'ok' && !empty($nom)) {
    $message = "Merci pour votre inscription, " . htmlspecialchars($nom) . ".";
} else {
    $message = "Accès non valide à la page de confirmation.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/solar/bootstrap.min.css">
</head>
<body class="container mt-5">

    <div class="alert alert-info">
        <h3><?= $message ?></h3>
    </div>

    <a href="index.php" class="btn btn-primary mt-3">Retour au formulaire</a>

</body>
</html>