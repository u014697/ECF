<?php 
session_start();
$title="connexion";
require "head.php";
?>
<?php
$message="";
$enternewpass=false;
if (isset ($_POST["connexion"])) {             // l'utilisateur vient d'entrer ses coordonnées de connexion
    if (isset($_POST["password"]) && (checklogin ($_POST["email"],$_POST["password"]))) {   // on vérifie qu'ils sont correct
        if (checknewpassneeded($_POST["email"])) {          // on verifie ensuite si me mdp est marqué "a changer"
            if (isset ($_POST["npassword"])) {              // enfn on regarde si le nouveau password a été renseigné
                modifmdp ($_POST["email"],$_POST["npassword"],0);
                setlogin($_POST["email"]);
                if (isset($_SESSION["requested_uri"])) {
                    $destination=$_SESSION["requested_uri"];
                    unset($_SESSION["requested_uri"]);
                    header ("location:".$destination); 
                    }
                else {
                    header ("location:acceuil.php"); 
                }
                }
            else {
                $message="<br><br>Modifiez votre mot de passe !";
                $enternewpass=true;
                unset ($_SESSION["token"]); // ON INVALIDE LE TOKEN pour éviter une navigation connectée par les url et on impose le changement
            }
         }
        else {
            if (isset($_SESSION["requested_uri"])) {
                $destination=$_SESSION["requested_uri"];
                unset($_SESSION["requested_uri"]);
                header ("location:".$destination); 
            }
            else {
                header ("location:acceuil.php"); 
            }

        } 
    }
    else {
        $message="identifiants non reconnus";
    }
}
elseif (isset ($_POST["mdpoublie"])) {
    if (resetmdp($_POST["email"])) {
        $message="un mot de passe provisoire vous a été envoyé par email";
    }
    else {
        $message="echec d'envoi du mot de passe par email";
    }
    
}
?>
<body>


    <?php
       require "header.php";
    ?>
    <?php require "console.php" ?>

    <main>
    <div class="contain">
    <div class="formulaire">
            <form  method="post">
                <div class="element">
                    <label for="email">Email    : </label>
                    <input  type="email" id="email" name="email" placeholder="votre email" required/>
                </div>
                <div>
                    <label for="password">Password :</label>
                    <input type="password" id="password" name="password"  pattern="(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}"/>
                    <p class="libellemdp">votre mot de passe doit contenir au moins 8 caractères dont au moins une majuscule, une minuscule et un caractère spécial</p>
                </div>
                <?php
                if ($enternewpass) {
                    ?>
                 <div>
                    <label for="npassword">Nouveau Password :</label>
                    <input type="password" id="npassword" name="npassword" required pattern="(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}"/>
                    <p class="libellemdp">votre mot de passe doit contenir au moins 8 caractères dont au moins une majuscule, une minuscule et un caractère spécial</p>
                </div>
                   <?php
                }
                ?>
                <div class="element">
                    <button class="formbutton" type="submit" id="connexion" name="connexion">Se Connecter</button>
                </div>
                <?php
                if ($role==0) {  // le bouton mdp oublié n'est présenté que lorsqu'on n'est pas connecté.
                    ?>
                    <div class="element">
                         <button class="formbutton" type="submit" id="mdpoublie" name="mdpoublie">Mot de passe oublié ?</button>
                    </div>

                    <?php
                }
                ?>
            </form>  
           <div class="element">
                <a href="creationcompte.php">pas encore de compte ?</a>
           </div>
    </div>
    </div>

    <?php
        require "footer.php";
    ?>
</body>
</html>