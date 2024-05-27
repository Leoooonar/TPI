<?php
// ETML
// Auteur: Leonar Dupuis                                            
// Date: 23.05.2024       
// Description : Page de vérification de la création d'une activité
//
// Version : 2.0.0
// Date : 27.05.2024
// Description : Gestion d'erreur chiffre négatif.

session_start();

// Inclure le fichier database.php
include("../models/database.php");

// Initialiser le tableau des erreurs
$errors = array();

$db = new Database();

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
    header("Location: ./authentification/login.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

$user = $_SESSION['user'];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification création activités</title>
    <link rel="stylesheet" href="../../../resources/css/style.css">
</head>
    <body>
        <main>
            <div class="headContainer">
                <nav class="navbar">
                    <ul>
                        <div class="left-content">
                            <a href="../resources/views/activitiesList.php"><li><h1>LISTE DES ACTIVITES</h1></li></a>
                        </div>    
                        <div class="center-content">
                            <li><a href="../index.php"><img id="logoImg" src="/resources/img/logo.webp" alt="Logo sportetculture"></a></li>
                        </div>
                        <div class="right-content">
                            <?php
                                if ($isLoggedIn) {
                                    echo '<li class="nav-item dropdown">';
                                    echo '<h1>MON COMPTE</h1>';
                                    echo '<a href="javascript:void(0)" class="dropbtn"></a>';
                                        echo '<div class="dropdown-content">';
                                        echo '<a href="../resources/views/userDetails.php">Détail du compte</a>';
                                        echo '<a href="../resources/views/myActivities.php">Mes activités</a>';
                                        echo '<a href="../resources/views/logout.php">Déconnexion</a>';
                                        echo '</div>';
                                    echo '</li>';
                                } else {
                                    echo '<li class="nav-item dropdown">';
                                    echo '<a href="../resources/views/authentification/login.php"><h1>SE CONNECTER</h1></a>';
                                    echo '<a href="javascript:void(0)" class="dropbtn"></a>';
                                        echo '<div class="dropdown-content">';
                                        echo '<a href="../resources/views/authentification/register.php">Inscription</a>'; 
                                        echo '</div>';
                                    echo '</li>';                               
                                }
                            ?>
                        </div>
                    </ul>
                </nav>
            </div>    
            <?php
            // Vérifie si les données du formulaire sont envoyées
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $title = $_POST['activity'];
                $capacity = $_POST['participant'];
                $description = $_POST['description'];

                // Vérifie que tous les champs sont remplis
                if (!empty($title) && !empty($capacity) && !empty($description)) {
                    // Vérifie que la capacité n'est pas négative
                    if ($capacity < 0) {
                        echo '<div id="contentContainer">';
                        echo '<br>';
                        echo 'La capacité ne peut pas être négative.';
                        echo '<br>';
                        echo '</div>';
                    } else {
                        // Crée une nouvelle activité
                        $activityId = $db->createActivity($title, $description, $capacity, $user['idUser']);
                        if ($activityId) {
                            // Redirige vers le profil d'activités
                            header("Location: ../resources/views/myActivities.php");
                            exit();
                        } else {
                            echo "Erreur lors de la création de l'activité.";
                        }
                    }
                } else {
                    echo '<div id="contentContainer">';
                    echo '<br>';
                    echo "Veuillez remplir tous les champs.";
                    echo '<br>';
                }
            } else {
                header("Location: ../../resources/views/myActivities.php");
                exit();
            }
            ?>
            <br>
            <div id="contentContainer">
            <span style="color:red;">Erreur</span>
            <br>
            <a id="pageBefore" href="../../resources/views/createActivities.php"><- Page précédente</a>
            </div>  
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="./resources/js/script.js"></script>
    </body>
</html>
