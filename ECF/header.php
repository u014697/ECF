<?php           // traitement de la deconnexion
if (isset ($_GET["deconnexion"])) {
    $_SESSION["token"]=null;
    unset ($_SESSION["token"]);
    header ("location:acceuil.php");
}
?>

<header>
<div class="container mt-7">
<nav class="navbar navbar-expand-md navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="acceuil.php"><img  src="image/logo.png" height="40px" width="40px"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link"  href="acceuil.php">qui sommes nous ?</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="catalogue.php">nos produits</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">contact</a>
                </li>

                <?php
                if ($role==3) {
                    echo '<li class="nav-item"><a class="nav-link" href="administration.php">administration</a></li>';
                } 
                elseif ($role==2) {
                    echo '<li class="nav-item"><a class="nav-link" href="intranet.php">intranet</a></li>';
                }
                elseif ($role==1) {
                    echo '<li class="nav-item"><a class="nav-link" href="monespace.php">mon espace</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="panier.php">panier</a></li>';
                }
                
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

            </ul>
        </div>
    </div>
</nav>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>


</header>