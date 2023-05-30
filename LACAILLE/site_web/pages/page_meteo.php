<?php include '../inc/header.php'; require_once '../rsc/JSON.php';

    $cleAPI = '9e820b8cdec17e18f975f12f9e470977';

    function getTemperature($ville, $cleAPI) {
        $url = "http://api.openweathermap.org/data/2.5/weather?q={$ville},FR&units=metric&appid={$cleAPI}";

        // Initialisation de cURL
        $ch = curl_init();
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        if (isset($data['main']['temp'])) {
            return $data['main']['temp'];
        } else {
            return null;
        }
    }

    function getRegionByDepartmentCode($departements_par_region, $code_departement) {
    foreach ($departements_par_region as $code_region => $departements) {
        foreach ($departements as $departement) {
            if ($departement['code_departement'] == $code_departement) {
                return $code_region;
            }
        }
    }
    return null;
    }


    $temp = getTemperature('Paris', $cleAPI);

    $region = isset($_GET['region']) ? $_GET['region'] : 'A';

    // Une fonction pour obtenir le nom de la région en fonction de la valeur du paramètre
    function get_region_name($region_code) {
        $regions = array(
            "A" => "Grand Est",
            "B" => "Nouvelle-Aquitaine",
            "C" => "Auvergne-Rhône-Alpes",
            "D" => "Bourgogne-Franche-Comté",
            "E" => "Bretagne",
            "F" => "Centre-Val de Loire",
            "G" => "Corse",
            "H" => "Île-de-France",
            "I" => "Occitanie",
            "J" => "Hauts-de-France",
            "K" => "Normandie",
            "L" => "Pays de la Loire",
            "M" => "Provence-Alpes-Côte d'Azur"
        );

    return isset($regions[$region_code]) ? $regions[$region_code] : null;
        }

    $region_name = get_region_name($region);

    // Liste des régions avec leurs 5 plus grandes villes
    $regions_villes = array(
        "A" => array("Strasbourg", "Mulhouse", "Reims", "Metz", "Nancy"),
        "B" => array("Bordeaux", "Limoges", "Poitiers", "Pau", "La Rochelle"),
        "C" => array("Lyon", "Grenoble", "Saint-Etienne", "Clermont-Ferrand", "Villeurbanne"),
        "D" => array("Dijon", "Besancon", "Belfort", "Chalon-sur-Saone", "Nevers"),
        "E" => array("Rennes", "Brest", "Quimper", "Lorient", "Vannes"),
        "F" => array("Tours", "Orleans", "Bourges", "Blois", "Chateauroux"),
        "G" => array("Ajaccio", "Bastia", "Porto-Vecchio", "Corte", "Calvi"),
        "H" => array("Paris", "Boulogne-Billancourt", "Saint-Denis", "Versailles", "Nanterre"),
        "I" => array("Toulouse", "Montpellier", "Nimes", "Perpignan", "Beziers"),
        "J" => array("Lille", "Amiens", "Roubaix", "Tourcoing", "Dunkerque"),
        "K" => array("Havre", "Rouen", "Caen", "Evreux", "Cherbourg"),
        "L" => array("Nantes", "Angers", "Le Mans", "Saint-Nazaire", "Cholet"),
        "M" => array("Marseille", "Nice", "Toulon", "Aix-en-Provence", "Avignon"),
    );

    if (isset($regions_villes[$region])) {
        $villes = $regions_villes[$region];
    } else {
        $villes = array();
    }

    ?>
    
    <div class="region-column">
        <h1 class="meteo">Météo pour la région : <?php echo $region_name; ?></h1>
            <h2 class="meteoregion">Météo en direct du top 5 des villes de la région </h2>

        <section class="carousel">
            <div class="carousel__container" role="button">
                <?php
                $json = new Services_JSON();
                foreach ($villes as $ville) {
                    $temperature = getTemperature($ville, $cleAPI);
                    $image_url = "https://source.unsplash.com/1600x900/?{$ville}";
                    ?>
                    <div class="carousel-item">
                        <img class="carousel-item__img" src="<?php echo $image_url; ?>" alt="<?php echo $ville; ?>"/>
                        <div class="carousel-item__details">
                            <div class="controls">
                                <span class="fas fa-play-circle"></span>
                                <span class="fas fa-plus-circle"></span>
                            </div>
                            <h5 class="carousel-item__details--title"><?php echo $ville; ?></h5>
                            <h6 class="carousel-item__details--subtitle"><?php echo $temperature; ?>°C</h6>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
            </div>
        </section>
    </div>

    <?php
    $departements_par_region = array(
                'C' => array(
                    array('code_departement' => '01', 'nom_departement' => 'Ain'),
                    array('code_departement' => '03', 'nom_departement' => 'Allier'),
                    array('code_departement' => '07', 'nom_departement' => 'Ardèche'),
                    array('code_departement' => '15', 'nom_departement' => 'Cantal'),
                    array('code_departement' => '26', 'nom_departement' => 'Drôme'),
                    array('code_departement' => '38', 'nom_departement' => 'Isère'),
                    array('code_departement' => '42', 'nom_departement' => 'Loire'),
                    array('code_departement' => '43', 'nom_departement' => 'Haute-Loire'),
                    array('code_departement' => '63', 'nom_departement' => 'Puy-de-Dôme'),
                    array('code_departement' => '69', 'nom_departement' => 'Rhône'),
                    array('code_departement' => '73', 'nom_departement' => 'Savoie'),
                    array('code_departement' => '74', 'nom_departement' => 'Haute-Savoie'),
                ),
                'D' => array(
                    array('code_departement' => '21', 'nom_departement' => 'Côte-d\'Or'),
                    array('code_departement' => '25', 'nom_departement' => 'Doubs'),
                    array('code_departement' => '39', 'nom_departement' => 'Jura'),
                    array('code_departement' => '58', 'nom_departement' => 'Nièvre'),
                    array('code_departement' => '70', 'nom_departement' => 'Haute-Saône'),
                    array('code_departement' => '71', 'nom_departement' => 'Saône-et-Loire'),
                    array('code_departement' => '89', 'nom_departement' => 'Yonne'),
                    array('code_departement' => '90', 'nom_departement' => 'Territoire de Belfort'),
                ),
                'E' => array(
                    array('code_departement' => '22', 'nom_departement' => 'Côtes-d\'Armor'),
                    array('code_departement' => '29', 'nom_departement' => 'Finistère'),
                    array('code_departement' => '35', 'nom_departement' => 'Ille-et-Vilaine'),
                    array('code_departement' => '56', 'nom_departement' => 'Morbihan'),
                ),
                'F' => array(
                    array('code_departement' => '18', 'nom_departement' => 'Cher'),
                    array('code_departement' => '28', 'nom_departement' => 'Eure-et-Loir'),
                    array('code_departement' => '36', 'nom_departement' => 'Indre'),
                    array('code_departement' => '37', 'nom_departement' => 'Indre-et-Loire'),
                    array('code_departement' => '41', 'nom_departement' => 'Loir-et-Cher'),
                    array('code_departement' => '45', 'nom_departement' => 'Loiret'),
                ),
                'A' => array(
                    array('code_departement' => '08', 'nom_departement' => 'Ardennes'),
                    array('code_departement' => '10', 'nom_departement' => 'Aube'),
                    array('code_departement' => '51', 'nom_departement' => 'Marne'),
                    array('code_departement' => '52', 'nom_departement' => 'Haute-Marne'),
                    array('code_departement' => '54', 'nom_departement' => 'Meurthe-et-Moselle'),
                    array('code_departement' => '55', 'nom_departement' => 'Meuse'),
                    array('code_departement' => '57', 'nom_departement' => 'Moselle'),
                    array('code_departement' => '67', 'nom_departement' => 'Bas-Rhin'),
                    array('code_departement' => '68', 'nom_departement' => 'Haut-Rhin'),
                    array('code_departement' => '88', 'nom_departement' => 'Vosges'),
                ),
                'J' => array(
                    array('code_departement' => '02', 'nom_departement' => 'Aisne'),
                    array('code_departement' => '59', 'nom_departement' => 'Nord'),
                    array('code_departement' => '60', 'nom_departement' => 'Oise'),
                    array('code_departement' => '62', 'nom_departement' => 'Pas-de-Calais'),
                    array('code_departement' => '80', 'nom_departement' => 'Somme'),
                ),
                'H' => array(
                    array('code_departement' => '75', 'nom_departement' => 'Paris'),
                    array('code_departement' => '77', 'nom_departement' => 'Seine-et-Marne'),
                    array('code_departement' => '78', 'nom_departement' => 'Yvelines'),
                    array('code_departement' => '91', 'nom_departement' => 'Essonne'),
                    array('code_departement' => '92', 'nom_departement' => 'Hauts-de-Seine'),
                    array('code_departement' => '93', 'nom_departement' => 'Seine-Saint-Denis'),
                    array('code_departement' => '94', 'nom_departement' => 'Val-de-Marne'),
                    array('code_departement' => '95', 'nom_departement' => 'Val-d\'Oise'),
                ),
                'K' => array(
                    array('code_departement' => '14', 'nom_departement' => 'Calvados'),
                    array('code_departement' => '27', 'nom_departement' => 'Eure'),
                    array('code_departement' => '50', 'nom_departement' => 'Manche'),
                    array('code_departement' => '61', 'nom_departement' => 'Orne'),
                    array('code_departement' => '76', 'nom_departement' => 'Seine-Maritime'),
                ),
                'B' => array(
                    array('code_departement' => '16', 'nom_departement' => 'Charente'),
                    array('code_departement' => '17', 'nom_departement' => 'Charente-Maritime'),
                    array('code_departement' => '19', 'nom_departement' => 'Corrèze'),
                    array('code_departement' => '23', 'nom_departement' => 'Creuse'),
                    array('code_departement' => '24', 'nom_departement' => 'Dordogne'),
                    array('code_departement' => '33', 'nom_departement' => 'Gironde'),
                    array('code_departement' => '40', 'nom_departement' => 'Landes'),
                    array('code_departement' => '47', 'nom_departement' => 'Lot-et-Garonne'),
                    array('code_departement' => '64', 'nom_departement' => 'Pyrénées-Atlantiques'),
                    array('code_departement' => '79', 'nom_departement' => 'Deux-Sèvres'),
                    array('code_departement' => '86', 'nom_departement' => 'Vienne'),
                    array('code_departement' => '87', 'nom_departement' => 'Haute-Vienne'),
                ),
                'I' => array(
                    array('code_departement' => '09', 'nom_departement' => 'Ariège'),
                    array('code_departement' => '11', 'nom_departement' => 'Aude'),
                    array('code_departement' => '12', 'nom_departement' => 'Aveyron'),
                    array('code_departement' => '30', 'nom_departement' => 'Gard'),
                    array('code_departement' => '31', 'nom_departement' => 'Haute-Garonne'),
                    array('code_departement' => '32', 'nom_departement' => 'Gers'),
                    array('code_departement' => '34', 'nom_departement' => 'Hérault'),
                    array('code_departement' => '46', 'nom_departement' => 'Lot'),
                    array('code_departement' => '48', 'nom_departement' => 'Lozère'),
                    array('code_departement' => '65', 'nom_departement' => 'Hautes-Pyrénées'),
                    array('code_departement' => '66', 'nom_departement' => 'Pyrénées-Orientales'),
                    array('code_departement' => '81', 'nom_departement' => 'Tarn'),
                    array('code_departement' => '82', 'nom_departement' => 'Tarn-et-Garonne'),
                ),
                'L' => array(
                    array('code_departement' => '44', 'nom_departement' => 'Loire-Atlantique'),
                    array('code_departement' => '49', 'nom_departement' => 'Maine-et-Loire'),
                    array('code_departement' => '53', 'nom_departement' => 'Mayenne'),
                    array('code_departement' => '72', 'nom_departement' => 'Sarthe'),
                    array('code_departement' => '85', 'nom_departement' => 'Vendée'),
                ),
                'M' => array(
                    array('code_departement' => '04', 'nom_departement' => 'Alpes-de-Haute-Provence'),
                    array('code_departement' => '05', 'nom_departement' => 'Hautes-Alpes'),
                    array('code_departement' => '06', 'nom_departement' => 'Alpes-Maritimes'),
                    array('code_departement' => '13', 'nom_departement' => 'Bouches-du-Rhône'),
                    array('code_departement' => '83', 'nom_departement' => 'Var'),
                    array('code_departement' => '84', 'nom_departement' => 'Vaucluse'),
                ),
                'G' => array(
                    array('code_departement' => '2A', 'nom_departement' => 'Corse-du-Sud'),
                    array('code_departement' => '2B', 'nom_departement' => 'Haute-Corse'),
                ),
            );

        if (isset($_GET['region'])) {
            $code_region = $_GET['region'];
        } else {
            $code_region = 'A'; // Région par défaut si le paramètre n'est pas présent dans l'URL
        }

        if (isset($departements_par_region[$code_region])) {
            foreach ($departements_par_region[$code_region] as $departement) {
                $code_departement = $departement['code_departement'];
                $nom_departement = $departement['nom_departement'];
            }
        } else {
            echo "<option value=''>Aucun département trouvé</option>";
        }

        $code_departement = '33';

        $region = getRegionByDepartmentCode($departements_par_region, $code_departement);

    ?>

    <?php
        $region = $_GET['region'];

        $region_name = get_region_name($region);
    ?>
        <div>
            <h3 class="choisedep">Veuillez choisir votre département : <h3>
            <form method="POST" action="../pages/departements.php">
                <select name="departements">
        </div>
                <?php
                $code_region = $_GET['region'];

                if (isset($code_region) && isset($departements_par_region[$code_region])) {
                    foreach ($departements_par_region[$code_region] as $departement) {
                        $code_departement = $departement['code_departement'];
                        $nom_departement = $departement['nom_departement'];
                        $nom_departement_encoded = htmlspecialchars($nom_departement, ENT_QUOTES, 'UTF-8');
                        echo "<option value='$nom_departement_encoded'>$nom_departement_encoded</option>";
                    }
                } else {
                    if (!isset($code_region)) {
                        echo "<option value=''>Code région non défini</option>";
                    } else {
                        echo "<option value=''>Aucun département trouvé</option>";
                    }
                }
                ?>

            </select>
            <input type="submit" name="departements_submit" value="Valider">
        </form>
    </body>
</html>
