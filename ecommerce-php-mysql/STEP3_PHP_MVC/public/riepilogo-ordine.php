<?php
// public/riepilogo-ordine.php
$pageTitle = "Riepilogo Ordine – ShopEsame";
require __DIR__ . "/partials/header.php";
require __DIR__ . "/../bootstrap.php";

$user_id = $_SESSION['user_id'] ?? null;

// Carica items del carrello: se loggato dal DB, altrimenti dalla sessione
$items = [];
$totale = 0.00;

if ($user_id) {
  // Carrello da DB
  $sql = "SELECT 
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
  // Carrello da sessione 
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

// Calcola totale
foreach ($items as $it) {
  $totale += (float)$it['prezzo'] * (int)$it['quantita'];
}
?>

<h1 class="h4 mb-4">Riepilogo Ordine</h1>

<?php if (count($items) === 0): ?>
  <div class="alert alert-secondary">Il carrello è vuoto.</div>
  <a class="btn btn-outline-pink btn-sm" href="/progetto_esame/STEP3_PHP_MVC/public/index.php">Torna allo shop</a>
  <?php require __DIR__ . "/partials/footer.php"; exit; ?>
<?php endif; ?>

<div class="row g-4">
  <!-- SINISTRA: elenco prodotti -->
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive mb-0">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Prodotto</th>
                <th class="text-center" style="width:120px;">Q.tà</th>
                <th class="text-end" style="width:140px;">Prezzo</th>
                <th class="text-end" style="width:160px;">Totale riga</th>
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
                        <!-- immagini da STEP1_HTML_CSS -->
                        <img src="/progetto_esame/STEP1_HTML_CSS/<?php echo htmlspecialchars($it['immagine']); ?>" alt=""
                             class="rounded me-2" style="width:64px;height:48px;object-fit:cover">
                      <?php endif; ?>
                      <div class="fw-semibold"><?php echo htmlspecialchars($it['nome']); ?></div>
                    </div>
                  </td>
                  <td class="text-center"><?php echo (int)$it['quantita']; ?></td>
                  <td class="text-end">€ <?php echo number_format((float)$it['prezzo'], 2, ',', '.'); ?></td>
                  <td class="text-end">€ <?php echo number_format($riga, 2, ',', '.'); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3" class="text-end">Totale</th>
                <th class="text-end">€ <?php echo number_format($totale, 2, ',', '.'); ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- DESTRA: indirizzo + pagamento -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <h2 class="h6 mb-3">Dettagli spedizione & pagamento</h2>

        <?php if ($user_id): ?>
          <?php
          // Dati utente base
          $stmtU = $pdo->prepare("SELECT nome, cognome, email FROM Utenti WHERE id = :id LIMIT 1");
          $stmtU->execute([':id' => $user_id]);
          $utente = $stmtU->fetch(PDO::FETCH_ASSOC);
          ?>

          <form method="post" action="/progetto_esame/STEP3_PHP_MVC/public/avvenuto-acquisto.php" class="vstack gap-3">
            <?php if ($utente): ?>
              <div>
                <label class="form-label">Nome e cognome</label>
                <input type="text" name="full_name" class="form-control"
                       value="<?= htmlspecialchars($utente['nome'] . ' ' . $utente['cognome']) ?>" required>
              </div>
              <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($utente['email']) ?>" required>
              </div>
            <?php else: ?>
              <div class="alert alert-info">Completa i dati di contatto.</div>
              <div>
                <label class="form-label">Nome e cognome</label>
                <input type="text" name="full_name" class="form-control" required>
              </div>
              <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
            <?php endif; ?>

            <div>
              <label class="form-label">Indirizzo</label>
              <input type="text" name="address" class="form-control" placeholder="Via e numero civico" required>
            </div>
            <div class="row g-2">
              <div class="col-8">
                <label class="form-label">Città</label>
                <input type="text" name="city" class="form-control" required>
              </div>
              <div class="col-4">
                <label class="form-label">CAP</label>
                <input type="text" name="zip" class="form-control" pattern="\d{5}" placeholder="es. 00100" required>
              </div>
            </div>

            <div>
              <label class="form-label">Metodo di pagamento</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="pm_card" value="carta" checked>
                <label class="form-check-label" for="pm_card">Carta</label>
              </div>
              <div class="ps-3 py-2 border rounded mb-2">
                <div class="mb-2">
                  <input type="text" name="card_number" class="form-control" placeholder="Numero carta (demo)" minlength="12" maxlength="19">
                </div>
                <div class="row g-2">
                  <div class="col-6"><input type="text" name="card_exp" class="form-control" placeholder="MM/AA"></div>
                  <div class="col-6"><input type="text" name="card_cvv" class="form-control" placeholder="CVV"></div>
                </div>
              </div>

              <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="pm_paypal" value="paypal">
                <label class="form-check-label" for="pm_paypal">PayPal</label>
              </div>

              <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="pm_cash" value="contrassegno">
                <label class="form-check-label" for="pm_cash">Contrassegno</label>
              </div>
            </div>

            <button class="btn btn-pink w-100">Conferma acquisto</button>
          </form>

        <?php else: ?>
          <div class="alert alert-warning">
            Per confermare l’ordine devi effettuare l’accesso o registrarti.
          </div>
          <a class="btn btn-outline-primary w-100 mb-2"
             href="/progetto_esame/STEP3_PHP_MVC/public/login.php?redirect=/progetto_esame/STEP3_PHP_MVC/public/riepilogo-ordine.php">
            Vai al login
          </a>
          <a class="btn btn-primary w-100"
             href="/progetto_esame/STEP3_PHP_MVC/public/iscrizione.php?redirect=/progetto_esame/STEP3_PHP_MVC/public/riepilogo-ordine.php">
            Registrati e continua
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . "/partials/footer.php"; ?>
