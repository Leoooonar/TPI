<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 21.05.2024       
//Description : Page de vérification de l'inscription du site   

session_start();

// Inclure le fichier database.php
include("../../models/database.php");

// Initialiser le tableau des erreurs
$errors = array();

// Vérifie si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION['user']);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../../../resources/css/style.css">
</head>
<body>
    <main>
        <div class="headContainer">
            <nav class="navbar">
                <ul>
                    <div class="left-content">
                        <li><h1>LISTE DES ACTIVITES</h1></li>
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
                                echo '<a href="./resources/views/userDetails.php">Détail du compte</a>';
                                echo '<a href="./resources/views/myActivities.php">Mes activités</a>';
                                echo '<a href="./resources/views/logout.php">Déconnexion</a>';
                                echo '</div>';
                                echo '</li>';
                            } else {
                                echo '<li class="nav-item dropdown">';
                                echo '<a href="../../resources/views/authentification/login.php"><h1>SE CONNECTER</h1></a>';
                                echo '<a href="javascript:void(0)" class="dropbtn"></a>';
                                    echo '<div class="dropdown-content">';
                                    echo '<a href="../../resources/views/authentification/register.php">Inscription</a>'; 
                                    echo '</div>';
                                echo '</li>';
                            }
                        ?>
                    </div>
                </ul>
            </nav>
        </div>    
<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $name = $_POST['name'];
    $confirm_password = $_POST['confirm_password'];

    // Vérifier si le nom d'utilisateur est vide
    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est requis";
    }

    // Vérifier si le prénom est vide
    if (empty($firstname)) {
        $errors[] = "Le prénom est requis";
    }
    
    // Vérifier si le nom est vide
    if (empty($name)) {
        $errors[] = "Le nom est requis";
    }

    // Vérifier si le mot de passe de confirmation correspond
    if ($password != $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }

    // Si aucune erreur n'a été détectée, procéder à l'inscription
    if (empty($errors)) {
        // Créer une instance de la classe Database
        $db = new Database();

        // Appeler la méthode pour vérifier l'inscription dans database.php
        $result = $db->registerUser($username, $password, $firstname, $name);

        // Vérifier le résultat de l'inscription
        if ($result) {
            // Rediriger l'utilisateur vers la page de connexion par exemple
            header("Location: ../../index.php");
            exit();
        } else {
            // Une erreur s'est produite lors de l'inscription
            $errors[] = "Une erreur s'est produite lors de l'inscription. Veuillez réessayer.";
        }
    }
}

// Si des erreurs sont survenues, afficher les erreurs
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo '<div id="contentContainer">';
        echo $error . "<br>";
        echo "<br>";
    }
}
?>
<br>
<a id="pageBefore" href="../../resources/views/authentification/login.php"><-Page précédente</a>
</div>  
    </main>
    <footer>
        <p class="item-2">Leonar Dupuis<br><a id="mail" href="mailto:sportetculture@gmail.com">sportetculture@gmail.com</a></p> 
    </footer>
    <script src="./resources/js/script.js"></script>
</body>
</html>
