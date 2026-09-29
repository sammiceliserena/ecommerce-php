<?php
// public/avvenuto-acquisto.php
$pageTitle = "Acquisto – SereCosmetics❀";
require __DIR__ . "/partials/header.php";
require_once __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  echo '<div class="alert alert-warning">Devi essere autenticato per confermare l\'acquisto.</div>';
  echo '<a class="btn btn-outline-pink" href="/progetto_esame/STEP3_PHP_MVC/public/login.php?redirect=' .
       urlencode('/progetto_esame/STEP3_PHP_MVC/public/riepilogo-ordine.php') . '">Vai al login</a>';
  require __DIR__ . "/partials/footer.php"; exit;
}

// leggi carrello dal DB
$sql = "SELECT 
          c.prodotto_id,
          c.quantita,
          p.nome,
          p.prezzo,
          p.immagine_url
        FROM Carrello c
        JOIN Prodotto p ON p.id = c.prodotto_id
        WHERE c.utente_id = :u
        ORDER BY c.id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':u' => (int)$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$items) {
  echo '<div class="alert alert-secondary">Il carrello è vuoto.</div>';
  echo '<a class="btn btn-outline-pink" href="/progetto_esame/STEP3_PHP_MVC/public/index.php">Torna alla Home</a>';
  require __DIR__ . "/partials/footer.php"; exit;
}

// calcola totale
$totale = 0.00;
foreach ($items as $it) {
  $totale += (float)$it['prezzo'] * (int)$it['quantita'];
}

try {
  $pdo->beginTransaction();

  // crea ordine (stato COMPLETATO)
  $stmtOrd = $pdo->prepare("
    INSERT INTO Ordine (utente_id, totale, stato) 
    VALUES (:u, :tot, 'COMPLETATO')
  ");
  $stmtOrd->execute([
    ':u'   => (int)$user_id,
    ':tot' => $totale
  ]);
  $ordine_id = (int)$pdo->lastInsertId();

  // inserisci righe ordine
  $stmtItem = $pdo->prepare("
    INSERT INTO OrdineItem (ordine_id, prodotto_id, quantita, prezzo_unitario)
    VALUES (:o, :p, :q, :pu)
  ");
  foreach ($items as $it) {
    $stmtItem->execute([
      ':o'  => $ordine_id,
      ':p'  => (int)$it['prodotto_id'],
      ':q'  => (int)$it['quantita'],
      ':pu' => (float)$it['prezzo'],   // prezzo “bloccato” al momento dell'acquisto
    ]);
  }

  // svuota carrello dell'utente
  $stmtDel = $pdo->prepare("DELETE FROM Carrello WHERE utente_id = :u");
  $stmtDel->execute([':u' => (int)$user_id]);

  $pdo->commit();

  // pagina di ringraziamento
  ?>
  <div class="text-center my-5">
    <h1 class="h4 mb-3">Acquisto completato</h1>
    <p class="mb-1">Grazie per il tuo ordine! 💖</p>
    <p class="text-muted">ID ordine: <strong><?= (int)$ordine_id ?></strong></p>
    <a class="btn btn-pink mt-3" href="/progetto_esame/STEP3_PHP_MVC/public/index.php">Torna alla Home</a>
    <a class="btn btn-outline-pink mt-3" href="/progetto_esame/STEP3_PHP_MVC/public/cronologia-ordini.php">Vai alla cronologia ordini</a>
  </div>
  <?php

} catch (Exception $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo '<div class="alert alert-danger">Errore durante la conferma dell\'ordine.</div>';
  // opzionale: loggare $e->getMessage()
}

require __DIR__ . "/partials/footer.php";
