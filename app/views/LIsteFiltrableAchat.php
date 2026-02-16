<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste Filtrable Achat</title>
</head>
<body>
    <h1>Liste des achats</h1>

    <?php if (isset($validationOk) && $validationOk === false) { ?>
        <p style="color: red;">La validation de l'achat a échoué.</p>
    <?php } elseif (isset($validationOk) && $validationOk === true && isset($distributionOk) && $distributionOk === false) { ?>
        <p style="color: orange;">Achat validé, mais distribution non effectuée.</p>
    <?php } elseif (isset($validationOk) && $validationOk === true && isset($distributionOk) && $distributionOk === true) { ?>
        <p style="color: green;">Achat validé puis distribué avec succès.</p>
    <?php } ?>

    <form method="get" id="filterVilleForm">
        <label for="idville">Filtrer par ville :</label>
        <select name="idville" id="idville">
            <option value="">Toutes les villes</option>
            <?php if (!empty($villes)) {
                foreach ($villes as $v) { ?>
                    <option value="<?= $v['id'] ?>" <?= (!empty($selectedVille) && (int)$selectedVille === (int)$v['id']) ? 'selected' : '' ?>>
                        <?= $v['nomVille'] ?>
                    </option>
                <?php }
            } ?>
        </select>
        <button type="submit">Filtrer</button>
    </form>

    <?php if (!empty($ville)) { ?>
        <p><strong>Ville sélectionnée :</strong> <?= $ville->nomVille ?></p>
    <?php } ?>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>ID Achat</th>
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
                        <td><?= $a['id'] ?></td>
                        <td><?= $a['nomVille'] ?? '-' ?></td>
                        <td><?= $a['libelle'] ?? '-' ?></td>
                        <td><?= $a['taux'] ?></td>
                        <td><?= $a['quantite'] ?></td>
                        <td><?= $a['date_achat'] ?></td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6">Aucun achat trouvé.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <p><a href="<?= BASE_URL ?>/achat">Retour à l'écran achat</a></p>

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