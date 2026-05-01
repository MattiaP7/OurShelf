<?php
defined("APP") or die("Accesso negato");

/** @var array $annuncio */
/** @var array $immagini */

$isProprietario = !empty($_SESSION['id_studente']) &&
  (int) $_SESSION['id_studente'] === (int) ($annuncio['id_venditore'] ?? $annuncio['id_studente']);

$carouselId = 'carousel-detail-' . (int) $annuncio['id_annuncio'];
$placeholder = 'https://images.pexels.com/photos/159866/books-book-pages-read-literature-159866.jpeg';
$hasFoto = !empty($immagini);

$badgeClass = match ($annuncio['condizione']) {
  'Ottime condizioni'      => 'bg-success text-white',
  'Buone condizioni'       => 'bg-warning text-dark',
  'Condizioni accettabili' => 'bg-info text-dark',
  'Danneggiato'            => 'bg-danger text-white',
  default                  => 'bg-secondary text-white',
};
?>

<style>
  .main-carousel .carousel-item img {
    height: 450px;
    object-fit: contain;
    background-color: #f8f9fa;
    /* Sfondo neutro per foto verticali */
  }

  .thumb-nav img {
    width: 80px;
    height: 60px;
    object-fit: cover;
    cursor: pointer;
    transition: all 0.2s;
    border: 3px solid transparent;
  }

  .thumb-nav img.active {
    border-color: var(--bs-primary);
    opacity: 1 !important;
  }

  .info-card {
    border-radius: 1.5rem;
    border: none;
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05);
  }

  .sticky-info {
    position: sticky;
    top: 20px;
  }
</style>

<div class="container py-4">
  <!-- Breadcrumb & Back -->
  <nav aria-label="breadcrumb" class="mb-4">
    <a href="index.php?page=annunci&action=index" class="btn btn-link text-decoration-none p-0 text-muted">
      <i class="bi bi-chevron-left"></i> Torna alla bacheca
    </a>
  </nav>

  <?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger rounded-4 shadow-sm mb-4">
      <?php foreach ($_SESSION['errors'] as $e): ?>
        <div><i class="bi bi-exclamation-triangle me-2"></i><?= safe_string($e) ?></div>
      <?php endforeach;
      unset($_SESSION['errors']); ?>
    </div>
  <?php endif; ?>

  <div class="row g-4">

    <!-- ══ COLONNA SINISTRA: GALLERIA IMMAGINI ════════════════════════════════ -->
    <div class="col-lg-7">
      <div class="card info-card overflow-hidden">
        <div id="<?= $carouselId ?>" class="carousel slide main-carousel" data-bs-ride="false">

          <!-- Badge Condizione sopra la foto -->
          <div class="position-absolute top-0 start-0 m-3 z-3">
            <span class="badge rounded-pill <?= $badgeClass ?> px-3 py-2 shadow-sm">
              <?= safe_string($annuncio['condizione']) ?>
            </span>
          </div>

          <div class="carousel-inner">
            <?php if ($hasFoto): ?>
              <?php foreach ($immagini as $i => $img): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                  <img src="<?= APP_BASE_URL ?>/public/uploads/annunci/<?= safe_string($img['nome_file']) ?>"
                    class="d-block w-100"
                    alt="Libro">
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="carousel-item active">
                <img src="<?= $placeholder ?>" class="d-block w-100 opacity-50" alt="No image">
              </div>
            <?php endif; ?>
          </div>

          <?php if ($hasFoto && count($immagini) > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
              <span class="carousel-control-prev-icon bg-dark rounded-circle p-3"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
              <span class="carousel-control-next-icon bg-dark rounded-circle p-3"></span>
            </button>
          <?php endif; ?>
        </div>

        <!-- Miniature Sotto -->
        <?php if ($hasFoto): ?>
          <div class="card-footer bg-white border-0 p-3">
            <div class="d-flex gap-2 justify-content-center thumb-nav">
              <?php foreach ($immagini as $i => $img): ?>
                <img src="<?= APP_BASE_URL ?>/public/uploads/annunci/<?= safe_string($img['nome_file']) ?>"
                  class="rounded-3 <?= $i === 0 ? 'active' : 'opacity-50' ?>"
                  data-bs-target="#<?= $carouselId ?>"
                  data-bs-slide-to="<?= $i ?>"
                  onclick="updateThumbs(this)">
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <?php if (!$hasFoto): ?>
          <h3 class="text-center">L'utente non ha caricato nessuna immagine!</h3>
          <h4 class="text-center">Questa immagine e' un placeholder</h4>
        <?php endif; ?>
      </div>

      <!-- Descrizione / Note -->
      <div class="mt-4">
        <h5 class="fw-bold"><i class="bi bi-card-text me-2"></i>Descrizione del venditore</h5>
        <div class="card info-card p-4 bg-light shadow-none">
          <p class="mb-0 text-secondary lh-lg">
            <?= !empty($annuncio['descrizione']) ? nl2br(safe_string($annuncio['descrizione'])) : 'Nessuna descrizione aggiuntiva fornita.' ?>
          </p>
        </div>
      </div>
    </div>

    <!-- ══ COLONNA DESTRA: INFO & ACQUISTO ════════════════════════════════════ -->
    <div class="col-lg-5">
      <div class="sticky-info">

        <div class="card info-card p-4 mb-4">
          <h6 class="text-primary fw-bold text-uppercase small mb-1"><?= safe_string($annuncio['materia']) ?></h6>
          <h2 class="fw-bold mb-1"><?= safe_string($annuncio['titolo']) ?></h2>
          <?php if (!empty($annuncio['volume'])): ?>
            <p class="text-muted fs-5 mb-3">Volume <?= safe_string($annuncio['volume']) ?></p>
          <?php endif; ?>

          <div class="d-flex align-items-baseline gap-3 mb-4">
            <span class="display-5 fw-bold text-dark">€<?= number_format($annuncio['prezzo_vendita'], 2) ?></span>
            <?php if (!empty($annuncio['prezzo_listino']) && $annuncio['prezzo_listino'] > 0): ?>
              <span class="text-muted text-decoration-line-through fs-5">€<?= number_format($annuncio['prezzo_listino'], 2) ?></span>
              <span class="badge bg-success-subtle text-success rounded-pill px-2">-<?= 100 - round(($annuncio['prezzo_vendita'] / $annuncio['prezzo_listino']) * 100) ?>%</span>
            <?php endif; ?>
          </div>

          <hr class="my-4 opacity-50">

          <div class="row g-3 mb-4">
            <div class="col-6">
              <span class="text-muted small d-block">Autore</span>
              <span class="fw-semibold"><?= safe_string($annuncio['autore'] ?? '-') ?></span>
            </div>
            <div class="col-6">
              <span class="text-muted small d-block">Editore</span>
              <span class="fw-semibold"><?= safe_string($annuncio['editore'] ?? '-') ?></span>
            </div>
            <div class="col-12">
              <span class="text-muted small d-block">Codice ISBN</span>
              <span class="fw-bold font-monospace text-primary"><?= safe_string($annuncio['isbn']) ?></span>
            </div>
          </div>

          <!-- Azioni -->
          <div class="d-grid gap-2">
            <?php if (!isset($_SESSION['id_studente'])): ?>
              <a href="index.php?page=login&action=index" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow-sm">
                ACCEDI PER ACQUISTARE
              </a>
            <?php elseif ($isProprietario): ?>
              <a href="index.php?page=annunci&action=uploadForm&id=<?= (int)$annuncio['id_annuncio'] ?>" class="btn btn-outline-primary rounded-pill py-2">
                <i class="bi bi-camera me-2"></i>Gestisci Immagini
              </a>
              <form method="POST" action="index.php?page=annunci&action=elimina" onsubmit="return confirm('Sei sicuro?')">
                <input type="hidden" name="id_annuncio" value="<?= (int)$annuncio['id_annuncio'] ?>">
                <button class="btn btn-link text-danger w-100 mt-2 small">Elimina annuncio</button>
              </form>
            <?php elseif ($annuncio['stato'] === 'disponibile'): ?>
              <form method="POST" action="index.php?page=annunci&action=acquista">
                <input type="hidden" name="id_annuncio" value="<?= (int)$annuncio['id_annuncio'] ?>">
                <button class="btn btn-primary btn-lg rounded-pill py-3 fw-bold w-100 shadow">
                  COMPRA ADESSO
                </button>
              </form>
            <?php else: ?>
              <button class="btn btn-secondary btn-lg rounded-pill py-3 disabled w-100">NON DISPONIBILE</button>
            <?php endif; ?>
          </div>
        </div>

        <!-- Card Scambio -->
        <div class="card info-card p-4">
          <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt text-primary me-2"></i>Luogo e Orario</h6>
          <div class="d-flex flex-column gap-3">
            <div class="d-flex align-items-center">
              <div class="bg-light p-2 rounded-circle me-3"><i class="bi bi-shop"></i></div>
              <div>
                <span class="text-muted small d-block">Punto di incontro</span>
                <span class="fw-bold text-dark"><?= safe_string($annuncio['luogo_scambio']) ?></span>
              </div>
            </div>
            <div class="d-flex align-items-center">
              <div class="bg-light p-2 rounded-circle me-3"><i class="bi bi-calendar-event"></i></div>
              <div>
                <span class="text-muted small d-block">Data prevista</span>
                <span class="fw-bold text-dark">
                  <?= date('d M Y \a\l\l\e H:i', strtotime($annuncio['data_ora_scambio'])) ?>
                </span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  function updateThumbs(el) {
    document.querySelectorAll('.thumb-nav img').forEach(t => t.classList.remove('active', 'opacity-100'));
    document.querySelectorAll('.thumb-nav img').forEach(t => t.classList.add('opacity-50'));
    el.classList.add('active', 'opacity-100');
    el.classList.remove('opacity-50');
  }

  const carouselEl = document.getElementById('<?= $carouselId ?>');
  if (carouselEl) {
    carouselEl.addEventListener('slid.bs.carousel', event => {
      const index = event.to;
      const thumbs = document.querySelectorAll('.thumb-nav img');
      if (thumbs[index]) updateThumbs(thumbs[index]);
    });
  }
</script>