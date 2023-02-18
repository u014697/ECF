<?php 
session_start();
$title="acceuil";
require "head.php";
?>

<body>

    <?php
       require "header.php";
       if (isset($_SESSION["requested_uri"])) {
        unset($_SESSION["requested_uri"]);
    }

    ?>

    <main>
    <div class="contain">
        <div class="formulaire">
            <p>
                Ventalis, est une entreprise spécialisée dans la vente de produit marketing ainsi que dans la visibilité de leurs clients.Ventalis fait un chiffre d’affaires de 3 millions d’euros, c’est une Licorne100% française composée de 50 salariés répartis dans toute la France.
                <br>
                Ventalis est dans une démarche écologique : nous reversons 20% de notre chiffre d’affaires annuel dans les projets qui œuvrent pour cela
            </p>
        </div>
    </div>

    </main>

    <?php
        require "footer.php";
    ?>
</body>
</html>
