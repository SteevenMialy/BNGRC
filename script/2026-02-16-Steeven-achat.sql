
CREATE TABLE gd_achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idVille INT NOT NULL,
    idDons INT NOT NULL,
    taux int NOT NULL,
    quantite DECIMAL(15,2) NOT NULL,
    date_achat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idVille) REFERENCES gd_villes(id),
    FOREIGN KEY (idDons) REFERENCES gd_dons(id)
);


