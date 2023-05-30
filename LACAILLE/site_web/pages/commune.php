<?php

if (isset($_POST['villes'])) {
  $ville = $_POST['villes'];
  setcookie("derniere_ville", $ville, time() + (86400 * 30), "/");
} elseif (isset($_COOKIE['derniere_ville'])) {
  $ville = $_COOKIE['derniere_ville'];
} else {
  $ville = null;
}

$derniere_ville = isset($_COOKIE['derniere_ville']) ? $_COOKIE['derniere_ville'] : null;

if ($derniere_ville !== null) {
  $derniere_ville_data = explode('|', $derniere_ville);
  $derniere_ville_nom = $derniere_ville_data[0];
  $derniere_ville_code_postal = $derniere_ville_data[1] ?? ''; // Utilisez l'opérateur de coalescence nulle pour éviter les erreurs
}

include '../inc/header.php';

?>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<?php
  function get_json_data($url)
  {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');

      $data = curl_exec($ch);

      if (curl_errno($ch)) {
          return false;
      }

      curl_close($ch);

      return json_decode($data, true);
  }

  $ville_data = explode('|', $_POST['villes']);
  $ville_nom = $ville_data[0];
  $ville_code_postal = $ville_data[1];

  $apiKey = '9e820b8cdec17e18f975f12f9e470977';

  function get_current_day_weather($forecast_data) {
      $current_day = date("Y-m-d");
      foreach ($forecast_data['list'] as $forecast) {
          $day = date("Y-m-d", $forecast['dt']);
          if ($day == $current_day) {
              return $forecast;
          }
      }
      return null;
  }

  function get_weather_icon_class($icon_id) {
      return "weather-icon-{$icon_id}";
  }

  function get_current_and_next_day_forecasts($forecast_data) {
      $current_day = date("Y-m-d");
      $next_day = date("Y-m-d", strtotime("+1 day"));
      $day_forecasts = [];

      foreach ($forecast_data['list'] as $forecast) {
          $day = date("Y-m-d", $forecast['dt']);
          if ($day == $current_day || $day == $next_day) {
              $day_forecasts[] = $forecast;
          }
      }

      return $day_forecasts;
  }

  function get_coordinates_from_zip($zip) {
      $url = "https://nominatim.openstreetmap.org/search?postalcode={$zip}&country=France&format=json";
      $json_data = get_json_data($url);

      if (count($json_data) > 0) {
          return [
              'lat' => $json_data[0]['lat'],
              'lon' => $json_data[0]['lon']
          ];
      } else {
          return null;
      }
  }

  function get_estimated_24_hour_forecasts($current_weather_data) {
      $hourly_forecasts = [];

      for ($i = 0; $i < 24; $i++) {
          $forecast = [
              'dt' => time() + $i * 60 * 60,
              'main' => [
                  'temp' => $current_weather_data['main']['temp']
              ],
              'weather' => [
                  0 => [
                      'description' => $current_weather_data['weather'][0]['description'],
                      'icon' => $current_weather_data['weather'][0]['icon']
                  ]
              ]
          ];
          $hourly_forecasts[] = $forecast;
      }

      return $hourly_forecasts;
  }

  echo '<h1 class="titrecommune">Météo en direct de ' . $ville_nom . ' (' . $ville_code_postal . ')</h1>';

  if ($derniere_ville):
    $derniere_ville_data = explode('|', $derniere_ville);
    $derniere_ville_nom = $derniere_ville_data[0];
    $derniere_ville_code_postal = $derniere_ville_data[1];
    echo '<p>Dernière ville consultée : ' . $derniere_ville_nom . ' (' . $derniere_ville_code_postal . ')</p>';
  endif;


  $coordinates = get_coordinates_from_zip($ville_code_postal);

  // Obtenir les prévisions météorologiques des 5 prochains jours à partir de l'API OpenWeather
  $url_forecast = "http://api.openweathermap.org/data/2.5/forecast?zip={$ville_code_postal},FR&units=metric&appid={$apiKey}&lang=fr";
  $json_forecast_data = get_json_data($url_forecast);

  $forecast_days = array();
  foreach ($json_forecast_data['list'] as $forecast) {
      $day = date("Y-m-d", $forecast['dt']);
      if (!isset($forecast_days[$day]) && count($forecast_days) < 5) {
          $forecast_days[$day] = $forecast;
      }
  }

  $month_fr = [
      'January' => 'Janvier',
      'February' => 'Février',
      'March' => 'Mars',
      'April' => 'Avril',
      'May' => 'Mai',
      'June' => 'Juin',
      'July' => 'Juillet',
      'August' => 'Août',
      'September' => 'Septembre',
      'October' => 'Octobre',
      'November' => 'Novembre',
      'December' => 'Décembre'
  ];

  $days_fr = [
      'Monday' => 'Lundi',
      'Tuesday' => 'Mardi',
      'Wednesday' => 'Mercredi',
      'Thursday' => 'Jeudi',
      'Friday' => 'Vendredi',
      'Saturday' => 'Samedi',
      'Sunday' => 'Dimanche'
  ];

  // Obtenir la météo actuelle à partir de l'API OpenWeather

  $url_weather = "http://api.openweathermap.org/data/2.5/weather?zip={$ville_code_postal},FR&units=metric&appid={$apiKey}&lang=fr";
  $json_weather_data = get_json_data($url_weather);

  if (isset($json_weather_data['main'])) {
      $date_time = date("Y-m-d H:i", $json_weather_data['dt']);
      $temp = $json_weather_data['main']['temp'];
      $description = $json_weather_data['weather'][0]['description'];
      $icon_id = $json_weather_data['weather'][0]['icon'];
      $icon_class = get_weather_icon_class($icon_id);
  }


  $date_time = '2023-04-14 14:30:00';
  $date_time_fr = date_format(date_create_from_format('Y-m-d H:i:s', $date_time), 'l d F Y, H:i');
  $date_time_fr = strtr($date_time_fr, $month_fr);
  $date_time_fr = strtr($date_time_fr, $days_fr);
  ?>



      <div class="current-weather-card">
      <div class="card">
        <span class="sun1"><img src="http://openweathermap.org/img/wn/<?php echo $icon_id; ?>.png" alt="<?php echo $description; ?>" /></span>
        <p class="sun2"><?php echo $description; ?></p>
      <div class="card-header">
        <span><?php echo $ville_nom; ?>, <?php echo $ville_code_postal; ?><br>France</span>
        <span><?php echo "Météo du jour" ?></span>
      </div>

      <span class="temp"><?php echo $temp;?>°</span>

      <div class="temp-scale">
        <span>Celcius</span>
      </div>
    </div>
      </div>

  <?php

  $current_day = date("Y-m-d");
  $current_day_forecasts = array();
  if (isset($json_forecast_data['list'])) {
      foreach ($json_forecast_data['list'] as $forecast) {
      $day = date("Y-m-d", $forecast['dt']);
      if ($day == $current_day) {
          $current_day_forecasts[] = $forecast;
      }
    }
  }

  echo "<h2 class='titre24h'>Météo sur 24 heures</h2>";
  echo "<div class='forecast-24h-container'>";

  $month = ucfirst(date('F', strtotime($date_time))); 
  $year = date('Y', strtotime($date_time)); 

  $current_and_next_day_forecasts = get_current_and_next_day_forecasts($json_forecast_data);
  setlocale(LC_TIME, 'fr_FR.utf8');

  $counter = 0;

  foreach ($current_and_next_day_forecasts as $forecast) {
    if ($counter >= 6) {
      break;
    }
      $date_time = date("Y-m-d H:i", $forecast['dt']);
      $temp = $forecast['main']['temp'];
      $description = $forecast['weather'][0]['description'];
      $icon_id = $forecast['weather'][0]['icon'];
      $icon_class = get_weather_icon_class($icon_id);
      $weekday = ucfirst(date('l', strtotime($day)));
      $weekday = ucfirst($days_fr[$weekday]);
      $day_number = date('d', strtotime($date_time));
      $month = date("F", strtotime($date_time));
      $month = ucfirst($month_fr[$month]); 
      $year = date('Y', strtotime($date_time)); 

      echo "<div class='card2'>";
      echo "<div class='weekday'>{$weekday} {$day_number} {$month} {$year}</div>";
      echo "<div class='hour'>".date('H:i', strtotime($date_time)). "</div>";
      echo "<div class='degre'>{$temp} °C</div>";
      echo "<div class='intep'><span class='{$icon_class}'><img src='http://openweathermap.org/img/wn/{$icon_id}.png' alt='{$description}' class='weather-icon-1' /></span>{$description}</div>";
      echo "</div>";
      $counter++;
  }

  echo "</div>";

  echo "<h2 class='fivedays'>Prévisions météorologiques des 5 prochains jours</h2>";
  echo "<div class='forecast-container'>";

  foreach ($forecast_days as $day => $forecast) {
      $temp = $forecast['main']['temp'];
      $description = $forecast['weather'][0]['description'];
      $icon_id = $forecast['weather'][0]['icon'];
      $icon_class = get_weather_icon_class($icon_id);
      $weekday = ucfirst(date('l', strtotime($day))); 
      $weekday = ucfirst($days_fr[$weekday]);
      $day_number = date('d', strtotime($day)); // Récupère le numéro du jour

      echo "<div class='forecast-card'>";
      echo "<h3 class='weekday'>{$weekday} {$day_number}</h3>";
      echo "<div><span class='{$icon_class}'><img src='http://openweathermap.org/img/wn/{$icon_id}.png' alt='{$description}' /></span> {$description}</div>";
      echo "<div>{$temp} °C</div>";
      echo "</div>";
      $counter++;
  }

  echo "</div>";

  ?>

    <div id="map" style="width: 35%; height: 400px;"></div>

    <script>

    document.addEventListener("DOMContentLoaded", function() {
        var lat = <?php echo $coordinates['lat']; ?>;
        var lon = <?php echo $coordinates['lon']; ?>;
        var mapOptions = {
            center: [lat, lon],
            zoom: 12
        };
        var map = L.map('map', mapOptions);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var gpsIcon = L.icon({
            iconUrl: '../img/gps_logo.svg',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
        });

        var marker = L.marker([lat, lon], {icon: gpsIcon}).addTo(map);
    });

    </script>

  </body>

</html>