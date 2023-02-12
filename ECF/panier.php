<?php 
session_start();
$title="panier";
require "head.php";
?>

<body>


    <?php
       require "header.php";
       if ($role!=1) {
        $_SESSION["requested_uri"]="panier.php";
        header ("location:login.php");
       }
    ?>
    
    <?php
        $message="";   
        if (isset($_GET["article"])&&is_numeric($_GET["article"])) {
            if (erasecartelement($_GET["article"])){
                $message= "article effacé";
            }
            else {
                $message="echec de l'effacement d'article";

            } 
        } 
        if (isset($_POST["commander"])) {
            if (confirmorder()) {
                $message= "commande effectuée";
            }
            else {
                $message= "echec de commande";

            } 
       }
    ?>
    <main>
    <?php require "console.php" ?>


    <div class="formulaire">
        <h2>Mon Panier</h2>
        <?php
            $result=getcart();
        ?>
        <form  method="post">

        <table>
            <tr>
                <th>produit</th>
                <th>quantité</th>
                <th>prix</th>
                <th>supprimer</th>
            </tr>
            <?php
            $total=0;
            if($result) {
                foreach($result as $element){
                    echo "<tr>";
                    echo "<td>";
                    echo $element["label"];
                    echo "</td>";
                    echo "<td>";
                    echo $element["volume"];
                    echo "</td>";
                    echo "<td>";
                    echo $element["price"];
                    $total += $element["price"]*$element["volume"];
                    echo "</td>";
                    echo "<td>";
                    echo '<a href="panier.php?article='.$element["idCartElement"].'"  /><img src="image/trash.png" width="16px"></a>';
                    echo "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
                <div class="element">
                    <?php
                    echo "total commande : ".$total." €";
                    ?>
                </div>
               <div class="element">
                    <button class="formbutton" type="submit" id="commander" name="commander">Commander</button>
                </div>
        </form>  
    </div>


    </div>
    <?php
        require "footer.php";
    ?>
</body>
</html>