<?php
// OurShelf/src/views/dashboard/index.php
// Variabili dal controller:

/** @var array $inVendita       — annunci disponibili + scaduti del venditore */
/** @var array $libriVenduti    — annunci conclusi (venduto) */
/** @var array $libriAcquistati — libri comprati dallo studente */
/** @var int   $n_scaduti    — quanti annunci sono scaduti */
/** @var int   $n_disponibili — quanti annunci sono attivi */

$nomeUtente = safe_string($_SESSION['nome'] ?? $_SESSION['nome_completo'] ?? 'Studente');
?>

<div class="mt-4 mb-3">
  <h2 class="fw-bold mb-0">La mia area personale</h2>
  <p class="text-muted mb-0">
    Ciao, <strong><?= $nomeUtente ?></strong>! Ecco un riepilogo delle tue attività.
  </p>
</div>

<!-- ══ AVVISO ANNUNCI SCADUTI (globale, sopra tutto) ═══════════════════════════ -->
<?php if ($n_scaduti > 0): ?>
  <div class="alert alert-warning d-flex align-items-center gap-3 rounded-4 mb-4 shadow-sm">
    <i class="bi bi-clock-history fs-4 flex-shrink-0 text-warning"></i>
    <div>
      <div class="fw-bold">
        <?= $n_scaduti === 1
          ? 'Hai 1 annuncio scaduto'
          : "Hai {$n_scaduti} annunci scaduti" ?>
      </div>
      <div class="small text-muted">
        La data di scambio è passata. Vai nella tab
        <strong>In vendita</strong> e clicca
        <i class="bi bi-arrow-clockwise"></i> per impostare una nuova data e
        riportarli in bacheca.
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- ══ STATISTICHE ═════════════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

  <div class="col-6 col-sm-3">
    <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100">
      <div class="display-6 fw-bold text-primary mb-1"><?= $n_disponibili ?></div>
      <div class="text-muted small">In vendita</div>
    </div>
  </div>

  <div class="col-6 col-sm-3">
    <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100
                <?= $n_scaduti > 0 ? 'border border-warning' : '' ?>">
      <div class="display-6 fw-bold text-warning mb-1"><?= $n_scaduti ?></div>
      <div class="text-muted small">Scaduti</div>
    </div>
  </div>

  <div class="col-6 col-sm-3">
    <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100">
      <div class="display-6 fw-bold text-success mb-1"><?= count($libriVenduti) ?></div>
      <div class="text-muted small">Venduti</div>
    </div>
  </div>

  <div class="col-6 col-sm-3">
    <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100">
      <div class="display-6 fw-bold text-info mb-1"><?= count($libriAcquistati) ?></div>
      <div class="text-muted small">Acquistati</div>
    </div>
  </div>

</div>

<!-- ══ TABS ════════════════════════════════════════════════════════════════════ -->
<ul class="nav nav-tabs border-0 mb-4" id="dashTab" role="tablist">

  <li class="nav-item" role="presentation">
    <button class="nav-link active fw-semibold" id="vendita-tab"
      data-bs-toggle="tab" data-bs-target="#vendita" type="button">
      <i class="bi bi-tag me-1"></i>In vendita
      <?php if (!empty($inVendita)): ?>
        <span class="badge bg-primary rounded-pill ms-1"><?= count($inVendita) ?></span>
      <?php endif; ?>
      <?php if ($n_scaduti > 0): ?>
        <span class="badge bg-warning text-dark rounded-pill ms-1">
          <?= $n_scaduti ?> <i class="bi bi-clock-history" style="font-size:.7rem;"></i>
        </span>
      <?php endif; ?>
    </button>
  </li>

  <li class="nav-item" role="presentation">
    <button class="nav-link fw-semibold" id="venduti-tab"
      data-bs-toggle="tab" data-bs-target="#venduti" type="button">
      <i class="bi bi-check2-circle me-1"></i>Venduti
      <?php if (!empty($libriVenduti)): ?>
        <span class="badge bg-success rounded-pill ms-1"><?= count($libriVenduti) ?></span>
      <?php endif; ?>
    </button>
  </li>

  <li class="nav-item" role="presentation">
    <button class="nav-link fw-semibold" id="acquistati-tab"
      data-bs-toggle="tab" data-bs-target="#acquistati" type="button">
      <i class="bi bi-bag-check me-1"></i>Acquistati
      <?php if (!empty($libriAcquistati)): ?>
        <span class="badge bg-info rounded-pill ms-1"><?= count($libriAcquistati) ?></span>
      <?php endif; ?>
    </button>
  </li>

</ul>

<div class="tab-content" id="dashTabContent">

  <!-- ══════════════════════════════════════════════════════════
       TAB: IN VENDITA  (disponibile + scaduto)
       ══════════════════════════════════════════════════════════ -->
  <div class="tab-pane fade show active" id="vendita" role="tabpanel">

    <div class="d-flex justify-content-end mb-3">
      <a href="index.php?page=annunci&action=crea"
        class="btn btn-primary btn-sm rounded-pill px-3">
        <i class="bi bi-plus-lg me-1"></i>Nuovo annuncio
      </a>
    </div>

    <?php if (empty($inVendita)): ?>
      <div class="text-center py-5">
        <i class="bi bi-tag display-3 text-muted opacity-50"></i>
        <h6 class="mt-3 fw-semibold">Nessun libro in vendita</h6>
        <p class="text-muted small">Pubblica il tuo primo annuncio!</p>
      </div>

    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light small text-uppercase text-muted">
            <tr>
              <th>Libro</th>
              <th>ISBN</th>
              <th>Condizione</th>
              <th>Stato</th>
              <th class="text-end">Prezzo</th>
              <th class="text-end">Pubblicato</th>
              <th class="text-end">Azioni</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($inVendita as $a):
              $isScaduto = $a['stato'] === 'scaduto';
              $badgeCondizione = match ($a['condizione']) {
                'Ottime condizioni'      => 'bg-success-subtle text-success',
                'Buone condizioni'       => 'bg-warning-subtle text-warning-emphasis',
                'Condizioni accettabili' => 'bg-info-subtle text-info-emphasis',
                'Danneggiato'            => 'bg-danger-subtle text-danger',
                default                  => 'bg-secondary-subtle text-secondary',
              };
            ?>
              <tr class="<?= $isScaduto ? 'table-warning' : '' ?>">

                <td>
                  <div class="fw-semibold <?= $isScaduto ? 'text-muted' : '' ?>">
                    <?= safe_string($a['titolo']) ?>
                  </div>
                  <div class="text-muted small"><?= safe_string($a['autore']) ?></div>
                </td>

                <td class="text-muted small font-monospace">
                  <?= safe_string($a['isbn']) ?>
                </td>

                <td>
                  <span class="badge rounded-pill <?= $badgeCondizione ?> small">
                    <?= safe_string($a['condizione']) ?>
                  </span>
                </td>

                <td>
                  <?php if ($isScaduto): ?>
                    <span class="badge bg-warning text-dark rounded-pill small">
                      <i class="bi bi-clock-history me-1"></i>Scaduto
                    </span>
                  <?php else: ?>
                    <span class="badge bg-success-subtle text-success rounded-pill small">
                      <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle;"></i>Attivo
                    </span>
                  <?php endif; ?>
                </td>

                <td class="text-end fw-bold <?= $isScaduto ? 'text-muted' : 'text-primary' ?>">
                  €<?= number_format($a['prezzo_vendita'], 2) ?>
                </td>

                <td class="text-end text-muted small">
                  <?= date('d/m/Y', strtotime($a['data_pubblicazione'])) ?>
                </td>

                <td class="text-end">
                  <div class="d-flex gap-1 justify-content-end">

                    <a href="index.php?page=annunci&action=dettaglio&id=<?= (int)$a['id_annuncio'] ?>"
                      class="btn btn-sm btn-outline-primary rounded-3"
                      title="Visualizza">
                      <i class="bi bi-eye"></i>
                    </a>

                    <a href="index.php?page=annunci&action=modifica&id=<?= (int)$a['id_annuncio'] ?>"
                      class="btn btn-sm <?= $isScaduto ? 'btn-warning' : 'btn-outline-secondary' ?> rounded-3"
                      title="<?= $isScaduto ? 'Rinnova: imposta nuova data' : 'Modifica annuncio' ?>">
                      <i class="bi bi-<?= $isScaduto ? 'arrow-clockwise' : 'pencil' ?>"></i>
                    </a>

                    <form method="POST" action="index.php?page=annunci&action=elimina"
                      onsubmit="return confirm('Eliminare definitivamente questo annuncio? Verranno cancellate anche le foto.')">
                      <input type="hidden" name="id_annuncio" value="<?= (int)$a['id_annuncio'] ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Elimina">
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

  <!-- ══════════════════════════════════════════════════════════
       TAB: VENDUTI
       ══════════════════════════════════════════════════════════ -->
  <div class="tab-pane fade" id="venduti" role="tabpanel">

    <?php if (empty($libriVenduti)): ?>
      <div class="text-center py-5">
        <i class="bi bi-check2-circle display-3 text-muted opacity-50"></i>
        <h6 class="mt-3 fw-semibold">Nessun libro venduto</h6>
        <p class="text-muted small">Le tue vendite concluse appariranno qui.</p>
      </div>

    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light small text-uppercase text-muted">
            <tr>
              <th>Libro</th>
              <th>ISBN</th>
              <th>Compratore</th>
              <th class="text-end">Incassato</th>
              <th class="text-end">Venduto il</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($libriVenduti as $a): ?>
              <tr>
                <td>
                  <div class="fw-semibold"><?= safe_string($a['titolo']) ?></div>
                  <div class="text-muted small"><?= safe_string($a['autore']) ?></div>
                </td>
                <td class="text-muted small font-monospace"><?= safe_string($a['isbn']) ?></td>
                <td>
                  <div class="small fw-medium">
                    <i class="bi bi-person-check me-1 text-success"></i>
                    <?= !empty($a['compratore'])
                      ? safe_string($a['compratore'])
                      : '<span class="text-muted fst-italic">N.D.</span>' ?>
                  </div>
                  <?php if (!empty($a['email_compratore'])): ?>
                    <div class="text-muted" style="font-size:.75rem;">
                      <?= safe_string($a['email_compratore']) ?>
                    </div>
                  <?php endif; ?>
                </td>
                <td class="text-end fw-bold text-success">
                  €<?= number_format($a['prezzo_vendita'], 2) ?>
                </td>
                <td class="text-end text-muted small">
                  <i class="bi bi-calendar-event me-1"></i>
                  <?= !empty($a['data_acquisto'])
                    ? date('d/m/Y H:i', strtotime($a['data_acquisto']))
                    : '—' ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <!-- ══════════════════════════════════════════════════════════
       TAB: ACQUISTATI
       ══════════════════════════════════════════════════════════ -->
  <div class="tab-pane fade" id="acquistati" role="tabpanel">

    <?php if (empty($libriAcquistati)): ?>
      <div class="text-center py-5">
        <i class="bi bi-bag-check display-3 text-muted opacity-50"></i>
        <h6 class="mt-3 fw-semibold">Nessun libro acquistato</h6>
        <p class="text-muted small">I libri che hai comprato appariranno qui.</p>
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
              <th class="text-end">Acquistato il</th>
              <th class="text-end">Annulla</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($libriAcquistati as $a):
              // Calcoliamo se è ancora annullabile (entro 24h)
              $dataAcquisto    = new DateTime($a['data_acquisto'] ?? 'now');
              $diff            = (new DateTime())->diff($dataAcquisto);
              $annullabile     = $diff->days < 1 && $diff->invert === 0;
              // invert=0 significa che dataAcquisto è nel passato
              // days < 1 significa meno di 24 ore fa
            ?>
              <tr>
                <td>
                  <div class="fw-semibold"><?= safe_string($a['titolo']) ?></div>
                  <div class="text-muted small"><?= safe_string($a['autore']) ?></div>
                </td>
                <td class="text-muted small font-monospace"><?= safe_string($a['isbn']) ?></td>
                <td class="text-muted small"><?= safe_string($a['venditore']) ?></td>
                <td class="text-end fw-bold">€<?= number_format($a['prezzo_vendita'], 2) ?></td>
                <td class="text-end text-muted small">
                  <?= !empty($a['data_acquisto'])
                    ? date('d/m/Y H:i', strtotime($a['data_acquisto']))
                    : 'N/A' ?>
                </td>
                <td class="text-end">
                  <?php if ($annullabile): ?>
                    <form method="POST" action="index.php?page=annunci&action=annullaAcquisto"
                      onsubmit="return confirm('Vuoi annullare l\'acquisto? Il libro tornerà disponibile.')">
                      <input type="hidden" name="data_acquisto" value="<?= safe_string($a['data_acquisto']) ?>">
                      <input type="hidden" name="id_annuncio" value="<?= (int)$a['id_annuncio'] ?>">
                      <button type="submit"
                        class="btn btn-sm btn-outline-danger rounded-3"
                        title="Annulla acquisto (entro 24h)">
                        <i class="bi bi-arrow-counterclockwise"></i>
                      </button>
                    </form>
                  <?php else: ?>
                    <button class="btn btn-sm btn-outline-secondary rounded-3 disabled"
                      title="Annullamento non più disponibile (oltre 24h)">
                      <i class="bi bi-lock text-muted"></i>
                    </button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

</div>