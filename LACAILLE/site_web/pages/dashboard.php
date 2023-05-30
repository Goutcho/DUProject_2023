<?php
include '../function/compteur.php';
$total = view();
$annee = (int)date('Y');
include '../inc/header.php'; 
?>

        <div class="card3">
            <div class="card-body">
                <strong style="font-size:3em"><?php echo $total ?></strong>
                Visite<?php echo $total > 1 ? 's' : '' ?> de pages au cours du dernier mois
            </div>
        </div>