
-- Insertion de données d'exemple
/* Regions */
INSERT INTO gd_regions (nom_region) VALUES
('Casablanca-Settat'),
('Marrakech-Safi'),
('Rabat-Salé-Kénitra');
/*Villes*/
INSERT INTO gd_villes (id_region, nom_ville) VALUES
(1, 'Casablanca'),
(1, 'Settat'),
(2, 'Marrakech'),
(2, 'Safi'),
(3, 'Rabat'),
(3, 'Kénitra');
/* Catégories de besoins */
INSERT INTO gd_types_besoin (nom_types, libele) VALUES
('Eau potable', 'litres'),
('Nourriture', 'kg'),
('Vêtements', 'pièces'),
('Médicaments', 'boîtes'),
('Couvertures', 'pièces');
/* Besoins par ville */
INSERT INTO gd_besoins_ville (id_ville, id_types, quantite_demandee, prix_unitaire, date_demande) VALUES
(1, 1, 500, 1.50, '2026-02-10'),
(1, 2, 300, 4.00, '2026-02-11'),
(2, 3, 200, 10.00, '2026-02-12'),
(3, 5, 150, 25.00, '2026-02-13'),
(4, 1, 400, 1.50, '2026-02-14'),
(5, 4, 100, 30.00, '2026-02-15');
/* Dons reçus */
INSERT INTO gd_dons (id_types, quantite, date_reception) VALUES
(1, 200, '2026-02-10'),
(1, 300, '2026-02-11'),
(2, 150, '2026-02-11'),
(3, 100, '2026-02-12'),
(5, 80, '2026-02-13'),
(4, 60, '2026-02-14');

/* Donnateurs */
INSERT INTO gd_donnateur (nom_donnateur) VALUES
('Donnateur A'),
('Donnateur B'),
('Donnateur C');

/* Stock */
INSERT INTO gd_stock (id_don, id_donnateur, quantite, date_reception) VALUES
(1, 1, 200, '2026-02-10'),
(2, 2, 300, '2026-02-11'),
(3, 3, 150, '2026-02-11'),
(4, 1, 100, '2026-02-12'),
(5, 2, 80, '2026-02-13'),
(6, 3, 60, '2026-02-14');



