<?php 
session_start();
$title="catalogue";
require "head.php";
?>

<body>


    <?php
       require "header.php";
    ?>
    
    <?php
        $message="";    
        if(isset($_POST["addtocart"])) {
            if (addtocart($_POST["id"],$_POST["quantite"],$_POST["prix"])){
                $message="<br><br>Produit ajouté au panier avec succes !";            
            }
            else {
                $message="<br><br>echec d'ajout du produit dans le panier !";                          
            }
        }
        if (isset($_GET["article"])&&is_numeric($_GET["article"])) {
            $article=getdetailproduct($_GET["article"]);
        }
        else {
            $message="<br><br>Produit inconnu !";
        }
    ?>
    <main>
    <?php echo $message; 
    ?>
    <div class="formulaire">
        <h2>Détail produit</h2>
        <table>
            <tr>
                <td>
                <div class="element" style="min-width:20rem">
                        nom du produit :
                        <?php echo $article["label"] ?>
                    </div>
                    <div class="element">
                        Catégorie :
                        <?php echo $article["categorie"] ?>
                    </div>
                    <div class="element">
                        prix HT (pour 1000 unités) :
                        <?php echo $article["price"] ?>
                    </div>
                    <img src="<?php echo $article["picture"] ?>" width="200px">
                </td>
                <td style="height:100%"> 

                    <table style="height:100%"><tr><td style="vertical-align:top">
                    <?php
                    if ($role==1) { ?>
                        <div class="formul">
                            <form  method="post">
                                <div class="element" style="min-width:20rem">
                                    <label for="quantite">Quantité : </label>
                                    <input  type="hidden"  id="id" name="id" value="<?php echo $article["idProduct"] ?>"/>
                                    <input  type="hidden"  id="prix" name="prix" value="<?php echo $article["price"] ?>"/>
                                    <input  style="min-width:6rem" type="number"  id="quantite" name="quantite" placeholder="1000" required/>
                                </div>

                                <div class="element">
                                <button class="right" type="submit" id="addtocart" name="addtocart">Ajouter au panier</button>
                                </div>
                            </form>  
                        </div>

                    </td></tr>
                    <tr ><td style="vertical-align:top">
                    <?php    }
                        echo "<div>".$article["description"]."</div>";
                    ?>
                    </td></tr></table>
                </td>
            </tr>
        </table>
        <form  method="post" action="catalogue.php">

                <div class="element">
                    <button class="right" type="submit" id="retour" name="retour">Retour à la liste</button>
                </div>
        </form>  
    </div>
    <?php
        require "footer.php";
    ?>
</body>
</html>