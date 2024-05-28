<?php
// ETML
// Auteur: Leonar Dupuis                                            
// Date: 28.05.2024       
// Description: Page profil visible à tous d'un utilisateur

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

// Récupérer l'ID de l'utilisateur depuis l'URL
$userId = $_GET['id'];

// Récupérer les détails de l'utilisateur depuis la base de données
$userDetails = $db->getUserById($userId);

// Vérifier si l'utilisateur existe
if ($userDetails) {
    $userFirstname = $userDetails['useFirstname'];
    $userLastname = $userDetails['useLastname'];
    $userType = $userDetails['useType'];

    // Récupérer les activités de l'utilisateur
    $activities = $db->getActivitiesForUser($userId);
    if ($userType == 'S') {
        $activityType = 'participe';
    } else if ($userType == 'T') {
        $activityType = 'organise';
    }
} else {
    // Gérer le cas où l'utilisateur n'existe pas
    $userFirstname = '';
    $userLastname = '';
    $userType = '';
    $activities = [];
    $activityType = '';
    $userNotFound = true;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?= htmlspecialchars($userFirstname) . ' ' . htmlspecialchars($userLastname) ?></title>
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
        <?php if (isset($userNotFound) && $userNotFound): ?>
            <h2 id="secondTitle">L'utilisateur n'existe pas.</h2>
            <br>
            <div id="contentContainer">
                <a id="pageBefore" href="../../index.php"><-Retour à l'accueil</a>
            </div>
        <?php else: ?>
            <h2 id="secondTitle">Profil de <?= htmlspecialchars($userFirstname) . ' ' . htmlspecialchars($userLastname) ?></h2>
            <hr>
            <br>
            <h3 id="thirdTitle"><?= htmlspecialchars($userFirstname) . ' ' .  htmlspecialchars($userLastname) . ' ' . htmlspecialchars($activityType) ?> :</h3>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Nom de l'activité</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($activities)): ?>
                        <?php foreach ($activities as $activity): ?>
                            <tr>
                                <td><?= htmlspecialchars($activity['actTitle']) ?></td>
                                <td><?= htmlspecialchars($activity['actDescription']) ?></td>
                                <td><button onclick="window.location.href='activitiesDetailsAndList.php?id=<?= htmlspecialchars($activity['idActivity']) ?>'">Consulter</button></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Aucune activité trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
    <footer>
        <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
    </footer>
    <script src="../js/script.js"></script>
</body>
</html>

