<?php 
session_start();
$title="administration";
require "head.php";
?>

<body>


    <?php
       require "header.php";
       if ($role!=3) header ("location:login.php");
    ?>
    
    <?php
        $message="";
        if (isset ($_POST["modifmdp"])) {
            if (modifmdp ($_POST["email"],$_POST["password"],0)) {
                $message="<br><br>modification effectuée";
            }
        else {
            $message="<br><br>echec de modification";
            }
        }
        elseif (isset ($_POST["creer"])) {
            if (createUser ($_POST["email"],$_POST["password"],$_POST["nom"],$_POST["prenom"],"Ventalis",2)) {
                $message="<br><br>modification effectuée";
            }
        else {
            $message="<br><br>echec de modification";
            }
        }
    ?>
    <main>
    <div>
    <?php echo $message; ?>

    <div class="formulairegauche">
        <h2>Reinitialisation MDP</h2>
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
                    <button class="formbutton" type="submit" id="modifmdp" name="modifmdp">Modifier</button>
                </div>
            </form>  
    </div>
    <div class="formulairedroite">
        <h2>Création d'un employé</h2>
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
                    <button class="formbutton" type="submit" id="creer" name="creer">Créer</button>
                </div>
            </form>  
    </div>


    </div>
    <?php
        require "footer.php";
    ?>
</body>
</html>