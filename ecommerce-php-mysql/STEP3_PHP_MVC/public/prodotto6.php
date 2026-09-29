<?php
$pageTitle = "Gel Sopracciglia – SereCosmetics❀";
require __DIR__ . "/partials/header.php";
?>

<!-- Contenuto principale -->
<main>
  <section class="container my-4">
    <div class="row g-4">
      <!-- Immagine prodotto -->
      <div class="col-md-6">
        <img class="img-fluid rounded" 
             src="/progetto_esame/STEP3_PHP_MVC/assets/img/sere6.jpg" 
             alt="Gel Sopracciglia" loading="lazy" width="600" height="450">
      </div>

      <!-- Dettagli prodotto + Recensioni -->
      <div class="col-md-6">
        <h1 class="h3">Gel Sopracciglia</h1>
        <p class="text-muted">SereCosmetics❀ • Categoria Occhi</p>
        <p>
          Gel trasparente a lunga durata che fissa e definisce le sopracciglia
          senza appesantirle. Perfetto per un look naturale e ordinato che dura tutto il giorno.
        </p>
        <p class="h4">€ 12,90</p>

        <!-- FORM che aggiunge al carrello -->
        <form action="/progetto_esame/STEP3_PHP_MVC/public/add_to_cart.php" 
              method="post" 
              class="d-flex gap-2 align-items-center">
          <input type="hidden" name="id" value="6">
          <input type="hidden" name="redirect" value="/progetto_esame/STEP3_PHP_MVC/public/prodotto6.php">
          <label class="me-2 mb-0" for="qty">Quantità</label>
          <input id="qty" type="number" name="qty" min="1" value="1" 
                 class="form-control" style="max-width:100px">
          <button type="submit" class="btn btn-pink">Aggiungi al carrello</button>
        </form>

        <!-- Recensioni -->
        <hr class="my-4">
        <h2 class="h6 mb-3">Recensioni</h2>
        <div class="d-flex align-items-center mb-3">
          <div class="me-2">⭐ ⭐ ⭐ ⭐ ⯪</div>
          <strong class="me-2">4,5/5</strong>
          <span class="text-muted">(15 recensioni)</span>
        </div>

        <div class="list-group">
          <div class="list-group-item">
            <div class="d-flex justify-content-between">
              <div>
                <strong>Sara</strong>
                <div class="text-warning small">⭐ ⭐ ⭐ ⭐ ⭐</div>
              </div>
              <small class="text-muted">08/03/2025</small>
            </div>
            <p class="mb-0 mt-2">Tiene le sopracciglia in ordine tutto il giorno.</p>
          </div>

          <div class="list-group-item">
            <div class="d-flex justify-content-between">
              <div>
                <strong>Valentina</strong>
                <div class="text-warning small">⭐ ⭐ ⭐ ⭐✩</div>
              </div>
              <small class="text-muted">28/02/2025</small>
            </div>
            <p class="mb-0 mt-2">
              Buon gel, non lascia residui ma la confezione è un po’ piccola.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . "/partials/footer.php"; ?>
