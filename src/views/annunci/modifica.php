<?php
defined("APP") or die("Accesso negato");

/** @var array $annuncio Dati dell'annuncio dal DB */
/** @var array $immagini Array delle immagini associate */
/** @var array $libri Elenco completo libri per la ricerca */
/** @var array $luoghi Lista dei luoghi */
/** @var array $condizioni Lista delle condizioni */

$isProprietario = !empty($_SESSION['id_studente']) &&
  (int)$_SESSION['id_studente'] === (int)$annuncio['proprietario'];

if (!$isProprietario) {
  die("Accesso negato: non sei il proprietario di questo annuncio.");
}

// Prepariamo i dati per JS: cerchiamo il libro corrente nell'elenco totale
$libroCorrente = null;
foreach ($libri as $l) {
  if ($l['isbn'] === $annuncio['isbn']) {
    $libroCorrente = $l;
    break;
  }
}
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <!-- Header -->
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold mb-0">
          <i class="bi bi-pencil-square text-primary me-2"></i>Modifica Annuncio
        </h2>
        <a href="index.php?page=annunci&action=dettaglio&id=<?= (int)$annuncio['id_annuncio'] ?>"
          class="btn btn-outline-secondary rounded-pill btn-sm">
          Annulla
        </a>
      </div>

      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5">

          <form action="index.php?page=annunci&action=update" method="POST" id="form-modifica">
            <input type="hidden" name="id_annuncio" value="<?= (int)$annuncio['id_annuncio'] ?>">
            <input type="hidden" id="isbn-hidden" name="isbn" value="<?= safe_string($annuncio['isbn']) ?>" required>

            <!-- ── SEZIONE 1: Ricerca Libro (Sistema Intelligente) ── -->
            <h5 class="text-uppercase small fw-bold text-primary mb-4" style="letter-spacing: 1px;">
              1. Riferimento Libro
            </h5>

            <div class="mb-4 position-relative">
              <label class="form-label fw-semibold">Cerca un libro diverso (opzionale)</label>

              <!-- Wrapper Ricerca (visibile se si clicca "Cambia") -->
              <div id="search-wrapper" class="d-none">
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                  <input type="text" id="book-search" class="form-control border-start-0 ps-0 rounded-end-3"
                    placeholder="Cerca titolo, autore o ISBN...">
                </div>
                <div id="search-results" class="list-group shadow mt-1 d-none"
                  style="position:absolute;z-index:1000;width:100%;max-height:200px;overflow-y:auto;"></div>
              </div>

              <!-- Card Libro Selezionato (visibile all'inizio) -->
              <div id="selected-book-card" class="card border-primary bg-primary-subtle shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center p-3">
                  <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary text-white rounded-3 px-3 py-2">
                      <i class="bi bi-book-half fs-4"></i>
                    </div>
                    <div>
                      <div class="fw-bold text-primary" id="display-titolo"><?= safe_string($annuncio['titolo']) ?></div>
                      <div class="small text-muted" id="display-info">di <?= safe_string($annuncio['autore']) ?> • ISBN <?= safe_string($annuncio['isbn']) ?></div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="resetSelezione()">Cambia</button>
                </div>
              </div>
            </div>

            <hr class="my-4 opacity-25">

            <!-- ── SEZIONE 2: Stato e Prezzo ── -->
            <h5 class="text-uppercase small fw-bold text-primary mb-4" style="letter-spacing: 1px;">
              2. Condizioni e Prezzo
            </h5>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Condizione</label>
                <select name="condizione" id="condizione-select" class="form-select rounded-3" required>
                  <?php foreach ($condizioni as $c): ?>
                    <option value="<?= $c ?>" <?= $annuncio['condizione'] === $c ? 'selected' : '' ?>>
                      <?= $c ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Prezzo Vendita (€)</label>
                <div class="input-group">
                  <span class="input-group-text">€</span>
                  <input type="number" step="0.01" name="prezzo_vendita" id="prezzo-input"
                    class="form-control rounded-end-3 fw-bold border-primary"
                    value="<?= (float)$annuncio['prezzo_vendita'] ?>" required>
                </div>
                <div id="prezzo-feedback" class="mt-2"></div>
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Note sull'usura</label>
                <textarea name="descrizione" class="form-control rounded-4" rows="3"
                  placeholder="Es: sottolineature, copertina rovinata..."><?= safe_string($annuncio['descrizione'] ?? '') ?></textarea>
              </div>
            </div>

            <hr class="my-4 opacity-25">

            <!-- ── SEZIONE 3: Scambio ── -->
            <h5 class="text-uppercase small fw-bold text-primary mb-4" style="letter-spacing: 1px;">
              3. Dettagli Scambio
            </h5>

            <div class="row g-3">
              <div class="col-md-7">
                <label class="form-label fw-semibold">Luogo dello scambio</label>
                <select name="id_luogo" class="form-select rounded-3" required>
                  <?php foreach ($luoghi as $l): ?>
                    <option value="<?= (int)$l['id_luogo'] ?>" <?= (int)$l['id_luogo'] === (int)$annuncio['luogo_scambio'] ? 'selected' : '' ?>>
                      <?= safe_string($l['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-5">
                <label class="form-label fw-semibold">Data e Ora prevista</label>
                <input type="datetime-local" name="data_ora_scambio" class="form-control rounded-3"
                  value="<?= !empty($annuncio['data_ora_scambio']) ? date('Y-m-d\TH:i', strtotime($annuncio['data_ora_scambio'])) : '' ?>" required>
              </div>
            </div>

            <!-- GESTIONE IMMAGINI (Mini-Preview) -->
            <div class="bg-light rounded-4 p-4 my-4 border">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Immagini (<?= count($immagini) ?>/3)</h6>
                <a href="index.php?page=annunci&action=uploadForm&id=<?= (int)$annuncio['id_annuncio'] ?>"
                  class="btn btn-sm btn-outline-primary rounded-pill">
                  <i class="bi bi-camera me-1"></i> Gestisci Foto
                </a>
              </div>
              <div class="d-flex gap-2">
                <?php foreach ($immagini as $img): ?>
                  <img src="<?= APP_BASE_URL ?>/public/uploads/annunci/<?= safe_string($img['nome_file']) ?>"
                    class="rounded-3 border shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                <?php endforeach; ?>
              </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm py-3">
              <i class="bi bi-check-circle me-2"></i>SALVA MODIFICHE
            </button>
          </form>
        </div>
      </div>

      <!-- Zona Pericolo -->
      <div class="mt-5 p-4 border border-danger border-opacity-25 rounded-4 bg-danger bg-opacity-10 d-flex align-items-center justify-content-between">
        <div>
          <h6 class="fw-bold text-danger mb-1">Zona Pericolo</h6>
          <p class="small text-danger mb-0">L'azione è irreversibile.</p>
        </div>
        <form method="POST" action="index.php?page=annunci&action=elimina" onsubmit="return confirm('Sei sicuro?')">
          <input type="hidden" name="id_annuncio" value="<?= (int)$annuncio['id_annuncio'] ?>">
          <button class="btn btn-outline-danger rounded-pill px-4 fw-bold">Elimina</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Logica speculare a crea.php
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

  // Inizializziamo il libro selezionato con quello attuale
  let libroSelezionato = <?= json_encode($libroCorrente) ?>;

  searchInput.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    resultsDiv.innerHTML = '';
    if (q.length < 2) {
      resultsDiv.classList.add('d-none');
      return;
    }

    const filtrati = LIBRI.filter(l =>
      l.titolo.toLowerCase().includes(q) || l.isbn.includes(q) || l.autore.toLowerCase().includes(q)
    ).slice(0, 5);

    if (filtrati.length) {
      filtrati.forEach(l => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'list-group-item list-group-item-action border-0 py-3';
        item.innerHTML = `<div class="fw-bold">${l.titolo}</div><div class="small text-muted">${l.autore} | ISBN: ${l.isbn}</div>`;
        item.onclick = () => selezionaLibro(l);
        resultsDiv.appendChild(item);
      });
      resultsDiv.classList.remove('d-none');
    }
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
  }

  function validazionePrezzo() {
    if (!libroSelezionato || !condizioneSelect.value) return;

    const perc = SOGLIE[condizioneSelect.value] || 1;
    const prezzoListino = parseFloat(libroSelezionato.prezzo);
    const prezzoMax = (prezzoListino * perc).toFixed(2);

    prezzoInput.max = prezzoMax;
    prezzoFeedback.innerHTML = `
      <div class="alert alert-info py-2 px-3 small border-0 shadow-sm">
        Listino: <strong>€${prezzoListino}</strong> | 
        Max per "${condizioneSelect.value}": <strong>€${prezzoMax}</strong>
      </div>`;

    if (parseFloat(prezzoInput.value) > prezzoMax) {
      prezzoInput.classList.add('is-invalid');
    } else {
      prezzoInput.classList.remove('is-invalid');
    }
  }

  condizioneSelect.addEventListener('change', validazionePrezzo);
  prezzoInput.addEventListener('input', validazionePrezzo);

  // Eseguiamo la validazione al caricamento per mostrare il feedback sul prezzo attuale
  window.addEventListener('DOMContentLoaded', validazionePrezzo);

  document.getElementById('form-modifica').addEventListener('submit', function(e) {
    const p = parseFloat(prezzoInput.value);
    const m = parseFloat(prezzoInput.max);
    if (p > m) {
      e.preventDefault();
      alert("Attenzione: Il prezzo inserito (€" + p + ") supera il limite massimo consentito per questa condizione (€" + m + ").");
    }
  });
</script>