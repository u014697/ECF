<?php
require "constante.php";
require "bdd.php";  // procédures d'accès aux données

//ouverture d'une session à la BDD

require "ref/connect.php";

// verification du statut du visiteur

$role=0;
if (isset($_SESSION["token"])) {
    if  (checkToken()) {
        $role=checkRole();
    }
}

?>



<!DOCTYPE html>
<html lang="fr"><head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="ventalis6.css">
    <title><?php echo $title ?></title>
</head>