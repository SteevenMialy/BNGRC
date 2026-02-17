INSERT INTO gd_villes (idRegion, nomVille)
SELECT r.id, v.nomVille
FROM (
    SELECT 'Toamasina'   AS nomVille, 'Atsinanana'       AS region
    UNION ALL SELECT 'Mananjary',   'Atsinanana'
    UNION ALL SELECT 'Farafangana', 'Atsinanana'
    UNION ALL SELECT 'Nosy Be',     'Atsinanana'
    UNION ALL SELECT 'Morondava',   'Atsimo Andrefana'
) v
JOIN gd_regions r ON r.nom = v.region
WHERE NOT EXISTS (
    SELECT 1
    FROM gd_villes gv
    WHERE gv.nomVille = v.nomVille
);
