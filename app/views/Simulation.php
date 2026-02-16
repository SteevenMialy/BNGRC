<?php include 'includes/side-bar.php'; ?>

<style>
    /* Rappel des styles pour la cohérence */
    .btn-primary {
        background-color: #00ab66 !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 10px 24px !important;
        font-weight: 600 !important;
        color: white !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none !important;
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

    /* Style spécifique pour le tableau de résultat */
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
    }

    .table-custom td {
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
        color: #1f2937;
    }
</style>

<main class="main">

    <div class="topbar">
        <div class="topbar__left">
            <div class="topbar__breadcrumb">
                Accueil &rsaquo; Achat &rsaquo; <span>Simulation</span>
            </div>
            <h1 class="topbar__title">Résultat de la Simulation</h1>
        </div>
    </div>

    <div class="table-section">
        <div class="table-section__header">
            <div>
                <div class="table-section__title">Détails du calcul</div>
                <div class="table-section__subtitle">Vérifiez les informations avant de confirmer l'achat</div>
            </div>
        </div>

        <div class="table-wrap" style="padding: 32px;">

            <?php if (isset($donDejaEnStock) && $donDejaEnStock): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <div class="text-danger"><i class="fa-solid fa-circle-exclamation me-2"></i><strong class="ml-3">Erreur :</strong> Ce don est encore disponible en stock. L'achat n'est pas nécessaire.</div>
                </div>
            <?php endif; ?>

            <?php if (isset($argentInsuffisant) && $argentInsuffisant): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="fa-solid fa-circle-xmark me-2"></i>
                    <div><strong>Erreur :</strong> Le prix calculé dépasse l'argent disponible. Simulation impossible.</div>
                </div>
            <?php endif; ?>

            <?php if (isset($prix, $stockreste, $argentreste, $ville, $don)) { ?>
                <div class="table-responsive mb-4">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Ville</th>
                                <th>Don</th>
                                <th>Prix Total</th>
                                <th>Stock restant</th>
                                <th>Argent restant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $ville->nomVille ?></td>
                                <td><?= $don->libelle ?></td>
                                <td class="fw-bold"><?= number_format($prix, 2, ',', ' ') ?></td>
                                <td><?= ($stockreste === false) ? '<span class="badge bg-danger">Insuffisant</span>' : $stockreste ?></td>
                                <td><?= ($argentreste === false) ? '<span class="badge bg-danger">Insuffisant</span>' : number_format($argentreste, 2, ',', ' ') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

            <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top">
                <?php if (isset($simulationValide) && $simulationValide && isset($idville, $iddon, $qte, $taux)): ?>
                    <a href="<?= BASE_URL ?>/achat/valider/<?= $idville ?>/<?= $iddon ?>/<?= $qte ?>/<?= $taux ?>" class="btn-primary">
                        <i class="fa-solid fa-check-double"></i> Valider la simulation
                    </a>
                <?php elseif (isset($simulationValide) && !$simulationValide): ?>
                    <button class="btn btn-secondary" style="border-radius: 8px; padding: 10px 24px;" disabled>
                        <i class="fa-solid fa-ban"></i> Validation impossible
                    </button>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>/achat/liste" class="btn-ghost">
                    <i class="fa-solid fa-list"></i> Voir la liste des achats
                </a>
            </div>

        </div>
    </div>

</main>

</div>
</body>

</html>