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

            <div class="sidebar__item">
                <a class="sidebar__link d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseAchatMenu"
                    role="button"
                    aria-expanded="<?= (strpos($_SERVER['REQUEST_URI'], 'achat') !== false) ? 'true' : 'false' ?>"
                    aria-controls="collapseAchatMenu"
                    style="cursor: pointer;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Achats</span>
                    </div>
                    <i class="fa-solid fa-chevron-down small"></i>
                </a>

                <div class="collapse <?= (strpos($_SERVER['REQUEST_URI'], 'achat') !== false) ? 'show' : '' ?>" id="collapseAchatMenu">
                    <div class="ps-4 mt-2">
                        <a href="<?= BASE_URL ?>/achat" class="d-block py-2 text-decoration-none sidebar__link">
                            <i class="fa-solid fa-circle-plus me-1"></i> Nouvel achat
                        </a>
                        <a href="<?= BASE_URL ?>/achat/liste" class="d-block py-2 text-decoration-none sidebar__link">
                            <i class="fa-solid fa-list-ul me-1"></i> Liste des achats
                        </a>
                    </div>
                </div>
            </div>

            <div class="sidebar__item">
                <a class="sidebar__link d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse"
                    href="#submenuStock"
                    role="button"
                    aria-expanded="false"
                    aria-controls="submenuStock">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-box-open"></i>
                        <span>Stock</span>
                    </div>
                    <i class="fa-solid fa-chevron-down small"></i>
                </a>

                <div class="collapse <?= (strpos($_SERVER['REQUEST_URI'], 'stock') !== false) ? 'show' : '' ?>" id="submenuStock">
                    <div class="ps-4 mt-2">
                        <a href="<?= BASE_URL ?>/stock/form" class="d-block py-2 text-decoration-none sidebar__link">
                            <i class="fa-solid fa-plus-circle me-1"></i> Insertion Stock
                        </a>
                        <!-- <a href="<?= BASE_URL ?>/stock/liste" class="d-block py-2 text-decoration-none sidebar__link">
                            <i class="fa-solid fa-list-ul me-1"></i> État du Stock
                        </a> -->
                    </div>
                </div>
            </div>

            <div class="sidebar__item">
                <a class="sidebar__link d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse"
                    data-bs-target="#submenuDons"
                    role="button"
                    aria-expanded="<?= (strpos($_SERVER['REQUEST_URI'], 'Dons') !== false) ? 'true' : 'false' ?>"
                    aria-controls="submenuDons"
                    style="cursor: pointer;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                        <span>Dons</span>
                    </div>
                    <i class="fa-solid fa-chevron-down small"></i>
                </a>

                <div class="collapse <?= (strpos($_SERVER['REQUEST_URI'], 'Dons') !== false) ? 'show' : '' ?>" id="submenuDons">
                    <div class="ps-4 mt-2">
                        <a href="<?= BASE_URL ?>/form/ajoutDons" class="d-block py-2 text-decoration-none sidebar__link">
                            <i class="fa-solid fa-plus-circle me-1"></i> Nouvel ajout
                        </a>
                    </div>
                </div>
            </div>

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