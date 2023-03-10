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
    ?>
    <main>
    <?php require "console.php" ?>
    <div class="contain">

    <div class="formulaire">
        <h2>Catalogue</h2>
        <form  method="post">
                <div class="element">
                    <label for="categorieproduit">categorie    : </label>
                    <select id="categorieproduit" name="categorieproduit" required>
                        <?php $result=getCategories(); 
                        if (isset($_POST["categorieproduit"])&&($_POST["categorieproduit"]=="toutes")) {
                            echo "<option selected='selected' value='toutes'>toutes</option>";
                        }
                        else{
                            echo "<option value='toutes' >toutes</option>";
                        }
                        foreach ($result as $option) {
                            if (isset($_POST["categorieproduit"])&&($_POST["categorieproduit"]==$option["categorie"])) {
                                echo "<option selected='selected' value='".$option["categorie"]."'>".$option["categorie"]."</option>";
                            }
                            else{
                                echo "<option value='".$option["categorie"]."'>".$option["categorie"]."</option>";
                            }
                        }?>
                    </select>
                </div>
                <div class="element">
                    <button class="formbutton" type="submit" id="actualiser" name="Actualiser">Actualiser</button>
                </div>

                <div>
                    <?php
                        if (isset($_POST["categorieproduit"])) {
                            $filtre=$_POST["categorieproduit"];
                        }
                        else {
                            $filtre='toutes';
                        }
                        $result=getproducts($filtre);
                        ?>
                        <table class="centered" style="table-layout:auto;width:100%">
                            <tr>
                                <th>produit</th>
                                <th>prix</th>
                            </tr>
                        <?php
                        foreach ($result as $product) {
                            echo "<tr>";
                            echo '<td><a href="product.php?article='.$product["idProduct"].'">';
                            echo $product["label"]."</a> </td><td>  ".$product["price"].'</td>';
                            echo "</tr>";
                        }
                        ?>
                        </table>
                </div>
            </form>  
    </div>
    </div>
    </main>
    <?php
        require "footer.php";
    ?>
</body>
</html>