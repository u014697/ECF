-- creation d'un admin (role=3)
-- le hash 'provisoire' entrainera une pricédure de choix d'un mdp par l'utilisateur a sa première tentative de connection

INSERT INTO Users (name,firstName,company,email,registrationNumber,hashpass,role)
        VALUES ('The', 'Bigboss', 'Ventalis', 'thebigboss@ventalis.com',1001,'provisoire',3);

-- creation des employés (role=2)

INSERT INTO Users (name,firstName,company,email,registrationNumber,hashpass,role)
        VALUES ('John', 'Ventalis', 'Ventalis', 'johnVentalis@ventalis.com',1002,'provisoire',2);
INSERT INTO Users (name,firstName,company,email,registrationNumber,hashpass,role)
        VALUES ('Jane', 'Ventalis', 'Ventalis', 'janeVentalis@ventalis.com',1003,'provisoire',2);

-- creation des utilisateurs (role=1)

INSERT INTO Users (name,firstName,company,email,idcontact,hashpass,role)
        VALUES ('John', 'Doe', 'JD inc.', 'johnDoe@JD.com',2,'provisoire',1);
INSERT INTO Users (name,firstName,company,email,idcontact,hashpass,role)
        VALUES ('Jane', 'Doe', 'JD inc.', 'janeDoe@JD.com',2,'provisoire',1);


-- creation des catégories

INSERT INTO Categories VALUES ('haut de gamme');
INSERT INTO Categories VALUES ('entrée de gamme');
INSERT INTO Categories VALUES ('bon rapport qualité prix');

-- création des produits

INSERT INTO Products (label,categorie,picture,description,price,stock) 
        VALUES ('Produit No1','haut de gamme','image/hdg.jpg','Produit haut de gamme trés bien et trés cher',3157.20,4000);
INSERT INTO Products (label,categorie,picture,description,price,stock) 
        VALUES ('Produit No2','entrée de gamme','image/bdg.jpg','Produit bas de gamme pas trés bien mais pas cher',512.20,4000);
INSERT INTO Products (label,categorie,picture,description,price,stock) 
        VALUES ('Produit No3','bon rapport qualité prix','image/mdg.jpg','Produit ayant un trés bon rapport qualité prix',1254.20,4000);
        
-- creation de quelques commandes
-- l'état vaut 0 avant que la commande ne soit passée, puis 1 aprés achat

INSERT INTO Orders (idUser,state) VALUES (4,1);  -- une commande de Johne Doe validée
INSERT INTO Orders (idUser,state) VALUES (4,0);  --  panier non validé de Johne Doe

-- détail des paniers

INSERT INTO CartElements (idProduct,volume,price,idOrder)  VALUES (1,1000,3100,1);
INSERT INTO CartElements (idProduct,volume,price,idOrder)  VALUES (3,1000,500,1) ;  -- 2 articles dans la commande No1
INSERT INTO CartElements (idProduct,volume,price,idOrder)  VALUES (2,1000,1200,1);   -- 1 article dans le panier

-- et quelques commentaires du personnel sur la commande No1

INSERT INTO Comments (idOrder,comment) VALUES (1,'Commande en cours de préparation');
INSERT INTO Comments (idOrder,comment) VALUES (1,'Commande expédiée');
INSERT INTO Comments (idOrder,comment) VALUES (1,'Commande réceptionnée avec réserves (colis abimmé)');

