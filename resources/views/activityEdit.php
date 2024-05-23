<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 23.05.2024       
//Description : Page de modification d'une activité (réservé aux enseignants)

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

// Récupérer l'ID de l'activité à modifier depuis l'URL
$activityId = $_GET['id'];

// Récupérer les détails de l'activité depuis la base de données
$activityDetails = $db->getActivityById($activityId);

// Vérifier si l'activité existe
if ($activityDetails) {
    // Récupérer les détails de l'activité
    $activityTitle = $activityDetails['actTitle'];
    $activityDescription = $activityDetails['actDescription'];
    $activityCapacity = $activityDetails['actCapacity'];
} else {
    // Gérer le cas où l'activité n'existe pas
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificiation d'une activité</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
    <body>
        <main>
            <div class="headContainer">
                <nav class="navbar">
                    <ul>
                        <div class="left-content">
                            <a href="activitiesList.php"><li><h1>LISTE DES ACTIVITES</h1></li></a>
                        </div>    
                        <div class="center-content">
                            <li><a href="../../index.php"><img id="logoImg" src="/resources/img/logo.webp" alt="Logo sportetculture"></a></li>
                        </div>
                        <div class="right-content">
                            <?php
                                if ($isLoggedIn) {
                                    echo '<li class="nav-item dropdown">';
                                        echo '<div class="active">';
                                            echo '<h1>MON COMPTE</h1>';
                                        echo '</div>';
                                        echo '<a href="javascript:void(0)" class="dropbtn"></a>';
                                        echo '<div class="dropdown-content">';
                                        echo '<a href="userDetails.php">Détails du compte</a>';
                                        echo '<a href="myActivities.php">Mes activités</a>';
                                        echo '<a href="logout.php">Déconnexion</a>';
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
            <h2 id="secondTitle">Modifier d'une activité</h2>
            <hr>
            <div class="userContainer">
            <form action="../../controllers/activityEditCheck.php" id="details" method="POST">
                <input type="hidden" name="activityId" value="<?= $activityId ?>">
                <label for="activity">Nom de l'activité:</label>
                <input type="text" id="activity" name="activity" value="<?= htmlspecialchars($activityTitle) ?>">
                <br>
                <label for="participant">Participants max:</label>
                <input type="number" id="participant" name="participant" value="<?= $activityCapacity ?>">
                <br>
                <label for="description">Description:</label>
                <textarea name="description" id="desc"><?= htmlspecialchars($activityDescription) ?></textarea>
                <br>
                <button type="submit">Modifier</button>
            </form>
            </div>
            <br>
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="../js/script.js"></script>
    </body>
</html>