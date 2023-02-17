<?php 
session_start();
$title="les commandes à traiter";
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
     if (!$client) {exit();}?>

    ?>
    <div class="contain">

    <div class="formulaire">

        <h2>commandes de  <?php echo $client["firstName"]." ".$client["name"]?> à traiter</h2>

            <?php $result=getorders($client["idUser"]); ?>

           
            <?php
                    $first=true;
                    $current=0;
                    foreach ($result as $order) {
                        if ($order["idOrder"]!=$current) {
                            $current=$order["idOrder"];
                            if (!$first) {
                                echo "</table>";
                            }
                            $first=false;
                            echo "<br>commande référence <a href='processorder.php?order=".$current."'>No :".$current."</a>";
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
            ?> 
            </table>

    </div>


    </div>
    <?php
        require "footer.php";
    ?>
</body>
</html>