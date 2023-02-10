-- cette transaction sql est destinée a s'assurer qu'au moment du paiement, l' article 
-- du panier est bien disponibles (stock suffisant)

DELIMITER //

CREATE FUNCTION OrderCart ( vrefcommande INT )  RETURNS INT 

BEGIN

DECLARE 
vid,  -- identifiant produit
vvol, -- volume demandé
vstock, -- volume en stock
vclient,-- identifiant client
vorder INT(11) ;-- identifant du panier

SELECT CartElements.idProduct,CartElements.volume,Products.stock,Orders.idUser,Orders.idOrder 
    INTO vid,vvol,vstock,vclient,vorder
    FROM CartElements
    JOIN Products ON cartelements.idProduct=Products.idProduct
    JOIN Orders ON cartelements.idOrder=Orders.idOrder
    WHERE Orders.idOrder=vrefcommande; 



IF (vstock >= vvol)
THEN

  -- On ajuste le stock restant dans la table des produits
  UPDATE Products SET stock = (vstock-vvol)  WHERE idProduct=vid;

  -- On valide la transaction en passant l'état du panier à commandé (valeur 1)
  UPDATE Orders SET state=1 WHERE idOrder = vorder;

  -- Validation
   return 1;
ELSE
 return 0;
END IF;
END;//

DELIMITER ;

-- avec Maria DB il ne semble pas possible de placer de COMMIT dans la procedure, ni de faire des IF en dehors de la procédure ...
-- on effectue donc la transaction autour de la procedure. la valeur de retour indiquera au programme utilisatueur sila transaction
-- a été effectuée ou pas.
-- si elle n'a pas été effectuées, seule une lecture a été faite donc inutile de fairte un rollback dans ce cas :)
START TRANSACTION;
   select ordercart(1);
COMMIT;  