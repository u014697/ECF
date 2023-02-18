
CREATE TABLE users (
    idUser INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    firstName VARCHAR(50) NOT NULL,
    company VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    idContact VARCHAR(50),
    registrationNumber INT(11),
    hashPass VARCHAR(100) NOT NULL,
    hashToken VARCHAR(100) ,
    expirationToken INT(11) NOT NULL ,
    tobechanged int(11) NOT NULL DEFAULT 0,
    role int(11) NOT NULL
);

CREATE TABLE categories (
    categorie VARCHAR(50) NOT NULL PRIMARY KEY
);

CREATE TABLE products (
    idProduct INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(50) NOT NULL,
    categorie VARCHAR(50) NOT NULL,
    picture VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    stock int(11) NOT NULL,
    FOREIGN KEY (categorie) REFERENCES categories(categorie)
);

CREATE TABLE orders (
    idOrder INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idUser INT(11) NOT NULL,
    state INT(11) NOT NULL,
    time INT(11) NOT NULL DEFAULT 0,
    FOREIGN KEY (idUser) REFERENCES users(idUser)
);

CREATE TABLE cartelements (
    idCartElement INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idProduct INT(11) NOT NULL,
    volume INT(11) NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    idOrder int(11) NOT NULL,
    FOREIGN KEY (idProduct) REFERENCES products(idProduct),
    FOREIGN KEY (idOrder) REFERENCES orders(idOrder) 
);

CREATE TABLE comments (
    idComment INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idOrder INT(11) NOT NULL,
    comment TEXT NOT NULL,
    time INT(11) NOT NULL DEFAULT 0,
    FOREIGN KEY (idOrder) REFERENCES orders(idOrder)
);

CREATE TABLE messages (
    idMessage INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idFrom INT(11) NOT NULL,
    idTo INT(11) NOT NULL,
    objet VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    time INT(11) NOT NULL,
    state INT(11) NOT NULL DEFAULT 0,
    FOREIGN KEY (idFrom) REFERENCES users(idUser),
    FOREIGN KEY (idTo) REFERENCES users(idUser)
);
