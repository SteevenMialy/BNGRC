-- Script to drop all tables in the database
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS gd_mvstock;
DROP TABLE IF EXISTS gd_stock;
DROP TABLE IF EXISTS gd_dons;
DROP TABLE IF EXISTS gd_besoins_ville;
DROP TABLE IF EXISTS gd_types_besoin;
DROP TABLE IF EXISTS gd_villes;
DROP TABLE IF EXISTS gd_regions;

