# ECF
ECF finale

instruction pour l'installation de l'application :

1. recuperer l'ensemble des fichiers et répertoire sur le git https://github.com/u014697/ECF
2. installez ces fichiers et répertoire à la racine du site
3. modifier le fichier ref/connect.php pour y rentrer le DSN et les identifiants de connexion à la BDD donné par votre hébergeur
4. créer les tables en executant la requete "create.sql" contenue dans le sous répertoire /sql
5. creer des utilisateurs et des produits avec le scipt initusers.php présent à la racine du site
6. IMPORTANT ! effacer du serveur ce fichier initusers.php, sans quoi un utilisateur mal intentionné pourrait creer un compte admin.
7. des données de démonstration sont préchargées, mais peuvent être effacées dans phpmyadmin.

A ce stade, s'agissant d'un environnement de test, l'envoi de mail n'est pas opérationnel.
lors de l'inscription, le mail n'est donc pas envoyé. Le mot de passe provisoire qui serait recu est "Nouveau-Mot2Passe". il devra être changé lors de la première connexion.


