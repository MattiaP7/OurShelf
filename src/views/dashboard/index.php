<?php
// views/dashboard/index.php
// Variabili disponibili:
//   $inVendita       (array) — annunci attivi del venditore
//   $libriVenduti    (array) — annunci conclusi del venditore
//   $libriAcquistati (array) — libri comprati dallo studente
?>

<div class="mt-4 mb-2">
  <h2 class="fw-bold mb-0">La mia area personale</h2>
  <p class="text-muted">
    Ciao, <strong>
      <?= safe_string($_SESSION['nome_completo']) ?>
    </strong>
    ! Ecco un riepilogo delle tue attività.
  </p>
</div>


<!-- Statistiche rapide -->
<div class="row g-3 mb-4">
  <div class="col-sm-4">
    <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100">
      <div class="display-6 fw-bold text-primary mb-1"><?= count($inVendita) ?></div>
      <div class="text-muted small">In vendita</div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100">
      <div class="display-6 fw-bold text-success mb-1"><?= count($libriVenduti) ?></div>
      <div class="text-muted small">Venduti</div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100">
      <div class="display-6 fw-bold text-info mb-1"><?= count($libriAcquistati) ?></div>
      <div class="text-muted small">Acquistati</div>
    </div>
  </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs border-0 mb-4" id="dashTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active fw-semibold" id="vendita-tab" data-bs-toggle="tab"
      data-bs-target="#vendita" type="button">
      <i class="bi bi-tag me-1"></i>In vendita
      <?php if (!empty($inVendita)): ?>
        <span class="badge bg-primary rounded-pill ms-1"><?= count($inVendita) ?></span>
      <?php endif; ?>
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link fw-semibold" id="venduti-tab" data-bs-toggle="tab"
      data-bs-target="#venduti" type="button">
      <i class="bi bi-check2-circle me-1"></i>Venduti
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link fw-semibold" id="acquistati-tab" data-bs-toggle="tab"
      data-bs-target="#acquistati" type="button">
      <i class="bi bi-bag-check me-1"></i>Acquistati
    </button>
  </li>
</ul>

<div class="tab-content" id="dashTabContent">

  <!-- Tab: In vendita -->
  <div class="tab-pane fade show active" id="vendita" role="tabpanel">
    <div class="d-flex justify-content-end mb-3">
      <a href="index.php?page=annunci&action=crea" class="btn btn-primary btn-sm rounded-pill px-3">
        <i class="bi bi-plus-lg me-1"></i> Nuovo annuncio
      </a>
    </div>
    <?php if (empty($inVendita)): ?>
      <div class='text-center py-5'>
        <i class='bi bi-tag display-3 text-muted opacity-50'></i>
        <h6 class='mt-3 fw-semibold'>Nessun libro in vendita</h6>
        <p class='text-muted small'>Pubblica il tuo primo annuncio!</p>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light small text-uppercase text-muted">
            <tr>
              <th>Libro</th>
              <th>ISBN</th>
              <th>Condizione</th>
              <th class="text-end">Prezzo</th>
              <th class="text-end">Pubblicato</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($inVendita as $a): ?>
              <tr>
                <td>
                  <div class="fw-semibold"><?= safe_string($a['titolo']) ?></div>
                  <div class="text-muted small"><?= safe_string($a['autore']) ?></div>
                </td>
                <td class="text-muted small"><?= safe_string($a['isbn']) ?></td>
                <td>
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
                </td>
                <td class="text-end fw-bold text-primary">€<?= number_format($a['prezzo_vendita'], 2) ?></td>
                <td class="text-end text-muted small"><?= date('d/m/Y', strtotime($a['data_pubblicazione'])) ?></td>
                <td class="text-end">
                  <div class="d-flex gap-1 justify-content-end">
                    <a href="index.php?page=annunci&action=dettaglio&id=<?= (int)$a['id_annuncio'] ?>"
                      class="btn btn-sm btn-outline-primary rounded-3">
                      <i class="bi bi-eye"></i>
                    </a>
                    <form method="POST" action="index.php?page=annunci&action=elimina"
                      onsubmit="return confirm('Rimuovere questo annuncio?')">
                      <input type="hidden" name="id_annuncio" value="<?= (int)$a['id_annuncio'] ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <!-- Tab: Venduti -->
  <div class="tab-pane fade" id="venduti" role="tabpanel">
    <?php if (empty($libriVenduti)): ?>
      <div class='text-center py-5'>
        <i class='bi bi-check2-circle display-3 text-muted opacity-50'></i>
        <h6 class='mt-3 fw-semibold'>Nessun libro venduto</h6>
        <p class='text-muted small'>Le tue vendite concluse appariranno qui.</p>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light small text-uppercase text-muted">
            <tr>
              <th>Libro</th>
              <th>ISBN</th>
              <th>Compratore</th>
              <th class="text-end">Prezzo</th>
              <th class="text-end">Venduto il</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($libriVenduti as $a): ?>

              <tr>
                <td>
                  <div class="fw-semibold text-dark"><?= safe_string($a['titolo']) ?></div>
                  <div class="text-muted small"><?= safe_string($a['autore']) ?></div>
                </td>
                <td class="text-muted small"><?= safe_string($a['isbn']) ?></td>
                <td>
                  <div class="small fw-medium">
                    <i class="bi bi-person-check me-1 text-success"></i>
                    <?= !empty($a['compratore']) ? safe_string($a['compratore']) : '<span class="text-muted fst-italic">N.D.</span>' ?>
                  </div>
                  <?php if (!empty($a['email_compratore'])): ?>
                    <div class="text-muted" style="font-size: 0.75rem;"><?= safe_string($a['email_compratore']) ?></div>
                  <?php endif; ?>
                </td>
                <td class="text-end fw-bold text-success">
                  €<?= number_format($a['prezzo_vendita'], 2) ?>
                </td>
                <td class="text-end text-muted small">
                  <i class="bi bi-calendar-event me-1"></i>
                  <?= !empty($a['data_acquisto']) ? date('d/m/Y', strtotime($a['data_acquisto'])) : '—' ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <!-- Tab: Acquistati -->
  <div class="tab-pane fade" id="acquistati" role="tabpanel">
    <?php if (empty($libriAcquistati)): ?>
      <div class='text-center py-5'>
        <i class='bi bi-bag-check display-3 text-muted opacity-50'></i>
        <h6 class='mt-3 fw-semibold'>Nessun libro acquistato</h6>
        <p class='text-muted small'>I libri che hai comprato appariranno qui.</p>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light small text-uppercase text-muted">
            <tr>
              <th>Libro</th>
              <th>ISBN</th>
              <th>Venditore</th>
              <th class="text-end">Pagato</th>
              <th class="text-end">Acquistato in data</th>
              <th class="text-end">Annulla ordine</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($libriAcquistati as $a): ?>
              <tr>
                <td>
                  <div class="fw-semibold"><?= safe_string($a['titolo']) ?></div>
                  <div class="text-muted small"><?= safe_string($a['autore']) ?></div>
                </td>
                <td class="text-muted small"><?= safe_string($a['isbn']) ?></td>
                <td class="text-muted small"><?= safe_string($a['venditore']) ?></td>
                <td class="text-end fw-bold">€<?= number_format($a['prezzo_vendita'], 2) ?></td>
                <td class="text-end text-muted small">
                  <?= !empty($a['data_acquisto']) ? date('d/m/Y', strtotime($a['data_acquisto'])) : 'N/A' ?>
                </td>
                <td class="text-end">
                  <form method="POST" action="index.php?page=annunci&action=annullaAcquisto"
                    onsubmit="return confirm('Vuoi annullare l\'acquisto? Il libro tornerà disponibile per gli altri studenti.')">
                    <input type="hidden" name="data_acquisto" value="<?= $a['data_acquisto'] ?>">
                    <input type="hidden" name="id_annuncio" value="<?= (int)$a['id_annuncio'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Annulla acquisto">
                      <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

</div>

<?php
// Helper locale per gli empty state
function _empty_state(string $icon, string $titolo, string $desc): string
{
  return "
    <div class='text-center py-5'>
      <i class='bi bi-{$icon} display-3 text-muted opacity-50'></i>
      <h6 class='mt-3 fw-semibold'>{$titolo}</h6>
      <p class='text-muted small'>{$desc}</p>
    </div>
  ";
}
?>