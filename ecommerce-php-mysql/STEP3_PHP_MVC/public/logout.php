<?php
// public/logout.php
session_start();

// Elimina tutte le variabili di sessione
$_SESSION = [];

// Cancella il cookie della sessione, se esiste
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Distrugge la sessione
session_destroy();

// Riporta alla home
header("Location: /progetto_esame/STEP3_PHP_MVC/public/index.php");
exit;
