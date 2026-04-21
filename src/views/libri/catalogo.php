<?php
$materia = [];
foreach ($libri as $l) {
  $materia[$l['materia']][] = $l;
}
$materie_sort = array_column($libri, 'materia');
array_multisort($materie_sort, SORT_ASC, $libri);

?>

<div class="mt-4">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h2 class="fw-bold mb-0">I libri della mia classe</h2>
      <p class="text-muted small mb-0">Elenco dei testi adottati per l'anno scolastico corrente</p>
    </div>
    <a href="index.php?page=annunci&action=crea" class="btn btn-primary rounded-pill px-4 shadow-sm">
      <i class="bi bi-tag me-1"></i> Metti in vendita
    </a>
  </div>

  <?php if (empty($libri)): ?>
    <div class="text-center py-5">
      <i class="bi bi-book display-3 text-muted opacity-50"></i>
      <h5 class="mt-3 text-muted">Nessun libro trovato per la tua classe</h5>
    </div>

  <?php else: ?>
    <?php foreach ($perMateria as $materia => $libriMateria): ?>
      <div class="mb-4">
        <h6 class="text-uppercase text-muted fw-bold small mb-3 d-flex align-items-center gap-2">
          <span class="badge bg-primary-subtle text-primary rounded-pill"><?= safe_string($materia) ?></span>
          <span class="text-muted"><?= count($libriMateria) ?> libro<?= count($libriMateria) > 1 ? 'i' : '' ?></span>
        </h6>
        <div class="row g-3">
          <?php foreach ($libriMateria as $l): ?>
            <div class="col-md-6 col-lg-4">
              <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="d-flex flex-column h-100">
                  <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1 lh-sm"><?= safe_string($l['titolo']) ?></h6>
                    <p class="text-muted small mb-2">
                      <i class="bi bi-person me-1"></i><?= safe_string($l['autore']) ?>
                    </p>
                    <div class="d-flex flex-wrap gap-1 mb-2">
                      <?php if (!empty($l['volume'])): ?>
                        <span class="badge bg-light text-dark border small">Vol. <?= safe_string($l['volume']) ?></span>
                      <?php endif; ?>
                      <?php if (!empty($l['editore'])): ?>
                        <span class="badge bg-light text-dark border small"><?= safe_string($l['editore']) ?></span>
                      <?php endif; ?>
                      <?php if (!empty($l['anno_scolastico'])): ?>
                        <span class="badge bg-light text-dark border small"><?= safe_string($l['anno_scolastico']) ?></span>
                      <?php endif; ?>
                    </div>
                    <div class="text-muted small">
                      <i class="bi bi-upc-scan me-1"></i><?= safe_string($l['isbn']) ?>
                    </div>
                  </div>
                  <div class="mt-3">
                    <a href="index.php?page=annunci&action=crea&isbn=<?= urlencode($l['isbn']) ?>"
                      class="btn btn-outline-primary btn-sm rounded-3 w-100">
                      <i class="bi bi-tag me-1"></i> Vendi questo libro
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>