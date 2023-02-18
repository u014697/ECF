-- les utilisateurs (et l'admin sont a créer avec le script initusers.php

-- creation des catégories

INSERT INTO Categories VALUES ('haut de gamme');
INSERT INTO Categories VALUES ('entrée de gamme');
INSERT INTO Categories VALUES ('bon rapport qualité prix');

-- création des produits

INSERT INTO Products (label,categorie,picture,description,price,stock) 
        VALUES ('Produit No1','haut de gamme','image/hdg.png','Produit haut de gamme trés bien et trés cher',3157.20,4000);
INSERT INTO Products (label,categorie,picture,description,price,stock) 
        VALUES ('Produit No2','entrée de gamme','image/bdg.png','Produit bas de gamme pas trés bien mais pas cher',512.20,4000);
INSERT INTO Products (label,categorie,picture,description,price,stock) 
        VALUES ('Produit No3','bon rapport qualité prix','image/mdg.png','Produit ayant un trés bon rapport qualité prix',1254.20,4000);
        


