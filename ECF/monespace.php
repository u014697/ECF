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
            if(sendtoclient($_POST["client"],$_POST["objet"],$_POST["message"])) {
                $message ="le message a été envoyé";
            }
            else {
                $message ="erreur d'envoi du message";
            }

        }
    
    ?>
    <main>
    <?php 
        require "console.php" ;
        $user=getUser();
        $vendor=getClient($user["idContact"]);
    ?>

    <div class="contain">
    <div class="formulaire">
        <h2>commandes de  <?php echo $user["firstName"]." ".$user["name"]?></h2>
            <?php 
                $result=getallorders($user["idUser"]); 
                if ($result) {
                    $first=true;
                    $current=0;
                    foreach ($result as $order) {
                        if ($order["idOrder"]!=$current) {
                            $current=$order["idOrder"];
                            if (!$first) {
                                echo "</table>";
                            }
                            $first=false;
                            echo "<br>commande référence <a href='viewmyorder.php?order=".$current."'>No :".$current."</a>";
                            echo '<br>date : '.date ("d m Y h:i:s",$order["time"]).'<br>';                       
                            echo "<table><tr><th>article</th><th>prix</th><th>quantité</th><th></th></tr>";
                        }
                        echo '<tr>';
                        echo '<td style="width:75%">'.$order["label"].'</td>';                       
                        echo '<td>'.$order["price"].'</td>';                       
                        echo '<td>'.$order["volume"].'</td>'; 
                        echo '</tr>';
                      
                    } 
                    if (!$first) {
                        echo "</table>";
                    }
                }
                else {
                    echo "<div class='element'>vous n'avez aucune commande</div>";
                }              
            ?> 
    </div>
    <div class="formulaire">

        <h2>Message de votre conseillé <?php echo $vendor["firstName"]." ".$vendor["name"] ?></h2>

            <?php 
                $result=getmessagefrom($vendor["idUser"]); 
                if ($result) {
                    foreach ($result as $message) {
                        echo 'objet : '.$message["objet"].'<br>';                       
                        echo 'date : '.date ("d m Y h:i:s",$message["time"]).'<br>';                       
                        echo 'message : '.$message["message"].'<br><br>';                       
                    }
                } 
                else {
                    echo "<div class='element'> vous n'avez aucun message</div>";
                }
                ?> 
            <h3> Répondre à <?php echo $vendor["firstName"]." ".$vendor["name"] ?></h3>
            <form  method="post">
                <div class="element">
                    <label for="objet">Objet    : </label>
                    <input  type="text" id="objet" name="objet" placeholder="objet" required/>
                    <input  type="hidden"  id="client" name="client" value="<?php echo $vendor["idUser"] ?>"/>
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