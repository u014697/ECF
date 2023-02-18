<?php 
session_start();
$title="intranet";
require "head.php";
?>

<body>


    <?php
       require "header.php";
       if ($role!=2) {
        $_SESSION["requested_uri"]="intranet.php";
        header ("location:login.php");
       }
    ?>
    
    <?php
        $message="";
        if (isset ($_POST["creercategorie"])) {
            if (!createcategorie($_POST["categorie"])) {
            $message="echec de création de la catégorie";
            }
        }
        elseif (isset ($_POST["creerproduit"])) {
            if (($_FILES["imageproduit"]["error"]==0) && (($_FILES["imageproduit"]["type"]=="image/png")||($_FILES["imageproduit"]["type"]=="image/jpg"))) {
                $nom = $_FILES["imageproduit"]["tmp_name"];
                $nomdestination = 'image/'.$_FILES["imageproduit"]["name"];

                if (createProduct ($_POST["nomproduit"],$_POST["categorieproduit"],$nomdestination,$_POST["descriptionproduit"],$_POST["prixproduit"])) {
                    $message="Création effectuée";
                    move_uploaded_file($nom, $nomdestination);
                }
                else {
                    $message="La création a échoué";
                }   
            }
            else {
                $message ="erreur dans le chargement de l'image";
            }
        }
        elseif (isset ($_POST["envoyer"])) {
            if(sendtoclient($_POST["radio"],$_POST["objet"],$_POST["message"])) {
                $message ="le message a été envoyé";
            }
            else {
                $message ="erreur d'envoi du message";
            }

        }
    
    ?>
    <main>
    <?php require "console.php" ?>
    <div class="contain">

    <div class="formulaire">
        <h2>Créer une catégorie</h2>
        <form  method="post">
                <div class="element">
                    <label for="categorie">nouvelle catégorie : </label>
                    <input  type="text" id="categorie" name="categorie" placeholder="nouvelle catégorie" required/>
                </div>
                <div class="element">
                    <button class="formbutton" type="submit" id="creercategorie" name="creercategorie">Créer</button>
                </div>
            </form>  
    </div>
    <div class="formulaire">
        <h2>Ajouter un produit</h2>
        <form  method="post" enctype="multipart/form-data">
                <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
                <div class="element">
                    <label for="nomproduit">Nom du produit    : </label>
                    <input  type="text" size="25" id="nomproduit" name="nomproduit" placeholder="nom" required/>
                </div>
                <div class="element">
                    <label for="imageproduit">image    : </label>
                    <input  type="file" accept="image/png, image/jpg" id="imageproduit" name="imageproduit"  required/>
                </div>
                <div>
                    <label for="descriptionproduit">Description    : </label>
                    <textarea  rows="5" cols="30" id="descriptionproduit"  name="descriptionproduit" placeholder="description" required></textarea>
                </div>
                <div class="element">
                    <label for="prixproduit">Prix HT pour 1000    : </label>
                    <input  type="text" size="25" id="prixproduit" name="prixproduit" placeholder="prix" required/>
                </div>
                <div class="element">
                    <label for="categorieproduit">categorie    : </label>
                    <select id="categorieproduit" name="categorieproduit" required>
                        <?php $result=getCategories(); 
                        foreach ($result as $option) {
                            echo "<option value='".$option["categorie"]."'>".$option["categorie"]."</option>";
                        }?>
                    </select>
                </div>
                <div class="element">
                    <button class="formbutton" type="submit" id="creerproduit" name="creerproduit">Créer</button>
                </div>
            </form>  
    </div>
    <div class="formulaire">
        <h2>Mes clients </h2>
        <?php
            $result=getclients();
        ?>

            <form  method="post">
                <table>
                    <tr>
                        <th> client</th>
                        <th> messages </th>
                        <th> commandes </th>
                    </tr>
                    <?php
                    $first=true;
                    foreach ($result as $client) {
                        $mescount=getmessagecount($client["idUser"]);
                        $ordcount=getordercount($client["idUser"]);
                        echo '<tr>';                       
                        echo '<td>'.$client["firstName"].' '.$client["name"].' ('.$client["email"].') </td>';
                        if ($mescount>0) {
                            echo '<td><a href="viewmessage.php?fromuser='.$client["idUser"].'">'.$mescount.'</a></td>';
                        }
                        else {
                            echo '<td>'.$mescount.'</td>';
                        }
                        if ($ordcount>0){
                            echo '<td><a href="vieworder.php?fromuser='.$client["idUser"].'">'.$ordcount.'</a></td>';
                        }
                        else {
                            echo '<td>'.$ordcount.'</td>';
                        }
                       echo '</tr>';
                    } 
                ?> 
                </table>
                <div class="element">
                </div>
                <div class="element">
                    <label for="objet">Objet    : </label>
                    <input  type="text"  id="objet" name="objet" placeholder="objet" required/>
                </div>
                <div>
                    <label for="message">Message :</label>
                    <textarea  rows="5"  id="message"  name="message" placeholder="votre message" required></textarea>
                </div>
                <div class="element">
                    <button class="formbutton" type="submit" id="envoyer" name="envoyer">Envoyer un message</button>
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