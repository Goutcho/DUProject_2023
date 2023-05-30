<?php 

include '../inc/header.php';

      $departement = $_POST['departements'];
      echo '<div class="main-departements">';
      echo '<h1 class="title-departements"> En direct de ' . $departement . '</h1>';

      // Lire les données du fichier CSV
      $villes = [];
      $filename = "../rsc/villes.csv";
      $file = fopen($filename, "r");

      while (($line = fgetcsv($file, 1000, ",")) !== FALSE) {
          $code_departement = $line[12];
          $nom_ville = $line[1];
          $code_postal = $line[2];

          if ($code_departement == $departement) {
              $villes[] = [
                  'nom' => $nom_ville,
                  'code_postal' => $code_postal
              ];
          }
      }

      fclose($file);

      // Afficher les villes du département sélectionné
      if (!empty($villes)) {
          echo "<h3 class=\"choisedep\">Veuillez choisir votre ville : <h3>";
          echo "<form action=\"commune.php\" method=\"POST\">";
          echo "<select name=\"villes\">";

      foreach ($villes as $ville) {
          $nom_ville = $ville['nom'];
          $code_postal = $ville['code_postal'];
          echo "<option value=\"{$nom_ville}|{$code_postal}\">{$nom_ville} - {$code_postal}</option>";

      }

          echo "</select>";
          echo "<input type=\"submit\" value=\"Valider\">";
          echo "</form>";
      } else {
          echo "<p>Aucune ville trouvée pour ce département.</p>";
      }
?>
      </div>
    </body>
</html>

