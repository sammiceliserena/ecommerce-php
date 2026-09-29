<?php
session_start();

// Controllo se esiste il carrello
if (isset($_SESSION['carrello']) && isset($_POST['id']) && isset($_POST['qty'])) {
    $id = intval($_POST['id']);
    $qty = intval($_POST['qty']);

    if ($qty > 0) {
        $_SESSION['carrello'][$id]['quantita'] = $qty;
    } else {
        // Se la quantità è <= 0, elimino il prodotto
        unset($_SESSION['carrello'][$id]);
    }
}

// Torno alla pagina carrello
header("Location: carrello.php");
exit;
?>
