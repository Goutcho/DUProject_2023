<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Projet DU II - Gustave Lacaille</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
    <?php 
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'function' . DIRECTORY_SEPARATOR . 'compteur.php';
    add_view();    
    ?>
        <nav>
            <div class="nav-wrapper">
              <button class="cta" onclick="window.location.href='../index.php';">
                  <span>Acceuil</span>
                  <svg viewBox="0 0 13 10" height="10px" width="15px">
                    <path d="M1,5 L11,5"></path>
                    <polyline points="8 1 12 5 8 9"></polyline>
                  </svg>
                </button>
                <a href="../index.php" class="logo"><img src="../img/logo.png"  alt="Logo"></a>
                  <button class="cta" onclick="window.location.href='../pages/dashboard.php';">
                    <span>Pages vues</span>
                    <svg viewBox="0 0 13 10" height="10px" width="15px">
                      <path d="M1,5 L11,5"></path>
                      <polyline points="8 1 12 5 8 9"></polyline>
                    </svg>
                  </button>          
        </nav>
