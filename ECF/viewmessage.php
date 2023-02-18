<?php 
session_start();
$title="les messages recus";
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
    
    ?>
    <main>

    <?php
    if (isset($_GET["fromuser"])&&is_numeric($_GET["fromuser"])) {
        $idclient=$_GET["fromuser"];
    }
    else {
        $idclient=0;
    }
    $client=getclient($idclient);
    if (!$client) {$message="ce client n'existe pas";}
     require "console.php";
     if (!$client) {exit();}

    ?>
    <div class="contain">
    <div class="formulaire">
        <h2>Recu de <?php echo $client["firstName"]." ".$client["name"] ?></h2>
            <?php 
                $result=getmessagefrom($client["idUser"]);
                foreach ($result as $message) {
                    echo 'objet : '.$message["objet"].'<br>';                       
                    echo 'envoyé le : '.date ("d m Y h:i:s",$message["time"]).'<br>';                       
                    echo 'message : '.$message["message"].'<br><br>';                       
                } 
            ?> 
            <h3> Répondre à <?php echo $client["firstName"]." ".$client["name"] ?></h3>
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