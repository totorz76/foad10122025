<?php
function showError($error)
{
    if (!empty($error)) {
        echo '<div class="alert alert-dismissible alert-warning">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <h5 class="alert-heading">Erreur:' . $error . '</h5> </div>';
    }
}
var_dump($_POST);
$nom = '';
$prenom = '';
$email = '';
$password = '';
$password_confirm = '';
$date_naissance = '';
$telephone = '';
$pays = '';
$type = '';
$age = '';
$centres_interet = [];
$error = [];
// Listes de valeurs autorisées
$liste_pays = ["France", "Belgique", "Suisse", "Canada"];
$types_acceptes = ["Etudiant", "Professionnel", "Speaker"];
$interets_acceptes = ["PHP", "JavaScript", "DevOps", "IA"];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoyer'])) {
    // récupération  et nettoyage
     // -------------------------
    // Nettoyage des inputs
    // -------------------------
    $nom = trim(strip_tags($_POST['nom'] ?? ''));
    $prenom = trim(strip_tags($_POST['prenom'] ?? ''));
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $date_naissance = $_POST['date_naissance'] ?? '';
    $telephone = trim($_POST['telephone'] ?? '');
    $pays = $_POST['pays'] ?? '';
    $type = $_POST['type_participant'] ?? '';
    $centres_interet = $_POST['centres_interet'] ?? [];


     // =====================================================
    // VALIDATION NOM
    // =====================================================
    if ($nom === '') {
        $error['nom'] = "Le nom ne doit pas être vide.";
    } elseif (strlen($nom) < 2 || strlen($nom) > 30) {
        $error['nom'] = "Le nom doit contenir entre 2 et 30 caractères.";
    }

    // =====================================================
    // VALIDATION PRENOM
    // =====================================================
    if ($prenom === '') {
        $error['prenom'] = "Le prénom ne doit pas être vide.";
    } elseif (strlen($prenom) < 2 || strlen($prenom) > 30) {
        $error['prenom'] = "Le prénom doit contenir entre 2 et 30 caractères.";
    }

    // =====================================================
    // VALIDATION EMAIL
    // =====================================================
    if ($email === '') {
        $error['email'] = "L'email ne doit pas être vide.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = "L'email n'est pas valide.";
    }

    // =====================================================
    // VALIDATION PASSWORD
    // =====================================================
    if ($password === '') {
        $error['password'] = "Le mot de passe est obligatoire.";
    } elseif (strlen($password) < 8) {
        $error['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
    } elseif (
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password)
    ) {
        $error['password'] = "Le mot de passe doit contenir une majuscule, une minuscule et un chiffre.";
    }

    // =====================================================
    // VALIDATION PASSWORD CONFIRM
    // =====================================================
    if ($password_confirm === '') {
        $error['password_confirm'] = "La confirmation du mot de passe est obligatoire.";
    } elseif ($password !== $password_confirm) {
        $error['password_confirm'] = "Les mots de passe ne correspondent pas.";
    }

    // =====================================================
    // VALIDATION DATE DE NAISSANCE
    // =====================================================
    if ($date_naissance === '') {
        $error['date_naissance'] = "La date de naissance est obligatoire.";
    } else {
        $d = DateTime::createFromFormat('Y-m-d', $date_naissance);
        if (!$d || $d->format('Y-m-d') !== $date_naissance) {
            $error['date_naissance'] = "Date invalide.";
        } else {
            $age = $d->diff(new DateTime())->y;
            if ($age < 18) {
                $error['date_naissance'] = "Vous devez avoir au moins 18 ans.";
            }
        }
    }

    // =====================================================
    // VALIDATION TELEPHONE
    // =====================================================
    if ($telephone === '') {
        $error['telephone'] = "Le téléphone est obligatoire.";
    } elseif (!preg_match('/^\+?[0-9]{10,15}$/', $telephone)) {
        $error['telephone'] = "Téléphone invalide (10 à 15 chiffres, peut débuter par +).";
    }

    // =====================================================
    // VALIDATION PAYS
    // =====================================================
    if ($pays === '') {
        $error['pays'] = "Le pays est obligatoire.";
    } elseif (!in_array($pays, $liste_pays)) {
        $error['pays'] = "Pays invalide.";
    }

    // =====================================================
    // VALIDATION TYPE PARTICIPANT
    // =====================================================
    if ($type === '') {
        $error['type_participant'] = "Le type de participant est obligatoire.";
    } elseif (!in_array($type, $types_acceptes)) {
        $error['type_participant'] = "Type non autorisé.";
    }

    // =====================================================
    // VALIDATION CENTRES D’INTERET
    // =====================================================
    if (empty($centres_interet)) {
        $error['centres_interet'] = "Vous devez sélectionner au moins un centre d’intérêt.";
    } else {
        foreach ($centres_interet as $ci) {
            if (!in_array($ci, $interets_acceptes)) {
                $error['centres_interet'] = "Valeur de centre d’intérêt non autorisée.";
                break;
            }
        }
    }

    // =====================================================
    // VALIDATION CONDITIONS
    // =====================================================
    if (empty($_POST['conditions'])) {
        $error['conditions'] = "Vous devez accepter les conditions.";
    }

    // =====================================================
    // REDIRECTION SI SUCCES
    // =====================================================
    if (empty($error)) {
    header("Location: success.php?status=ok&nom=" . urlencode($nom));
    exit;
    }

    var_dump($error);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://bootswatch.com/5/solar/bootstrap.min.css">
    <title>Document</title>
</head>

<body>
    <?php  include "header.php";
    ?>
    <div class="container">
    <form action="" method="post">

        <!-- NOM -->
        <div>
            <label class="form-label mt-4">Nom</label>
            <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($nom) ?>">
            <?php if (!empty($error['nom'])): ?>
                <div class="alert alert-warning mt-1"><?= $error['nom'] ?></div>
            <?php endif; ?>
        </div>

        <!-- PRENOM -->
        <div>
            <label class="form-label mt-4">Prénom</label>
            <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($prenom) ?>">
            <?php if (!empty($error['prenom'])): ?>
                <div class="alert alert-warning mt-1"><?= $error['prenom'] ?></div>
            <?php endif; ?>
        </div>

        <!-- EMAIL -->
        <div>
            <label class="form-label mt-4">Email</label>
            <input type="text" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>">
            <?php if (!empty($error['email'])): ?>
                <div class="alert alert-warning mt-1"><?= $error['email'] ?></div>
            <?php endif; ?>
        </div>

        <!-- PASSWORD -->
        <div>
            <label class="form-label mt-4">Mot de passe</label>
            <input type="password" class="form-control" name="password">
            <?php if (!empty($error['password'])): ?>
                <div class="alert alert-warning mt-1"><?= $error['password'] ?></div>
            <?php endif; ?>
        </div>

        <!-- PASSWORD CONFIRM -->
        <div>
            <label class="form-label mt-4">Confirmer mot de passe</label>
            <input type="password" class="form-control" name="password_confirm">
            <?php if (!empty($error['password_confirm'])): ?>
                <div class="alert alert-warning mt-1"><?= $error['password_confirm'] ?></div>
            <?php endif; ?>
        </div>

        <!-- DATE NAISSANCE -->
        <div>
            <label class="form-label mt-4">Date de naissance</label>
            <input type="date" class="form-control" name="date_naissance" value="<?= htmlspecialchars($date_naissance) ?>">
            <?php if (!empty($error['date_naissance'])): ?>
                <div class="alert alert-warning mt-1"><?= $error['date_naissance'] ?></div>
            <?php endif; ?>
        </div>

        <!-- TELEPHONE -->
        <div>
            <label class="form-label mt-4">Téléphone</label>
            <input type="text" class="form-control" name="telephone" value="<?= htmlspecialchars($telephone) ?>">
            <?php if (!empty($error['telephone'])): ?>
                <div class="alert alert-warning mt-1"><?= $error['telephone'] ?></div>
            <?php endif; ?>
        </div>

        <!-- PAYS -->
        <div>
            <label class="form-label mt-4">Pays</label>
            <select class="form-control" name="pays">
                <option value="">-- Sélectionner --</option>
                <?php foreach ($liste_pays as $p): ?>
                    <option value="<?= $p ?>" <?= ($pays === $p ? 'selected' : '') ?>><?= $p ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($error['pays'])): ?>
                <div class="alert alert-warning mt-1"><?= $error['pays'] ?></div>
            <?php endif; ?>
        </div>

        <!-- TYPE PARTICIPANT -->
        <div>
            <label class="form-label mt-4">Type de participant</label>
            <select class="form-control" name="type_participant">
                <option value="">-- Sélectionner --</option>
                <?php foreach ($types_acceptes as $t): ?>
                    <option value="<?= $t ?>" <?= ($type === $t ? 'selected' : '') ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($error['type_participant'])): ?>
                <div class="alert alert-warning mt-1"><?= $error['type_participant'] ?></div>
            <?php endif; ?>
        </div>

        <!-- CENTRES D'INTERET -->
        <div class="mt-4">
            <label class="form-label">Centres d’intérêt (au moins un)</label><br>

            <?php foreach ($interets_acceptes as $ci): ?>
                <label>
                    <input type="checkbox" name="centres_interet[]" value="<?= $ci ?>"
                        <?= in_array($ci, $centres_interet) ? 'checked' : '' ?>>
                    <?= $ci ?>
                </label><br>
            <?php endforeach; ?>

            <?php if (!empty($error['centres_interet'])): ?>
                <div class="alert alert-warning mt-1"><?= $error['centres_interet'] ?></div>
            <?php endif; ?>
        </div>

        <!-- CONDITIONS -->
        <div class="mt-4">
            <label>
                <input type="checkbox" name="conditions" value="1"
                    <?= (!empty($_POST['conditions']) ? 'checked' : '') ?>>
                J’accepte les conditions générales
            </label>
            <?php if (!empty($error['conditions'])): ?>
                <div class="alert alert-warning mt-1"><?= $error['conditions'] ?></div>
            <?php endif; ?>
        </div>

        <!-- SUBMIT -->
        <br>
        <div class="d-grid gap-2">
            <button class="btn btn-lg btn-success" type="submit" name="envoyer">Envoyer</button>
        </div>

    </form>
</div>


</body>

</html>
