<?php
// OurShelf/src/views/annunci/modifica.php
defined("APP") or die("Accesso negato");

/** @var array  $annuncio   Dati dell'annuncio dal DB */
/** @var array  $immagini   Immagini associate (da Immagini_Annunci) */
/** @var array  $libri      Elenco completo libri per la ricerca */
/** @var array  $luoghi     Lista dei luoghi di scambio */
/** @var array  $condizioni Lista delle condizioni possibili */

// Sicurezza: solo il proprietario può accedere
$isProprietario = !empty($_SESSION['id_studente']) &&
  (int) $_SESSION['id_studente'] === (int) $annuncio['proprietario'];

if (!$isProprietario) {
  $_SESSION['errors'][] = "Non sei autorizzato a modificare questo annuncio.";
  header("Location: index.php?page=annunci&action=dettaglio&id=" . (int)$annuncio['id_annuncio']);
  exit;
}

// Troviamo il libro corrente nell'elenco per pre-inizializzare JS
$libroCorrente = null;
foreach ($libri as $l) {
  if ($l['isbn'] === $annuncio['isbn']) {
    $libroCorrente = $l;
    break;
  }
}

// Capisce se l'annuncio è scaduto per mostrare l'avviso data
$isScaduto = $annuncio['stato'] === 'scaduto';

// Trova l'id_luogo corrente (la query ritorna il nome, cerchiamo l'id)
$idLuogoCorrente = 0;
foreach ($luoghi as $l) {
  if ($l['nome'] === $annuncio['luogo_scambio']) {
    $idLuogoCorrente = (int) $l['id_luogo'];
    break;
  }
}
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <!-- Header -->
      <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
          <h2 class="fw-bold mb-0">
            <i class="bi bi-pencil-square text-primary me-2"></i>Modifica Annuncio
          </h2>
          <p class="text-muted small mb-0 mt-1">
            Le modifiche aggiornano l'annuncio immediatamente in bacheca.
          </p>
        </div>
        <a href="index.php?page=annunci&action=dettaglio&id=<?= (int)$annuncio['id_annuncio'] ?>"
          class="btn btn-outline-secondary rounded-pill btn-sm px-3">
          <i class="bi bi-x-lg me-1"></i>Annulla
        </a>
      </div>

      <!-- Avviso scaduto -->
      <?php if ($isScaduto): ?>
        <div class="alert alert-warning rounded-4 d-flex align-items-center gap-3 mb-4 shadow-sm">
          <i class="bi bi-clock-history fs-4 flex-shrink-0"></i>
          <div>
            <div class="fw-bold">Annuncio scaduto</div>
            <div class="small">
              La data di scambio è passata. Imposta una nuova data futura per
              riportare l'annuncio in bacheca.
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Flash errors -->
      <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible rounded-4 shadow-sm mb-4">
          <?php foreach ($_SESSION['errors'] as $e): ?>
            <div><i class="bi bi-exclamation-circle me-1"></i><?= safe_string($e) ?></div>
          <?php endforeach; ?>
          <?php unset($_SESSION['errors']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5">

          <form action="index.php?page=annunci&action=update"
            method="POST"
            id="form-modifica">

            <input type="hidden" name="id_annuncio" value="<?= (int)$annuncio['id_annuncio'] ?>">
            <input type="hidden" id="isbn-hidden" name="isbn"
              value="<?= safe_string($annuncio['isbn']) ?>" required>

            <!-- ══ SEZIONE 1: Libro ════════════════════════════════════════════ -->
            <h5 class="text-uppercase small fw-bold text-primary mb-3" style="letter-spacing:1px;">
              <span class="badge bg-primary rounded-pill me-2">1</span>Libro
            </h5>

            <div class="mb-4 position-relative">
              <label class="form-label fw-semibold">
                Libro in vendita
                <span class="text-muted fw-normal small">— clicca "Cambia" per scegliere un libro diverso</span>
              </label>

              <!-- Search box (nascosta di default) -->
              <div id="search-wrapper" class="d-none mb-2">
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                  </span>
                  <input type="text" id="book-search"
                    class="form-control border-start-0 ps-0 rounded-end-3"
                    placeholder="Cerca per titolo, autore o ISBN..." autocomplete="off">
                </div>
                <div id="search-results"
                  class="list-group shadow mt-1 d-none"
                  style="position:absolute;z-index:1000;width:100%;max-height:220px;overflow-y:auto;">
                </div>
              </div>

              <!-- Card libro selezionato (visibile di default con dati attuali) -->
              <div id="selected-book-card" class="card border-primary bg-primary-subtle">
                <div class="card-body d-flex justify-content-between align-items-center p-3">
                  <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary text-white rounded-3 px-3 py-2">
                      <i class="bi bi-book-half fs-4"></i>
                    </div>
                    <div>
                      <div class="fw-bold text-primary" id="display-titolo">
                        <?= safe_string($annuncio['titolo']) ?>
                      </div>
                      <div class="small text-muted" id="display-info">
                        di <?= safe_string($annuncio['autore']) ?> • ISBN <?= safe_string($annuncio['isbn']) ?>
                      </div>
                    </div>
                  </div>
                  <button type="button"
                    class="btn btn-sm btn-outline-primary rounded-pill px-3"
                    onclick="resetSelezione()">
                    Cambia
                  </button>
                </div>
              </div>
            </div>

            <hr class="my-4 opacity-25">

            <!-- ══ SEZIONE 2: Condizione e Prezzo ═════════════════════════════ -->
            <h5 class="text-uppercase small fw-bold text-primary mb-3" style="letter-spacing:1px;">
              <span class="badge bg-primary rounded-pill me-2">2</span>Condizione e Prezzo
            </h5>

            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Condizione <span class="text-danger">*</span></label>
                <select name="condizione" id="condizione-select" class="form-select rounded-3" required>
                  <?php foreach ($condizioni as $c): ?>
                    <option value="<?= safe_string($c) ?>"
                      <?= $annuncio['condizione'] === $c ? 'selected' : '' ?>>
                      <?= safe_string($c) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Prezzo (€) <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">€</span>
                  <input type="number" step="0.01" min="0.01"
                    name="prezzo_vendita" id="prezzo-input"
                    class="form-control rounded-end-3"
                    value="<?= number_format((float)$annuncio['prezzo_vendita'], 2, '.', '') ?>"
                    required>
                </div>
                <div id="prezzo-feedback" class="mt-2"></div>
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Note sull'usura</label>
                <textarea name="descrizione" class="form-control rounded-3" rows="3"
                  placeholder="Es: sottolineature a matita, copertina leggermente rovinata..."><?= safe_string($annuncio['descrizione'] ?? '') ?></textarea>
              </div>
            </div>

            <hr class="my-4 opacity-25">

            <!-- ══ SEZIONE 3: Scambio ══════════════════════════════════════════ -->
            <h5 class="text-uppercase small fw-bold text-primary mb-3" style="letter-spacing:1px;">
              <span class="badge bg-primary rounded-pill me-2">3</span>Dettagli Scambio
            </h5>

            <div class="row g-3 mb-4">
              <div class="col-md-7">
                <label class="form-label fw-semibold">Luogo <span class="text-danger">*</span></label>
                <select name="id_luogo" class="form-select rounded-3" required>
                  <?php foreach ($luoghi as $l): ?>
                    <option value="<?= (int)$l['id_luogo'] ?>"
                      <?= (int)$l['id_luogo'] === $idLuogoCorrente ? 'selected' : '' ?>>
                      <?= safe_string($l['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-5">
                <label class="form-label fw-semibold">
                  Data e ora
                  <span class="text-danger">*</span>
                  <?php if ($isScaduto): ?>
                    <span class="badge bg-warning text-dark ms-1 small">Nuova data richiesta</span>
                  <?php endif; ?>
                </label>
                <input type="datetime-local"
                  name="data_ora_scambio"
                  id="data-input"
                  class="form-control rounded-3 <?= $isScaduto ? 'border-warning' : '' ?>"
                  min="<?= date('Y-m-d\TH:i') ?>"
                  value="<?= !$isScaduto && !empty($annuncio['data_ora_scambio'])
                            ? date('Y-m-d\TH:i', strtotime($annuncio['data_ora_scambio']))
                            : '' ?>"
                  required>
                <?php if ($isScaduto): ?>
                  <div class="form-text text-warning fw-semibold">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Imposta una data futura per riattivare l'annuncio.
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <hr class="my-4 opacity-25">

            <!-- ══ SEZIONE 4: Foto ════════════════════════════════════════════ -->
            <h5 class="text-uppercase small fw-bold text-primary mb-3" style="letter-spacing:1px;">
              <span class="badge bg-primary rounded-pill me-2">4</span>Foto
              <span class="text-muted fw-normal small">(<?= count($immagini) ?>/3)</span>
            </h5>

            <div class="bg-light rounded-4 p-4 border mb-4">
              <?php if (!empty($immagini)): ?>
                <div class="d-flex gap-3 flex-wrap mb-3">
                  <?php foreach ($immagini as $img): ?>
                    <div class="position-relative" style="width:90px;height:75px;">
                      <img src="<?= APP_BASE_URL ?>/public/uploads/annunci/<?= safe_string($img['nome_file']) ?>"
                        class="w-100 h-100 rounded-3 border shadow-sm"
                        style="object-fit:cover;">
                      <!-- Pulsante elimina singola foto -->
                      <a href="index.php?page=annunci&action=eliminaImmagine&id_img=<?= (int)$img['id_immagine'] ?>&id_annuncio=<?= (int)$annuncio['id_annuncio'] ?>"
                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-0
                                d-flex align-items-center justify-content-center shadow"
                        style="width:22px;height:22px;"
                        onclick="return confirm('Eliminare questa foto?')"
                        title="Elimina foto">
                        <i class="bi bi-x" style="font-size:.7rem;"></i>
                      </a>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <p class="text-muted small mb-3">
                  <i class="bi bi-image me-1"></i>Nessuna foto caricata.
                </p>
              <?php endif; ?>

              <?php if (count($immagini) < 3): ?>
                <a href="index.php?page=annunci&action=uploadForm&id=<?= (int)$annuncio['id_annuncio'] ?>"
                  class="btn btn-outline-primary btn-sm rounded-pill px-3">
                  <i class="bi bi-camera me-1"></i>
                  <?= empty($immagini) ? 'Aggiungi foto' : 'Aggiungi altre foto' ?>
                </a>
              <?php else: ?>
                <a href="index.php?page=annunci&action=uploadForm&id=<?= (int)$annuncio['id_annuncio'] ?>"
                  class="btn btn-outline-secondary btn-sm rounded-pill px-3 disabled">
                  <i class="bi bi-camera me-1"></i>Massimo raggiunto (3/3)
                </a>
              <?php endif; ?>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm py-3">
              <?php if ($isScaduto): ?>
                <i class="bi bi-arrow-clockwise me-2"></i>SALVA E RIATTIVA ANNUNCIO
              <?php else: ?>
                <i class="bi bi-check-circle me-2"></i>SALVA MODIFICHE
              <?php endif; ?>
            </button>

          </form>
        </div>
      </div>

      <!-- Zona Pericolo -->
      <div class="mt-4 p-4 border border-danger border-opacity-25 rounded-4
                  bg-danger bg-opacity-10 d-flex align-items-center justify-content-between">
        <div>
          <h6 class="fw-bold text-danger mb-1">
            <i class="bi bi-exclamation-triangle me-1"></i>Zona Pericolo
          </h6>
          <p class="small text-danger mb-0">
            Eliminare l'annuncio è irreversibile. Verranno cancellate anche tutte le foto.
          </p>
        </div>
        <form method="POST" action="index.php?page=annunci&action=elimina"
          onsubmit="return confirm('Sei sicuro di voler eliminare questo annuncio? L\'operazione è irreversibile.')">
          <input type="hidden" name="id_annuncio" value="<?= (int)$annuncio['id_annuncio'] ?>">
          <button class="btn btn-outline-danger rounded-pill px-4 fw-bold">
            <i class="bi bi-trash me-1"></i>Elimina
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  const LIBRI = <?= json_encode(array_values($libri)) ?>;
  const SOGLIE = {
    'Ottime condizioni': 0.75,
    'Buone condizioni': 0.60,
    'Condizioni accettabili': 0.40,
    'Danneggiato': 0.20
  };

  const searchInput = document.getElementById('book-search');
  const resultsDiv = document.getElementById('search-results');
  const searchWrapper = document.getElementById('search-wrapper');
  const selectedBookCard = document.getElementById('selected-book-card');
  const isbnHidden = document.getElementById('isbn-hidden');
  const condizioneSelect = document.getElementById('condizione-select');
  const prezzoInput = document.getElementById('prezzo-input');
  const prezzoFeedback = document.getElementById('prezzo-feedback');

  // Inizializzato con il libro attuale dell'annuncio
  let libroSelezionato = <?= json_encode($libroCorrente) ?>;

  // ── Ricerca libro ─────────────────────────────────────────────────────────────
  searchInput.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    resultsDiv.innerHTML = '';
    if (q.length < 2) {
      resultsDiv.classList.add('d-none');
      return;
    }

    const filtrati = LIBRI.filter(l =>
      l.titolo.toLowerCase().includes(q) ||
      l.isbn.includes(q) ||
      l.autore.toLowerCase().includes(q)
    ).slice(0, 5);

    if (filtrati.length) {
      filtrati.forEach(l => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'list-group-item list-group-item-action border-0 py-3';
        item.innerHTML = `
        <div class="fw-bold">${l.titolo}</div>
        <div class="small text-muted">${l.autore} | ISBN: ${l.isbn}</div>`;
        item.onclick = () => selezionaLibro(l);
        resultsDiv.appendChild(item);
      });
      resultsDiv.classList.remove('d-none');
    } else {
      resultsDiv.innerHTML = '<div class="list-group-item text-muted small py-3">Nessun risultato.</div>';
      resultsDiv.classList.remove('d-none');
    }
  });

  // Chiudi risultati cliccando fuori
  document.addEventListener('click', e => {
    if (!searchWrapper.contains(e.target)) resultsDiv.classList.add('d-none');
  });

  function selezionaLibro(libro) {
    libroSelezionato = libro;
    searchWrapper.classList.add('d-none');
    resultsDiv.classList.add('d-none');
    selectedBookCard.classList.remove('d-none');
    document.getElementById('display-titolo').textContent = libro.titolo;
    document.getElementById('display-info').textContent = `di ${libro.autore} • ISBN ${libro.isbn}`;
    isbnHidden.value = libro.isbn;
    validazionePrezzo();
  }

  function resetSelezione() {
    searchWrapper.classList.remove('d-none');
    selectedBookCard.classList.add('d-none');
    searchInput.value = '';
    searchInput.focus();
    resultsDiv.classList.add('d-none');
  }

  // ── Validazione prezzo ────────────────────────────────────────────────────────
  function validazionePrezzo() {
    if (!libroSelezionato || !condizioneSelect.value) return;

    const perc = SOGLIE[condizioneSelect.value] || 1;
    const prezzoListino = parseFloat(libroSelezionato.prezzo);
    const prezzoMax = (prezzoListino * perc).toFixed(2);

    prezzoInput.max = prezzoMax;

    prezzoFeedback.innerHTML = `
    <div class="alert alert-info py-2 px-3 small border-0 shadow-sm mb-0">
      <i class="bi bi-info-circle-fill me-1"></i>
      Listino: <strong>€${prezzoListino}</strong> &mdash;
      Massimo per <strong>"${condizioneSelect.value}"</strong>:
      <strong class="text-primary">€${prezzoMax}</strong>
    </div>`;

    if (parseFloat(prezzoInput.value) > parseFloat(prezzoMax)) {
      prezzoInput.classList.add('is-invalid');
    } else {
      prezzoInput.classList.remove('is-invalid');
    }
  }

  condizioneSelect.addEventListener('change', validazionePrezzo);
  prezzoInput.addEventListener('input', validazionePrezzo);

  // Validazione al caricamento per mostrare subito il feedback
  window.addEventListener('DOMContentLoaded', validazionePrezzo);

  // ── Validazione submit ────────────────────────────────────────────────────────
  document.getElementById('form-modifica').addEventListener('submit', function(e) {
    const p = parseFloat(prezzoInput.value);
    const m = parseFloat(prezzoInput.max);

    if (p > m) {
      e.preventDefault();
      alert(`Il prezzo €${p.toFixed(2)} supera il massimo consentito di €${m} per questa condizione.`);
      prezzoInput.focus();
      return;
    }

    // Verifica data futura (importante per riattivare annunci scaduti)
    const dataInput = document.getElementById('data-input');
    if (dataInput && dataInput.value) {
      const dataScelta = new Date(dataInput.value);
      if (dataScelta <= new Date()) {
        e.preventDefault();
        alert('La data e ora dello scambio deve essere nel futuro.');
        dataInput.focus();
      }
    }
  });
</script>