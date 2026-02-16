<?php include 'includes/side-bar.php'; ?>

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
            <h1 class="topbar__title">Liste des Achats</h1>
        </div>
    </div>

    <div class="table-section">
        
        <div class="table-section__header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="table-section__title">Historique des achats</div>
                <div class="table-section__subtitle">Consultez et filtrez vos transactions</div>
            </div>
            
            <form method="get" id="filterVilleForm" class="d-flex gap-2">
                <select name="idville" id="idville" class="form-select" style="min-width: 200px;">
                    <option value="">Toutes les villes</option>
                    <?php if (!empty($villes)) {
                        foreach ($villes as $v) { ?>
                            <option value="<?= $v['id'] ?>" <?= (!empty($selectedVille) && (int)$selectedVille === (int)$v['id']) ? 'selected' : '' ?>>
                                <?= $v['nomVille'] ?>
                            </option>
                        <?php }
                    } ?>
                </select>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-filter"></i> Filtrer
                </button>
            </form>
        </div>

        <div class="table-wrap" style="padding: 32px;">
            
            <?php if (isset($validationOk)): ?>
                <?php if ($validationOk === false): ?>
                    <div class="alert alert-danger mb-4"><i class="fa-solid fa-xmark-circle me-2"></i> La validation de l'achat a échoué.</div>
                <?php elseif ($validationOk === true && isset($distributionOk) && $distributionOk === false): ?>
                    <div class="alert alert-warning mb-4"><i class="fa-solid fa-circle-exclamation me-2"></i> Achat validé, mais distribution non effectuée.</div>
                <?php elseif ($validationOk === true && isset($distributionOk) && $distributionOk === true): ?>
                    <div class="alert alert-success mb-4"><i class="fa-solid fa-check-circle me-2"></i> Achat validé puis distribué avec succès.</div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!empty($ville)): ?>
                <div class="mb-3">
                    <span class="text-muted">Filtré par :</span> <span class="badge-city"><?= $ville->nomVille ?></span>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ville</th>
                            <th>Don</th>
                            <th>Taux</th>
                            <th>Quantité</th>
                            <th>Date Achat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($achats)) {
                            foreach ($achats as $a) { ?>
                                <tr>
                                    <td class="text-muted">#<?= $a['id'] ?></td>
                                    <td><span class="fw-semibold"><?= $a['nomVille'] ?? '-' ?></span></td>
                                    <td><?= $a['libelle'] ?? '-' ?></td>
                                    <td><?= number_format($a['taux'], 2) ?>%</td>
                                    <td class="fw-bold"><?= $a['quantite'] ?></td>
                                    <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($a['date_achat'])) ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Aucun achat trouvé.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-3 border-top">
                <a href="<?= BASE_URL ?>/achat" class="btn-ghost">
                    <i class="fa-solid fa-arrow-left"></i> Nouveau calcul d'achat
                </a>
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('filterVilleForm').addEventListener('submit', function (e) {
        e.preventDefault();
        var idVille = document.getElementById('idville').value;
        if (idVille) {
            window.location.href = "<?= BASE_URL ?>/achat/liste/" + idVille;
        } else {
            window.location.href = "<?= BASE_URL ?>/achat/liste";
        }
    });
</script>
</body>
</html>