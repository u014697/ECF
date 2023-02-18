<?php 
session_start();
$title="traitement d'une commande";
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
        if (isset ($_POST["envoyer"])) {
            if(sendtoclient($_POST["client"],$_POST["objet"],$_POST["message"])) {
                $message ="le message a été envoyé";
            }
            else {
                $message ="erreur d'envoi du message";
            }
        }
        if (isset ($_POST["valider"])) {
            if(processorder($_POST["commande"],$_POST["message"])) {
                $message ="la commande est validée";
            }
            else {
                $message ="erreur de validation de commande";
            }
        }
    
    ?>
    <main>

    <?php
    if (isset($_GET["order"])&&is_numeric($_GET["order"])) {
        $idorder=$_GET["order"];
    }
    else {
        $idorder=0;
    }
    $order=getorder($idorder);
    $client=getclient($order["idUser"]);
    if (!$client) {$message="ce client n'existe pas";}
     require "console.php";
     if (!$client) {exit();}?>

    <div class="contain">
    <div class="formulaire">
        <h2>Traiter la commande ref:<?php echo $idorder; ?></h2>
            <?php
                            $sum=0;
                            $result=getoneorder($idorder); 
                            echo 'émise le : '.date ("d m Y h:i:s",$result[0]["time"]).'<br>';                       
                            echo "<table style='table-layout=auto;width=100%'><tr><th></th><th>article</th><th>prix</th><th>quantité</th><th>prix total</th></tr>";
                            foreach ($result as $order) {
                                echo '<tr>';
                                echo '<td><img src="'.$order["picture"].'" style="width:100px"></td>';
                                echo '<td>'.$order["label"].'</td>';                       
                                echo '<td>'.$order["price"].'</td>';                       
                                echo '<td>'.$order["volume"].'</td>'; 
                                echo '<td>'.$order["volume"]*$order["price"].'</td>';
                                echo '</tr>';
                                $sum+=$order["volume"]*$order["price"];
                            }
                            echo "</table>";
                            echo "<p>Montant global de la commande : ".$sum." € HT</p>";
             
            ?>
            <form  method="post">
                <div>
                    <label for="message">commentaire :</label>
                    <textarea  rows="5"  id="message"  name="message" placeholder="votre message" required></textarea>
                    <input  type="hidden"  id="commande" name="commande" value="<?php echo $idorder?>"/>
                </div>

                <div class="element">
                    <button class="formbutton" type="submit" id="valider" name="valider">Valider la commande</button>
                </div>
            </form>  
    </div>
    <div class="formulaire">
        <h2>Contacter <?php echo $client["firstName"]." ".$client["name"] ?></h2>

            <form  method="post">
                <div class="element">
                    <label for="objet">Objet    : </label>
                    <input  type="text" id="objet" name="objet" placeholder="objet" required/>
                    <input  type="hidden"  id="client" name="client" value="<?php echo $client["idUser"] ?>"/>
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