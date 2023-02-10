<?php

// verification du email/pass. si OK, mise en place d'un token avec enregistrement dans la BDD et durée de validité
function checkLogin($user,$pass) {
    global $pdo;
    try {    
        $checklo = $pdo->prepare('SELECT hashpass FROM users WHERE email=:email');
        $checklo->bindValue(':email', $user, PDO::PARAM_STR);
        $checklo->execute();
        $result= $checklo->fetch(PDO::FETCH_ASSOC);
        if (password_verify($pass,$result["hashpass"])) {    // le mdp coincide avec le mail !
            $token = bin2hex(random_bytes(16));
            $settoken = $pdo->prepare("UPDATE users SET hashtoken=:token, expirationtoken=:expiration WHERE email=:email") ;
            $settoken->bindValue(':token', hash('md5', $token), PDO::PARAM_STR);
            $expiration = date("Y/m/d",strtotime('+1 day'));    // le token est valable 1 jour aprés la derniere visite
            $settoken->bindValue(':expiration', $expiration, PDO::PARAM_STR);
            $settoken->bindValue(':email', $user, PDO::PARAM_STR);
            echo hash('md5', $token)."<br>".$expiration."<br>".$user."<br>";
            $settoken->execute();
            $_SESSION["token"] = $token;
            return true;
        }
        return false;
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
            $checktok = $pdo->prepare('SELECT count(hashtoken) AS C FROM users WHERE hashtoken=:hashtoken');
            $checktok->bindValue(':hashtoken', hash('md5', $_SESSION["token"]), PDO::PARAM_STR);
            $checktok->execute();
            $result= $checktok->fetch(PDO::FETCH_ASSOC);
        
            if ($result["C"] == 1) {    // le token a été trouvé !
                return true; 
            }    
        }
        return false;
    
    }
    catch (PDOException $e) {
        return false;
    }

}

// on lit le role correspondant au token 

function checkRole () {
    global $pdo;

    $statement = $pdo->prepare("select role FROM Users WHERE hashToken = :hashtoken");
    $statement->bindValue(':hashtoken', hash('md5', $_SESSION["token"]), PDO::PARAM_STR);
    $statement->execute();
    $role = $statement->fetch(PDO::FETCH_ASSOC);
    return $role["role"];
}

function createUser ($email,$pass,$nom,$prenom,$societe,$newrole) {
    global $pdo;
    try {
        if (!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/', $pass)) return false;

        if ($newrole==1)  {  // pour un client, on recherche le conseillé le moins chargé pour lui affecter le client
            $statement1 = $pdo->prepare('SELECT idcontact,count(idContact) AS C FROM users WHERE role=1 group by idContact ORDER BY C ASC Limit 1');
            $statement1->execute();
            $result= $statement1->fetch(PDO::FETCH_ASSOC);;
            $idcontact=$result["idcontact"];
        }
        else {
            $idcontact=0;
        }

        $statement = $pdo->prepare("INSERT INTO users(name,firstname,company,email,idcontact,registrationnumber,hashpass,hashtoken,expirationtoken,role)
           VALUES(:nom,:prenom,:company,:email,:idcontact,:registrationnumber,:hashpass,:hashtoken,:expirationToken,:role)");
        $statement->bindValue(':nom', $nom, PDO::PARAM_STR);
        $statement->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $statement->bindValue(':company', $societe, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':role', $newrole, PDO::PARAM_INT);
        $statement->bindValue(':idcontact',$idcontact, PDO::PARAM_STR);
        if (($newrole==2)||($newrole==1)) {  // les employés yc l'administrateur ont un matricule
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

function cancelUser () {
    /* the token will be used to know which account is to be deleted */
    unset($_SESSION['token']);
    return true;
}

function getName() {
    return "name";
}

function getFirstName() {
    return "firstname";
}

function getBirthDate() {
    $date = "25-06-1996";
    return date("Y-m-d", strtotime($date));   
}

function getEmail() {
    return "monemail@chezmoi.com";
}

function setName($a) {
    return;
}
function setFirstName($a) {
    return;
}
function setBirthDate($a) {
    return;
}
function setEmail($a) {
    return;
}
function setAvatar($a) {
    return;
}
?>