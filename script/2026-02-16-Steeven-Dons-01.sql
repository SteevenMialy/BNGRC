CREATE DATABASE IF NOT EXISTS gestion_dons;
USE gestion_dons;

CREATE TABLE gd_regions(
    id_region INT AUTO_INCREMENT PRIMARY KEY,
    nom_region VARCHAR(100) NOT NULL
);

CREATE TABLE gd_villes (
    id_ville INT AUTO_INCREMENT PRIMARY KEY,
    id_region INT NOT NULL,
    nom_ville VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_region) REFERENCES gd_regions(id_region)
);

CREATE TABLE gd_types_besoin (
    id_types INT AUTO_INCREMENT PRIMARY KEY,
    nom_types VARCHAR(100) NOT NULL,
    prix_unitaire DECIMAL(15,2) NOT NULL,
    libele VARCHAR(20)
);

CREATE TABLE gd_besoins_ville (
    id_besoin INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_types INT NOT NULL,
    quantite_demandee DECIMAL(15,2) NOT NULL,
    date_demande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ville) REFERENCES gd_villes(id_ville),
    FOREIGN KEY (id_types) REFERENCES gd_types_besoin(id_types)
);

CREATE TABLE gd_dons (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    libelle_don VARCHAR(255),
    id_types INT NOT NULL,
    quantite DECIMAL(15,2) NOT NULL,
    date_reception TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_types) REFERENCES gd_types_besoin(id_types)
);

CREATE TABLE gd_stock (
    id_stock INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT NOT NULL,
    quantite DECIMAL(15,2) NOT NULL,
    date_reception TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_don) REFERENCES gd_dons(id_don)
);

CREATE TABLE gd_mvstock (
    id_mvstock INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NOT NULL,
    id_stock INT NOT NULL,
    entree DECIMAL(15,2) NOT NULL,
    sortie DECIMAL(15,2) NOT NULL,
    date_attribution TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    designation VARCHAR(255),
    FOREIGN KEY (id_besoin) REFERENCES gd_besoins_ville(id_besoin),
    FOREIGN KEY (id_stock) REFERENCES gd_stock(id_stock)
);

