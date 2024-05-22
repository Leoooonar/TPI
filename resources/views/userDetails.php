<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 22.05.2024       
//Description : Page détails de l'utilisateur 

session_start();
include("../../models/database.php");
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil utilisateur</title>
    <link rel="stylesheet" href="../css/style.css">
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
                            <li><a href="#"><img id="logoImg" src="/resources/img/logo.webp" alt="Logo sportetculture"></a></li>
                        </div>
                        <div class="right-content">
                            <?php
                                if ($isLoggedIn) {
                                    echo '<li class="nav-item dropdown">';
                                        echo '<h1>MON COMPTE</h1>';
                                        echo '<a href="javascript:void(0)" class="dropbtn"></a>';
                                        echo '<div class="dropdown-content">';
                                        echo '<a href="./resources/views/userDetails.php">Détails du compte</a>';
                                        echo '<a href="./resources/views/myActivities.php">Mes activités</a>';
                                        echo '<a href="./resources/views/logout.php">Déconnexion</a>';
                                        echo '</div>';
                                    echo '</li>';
                                } else {
                                    echo '<li class="nav-item dropdown">';
                                    echo '<a href="./resources/views/authentification/login.php"><h1>SE CONNECTER</h1></a>';
                                    echo '<a href="javascript:void(0)" class="dropbtn"></a>';
                                        echo '<div class="dropdown-content">';
                                        echo '<a href="./resources/views/authentification/register.php">Inscription</a>'; 
                                        echo '</div>';
                                    echo '</li>';
                                }
                            ?>
                        </div>
                    </ul>
                </nav>
            </div>    
            <br>
            <h2 id="secondTitle">Informations générales</h2>
            <hr>
            <?php
                if ($isLoggedIn) {
                    echo '<div class="infoContainer">';

                        echo '<div class="userType">';
                        if ($user['useType'] == 'S'){
                            echo '<h5>Etudiant</h5>';
                        } else {
                            echo '<h5>Enseignant</h5>';
                        }
                        echo '</div>';
                        
                        echo '<br>';
                        echo '<p><strong>Nom d\'utilisateur:</strong> ' . $user['useNickname'] . '</p>';
                        echo '<br>';
                        echo '<p><strong>Prénom:</strong> ' . ($user['useFirstname'] ?? 'Non renseigné') . '</p>';
                        echo '<br>';
                        echo '<p><strong>Nom:</strong> ' . ($user['useLastname'] ?? 'Non renseigné') . '</p>';
                        echo '<br>';
                        echo '<p><strong>Adresse e-mail:</strong> ' . ($user['useEmail'] ?? 'Non renseignée') . '</p>';
                        echo '<br>';
                        echo '<p><strong>Genre:</strong> ' . ($user['useGender'] ?? 'Non renseigné') . '</p>';
                        echo '<br>';

                        // Bouton d'ajouts/modifications d'informations
                        echo '<a href="userEditDetails.php"><button type="submit">Modifier</button></a>';
                    echo '</div>';
                }
            ?> 
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="./resources/js/script.js"></script>
    </body>
</html>