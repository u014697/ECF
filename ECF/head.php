<?php

require "bdd.php";  // procédures d'accès aux données

//ouverture d'une session à la BDD

$pdo = new PDO('mysql:host=localhost;dbname=Ventalis', 'root', '');

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
    <link rel="stylesheet" href="ventalis.css">
    <title><?php echo $title ?></title>
    <script type="text/javascript">
        function validateMDP(mdp){
            var Reg = new RegExp(/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/);
            return Reg.test(mdp);
        }
    </script>
}
</head>