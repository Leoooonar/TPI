<?php
// ETML
// Auteur: Leonar Dupuis                                            
// Date: 23.05.2024       
// Description : Contrôleur pour supprimer une activité

session_start();
include("../models/database.php");
$db = new Database();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../views/authentification/login.php"); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Vérifie si l'utilisateur est un enseignant
$user = $_SESSION['user'];
if ($user['useType'] != 'T') {
    header("Location: ../views/activitiesList.php"); // Redirige vers la liste des activités si l'utilisateur n'est pas un enseignant
    exit();
}

// Vérifie si l'ID de l'activité à supprimer est fourni dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $activityId = $_GET['id'];

    // Supprime l'activité
    $success = $db->deleteActivity($activityId);

    if ($success) {
        // Redirige vers la page de liste des activités après la suppression
        header("Location: ../resources/views/myActivities.php?delete=success");
        exit();
    } else {
        header("Location: ../resources/views/myActivities.php?delete=error");
        exit();
    }
} else {
    echo '<div id="contentContainer">';
    echo '<br>';
    echo "ID de l'activité non fourni.";
    echo '<br>';
    echo '<a id="pageBefore" href="../../resources/views/myActivities.php"><-Page précédente</a>';
    echo '</div>';
}
?>
