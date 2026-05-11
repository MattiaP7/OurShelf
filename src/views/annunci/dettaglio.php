<?php
defined("APP") or die("Accesso negato");

/** @var array $annuncio */
/** @var array $immagini */
/** @var string $avatar_venditore foto profilo del venditore*/

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

$_navAvatarUrl = '';
if (!empty($avatar_venditore)) {
  $_navAvatarUrl = APP_BASE_URL . '/public/uploads/users/' . $avatar_venditore;
}
?>

<style>
  .main-carousel .carousel-item img {
    height: 450px;
    object-fit: contain;
    background-color: #f8f9fa;
    cursor: zoom-in;
    /* Cursore per far capire che è cliccabile */
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
    <a href="index.php?page=home&action=index" class="btn btn-link text-decoration-none p-0 text-muted">
      <i class="bi bi-chevron-left"></i> Torna alla bacheca
    </a>
  </nav>

  <div class="row g-4">

    <!-- ══ COLONNA SINISTRA: GALLERIA IMMAGINI ════════════════════════════════ -->
    <div class="col-lg-7">
      <div class="card info-card overflow-hidden">
        <div id="<?= $carouselId ?>" class="carousel slide main-carousel" data-bs-ride="false">

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
                    class="d-block w-100" alt="Libro"
                    data-bs-toggle="modal" data-bs-target="#modalZoom" onclick="document.getElementById('imgZoom').src = this.src">
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

        <?php if ($hasFoto): ?>
          <div class="card-footer bg-white border-0 p-3">
            <div class="d-flex gap-2 justify-content-center thumb-nav">
              <?php foreach ($immagini as $i => $img): ?>
                <img src="<?= APP_BASE_URL ?>/public/uploads/annunci/<?= safe_string($img['nome_file']) ?>"
                  class="rounded-3 <?= $i === 0 ? 'active' : 'opacity-50' ?>"
                  data-bs-target="#<?= $carouselId ?>" data-bs-slide-to="<?= $i ?>"
                  onclick="updateThumbs(this)">
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <?php if (!$hasFoto): ?>
          <h3 class="text-center mt-3">L'utente non ha caricato nessuna immagine!</h3>
          <h4 class="text-center text-muted mb-3">Questa immagine è un placeholder</h4>
        <?php endif; ?>
      </div>

      <!-- Descrizione / Note -->
      <div class="mt-4">
        <?php if (!empty($annuncio['descrizione'])): ?>
          <h5 class="fw-bold"><i class="bi bi-card-text me-2"></i>Descrizione del venditore</h5>
          <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <h6 class="fw-bold small text-muted text-uppercase mb-2" style="font-size:0.75rem; letter-spacing:0.5px;">Note del venditore</h6>
            <p class="mb-0 small fst-italic text-secondary">
              "<?= safe_string($annuncio['descrizione']) ?>"
            </p>
          </div>
        <?php else: ?>
          <h5 class="fw-bold">Nessuna descrizione dal venditore</h5>
        <?php endif; ?>
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

          <div class="mb-4">
            <div class="d-flex align-items-baseline gap-3">
              <span class="display-5 fw-bold text-dark">€<?= number_format($annuncio['prezzo_vendita'], 2) ?></span>

              <?php if (!empty($annuncio['prezzo_listino']) && $annuncio['prezzo_listino'] > 0): ?>
                <span class="text-muted text-decoration-line-through fs-5">€<?= number_format($annuncio['prezzo_listino'], 2) ?></span>
                <span class="badge bg-success-subtle text-success rounded-pill px-2">
                  -<?= 100 - round(($annuncio['prezzo_vendita'] / $annuncio['prezzo_listino']) * 100) ?>%
                </span>
              <?php endif; ?>
            </div>

            <?php if (!empty($annuncio['prezzo_listino']) && $annuncio['prezzo_listino'] > $annuncio['prezzo_vendita']): ?>
              <div class="text-success small fw-medium mt-1">
                <h4>Risparmi €<?= number_format($annuncio['prezzo_listino'] - $annuncio['prezzo_vendita'], 2) ?> rispetto al nuovo</h4>
              </div>
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

          <div class="d-grid gap-2">
            <?php if (!isset($_SESSION['id_studente'])): ?>
              <a href="index.php?page=login&action=index" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow-sm">
                ACCEDI PER ACQUISTARE
              </a>
            <?php elseif ($isProprietario): ?>
              <div class="mt-4">
                <!-- Gruppo Azioni Principali -->
                <div class="row g-2">
                  <div class="col-12">
                    <a href="index.php?page=annunci&action=modifica&id=<?= (int)$annuncio['id_annuncio'] ?>"
                      class="btn btn-primary w-100 rounded-pill py-2 fw-semibold shadow-sm transition-all">
                      <i class="bi bi-pencil-square me-2"></i>Modifica Annuncio
                    </a>
                  </div>
                </div>

                <!-- Separatore con testo discreto -->
                <div class="text-center my-3">
                  <hr class="mb-2 opacity-25">
                </div>

                <!-- Azione di Eliminazione Migliorata -->
                <form method="POST" action="index.php?page=annunci&action=elimina"
                  onsubmit="return confirm('ATTENZIONE: L\'eliminazione è definitiva. Vuoi procedere?')">
                  <input type="hidden" name="id_annuncio" value="<?= (int)$annuncio['id_annuncio'] ?>">

                  <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 btn-sm fw-medium d-flex align-items-center justify-content-center border-2 btn-danger-soft">
                    <i class="bi bi-trash3 me-2"></i>Elimina definitivamente
                  </button>
                </form>
              </div>
            <?php elseif ($annuncio['stato'] === 'scaduto'): ?>
              <div class="alert alert-warning rounded-3 small text-center py-2">
                <i class="bi bi-clock-history me-1"></i>
                La data di scambio è passata. Il venditore può rinnovare l'annuncio.
              </div>
              <button class="btn btn-secondary btn-lg rounded-pill py-3 disabled w-100">
                SCAMBIO SCADUTO
              </button>
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

        <!-- Info Venditore e Scambio -->
        <div class="card info-card p-4">
          <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Dettagli Venditore & Scambio</h6>

          <!-- Venditore -->
          <div class="d-flex align-items-center mb-3">
            <?php if ($_navAvatarUrl): ?>
              <img src="<?= $_navAvatarUrl ?>"
                alt="Avatar"
                class="rounded-circle border"
                style="width:32px;height:32px;object-fit:cover;"
                onerror="this.outerHTML='<i class=\'bi bi-person-circle fs-5\'></i>'">
            <?php else: ?>
              <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                <i class="bi bi-person text-primary"></i>
              </div>
            <?php endif; ?>

            <div>
              <div class="text-muted" style="font-size:0.7rem;">Venditore</div>
              <div class="fw-bold">
                <?php if ($isProprietario): ?>
                  <a href="index.php?page=dashboard"><?= safe_string($annuncio['venditore']) ?></a>
                <?php else: ?>
                  <?= safe_string($annuncio['venditore']) ?>
                <?php endif; ?>
              </div>
              <div class="small text-muted">
                <a href="mailto:<?= safe_string($annuncio['email_venditore']) ?>" class="text-decoration-none">
                  <i class="bi bi-envelope me-1"></i><?= safe_string($annuncio['email_venditore']) ?>
                </a>
              </div>
            </div>
          </div>

          <!-- Luogo -->
          <div class="d-flex align-items-center mb-3">
            <div class="bg-light p-2 rounded-circle me-3"><i class="bi bi-geo-alt"></i></div>
            <div>
              <span class="text-muted small d-block">Punto di incontro</span>
              <span class="fw-bold text-dark"><?= safe_string($annuncio['luogo_scambio']) ?></span>
            </div>
          </div>

          <!-- Data (come originale) -->
          <div class="d-flex align-items-center">
            <div class="bg-light p-2 rounded-circle me-3"><i class="bi bi-calendar-event"></i></div>
            <div>
              <span class="text-muted small d-block">Data prevista</span>
              <span class="fw-bold text-dark">
                <?= date('d/m/Y H:i', strtotime($annuncio['data_ora_scambio'])) ?>
              </span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- MODALE PER INGRANDIMENTO -->
<div class="modal fade" id="modalZoom" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0 text-center position-relative">
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
        <img src="" id="imgZoom" class="img-fluid rounded shadow-lg">
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