<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 23.05.2024       
//Description : Page relatif aux activités de l'utilisateur

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

// Récupérer les activités pour l'utilisateur connecté
$activities = $db->getActivitiesForUser($user['idUser'], $user['useType']);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste de mes activités</title>
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
            <h2 id="secondTitle">Liste de mes activités</h2>
            <hr>
            <br>
            <div id="createButton">
            <?php if ($user['useType'] == 'T'): ?>
                <a href="createActivities.php"><button>Créer une activité</button></a>
            <?php endif; ?>
            </div>
            <br>
            <?php if (!empty($activities)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>NOM</th>
                            <?php if ($user['useType'] == 'S'): ?>
                                <th>ORGANISATEUR</th>
                            <?php endif; ?>
                            <th>DESCRIPTION</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activities as $activity): ?>
                            <tr>
                                <td><?= htmlspecialchars($activity['actTitle']) ?></td>
                                <?php if ($user['useType'] == 'S'): ?>
                                    <td><?= htmlspecialchars($activity['organizerName']) ?></td>
                                <?php endif; ?>
                                <td><?= htmlspecialchars($activity['actDescription']) ?></td>
                                <td>
                                    <?php if ($user['useType'] == 'S'): ?>
                                        <button onclick="window.location.href='details.php?id=<?= $activity['idActivity'] ?>'">Consulter</button>
                                        <button onclick="window.location.href='unsubscribe.php?id=<?= $activity['idActivity'] ?>'">Se désinscrire</button>
                                    <?php else: ?>
                                        <button onclick="window.location.href='activitiesDetailsAndList.php?id=<?= $activity['idActivity'] ?>'">Détails</button>
                                        <button onclick="window.location.href='activityEdit.php?id=<?= $activity['idActivity'] ?>'">Modifier</button>
                                        <button class="delete-button" onclick="confirmDelete(<?= $activity['idActivity'] ?>)">Supprimer</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div id="contentContainer">
                    <p>Vous n'avez aucune activité pour le moment.</p>
                </div>
            <?php endif; ?>
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="../js/script.js"></script>
    </body>
</html>