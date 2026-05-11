<?php
// views/annunci/upload_immagini.php

/** @var int $idAnnuncio */
/** @var array $immagini */

$slotsRimasti = 3 - count($immaginiUrl ?? []);
?>

<div class="mt-4">
  <a href="index.php?page=annunci&action=dettaglio&id=<?= (int)$idAnnuncio ?>"
    class="btn btn-link text-muted ps-0 mb-3 d-inline-flex align-items-center gap-1 text-decoration-none">
    <i class="bi bi-arrow-left"></i> Torna all'annuncio
  </a>

  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 p-4">

        <div class="mb-4">
          <p class="small fw-semibold text-muted text-uppercase mb-2" style="letter-spacing:.5px;">
            Foto attuali (<?= count($immagini) ?>/3)
          </p>

          <?php if (empty($immagini)): ?>
            <div class="p-3 border rounded-3 bg-light text-center">
              <p class="text-muted small mb-0">Non ci sono ancora immagini per questo annuncio.</p>
            </div>
          <?php else: ?>
            <?php foreach ($immagini as $i => $img): ?>
              <div class="position-relative group-hover">
                <img src="<?= APP_BASE_URL ?>/public/uploads/annunci/<?= safe_string($img['nome_file']) ?>"
                  class="rounded-3 border shadow-sm"
                  style="width:120px;height:100px;object-fit:cover;"
                  alt=<?= safe_string($img['nome_file']) ?>>


                <!-- Link per eliminare la singola foto -->
                <a href="index.php?page=annunci&action=eliminaImmagine&id_img=<?= $img['id_immagine'] ?>&id_annuncio=<?= $idAnnuncio ?>"
                  class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-0 d-flex align-items-center justify-content-center shadow"
                  style="width:24px;height:24px;"
                  onclick="return confirm('Vuoi eliminare questa foto?')">
                  <i class="bi bi-trash" style="font-size: .8rem;"></i>
                </a>
              </div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>
      </div>

      <!-- Stepper -->
      <div class="d-flex align-items-center gap-2 mb-4">
        <div class="d-flex align-items-center gap-1">
          <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
            style="width:28px;height:28px;">
            <i class="bi bi-check2" style="font-size:.8rem;"></i>
          </div>
          <span class="small text-success fw-semibold">Annuncio pubblicato</span>
        </div>
        <div class="flex-grow-1 border-top border-2 border-primary opacity-50"></div>
        <div class="d-flex align-items-center gap-1">
          <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold"
            style="width:28px;height:28px;font-size:.85rem;">2</div>
          <span class="small text-primary fw-semibold">Foto del libro</span>
        </div>
      </div>

      <h4 class="fw-bold mb-1">
        <i class="bi bi-images text-primary me-2"></i>Aggiungi le foto
      </h4>
      <p class="text-muted small mb-4">
        Carica fino a <strong>3 foto</strong> · JPG, PNG · WEBP max 2 MB ciascuna.
      </p>

      <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 small">
          <?php foreach ($_SESSION['errors'] as $e): ?>
            <div><i class="bi bi-exclamation-circle me-1"></i><?= safe_string($e) ?></div>
          <?php endforeach; ?>
          <?php unset($_SESSION['errors']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 small">
          <i class="bi bi-check-circle me-1"></i><?= safe_string($_SESSION['success']) ?>
          <?php unset($_SESSION['success']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <!-- Foto già caricate -->
      <?php if (!empty($immaginiUrl)): ?>
        <div class="mb-4">
          <p class="small fw-semibold text-muted text-uppercase mb-2" style="letter-spacing:.5px;">
            Foto caricate (<?= count($immaginiUrl) ?>/3)
          </p>
          <div class="d-flex gap-2 flex-wrap">
            <?php foreach ($immaginiUrl as $url): ?>
              <img src="<?= safe_string($url) ?>" alt="Foto libro"
                class="rounded-3 border shadow-sm"
                style="width:100px;height:80px;object-fit:cover;">
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($slotsRimasti > 0): ?>
        <form method="POST"
          action="index.php?page=annunci&action=uploadImmagini"
          enctype="multipart/form-data"
          id="form-upload">
          <input type="hidden" name="id_annuncio" value="<?= (int)$idAnnuncio ?>">

          <div id="drop-zone"
            class="border border-2 border-dashed rounded-4 p-4 text-center mb-3"
            style="border-color:#6ea8fe!important;background:#f8fbff;cursor:pointer;transition:background .2s;">
            <i class="bi bi-cloud-arrow-up-fill fs-1 text-primary opacity-50 mb-1 d-block"></i>
            <p class="fw-semibold text-primary mb-1">Trascina le foto qui</p>
            <p class="text-muted small mb-3">oppure clicca per selezionare</p>
            <input type="file" id="file-input" name="immagini[]"
              accept="image/jpeg,image/png,image/webp" multiple class="d-none">
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4"
              onclick="document.getElementById('file-input').click()">
              <i class="bi bi-folder2-open me-1"></i>Sfoglia
            </button>
            <p class="text-muted mt-2 mb-0" style="font-size:.75rem;">
              Puoi aggiungere ancora <strong id="slots-label"><?= $slotsRimasti ?></strong> foto
            </p>
          </div>

          <div id="preview-wrap" class="d-flex gap-2 flex-wrap mb-3"></div>
          <div id="js-error" class="alert alert-danger small d-none rounded-3 py-2 mb-3"></div>

          <div class="d-grid gap-2">
            <button type="submit" id="btn-upload"
              class="btn btn-primary rounded-3 fw-bold py-2 shadow-sm" disabled>
              <i class="bi bi-upload me-2"></i>CARICA FOTO
            </button>
            <a href="index.php?page=annunci&action=dettaglio&id=<?= (int)$idAnnuncio ?>"
              class="btn btn-outline-secondary rounded-3 py-2">
              Salta per ora
            </a>
          </div>
        </form>

      <?php else: ?>
        <div class="alert alert-info rounded-3 text-center small py-3">
          <i class="bi bi-images me-1"></i> Hai già caricato il massimo di 3 foto.
        </div>
        <a href="index.php?page=annunci&action=dettaglio&id=<?= (int)$idAnnuncio ?>"
          class="btn btn-primary w-100 rounded-3">
          Vai all'annuncio <i class="bi bi-arrow-right ms-1"></i>
        </a>
      <?php endif; ?>

    </div>
  </div>
</div>
</div>

<script>
  (function() {
    const MAX = <?= $slotsRimasti ?>;
    const MAXB = 2 * 1024 * 1024;
    const TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    const drop = document.getElementById('drop-zone');
    const input = document.getElementById('file-input');
    const wrap = document.getElementById('preview-wrap');
    const btn = document.getElementById('btn-upload');
    const err = document.getElementById('js-error');
    const slotLbl = document.getElementById('slots-label');
    if (!drop) return;

    let files = [];

    drop.addEventListener('dragover', e => {
      e.preventDefault();
      drop.style.background = '#e7f1ff';
    });
    drop.addEventListener('dragleave', e => {
      e.preventDefault();
      drop.style.background = '#f8fbff';
    });
    drop.addEventListener('drop', e => {
      e.preventDefault();
      drop.style.background = '#f8fbff';
      handle([...e.dataTransfer.files]);
    });
    drop.addEventListener('click', e => {
      if (e.target.tagName !== 'BUTTON') input.click();
    });
    input.addEventListener('change', () => {
      handle([...input.files]);
    });

    function handle(newFiles) {
      clearErr();
      const errs = [];
      newFiles.forEach(f => {
        if (files.length >= MAX) {
          errs.push('Massimo ' + MAX + ' foto consentite.');
          return;
        }
        if (!TYPES.includes(f.type)) {
          errs.push('"' + f.name + '": formato non supportato (usa JPG, PNG ).');
          return;
        }
        if (f.size > MAXB) {
          errs.push('"' + f.name + '": supera il limite di 2 MB.');
          return;
        }
        if (files.some(x => x.name === f.name && x.size === f.size)) return;
        files.push(f);
      });
      if (errs.length) showErr(errs.join('<br>'));
      render();
      sync();
    }

    function render() {
      wrap.innerHTML = '';
      slotLbl.textContent = MAX - files.length;
      files.forEach((f, i) => {
        const r = new FileReader();
        r.onload = ev => {
          const d = document.createElement('div');
          d.className = 'position-relative';
          d.style.cssText = 'width:100px;height:82px;';
          d.innerHTML = `
          <img src="${ev.target.result}" class="w-100 h-100 rounded-3 border shadow-sm" style="object-fit:cover;">
          <button type="button" data-i="${i}"
            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-0 d-flex align-items-center justify-content-center"
            style="width:20px;height:20px;font-size:.65rem;"><i class="bi bi-x"></i></button>
          <span class="position-absolute bottom-0 start-0 m-1 badge bg-dark bg-opacity-60 text-white rounded-pill"
            style="font-size:.6rem;">${fmt(f.size)}</span>`;
          d.querySelector('button').onclick = () => {
            files.splice(i, 1);
            render();
            sync();
          };
          wrap.appendChild(d);
        };
        r.readAsDataURL(f);
      });
      btn.disabled = files.length === 0;
    }

    function sync() {
      const dt = new DataTransfer();
      files.forEach(f => dt.items.add(f));
      input.files = dt.files;
    }

    function showErr(m) {
      err.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>' + m;
      err.classList.remove('d-none');
    }

    function clearErr() {
      err.innerHTML = '';
      err.classList.add('d-none');
    }

    function fmt(b) {
      return b < 1048576 ? (b / 1024).toFixed(0) + ' KB' : (b / 1048576).toFixed(1) + ' MB';
    }
  })();
</script>