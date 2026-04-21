<?php
$isProprietario = !empty($_SESSION['id_studente']) &&
  (int)$_SESSION['id_studente'] === (int)$annuncio['id_venditore'];

// Immagini di stock (placeholder finché il sistema di upload non è pronto)
// Tre scatti diversi di libri per simulare il carosello
$immagini = [
  'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=800&q=80', // pila di libri
  'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800&q=80',   // libro aperto
  'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800&q=80', // scaffale
];
$carouselId = 'carousel-annuncio-' . (int)$annuncio['id_annuncio'];
?>

<div class="mt-4">
  <a href="javascript:history.back()" class="btn btn-link text-muted ps-0 mb-3 d-inline-flex align-items-center gap-1">
    <i class="bi bi-arrow-left"></i> Indietro
  </a>

  <?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3">
      <?php foreach ($_SESSION['errors'] as $e): ?>
        <div><i class="bi bi-exclamation-circle me-1"></i><?= safe_string($e) ?></div>
      <?php endforeach; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php $_SESSION['errors'] = []; ?>
  <?php endif; ?>

  <div class="row g-4">


    <div class="col-lg-7">

      <!-- Carosello immagini -->
      <div id="<?= $carouselId ?>" class="carousel slide rounded-4 overflow-hidden shadow-sm mb-4"
        data-bs-ride="carousel">

        <!-- Indicatori -->
        <div class="carousel-indicators">
          <?php foreach ($immagini as $i => $img): ?>
            <button type="button"
              data-bs-target="#<?= $carouselId ?>"
              data-bs-slide-to="<?= $i ?>"
              class="<?= $i === 0 ? 'active' : '' ?>"
              aria-label="Immagine <?= $i + 1 ?>">
            </button>
          <?php endforeach; ?>
        </div>

        <!-- Slide -->
        <div class="carousel-inner">
          <?php foreach ($immagini as $i => $img): ?>
            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
              <img src="<?= $img ?>" class="d-block w-100"
                style="height:340px;object-fit:cover;"
                alt="Immagine <?= $i + 1 ?> del libro">
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Controlli prev/next (mostrati solo se ci sono più immagini) -->
        <?php if (count($immagini) > 1): ?>
          <button class="carousel-control-prev" type="button"
            data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Precedente</span>
          </button>
          <button class="carousel-control-next" type="button"
            data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Successiva</span>
          </button>
        <?php endif; ?>
      </div>

      <!-- Info libro -->
      <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
          <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold">
            <?= safe_string($annuncio['materia']) ?>
          </span>
          <?php
          $badgeClass = '';
          switch ($annuncio['condizione']) {
            case 'Ottime condizioni':
              $badgeClass = 'bg-success-subtle text-success';
              break;
            case 'Buone condizioni':
              $badgeClass = 'bg-warning-subtle text-warning-emphasis';
              break;
            case 'Condizioni accettabili':
              $badgeClass = 'bg-info-subtle text-info-emphasis';
              break;
            case 'Danneggiato':
              $badgeClass = 'bg-danger-subtle text-danger';
              break;
            default:
              $badgeClass = 'bg-secondary-subtle text-secondary';
          }
          ?>
          <span class="badge rounded-pill <?= $badgeClass ?> small">
            <?= safe_string($annuncio['condizione']) ?>
          </span>
        </div>

        <?php if (!empty($annuncio['volume'])): ?>
          <h3 class="fw-bold mb-1 lh-sm">
            <?= safe_string($annuncio['titolo']) ?> - Vol. <?= safe_string($annuncio['volume']) ?>
          </h3>
        <?php else: ?>
          <h3 class="fw-bold mb-1 lh-sm">
            <?= safe_string($annuncio['titolo']) ?>
          </h3>
        <?php endif; ?>


        <dl class="row small mb-0">
          <dt class="col-sm-4 text-muted">Autore</dt>
          <dd class="col-sm-8"><?= safe_string($annuncio['autore']) ?></dd>

          <dt class="col-sm-4 text-muted">Editore</dt>
          <dd class="col-sm-8"><?= safe_string($annuncio['editore']) ?></dd>

          <dt class="col-sm-4 text-muted">ISBN</dt>
          <dd class="col-sm-8 font-monospace"><?= safe_string($annuncio['isbn']) ?></dd>

          <?php if (!empty($annuncio['anno_scolastico'])): ?>
            <dt class="col-sm-4 text-muted">Anno scolastico</dt>
            <dd class="col-sm-8"><?= safe_string($annuncio['anno_scolastico']) ?></dd>
          <?php endif; ?>

          <dt class="col-sm-4 text-muted">Pubblicato il</dt>
          <dd class="col-sm-8"><?= date('d/m/Y', strtotime($annuncio['data_pubblicazione'])) ?></dd>
        </dl>

        <?php if (!empty($annuncio['descrizione'])): ?>
          <div class="bg-light rounded-3 p-3 mt-3">
            <div class="fw-semibold small text-muted text-uppercase mb-1">Note del venditore</div>
            <p class="mb-0 fst-italic small">"<?= safe_string($annuncio['descrizione']) ?>"</p>
          </div>
        <?php endif; ?>
      </div>
    </div>


    <div class="col-lg-5">

      <!-- Card prezzo + azione -->
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-3">

        <div class="text-center mb-4">
          <div class="display-5 fw-bold text-primary">€<?= number_format($annuncio['prezzo'], 2) ?></div>
          <div class="text-muted small">Prezzo richiesto</div>
        </div>

        <?php if (!isset($_SESSION['id_studente'])): ?>
          <!-- Utente non loggato -->
          <a href="index.php?page=login&action=index" class="btn btn-primary rounded-3 w-100 py-2 fw-semibold mb-2">
            <i class="bi bi-box-arrow-in-right me-1"></i> Accedi per acquistare
          </a>

        <?php elseif ($isProprietario): ?>
          <!-- Venditore: vede solo il pulsante elimina -->
          <div class="alert alert-info rounded-3 small mb-3 py-2 text-center">
            <i class="bi bi-info-circle me-1"></i> Questo è un tuo annuncio.
          </div>
          <form method="POST" action="index.php?page=annunci&action=elimina"
            onsubmit="return confirm('Sei sicuro di voler rimuovere questo annuncio?')">
            <input type="hidden" name="id_annuncio" value="<?= (int)$annuncio['id_annuncio'] ?>">
            <button type="submit" class="btn btn-outline-danger rounded-3 w-100">
              <i class="bi bi-trash me-1"></i> Rimuovi annuncio
            </button>
          </form>

        <?php elseif ($annuncio['stato'] === 'disponibile'): ?>
          <!-- Acquirente -->
          <form method="POST" action="index.php?page=annunci&action=acquista"
            onsubmit="return confirm('Confermi l\'acquisto di questo libro?')">
            <input type="hidden" name="id_annuncio" value="<?= (int)$annuncio['id_annuncio'] ?>">
            <button type="submit" class="btn btn-primary rounded-3 w-100 py-2 fw-semibold">
              <i class="bi bi-bag-check me-1"></i> Acquista
            </button>
          </form>
          <p class="text-muted small text-center mt-2 mb-0">
            Confermando, l'annuncio verrà rimosso dalla bacheca.
          </p>

        <?php else: ?>
          <!-- Già venduto -->
          <div class="alert alert-secondary rounded-3 text-center mb-0">
            <i class="bi bi-check-circle me-1"></i> Questo libro è già stato venduto.
          </div>
        <?php endif; ?>
      </div>

      <!-- Card info scambio -->
      <div class="card border-0 shadow-sm rounded-4 p-4">
        <h6 class="fw-bold mb-3">
          <i class="bi bi-arrow-left-right text-primary me-1"></i> Dettagli scambio
        </h6>
        <ul class="list-unstyled mb-0 small">
          <li class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-person-circle text-muted fs-5"></i>
            <div>
              <?php if ($isProprietario): ?>
                <div class="text-muted">Venditore</div>
                <a class="fw-semibold" href="index.php?page=dashboard&action=index">
                  <?= safe_string($annuncio['venditore']) ?>
                </a>
              <?php else: ?>
                <div class="text-muted">Venditore</div>
                <div class="fw-semibold"><?= safe_string($annuncio['venditore']) ?></div>
              <?php endif; ?>
            </div>
          </li>
          <li class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-geo-alt text-muted fs-5"></i>
            <div>
              <div class="text-muted">Luogo</div>
              <div class="fw-semibold"><?= safe_string($annuncio['luogo_scambio']) ?></div>
            </div>
          </li>
          <?php if (!empty($annuncio['data_ora_scambio'])): ?>
            <li class="d-flex align-items-center gap-2">
              <i class="bi bi-calendar-event text-muted fs-5"></i>
              <div>
                <div class="text-muted">Data e ora</div>
                <div class="fw-semibold">
                  <?= date('d/m/Y', strtotime($annuncio['data_ora_scambio'])) ?>
                  alle <?= date('H:i', strtotime($annuncio['data_ora_scambio'])) ?>
                </div>
              </div>
            </li>
          <?php endif; ?>
        </ul>
      </div>

    </div>
  </div>
</div>