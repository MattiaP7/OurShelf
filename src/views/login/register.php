<?php
// Variabili: 

/** @var array $classi */
?>

<div class="auth-card">
  <div class="text-center mb-4">
    <h3 class="fw-bold">Crea un account</h3>
    <p class="text-muted small">Inizia a scambiare i tuoi libri oggi stesso</p>
  </div>

  <div class="position-fixed top-0 end-0 p-3" style="z-index:1050;">
    <?php flash_error(); ?>
    <?php flash_success(); ?>
  </div>

  <form action="index.php?page=login&action=store" method="post" enctype="multipart/form-data">

    <!-- ── Nome / Cognome ──────────────────────────────── -->
    <div class="row g-3 mb-3">
      <div class="col-6">
        <label class="form-label small fw-bold text-muted mb-1">Nome</label>
        <div class="input-group shadow-sm" style="border:1px solid #dee2e6;border-radius:.375rem;overflow:hidden;">
          <input type="text" name="nome" class="form-control border-0" placeholder="Mario"
            style="box-shadow:none;" required>
        </div>
      </div>
      <div class="col-6">
        <label class="form-label small fw-bold text-muted mb-1">Cognome</label>
        <div class="input-group shadow-sm" style="border:1px solid #dee2e6;border-radius:.375rem;overflow:hidden;">
          <input type="text" name="cognome" class="form-control border-0" placeholder="Rossi"
            style="box-shadow:none;" required>
        </div>
      </div>
    </div>

    <!-- ── Data nascita / Sesso ────────────────────────── -->
    <div class="row g-3 mb-3">
      <div class="col-md-7">
        <label class="form-label small fw-bold text-muted mb-1">Data di Nascita</label>
        <div class="input-group shadow-sm" style="border:1px solid #dee2e6;border-radius:.375rem;overflow:hidden;">
          <input type="date" name="data_nascita" class="form-control border-0"
            style="box-shadow:none;" required>
        </div>
      </div>
      <div class="col-md-5">
        <label class="form-label small fw-bold text-muted mb-1">Sesso</label>
        <div class="input-group shadow-sm" style="border:1px solid #dee2e6;border-radius:.375rem;overflow:hidden;">
          <select name="sesso" class="form-select border-0" style="box-shadow:none;" required>
            <option value="" selected disabled>Scegli...</option>
            <option value="m">Maschio</option>
            <option value="f">Femmina</option>
          </select>
        </div>
      </div>
    </div>

    <!-- ── Classe ──────────────────────────────────────── -->
    <div class="mb-3">
      <label class="form-label small fw-bold text-muted mb-1">La tua Classe</label>
      <div class="input-group shadow-sm" style="border:1px solid #dee2e6;border-radius:.375rem;overflow:hidden;">
        <select name="id_classe" class="form-select border-0" style="box-shadow:none;" required>
          <option value="0" disabled selected>Seleziona classe...</option>
          <?php foreach ($classi as $classe): ?>
            <option value="<?= $classe['id_classe'] ?>">
              <?= safe_string($classe['anno'] . $classe['sezione'] . ' - ' . $classe['indirizzo']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <!-- ── Email ───────────────────────────────────────── -->
    <div class="mb-3">
      <label class="form-label small fw-bold text-muted mb-1">Email scolastica</label>
      <div class="input-group shadow-sm" style="border:1px solid #dee2e6;border-radius:.375rem;overflow:hidden;">
        <span class="input-group-text bg-white border-0 pe-1">
          <i class="bi bi-envelope text-muted"></i>
        </span>
        <input type="email" name="email" class="form-control border-0 ps-1"
          placeholder="mario@isit100.fe.it" style="box-shadow:none;" required>
      </div>
    </div>

    <!-- ── Password ────────────────────────────────────── -->
    <div class="row g-3 mb-3">
      <div class="col-12">
        <label class="form-label small fw-bold text-muted mb-1">Password</label>
        <div class="input-group shadow-sm" style="border:1px solid #dee2e6;border-radius:.375rem;overflow:hidden;">
          <span class="input-group-text bg-white border-0 pe-1">
            <i id="icon-password" class="bi bi-lock text-muted"></i>
          </span>
          <input id="password" type="password" name="password"
            class="form-control border-0 ps-1" placeholder="••••••••"
            style="box-shadow:none;" required>
          <span class="input-group-text bg-white border-0">
            <div class="form-check mb-0 d-flex align-items-center">
              <input class="form-check-input" type="checkbox" style="cursor:pointer;"
                onclick="showPassword('password','icon-password')">
              <label class="form-check-label small text-muted fw-bold mb-0 ms-2"
                style="cursor:pointer;">Mostra</label>
            </div>
          </span>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label small fw-bold text-muted mb-1">Conferma Password</label>
        <div class="input-group shadow-sm" style="border:1px solid #dee2e6;border-radius:.375rem;overflow:hidden;">
          <span class="input-group-text bg-white border-0 pe-1">
            <i id="icon-confPassword" class="bi bi-lock text-muted"></i>
          </span>
          <input id="confPassword" type="password" name="confPassword"
            class="form-control border-0 ps-1" placeholder="••••••••"
            style="box-shadow:none;" required>
          <span class="input-group-text bg-white border-0">
            <div class="form-check mb-0 d-flex align-items-center">
              <input class="form-check-input" type="checkbox" style="cursor:pointer;"
                onclick="showPassword('confPassword','icon-confPassword')">
              <label class="form-check-label small text-muted fw-bold mb-0 ms-2"
                style="cursor:pointer;">Mostra</label>
            </div>
          </span>
        </div>
      </div>
    </div>

    <!-- ── Avatar (opzionale) ─────────────────────────── -->
    <div class="mb-4">
      <label class="form-label small fw-bold text-muted mb-1">
        Foto profilo <span class="text-muted fw-normal">(opzionale)</span>
      </label>

      <div class="d-flex align-items-center gap-3">
        <!-- Anteprima circolare -->
        <div id="avatar-preview"
          class="rounded-circle bg-light border d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0"
          style="width:64px;height:64px;">
          <i class="bi bi-person-fill text-muted fs-3"></i>
        </div>

        <div class="flex-grow-1">
          <input type="file" id="avatar-input" name="avatar"
            accept="image/jpeg,image/png" class="d-none">
          <button type="button"
            class="btn btn-outline-secondary btn-sm rounded-pill px-3 mb-1"
            onclick="document.getElementById('avatar-input').click()">
            <i class="bi bi-upload me-1"></i>Scegli foto
          </button>
          <p class="text-muted mb-0" style="font-size:.72rem;">
            JPG · PNG · WEBP max 2 MB
          </p>
          <p id="avatar-name" class="text-primary small mb-0 fw-semibold"></p>
          <p id="avatar-err" class="text-danger small mb-0"></p>
        </div>
      </div>
    </div>

    <!-- ── Submit ──────────────────────────────────────── -->
    <button type="submit" class="btn btn-primary w-100 btn-modern shadow-sm mb-3">
      Registrati <i class="bi bi-person-plus-fill ms-2"></i>
    </button>

    <div class="text-center">
      <p class="small text-muted">Hai già un account?
        <a href="index.php?page=login" class="text-decoration-none fw-bold">Accedi qui</a>
      </p>
    </div>
  </form>
</div>

<script>
  (function() {
    const input = document.getElementById('avatar-input');
    const preview = document.getElementById('avatar-preview');
    const name = document.getElementById('avatar-name');
    const errEl = document.getElementById('avatar-err');
    const MAXB = 2 * 1024 * 1024;
    const TYPES = ['image/jpeg', 'image/png'];

    input.addEventListener('change', function() {
      const f = this.files[0];
      name.textContent = '';
      errEl.textContent = '';

      if (!f) return;

      if (!TYPES.includes(f.type)) {
        errEl.textContent = 'Formato non supportato. Usa JPG, PNG.';
        this.value = '';
        return;
      }
      if (f.size > MAXB) {
        errEl.textContent = 'Il file supera il limite di 2 MB.';
        this.value = '';
        return;
      }

      name.textContent = f.name;

      const r = new FileReader();
      r.onload = ev => {
        preview.innerHTML = `<img src="${ev.target.result}"
        style="width:64px;height:64px;object-fit:cover;border-radius:50%;">`;
      };
      r.readAsDataURL(f);
    });
  })();
</script>