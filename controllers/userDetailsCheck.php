<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 22.05.2024       
//Description : Page d'édition des informations de l'utilisateur 

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
    <title>Connexion</title>
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
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Récupérer les données du formulaire soumis
                $newUsername = $_POST['username'];
                $newFirstname = $_POST['firstname'];
                $newLastname = $_POST['lastname'];
                $newEmail = $_POST['email'];
                $newGender = $_POST['gender'];

                if (!preg_match("/^[a-zA-Z]*$/", $newFirstname)) {
                    $errors[] = "Le prénom ne doit contenir que des lettres";
                }

                if (!preg_match("/^[a-zA-Z]*$/", $newLastname)) {
                    $errors[] = "Le nom ne doit contenir que des lettres";
                }

                // Si des erreurs sont détectées, afficher les messages d'erreur
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo '<div id="contentContainer">';
                        echo '<br>';
                        echo "<p>$error</p>";
                    }
                } else {
                    // Met à jour les informations de l'utilisateur dans la base de données
                    $db->updateUserInfo($user['idUser'], 
                    $newUsername, 
                    $newFirstname, 
                    $newLastname, 
                    $newEmail, 
                    $newGender);

                    $_SESSION['user']['useUsername'] = $newUsername;
                    $_SESSION['user']['useFirstname'] = $newFirstname;
                    $_SESSION['user']['useLastname'] = $newLastname;
                    $_SESSION['user']['useGender'] = $newGender;
                    $_SESSION['user']['useEmail'] = $newEmail;

                    // Redirige l'utilisateur vers sa page de profil après la mise à jour
                    header("Location: ../resources/views/userDetails.php");
                    exit(); 
                }
            }
            ?>
            <br>
            <div id="contentContainer">
            <span style="color:red;">Erreur</span>
            <br>
            <a id="pageBefore" href="../../resources/views/userEditDetails.php"><-Page précédente</a>
            </div>  
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="./resources/js/script.js"></script>
    </body>
</html>