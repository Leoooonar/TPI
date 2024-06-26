<?php
// ETML
// Auteur: Leonar Dupuis                                            
// Date: 23.05.2024       
// Description : Page de vérification de la modification d'une activité (réservé aux enseignants)
//
// Version : 2.0.0
// Date : 27.05.2024
// Description : Gestion d'erreur si le nombre de participants est > actCapacity

session_start();
include("../models/database.php");
$db = new Database();

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
    header("Location: ../../resources/views/authentification/login.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

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
            // Vérifie si les données du formulaire sont soumises
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Vérifie que tous les champs obligatoires sont remplis
                if (empty($_POST["activityId"]) || empty($_POST["activity"]) || empty($_POST["description"])  || empty($_POST["participant"])) {
                    echo '<div id="contentContainer">';
                    echo '<br>';
                    echo "Tous les champs sont obligatoires. Veuillez remplir tous les champs.";
                    echo '<br>';
                } else {
                    // Récupère les données du formulaire
                    $activityId = $_POST["activityId"];
                    $activityTitle = $_POST["activity"];
                    $activityDescription = $_POST["description"];
                    $activityCapacity = isset($_POST["participant"]) ? $_POST["participant"] : NULL;

                    // Vérifie que la capacité n'est pas négative
                    if ($activityCapacity < 0) {
                        echo '<div id="contentContainer">';
                        echo '<br>';
                        echo 'La capacité ne peut pas être négative.';
                        echo '<br>';
                    } else
                    

                    // Récupère le nombre actuel de participants
                    $currentParticipantCount = $db->getParticipantCount($activityId);

                    // Vérifie si la nouvelle capacité est inférieure au nombre actuel de participants
                    if ($activityCapacity < $currentParticipantCount) {
                        echo '<div id="contentContainer">';
                        echo '<div id="errormsg">';
                        echo '</div>';
                        echo 'Nombre de participants trop élevé pour être modifié.';
                        echo '<br>';
                        echo '<br>';
                    } else {
                        // Récupère le nombre actuel de participants
                        $currentParticipantCount = $db->getParticipantCount($activityId);

                        // Vérifie si la nouvelle capacité est inférieure au nombre actuel de participants
                        if ($activityCapacity < $currentParticipantCount) {
                            echo '<div id="contentContainer">';
                            echo '<br>';
                            echo 'Nombre de participants trop élevé pour être modifié.';
                            echo '<br>';
                        } else {
                            // Effectue la mise à jour de l'activité dans la base de données
                            $result = $db->updateActivity($activityId, $activityTitle, $activityDescription, $activityCapacity);

                            if ($result) {
                                // Redirige vers une page de confirmation
                                header("Location: ../resources/views/myActivities.php?edit=success");
                                exit();
                            } else {
                                // Gère le cas où la mise à jour a échoué
                                // Par exemple, affiche un message d'erreur
                                header("Location: ../resources/views/myActivities.php?edit=error");
                            }
                        }
                    }
                }        
            }
        ?>
        <br>
        <div id="contentContainer">
        <span style="color:red;">Erreur</span>
        <br>
        <a id="pageBefore" href="../../resources/views/myActivities.php"><- Page précédente</a>
        </div>  
    </main>
    <footer>
        <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
    </footer>
    <script src="./resources/js/script.js"></script>
</body>
</html>