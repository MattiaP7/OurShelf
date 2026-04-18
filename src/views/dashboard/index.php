<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
    <div>
      <h1 class="fw-bold mb-0">La tua Dashboard</h1>
      <p class="text-muted mb-0">Benvenuto, <span class="fw-semibold text-primary"><?= safe_string($_SESSION['email']) ?></span></p>
    </div>
    <a href="index.php?page=annunci&action=crea" class="btn btn-success rounded-pill px-4 shadow-sm">
      <i class="bi bi-plus-lg me-2"></i>Vendi un libro
    </a>
  </div>

  <div class="row g-4">

    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="card-title mb-0 d-flex align-items-center">
            <i class="bi bi-tag text-warning me-2 fs-4"></i> In vendita
          </h5>
        </div>
        <div class="card-body p-0">
          <?php if (empty($inVendita)): ?>
            <div class="p-4 text-center text-muted">
              <i class="bi bi-info-circle d-block mb-2 fs-2"></i>
              Nessun annuncio attivo
            </div>
          <?php else: ?>
            <div class="list-group list-group-flush">
              <?php foreach ($inVendita as $annuncio): ?>
                <div class="list-group-item p-3 border-0 border-bottom border-light">
                  <div class="d-flex justify-content-between align-items-start">
                    <div>
                      <h6 class="mb-1 fw-bold"><?= safe_string($annuncio['titolo']) ?></h6>
                      <span class="badge bg-light text-dark border"><?= number_format($annuncio['prezzo'], 2) ?> €</span>
                    </div>
                    <form action="index.php?page=annunci&action=elimina" method="POST" onsubmit="return confirm('Vuoi davvero rimuovere questo annuncio?')">
                      <input type="hidden" name="id_annuncio" value="<?= $annuncio['id_annuncio'] ?>">
                      <button class="btn btn-sm btn-outline-danger border-0">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="card-title mb-0 d-flex align-items-center">
            <i class="bi bi-cart-check text-success me-2 fs-4"></i> Da ritirare
          </h5>
        </div>
        <div class="card-body p-0">
          <?php if (empty($libriAcquistati)): ?>
            <div class="p-4 text-center text-muted">
              <i class="bi bi-bag-x d-block mb-2 fs-2"></i>
              Nessun acquisto
            </div>
          <?php else: ?>
            <div class="list-group list-group-flush">
              <?php foreach ($libriAcquistati as $annuncio): ?>
                <div class="list-group-item p-3 border-0 border-bottom border-light">
                  <h6 class="mb-1 fw-bold"><?= safe_string($annuncio['titolo']) ?></h6>
                  <div class="small text-muted">
                    <i class="bi bi-geo-alt me-1"></i> <?= safe_string($annuncio['nome_luogo']) ?><br>
                    <i class="bi bi-clock me-1"></i> <?= date('d/m H:i', strtotime($annuncio['data_ora_scambio'])) ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="card-title mb-0 d-flex align-items-center">
            <i class="bi bi-clock-history text-primary me-2 fs-4"></i> Venduti
          </h5>
        </div>
        <div class="card-body p-0">
          <?php if (empty($libriVenduti)): ?>
            <div class="p-4 text-center text-muted">
              <i class="bi bi-hourglass-split d-block mb-2 fs-2"></i>
              Ancora nessuna vendita
            </div>
          <?php else: ?>
            <div class="list-group list-group-flush">
              <?php foreach ($libriVenduti as $annuncio): ?>
                <div class="list-group-item p-3 border-0 border-bottom border-light opacity-75">
                  <h6 class="mb-1 fw-bold text-decoration-line-through"><?= safe_string($annuncio['titolo']) ?></h6>
                  <span class="badge bg-success-subtle text-success">Venduto per <?= number_format($annuncio['prezzo'], 2) ?> €</span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</div>

<style>
  /* Aggiungi un tocco di stile extra */
  .card {
    border-radius: 15px;
  }

  .list-group-item:last-child {
    border-bottom: 0 !important;
  }

  .btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
  }

  .shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
  }
</style>