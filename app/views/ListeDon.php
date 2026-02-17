<?php include 'includes/side-bar.php'; 

use app\controllers\DonsController;?>

<style>
    /* Rappel des styles globaux pour la cohérence */
    .form-select {
        border: 1px solid #d1d5db !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        transition: all 0.2s ease;
    }

    .form-select:focus {
        border-color: #00ab66 !important;
        box-shadow: 0 0 0 4px rgba(0, 171, 102, 0.1) !important;
        outline: none;
    }

    .btn-primary {
        background-color: #00ab66 !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 9px 20px !important;
        font-weight: 600 !important;
        color: white !important;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-ghost {
        background-color: #f3f4f6 !important;
        color: #4b5563 !important;
        border-radius: 8px !important;
        padding: 10px 24px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* Style du tableau */
    .table-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }

    .table-custom th {
        background-color: #f9fafb;
        color: #4b5563;
        font-weight: 600;
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    .table-custom td {
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
        color: #1f2937;
    }

    .table-custom tr:last-child td {
        border-bottom: none;
    }

    .badge-city {
        background-color: #e0f2fe;
        color: #0369a1;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>

<main class="main">

    <div class="topbar">
        <div class="topbar__left">
            <div class="topbar__breadcrumb">
                Accueil &rsaquo; Achat &rsaquo; <span>Liste</span>
            </div>
            <h1 class="topbar__title">Etat du stock</h1>
        </div>
    </div>

    <div class="table-section">
        
        <div class="table-section__header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="table-section__title">Liste ds dons</div>
                <div class="table-section__subtitle">Consultez vos dons</div>
            </div>
        </div>

        <div class="table-wrap" style="padding: 32px;">
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>libelle</th>
                            <th>prix unitaire</th>
                            <th>type</th>
                            <th>date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dons)) {
                            foreach ($dons as $don) { ?>

                                <tr>
                                    <td class="text-muted">#<?= $don->id ?></td>
                                    <td><span class="fw-semibold"><?= $don->libelle ?? '-' ?></span></td>
                                    <td class="fw-bold"><?= $don->pu ?></td>
                                    <td class="fw-bold"><?= $don->types_besoin->nom ?></td>
                                    <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($don->daty)) ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Aucun don trouvé.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="<?= BASE_URL ?>/assets/js/jquery-3.7.1.min.js"></script>
<script src="<?= BASE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>