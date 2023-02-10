<?php 
session_start();
$title="connexion";
require "head.php";
?>
<?php
if (isset ($_POST["connexion"])) {
    if (checklogin ($_POST["email"],$_POST["password"])) {
        header ("location:acceuil.php");
    }
    else {
        exit ("bof !");
    }
}
?>
<body>


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
                <div class="element">
                    <label for="password">Password :</label>
                    <input type="password" id="password" name="password" required pattern="(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}"/>
                </div>
               <div class="element">
                    <button type="submit" id="connexion" name="connexion">Se Connecter</button>
                </div>
            </form>  
            <div class="element">
                <a href="creationcompte.php">pas encore de compte ?</a>
           </div>
    </div>

    <?php
        require "footer.php";
    ?>
</body>
</html>