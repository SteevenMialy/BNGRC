<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulation</title>
</head>

<body>
    <h1>simulation achat</h1>
    <?php if (!empty($prix) && !empty($stockreste) && !empty($argentreste) && !empty($ville) && !empty($don)) { ?>
        <table>
            <tr>
                <th>ville</th>
                <th>don</th>
                <th>prix</th>
                <th>qte stock</th>
                <th>argent restant</th>
            </tr>
            <tr>
                <td><?= $ville['nomVille'] ?></td>
                <td><?= $don['libelle'] ?></td>
                <td><?= $prix ?></td>
                <td><?= $stockreste ?></td>
                <td><?= $argentreste ?></td>
            </tr>
        </table>
    <?php } ?>
</body>

</html>