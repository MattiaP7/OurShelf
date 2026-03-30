<?php
require_once __DIR__ . '/../../utils/helpers.php';

?>

<section class="hero-section text-center py-5 bg-light mb-4">
  <div class="container">
    <h1 class="display-4">Benvenuti su OurShelf</h1>
    <a href="index.php?page=libri&action=lista" class="btn btn-primary btn-lg">Sfoglia il catalogo</a>
  </div>
</section>

<div class="row">
  <div class="col-md-8">
    <h2>Ultimi Arrivi</h2>
    <hr>

    <?php if (isset($annunci) && !empty($annunci)): ?>
      <div class="list-group">
        <?php foreach ($annunci as $libro): ?>
          <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1"><?= safe_string($libro['titolo']); ?></h5>
              <small>Autore: <?= safe_string($libro['autore']); ?></small>
            </div>
            <span class="badge bg-success rounded-pill">
              <?php
              if (isset($libro['prezzo']))
                echo safe_string($libro['prezzo']);
              else
                echo '0'
              ?>
              €</span>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info">
        Non ci sono ancora libri disponibili. Sii il primo a caricarne uno!
      </div>
    <?php endif; ?>
  </div>
</div>