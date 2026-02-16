
INSERT INTO gd_regions (nom_region) VALUES 
('Dakar'), ('Saint-Louis'), ('Ziguinchor');

INSERT INTO gd_villes (id_region, nom_ville) VALUES 
(1, 'Dakar Plateau'), (1, 'Pikine'), 
(2, 'Saint-Louis Centre'), 
(3, 'Bignona');

INSERT INTO gd_types_besoin (nom_types, prix_unitaire, libele) VALUES 
('Riz', 500.00, 'kg'),
('Huile', 1200.00, 'Litre'),
('Kit Médical', 15000.00, 'Unité'),
('Eau minérale', 300.00, 'Pack');

INSERT INTO gd_besoins_ville (id_ville, id_types, quantite_demandee) VALUES 
(1, 1, 1000.00), -- 1000kg de riz pour Dakar
(2, 4, 500.00),  -- 500 packs d'eau pour Pikine
(4, 3, 50.00);   -- 50 kits médicaux pour Bignona

-- Enregistrement des dons reçus
INSERT INTO gd_dons (libelle_don, id_types, quantite) VALUES 
('Don de l''ONG Alpha - Riz', 1, 2000.00),
('Don Particulier - Huile', 2, 100.00),
('Don Fondation Santé - Kits', 3, 100.00);

-- Entrée en stock de ces dons
INSERT INTO gd_stock (id_don, quantite) VALUES 
(1, 2000.00),
(2, 100.00),
(3, 100.00);

