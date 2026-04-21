<?php
defined("APP") or die("Accesso negato");
// Variabili disponibili: $user (array), $classi (array)
?>

<script src="/pirazzi/OurShelf/src/utils/showPassword.js"></script>

<div class="mt-4">
  <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm"
      style="width:64px;height:64px; border: 3px solid white;">
      <span class="fw-bold text-white fs-3">
        <?= strtoupper(mb_substr($user['nome'], 0, 1)) ?>
      </span>
    </div>
    <div>
      <h3 class="fw-bold mb-0 text-dark"><?= safe_string($user['nome']) ?> <?= safe_string($user['cognome']) ?></h3>
      <span class="badge bg-primary-subtle text-primary fw-medium"><?= safe_string($user['email']) ?></span>
    </div>
  </div>

  <?= flash_error(); ?>

  <form method="POST" action="index.php?page=users&action=update">
    <div class="row g-4">

      <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
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
                  <option value="<?= (int)$c['id_classe'] ?>"
                    <?= (int)$user['id_classe'] === (int)$c['id_classe'] ? 'selected' : '' ?>>
                    <?= safe_string($c['anno']) ?>° <?= safe_string($c['sezione']) ?> — <?= safe_string($c['indirizzo']) ?>
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
                <input required type="email" name="email" class="form-control bg-light border-0 py-2"
                  value="<?= safe_string($user['email']) ?>">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">

        <div class="card border-0 shadow-sm rounded-4 p-4 mb-3">
          <h6 class="fw-bold text-muted text-uppercase small mb-1" style="letter-spacing:1px;">
            <i class="bi bi-shield-lock text-primary me-2"></i>Sicurezza
          </h6>
          <p class="text-muted" style="font-size: 0.75rem;">Lascia vuoto se non vuoi cambiare password.</p>

          <div class="mb-3">
            <label class="form-label small fw-bold text-secondary mb-1">Password attuale</label>
            <div class="input-group input-group-sm border rounded-3 overflow-hidden">
              <input id="oldPassword" type="password" name="oldPassword" class="form-control border-0" placeholder="••••••••">
              <button class="btn btn-white border-0" type="button" onclick="showPassword('oldPassword','icon-oldPassword')">
                <i id="icon-oldPassword" class="bi bi-eye text-muted"></i>
              </button>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-bold text-secondary mb-1">Nuova password</label>
            <div class="input-group input-group-sm border rounded-3 overflow-hidden">
              <input id="newPassword" type="password" name="newPassword" class="form-control border-0" placeholder="••••••••">
              <button class="btn btn-white border-0" type="button" onclick="showPassword('newPassword','icon-newPassword')">
                <i id="icon-newPassword" class="bi bi-eye text-muted"></i>
              </button>
            </div>
          </div>

          <div class="mb-0">
            <label class="form-label small fw-bold text-secondary mb-1">Conferma Nuova</label>
            <div class="input-group input-group-sm border rounded-3 overflow-hidden">
              <input id="confNewPassword" type="password" name="confNewPassword" class="form-control border-0" placeholder="••••••••">
              <button class="btn btn-white border-0" type="button" onclick="showPassword('confNewPassword','icon-confNewPassword')">
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
            <a href="index.php?page=home&action=index" class="btn btn-link btn-sm text-secondary text-decoration-none">
              Annulla e torna indietro
            </a>
          </div>
        </div>

      </div>
    </div>
  </form>
</div>

<br><br><br><br>