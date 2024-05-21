<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 21.05.2024       
//Description : Page de connexion du site   

session_start();

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
                            <li><a href="../../../index.php"><img id="logoImg" src="/resources/img/logo.webp" alt="Logo sportetculture"></a></li>
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
                                        echo '<a href="./resources/views/logout.php">Déconnexion</a>';
                                        echo '</div>';
                                    echo '</li>';
                                } else {
                                    echo '<li class="nav-item dropdown">';
                                    echo '<a href="#"><h1>SE CONNECTER</h1></a>';
                                    echo '<a href="javascript:void(0)" class="dropbtn"></a>';
                                        echo '<div class="dropdown-content">';
                                        echo '<a href="register.php">Inscription</a>'; 
                                        echo '</div>';
                                    echo '</li>';
                                }
                            ?>

                        </div>
                    </ul>
                </nav>
            </div>    
            <div class="form-content">
                <form action="../../../controllers/authentification/loginCheck.php" method="POST">
                    <div class="login-png">    
                        <img src="../../../resources/img/login.png" alt="">
                    </div>
                    <h1 class="form-title">Connexion</h1> 
                    <div class="label-content">
                        <label><b>Nom d'utilisateur</b></label>
                        <br>
                        <input type="text" placeholder="Entrer le nom d'utilisateur" name="username">
                    </div>
                    <br>
                    <div class="label-content">
                        <label><b>Mot de passe</b></label>
                        <br>
                        <input type="password" placeholder="Entrer le mot de passe" name="password">
                        <br>
                    </div>
                    <div class="label-content">
                        <input type="submit" id='submit' value='LOGIN' >
                    </div>
                </form>
                <a href="register.php"><h4 class="inscription">S'inscrire</h4></a>
            </div>  
            <br>
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="./resources/js/script.js"></script>
    </body>
</html>