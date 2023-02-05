<?php 
session_start();
$title="acceuil";
require "head.php";
?>

<body>

    <?php
        $specificRoleMenu='<a class="menulink" href="contact.php">contact</a>';  // pour integrer le lien vers le menu utilisateur
        $cartMenu='<a class="menulink right" href="contact.php">contact</a>';  // pour integrer le lien vers le panier
       require "header.php";
    ?>



    <main>
        <div class="ventalis">
            <p>
                Ventalis, est une entreprise spécialisée dans la vente de produit marketing ainsi que dans la visibilité de leurs clients.Ventalis fait un chiffre d’affaires de 3 millions d’euros, c’est une Licorne100% française composée de 50 salariés répartis dans toute la France.
                <br>
                Ventalis est dans une démarche écologique : nous reversons 20% de notre chiffre d’affaires annuel dans les projets qui œuvre pour cela
            </p>
        </div>
    </main>

    <footer>
        mentions légales 
    </footer>
</body>
</html>
