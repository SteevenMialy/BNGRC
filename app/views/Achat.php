<?php include 'includes/side-bar.php'; ?>

<style>
    .form-label {
        font-size: 0.9rem;
        color: #495057;
        margin-bottom: 8px;
        display: block;
    }

    .form-control,
    .form-select {
        border: 1px solid #d1d5db !important;
        border-radius: 8px !important;
        padding: 10px 14px !important;
        width: 100%;
        transition: all 0.2s ease;
        margin-bottom: 10px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #00ab66 !important;
        box-shadow: 0 0 0 4px rgba(0, 171, 102, 0.1) !important;
        outline: none;
    }

    .btn-primary {
        background-color: #00ab66 !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 10px 24px !important;
        font-weight: 600 !important;
        color: white !important;
        cursor: pointer;
    }

    .btn-ghost {
        background-color: #f3f4f6 !important;
        color: #4b5563 !important;
        border-radius: 8px !important;
        padding: 10px 24px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
    }
</style>

<main class="main">

    <div class="topbar">
        <div class="topbar__left">
            <div class="topbar__breadcrumb">
                Accueil &rsaquo; <span>Achat</span>
            </div>
            <h1 class="topbar__title">ACHAT</h1>
        </div>
    </div>

    <div class="table-section">
        <div class="table-section__header">
            <div>
                <div class="table-section__title">Simulation d'Achat</div>
                <div class="table-section__subtitle">Entrez les paramètres de simulation</div>
            </div>
        </div>

        <div class="table-wrap" style="padding: 32px;">
            <?php if (!empty($dons) && !empty($ville)) { ?>
                <form id="formSimulation" method="post">
                    <div class="row">

                        <div class="col-md-6 mb-4">
                            <label for="ville" class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
                            <select class="form-select" name="ville" id="ville" required>
                                <option value="">Sélectionnez une ville</option>
                                <?php foreach ($ville as $v) { ?>
                                    <option value="<?= $v['id'] ?>"><?= $v['nomVille'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="Don" class="form-label fw-semibold">Don <span class="text-danger">*</span></label>
                            <select class="form-select" name="Don" id="Don" required>
                                <option value="">Sélectionnez un don</option>
                                <?php foreach ($dons as $d) { ?>
                                    <option value="<?= $d['id'] ?>"><?= $d['libelle'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="qte" class="form-label fw-semibold">Quantité <span class="text-danger">*</span></label>
                            <input type="number" name="qte" id="qte" class="form-control" placeholder="Quantité" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="taux" class="form-label fw-semibold">Taux <span class="text-danger">*</span></label>
                            <input type="number" name="taux" id="taux" class="form-control" placeholder="Taux" required>
                        </div>

                    </div>

                    <div class="d-flex gap-3 mt-2">
                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-calculator"></i> Simuler
                        </button>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>

</main>

<script src="<?= BASE_URL ?>/assets/js/jquery-3.7.1.min.js"></script>
<script src="<?= BASE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById("formSimulation").addEventListener("submit", function(e) {
        e.preventDefault(); // empêche l'envoi normal

        let idDon = document.getElementById("Don").value;
        let qte = document.getElementById("qte").value;
        let taux = document.getElementById("taux").value;
        let idVille = document.getElementById("ville").value;

        if (idDon === "" || qte === "" || taux === "" || idVille === "") {
            alert("Veuillez remplir tous les champs");
            return;
        }

        let url = "<?= BASE_URL ?>/simulation/" + idVille + "/" + idDon + "/" + qte + "/" + taux;

        window.location.href = url;
    });
</script>

</body>

</html>