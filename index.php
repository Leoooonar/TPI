<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 17.05.2024       
//Description : page d'accueil du site     

session_start();
include("./models/database.php");
$db = new Database();

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="./resources/css/style.css">
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
                            <li><img id="logoImg" src="/resources/img/logo.webp" alt=""></li>
                        </div>
                        <div class="right-content">
                            <li><h1>SE CONNECTER</h1></li>
                        </div>
                    </ul>
                </nav>
            </div>    
            <div id="contentContainer">
                <div id="textBlock">
                    <p id="paragraph">
                        Bienvenue sur notre plateforme dédiée à l'enrichissement culturel et sportif !<br>
                        Ici, nous facilitons la connexion entre les organisteurs d'activités et les élèves désireux de s'épanouir à travers une multitude
                        d'activités choisies.
                    </p>
                    <hr>
                    <br>
                    <p id="secondParagraph">
                        Veuillez vous <a href="./resources/views/authentification/login.php" id="list">connecter</a> afin de pouvoir vous inscrire<br>
                        à une activité sportive ou culturelle.
                    </p>
                </div>
                <br>
                <img id="activitiesImg" src="/resources/img/activities.jpg" alt="Image d'activités diverses">
            </div>
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="./resources/js/script.js"></script>
    </body>
</html>