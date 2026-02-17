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

    .invalide {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1) !important;
    }
</style>

<main class="main">

    <div class="topbar">
        <div class="topbar__left">
            <div class="topbar__breadcrumb">
                Accueil &rsaquo; <span>Don</span>
            </div>
            <h1 class="topbar__title">DON</h1>
        </div>
    </div>

    <div class="table-section">
        <div class="table-section__header">
            <div>
                <div class="table-section__title">Nouveau don</div>
                <div class="table-section__subtitle">Enregistrer un don</div>
            </div>
        </div>

        <div class="table-wrap" style="padding: 32px;">
            <?php if (!empty($types)) { ?>
                <form action="<?= BASE_URL ?>/don/insert" id="formSimulation" method="post">
                    <div class="row">

                        <div class="col-md-6 mb-4">
                            <label for="libelle" class="form-label fw-semibold">Libelle <span class="text-danger">*</span></label>
                            <input type="text" name="libelle" id="libelle" class="form-control" placeholder="Libelle du don" required>
                            <p class="text-danger d-none" id="error-libelle"></p>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="type" class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" id="type" required>
                                <option value="">Sélectionnez le type</option>
                                <?php foreach ($types as $t) { ?>
                                    <option value="<?= $t['id'] ?>"><?= $t['nom'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="pu" class="form-label fw-semibold">Prix unitaire <span class="text-danger">*</span></label>
                            <input type="number" name="pu" id="pu" class="form-control" placeholder="Prix unitaire" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="daty" class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
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
            <?php } ?>
        </div>
    </div>

</main>

<script src="<?= BASE_URL ?>/assets/js/jquery-3.7.1.min.js"></script>
<script src="<?= BASE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>const BASE_URL = "<?= BASE_URL ?>";</script>
<script>
    
    document.getElementById("formSimulation").addEventListener("submit", function(e) {
        e.preventDefault(); // empêche l'envoi normal

        var error = document.getElementById("error-libelle");
        var inputLibelle = document.getElementById("libelle");
        var formData = new FormData(this);
        fetch(BASE_URL + "/don/checkInsert", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.succes){
                window.location.href = data.redirection;
            } else {
                error.classList.remove("d-none");
                error.textContent = data.error;
                inputLibelle.classList.add("is-invalid");
                inputLibelle.classList.add("invalide");
            }
        })
        .catch(error => {
            console.error("Erreur lors de l'enregistrement du don:", error);
            alert("Erreur lors de l'enregistrement du don");
        });
    });
</script>

</body>

</html>