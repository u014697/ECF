<?php 
session_start();
$title="contact";
require "head.php";
?>

<body>


    <?php
       require "header.php";
    ?>
    
    <?php
        $message="";
        if (isset ($_POST["envoyer"])) {
            $to      = CONTACT_EMAIL;
            $objet = $_POST["objet"];
            $message = "dossier suivi par ".$_POST["contact"]."<br>".$_POST["message"];
     //   mail($to, $objet, $message);
            $message ="le message a été envoyé";
        }
    
    ?>
    <main>
    <div>
    <?php require "console.php" ?>

    <div class="formulaire">
        <h2>Contact </h2>
        <?php
            $result=getclients();
        ?>

            <form  method="post">
                <div>
                    <p>
                        vous pouvez nous envoyer un mail :
                    </p>
                </div>
                <div class="element">
                    <label for="objet">votre contact : </label>
                    <input  type="text" size="50" id="contact" name="contact" placeholder="prénom nom" required/>
                </div>
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