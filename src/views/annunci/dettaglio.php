<?php
// views/annunci/dettaglio.php

$isProprietario = !empty($_SESSION['id_studente']) &&
  (int)$_SESSION['id_studente'] === (int)$annuncio['id_venditore'];

// Placeholder immagini
$immagini = [
  'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=800&q=80',
  'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800&q=80',
  'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800&q=80',
];
$carouselId = 'carousel-annuncio-' . (int)$annuncio['id_annuncio'];

// Calcolo risparmio
$risparmio = 0;
if (!empty($annuncio['prezzo_listino']) && $annuncio['prezzo_listino'] > 0) {
  $risparmio = 100 - round(($annuncio['prezzo_vendita'] / $annuncio['prezzo_listino']) * 100);
}

// Badge condizione
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

<div class="container-fluid px-0 mt-3">
  <a href="javascript:history.back()" class="btn btn-link text-muted ps-0 mb-2 d-inline-flex align-items-center gap-1 text-decoration-none">
    <i class="bi bi-arrow-left"></i> Torna alla ricerca
  </a>

  <?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3">
      <?php foreach ($_SESSION['errors'] as $e): ?>
        <div class="small"><i class="bi bi-exclamation-circle me-1"></i><?= safe_string($e) ?></div>
      <?php endforeach; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php $_SESSION['errors'] = []; ?>
  <?php endif; ?>

  <div class="row g-3">

    <div class="col-xl-4 col-lg-4">
      <div id="<?= $carouselId ?>" class="carousel slide rounded-4 overflow-hidden shadow-sm mb-3" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php foreach ($immagini as $i => $img): ?>
            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
              <img src="<?= $img ?>" class="d-block w-100" style="height:280px; object-fit:cover;" alt="Libro">
            </div>
          <?php endforeach; ?>
        </div>
        <?php if (count($immagini) > 1): ?>
          <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        <?php endif; ?>
      </div>

      <?php if (!empty($annuncio['descrizione'])): ?>
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
          <h6 class="fw-bold small text-muted text-uppercase mb-2" style="font-size:0.75rem; letter-spacing:0.5px;">Note del venditore</h6>
          <p class="mb-0 small fst-italic text-secondary" style="max-height: 100px; overflow-y: auto;">
            "<?= safe_string($annuncio['descrizione']) ?>"
          </p>
        </div>
      <?php endif; ?>
    </div>

    <div class="col-xl-4 col-lg-4">
      <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
        <div class="mb-2">
          <span class="badge rounded-pill bg-primary-subtle text-primary px-3"><?= safe_string($annuncio['materia']) ?></span>
          <span class="badge rounded-pill <?= $badgeClass ?> ms-1"><?= safe_string($annuncio['condizione']) ?></span>
        </div>

        <h3 class="fw-bold mb-3 text-dark">
          <?= safe_string($annuncio['titolo']) ?>
          <?php if (!empty($annuncio['volume'])): ?>
            <span class="text-primary text-opacity-75">Vol. <?= safe_string($annuncio['volume']) ?></span>
          <?php endif; ?>
        </h3>

        <div class="d-flex flex-column gap-3">
          <div class="d-flex justify-content-between border-bottom pb-2">
            <span class="text-muted small">Autore</span>
            <span class="fw-semibold small text-end"><?= safe_string($annuncio['autore']) ?></span>
          </div>
          <div class="d-flex justify-content-between border-bottom pb-2">
            <span class="text-muted small">Editore</span>
            <span class="fw-semibold small text-end"><?= safe_string($annuncio['editore']) ?></span>
          </div>
          <div class="d-flex justify-content-between border-bottom pb-2">
            <span class="text-muted small">ISBN</span>
            <span class="font-monospace fw-bold text-primary small"><?= safe_string($annuncio['isbn']) ?></span>
          </div>
          <?php if (!empty($annuncio['anno_scolastico'])): ?>
            <div class="d-flex justify-content-between border-bottom pb-2">
              <span class="text-muted small">Anno Consigliato</span>
              <span class="fw-semibold small"><?= safe_string($annuncio['anno_scolastico']) ?></span>
            </div>
          <?php endif; ?>
          <div class="d-flex justify-content-between">
            <span class="text-muted small">Data annuncio</span>
            <span class="text-secondary small"><?= date('d/m/Y', strtotime($annuncio['data_pubblicazione'])) ?></span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-lg-4">
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-3 text-center bg-white">
        <span class="text-muted small text-uppercase fw-bold" style="letter-spacing:1px;">Prezzo Richiesto</span>
        <div class="display-5 fw-bold text-primary my-1">€<?= number_format($annuncio['prezzo_vendita'], 2) ?></div>

        <?php if (!empty($annuncio['prezzo_listino']) && $annuncio['prezzo_listino'] > 0):
          // Calcolo della percentuale di risparmio
          $risparmio = 100 - round(($annuncio['prezzo_vendita'] / $annuncio['prezzo_listino']) * 100);
        ?>
          <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
            <span class="text-muted text-decoration-line-through fs-5">
              €<?= number_format($annuncio['prezzo_listino'], 2) ?>
            </span>

            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2">
              <i class="bi bi-arrow-down-short"></i>-<?= $risparmio ?>%
            </span>
          </div>

          <div class="text-success small fw-medium">
            Risparmi €<?= number_format($annuncio['prezzo_listino'] - $annuncio['prezzo_vendita'], 2) ?> rispetto al nuovo
          </div>
        <?php endif; ?>

        <div class="d-grid mt-2">
          <?php if (!isset($_SESSION['id_studente'])): ?>
            <a href="index.php?page=login&action=index" class="btn btn-primary btn-lg rounded-3 fw-bold">ACCEDI PER ACQUISTARE</a>
          <?php elseif ($isProprietario): ?>
            <div class="alert alert-info rounded-3 small mb-3 py-2 text-center">
              <i class="bi bi-info-circle me-1"></i> Questo è un tuo annuncio.
            </div>
            <form method="POST" action="index.php?page=annunci&action=elimina" onsubmit="return confirm('Vuoi rimuovere l\'annuncio?')">
              <input type="hidden" name="id_annuncio" value="<?= (int)$annuncio['id_annuncio'] ?>">
              <button class="btn btn-outline-danger w-100 rounded-3"><i class="bi bi-trash me-1"></i>ELIMINA ANNUNCIO</button>
            </form>
          <?php elseif ($annuncio['stato'] === 'disponibile'): ?>
            <form method="POST" action="index.php?page=annunci&action=acquista" onsubmit="return confirm('Confermi l\'acquisto?')">
              <input type="hidden" name="id_annuncio" value="<?= (int)$annuncio['id_annuncio'] ?>">
              <button class="btn btn-primary btn-lg rounded-3 fw-bold w-100 shadow">ACQUISTA ORA</button>
            </form>
          <?php else: ?>
            <button class="btn btn-secondary btn-lg rounded-3 fw-bold disabled w-100">VENDUTO</button>
          <?php endif; ?>
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Dettagli Scambio</h6>
        <div class="small">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
              <i class="bi bi-person text-primary"></i>
            </div>
            <div>
              <div class="text-muted" style="font-size:0.7rem;">Venditore</div>
              <div class="fw-bold">
                <?php if ($isProprietario): ?>
                  <a href="index.php?page=dashboard">
                    <?= safe_string($annuncio['venditore']) ?>
                  </a>
                <?php else: ?>
                  <?= safe_string($annuncio['venditore']) ?>
                <?php endif; ?>
              </div>
              <div class="fw-bold">
                <a href="mailto:<?= safe_string($annuncio['email_venditore']) ?>">
                  <?= safe_string($annuncio['email_venditore']) ?>
                </a>
              </div>
            </div>
          </div>
          <div class="d-flex align-items-center mb-3">
            <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
              <i class="bi bi-geo-alt text-primary"></i>
            </div>
            <div>
              <div class="text-muted" style="font-size:0.7rem;">Dove</div>
              <div class="fw-bold"><?= safe_string($annuncio['luogo_scambio']) ?></div>
            </div>
          </div>
          <?php if (!empty($annuncio['data_ora_scambio'])): ?>
            <div class="d-flex align-items-center">
              <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                <i class="bi bi-clock text-primary"></i>
              </div>
              <div>
                <div class="text-muted" style="font-size:0.7rem;">Quando</div>
                <div class="fw-bold"><?= date('d/m/Y H:i', strtotime($annuncio['data_ora_scambio'])) ?></div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</div>