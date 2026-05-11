<?php
// OurShelf/src/views/users/index.php
defined("APP") or die("Accesso negato");

/** @var array $user   - dati utente (incluso $user['foto']) */
/** @var array $classi - elenco classi */

// ── FIX URL ───────────────────────────────────────────────────────────────────
// Il problema: il <base href> in layout.php punta a .../src/
// Quindi un src="/uploads/users/foto.jpg" viene risolto come
// .../src/uploads/users/foto.jpg  →  404
// Soluzione: usare sempre l'URL ASSOLUTO completo per i file in public/
// APP_BASE_URL è definita in layout.php (già incluso prima di questa view)
$avatarUrl = !empty($user['foto'])
  ? APP_BASE_URL . '/public/uploads/users/' . $user['foto']
  : '';
?>

<div class="mt-4">

  <!-- Intestazione profilo con avatar -->
  <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
    <div class="flex-shrink-0" style="width:64px;height:64px;">
      <?php if ($avatarUrl): ?>
        <img src="<?= $avatarUrl ?>"
          alt="Avatar"
          class="rounded-circle border shadow-sm w-100 h-100"
          style="object-fit:cover;"
          onerror="this.outerHTML='<div class=\'rounded-circle bg-primary d-flex align-items-center justify-content-center shadow-sm w-100 h-100\' style=\'border:3px solid white\'><span class=\'fw-bold text-white fs-3\'><?= strtoupper(mb_substr($user['nome'], 0, 1)) ?></span></div>'">
      <?php else: ?>
        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center shadow-sm w-100 h-100"
          style="border:3px solid white;">
          <span class="fw-bold text-white fs-3">
            <?= strtoupper(mb_substr($user['nome'], 0, 1)) ?>
          </span>
        </div>
      <?php endif; ?>
    </div>
    <div>
      <h3 class="fw-bold mb-0 text-dark">
        <?= safe_string($user['nome']) ?> <?= safe_string($user['cognome']) ?>
      </h3>
      <span class="badge bg-primary-subtle text-primary fw-medium">
        <?= safe_string($user['email']) ?>
      </span>
    </div>
  </div>

  <!-- Form unico: dati + avatar + password -->
  <form method="POST"
    action="index.php?page=users&action=update"
    enctype="multipart/form-data">

    <div class="row g-4">

      <!-- ── Colonna sinistra: dati personali + foto ─────────────────────────── -->
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
          <h6 class="fw-bold text-muted text-uppercase small mb-4" style="letter-spacing:1px;">
            <i class="bi bi-person-lines-fill text-primary me-2"></i>Informazioni Profilo
          </h6>

          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label small fw-bold text-secondary mb-1">Nome</label>
              <input type="text" name="nome" class="form-control rounded-3 bg-light border-0 py-2"
                value="<?= safe_string($user['nome']) ?>" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label small fw-bold text-secondary mb-1">Cognome</label>
              <input type="text" name="cognome" class="form-control rounded-3 bg-light border-0 py-2"
                value="<?= safe_string($user['cognome']) ?>" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label small fw-bold text-secondary mb-1">Data di nascita</label>
              <input type="date" name="data_nascita" class="form-control rounded-3 bg-light border-0 py-2"
                value="<?= safe_string($user['data_nascita']) ?>" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label small fw-bold text-secondary mb-1">Sesso</label>
              <select name="sesso" class="form-select rounded-3 bg-light border-0 py-2" required>
                <option value="m" <?= $user['sesso'] === 'm' ? 'selected' : '' ?>>Maschio</option>
                <option value="f" <?= $user['sesso'] === 'f' ? 'selected' : '' ?>>Femmina</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold text-secondary mb-1">La tua Classe</label>
              <select name="id_classe" class="form-select rounded-3 bg-light border-0 py-2" required>
                <?php foreach ($classi as $c): ?>
                  <option value="<?= (int) $c['id_classe'] ?>"
                    <?= (int) $user['id_classe'] === (int) $c['id_classe'] ? 'selected' : '' ?>>
                    <?= safe_string($c['anno']) ?>°
                    <?= safe_string($c['sezione']) ?> —
                    <?= safe_string($c['indirizzo']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold text-secondary mb-1">Email Istituzionale</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-0">
                  <i class="bi bi-envelope text-muted"></i>
                </span>
                <input type="email" name="email" class="form-control bg-light border-0 py-2"
                  value="<?= safe_string($user['email']) ?>" required>
              </div>
            </div>
          </div>

          <!-- ── Sezione foto profilo ─────────────────────────────────────────── -->
          <hr class="my-4">
          <h6 class="fw-bold text-muted text-uppercase small mb-3" style="letter-spacing:1px;">
            <i class="bi bi-person-bounding-box text-primary me-2"></i>Foto Profilo
          </h6>

          <div class="d-flex align-items-center gap-3">
            <div id="avatar-preview"
              class="rounded-circle border overflow-hidden flex-shrink-0 bg-light
                        d-flex align-items-center justify-content-center"
              style="width:72px;height:72px;">
              <?php if ($avatarUrl): ?>
                <img src="<?= $avatarUrl ?>" alt=""
                  style="width:72px;height:72px;object-fit:cover;border-radius:50%;">
              <?php else: ?>
                <i class="bi bi-person-fill text-muted fs-2"></i>
              <?php endif; ?>
            </div>

            <div class="flex-grow-1">
              <input type="file" id="avatar-input" name="avatar"
                accept="image/jpeg,image/png,image/webp" class="d-none">
              <button type="button"
                class="btn btn-outline-secondary btn-sm rounded-pill px-3 mb-1"
                onclick="document.getElementById('avatar-input').click()">
                <i class="bi bi-upload me-1"></i>
                <?= $avatarUrl ? 'Cambia foto' : 'Scegli foto' ?>
              </button>
              <p class="text-muted mb-0" style="font-size:.72rem;">
                JPG · PNG · WEBP · max 2 MB · lascia vuoto per non cambiare
              </p>
              <p id="avatar-name" class="text-primary small mb-0 fw-semibold"></p>
              <p id="avatar-err" class="text-danger small mb-0"></p>
            </div>
          </div>
        </div>
      </div>

      <!-- ── Colonna destra: sicurezza + submit ──────────────────────────────── -->
      <div class="col-lg-4">

        <div class="card border-0 shadow-sm rounded-4 p-4 mb-3">
          <h6 class="fw-bold text-muted text-uppercase small mb-1" style="letter-spacing:1px;">
            <i class="bi bi-shield-lock text-primary me-2"></i>Cambia Password
          </h6>
          <p class="text-muted" style="font-size:.75rem;">
            Lascia vuoto se non vuoi cambiare password.
          </p>

          <div class="mb-3">
            <label class="form-label small fw-bold text-secondary mb-1">Password attuale</label>
            <div class="input-group input-group-sm border rounded-3 overflow-hidden">
              <input id="oldPassword" type="password" name="oldPassword"
                class="form-control border-0" placeholder="••••••••">
              <button class="btn btn-white border-0" type="button"
                onclick="showPassword('oldPassword','icon-oldPassword')">
                <i id="icon-oldPassword" class="bi bi-eye text-muted"></i>
              </button>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-bold text-secondary mb-1">Nuova password</label>
            <div class="input-group input-group-sm border rounded-3 overflow-hidden">
              <input id="newPassword" type="password" name="newPassword"
                class="form-control border-0" placeholder="••••••••">
              <button class="btn btn-white border-0" type="button"
                onclick="showPassword('newPassword','icon-newPassword')">
                <i id="icon-newPassword" class="bi bi-eye text-muted"></i>
              </button>
            </div>
          </div>
          <div class="mb-0">
            <label class="form-label small fw-bold text-secondary mb-1">Conferma Nuova</label>
            <div class="input-group input-group-sm border rounded-3 overflow-hidden">
              <input id="confNewPassword" type="password" name="confNewPassword"
                class="form-control border-0" placeholder="••••••••">
              <button class="btn btn-white border-0" type="button"
                onclick="showPassword('confNewPassword','icon-confNewPassword')">
                <i id="icon-confNewPassword" class="bi bi-eye text-muted"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-3 bg-primary bg-opacity-10">
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary rounded-3 py-2 fw-bold shadow-sm">
              <i class="bi bi-cloud-arrow-up me-2"></i>AGGIORNA PROFILO
            </button>
            <a href="index.php?page=home&action=index"
              class="btn btn-link btn-sm text-secondary text-decoration-none">
              Annulla e torna indietro
            </a>
          </div>
        </div>

      </div>
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
    const TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    input.addEventListener('change', function() {
      const f = this.files[0];
      name.textContent = '';
      errEl.textContent = '';
      if (!f) return;

      if (!TYPES.includes(f.type)) {
        errEl.textContent = 'Formato non supportato. Usa JPG, PNG o WEBP.';
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
        preview.innerHTML =
          `<img src="${ev.target.result}"
              style="width:72px;height:72px;object-fit:cover;border-radius:50%;">`;
      };
      r.readAsDataURL(f);
    });
  })();
</script>

<br><br>