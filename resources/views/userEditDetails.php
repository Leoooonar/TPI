<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 22.05.2024       
//Description : Page d'édition des informations de l'utilisateur 

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
            <h2 id="secondTitle">Edition des informations</h2>
            <hr>
            <div class="userContainer">
                <form action="../../controllers/userDetailsCheck.php" id="details" method="POST">
                    <label for="username">Pseudonyme:</label>
                    <input type="text" id="username" name="username" value="<?php echo $user['useNickname']; ?>">
                    <br>
                    <label for="firstname">Prénom:</label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo $user['useFirstname'] ?? ''; ?>">
                    <br>
                    <label for="lastname">Nom:</label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo $user['useLastname'] ?? ''; ?>">
                    <br>
                    <label for="email">Adresse e-mail:</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['useEmail'] ?? ''; ?>">
                    <br>
                    <label for="gender">Genre:</label>
                    <select id="gender" name="gender">
                        <option value="M" <?php echo ($user['useGender'] == 'M') ? 'selected' : ''; ?>>Masculin</option>
                        <option value="F" <?php echo ($user['useGender'] == 'F') ? 'selected' : ''; ?>>Féminin</option>
                        <option value="O" <?php echo ($user['useGender'] == 'O') ? 'selected' : ''; ?>>Autre</option>
                    </select>
                    <br>
                    <br>
                        <button type="submit">Sauvegarder</button>
                </form>
            </div>
            <?php
            var_dump($user)?>
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="./resources/js/script.js"></script>
    </body>
</html>