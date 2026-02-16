-- Insertion des données dans les nouvelles tables

-- Insertion des régions
INSERT INTO gd_regions (nom) VALUES 
('Atsinanana'), ('Atsimo Andrefana'), ('Analamanaga');

-- Insertion des villes
INSERT INTO gd_villes (idRegion, nomVille) VALUES 
(1, 'Toamasina'), (1, 'Fenerive Est'), 
(2, 'Toliara'), (2, 'Morombe'),
(3, 'Antananarivo');

-- Insertion des types de dons
INSERT INTO gd_typesDons (nom) VALUES 
('nature'),
('materiaux'),
('argent');

-- Insertion des besoins par ville
INSERT INTO gd_besoinVille (idVille, idDon, qte) VALUES 
(1, 1, 1000.00), -- 1000kg de riz pour Toamasina
(2, 2, 500.00),  -- 500 packs d'eau pour Fenerive Est
(4, 3, 3000);   /* 50m argent pour Morombe */

-- Insertion des dons reçus
INSERT INTO gd_dons (libelle, pu, idTypes) VALUES 
('Riz', 500.00, 1),
('Huile', 1200.00, 1),
('Kits medical', 15000.00, 2),
('argent', 1, 3);

-- Insertion des stocks
INSERT INTO gd_stock (idDon, qte) VALUES 
(1, 2000.00),
(2, 100.00),
(3, 100.00),
(4, 150000.00);