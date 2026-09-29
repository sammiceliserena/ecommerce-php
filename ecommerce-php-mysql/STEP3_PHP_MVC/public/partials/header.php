<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentFile = basename($_SERVER['SCRIPT_NAME']); // es: "index.php" o "ricerca.php"
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'SereCosmetics❀') ?></title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
        crossorigin="anonymous">

  <!-- Slick -->
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>

  <!-- CSS -->
  <link rel="stylesheet" href="/progetto_esame/STEP1_HTML_CSS/assets/css/styles.css">

  <style>
    body { padding-top: 72px; }
    .card-product img { object-fit: cover; height: 160px; }

    .nav-link.emoji-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      font-size: 1.5rem;
      background-color: rgba(128, 128, 128, 0.2);
      color: black;
      transition: background-color 0.2s ease-in-out;
      margin: 0 10px;
    }
    .nav-link.emoji-icon:hover {
      background-color: rgba(128, 128, 128, 0.4);
    }

    .navbar-search {
      flex: 1 1 380px;
      max-width: 600px;
      margin: 8px 12px;
    }
    .navbar-search .form-control {
      border-radius: 999px;
      padding-left: 14px;
      padding-right: 14px;
    }
    .navbar-search .btn {
      border-radius: 999px;
      white-space: nowrap;
    }
    @media (max-width: 991.98px) {
      .navbar-search { flex: 1 1 auto; max-width: 100%; margin: 10px 0; }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="/progetto_esame/STEP3_PHP_MVC/public/index.php">SereCosmetics❀</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <?php if ($currentFile !== "index.php" && $currentFile !== "ricerca.php"): ?>
        <!-- Barra di ricerca visibile solo fuori da index e ricerca -->
        <form class="d-flex navbar-search" role="search"
              action="/progetto_esame/STEP3_PHP_MVC/public/ricerca.php" method="get">
          <input class="form-control me-2" type="search" name="q"
                 placeholder="Cerca prodotti…"
                 value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
          <button class="btn btn-pink" type="submit">Cerca</button>
        </form>
      <?php endif; ?>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link emoji-icon" href="/progetto_esame/STEP3_PHP_MVC/public/index.php" title="Home">🏠</a>
        </li>
        <li class="nav-item">
          <?php if (!empty($_SESSION['user_id'])): ?>
            <a class="nav-link emoji-icon" href="/progetto_esame/STEP3_PHP_MVC/public/logout.php" title="Logout">↩️</a>
          <?php else: ?>
            <a class="nav-link emoji-icon" href="/progetto_esame/STEP3_PHP_MVC/public/login.php" title="Login">👤</a>
          <?php endif; ?>
        </li>
        <li class="nav-item">
          <a class="nav-link emoji-icon" href="/progetto_esame/STEP3_PHP_MVC/public/carrello.php" title="Carrello">🛒</a>
        </li>
        <li class="nav-item">
          <a class="nav-link emoji-icon" href="/progetto_esame/STEP3_PHP_MVC/public/cronologia-ordini.php" title="Ordini">📦</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container my-4">

