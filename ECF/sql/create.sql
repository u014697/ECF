CREATE DATABASE Ventalis;

CREATE TABLE Users (
    idUser INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    firstName VARCHAR(50) NOT NULL,
    company VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    idContact VARCHAR(50),
    registrationNumber INT(11),
    hashPass VARCHAR(100) NOT NULL,
    hashToken VARCHAR(100) ,
    expirationToken DATETIME ,
    role int(11) NOT NULL
);

CREATE TABLE Categories (
    categorie VARCHAR(50) NOT NULL PRIMARY KEY
);

CREATE TABLE Products (
    idProduct INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(50) NOT NULL,
    categorie VARCHAR(50) NOT NULL,
    picture VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    stock int(11) NOT NULL,
    FOREIGN KEY (categorie) REFERENCES Categories(categorie)
);

CREATE TABLE Orders (
    idOrder INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idUser INT(11) NOT NULL,
    state INT(11) NOT NULL,
    FOREIGN KEY (idUser) REFERENCES Users(idUser)
);

CREATE TABLE CartElements (
    idCartElement INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idProduct INT(11) NOT NULL,
    volume INT(11) NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    idOrder int(11) NOT NULL,
    FOREIGN KEY (idProduct) REFERENCES Products(idProduct),
    FOREIGN KEY (idOrder) REFERENCES Orders(idOrder) 
);

CREATE TABLE Comments (
    idComment INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idOrder INT(11) NOT NULL,
    comment TEXT NOT NULL,
    FOREIGN KEY (idOrder) REFERENCES Orders(idOrder)
);
