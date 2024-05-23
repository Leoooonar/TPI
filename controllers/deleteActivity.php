<?php
// ETML
// Auteur: Leonar Dupuis                                            
// Date: 23.05.2024       
// Description : Contrôleur pour supprimer une activité

session_start();
include("../models/database.php");
$db = new Database();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../views/authentification/login.php"); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression d'une activité</title>
    <link rel="stylesheet" href="../../../resources/css/style.css">
</head>
    <body>
        <main>
            <div class="headContainer">
                <nav class="navbar">
                    <ul>
                        <div class="left-content">
                            <li><h1>LISTE DES ACTIVITES</h1></li>
                        </div>    
                        <div class="center-content">
                            <li><a href="../../index.php"><img id="logoImg" src="/resources/img/logo.webp" alt="Logo sportetculture"></a></li>
                        </div>
                        <div class="right-content">
                            <?php
                                if ($isLoggedIn) {
                                    echo '<li class="nav-item dropdown">';
                                    echo '<h1>MON COMPTE</h1>';
                                    echo '<a href="javascript:void(0)" class="dropbtn"></a>';
                                        echo '<div class="dropdown-content">';
                                        echo '<a href="./resources/views/userDetails.php">Détail du compte</a>';
                                        echo '<a href="./resources/views/myActivities.php">Mes activités</a>';
                                        echo '<a href="../../resources/views/logout.php">Déconnexion</a>';
                                        echo '</div>';
                                    echo '</li>';
                                } else {
                                    echo '<li class="nav-item dropdown">';
                                    echo '<a href="../../resources/views/authentification/login.php"><h1>SE CONNECTER</h1></a>';
                                    echo '<a href="javascript:void(0)" class="dropbtn"></a>';
                                        echo '<div class="dropdown-content">';
                                        echo '<a href="../../resources/views/authentification/register.php">Inscription</a>'; 
                                        echo '</div>';
                                    echo '</li>';                               
                                }
                            ?>
                        </div>
                    </ul>
                </nav>
            </div>    
            <?php
            // Vérifie si l'ID de l'activité à supprimer est fourni dans l'URL
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $activityId = $_GET['id'];

                // Supprime l'activité
                $success = $db->deleteActivity($activityId);

                if ($success) {
                    // Redirige vers la page de liste des activités après la suppression
                    header("Location: ../resources/views/activitiesList.php");
                    exit();
                } else {
                    echo '<div id="contentContainer">';
                    echo '<br>';
                    echo "Erreur lors de la suppression de l'activité.";
                    echo '<br>';
                }
            } else {
                echo '<div id="contentContainer">';
                echo '<br>';
                echo "ID de l'activité non fourni.";
                echo '<br>';
            }
            ?>
            <br>
            <a id="pageBefore" href="../../resources/views/createActivities.php"><-Page précédente</a>
            </div>  
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="./resources/js/script.js"></script>
    </body>
</html>
