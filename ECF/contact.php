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
            $message = "de la part de ".$_POST["contact"]."de la société ".$_POST["company"]."<br>".$_POST["message"];
     //   mail($to, $objet, $message);
            $message ="le message a été envoyé";
        }
    
    ?>
    <main>
    <?php require "console.php" ?>
    <div class="contain">

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
                    <label for="contact">votre nom : </label>
                    <input  type="text"  id="contact" name="contact" placeholder="prénom nom" required/>
                </div>
                <div class="element">
                    <label for="company">votre société : </label>
                    <input  type="text" id="company" name="company" placeholder="votre société" required/>
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