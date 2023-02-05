<header>
        <div class="menu">
            <a href="acceuil.php"><img class="left" src="image/logo.png" height="25px" width="25px"/></a>
            <a class="menulink" href="acceuil.php">qui sommes nous ?</a>
            <a class="menulink" href="nosprestations.php">nos prestations</a>
            <a class="menulink" href="contact.php">contact</a>
            <?php echo $specificRoleMenu ?>
            <button class="right" type="submit" id="connexion" name="connexion" onclick="window.location.href='connexion.php';">Se Connecter</button>
            <?php echo $cartMenu ?>
        </div>
</header>