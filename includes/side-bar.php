<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC</title>

    <!-- Bootstrap 5.3 -->
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/lib/fontawesome/css/all.min.css">
    <!-- Google Fonts -->
    <link href="<?= BASE_URL ?>/assets/lib/fonts/google-fonts.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>

<body>

    <div class="layout">

        <aside class="sidebar">
            <div class="sidebar__logo">
                <div class="sidebar__logo-icon">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <div class="sidebar__logo-text">
                    BNGRC
                    <span>Gestion des dons</span>
                </div>
            </div>

            <span class="sidebar__section-label">Navigation</span>

            <a href="<?= BASE_URL ?>/" class="sidebar__link active">
                <i class="fa-solid fa-grip"></i>
                Tableau de bord
            </a>

            <a href="<?= BASE_URL ?>/" class="sidebar__link">
                <i class="fa-solid fa-file-lines"></i>
                Tous les besoins
                <span class="sidebar__badge">24</span>
            </a>

            <a href="<?= BASE_URL ?>/listBesoin/satisfaits" class="sidebar__link">
                <i class="fa-solid fa-check"></i>
                Satisfaits
            </a>

            <a href="<?= BASE_URL ?>/listBesoin/nonSatisfaits" class="sidebar__link">
                <i class="fa-solid fa-xmark"></i>
                Non satisfaits
                <span class="sidebar__badge" style="background:rgba(244,63,94,.12);color:#f43f5e;">7</span>
            </a>

            <span class="sidebar__section-label" style="margin-top:12px">Gestion</span>

            <a href="<?= BASE_URL ?>/achat" class="sidebar__link">
                <i class="fa-solid fa-cart-shopping"></i>
                Achats
            </a>

            <a href="<?= BASE_URL ?>/stock/form" class="sidebar__link">
                <i class="fa-solid fa-box-open"></i> Insertion Stock
            </a>

            <a href="<?= BASE_URL ?>/form/ajoutBesoin" class="sidebar__link">
                <i class="fa-solid fa-clipboard-list"></i>
                Enregistrer un besoin
            </a>

            <a href="#" class="sidebar__link">
                <i class="fa-solid fa-globe"></i>
                Régions
            </a>

            <a href="#" class="sidebar__link">
                <i class="fa-solid fa-users"></i>
                Donateurs
            </a>

        </aside>