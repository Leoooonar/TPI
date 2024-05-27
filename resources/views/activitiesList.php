<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 23.05.2024       
//Description : Page répertoriant liste des activités du site
//
// Version : 2.0.0
// Date : 27.05.2024
// Description : Ajout de la méthode getAllActivites et d'un bouton d'inscription pour les élèves 

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

// Récupère toutes les activités depuis la base de données
$activities = $db->getAllActivities();

foreach ($activities as &$activity) {
    $organizer = $db->getActivityOrganizer($activity['idActivity']);
    if ($organizer) {
        $activity['organizerFirstname'] = $organizer['useFirstname'] ?? '';
        $activity['organizerName'] = $organizer['useLastname'] ?? '';
        $activity['actDescription'] .= ' (Organisé par ' . htmlspecialchars($organizer['useFirstname']) . ' ' . htmlspecialchars($organizer['useLastname']) . ')';
    } else {
        $activity['organizerFirstname'] = '';
        $activity['organizerName'] = '';
    }

    // Vérifie la capacité pour définir le statut
    $capacityCheck = $db->checkActivityCapacity($activity['idActivity']);
    if ($capacityCheck) {
        $activity['status'] = '<span style="color: green;">DISPONIBLE</span>';
    } else {
        $activity['status'] = '<span style="color: red;">INDISPONIBLE</span>';
    }
}
unset($activity); // Casse la référence du dernier élément
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des activités</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
    <body>
        <main>
            <div class="headContainer">
                <nav class="navbar">
                    <ul>
                        <div class="left-content">
                            <div class="active">
                            <a href="#"><li><h1>LISTE DES ACTIVITES</h1></li></a>
                            </div>
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
            <h2 id="secondTitle">Liste des activités</h2>
            <hr>
            <br>
            <div id="contentContainer">
            <?php
                if (isset($_GET['subscribe'])) {
                    if ($_GET['subscribe'] === 'success') {
                        echo '<div class="success-message">Vous vous êtes inscrit avec succès à cette activité.</div>';
                        echo '<br>';
                    } elseif ($_GET['subscribe'] === 'error') {
                        echo '<span style="color:red;">Erreur</span>.Veuillez réessayer plus tard.';
                        echo '<br>';
                        echo '<br>';
                    }
                    elseif ($_GET['subscribe'] === 'full') {
                        echo '<div id="errormsg">';
                        echo '<p>Erreur.</p>';
                        echo '</div>';
                        echo '<br>';
                        echo 'La capacité maximale de cette activité est atteinte. Vous ne pouvez pas vous inscrire.';
                        echo '<br>';
                        echo '<br>';
                    }
                }
            ?>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>NOM</th>
                        <th>STATUT</th>
                        <th>DESCRIPTION</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($activities as $activity) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($activity['actTitle']) . '</td>';
                    echo '<td>' . $activity['status'] . '</td>';
                    echo '<td>' . htmlspecialchars($activity['actDescription']) . '</td>';
                    echo '<td>';
                    if ($user['useType'] == 'S' && strpos($activity['status'], 'DISPONIBLE') !== false) {
                        echo '<button class="add-button" onclick="window.location.href=\'../../controllers/subscribe.php?id=' . $activity['idActivity'] . '\'">S\'inscrire</button>';
                    }
                    echo '<button onclick="window.location.href=\'activitiesDetailsAndList.php?id=' . $activity['idActivity'] . '\'">Consulter</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="../js/script.js"></script>
    </body>
</html>