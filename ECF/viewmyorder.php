<?php 
session_start();
$title="détail d'une commande";
require "head.php";
?>

<body>


    <?php
       require "header.php";
       if ($role!=1) {
        $_SESSION["requested_uri"]="intranet.php";
        header ("location:login.php");
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
    if (!$order) {
        $message="cette commande n'existe pas";
        require "console.php";
        if (!$order) {exit();}
       }

    // on va vérifier que la commande a visualiser appartient bien à la personne connectée !
    $myId=getUser();
    if ($order["idUser"]!=$myId["idUser"]) {
        $message="cette commande n'est pas a vous !";
        require "console.php";
        if (!$order) {exit();}
    }
    ?>
    <div class="contain">
    <div class="formulaire">
        <h2>commande référence No : <?php echo $idorder; ?></h2>
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
                $comments=getcomments($idorder);
                foreach ($comments as $comment) {
                    echo 'commenté le : '.date ("d m Y h:i:s",$comment["time"]).'<br>'; 
                    echo $comment["comment"]."<br>";                      
                }

            ?> 

    </div>
    </div>
</main>
    <?php
        require "footer.php";
    ?>
</body>
</html>