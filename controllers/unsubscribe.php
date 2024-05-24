<?php
// ETML
// Auteur: Leonar Dupuis
// Date: 24.05.2024
// Description : Page de désinscription à une activité (Eleve)

session_start();
include("../models/database.php");

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../resources/views/authentification/login.php"); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Vérifie si l'identifiant de l'activité est présent dans la requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activityId'])) {
    // Récupère l'ID de l'activité depuis la requête POST
    $activityId = $_POST['activityId'];

    // Récupère l'ID de l'utilisateur depuis la session
    $userId = $_SESSION['user']['idUser'];

    // Connexion à la base de données
    $db = new Database();

    // Désinscription de l'utilisateur de l'activité
    $success = $db->removeParticipantFromActivity($userId, $activityId);

    // Redirection vers la page des activités de l'utilisateur avec un message de succès ou d'erreur
    if ($success) {
        header("Location: ../resources/views/myActivities.php?unsubscribe=success");
    } else {
        header("Location: ../resources/views/myActivities.php?unsubscribe=error");
    }
} else {
    // Redirection vers la page des activités avec un message d'erreur si l'identifiant de l'activité est manquant
    header("Location: ../resources/views/myActivities.php?unsubscribe=missing");
}
?>
