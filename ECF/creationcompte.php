<?php 
session_start();
$title="Creation de compte";
require "head.php";
?>

<body>

<?php
if (isset ($_POST["inscrire"])) {
    if (createUser ($_POST["email"],$_POST["password"],$_POST["nom"],$_POST["prenom"],$_POST["societe"],1)) {
        header ("location:acceuil.php");
    }
}
?>

    <?php
       require "header.php";
    ?>

    <main>


    <div class="formulaire">
            <form  method="post">
                <div class="element">
                    <label for="email">Email    : </label>
                    <input  type="email" id="email" name="email" placeholder="votre email" required/>
                </div>
                <div>
                    <label for="password">Password :</label>
                    <input type="password" id="password" name="password" required pattern="(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}"/>
                    <p class="libellemdp">votre mot de passe doit contenir au moins 8 caractères dont au moins une majuscule, une minuscule et un caractère spécial</p>
                </div>
                <div class="element">
                    <label for="nom">nom : </label>
                    <input  type="text" id="nom" name="nom" placeholder="votre nom" required/>
                </div>
                <div class="element">
                    <label for="prenom">Prénom     : </label>
                    <input  type="text" id="prenom" name="prenom" placeholder="votre prénom" required/>
                </div>
                <div class="element">
                    <label for="societe">Société     : </label>
                    <input  type="text" id="societe" name="societe" placeholder="votre société" required/>
                </div>
                <div class="element">
                    <button class="formbutton"  type="submit" id="inscrire" name="inscrire">s'inscrire</button>
                </div>
            </form>  
    </div>

    </main>

    <?php
        require "footer.php";
    ?>
</body>
</html>