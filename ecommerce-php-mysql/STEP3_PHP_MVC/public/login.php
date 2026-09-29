<?php
// public/login.php
$pageTitle = "Login – SereCosmetics❀";
require __DIR__ . "/partials/header.php";
require __DIR__ . "/../bootstrap.php";
require_once __DIR__ . '/../User.php';

$messaggio = null;

// pagina di ritorno dopo il login (index.php)
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '/progetto_esame/STEP3_PHP_MVC/public/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email    = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  if (!empty($_POST['redirect'])) {
    $redirect = $_POST['redirect'];
  }

  if ($email && $password) {
    $utente = User::findByEmail($pdo, $email);
    if ($utente && password_verify($password, $utente['password_hash'])) {
      $_SESSION['user_id'] = (int)$utente['id'];

      // carrello ospite nel DB
      if (!empty($_SESSION['carrello']) && is_array($_SESSION['carrello'])) {
        foreach ($_SESSION['carrello'] as $prodId => $q) {
          $prodId = (int)$prodId;
          $q = max(1, (int)$q);
          if ($prodId <= 0) continue;

          $sel = $pdo->prepare("SELECT id FROM Carrello WHERE utente_id = :u AND prodotto_id = :p");
          $sel->execute([':u' => (int)$_SESSION['user_id'], ':p' => $prodId]);
          $existing = $sel->fetch();

          if ($existing) {
            $upd = $pdo->prepare("UPDATE Carrello SET quantita = quantita + :q WHERE id = :id AND utente_id = :u");
            $upd->execute([':q' => $q, ':id' => $existing['id'], ':u' => (int)$_SESSION['user_id']]);
          } else {
            $ins = $pdo->prepare("INSERT INTO Carrello (utente_id, prodotto_id, quantita) VALUES (:u, :p, :q)");
            $ins->execute([':u' => (int)$_SESSION['user_id'], ':p' => $prodId, ':q' => $q]);
          }
        }
        unset($_SESSION['carrello']);
      }

      header("Location: " . $redirect);
      exit;
    } else {
      $messaggio = "Credenziali non valide.";
    }
  } else {
    $messaggio = "Inserisci email e password.";
  }
}
?>
<h1 class="h4 mb-3">Login</h1>

<?php if ($messaggio): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($messaggio) ?></div>
<?php endif; ?>

<form method="post" class="row g-3" action="/progetto_esame/STEP3_PHP_MVC/public/login.php">
  <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
  <div class="col-12">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="col-12">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <div class="col-12">
  <button class="btn btn-pink">Accedi</button>
</div>
</form>

<p class="mt-3">
  Non hai un account?
  <a class="btn btn-outline-pink btn-sm ms-2" href="/progetto_esame/STEP3_PHP_MVC/public/iscrizione.php?redirect=<?= urlencode($redirect) ?>">Registrati</a>
</p>

<?php require __DIR__ . "/partials/footer.php"; ?>
