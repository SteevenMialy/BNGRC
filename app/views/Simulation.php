<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulation</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/bootstrap/css/bootstrap.min.css">
</head>

<body class="container mt-4">
    <h1>Simulation achat</h1>

    <?php if (isset($donDejaEnStock) && $donDejaEnStock): ?>
        <div class="alert alert-danger">
            <strong>Erreur :</strong> Ce don est encore disponible en stock. L'achat n'est pas nécessaire.
        </div>
    <?php endif; ?>

    <?php if (isset($argentInsuffisant) && $argentInsuffisant): ?>
        <div class="alert alert-danger">
            <strong>Erreur :</strong> Le prix calculé dépasse la quantité d'argent disponible en stock. Simulation impossible.
        </div>
    <?php endif; ?>

    <?php if (isset($prix, $stockreste, $argentreste, $ville, $don)) { ?>
        <table class="table table-bordered">
            <tr>
                <th>Ville</th>
                <th>Don</th>
                <th>Prix</th>
                <th>Qte stock restant</th>
                <th>Argent restant</th>
            </tr>
            <tr>
                <td><?= $ville->nomVille ?></td>
                <td><?= $don->libelle ?></td>
                <td><?= $prix ?></td>
                <td><?= ($stockreste === false) ? '<span class="text-danger">Stock insuffisant</span>' : $stockreste ?></td>
                <td><?= ($argentreste === false) ? '<span class="text-danger">Argent insuffisant</span>' : $argentreste ?></td>
            </tr>
        </table>
    <?php } ?>

    <?php if (isset($simulationValide) && $simulationValide && isset($idville, $iddon, $qte, $taux)): ?>
        <a href="<?= BASE_URL ?>/achat/valider/<?= $idville ?>/<?= $iddon ?>/<?= $qte ?>/<?= $taux ?>" class="btn btn-success">
            Valider la simulation
        </a>
    <?php elseif (isset($simulationValide) && !$simulationValide): ?>
        <button class="btn btn-secondary" disabled>Validation impossible</button>
    <?php endif; ?>

    <p class="mt-3">
        <a href="<?= BASE_URL ?>/achat/liste" class="btn btn-outline-primary">Voir la liste des achats</a>
    </p>
</body>
            
</html>