<?php
$pageTitle = "Ricerca – ShopEsame";
require __DIR__."/partials/header.php";
require __DIR__."/../bootstrap.php";
require_once __DIR__ . '/../Product.php';

$q   = isset($_GET['q']) ? trim($_GET['q']) : '';
$cat = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
$results = Product::search($pdo, $q, $cat ?: null);

// STEP1 per immagini e stile rosa
$STEP1_BASE = "/progetto_esame/STEP1_HTML_CSS";

/* ID prodotto + pagina prodotto */
$productPages = [
  1 => "prodotto1.php",
  2 => "prodotto2.php",
  3 => "prodotto3.php",
  4 => "prodotto4.php",
  5 => "prodotto5.php",
  6 => "prodotto6.php",
  7 => "prodotto7.php",
  8 => "prodotto8.php",
];
?>
<h1 class="h4 mb-3">Ricerca prodotti</h1>

<form method="get" class="row g-2 mb-3">
  <div class="col-md-6">
    <input class="form-control" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cerca...">
  </div>
  <div class="col-md-3 d-grid">
    <button class="btn btn-pink">Cerca</button>
  </div>
</form>

<div class="row g-3">
  <?php foreach ($results as $p): ?>
    <div class="col-md-4">
      <div class="card card-product">
        <img
          src="<?= $STEP1_BASE . '/' . htmlspecialchars($p['immagine_url'] ?: 'assets/img/default.jpg') ?>"
          class="card-img-top"
          alt="<?= htmlspecialchars($p['nome']) ?>"
          loading="lazy" width="400" height="300">
        <div class="card-body">
          <h3 class="h6"><?= htmlspecialchars($p['nome']) ?></h3>
          <p class="text-muted">€ <?= number_format($p['prezzo'], 2, ',', '.') ?></p>
          <a class="btn btn-outline-pink btn-sm"
             href="<?= htmlspecialchars($productPages[(int)$p['id']] ?? '#') ?>">
             Dettagli
          </a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <?php if (empty($results)): ?>
    <p>Nessun prodotto trovato.</p>
  <?php endif; ?>
</div>

<?php require __DIR__."/partials/footer.php"; ?>
