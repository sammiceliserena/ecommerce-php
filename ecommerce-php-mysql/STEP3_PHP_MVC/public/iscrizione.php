<?php
// public/iscrizione.php
$pageTitle = "Iscrizione – ShopEsame";
require __DIR__ . "/partials/header.php";
require __DIR__ . "/../bootstrap.php";
require_once __DIR__ . '/../User.php';

$messaggio = null;

// pagina a cui tornare dopo la registrazione (index.php)
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '/progetto_esame/STEP3_PHP_MVC/public/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome     = trim($_POST['nome'] ?? '');
  $cognome  = trim($_POST['cognome'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  if (!empty($_POST['redirect'])) {
    $redirect = $_POST['redirect']; 
  }

  if ($nome && $cognome && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 6) {
    if (User::findByEmail($pdo, $email)) {
      $messaggio = "Email già registrata.";
    } else {
      // crea utente con password_hash
      $id = User::create($pdo, $nome, $cognome, $email, $password);
      $_SESSION['user_id'] = (int)$id;

      // carrello ospite nel DB
      if (!empty($_SESSION['carrello']) && is_array($_SESSION['carrello'])) {
        foreach ($_SESSION['carrello'] as $prodId => $q) {
          $prodId = (int)$prodId;
          $q = max(1, (int)$q);
          if ($prodId <= 0) continue;

          $sel = $pdo->prepare("SELECT id FROM Carrello WHERE utente_id = :u AND prodotto_id = :p");
          $sel->execute([':u' => (int)$id, ':p' => $prodId]);
          $existing = $sel->fetch();

          if ($existing) {
            $upd = $pdo->prepare("UPDATE Carrello SET quantita = quantita + :q WHERE id = :id AND utente_id = :u");
            $upd->execute([':q' => $q, ':id' => $existing['id'], ':u' => (int)$id]);
          } else {
            $ins = $pdo->prepare("INSERT INTO Carrello (utente_id, prodotto_id, quantita) VALUES (:u, :p, :q)");
            $ins->execute([':u' => (int)$id, ':p' => $prodId, ':q' => $q]);
          }
        }
        unset($_SESSION['carrello']);
      }

      header("Location: " . $redirect);
      exit;
    }
  } else {
    $messaggio = "Compila correttamente tutti i campi (password almeno 6 caratteri).";
  }
}
?>
<h1 class="h4 mb-3">Iscrizione</h1>

<?php if ($messaggio): ?>
  <div class="alert alert-warning"><?= htmlspecialchars($messaggio) ?></div>
<?php endif; ?>

<form method="post" class="row g-3" action="/progetto_esame/STEP3_PHP_MVC/public/iscrizione.php">
  <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
  <div class="col-md-6">
    <label class="form-label">Nome</label>
    <input name="nome" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Cognome</label>
    <input name="cognome" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" minlength="6" required>
  </div>
  <div class="col-12">
  <button class="btn btn-pink">Crea account</button>
</div>
</form>

<p class="mt-3">
  Hai già un account?
  <a href="/progetto_esame/STEP3_PHP_MVC/public/login.php?redirect=<?= urlencode($redirect) ?>" 
     class="btn btn-outline-pink btn-sm ms-2">
     Accedi
  </a>
</p>

<?php require __DIR__ . "/partials/footer.php"; ?>
