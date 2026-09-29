<?php
$pageTitle = "Palette Occhi – SereCosmetics❀";
require __DIR__ . "/partials/header.php";
?>

<!-- Contenuto principale -->
<main>
  <section class="container my-4">
    <div class="row g-4">
      <!-- Immagine prodotto -->
      <div class="col-md-6">
        <img class="img-fluid rounded" 
             src="/progetto_esame/STEP3_PHP_MVC/assets/img/sere7.jpg" 
             alt="Palette Occhi" loading="lazy" width="600" height="450">
      </div>

      <!-- Dettagli prodotto + Recensioni -->
      <div class="col-md-6">
        <h1 class="h3">Palette Occhi</h1>
        <p class="text-muted">SereCosmetics❀ • Categoria Occhi</p>
        <p>
          Una palette completa con 12 tonalità altamente pigmentate,
          perfette per creare look naturali o intensi. Finish matt e shimmer
          per ogni occasione.
        </p>
        <p class="h4">€ 59,90</p>

        <!-- FORM che aggiunge al carrello -->
        <form action="/progetto_esame/STEP3_PHP_MVC/public/add_to_cart.php" 
              method="post" 
              class="d-flex gap-2 align-items-center">
          <input type="hidden" name="id" value="7">
          <input type="hidden" name="redirect" value="/progetto_esame/STEP3_PHP_MVC/public/prodotto7.php">
          <label class="me-2 mb-0" for="qty">Quantità</label>
          <input id="qty" type="number" name="qty" min="1" value="1" 
                 class="form-control" style="max-width:100px">
          <button type="submit" class="btn btn-pink">Aggiungi al carrello</button>
        </form>

        <!-- Recensioni -->
        <hr class="my-4">
        <h2 class="h6 mb-3">Recensioni</h2>
        <div class="d-flex align-items-center mb-3">
          <div class="me-2">⭐ ⭐ ⭐ ⭐ ⭐</div>
          <strong class="me-2">5/5</strong>
          <span class="text-muted">(20 recensioni)</span>
        </div>

        <div class="list-group">
          <div class="list-group-item">
            <div class="d-flex justify-content-between">
              <div>
                <strong>Chiara</strong>
                <div class="text-warning small">⭐ ⭐ ⭐ ⭐ ⭐</div>
              </div>
              <small class="text-muted">10/03/2025</small>
            </div>
            <p class="mb-0 mt-2">Colori bellissimi e super scriventi, la adoro!</p>
          </div>

          <div class="list-group-item">
            <div class="d-flex justify-content-between">
              <div>
                <strong>Laura</strong>
                <div class="text-warning small">⭐ ⭐ ⭐ ⭐ ⯪</div>
              </div>
              <small class="text-muted">01/03/2025</small>
            </div>
            <p class="mb-0 mt-2">
              Ottima qualità, forse un po’ costosa ma vale il prezzo.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . "/partials/footer.php"; ?>
