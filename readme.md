# Installation de PHP et accéder à ce projet

1. Pour l'environnement de code, télécharger la dernière version de Visual Studio Code : https://code.visualstudio.com/

2. Une fois l'application installé, on se rend sur le site officiel de PHP, on télécharge la dernière version qu'on extrait à la racine de notre disque. (https://www.php.net/downloads.php)

3. Par la suite, on télécharge composer (https://getcomposer.org/). On lance le .exe -> on spécifie le chemin vers notre dossier PHP avant de terminer l'installation.

4. Maintenant, dans Visual Studio code, on ouvre le terminal en bas de l'interface et on vérifie que php est bien installé (php -v)

Si PHP est bien installé alors on peut lancer le projet sur le navigateur : 

5. Ouvrir le projet P_Appro2 dans visual code et sélectionner index.php -> dans le terminal, écrire "PHP -S localhost:3000"*

6. Le serveur de dev. web se lance, et on a plus qu'à spécifier le chemin 
"http://localhost:3000/index.php" pour accéder au site dans la barre de recherche d'un navigateur web.

*A noter : le numéro de port choisi ici peut être changé par celui qu'on veut.*

# Docker pour MySQL et PhpMyAdmin

Pour utiliser Docker pour MySQL et PhpMyAdmin, suivez ces étapes :

1. Assurez-vous que Docker est installé sur votre machine.

2. Dans le terminal, exécutez les commandes suivantes :

(bash)
<docker-compose up -d

3. Accédez à PhpMyAdmin en ouvrant le navigateur vers http://localhost:8080 (Identifiants spécifiés dans le fichier 'docker-compose.yml)

4. Importez la base données depuis le dossier du projet "ConnecteurMySQL" 