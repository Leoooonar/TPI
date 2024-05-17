<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 17.05.2024       
//Description : Page gérant fonctions directement lié à la base de données, permet la connexion à la db.  

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
            $this->conn = new PDO("mysql:host={$this->host};port={$this->port};dbname={$this->database}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection échouée: " . $e->getMessage();
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
}