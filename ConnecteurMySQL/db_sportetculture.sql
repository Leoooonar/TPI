-- ETML
-- Auteur : Leonar
-- Date : 17.05.2024
-- Description : Script SQL à importer dans PhpMyAdmin pour les créations des tables, de 3 enseignants et 5 élèves.
-- 
-- Version : 1.1.0
-- Date : 21.05.2024
-- Description : Supression de la table t_student et t_teacher pour en créer une liant les deux: t_user


USE db_sportetculture;

-- Création des tables
CREATE TABLE t_user (
  idUser INT AUTO_INCREMENT PRIMARY KEY,
  useNickname VARCHAR(255) NOT NULL,
  useFirstname VARCHAR(255) NOT NULL,
  useLastname VARCHAR(255) NOT NULL,
  useEmail VARCHAR(255) DEFAULT NULL,
  useGender CHAR(1) DEFAULT NULL,
  usePassword VARCHAR(255) NOT NULL,
  useType CHAR(1) NOT NULL
);

CREATE TABLE t_activity (
  idActivity INT AUTO_INCREMENT PRIMARY KEY,
  actTitle VARCHAR(255) NOT NULL,
  actDescription TEXT NOT NULL,
  actCapacity INT NOT NULL
);

CREATE TABLE t_participer (
  fkUser INT NOT NULL,
  fkActivity INT NOT NULL,
  PRIMARY KEY (fkUser , fkActivity),
  FOREIGN KEY (fkUser) REFERENCES t_user(idUser),
  FOREIGN KEY (fkActivity) REFERENCES t_activity(idActivity)
);

