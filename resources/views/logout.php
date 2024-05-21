<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 21.05.2024       
//Description : Page permettant de se déconnecter

session_start();

// Détruire toutes les données de session
session_destroy();

// Redirige vers l'accueil
header("Location: ../../index.php");
exit();
?>