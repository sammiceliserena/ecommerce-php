<?php
// public/index.php
require_once __DIR__ . '/../config.php';
require __DIR__ . '/partials/header.php';

// id prodotto + pagina PHP del prodotto (STEP3)
$productPages = [
  1 => "/progetto_esame/STEP3_PHP_MVC/public/prodotto1.php",
  2 => "/progetto_esame/STEP3_PHP_MVC/public/prodotto2.php",
  3 => "/progetto_esame/STEP3_PHP_MVC/public/prodotto3.php",
  4 => "/progetto_esame/STEP3_PHP_MVC/public/prodotto4.php",
  5 => "/progetto_esame/STEP3_PHP_MVC/public/prodotto5.php",
  6 => "/progetto_esame/STEP3_PHP_MVC/public/prodotto6.php",
  7 => "/progetto_esame/STEP3_PHP_MVC/public/prodotto7.php",
  8 => "/progetto_esame/STEP3_PHP_MVC/public/prodotto8.php",
];

// Ordine identico a index.html
$newOrder = [
  'assets/img/sere1.jpg', // Lip Gloss
  'assets/img/sere2.jpg', // Mascara
  'assets/img/sere3.jpg', // Fondotinta
  'assets/img/sere4.jpg', // Blush liquido
];
$bestOrder = [
  'assets/img/sere5.jpg', // Matita sopracciglia
  'assets/img/sere6.jpg', // Gel sopracciglia
  'assets/img/sere7.jpg', // Palette occhi
  'assets/img/sere8.jpg', // Illuminante
];

// Query “New Collection”
$phNew = implode(',', array_fill(0, count($newOrder), '?'));
$sqlNew = "
  SELECT id, nome, prezzo, immagine_url
  FROM Prodotto
  WHERE attivo = 1 AND immagine_url IN ($phNew)
  ORDER BY FIELD(immagine_url, " . implode(',', array_fill(0, count($newOrder), '?')) . ")
";
$stmtNew = $pdo->prepare($sqlNew);
$stmtNew->execute(array_merge($newOrder, $newOrder));
$newProducts = $stmtNew->fetchAll(PDO::FETCH_ASSOC);

// Query “I più venduti”
$phBest = implode(',', array_fill(0, count($bestOrder), '?'));
$sqlBest = "
  SELECT id, nome, prezzo, immagine_url
  FROM Prodotto
  WHERE attivo = 1 AND immagine_url IN ($phBest)
  ORDER BY FIELD(immagine_url, " . implode(',', array_fill(0, count($bestOrder), '?')) . ")
";
$stmtBest = $pdo->prepare($sqlBest);
$stmtBest->execute(array_merge($bestOrder, $bestOrder));
$bestProducts = $stmtBest->fetchAll(PDO::FETCH_ASSOC);

// Base assets STEP1 per immagini/CSS rosa
$STEP1_BASE = "/progetto_esame/STEP1_HTML_CSS";
?>


<section class="hero text-center">
  <div class="container">
    <h1 class="display-5">SereCosmetics❀</h1>
    <p class="lead">Make-up - Bellezza</p>

    <!-- Barra di ricerca -->
    <form action="ricerca.php" method="get" class="row justify-content-center g-2 mt-3">
      <div class="col-md-6">
        <input type="text" name="q" class="form-control" placeholder="Cerca un prodotto..." required>
      </div>
      <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-pink">Cerca</button>
      </div>
    </form>
  </div>
</section>

<!-- Sezione New Collection -->
<section class="container my-5">
  <h2 class="h4 mb-3">New Collection</h2>
  <div class="row g-3">
    <?php foreach ($newProducts as $p): ?>
      <?php $link = $productPages[$p['id']] ?? '#'; ?>
      <div class="col-md-3">
        <div class="card card-product">
          <img
            src="<?= $STEP1_BASE . '/' . htmlspecialchars($p['immagine_url']) ?>"
            class="card-img-top"
            alt="<?= htmlspecialchars($p['nome']) ?>"
            loading="lazy" width="400" height="300">
          <div class="card-body">
            <h3 class="h6"><?= htmlspecialchars($p['nome']) ?></h3>
            <p class="text-muted">€ <?= number_format((float)$p['prezzo'], 2, ',', '.') ?></p>
            <a class="btn btn-outline-pink btn-sm" href="<?= htmlspecialchars($link) ?>">Dettagli</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Sezione I più venduti -->
<section class="container my-5">
  <h2 class="h4 mb-3">I più venduti</h2>
  <div class="row g-3">
    <?php foreach ($bestProducts as $p): ?>
      <?php $link = $productPages[$p['id']] ?? '#'; ?>
      <div class="col-md-3">
        <div class="card card-product">
          <img
            src="<?= $STEP1_BASE . '/' . htmlspecialchars($p['immagine_url']) ?>"
            class="card-img-top"
            alt="<?= htmlspecialchars($p['nome']) ?>"
            loading="lazy" width="400" height="300">
          <div class="card-body">
            <h3 class="h6"><?= htmlspecialchars($p['nome']) ?></h3>
            <p class="text-muted">€ <?= number_format((float)$p['prezzo'], 2, ',', '.') ?></p>
            <a class="btn btn-outline-pink btn-sm" href="<?= htmlspecialchars($link) ?>">Dettagli</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
