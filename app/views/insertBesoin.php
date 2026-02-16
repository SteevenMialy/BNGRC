<?php include 'includes/side-bar.php'; ?>

<style>
    /* On garde tes styles personnalisés pour le look moderne */
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
        /* S'assure que le champ prend toute la largeur de sa colonne */
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
                Accueil &rsaquo; <span>Nouveau Besoin</span>
            </div>
            <h1 class="topbar__title">Enregistrer un Besoin</h1>
        </div>
    </div>

    <div class="table-section">
        <div class="table-section__header">
            <div>
                <div class="table-section__title">Formulaire de demande</div>
                <div class="table-section__subtitle">Remplissez tous les champs requis</div>
            </div>
        </div>

        <div class="table-wrap" style="padding: 32px;">
            <form action="<?= BASE_URL ?>/besoin/insert" method="POST" id="besoinForm">

                <div class="row">

                    <div class="col-md-6 mb-4">
                        <label for="idVille" class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
                        <select class="form-select" id="idVille" name="idVille" required>
                            <option value="">Sélectionnez une ville</option>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?= $ville['id'] ?>"><?= $ville['nomVille'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <br>
                    <div class="col-md-6 mb-4">
                        <label for="idDon" class="form-label fw-semibold">Type de Don <span class="text-danger">*</span></label>
                        <select class="form-select" id="idDon" name="idDon" required>
                            <option value="">Sélectionnez un don</option>
                            <?php foreach ($dons as $don): ?>
                                <option value="<?= $don['id'] ?>"><?= $don['libelle'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <br>
                    <div class="col-md-6 mb-4">
                        <label for="qte" class="form-label fw-semibold">Quantité demandée <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" id="qte" name="qte" placeholder="Ex: 100" required>
                    </div>

                    <br>
                    <div class="col-md-6 mb-4">
                        <label for="daty" class="form-label fw-semibold">Date de demande <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="daty" name="daty" required>
                    </div>

                </div>
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-check"></i> Enregistrer
                    </button class="ml-3">
                    <a href="<?= BASE_URL ?>/" class="btn-ghost">
                        <i class="fa-solid fa-xmark"></i> Annuler
                    </a>
                </div>

            </form>
        </div>
    </div>

</main>

<script src="<?= BASE_URL ?>/assets/js/jquery-3.7.1.min.js"></script>
<script src="<?= BASE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
var form = document.getElementById('besoinForm');
form.addEventListener('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        body: formData
    }) .then(response => response.json())
    .then(data => {
        if(data.success) {
            window.location.href = data.redirection;
        } else {
            alert(data.error || 'Une erreur est survenue');
        }
    }) .catch(error => {
        console.error('Error:', error);
    });
});
</script>
</body>

</html>