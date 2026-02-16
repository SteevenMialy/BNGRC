<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achat</title>
</head>

<body>
    <h1>ACHAT</h1>
    <?php if (!empty($dons) && !empty($ville)) { ?>
        <form id="formSimulation" method="post">
            <p>
                <select name="ville" id="ville">
                    <option value="">Sélectionnez une ville</option>
                    <?php foreach ($ville as $v) { ?>
                        <option value="<?= $v['id'] ?>"><?= $v['nomVille'] ?></option>
                    <?php } ?>
                </select>
            </p>
            <p>
                <select name="Don" id="Don">
                    <option value="">Sélectionnez un don</option>
                    <?php foreach ($dons as $d) { ?>
                        <option value="<?= $d['id'] ?>"><?= $d['libelle'] ?></option>
                    <?php } ?>
                </select>
            </p>
            <p><input type="number" name="qte" id="qte" placeholder="Quantité"></p>
            <p><input type="number" name="taux" id="taux" placeholder="Taux"></p>
            <p><input type="submit" value="simuler"></p>
        </form>
    <?php } ?>

    <script>
        document.getElementById("formSimulation").addEventListener("submit", function (e) {
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