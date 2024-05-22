<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 17.05.2024       
//Description : Page gérant fonctions directement lié à la base de données, permet la connexion à la db.  
//
// Version : 1.0.1
// Date : 21.05.2024
// Description : Ajout des méthodes checkLogin et registerUser
//
// Version : 1.0.1
// Date : 22.05.2024
// Description : Ajout de la méthode UpdateUserInfo

class Database {
    private $host = "host.docker.internal"; 
    private $port = "6033"; 
    private $username = "root";
    private $password = "root";
    private $database = "db_sportetculture";
    private $conn;

    //Constructeur
    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};
            port={$this->port};
            dbname={$this->database}", 
            $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connexion réussie: ";
        } catch(PDOException $e) {
            echo "Connexion échouée: " . $e->getMessage();
            exit();
        }
    }
    
    //Méthode pour préparer et exécuter une requête avec des paramètres
    private function queryPrepare($query, $params = array()) {
        try {
            // Vérifie si la connexion à la base de données est établie
            if(!$this->conn) {
                throw new PDOException("La connexion à la base de données n'est pas établie.");
            }

            // Prépare la requête SQL
            $stmt = $this->conn->prepare($query);

            // Vérifie si la préparation de la requête a échoué
            if(!$stmt) {
                throw new PDOException("Erreur lors de la préparation de la requête.");
            }

            // Exécute la requête avec les paramètres fournis
            $result = $stmt->execute($params);

            // Vérifie si l'exécution de la requête a échoué
            if(!$result) {
                throw new PDOException("Erreur lors de l'exécution de la requête.");
            }

            // Retourne l'objet PDOStatement résultant
            return $stmt;
        } catch(PDOException $e) {
            // Gère l'erreur
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /////////////////////////////////////////////////////////////////////
    //                      GESTION UTILISATEURS                       //
    /////////////////////////////////////////////////////////////////////
    
    //Méthode pour vérifier le login
    public function checkLogin($username, $password) {
        $query = "SELECT * FROM t_user WHERE useNickname = :username";
        $stmt = $this->queryPrepare($query, array(':username' => $username));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row && password_verify($password, $row['usePassword'])) {
            // Le mot de passe est correct, stocker l'utilisateur dans la session
            $_SESSION['user'] = $row;
            return true;
        }
    
        // si le nom d'utilisateur ou le mot de passe est incorrect, retourne false
        return false;
    }
        
    public function registerUser($username, $password, $firstname, $name) {
        try {
            // Hasher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
            // Préparer la requête d'insertion
            $query = "INSERT INTO t_user (useNickname, usePassword, 
            useFirstname, useLastname, useType) 
                      VALUES (:username, :password, :firstname, :name, 'S')";
            $params = array(
                ':username' => $username,
                ':password' => $hashed_password,
                ':firstname' => $firstname,
                ':name' => $name
            );
            $this->queryPrepare($query, $params);
    
            // Si l'insertion a réussi, retourner true
            return true;
        } catch(PDOException $e) {
            // Une erreur s'est produite lors de l'insertion
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function updateUserInfo($userId, $newNickname, $newFirstname, $newLastname, $newEmail, $newGender) {
        try {
            $query = "UPDATE t_user 
                      SET useNickname = :username,
                          useFirstname = :firstname, 
                          useLastname = :lastname, 
                          useEmail = :email, 
                          useGender = :gender 
                      WHERE idUser = :userId";
    
            $params = array(
                ':username' => $newNickname,
                ':firstname' => $newFirstname,
                ':lastname' => $newLastname,
                ':email' => $newEmail,
                ':gender' => $newGender,
                ':userId' => $userId
            );
    
            $result = $this->queryPrepare($query, $params);
    
            if ($result === false) {
                throw new Exception("Erreur lors de la mise à jour des informations de l'utilisateur.");
            }
    
            return true; // Retourne true si la mise à jour est réussie
        } catch(Exception $e) {
            // Gère l'erreur
            echo "Error: " . $e->getMessage();
            return false; // Retourne false en cas d'erreur
        }
    }
    
    
}
?>