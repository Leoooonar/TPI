<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 23.05.2024       
//Description : Page relatif aux activités de l'utilisateur
//
// Version : 1.0.1
// Date : 27.05.2024
// Description : Optimisation méthode getActivitesForUser (1 paramètre en -), + correction bug qui répétion activité (ajout d'un unset)

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
$activities = $db->getActivitiesForUser($user['idUser']);

// Récupérer les organisateurs pour chaque activité
foreach ($activities as &$activity) {
    $organizer = $db->getActivityOrganizer($activity['idActivity']);
    if ($organizer) {
        $activity['organizerFirstname'] = $organizer['useFirstname'] ?? '';
        $activity['organizerName'] = $organizer['useLastname'] ?? '';
    } else {
        $activity['organizerFirstname'] = '';
        $activity['organizerName'] = '';
    }
}
unset($activity); // Casse la référence avec le dernier élément

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
                        <?php if ($isLoggedIn): ?>
                            <li class="nav-item dropdown">
                                <div class="active">
                                    <h1>MON COMPTE</h1>
                                </div>
                                <a href="javascript:void(0)" class="dropbtn"></a>
                                <div class="dropdown-content">
                                    <a href="userDetails.php">Détails du compte</a>
                                    <a href="myActivities.php">Mes activités</a>
                                    <a href="logout.php">Déconnexion</a>
                                </div>
                            </li>
                        <?php else: ?>
                            <li class="nav-item dropdown">
                                <a href="./resources/views/authentification/login.php"><h1>SE CONNECTER</h1></a>
                                <a href="javascript:void(0)" class="dropbtn"></a>
                                <div class="dropdown-content">
                                    <a href="./resources/views/authentification/register.php">Inscription</a>
                                </div>
                            </li>
                        <?php endif; ?>
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
        <div id="searchResult">
            <div id="contentContainer">
                <?php if (isset($_GET['create']) && $_GET['create'] === 'success'): ?>
                    <div class="success-message">L'activité a bien été créée.</div>
                    <br>
                <?php elseif ($_GET['create'] === 'error'): ?>
                    <span style="color:red;">Erreur lors de la création de l'activité.</span>
                    <br>
                <?php endif; ?>
                <?php if (isset($_GET['edit']) && $_GET['edit'] === 'success'): ?>
                    <div class="success-message">L'activité a bien été modifié.</div>
                    <br>
                <?php elseif ($_GET['edit'] === 'error'): ?>
                    <span style="color:red;">Erreur lors de la modification de l'activité.</span>
                    <br>
                <?php endif; ?>
                <?php if (isset($_GET['delete']) && $_GET['delete'] === 'success'): ?>
                    <div class="success-message">L'activité a bien été supprimée.</div>
                    <br>
                <?php elseif ($_GET['delete'] === 'error'): ?>
                    <span style="color:red;">Erreur lors de la supression de l'activité.</span>
                    <br>
                <?php endif; ?>
            </div>
        </div>
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
                    <?php if (isset($_GET['unsubscribe']) && $_GET['unsubscribe'] === 'success'): ?>
                        <div class="success-message">Vous vous êtes désinscrit avec succès de cette activité.</div>
                        <br>
                    <?php elseif ($_GET['unsubscribe'] === 'error'): ?>
                        <span style="color:red;">Une erreur est survenue lors de votre désinscription.</span>
                        <br>
                    <?php elseif ($_GET['unsubscribe'] === 'missing'): ?>
                        <span style="color:red;">L'identifiant de l'activité est manquant.</span>
                        <br>
                    <?php endif; ?>
                </div>
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
