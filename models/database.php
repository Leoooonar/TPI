<?php
//ETML
//Auteur: Leonar Dupuis                                            
//Date: 17.05.2024       
//Description : Page gérant fonctions directement lié à la base de données, permet la connexion à la db.  
//
// Version : 2.0.0
// Date : 21.05.2024
// Description : Ajout des méthodes checkLogin et registerUser
//
// Version : 3.0.0
// Date : 22.05.2024
// Description : Ajout de la méthode UpdateUserInfo
//
// Version : 4.0.0
// Date : 23.05.2024
// Description : Ajout des méthodes createActivity, getActivityById, updateActivity, deleteActivity et getActivitiesForUser
//
// Version : 5.0.0
// Date : 24.05.2024
// Description : Ajout des méthodes searchUsers, getParticipantsForActivity, getActivityOrganizer et removeParticipantFromActivity
//



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
            throw new PDOException("Connexion à la base de données non établie.");
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
    $query = "SELECT * FROM t_user 
    WHERE useNickname = :username";
    $stmt = $this->queryPrepare($query, 
    array(':username' => $username));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['usePassword'])) {
        //Le mot de passe est correct, le stock dans la session
        $_SESSION['user'] = $row;
        return true;
    }
    //si nom d'utilisateur ou le mot de passe incorrect -> false
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

//Méthode pour faire la mise à jour des données utilisateur
public function updateUserInfo($userId, $newNickname, $newFirstname, 
$newLastname, $newEmail, $newGender) 
{
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
            throw new Exception("Erreur lors de la mise à jour 
            des informations de l'utilisateur.");
        }

        return true; // Retourne true si la mise à jour est réussie
    } catch(Exception $e) {
        // Gère l'erreur
        echo "Error: " . $e->getMessage();
        return false; // Retourne false en cas d'erreur
    }
}

    /////////////////////////////////////////////////////////////////////
    //                         GESTION ACTIVITES                       //
    /////////////////////////////////////////////////////////////////////
    
    //Recherche les activités d'un utilisateur
    public function getActivitiesForUser($userId) {
        $sql = "SELECT a.idActivity, a.actTitle, a.actDescription 
                FROM t_activity a
                JOIN t_participer p ON a.idActivity = p.fkActivity
                WHERE p.fkUser = ?";
        $params = [$userId];
        
        $stmt = $this->queryPrepare($sql, $params);
    
        // Vérifie si des activités ont été trouvées
        if ($stmt && $stmt->rowCount() > 0) {
            $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $activities;
        } else {
            return []; // Aucune activité trouvée
        }
    }
     
    // Crée une nouvelle activité
    public function createActivity($title, $description, $capacity, $userId) {
        $sql = "INSERT INTO t_activity (actTitle, actDescription, actCapacity) 
        VALUES (?, ?, ?)";
        $params = [$title, $description, $capacity];

        $stmt = $this->queryPrepare($sql, $params);
        if ($stmt) {
            $activityId = $this->conn->lastInsertId();

            // Associer l'activité à l'utilisateur qui l'a créée
            $sqlLink = "INSERT INTO t_participer (fkUser, fkActivity) 
            VALUES (?, ?)";
            $paramsLink = [$userId, $activityId];
            $stmtLink = $this->queryPrepare($sqlLink, $paramsLink);

            if ($stmtLink) {
                return $activityId;
            }
        }
        return false;
    }

//Récupère le nombre de participants d'une activité de type élèves
public function getParticipantCount($activityId) {
    $sql = "SELECT COUNT(*) as participantCount 
            FROM t_participer 
            JOIN t_user ON t_participer.fkUser = t_user.idUser
            WHERE fkActivity = ? AND t_user.useType = 'S'";
    $params = [$activityId];
    $stmt = $this->queryPrepare($sql, $params);
    
    if ($stmt && $stmt->rowCount() > 0) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data['participantCount'];
    } else {
        return 0;
    }
}

// Mise à jour des informations de l'activité sélectionné par l'enseignant
public function updateActivity($activityId, $title, $description, $capacity) {
// Requête SQL pour mettre à jour l'activité
$query = "UPDATE t_activity 
SET actTitle = :title, actDescription = :description, actCapacity = :capacity 
WHERE idActivity = :activityId";

// Paramètres de la requête
$params = array(
    ":title" => $title,
    ":description" => $description,
    ":capacity" => $capacity,
    ":activityId" => $activityId
);
    $stmt = $this->queryPrepare($query, $params);

    // Retourne vrai si la mise à jour a réussi, sinon faux
    return $stmt !== false;
}

// Supprime une activité et les participations associées
public function deleteActivity($activityId) {
    // Supprimer les participations associées à l'activité
    $sqlDeleteParticipations = "DELETE FROM t_participer WHERE fkActivity = ?";
    $paramsDeleteParticipations = [$activityId];
    $stmtDeleteParticipations = $this->queryPrepare($sqlDeleteParticipations, $paramsDeleteParticipations);

    // Supprimer l'activité
    $sqlDeleteActivity = "DELETE FROM t_activity WHERE idActivity = ?";
    $paramsDeleteActivity = [$activityId];
    $stmtDeleteActivity = $this->queryPrepare($sqlDeleteActivity, $paramsDeleteActivity);

    // Vérifier si les deux suppressions ont réussi
    if ($stmtDeleteParticipations !== false && $stmtDeleteActivity !== false) {
        return true;
    } else {
        return false;
    }
}

// Méthode pour récupérer les détails d'une activité par son ID
public function getActivityById($activityId) {
    try {
        // Requête SQL avec un paramètre :activity_id
        $query = "SELECT * FROM t_activity 
        WHERE idActivity = :activity_id";

        // Paramètres à passer à la requête
        $params = array(':activity_id' => $activityId);

        // Exécute la requête préparée avec des paramètres
        $stmt = $this->queryPrepare($query, $params);

        // Récup. la ligne du résultat en tableau assiociatif
        $activityDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retourne les détails de l'activité
        return $activityDetails;
    } catch(PDOException $e) {
        // Gère l'erreur
        echo "Error: " . $e->getMessage();
        return false;
    }
}

//Récupère les informations d'un utilisateur type enseignant
public function getActivityOrganizer($activityId) {
    $sql = "SELECT u.idUser, u.useFirstname, u.useLastname 
            FROM t_user u
            JOIN t_participer p ON u.idUser = p.fkUser
            WHERE p.fkActivity = ? AND u.useType = 'T'";
    $params = [$activityId];
    $stmt = $this->queryPrepare($sql, $params);
    
    if ($stmt && $stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return null; // Aucun enseignant trouvé
    }
}

// Permet d'obtenir la capacité et le nombre actuel de participants de type élèves
public function checkActivityCapacity($activityId) {
    $sql = "SELECT actCapacity, 
                    (SELECT COUNT(*) 
                    FROM t_participer 
                    JOIN t_user ON t_participer.fkUser = t_user.idUser
                    WHERE fkActivity = ? AND t_user.useType = 'S') 
                    as currentParticipants
            FROM t_activity
            WHERE idActivity = ?";
    $params = [$activityId, $activityId];
    $stmt = $this->queryPrepare($sql, $params);
    
    if ($stmt && $stmt->rowCount() > 0) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data['actCapacity'] > $data['currentParticipants'];
    } else {
        return false;
    }
}

//Recherche les participants inscris à une activité
public function getParticipantsForActivity($activityId) {
    $query = "
        SELECT u.idUser, u.useFirstname, 
        u.useLastname, u.useNickname
        FROM t_participer p
        JOIN t_user u ON p.fkUser = u.idUser
        WHERE p.fkActivity = ? AND u.useType = 'S'
    ";
    $stmt = $this->queryPrepare($query, array($activityId));
    return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
}    

// Rechercher des utilisateurs par prénom ou nom ou pseudo
public function searchUsers($searchTerm) {
    $query = "SELECT idUser, useFirstname, useLastname, useNickname 
                FROM t_user 
                WHERE (useFirstname 
                LIKE :searchTerm 
                OR useLastname 
                LIKE :searchTerm 
                OR useNickname 
                LIKE :searchTerm)
                AND useType = 'S'";
    $params = array(':searchTerm' => '%' . $searchTerm . '%');
    return $this->queryPrepare($query, $params)->fetchAll(PDO::FETCH_ASSOC);
}    

// Ajouter un participant à une activité
public function addParticipantToActivity($userId, $activityId) {
    $query = "INSERT INTO t_participer (fkUser, fkActivity) 
    VALUES (:userId, :activityId)";
    $params = array(':userId' => $userId, 
    ':activityId' => $activityId);
    return $this->queryPrepare($query, $params);
}


// Méthode pour supprimer un participant d'une activité
public function removeParticipantFromActivity($userId, $activityId) {
    try {
        // Requête SQL pour supprimer le participant de l'activité
        $query = "DELETE FROM t_participer 
        WHERE fkUser = :userId 
        AND fkActivity = :activityId";

        // Paramètres de la requête
        $params = array(':userId' => $userId, 
        ':activityId' => $activityId);

        // Exécute la requête avec la méthode queryPrepare
        $stmt = $this->queryPrepare($query, $params);

        // Retourne true si la suppression a réussi
        return $stmt !== false;
    } catch (PDOException $e) {
        // En cas d'erreur, affiche l'erreur et retourne false
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}

    // Récupères toutes les activités du site 
    public function getAllActivities() {
        $query = "SELECT * FROM t_activity";
        $stmt = $this->queryPrepare($query);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }    

    /////////////////////////////////////////////////////////////////////
    //                  CONSULTATION PROFIL UTILISATEUR                //
    /////////////////////////////////////////////////////////////////////
    
//Récupère les infos d'un utilisateur à partir de l'id
public function getUserById($userId) {
    $sql = "SELECT * FROM t_user WHERE idUser = ?";
    $stmt = $this->queryPrepare($sql, [$userId]);
    if ($stmt && $stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return null;
    }
}
}
?>