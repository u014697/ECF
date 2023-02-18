<?php

// recherche de l'utilisateur connecté a partir de son token
// en retour un tableau associatif d'un ligne de la table user, ou false si echec

function getUser () {
    global $pdo;

    try {
        if (isset ($_SESSION["token"])) {
            $getuser = $pdo->prepare("select * FROM users WHERE hashToken = :hashtoken");
            $getuser->bindValue(':hashtoken', hash('md5', $_SESSION["token"]), PDO::PARAM_STR);
            $getuser->execute();
            $user= $getuser->fetch(PDO::FETCH_ASSOC);
            return $user;
            }   
        return false; 
        }   
    catch (PDOException $e) {
        return false;
    }
}


// verification du email/pass. si OK, mise en place d'un token avec enregistrement dans la BDD et durée de validité
function checkLogin($user,$pass) {
    global $pdo;
    try {    
        $user=strtolower($user);
        $checklo = $pdo->prepare('SELECT hashpass FROM users WHERE email=:email');
        $checklo->bindValue(':email', $user, PDO::PARAM_STR);
        $checklo->execute();
        $result= $checklo->fetch(PDO::FETCH_ASSOC);
        if ($result && password_verify($pass,$result["hashpass"])) {    // le mdp coincide avec le mail !
               return setLogin($user);
        }
        return false;
    }
    catch (PDOException $e) {
        echo "erreur : ".$e->getMessage();
        return false;
    }
}

// connexion d'un utilisateur, mise à jour du token dans la table user

function setLogin($user) {
    global $pdo;
    try {    
            $token = bin2hex(random_bytes(16));
            $settoken = $pdo->prepare("UPDATE users SET hashtoken=:token, expirationtoken=:expiration WHERE email=:email") ;
            $settoken->bindValue(':token', hash('md5', $token), PDO::PARAM_STR);
            $expiration = time()+7200;    // le token est valable 2 heures aprés dernier accés
            $settoken->bindValue(':expiration', $expiration, PDO::PARAM_INT);
            $settoken->bindValue(':email', $user, PDO::PARAM_STR);
            $settoken->execute();
            $_SESSION["token"] = $token;
            return true;
        }

    catch (PDOException $e) {
        echo "erreur de connexion ";
        return false;
    }
}

//verification si unmot de passe doit être changé. retourne true si il doit être changé.

    function checknewpassneeded ($email) {
        global $pdo;
        try {    
            $email=strtolower($email);
            $checklo = $pdo->prepare('SELECT email,tobechanged FROM users WHERE email=:email');
            $checklo->bindValue(':email', $email, PDO::PARAM_STR);
            $checklo->execute();
            $result= $checklo->fetch(PDO::FETCH_ASSOC);
            return $result["tobechanged"]==1;   // le mdp doit être changé !
        }
        catch (PDOException $e) {
            echo "erreur : ".$e->getMessage();
            return false;
        }
}

// on vérifie que le token est présent et valide dans la BDD
function checkToken () {
    global $pdo;

    try {
        if (isset ($_SESSION["token"])) {
            $checktok = $pdo->prepare('SELECT * FROM users WHERE hashtoken=:hashtoken');
            $checktok->bindValue(':hashtoken', hash('md5', $_SESSION["token"]), PDO::PARAM_STR);
            $checktok->execute();
            $result= $checktok->fetch(PDO::FETCH_ASSOC);
            if (!$result) return false;  // le token n'a pas été trouvé
            if ($result["expirationToken"] < time()) return false;     // on verifie si le token n'est pas expiré 
            $settoken = $pdo->prepare("UPDATE users SET expirationtoken=:expiration WHERE hashtoken=:hashtoken") ;
            $expiration = time()+7200;    // le token est valable 2 heures aprés dernier accés
            $settoken->bindValue(':expiration', $expiration, PDO::PARAM_INT); 
            $settoken->bindValue(':hashtoken', hash('md5', $_SESSION["token"]), PDO::PARAM_STR);
            $settoken->execute();   // on remet a jour la durée de validité du token 
            return true;
        }
        return false;   
    }
    catch (PDOException $e) {
        return false;
    }
}

// on lit le role correspondant au token 

function checkRole () {
    $role=getUser();
    if(!$role) return 0;
    return $role["role"];
}

// création d'un utilisateur ou d'un employé

function createUser ($email,$pass,$nom,$prenom,$societe,$newrole) {
    global $pdo;
    try {
        if (!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/', $pass)) return false;
        $email=strtolower($email);

        if ($newrole==1)  {  // pour un client, on recherche le conseillé le moins chargé pour lui affecter le client
            $statement1 = $pdo->prepare('SELECT users.idUser,count(utilisateurs.idUser) as C from users
                                            left join users as utilisateurs on users.idUser=utilisateurs.idContact
                                            where users.role=2
                                            group by users.idUser
                                            order by C asc Limit 1');
            $statement1->execute();
            $result= $statement1->fetch(PDO::FETCH_ASSOC);;
            $idcontact=$result["idUser"];
        }
        else {
            $idcontact=0;
        }

        $statement = $pdo->prepare("INSERT INTO users(name,firstname,company,email,idcontact,registrationnumber,hashpass,hashtoken,expirationtoken,role)
           VALUES(:nom,:prenom,:company,:email,:idcontact,:registrationnumber,:hashpass,:hashtoken,:expirationToken,:role)");
        $statement->bindValue(':nom', htmlspecialchars($nom), PDO::PARAM_STR);
        $statement->bindValue(':prenom', htmlspecialchars($prenom), PDO::PARAM_STR);
        $statement->bindValue(':company', htmlspecialchars($societe), PDO::PARAM_STR);
        $statement->bindValue(':email', htmlspecialchars($email), PDO::PARAM_STR);
        $statement->bindValue(':role', $newrole, PDO::PARAM_INT);
        $statement->bindValue(':idcontact',$idcontact, PDO::PARAM_STR);
        if (($newrole==2)||($newrole==3)) {  // les employés yc l'administrateur ont un matricule
            $registrationNumber= rand(1,9999999);
        }
        else {
            $registrationNumber= 0;
        }
        $statement->bindValue(':registrationnumber',$registrationNumber, PDO::PARAM_STR);
        $statement->bindValue(':hashpass', password_hash($pass,PASSWORD_BCRYPT), PDO::PARAM_STR);
        $token = bin2hex(random_bytes(16));
        $statement->bindValue(':hashtoken', hash('md5', $token), PDO::PARAM_STR);
        $expiration = date("Y/m/d",strtotime('+1 day'));
        $statement->bindValue(':expirationToken', $expiration, PDO::PARAM_STR);
        $statement->execute();
        unset ($_SESSION["token"]);
        return true;
    }
    catch (PDOException $e) {
        echo"Échec de la connexion".$e->getMessage();
        return false;
    }
}
function resetmdp ($email) {
 
   $email=strtolower($email);
   $newpass=NEW_PASSWORD;
   $to      = $email;
   $subject = 'Nouveau Mot de Passe Ventalis.com';
   $message = 'Votre nouveau mot de passe est "Nouveau-Mot2passe"';
   $headers = 'From: webmaster@ventalis.com' ;
//   mail($to, $subject, $message, $headers);

    return modifmdp($email,$newpass,1); //modif du mdp en marquant qu'il devra être changé à la prochaine connexion
}

function modifmdp ($email,$pass,$tobechanged) {
    global $pdo;


    try {
        $checkuser = $pdo->prepare('SELECT count(email) AS C FROM users WHERE email=:email');
        $checkuser->bindValue(':email', $email, PDO::PARAM_STR);
        $checkuser->execute();
        $result= $checkuser->fetch(PDO::FETCH_ASSOC);
    
        if ($result["C"] != 1) {    // le compte n'existe pas!
            return false; 
        }    

        $statement = $pdo->prepare("UPDATE users SET hashpass=:hashpass, tobechanged=:tobechanged WHERE email=:email");
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':tobechanged', $tobechanged, PDO::PARAM_STR);
        $statement->bindValue(':hashpass', password_hash($pass,PASSWORD_BCRYPT), PDO::PARAM_STR);
        $statement->execute();
        return true;
    }
    catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

// envoi d'un message d'un client vers son conseillé ventalis

function sendmessage ($objet,$message) {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
    
    try {
        // on commence par retrouver l'identité de l'emetteur du message (le client)
        $client = getUser();
        if (!$client) return false;

        // ensuite, on crée le message dans la table messages

        $createmes = $pdo->prepare('INSERT INTO messages(idFrom,idTo,objet,message,time,state) VALUES (:from, :to, :objet, :message,:time,0) ');   
        $createmes->bindValue(':from', $client["idUser"], PDO::PARAM_STR);
        $createmes->bindValue(':to', $client["idContact"], PDO::PARAM_STR);
        $createmes->bindValue(':objet', htmlspecialchars($objet), PDO::PARAM_STR);
        $createmes->bindValue(':message', htmlspecialchars($message), PDO::PARAM_STR);
        $createmes->bindValue(':time', time(), PDO::PARAM_INT);
        
        return $createmes->execute();
    }
    catch (PDOException $e) {
        echo $e->getMessage();

        return false;
    }
  }

// lecture des messages recus

function getmessage () {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
    
    try {
        // on commence par retrouver l'identité de l'emetteur du message (le client)
        $client = getUser();
        if (!$client) return false;

        // ensuite, on lit les  message dans la table messages

        $getmes = $pdo->prepare('SELECT * FROM messages WHERE idTo= :to ');   
        $getmes->bindValue(':to', $client["idContact"], PDO::PARAM_STR);
        $getmes->execute();
        return $getmes->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        echo $e->getMessage();

        return false;
    }
  }

  function getmessagefrom ($id) {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
    
    try {
        // on commence par retrouver l'identité du destinaitaire (l'utilisateur connecté)
        $client = getUser();
        if (!$client) return false;

        // ensuite, on lit les  messages dans la table messages

        $getmes = $pdo->prepare('SELECT * FROM messages WHERE idTo= :to AND idFrom= :from');   
        $getmes->bindValue(':to', $client["idUser"], PDO::PARAM_STR);
        $getmes->bindValue(':from', $id, PDO::PARAM_STR);
        $getmes->execute();
        return $getmes->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        echo $e->getMessage();

        return false;
    }
  }

  function getorderfrom ($id) {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
    
    try {
 
        $getmes = $pdo->prepare('SELECT * FROM orders WHERE idUser= :iduser AND state= 1');   
        $getmes->bindValue(':iduser', $id, PDO::PARAM_STR);
        $getmes->execute();
        return $getmes->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        echo $e->getMessage();

        return false;
    }
  }

  function getcomments ($idorder) {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
    
    try {
        // on lit les  commentaires sur la commande dans la table comments

        $getmes = $pdo->prepare('SELECT * FROM comments WHERE idOrder=:idorder');   
        $getmes->bindValue(':idorder', $idorder, PDO::PARAM_INT);
        $getmes->execute();
        return $getmes->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        echo $e->getMessage();

        return false;
    }
  }


  function sendtoclient ($client,$objet,$message) {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
    
        try {
                    // on commence par retrouver l'identité de l'emetteur du message (l'employé)
        $employe= getUser();
        if (!$employe) return false;

        // ensuite, on enregistre le message
        $mess = $pdo->prepare('INSERT INTO messages (idFrom,idTo,objet,message,time,state) VALUES (:idfrom,:idto,:objet,:message,:time,0)');  
        $mess->bindValue(':idfrom', $employe["idUser"], PDO::PARAM_INT);
        $mess->bindValue(':idto', $client, PDO::PARAM_INT);
        $mess->bindValue(':objet', htmlspecialchars($objet), PDO::PARAM_STR);
        $mess->bindValue(':message', htmlspecialchars($message), PDO::PARAM_STR);
        $mess->bindValue(':time', time(), PDO::PARAM_INT);
        $mess->execute();
        return true;
        }
        catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }
  }

  // validation  d'une commande par l'employé

  function processorder ($idorder,$comment) {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
    
        try {

       // on passe la commande au statut validé (state=2)
        $val = $pdo->prepare('UPDATE orders SET state=2 WHERE idOrder=:idorder');
        $val->bindValue(':idorder', $idorder, PDO::PARAM_INT);
        $val->execute();

        // ensuite, on enregistre le commentaire
        $mess = $pdo->prepare('INSERT INTO comments (idOrder,comment,time) VALUES (:idorder,:comment,:time)');  
        $mess->bindValue(':idorder', $idorder, PDO::PARAM_INT);
        $mess->bindValue(':time', time(), PDO::PARAM_INT);
        $mess->bindValue(':comment', htmlspecialchars($comment), PDO::PARAM_STR);
        $mess->execute();
        return true;
        }
        catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }
  }

  function getCommande () {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
    
        // on recherche dans users le client correspondant au token de connexion
        // puis, avec comme clef son identifiant, on trouve les commandes dans la table orders
        // puis avec comme clef les numéros de commande, on trouve les éléments de chaque commande dans carelement
        // enfin, on cherche les détails de chaque produit commandé.
        $checkorder = $pdo->prepare('SELECT users.iduser,orders.idorder,orders.state,cartelements.idproduct,cartelements.volume,cartelements.price,products.label 
                                    FROM users
                                    JOIN orders ON users.iduser=orders.iduser   
                                    JOIN cartelements ON orders.idorder=cartelements.idorder 
                                    JOIN products ON cartelements.idproduct=products.idproduct 
                                    WHERE hashtoken=:hashtoken 
                                    ORDER BY orders.idorder DESC');   
        $checkorder->bindValue(':hashtoken', hash('md5', $_SESSION["token"]), PDO::PARAM_STR);
        $checkorder->execute();
        return $checkorder->fetchAll(PDO::FETCH_ASSOC);
  }
 
  // creation d'une nouvelle catégorie de produits

  function createcategorie ($categorie) {
    global $pdo;
 
        try {
            $createcat = $pdo->prepare('INSERT INTO categories(categorie) VALUES (:categorie) ');   
            $createcat->bindValue(':categorie', htmlspecialchars($categorie), PDO::PARAM_STR);
            $createcat->execute();
            return true;   
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }    
  }
 
  // ajout d'un nouveau produit dans le catalogue

  function createProduct ($label,$categorie,$picture,$description,$price) {
    global $pdo;

        try {
            $createprod = $pdo->prepare('INSERT INTO products(label,categorie,picture,description,price,stock) 
                                        VALUES (:label,:categorie,:picture,:description,:price,100000) ');   
            $createprod->bindValue(':label', htmlspecialchars($label), PDO::PARAM_STR);
            $createprod->bindValue(':categorie', htmlspecialchars($categorie), PDO::PARAM_STR);
            $createprod->bindValue(':picture', $picture, PDO::PARAM_STR);
            $createprod->bindValue(':description', htmlspecialchars($description), PDO::PARAM_STR);
            $createprod->bindValue(':price', htmlspecialchars($price), PDO::PARAM_STR);
            $createprod->execute();
            return true;   
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }    
  }

  // ajout d'un article dans le panier

  function addtocart ($idproduct,$quantity,$price) {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
 
        try {
            // on commence par identifier le client via son token
            $user= getUser();
            if (!$user) return false;
    
            // puis on regarde si il a un panier en cours dans la table orders 
            $getorder = $pdo->prepare('SELECT idOrder  FROM orders WHERE idUser=:user AND state=0');
            $getorder->bindValue(':user', $user["idUser"], PDO::PARAM_STR);
            $getorder->execute();
            $order= $getorder->fetch(PDO::FETCH_ASSOC);

            //si pas de panier en cours, on le crée dans la table orders
            if (!$order) {
                $setorder = $pdo->prepare('INSERT INTO orders(idUser,state) VALUES (:user,0) ');
                $setorder->bindValue(':user', $user["idUser"], PDO::PARAM_STR);
                $setorder->execute();
                $getorder = $pdo->prepare('SELECT idOrder  FROM orders WHERE idUser=:user AND state=0');
                $getorder->bindValue(':user', $user["idUser"], PDO::PARAM_STR);
                $getorder->execute();
                $order= $getorder->fetch(PDO::FETCH_ASSOC);   
            }
            //enfin on crée le cart element

            $atc = $pdo->prepare('INSERT INTO cartelements(idProduct,volume,price,idOrder) 
                                        VALUES (:idproduct,:volume,:price,:idorder) ');   
            $atc->bindValue(':idproduct', $idproduct, PDO::PARAM_STR);
            $atc->bindValue(':volume', htmlspecialchars($quantity), PDO::PARAM_STR);
            $atc->bindValue(':price', htmlspecialchars($price), PDO::PARAM_STR);
            $atc->bindValue(':idorder', $order["idOrder"], PDO::PARAM_STR);
            $atc->execute();
            return true;   
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }    
  }
 
  function getcart () {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
 
        try {
            // on commence par identifier le client via son token
            $user= getUser();
            if (!$user) return false;
    
            // puis on regarde si il a un panier en cours dans la table orders 
            $getorder = $pdo->prepare('SELECT idOrder  FROM orders WHERE idUser=:user AND state=0');
            $getorder->bindValue(':user', $user["idUser"], PDO::PARAM_STR);
            $getorder->execute();
            $order= $getorder->fetch(PDO::FETCH_ASSOC);
            if(!$order) return false;

            //enfin on lit les cart element

            $atc = $pdo->prepare('SELECT cartelements.idCartElement,cartelements.idProduct,cartelements.idProduct,cartelements.volume,cartelements.price,cartelements.idOrder,products.label FROM cartelements 
                                        JOIN products ON products.idProduct=cartelements.idProduct
                                        WHERE idOrder=:idorder');   
            $atc->bindValue(':idorder', $order["idOrder"], PDO::PARAM_STR);
            $atc->execute();   
            return $atc->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }    
  }

  // donne toutes les commandes avec le statut créée d'un client 
  function getorders ($iduser) {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
 
        try {

            //enfin on lit les cart elements correspondants aux commandes crées par l'utilisateur en pramétre, eton y adjoint le nom des produits

            $atc = $pdo->prepare('SELECT orders.idOrder,orders.time,cartelements.idCartElement,cartelements.idProduct,cartelements.idProduct,cartelements.volume,cartelements.price,cartelements.idOrder,products.label FROM orders
                                        JOIN cartelements ON cartelements.idOrder=orders.idOrder
                                        JOIN products ON products.idProduct=cartelements.idProduct
                                        WHERE orders.idUser=:iduser and orders.state=1');   
            $atc->bindValue(':iduser', $iduser, PDO::PARAM_STR);
            $atc->execute();   
            return $atc->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }    
  }

  // donne toutes les commandes quelque soit le statut (sauf panier) créée d'un client 
  function getallorders ($iduser) {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
 
        try {

            //enfin on lit les cart elements correspondants aux commandes crées par l'utilisateur en pramétre, eton y adjoint le nom des produits

            $atc = $pdo->prepare('SELECT orders.idOrder,orders.time,cartelements.idCartElement,cartelements.idProduct,cartelements.idProduct,cartelements.volume,cartelements.price,cartelements.idOrder,products.label FROM orders
                                        JOIN cartelements ON cartelements.idOrder=orders.idOrder
                                        JOIN products ON products.idProduct=cartelements.idProduct
                                        WHERE orders.idUser=:iduser and orders.state!=0');   
            $atc->bindValue(':iduser', $iduser, PDO::PARAM_STR);
            $atc->execute();   
            return $atc->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }    
  }

  // donne les détails d'une commande 
  function getoneorder ($idorder) {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
 
        try {

            //on lit les cart elements correspondants à la commande en pramétre, eton y adjoint le nom des produits

            $atc = $pdo->prepare('SELECT orders.idOrder,orders.time,cartelements.idCartElement,cartelements.idProduct,cartelements.idProduct,cartelements.volume,cartelements.price,cartelements.idOrder,products.label,products.picture FROM orders
                                        JOIN cartelements ON cartelements.idOrder=orders.idOrder
                                        JOIN products ON products.idProduct=cartelements.idProduct
                                        WHERE orders.idOrder=:idorder and orders.state!=0');   
            $atc->bindValue(':idorder', $idorder, PDO::PARAM_STR);
            $atc->execute();   
            return $atc->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }    
  }

  function getorder ($idorder) {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
 
        try {
            $atc = $pdo->prepare('SELECT * FROM orders WHERE idOrder=:order');   
            $atc->bindValue(':order', $idorder, PDO::PARAM_STR);
            $atc->execute();   
            return $atc->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }    
  }

  function erasecartelement ($idelement) {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
 
        try {
            // on commence par identifier le client via son token
            $user= getUser();
            if (!$user) return false;
    
            //enfin on efface le cart element

            $atc = $pdo->prepare('DELETE FROM cartelements
                                        WHERE idCartElement=:idelement');   
            $atc->bindValue(':idelement', $idelement, PDO::PARAM_STR);
            $atc->execute();   
            return true;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }    
  }
 
  //confirmation de commande du panier en cours
  function confirmorder () {
    global $pdo;

        if (!isset ($_SESSION["token"])) return false;
 
        try {
            // on commence par identifier le client via son token
            $user= getUser();
            if (!$user) return false;
    
            //enfin on passe le panier en commande

            $atc = $pdo->prepare('UPDATE orders SET state=1,time=:time WHERE state=0 AND idUser=:user');
            $atc->bindValue(':user', $user["idUser"], PDO::PARAM_STR);
            $atc->bindValue(':time', time(), PDO::PARAM_INT);
            $atc->execute();   
            return true;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }    
  }
 
  function getcategories() {
    global $pdo;

    try {
        $getcat = $pdo->prepare('SELECT * FROM categories');   
        $getcat->execute();
        return $getcat->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        return null;
    }    

}
function getproducts($filtre) {
    global $pdo;

    try {
        if ($filtre=='toutes') {
            $getpro = $pdo->prepare('SELECT idProduct,label,price FROM products');   
        }
        else {
            $getpro = $pdo->prepare('SELECT idProduct,label,price FROM products WHERE categorie=:categorie');   
            $getpro->bindValue(':categorie', $filtre, PDO::PARAM_STR);
        }
        $getpro->execute();
        return $getpro->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        return false;
    }    

}
// extraction des caracteristiques du produit No $id
function getdetailproduct($id) {
    global $pdo;

    try {
        $getpro = $pdo->prepare('SELECT * FROM products WHERE idProduct=:id');   
        $getpro->bindValue(':id', $id, PDO::PARAM_INT);
        $getpro->execute();
        return $getpro->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        return false;
    }    
}

//  retourne tous les client de l'employé connecté
function getclients() {
    global $pdo;

    try {
        // on commence par retrouver l'identité de l'employé par son token
        $employe= getUser();
        //ensuite on récupere ses clients
        $getclients= $pdo->prepare('SELECT * FROM users WHERE idContact=:contact');   
        $getclients->bindValue(':contact', $employe["idUser"], PDO::PARAM_STR);
        $getclients->execute();
        return $getclients->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        return null;
    }    
}

//  retourne les détails d'un utilisateur

function getclient($id) {
    global $pdo;

    try {
        $getclient= $pdo->prepare('SELECT * FROM users WHERE idUser=:id');   
        $getclient->bindValue(':id', $id, PDO::PARAM_STR);
        $getclient->execute();
        return $getclient->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        return false;
    }    
}

function getmessagecount($from) {
    global $pdo;

    try {
        // on commence par retrouver l'identité du proprétaire de la boited e réception
        $to= getUser();
        //ensuite on récupere le nombre de message recu
        $getmes= $pdo->prepare('SELECT COUNT(idMessage) AS C FROM messages WHERE idTo=:to AND idFrom=:from');   
        $getmes->bindValue(':to', $to["idUser"], PDO::PARAM_STR);
        $getmes->bindValue(':from', $from, PDO::PARAM_STR);
        $getmes->execute();
        $res = $getmes->fetch(PDO::FETCH_ASSOC);
        return $res["C"];
    }
    catch (PDOException $e) {

        return 0;
    }    

}

//recupere les commandes crée par un utilisateur (etat=1)
function getordercount($user) {
    global $pdo;

    try {
        $getord= $pdo->prepare('SELECT COUNT(idOrder) AS C FROM orders WHERE state=1 AND idUser=:user');   
        $getord->bindValue(':user', $user, PDO::PARAM_STR);
        $getord->execute();
        $res = $getord->fetch(PDO::FETCH_ASSOC);
        return $res["C"];
    }
    catch (PDOException $e) {

        return 0;
    }    

}



function cancelUser () {
    /* the token will be used to know which account is to be deleted */
    unset($_SESSION['token']);
    return true;
}

