<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 22.05.2024       
//Description : Page d'édition des informations de l'utilisateur 

session_start();

// Inclure le fichier database.php
include("../models/database.php");

// Initialiser le tableau des erreurs
$errors = array();

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

$user = $_SESSION['user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire soumis
    $newUsername = $_POST['username'];
    $newFirstname = $_POST['firstname'];
    $newLastname = $_POST['lastname'];
    $newEmail = $_POST['email'];
    $newGender = $_POST['gender'];

    if (!preg_match("/^[a-zA-Z]*$/", $newFirstname)) {
        $errors[] = "Le prénom ne doit contenir que des lettres";
    }

    if (!preg_match("/^[a-zA-Z]*$/", $newLastname)) {
        $errors[] = "Le nom ne doit contenir que des lettres";
    }

    // Si des erreurs sont détectées, afficher les messages d'erreur
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    } else {
        // Met à jour les informations de l'utilisateur dans la base de données
        $db->updateUserInfo($user['idUser'], $newUsername, $newFirstname, $newLastname, $newEmail, $newGender);

        $_SESSION['user']['useUsername'] = $newUsername;
        $_SESSION['user']['useFirstname'] = $newFirstname;
        $_SESSION['user']['useLastname'] = $newLastname;
        $_SESSION['user']['useGender'] = $newGender;
        $_SESSION['user']['useEmail'] = $newEmail;

        // Redirige l'utilisateur vers sa page de profil après la mise à jour
        header("Location: ../resources/views/userDetails.php");
        exit(); 
    }
}
?>