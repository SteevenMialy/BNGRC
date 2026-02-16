<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>List besoins</h1>

    <ul>
        <li><a href="<?= BASE_URL ?>/liste/satisfait">Satisfaits</a></li>
        <li><a href="<?= BASE_URL ?>/liste/non-satisfait">Non satisfaits</a></li>
    </ul>
<br>
    <button type="button" id="delivrer">Distribuer les dons</button>
    <br><br>
    <table>
        <tr>
           <th>#id</th> 
           <th>Ville</th>
           <th>Région</th>
           <th>Type</th>
           <th>Quantite demandée</th>
            <th>Date de demande</th>
        </tr>
        <?php foreach ($besoins as $besoin): ?>
            <tr>
                <td><?= $besoin->id_besoin ?></td>
                <td><?= $besoin->ville->nom_ville ?></td>
                <td><?= $besoin->ville->region->nom_region ?></td>
                <td><?= $besoin->types_besoin->nom_types ?></td>
                <td><?= $besoin->quantite_demandee ?></td>
                <td><?= $besoin->date_demande ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

<script>const BASE_URL = "<?= BASE_URL ?>";</script>
<script src="<?= BASE_URL ?>/assets/js/listBesoin.js"></script>
</body>

</html>