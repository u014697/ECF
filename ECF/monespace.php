<?php 
session_start();
$title="mon espace";
require "head.php";
?>

<body>


    <?php
       require "header.php";
       if ($role!=1) {
        $_SESSION["requested_uri"]="monespace.php";
        header ("location:login.php");
       }
    ?>
    
    <?php
        $message="";
        if (isset ($_POST["envoyer"])) {
            if (!sendmessage($_POST["objet"],$_POST["message"])) {
            $message="echec de l'envoi du message";
            }
        }
        elseif (isset ($_POST["creer"])) {
            if (createUser ($_POST["email"],$_POST["password"],$_POST["nom"],$_POST["prenom"],"Ventalis",2)) {
                $message="modification effectuée";
            }
        else {
            $message="echec de modification";
            }
        }
    ?>
    <main>
    <div>
    <?php require "console.php" ?>


    <div class="formulairegauche">
        <h2>mes commandes</h2>
        <?php
            $result=getcommande();
        ?>
        <table class="centered">
            <tr>
                <th>commande</th>
                <th>état</th>
                <th>produit</th>
                <th>prix</th>
                <th>quantité</th>
            </tr>
            <?php
            foreach ($result as $commande) {
                echo "<tr>";
                echo "<td>".$commande["idorder"]."</td>";
                echo "<td>".$commande["state"]."</td>";
                echo "<td>".$commande["label"]."</td>";
                echo "<td>".$commande["price"]."</td>";
                echo "<td>".$commande["volume"]."</td>";
                echo "</tr>"; 
            } 
            ?> 
        </table>
    </div>
    <div class="formulairedroite">
        <h2>Contacter mon conseillé </h2>
            <form  method="post">
                <div class="element">
                    <label for="objet">Objet    : </label>
                    <input  type="text" size="50" id="objet" name="objet" placeholder="objet" required/>
                </div>
                <div>
                    <label for="message">Message :</label>
                    <textarea  rows="5" cols="50" id="message"  name="message" placeholder="votre message" required></textarea>
                </div>
                <div class="element">
                    <button class="formbutton" type="submit" id="envoyer" name="envoyer">Envoyer</button>
                </div>
            </form>  
    </div>


    </div>
    <?php
        require "footer.php";
    ?>
</body>
</html>