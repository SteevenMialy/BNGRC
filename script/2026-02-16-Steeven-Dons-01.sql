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
    libele VARCHAR(20)
);

CREATE TABLE gd_besoins_ville (
    id_besoin INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_types INT NOT NULL,
    quantite_demandee DECIMAL(15,2) NOT NULL,
    date_demande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    prix_unitaire DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (id_ville) REFERENCES gd_villes(id_ville),
    FOREIGN KEY (id_types) REFERENCES gd_types_besoin(id_types)
);

CREATE TABLE gd_dons (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_types INT NOT NULL,
    quantite DECIMAL(15,2) NOT NULL,
    date_reception TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_types) REFERENCES gd_types_besoin(id_types)
);

CREATE TABLE gd_donnateur (
    id_donnateur INT AUTO_INCREMENT PRIMARY KEY,
    nom_donnateur VARCHAR(100) NOT NULL
);

CREATE TABLE gd_stock (
    id_stock INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT NOT NULL,
    id_donnateur INT NOT NULL,
    quantite DECIMAL(15,2) NOT NULL,
    date_reception TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_don) REFERENCES gd_dons(id_don),
    FOREIGN KEY (id_donnateur) REFERENCES gd_donnateur(id_donnateur)
);

CREATE TABLE distributions (
    id_distribution INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NOT NULL,
    id_don INT NOT NULL,
    quantite_attribuee DECIMAL(15,2) NOT NULL,
    date_attribution TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_besoin) REFERENCES gd_besoins_ville(id_besoin),
    FOREIGN KEY (id_don) REFERENCES gd_dons(id_don)
);
