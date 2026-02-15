<?php include 'includes/header.php'; ?>

<body>

    <div class="container-fluid bg-secondary my-5">
        <div class="row justify-content-center py-5">
            <div class="col-md-8">
                <div class="card shadow p-4">

                    <h3 class="text-center mb-4">Liste des Objets de l'utilisateur
                        <?= isset($_SESSION['user']->nom_user) ? htmlspecialchars($_SESSION['user']->nom_user) : '' ?>
                    </h3>
                    <a href="<?= BASE_URL ?>/ajoutobject" class="btn btn-primary btn-block">Ajouter object</a>

                    <?php if (isset($objects) && !empty($objects)) { ?>
                        <ul class="list-group mt-3">
                            <?php foreach ($objects as $item): ?>
                                <?php
                                $firstPic = (isset($item->pictures[0]) ? ($item->pictures[0])->getPathImg() : null);
                                ?>
                                <li class="list-group-item d-flex align-items-center" id="det<?= $item->id ?>">
                                    <?php if ($firstPic): ?>
                                        <img src="uploads/object/<?= htmlspecialchars($firstPic) ?>" alt=""
                                            class="img-thumbnail me-3" style="width:80px; height:80px; object-fit:cover;">
                                    <?php else: ?>
                                        <div class="img-thumbnail me-3 d-flex align-items-center justify-content-center bg-light"
                                            style="width:80px; height:80px;">Aucune</div>
                                    <?php endif; ?>
                                    <div class="flex-grow-1 ml-4">
                                        <?= htmlspecialchars($item->getObjName() ?? ''); ?>
                                    </div>
                                    <a href="<?= BASE_URL ?>/intervalle/10/<?= $item->id ?>" class="btn btn-outline-secondary btn-sm me-2">+/-10%</a>
                                    <a href="<?= BASE_URL ?>/intervalle/20/<?= $item->id ?>" class="btn btn-outline-secondary btn-sm">+/-20%</a>
                                    <a href="<?= BASE_URL ?>/object/<?= $item->id ?>" class="btn btn-primary btn-sm">Voir
                                        détails</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php } else { ?>
                        <p class="text-center mt-3">Vous n'avez aucun objet disponible.</p>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>


    <?php include 'includes/footer.php'; ?>

</body>

</html>