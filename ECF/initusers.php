<?php
    echo "initialisation des tables de l'application Ventalis. <br>";
    echo "IMPORTANT, les tables doivent être vide avant de lancer ce script.<br><br>";
    $pass="Mot-2-Passe";
    include 'ref/connect.php';
    include "bdd.php";

    createUser ("bigboss@ventalis.com",$pass,"Boss","Big","Ventalis",3);   // administrateur
    echo "creation Administrateur bigboss@ventalis.com pass=".$pass."<br>";
    createUser ("johnventalis@ventalis.com",$pass,"Ventalis","John","Ventalis",2);   // employé
    echo "creation Employé johnventalis@ventalis.com pass=".$pass."<br>";
    createUser ("janeventalis@ventalis.com",$pass,"Ventalis","Jane","Ventalis",2);   // employé
    echo "creation Employé janeventalis@ventalis.com pass=".$pass."<br>";
    createUser ("jerryventalis@ventalis.com",$pass,"Ventalis","Jerry","Ventalis",2);   // employé
    echo "creation Employé jerryventalis@ventalis.com pass=".$pass."<br>";
    createUser ("johndoe@jd.com",$pass,"Doe","John","JD.inc",1);   // utilisateur
    echo "creation Utilisateur johndoe@jd.com pass=".$pass."<br>";
    createUser ("janedoe@jd.com",$pass,"Doe","Jane","JD.inc",1);   // utilisateur
    echo "creation Utilisateur janedoe@jd.com pass=".$pass."<br>";
    createUser ("jerrydoe@jd.com",$pass,"Doe","Jerry","JD.inc",1);   // utilisateur 
    echo "creation Utilisateur jerrydoe@jd.com pass=".$pass."<br>";
    echo "----<br>";
    createCategorie ("haut de gamme");
    echo "creation catégorie 'haut de gamme'<br>";
    createCategorie ("entrée de gamme");
    echo "creation catégorie 'entrée de gamme'<br>";
    createCategorie ("bon rapport qualité prix");
    echo "creation catégorie 'bon rapport qualité prix'<br>";
    echo "----<br>";
    createProduct ('Produit No1','haut de gamme','image/hdg.png','Produit haut de gamme trés bien et trés cher',3157.20,4000);
    echo "creation du produit : 'Produit No1'<br>";
    createProduct ('Produit No2','entrée de gamme','image/bdg.png','Produit bas de gamme pas trés bien mais pas cher',512.20,4000);
    echo "creation du produit : 'Produit No2'<br>";
    createProduct ('Produit No3','bon rapport qualité prix','image/mdg.png','Produit ayant un trés bon rapport qualité prix',1254.20,4000);
    echo "creation du produit : 'Produit No3'<br>";   
    echo "-----<br>";
    echo "initialisation terminée, merci d'effacer ce script du serveur !" 
?>