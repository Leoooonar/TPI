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

// Récupérer les organisateurs pour chaque activité
foreach ($activities as &$activity) {
    $organizer = $db->getActivityOrganizer($activity['idActivity']);
    if ($organizer) {
        $activity['organizerFirstname'] = $organizer['useFirstname'] ?? '';
        $activity['organizerName'] = $organizer['useLastname'] ?? '';
    } else {
        // Si aucun organisateur n'est trouvé, définir les valeurs par défaut
        $activity['organizerFirstname'] = '';
        $activity['organizerName'] = '';
    }
}

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
                                    <td><?= htmlspecialchars($activity['organizerName']) ?> <?= htmlspecialchars($activity['organizerFirstname']) ?></td>
                                <?php endif; ?>
                                <td><?= htmlspecialchars($activity['actDescription']) ?></td>
                                <td>
                                    <?php if ($user['useType'] == 'S'): ?>
                                        <button onclick="window.location.href='activitiesDetailsAndList.php?id=<?= $activity['idActivity'] ?>'">Consulter</button>
                                        <form method="POST" action="../../controllers/unsubscribe.php">
                                            <input type="hidden" name="activityId" value="<?= $activity['idActivity'] ?>">
                                            <button type="submit" class="delete-button">Se désinscrire</button>
                                        </form>                                   
                                        <?php else: ?>
                                        <button onclick="window.location.href='activitiesDetailsAndList.php?id=<?= $activity['idActivity'] ?>'">Consulter</button>
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
                    <div id="searchResult">
                    <?php
                    if (isset($_GET['unsubscribe']) && $_GET['unsubscribe'] === 'success') {
                        echo '<div class="success-message">Vous vous êtes désinscrit avec succès de cette activité.</div>';
                        echo '<br>';
                        echo '</div>'; 
                    } elseif ($_GET['unsubscribe'] === 'error') {
                        echo 'Une <span style="color:red;">erreur</span> est survenue lors de votre désinscription.';
                        echo '<br>';
                    } elseif ($_GET['unsubscribe'] === 'missing') {
                        echo 'L\'identifiant de l\'activité est <span style="color:red;">manquant</span>.';
                        echo '<br>';
                    }
                    ?>
                    </div>
                    <p>Vous n'avez aucune activité pour le moment.</p>
            <?php endif; ?>
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="../js/script.js"></script>
    </body>
</html>