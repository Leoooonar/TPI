<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 23.05.2024       
//Description : Page détails d'une activité

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

// Récupérer l'ID de l'activité à afficher depuis l'URL
$activityId = $_GET['id'];

// Récupérer les détails de l'activité depuis la base de données
$activityDetails = $db->getActivityById($activityId);

// Récupérer les détails de l'activité
$activityTitle = $activityDetails['actTitle'];
$activityDescription = $activityDetails['actDescription'];
$activityCapacity = $activityDetails['actCapacity'];

// Récupérer la liste des participants à l'activité depuis la base de données
$participants = $db->getParticipantsForActivity($activityId);

// Traitement de la recherche d'utilisateurs
$searchResults = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchTerm'])) {
    $searchTerm = $_POST['searchTerm'];
    $searchResults = $db->searchUsers($searchTerm);
}

// Traitement de l'ajout d'un participant
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
    $userId = $_POST['userId'];
    $db->addParticipantToActivity($userId, $activityId);
    // Rafraîchir la liste des participants
    $participants = $db->getParticipantsForActivity($activityId);
}

// Traitement de la suppression d'un participant
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeUserId'])) {
    $removeUserId = $_POST['removeUserId'];
    $db->removeParticipantFromActivity($removeUserId, $activityId);
    // Rafraîchir la liste des participants
    $participants = $db->getParticipantsForActivity($activityId);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'activité</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
    <body>
        <main>
            <div class="headContainer">
                <nav class="navbar">
                    <ul>
                        <div class="left-content">
                            <div class="active">
                                <a href="activitiesList.php"><li><h1>LISTE DES ACTIVITES</h1></li></a>
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
                                    echo '<a href="./authentification/login.php"><h1>SE CONNECTER</h1></a>';
                                    echo '<a href="javascript:void(0)" class="dropbtn"></a>';
                                        echo '<div class="dropdown-content">';
                                        echo '<a href="./authentification/register.php">Inscription</a>'; 
                                        echo '</div>';
                                    echo '</li>';
                                }
                            ?>
                        </div>
                    </ul>
                </nav>
            </div>    
            <br>
            <h2 id="secondTitle">Détails de l'activité</h2>
            <hr>
            <br>
            <div id="contentContainer">
                <div id="textBlock">
                    <p id="secondParagraph">
                    Cliquez sur le nom d’un enseignant pour consulter les activités qu’il organise et
                    sur celui d’un élève pour découvrir celles auxquelles il participe !
                    </p>
            </div>
            <?php
                if ($isLoggedIn) {
                    echo '<div class="infoContainer">';
                        echo '<p><strong>Nom:</strong> ' . $activityTitle . '</p>';
                        echo '<br>';
                        echo '<p><strong>Organisateur:</strong> ' . $user['useFirstname'] . ' ' . $user['useLastname'] . '</p>';
                        echo '<br>';
                        echo '<p><strong>Max de participants:</strong> ' . $activityCapacity . '</p>';
                        echo '<br>';
                        echo '<p><strong>Statut:</strong> ' . $user['useType'] . '</p>';
                        echo '<br>';
                        echo '<p><strong>Description:</strong> ' . $activityDescription . '</p>';
                    echo '</div>';
                }
            ?> 
            <br>

            <h2 id="secondTitle">Liste des participants</h2>
            <?php if ($user['useType'] == 'T'): ?>
                <form method="POST">
                    <input type="text" name="searchTerm" placeholder="Rechercher un utilisateur">
                    <br>
                    <button type="submit">Rechercher</button>
                    <br>
                </form>
                <?php if (!empty($searchResults)): ?>
                    <ul>
                        <?php foreach ($searchResults as $result): ?>
                            <li class="addUser">
                            <div id="searchResult">
                                <?= htmlspecialchars($result['useFirstname'] . ' ' . $result['useLastname'] . ' (' . $result['useNickname'] . ')') ?>
                                </div>
                                <form method="POST" style="display:inline;">
                                        <input type="hidden" name="userId" value="<?= htmlspecialchars($result['idUser']) ?>">
                                    <button type="submit">Ajouter</button>
                                </form>
                            </li>
                            <br>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (!empty($participants)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($participants as $participant): ?>
                            <tr>
                                <td><?= htmlspecialchars($participant['useFirstname']) ?></td>
                                <td><?= htmlspecialchars($participant['useLastname']) ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="removeUserId" value="<?= htmlspecialchars($participant['idUser']) ?>">
                                        <button type="submit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div id="contentContainer">
                    <p>Aucun participant pour le moment.</p>
                </div>
            <?php endif; ?>
            <br>
        </main>
        <footer>
            <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
        </footer>
        <script src="../js/script.js"></script>
    </body>
</html>