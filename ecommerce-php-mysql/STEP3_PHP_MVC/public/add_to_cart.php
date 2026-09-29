<?php
require_once __DIR__ . '/../config.php';

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /progetto_esame/STEP3_PHP_MVC/public/carrello.php');
    exit;
}

// Dati dal form
$id  = isset($_POST['id'])  ? (int)$_POST['id']  : 0;
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

// Validazione base
if ($id <= 0 || $qty <= 0) {
    $_SESSION['flash_error'] = 'Dati non validi.';
    header('Location: /progetto_esame/STEP3_PHP_MVC/public/carrello.php');
    exit;
}

// Verifica prodotto attivo
$stmt = $pdo->prepare("SELECT id, nome, prezzo FROM Prodotto WHERE id = :id AND attivo = 1");
$stmt->execute([':id' => $id]);
$prodotto = $stmt->fetch();
if (!$prodotto) {
    $_SESSION['flash_error'] = 'Prodotto non disponibile.';
    header('Location: /progetto_esame/STEP3_PHP_MVC/public/carrello.php');
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;

if (empty($user_id)) {
    // OSPITE: carrello in sessione
    if (!isset($_SESSION['carrello'])) {
        $_SESSION['carrello'] = [];
    }
    if (!isset($_SESSION['carrello'][$id])) {
        $_SESSION['carrello'][$id] = 0;
    }
    $_SESSION['carrello'][$id] += $qty;

    $_SESSION['flash_success'] = 'Prodotto aggiunto al carrello.';
    header('Location: /progetto_esame/STEP3_PHP_MVC/public/carrello.php');
    exit;
}

// LOGGATO: scrivi sul DB Carrello
$sel = $pdo->prepare("SELECT id FROM Carrello WHERE utente_id = :u AND prodotto_id = :p");
$sel->execute([':u' => (int)$user_id, ':p' => $id]);
$existing = $sel->fetch();

try {
    if ($existing) {
        $upd = $pdo->prepare("UPDATE Carrello SET quantita = quantita + :q WHERE id = :id AND utente_id = :u");
        $upd->execute([':q' => $qty, ':id' => $existing['id'], ':u' => (int)$user_id]);
    } else {
        $ins = $pdo->prepare("INSERT INTO Carrello (utente_id, prodotto_id, quantita) VALUES (:u, :p, :q)");
        $ins->execute([':u' => (int)$user_id, ':p' => $id, ':q' => $qty]);
    }
} catch (PDOException $e) {
    error_log('[add_to_cart] ' . $e->getMessage());
    $_SESSION['flash_error'] = 'Errore durante l\'aggiunta al carrello.';
    header('Location: /progetto_esame/STEP3_PHP_MVC/public/carrello.php');
    exit;
}

$_SESSION['flash_success'] = 'Prodotto aggiunto al carrello.';
header('Location: /progetto_esame/STEP3_PHP_MVC/public/carrello.php');
exit;
