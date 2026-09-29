<?php
session_start();

// Controllo se esiste il carrello
if (isset($_SESSION['carrello']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Se il prodotto è nel carrello lo elimino
    if (isset($_SESSION['carrello'][$id])) {
        unset($_SESSION['carrello'][$id]);
    }
}

// Dopo la rimozione torno al carrello
header("Location: carrello.php");
exit;
?>
