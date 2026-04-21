<?php
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
        <p class="text-muted small mb-4">Cerca il libro tramite ISBN o titolo, selezionalo e completa i dettagli della vendita.</p>

        <?php if (!empty($_SESSION['errors'])): ?>
          <div class="alert alert-danger alert-dismissible fade show rounded-3">
            <?php foreach ($_SESSION['errors'] as $e): ?>
              <div><i class="bi bi-exclamation-circle me-1"></i><?= safe_string($e) ?></div>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php $_SESSION['errors'] = []; ?>
        <?php endif; ?>

        <form method="POST" action="index.php?page=annunci&action=store">

          <!-- ================================================
               SEZIONE 1 — Ricerca e selezione libro
               ================================================ -->
          <div class="mb-4">
            <label for="isbn-search" class="form-label fw-semibold">
              Cerca libro per ISBN o titolo <span class="text-danger">*</span>
            </label>

            <!--
              L'utente digita ISBN o titolo.
              Il datalist filtra le opzioni in tempo reale (comportamento nativo del browser).
              Ogni <option> ha come value l'ISBN — quando l'utente sceglie,
              il campo si popola con l'ISBN e lo script JS trova il libro corrispondente.
            -->
            <input
              type="text"
              id="isbn-search"
              class="form-control rounded-3 mb-2"
              placeholder="Es. 9788808123456 oppure Matematica…"
              list="libri-datalist"
              autocomplete="off">

            <datalist id="libri-datalist">
              <?php foreach ($libri as $l): ?>
                <option value="<?= safe_string($l['isbn']) ?>">
                  <?= safe_string($l['titolo']) ?> — <?= safe_string($l['autore']) ?>
                </option>
              <?php endforeach; ?>
            </datalist>

            <!-- Campo hidden effettivo inviato al server -->
            <input type="hidden" id="isbn" name="isbn">

            <!-- Anteprima libro trovato -->
            <div id="libro-preview" class="card border-0 bg-success-subtle rounded-3 p-3 d-none">
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-check-circle-fill text-success fs-5 mt-1 flex-shrink-0"></i>
                <div class="small">
                  <div class="fw-bold" id="preview-titolo"></div>
                  <div class="text-muted" id="preview-autore"></div>
                  <div class="text-muted" id="preview-materia"></div>
                  <div class="text-muted font-monospace" id="preview-isbn"></div>
                </div>
              </div>
            </div>

            <!-- ISBN non trovato nel catalogo -->
            <div id="isbn-not-found" class="alert alert-warning rounded-3 py-2 small d-none mt-2 mb-0">
              <i class="bi bi-exclamation-triangle me-1"></i>
              ISBN non trovato nel catalogo scolastico. Verifica il codice e riprova.
            </div>
          </div>

          <hr class="my-4">


          <!-- Condizione: 'Ottime condizioni','Buone condizioni','Condizioni accettabili','Danneggiato' -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Condizione <span class="text-danger">*</span></label>
            <select name="condizione" class="form-select rounded-3" required>
              <option value="" disabled selected>Seleziona la condizione del libro</option>
              <?php foreach ($condizioni as $condition): ?>
                <option value="<?= safe_string($condition) ?>">
                  <?= safe_string($condition) ?>
                </option>
              <?php endforeach; ?>
              <!-- <option value="Ottime condizioni">Ottime condizioni</option>
              <option value="Buone condizioni">Buone condizioni</option>
              <option value="Condizioni accettabili">Con qualche segno d'uso</option>
              <option value="Danneggiato">Danneggiato</option> -->
            </select>
          </div>

          <!-- Prezzo -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Prezzo di vendita (€) <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">€</span>
              <input type="number"
                id="prezzo-input"
                name="prezzo"
                class="form-control rounded-end-3"
                step="0.01"
                min="0.01"
                placeholder="Es. 12.50"
                required>
            </div>
            <div id="prezzo-suggerimento" class="form-text">
              Inserisci il prezzo di vendita desiderato.
            </div>
          </div>

          <!-- Descrizione -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Note aggiuntive</label>
            <textarea name="descrizione" class="form-control rounded-3" rows="3"
              placeholder="Es. sottolineature minime nelle prime pagine, nessuna pagina mancante…"></textarea>
          </div>

          <!-- Data e ora scambio -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Data e ora dello scambio <span class="text-danger">*</span></label>
            <input type="datetime-local" name="data_ora_scambio" class="form-control rounded-3"
              min="<?= date('Y-m-d\TH:i') ?>" required>
          </div>

          <!-- Luogo scambio -->
          <div class="mb-4">
            <label class="form-label fw-semibold">Luogo dello scambio <span class="text-danger">*</span></label>
            <select name="id_luogo" class="form-select rounded-3" required>
              <option value="" disabled selected>Seleziona un luogo</option>
              <?php foreach ($luoghi as $luogo): ?>
                <option value="<?= (int)$luogo['id_luogo'] ?>">
                  <?= safe_string($luogo['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 fw-semibold">
              <i class="bi bi-send me-1"></i> Pubblica annuncio
            </button>
            <a href="index.php?page=annunci&action=index" class="btn btn-outline-secondary rounded-3 px-4 py-2">
              Annulla
            </a>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>

<script>
  /**
   * Dataset dei libri passato da PHP.
   * Usiamo array_values per garantire che sia un array JS pulito [{}, {}].
   */
  const LIBRI = <?= json_encode(array_values($libri)) ?>;

  const searchInput = document.getElementById('isbn-search');
  const isbnHidden = document.getElementById('isbn');
  const preview = document.getElementById('libro-preview');
  const notFound = document.getElementById('isbn-not-found');

  /**
   * Funzione core per la ricerca e l'aggiornamento dell'UI
   */
  function aggiornaSelezione(valore) {
    if (!valore) {
      resetInterfaccia();
      return;
    }

    const v = valore.trim().toLowerCase();
    // Versione solo numerica per l'ISBN (toglie spazi, trattini, etc.)
    const vSoloNumeri = v.replace(/\D/g, '');

    // Cerchiamo il match nell'array LIBRI
    const libro = LIBRI.find(l => {
      const isbnDB = String(l.isbn).trim().toLowerCase();
      const isbnDBSoloNumeri = isbnDB.replace(/\D/g, '');
      const titoloDB = (l.titolo || "").toLowerCase();
      const autoreDB = (l.autore || "").toLowerCase();

      return (
        (vSoloNumeri !== '' && isbnDBSoloNumeri === vSoloNumeri) || // Match ISBN numerico
        isbnDB === v || // Match ISBN stringa esatta
        titoloDB === v || // Match Titolo esatto
        (titoloDB + " — " + autoreDB) === v // Match testo del datalist
      );
    });

    if (libro) {
      // 1. Popola i campi della preview
      document.getElementById('preview-titolo').textContent = libro.titolo;
      document.getElementById('preview-autore').textContent = libro.autore;
      document.getElementById('preview-materia').textContent = '📚 ' + libro.materia;
      document.getElementById('preview-isbn').textContent = libro.isbn;

      // 2. Imposta il valore reale per il database
      isbnHidden.value = libro.isbn;

      // 3. Gestisci la visibilità
      preview.classList.remove('d-none');
      notFound.classList.add('d-none');

      console.log("Libro selezionato correttamente:", libro.isbn);
    } else {
      // Nessun match trovato
      isbnHidden.value = '';
      preview.classList.add('d-none');

      // Mostriamo il "non trovato" solo se l'utente ha inserito una stringa lunga
      // per evitare che appaia mentre sta ancora scrivendo le prime lettere
      if (v.length > 8) {
        notFound.classList.remove('d-none');
      } else {
        notFound.classList.add('d-none');
      }
    }
  }

  function resetInterfaccia() {
    isbnHidden.value = '';
    preview.classList.add('d-none');
    notFound.classList.add('d-none');
  }

  /**
   * EVENT LISTENERS - Copriamo tutti i casi per massima compatibilità (Chrome/Firefox/Safari)
   */

  // Gestisce la digitazione e il "taglia/incolla" da tastiera
  searchInput.addEventListener('input', function() {
    aggiornaSelezione(this.value);
  });

  // Gestisce la selezione cliccata dal menù a tendina del datalist
  searchInput.addEventListener('change', function() {
    aggiornaSelezione(this.value);
  });

  // Gestisce l'incollamento tramite mouse (tasto destro -> incolla)
  searchInput.addEventListener('paste', function() {
    // Il timeout serve per attendere che il browser inserisca il testo nel campo
    setTimeout(() => {
      aggiornaSelezione(this.value);
    }, 100);
  });

  // Gestisce la pressione di INVIO per forzare la ricerca
  searchInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault(); // Evita l'invio del form accidentale
      aggiornaSelezione(this.value);
    }
  });

  console.log("Script Ricerca Libri caricato. Totale libri in memoria:", LIBRI.length);

  // Selezioniamo gli elementi necessari
  const condizioneSelect = document.querySelector('select[name="condizione"]');
  const prezzoSuggerimento = document.getElementById('prezzo-suggerimento');
  const prezzoInput = document.getElementById('prezzo-input');

  // Mappa dei suggerimenti in base alla condizione
  const suggerimentiPrezzo = {
    'Ottime condizioni': {
      perc: '70-75%',
      testo: 'Quasi nuovo: consigliato circa il 70-75% del prezzo originale.'
    },
    'Buone condizioni': {
      perc: '50-60%',
      testo: 'Usato garantito: consigliato circa il 50-60% del prezzo originale.'
    },
    'Condizioni accettabili': {
      perc: '30-40%',
      testo: 'Segni di usura presenti: consigliato circa il 30-40% del prezzo originale.'
    },
    'Danneggiato': {
      perc: '10-20%',
      testo: 'Molto usurato: prezzo simbolico consigliato (10-20%).'
    }
  };

  // Funzione per aggiornare il placeholder e il testo di aiuto
  function aggiornaSuggerimentoPrezzo() {
    const condizione = condizioneSelect.value;
    const info = suggerimentiPrezzo[condizione];

    if (info) {
      // Cambiamo il testo sotto l'input
      prezzoSuggerimento.innerHTML = `<i class="bi bi-info-circle me-1"></i> <strong>${info.perc}</strong> - ${info.testo}`;
      prezzoSuggerimento.className = "form-text text-primary animate__animated animate__fadeIn"; // Opzionale: aggiunge enfasi

      // Cambiamo il placeholder dell'input per dare un'idea visiva
      prezzoInput.placeholder = `Suggerito: ${info.perc} del listino`;
    } else {
      prezzoSuggerimento.textContent = "Seleziona una condizione per vedere il prezzo consigliato.";
      prezzoSuggerimento.className = "form-text text-muted";
    }
  }

  // Ascoltiamo il cambio della condizione
  condizioneSelect.addEventListener('change', aggiornaSuggerimentoPrezzo);
</script>