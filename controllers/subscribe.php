<?php
// ETML
// Auteur: Leonar Dupuis                                            
// Date: 27.05.2024       
// Description : Page pour inscrire un élève à une activité

session_start();
include("../models/database.php");
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

// Vérifie si l'utilisateur est un élève
if ($user['useType'] != 'S') {
    header("Location: ../resources/views/activitiesList.php"); // Rediriger vers la page des activités si l'utilisateur n'est pas un élève
    exit();
}

// Vérifie si l'identifiant de l'activité est fourni
if (isset($_GET['id'])) {
    $activityId = $_GET['id'];

    // Vérifie la capacité de l'activité
    if ($db->checkActivityCapacity($activityId)) {
        // Ajoute l'élève à l'activité si la capacité n'est pas atteinte
        if ($db->addParticipantToActivity($user['idUser'], $activityId)) {
            // Redirige vers la page des activités avec un message de succès
            header("Location: ../resources/views/activitiesList.php?subscribe=success");
        } else {
            // Redirige vers la page des activités avec un message d'erreur
            header("Location: ../resources/views/activitiesList.php?subscribe=error");
        }
    } else {
        // Capacité atteinte
        header("Location: ../resources/views/activitiesList.php?subscribe=full");
    }
}
?>
