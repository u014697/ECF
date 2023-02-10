<?php           // traitement de la deconnexion
if (isset ($_GET["deconnexion"])) {
    $_SESSION["token"]=null;
    unset ($_SESSION["token"]);
    header ("location:acceuil.php");
}
?>

<header>
        <div class="menu">
            <a href="acceuil.php"><img class="left" src="image/logo.png" height="40px" width="40px"/></a>
            <a class="menulink" href="acceuil.php">qui sommes nous ?</a>
            <a class="menulink" href="nosprestations.php">nos prestations</a>
            <a class="menulink" href="contact.php">contact</a>
            <?php
                if ($role==3) {
                    echo '<a class="menulink" href="admin.php">administration</a>';
                } 
                elseif ($role==2) {
                    echo '<a class="menulink" href="intranet.php">intranet</a>';
                }
                elseif ($role==1) {
                    echo '<a class="menulink" href="monespace.php">mon espace</a>';
                }
            ?>
            <?php
            if ($role==0) {
            ?>
                <button class="right" type="submit" id="connexion" name="connexion" onclick="window.location.href='login.php';">Se Connecter</button>
            <?php
            }
            else {
            ?>
                <button class="right" type="submit" id="deconnexion" name="deconnexion" onclick="window.location.href='login.php?deconnexion';">Deconnexion</button>
            <?php
            }
            ?>
            <?php 
                if ($role==1) {
                    echo '<a class="menulink right" href="panier.php">panier</a>';
                } 
             ?>
        </div>
</header>