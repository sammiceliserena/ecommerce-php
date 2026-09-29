<?php
// public/carrello.php
require_once __DIR__ . '/../config.php';
$pageTitle = "Carrello – SereCosmetics❀";

// NAVBAR + CSS rosa 
require __DIR__ . '/partials/header.php';

$user_id = $_SESSION['user_id'] ?? null;

/** Se ora sei loggato e hai articoli nel carrello di SESSIONE,
 *  migrali nel DB e poi svuota la sessione. */
if ($user_id && !empty($_SESSION['carrello']) && is_array($_SESSION['carrello'])) {
    foreach ($_SESSION['carrello'] as $prodId => $q) {
        $prodId = (int)$prodId;
        $q = max(1, (int)$q);
        if ($prodId <= 0) continue;

        $sel = $pdo->prepare("SELECT id FROM Carrello WHERE utente_id = :u AND prodotto_id = :p");
        $sel->execute([':u' => (int)$user_id, ':p' => $prodId]);
        $existing = $sel->fetch();

        if ($existing) {
            $upd = $pdo->prepare("UPDATE Carrello SET quantita = quantita + :q WHERE id = :id AND utente_id = :u");
            $upd->execute([':q' => $q, ':id' => $existing['id'], ':u' => (int)$user_id]);
        } else {
            $ins = $pdo->prepare("INSERT INTO Carrello (utente_id, prodotto_id, quantita) VALUES (:u, :p, :q)");
            $ins->execute([':u' => (int)$user_id, ':p' => $prodId, ':q' => $q]);
        }
    }
    unset($_SESSION['carrello']);
}

// Gestione azioni POST (update qty / remove)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($user_id) {
        if ($action === 'update') {
            $cart_id = (int)($_POST['cart_id'] ?? 0);
            $qty     = max(1, min((int)($_POST['qty'] ?? 1), 99));
            $stmt = $pdo->prepare("UPDATE Carrello SET quantita = :q WHERE id = :id AND utente_id = :u");
            $stmt->execute([':q' => $qty, ':id' => $cart_id, ':u' => $user_id]);
        }
        if ($action === 'remove') {
            $cart_id = (int)($_POST['cart_id'] ?? 0);
            $stmt = $pdo->prepare("DELETE FROM Carrello WHERE id = :id AND utente_id = :u");
            $stmt->execute([':id' => $cart_id, ':u' => $user_id]);
        }
    } else {
        if (!isset($_SESSION['carrello'])) {
            $_SESSION['carrello'] = [];
        }
        if ($action === 'update') {
            $prod_id = (int)($_POST['prodotto_id'] ?? 0);
            $qty     = max(1, min((int)($_POST['qty'] ?? 1), 99));
            if ($prod_id > 0 && isset($_SESSION['carrello'][$prod_id])) {
                $_SESSION['carrello'][$prod_id] = $qty;
            }
        }
        if ($action === 'remove') {
            $prod_id = (int)($_POST['prodotto_id'] ?? 0);
            if ($prod_id > 0 && isset($_SESSION['carrello'][$prod_id])) {
                unset($_SESSION['carrello'][$prod_id]);
            }
        }
    }

    header('Location: /progetto_esame/STEP3_PHP_MVC/public/carrello.php');
    exit;
}

// Lettura articoli carrello
$items = [];
$totale = 0.00;

if ($user_id) {
    // LOGGATO: carrello da DB
    $sql = "SELECT 
              c.id            AS cart_id,
              p.id            AS prodotto_id,
              p.nome          AS nome,
              p.prezzo        AS prezzo,
              p.immagine_url  AS immagine,
              c.quantita      AS quantita
            FROM Carrello c
            JOIN Prodotto p ON p.id = c.prodotto_id
            WHERE c.utente_id = :u
            ORDER BY c.id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u' => $user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // OSPITE: carrello da SESSIONE
    $sessionCart = $_SESSION['carrello'] ?? [];
    $ids = array_keys($sessionCart);
    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT id AS prodotto_id, nome, prezzo, immagine_url AS immagine
                FROM Prodotto
                WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);
        $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($prods as $p) {
            $q = (int)($sessionCart[$p['prodotto_id']] ?? 0);
            if ($q > 0) {
                $items[] = [
                    'cart_id'     => null,
                    'prodotto_id' => $p['prodotto_id'],
                    'nome'        => $p['nome'],
                    'prezzo'      => $p['prezzo'],
                    'immagine'    => $p['immagine'],
                    'quantita'    => $q,
                ];
            }
        }
    }
}

// Calcolo totale
foreach ($items as $it) {
    $totale += (float)$it['prezzo'] * (int)$it['quantita'];
}

// Base assets STEP1 per immagini
$STEP1_BASE = "/progetto_esame/STEP1_HTML_CSS";
?>

<h1 class="h3 mb-4">Carrello</h1>

<?php if (!empty($_SESSION['flash_success'])): ?>
  <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div>
<?php endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
<?php endif; ?>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <?php if (count($items) > 0): ?>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Prodotto</th>
                  <th style="width:160px">Q.tà</th>
                  <th class="text-end">Prezzo</th>
                  <th class="text-end">Totale</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($items as $it): 
                $riga = (float)$it['prezzo'] * (int)$it['quantita'];
              ?>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <?php if (!empty($it['immagine'])): ?>
                        <img src="<?= $STEP1_BASE . '/' . htmlspecialchars($it['immagine']) ?>" alt=""
                             class="rounded me-2" style="width:64px;height:48px;object-fit:cover">
                      <?php endif; ?>
                      <div>
                        <div class="fw-semibold"><?= htmlspecialchars($it['nome']) ?></div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <form action="/progetto_esame/STEP3_PHP_MVC/public/carrello.php" method="post" class="d-flex align-items-center gap-1">
                      <input type="hidden" name="action" value="update">
                      <?php if ($user_id): ?>
                        <input type="hidden" name="cart_id" value="<?= (int)$it['cart_id']; ?>">
                      <?php else: ?>
                        <input type="hidden" name="prodotto_id" value="<?= (int)$it['prodotto_id']; ?>">
                      <?php endif; ?>

                      <!-- Bottone - -->
                      <button type="button" class="btn btn-outline-secondary btn-sm"
                              onclick="let input=this.nextElementSibling; input.stepDown(); input.dispatchEvent(new Event('change'))">−</button>

                      <!-- Input quantità -->
                      <input type="number" name="qty" min="1" max="99" value="<?= (int)$it['quantita']; ?>"
                             class="form-control text-center" style="max-width:60px"
                             onchange="this.form.submit()">

                      <!-- Bottone + -->
                      <button type="button" class="btn btn-outline-secondary btn-sm"
                              onclick="let input=this.previousElementSibling; input.stepUp(); input.dispatchEvent(new Event('change'))">+</button>
                    </form>
                  </td>
                  <td class="text-end">€ <?= number_format((float)$it['prezzo'], 2, ',', '.') ?></td>
                  <td class="text-end">€ <?= number_format($riga, 2, ',', '.') ?></td>
                  <td class="text-end">
                    <form action="/progetto_esame/STEP3_PHP_MVC/public/carrello.php" method="post" onsubmit="return confirm('Rimuovere questo articolo?');">
                      <input type="hidden" name="action" value="remove">
                      <?php if ($user_id): ?>
                        <input type="hidden" name="cart_id" value="<?= (int)$it['cart_id'] ?>">
                      <?php else: ?>
                        <input type="hidden" name="prodotto_id" value="<?= (int)$it['prodotto_id'] ?>">
                      <?php endif; ?>
                      <button class="btn btn-outline-danger btn-sm" type="submit">🗑️ Rimuovi</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="mb-0">Il carrello è vuoto.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Riepilogo a destra -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <h2 class="h6 mb-3">Totale provvisorio</h2>
        <div class="d-flex justify-content-between mb-3">
          <span>Subtotale</span>
          <strong>€ <?= number_format($totale, 2, ',', '.') ?></strong>
        </div>

        <?php if (!$user_id): ?>
          <a href="/progetto_esame/STEP3_PHP_MVC/public/login.php?redirect=/progetto_esame/STEP3_PHP_MVC/public/riepilogo-ordine.php"
             class="btn btn-pink w-100" title="Effettua il login per procedere">
            Procedi al riepilogo
          </a>
          <small class="text-muted d-block mt-2">
            Oppure <a href="/progetto_esame/STEP3_PHP_MVC/public/iscrizione.php?redirect=/progetto_esame/STEP3_PHP_MVC/public/riepilogo-ordine.php">registrati</a> per continuare.
          </small>
        <?php elseif ($user_id && count($items) === 0): ?>
          <button class="btn btn-secondary w-100" disabled>Procedi al riepilogo</button>
        <?php else: ?>
          <a href="/progetto_esame/STEP3_PHP_MVC/public/riepilogo-ordine.php" class="btn btn-pink w-100">Procedi al riepilogo</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php
// FOOTER 
require __DIR__ . '/partials/footer.php';
