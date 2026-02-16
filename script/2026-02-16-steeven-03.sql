update gd_stock set qte = 0 where idDon = 1;
ALTER TABLE gd_achat add column idDons INT NOT NULL;
ALTER TABLE gd_achat add foreign key (idDons) references gd_dons(id);
ALTER TABLE gd_achat add column quantite DECIMAL(15,2) NOT NULL after taux;

