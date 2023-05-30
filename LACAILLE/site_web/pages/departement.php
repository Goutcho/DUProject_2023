<?php include '../inc/header.php'; ?>

<?php
$code_departement = $_GET['departement'];

// Recherche des informations du département
$departement_info = null;
foreach ($departements as $departement) {
    if ($departement['code_departement'] == $code_departement) {
        $departement_info = $departement;
        break;
    }
}

// Affichage du titre avec le nom du département
if ($departement_info) {
    echo "<h1>La météo pour le département {$departement_info['nom_departement']}</h1>";
} else {
    echo "<h1>Département introuvable</h1>";
}

?>

<?php include '../includes/footer.php'; ?>
