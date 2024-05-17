--ETML
--Auteur : Leonar
--Date : 17.05.2024
--Description : Script SQL à importer dans PhpMyAdmin pour les créations des tables, de 3 enseignants et 5 élèves.

USE db_sportetculture;

-- Création des tables
CREATE TABLE t_student (
  idStudent INT AUTO_INCREMENT PRIMARY KEY,
  stuLastname VARCHAR(255) NOT NULL,
  stuFirstname VARCHAR(255) NOT NULL,
  stuEmail VARCHAR(255) NOT NULL,
  stuGender CHAR(1) NOT NULL,
  stuPassword VARCHAR(255) NOT NULL
);

CREATE TABLE t_teacher (
  idTeacher INT AUTO_INCREMENT PRIMARY KEY,
  teaFirstname VARCHAR(255) NOT NULL,
  teaLastname VARCHAR(255) NOT NULL,
  teaGender CHAR(1) NOT NULL,
  teaPassword VARCHAR(255) NOT NULL
);

CREATE TABLE t_activity (
  idActivity INT AUTO_INCREMENT PRIMARY KEY,
  actTitle VARCHAR(255) NOT NULL,
  actDescription TEXT NOT NULL,
  actCapacity INT NOT NULL,
  fkTeacher INT NOT NULL,
  FOREIGN KEY (fkTeacher) REFERENCES t_teacher(idTeacher)
);

CREATE TABLE t_participer (
  fkStudent INT NOT NULL,
  fkActivity INT NOT NULL,
  PRIMARY KEY (fkStudent , fkActivity),
  FOREIGN KEY (fkStudent) REFERENCES t_student(idStudent),
  FOREIGN KEY (fkActivity) REFERENCES t_activity(idActivity)
);

-- Insertion de trois enseignants
INSERT INTO t_teacher (teaLastname, teaFirstname, teaGender, teaPassword) VALUES
('Rochat', 'Jean', 'O', 'teacher123$'),
('Pittet', 'Marie', 'F', 'teacher123$'),
('Findik', 'Mohammed', 'M', 'teacher123$');

-- Insertion de trois élèves
INSERT INTO t_student (stuLastname, stuFirstname, stuGender, stuPassword) VALUES
('Fernandez', 'Tony', 'M', 'student123$'),
('Holland', 'Tom', 'M', 'student123$'),
('Brown', 'Thomas', 'M', 'student123$'),
('Martin', 'Louise', 'F', 'student123$'),
('Brunetti', 'Jeanne', 'F', 'student123$');

