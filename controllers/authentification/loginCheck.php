<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 21.05.2024       
//Description : Page de vérification de la connexion du site   

session_start();

// Inclure le fichier database.php
include("../../models/database.php");

// Initialiser le tableau des erreurs
$errors = array();

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
}
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
                                    echo '</li>';                                }
                            ?>
                        </div>
                    </ul>
                </nav>
            </div>    
<?php

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérifier si le nom d'utilisateur est vide
    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est requis.";
    }

    // Vérifier si le mot de passe est vide
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    }

    // Si aucun erreur n'a été détectée, vérifier les informations de connexion
    if (empty($errors)) {
        // Créer une instance de la classe Database
        $db = new Database();

        // Appeler la méthode pour vérifier le login dans database.php
        $result = $db->checkLogin($username, $password);

        // Vérifier le résultat de la vérification du login
        if ($result) {
            // Les informations de connexion sont valides, rediriger l'utilisateur vers la page d'accueil par exemple
            header("Location: ../../index.php");
            exit();
        } else {
            // Les informations de connexion sont incorrectes, ajouter un message d'erreur
            $errors[] = "Nom d'utilisateur ou mot de passe <span style='color:red;'>incorrect</span>";
        }
    }
}

// Si des erreurs sont survenues, afficher les erreurs
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo '<div id="contentContainer">';
        echo $error . "<br>";
        echo '<br>';
    }
}
?>
<br>
<a id="pageBefore" href="../../resources/views/authentification/login.php"><-Page précédente</a>
</div>  
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="./resources/js/script.js"></script>
    </body>
</html>