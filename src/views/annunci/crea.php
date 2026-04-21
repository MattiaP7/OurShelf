<?php
// Ordiniamo i libri per titolo per una ricerca più fluida
if (!empty($libri) && is_array($libri)) {
  $materie_sort = array_column($libri, 'materia');
  array_multisort($materie_sort, SORT_ASC, $libri);
} else {
  $libri = [];
}
?>

<div class="mt-4">
  <a href="index.php?page=home&action=index" class="btn btn-link text-muted ps-0 mb-3 d-inline-flex align-items-center gap-1">
    <i class="bi bi-arrow-left"></i> Torna agli annunci
  </a>

  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded-4 p-4">
        <h4 class="fw-bold mb-1"><i class="bi bi-plus-circle text-primary me-2"></i>Pubblica un annuncio</h4>
        <p class="text-muted small mb-4">Trova il libro e imposta il prezzo. Il sistema verificherà che non superi il prezzo di listino.</p>

        <?php if (!empty($_SESSION['errors'])): ?>
          <div class="alert alert-danger alert-dismissible fade show rounded-3">
            <?php foreach ($_SESSION['errors'] as $e): ?>
              <div><i class="bi bi-exclamation-circle me-1"></i><?= safe_string($e) ?></div>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=annunci&action=store" id="form-annuncio">

          <div class="mb-4 position-relative">
            <label class="form-label fw-semibold">Quale libro vuoi vendere? <span class="text-danger">*</span></label>

            <div id="search-wrapper">
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="book-search" class="form-control border-start-0 ps-0 rounded-end-3"
                  placeholder="Scrivi titolo, autore o ISBN..." autocomplete="off">
              </div>
              <div id="search-results" class="list-group shadow mt-1 d-none"
                style="position: absolute; z-index: 1000; width: 100%; max-height: 250px; overflow-y: auto;">
              </div>
            </div>

            <div id="selected-book-card" class="card border-primary bg-primary-subtle d-none animate__animated animate__fadeIn">
              <div class="card-body d-flex justify-content-between align-items-center p-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-primary text-white rounded-3 px-3 py-2">
                    <i class="bi bi-book-half fs-4"></i>
                  </div>
                  <div>
                    <div class="fw-bold text-primary" id="display-titolo"></div>
                    <div class="small text-muted" id="display-info"></div>
                  </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="resetSelezione()">
                  Cambia
                </button>
              </div>
            </div>

            <input type="hidden" id="isbn-hidden" name="isbn" required>
          </div>

          <hr class="my-4">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Condizione <span class="text-danger">*</span></label>
              <select name="condizione" id="condizione-select" class="form-select rounded-3" required disabled>
                <option value="" disabled selected>Prima seleziona un libro...</option>
                <?php foreach ($condizioni as $condition): ?>
                  <option value="<?= safe_string($condition) ?>"><?= safe_string($condition) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Prezzo di vendita (€) <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">€</span>
                <input type="number" id="prezzo-input" name="prezzo" class="form-control"
                  step="0.01" min="0.01" placeholder="0.00" required disabled>
              </div>
              <div id="prezzo-feedback" class="form-text mt-2"></div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Note sull'usura</label>
            <textarea name="descrizione" class="form-control rounded-3" rows="2"
              placeholder="Esempio: sottolineato a matita, copertina leggermente usurata..."></textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Data e ora scambio <span class="text-danger">*</span></label>
              <input type="datetime-local" name="data_ora_scambio" class="form-control rounded-3"
                min="<?= date('Y-m-d\TH:i') ?>" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label fw-semibold">Dove vi vedrete? <span class="text-danger">*</span></label>
              <select name="id_luogo" class="form-select rounded-3" required>
                <option value="" disabled selected>Seleziona un luogo</option>
                <?php foreach ($luoghi as $luogo): ?>
                  <option value="<?= (int)$luogo['id_luogo'] ?>"><?= safe_string($luogo['nome']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 rounded-3 py-3 fw-bold shadow-sm" id="btn-submit">
            <i class="bi bi-check2-circle me-2"></i>PUBBLICA ANNUNCIO
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Dati passati dal PHP
  const LIBRI = <?= json_encode(array_values($libri)) ?>;

  // Configurazione soglie di prezzo basate sulla condizione
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

  let libroSelezionato = null;

  // 1. Logica di ricerca dinamica
  searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();
    resultsDiv.innerHTML = '';

    if (query.length < 2) {
      resultsDiv.classList.add('d-none');
      return;
    }

    const filtrati = LIBRI.filter(l =>
      l.titolo.toLowerCase().includes(query) ||
      l.isbn.includes(query) ||
      l.autore.toLowerCase().includes(query)
    ).slice(0, 5); // Mostriamo solo i primi 5 per pulizia

    if (filtrati.length > 0) {
      filtrati.forEach(l => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'list-group-item list-group-item-action border-0 py-3';
        item.innerHTML = `
          <div class="fw-bold">${l.titolo}</div>
          <div class="small text-muted">${l.autore} | ISBN: ${l.isbn}</div>
        `;
        item.onclick = () => selezionaLibro(l);
        resultsDiv.appendChild(item);
      });
      resultsDiv.classList.remove('d-none');
    } else {
      resultsDiv.classList.add('d-none');
    }
  });

  // 2. Funzione per confermare la selezione del libro
  function selezionaLibro(libro) {
    libroSelezionato = libro;

    // UI: Nascondi ricerca e mostra card
    searchWrapper.classList.add('d-none');
    resultsDiv.classList.add('d-none');
    selectedBookCard.classList.remove('d-none');

    // Popola card
    document.getElementById('display-titolo').textContent = libro.titolo;
    document.getElementById('display-info').textContent = `di ${libro.autore} • ISBN ${libro.isbn}`;

    // Attiva i campi successivi
    isbnHidden.value = libro.isbn;
    condizioneSelect.disabled = false;
    condizioneSelect.focus();

    validazionePrezzo();
  }

  // 3. Reset selezione
  function resetSelezione() {
    libroSelezionato = null;
    isbnHidden.value = '';
    searchWrapper.classList.remove('d-none');
    selectedBookCard.classList.add('d-none');
    searchInput.value = '';
    searchInput.focus();

    condizioneSelect.disabled = true;
    condizioneSelect.value = '';
    prezzoInput.disabled = true;
    prezzoInput.value = '';
    prezzoFeedback.innerHTML = '';
  }

  // 4. Calcolo soglie e blocco prezzo
  function validazionePrezzo() {
    if (!libroSelezionato || !condizioneSelect.value) {
      prezzoInput.disabled = true;
      return;
    }

    prezzoInput.disabled = false;
    const perc = SOGLIE[condizioneSelect.value] || 1;
    const prezzoListino = parseFloat(libroSelezionato.prezzo);
    const prezzoMax = (prezzoListino * perc).toFixed(2);

    // CRITICO: Impediamo fisicamente di andare oltre
    prezzoInput.max = prezzoMax;

    prezzoFeedback.innerHTML = `
      <div class="alert alert-info py-2 px-3 mb-0 small border-0 shadow-sm">
        <i class="bi bi-info-circle-fill me-2"></i>
        Il prezzo di listino del libro sarebbe <strong class="text-primary">€${prezzoListino}</strong><br>
        Per la condizione <strong>"${condizioneSelect.value}"</strong>, il prezzo massimo consentito è 
        <strong class="text-primary">€${prezzoMax}</strong> (${Math.round(perc*100)}% del listino).
      </div>
    `;

    // Se l'utente aveva già inserito un prezzo superiore, lo resettiamo al massimo
    if (parseFloat(prezzoInput.value) > prezzoMax) {
      prezzoInput.value = prezzoMax;
    }
  }

  condizioneSelect.addEventListener('change', validazionePrezzo);

  // Validazione finale prima dell'invio
  document.getElementById('form-annuncio').addEventListener('submit', function(e) {
    const p = parseFloat(prezzoInput.value);
    const m = parseFloat(prezzoInput.max);
    if (p > m) {
      e.preventDefault();
      alert("Il prezzo inserito supera il limite consentito per questo libro.");
    }
  });
</script>