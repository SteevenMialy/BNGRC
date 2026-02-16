<?php include 'includes/side-bar.php'; ?>

<!-- ════════════ MAIN ════════════ -->
<main class="main">

  <!-- Topbar -->
  <div class="topbar">
    <div class="topbar__left">
      <div class="topbar__breadcrumb">
        Accueil &rsaquo; <span>Besoins</span>
      </div>
      <h1 class="topbar__title">Liste des Besoins</h1>
    </div>
    <div class="topbar__actions">
      <button class="btn-ghost">
        <i class="fa-solid fa-magnifying-glass"></i>
        Rechercher
      </button>
      <button id="delivrer" class="btn-primary">
        <i class="fa-solid fa-paper-plane"></i>
        Distribuer les dons
      </button>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-card stat-card--emerald">
      <div class="stat-card__icon">
        <i class="fa-solid fa-chart-line"></i>
      </div>
      <div class="stat-card__label">Total besoins</div>
      <div class="stat-card__value"><?= $counts['total'] ?></div>
      <div class="stat-card__sub">+3 cette semaine</div>
    </div>
    <div class="stat-card stat-card--sky">
      <div class="stat-card__icon">
        <i class="fa-solid fa-circle-check"></i>
      </div>
      <div class="stat-card__label">Satisfaits</div>
      <div class="stat-card__value"><?= $counts['satisfaits'] ?></div>
      <div class="stat-card__sub"><?= $counts['total'] > 0 ? round(($counts['satisfaits'] / $counts['total']) * 100, 1) : 0 ?>% du total</div>
    </div>
    <div class="stat-card stat-card--amber">
      <div class="stat-card__icon">
        <i class="fa-solid fa-circle-exclamation"></i>
      </div>
      <div class="stat-card__label">En attente</div>
      <div class="stat-card__value"><?= $counts['non_satisfaits'] ?></div>
      <div class="stat-card__sub">Nécessitent action</div>
    </div>
  </div>

  <!-- Table section -->
  <div class="table-section">
      <div class="table-section__header">
        <div>
          <div class="table-section__title">Demandes enregistrées</div>
          <div class="table-section__subtitle">5 besoins affichés · Mis à jour aujourd'hui</div>
        </div>
        <div class="filter-tabs">
          <button class="filter-tab active">Tous</button>
          <a href="<?= BASE_URL ?>/listBesoin/satisfaits" class="filter-tab">Satisfaits</a>
        <a href="<?= BASE_URL ?>/listBesoin/nonSatisfaits" class="filter-tab">Non satisfaits</a>
      </div>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#id</th>
            <th>Ville</th>
            <th>Région</th>
            <th>Don</th>
            <th>Qté demandée</th>
            <th>Date de demande</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($besoins as $besoin): ?>
            <tr>
              <td><?= $besoin->id ?></td>
              <td class="cell-ville"><?= $besoin->ville->nomVille ?></td>
              <td class="cell-region"><?= $besoin->ville->region->nom ?></td>
              <td><span class="badge badge--alimentaire"><?= $besoin->don->libelle ?></span></td>
              <td class="cell-qty"><?= $besoin->qte ?> unités</td>
              <td class="cell-date"><?= $besoin->daty ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div>
  </div>

</main>
</div>

<!-- Toast -->
<div class="toast-wrap">
  <div class="toast-item" id="toast">
    <div class="toast-item__icon">
      <i class="fa-solid fa-check"></i>
    </div>
    <div class="toast-item__text">
      <div class="toast-item__title">Distribution lancée !</div>
      <div class="toast-item__sub">24 bénéficiaires notifiés.</div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="<?= BASE_URL ?>/assets/js/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5.3 JS -->
<script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
<script> const BASE_URL = "<?= BASE_URL ?>"; </script>
<script src="<?= BASE_URL ?>/js/listBesoin.js"></script>

</body>

</html>