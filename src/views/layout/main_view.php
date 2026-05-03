<?php
// Variabili disponibili:

/** @var array $materie - array delle materie */
/** @var array $annunci - array contentente tutti gli annunci */
/** @var array $condizioni - array con le informazioni sulle condizioni dei libri  */
/** @var array $immagini - array con le immagini dell'annuncio  */

// immagine di stock placeholder per tutti gli annunci senza immagini
define('STOCK_IMG', 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=600&q=80');
?>

<div class="container py-4">
  <div class="row g-4">

    <!-- ================================================
         COLONNA SINISTRA — Elenco annunci
         ================================================ -->
    <div class="col-md-8">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="fw-bold mb-0">Annunci disponibili
          <span class="badge bg-primary rounded-pill ms-1 fs-6"><?= count($annunci) ?></span>
        </h4>
        <?php if (!empty($_SESSION['id_studente'])): ?>
          <a href="index.php?page=annunci&action=crea" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Pubblica
          </a>
        <?php endif; ?>
      </div>

      <?php if (empty($annunci)): ?>
        <div class="text-center py-5 text-muted">
          <i class="bi bi-search display-3 opacity-50"></i>
          <h6 class="mt-3">Nessun annuncio trovato</h6>
          <p class="small">Prova a modificare i filtri oppure torna più tardi.</p>
        </div>

      <?php else: ?>
        <div class="row g-3">
          <?php foreach ($annunci as $a):
            $isProprietario = !empty($_SESSION['id_studente']) &&
              (int)$_SESSION['id_studente'] === (int)$a['id_venditore'];
          ?>
            <div class="col-sm-6 col-lg-4">
              <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                <div style="height:160px;overflow:hidden;position:relative;">
                  <?php if (empty($a['foto'])): ?>
                    <!-- Se non ci sono foto, usa lo stock -->
                    <img src="<?= STOCK_IMG ?>" alt="Copertina libro"
                      class="w-100 h-100" style="object-fit:cover;">
                  <?php else: ?>
                    <!-- Prendi la prima immagine dell'annuncio specifico -->
                    <img src="<?= APP_BASE_URL ?>/public/uploads/annunci/<?= $a['foto'] ?>"
                      alt="Foto libro"
                      class="w-100 h-100" style="object-fit:cover;">
                  <?php endif; ?>
                  <!-- Badge condizione sovrapposto -->
                  <?php
                  $badgeClass = '';
                  switch ($a['condizione']) {
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
                    <?= safe_string($a['condizione']) ?>
                  </span>
                  <?php if ($isProprietario): ?>
                    <span class="position-absolute top-0 start-0 m-2 badge bg-dark rounded-pill small">
                      <i class="bi bi-person-fill me-1"></i>Tuo
                    </span>
                  <?php endif; ?>
                </div>

                <div class="card-body d-flex flex-column p-3">
                  <!-- Materia -->
                  <span class="badge bg-primary-subtle text-primary rounded-pill small mb-1 align-self-start">
                    <?= safe_string($a['materia']) ?>
                  </span>

                  <h6 class="fw-bold mb-1 lh-sm" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    <?= safe_string($a['titolo']) ?>
                  </h6>
                  <p class="text-muted small mb-0">
                    <i class="bi bi-person me-1"></i><?= safe_string($a['autore']) ?>
                  </p>

                  <?php if (!empty($a['isbn'])): ?>
                    <span class="badge bg-light text-dark border mt-2 align-self-start" style="font-size:0.7rem;">
                      ISBN: <?= safe_string($a['isbn']) ?>
                    </span>
                  <?php endif; ?>

                  <?php if (!empty($a['descrizione'])): ?>
                    <p class="text-muted small mt-2 mb-0 fst-italic"
                      style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                      "<?= safe_string($a['descrizione']) ?>"
                    </p>
                  <?php endif; ?>

                  <div class="mt-auto pt-3">
                    <div class="d-flex align-items-baseline gap-2 mb-2">
                      <span class="fs-5 fw-bold text-primary">
                        €<?= number_format($a['prezzo_vendita'], 2) ?>
                      </span>

                      <?php if (!empty($a['prezzo_listino']) && $a['prezzo_listino'] > 0):
                        $risparmio = 100 - round(($a['prezzo_vendita'] / $a['prezzo_listino']) * 100);
                      ?>
                        <span class="text-muted text-decoration-line-through small">
                          €<?= number_format($a['prezzo_listino'], 2) ?>
                        </span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.7rem;">
                          -<?= $risparmio ?>%
                        </span>
                      <?php endif; ?>
                    </div>

                    <a href="index.php?page=annunci&action=dettaglio&id=<?= (int)$a['id_annuncio'] ?>"
                      class="btn btn-outline-primary btn-sm rounded-3 w-100">
                      <i class="bi bi-eye me-1"></i> Dettaglio
                    </a>
                  </div>

                </div>

              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- ================================================
         COLONNA DESTRA — Form filtro/ricerca
         ================================================ -->
    <div class="col-md-4">
      <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top:80px; z-index: 10;">
        <h5 class="fw-bold mb-1">
          <i class="bi bi-funnel text-primary me-1"></i> Cerca il libro giusto
        </h5>
        <p class="text-muted small mb-3">Filtra gli annunci per trovare quello che ti serve.</p>

        <form method="GET" action="index.php">
          <input type="hidden" name="page" value="home">
          <input type="hidden" name="action" value="index">

          <div class="mb-3">
            <label class="form-label fw-semibold small">ISBN</label>
            <input class="form-control rounded-3" type="text" name="isbn"
              value="<?= safe_string($_GET['isbn'] ?? '') ?>"
              placeholder="Es. 9788808699862">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Titolo</label>
            <input class="form-control rounded-3" type="text" name="titolo"
              value="<?= safe_string($_GET['titolo'] ?? '') ?>"
              placeholder="Es. Matematica a colori">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Materia</label>
            <select name="materia" class="form-select rounded-3">
              <option value="">Tutte</option>
              <?php foreach ($materie as $m): ?>
                <option value="<?= safe_string($m) ?>"
                  <?= (($_GET['materia'] ?? '') === $m) ? 'selected' : '' ?>>
                  <?= safe_string($m) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Editore</label>
            <input class="form-control rounded-3" type="text" name="editore"
              value="<?= safe_string($_GET['editore'] ?? '') ?>"
              placeholder="Es. Zanichelli">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Condizione</label>
            <select name="condizione" class="form-select rounded-3">
              <option value="" disabled selected>Seleziona la condizione del libro</option>
              <?php foreach ($condizioni as $condition): ?>
                <option
                  value="<?= safe_string($condition) ?>"
                  <?= (($_GET['condizione'] ?? '') === $condition) ? 'selected' : '' ?>>
                  <?= safe_string($condition) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="row g-2 mb-4">
            <div class="col-6">
              <label class="form-label fw-semibold small">Prezzo min (€)</label>
              <input type="number" name="prezzo_min" class="form-control rounded-3"
                value="<?= (float)($_GET['prezzo_min'] ?? 0) ?: '' ?>"
                min="0" step="0.5" placeholder="0">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold small">Prezzo max (€)</label>
              <input type="number" name="prezzo_max" class="form-control rounded-3"
                value="<?= (float)($_GET['prezzo_max'] ?? 0) ?: '' ?>"
                min="0" step="0.5" placeholder="∞">
            </div>
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary rounded-3 fw-semibold">
              <i class="bi bi-search me-1"></i> Cerca
            </button>
            <a href="index.php?page=home&action=index" class="btn btn-outline-secondary rounded-3">
              <i class="bi bi-x-lg me-1"></i> Azzera filtri
            </a>
          </div>

        </form>
      </div>
    </div>

  </div>
</div>