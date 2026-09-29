<?php
// public/cronologia-ordini.php
require_once __DIR__ . '/../config.php';
$pageTitle = "Cronologia Ordini – SereCosmetics❀";
require __DIR__ . '/partials/header.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
  // non loggata: chiedi login o registrazione
  $self = '/progetto_esame/STEP3_PHP_MVC/public/cronologia-ordini.php';
  ?>
  <div class="alert alert-warning">
    Devi effettuare l’accesso per vedere i tuoi ordini.
  </div>
  <div class="d-flex gap-2">
    <a class="btn btn-pink" href="/progetto_esame/STEP3_PHP_MVC/public/login.php?redirect=<?= urlencode($self) ?>">Vai al Login</a>
    <a class="btn btn-outline-pink" href="/progetto_esame/STEP3_PHP_MVC/public/iscrizione.php?redirect=<?= urlencode($self) ?>">Registrati</a>
  </div>
  <?php
  require __DIR__ . '/partials/footer.php';
  exit;
}

// utente loggato: carica ordini
$stmt = $pdo->prepare("
  SELECT id, totale, stato, creato_il
  FROM Ordine
  WHERE utente_id = :u
  ORDER BY creato_il DESC, id DESC
");
$stmt->execute([':u' => (int)$user_id]);
$ordini = $stmt->fetchAll(PDO::FETCH_ASSOC);

// per caricare gli item di un ordine
$getItems = $pdo->prepare("
  SELECT oi.quantita, oi.prezzo_unitario, p.nome, p.immagine_url
  FROM OrdineItem oi
  JOIN Prodotto p ON p.id = oi.prodotto_id
  WHERE oi.ordine_id = :o
  ORDER BY oi.id ASC
");

// per immagini di STEP1
$STEP1_BASE = "/progetto_esame/STEP1_HTML_CSS";
?>
<h1 class="h4 mb-3">Cronologia ordini</h1>

<?php if (empty($ordini)): ?>
  <div class="alert alert-secondary">Nessun ordine presente.</div>
<?php else: ?>
  <div class="list-group">
    <?php foreach ($ordini as $o): ?>
      <?php
        $getItems->execute([':o' => (int)$o['id']]);
        $righe = $getItems->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <div class="list-group-item">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <strong>Ordine #<?= (int)$o['id'] ?></strong>
            <div class="text-muted small">
              <?= htmlspecialchars($o['creato_il']) ?> — Stato: <?= htmlspecialchars($o['stato']) ?>
            </div>
          </div>
          <div class="ms-3">
            <span class="fw-semibold">€ <?= number_format((float)$o['totale'], 2, ',', '.') ?></span>
          </div>
        </div>

        <?php if (!empty($righe)): ?>
          <ul class="mt-3 mb-0 list-unstyled">
            <?php foreach ($righe as $r): ?>
              <li class="d-flex align-items-center mb-2">
                <?php if (!empty($r['immagine_url'])): ?>
                  <img
                    src="<?= $STEP1_BASE . '/' . htmlspecialchars($r['immagine_url']) ?>"
                    alt=""
                    class="rounded me-2"
                    style="width:48px;height:36px;object-fit:cover">
                <?php endif; ?>
                <div class="flex-grow-1">
                  <?= htmlspecialchars($r['nome']) ?>
                  × <?= (int)$r['quantita'] ?>
                </div>
                <div class="text-end" style="min-width:110px">
                  € <?= number_format(((float)$r['prezzo_unitario'] * (int)$r['quantita']), 2, ',', '.') ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
