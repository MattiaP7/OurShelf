<?php
defined("APP") or die("Accesso negato");
// Variabili disponibili: $user (array), $classi (array)
?>

<script src="/pirazzi/OurShelf/src/utils/showPassword.js"></script>
<div class="row justify-content-center mt-4">
  <div class="col-lg-7">


    <!-- Intestazione profilo -->
    <div class="d-flex align-items-center gap-3 mb-4">
      <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0"
        style="width:56px;height:56px;">
        <span class="fw-bold text-white fs-4">
          <?= strtoupper(mb_substr($user['nome'], 0, 1)) ?>
        </span>
      </div>
      <div>
        <h4 class="fw-bold mb-0"><?= safe_string($user['nome']) ?> <?= safe_string($user['cognome']) ?></h4>
        <span class="text-muted small"><?= safe_string($user['email']) ?></span>
      </div>
    </div>

    <!-- Flash errors -->
    <?= flash_error(); ?>

    <form method="POST" action="index.php?page=users&action=update">

      <!-- ================================================
           SEZIONE 1 — Dati personali
           ================================================ -->
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h6 class="fw-bold text-muted text-uppercase small mb-3" style="letter-spacing:.8px;">
          <i class="bi bi-person me-1"></i> Dati personali
        </h6>

        <div class="row g-3">

          <div class="col-sm-6">
            <label class="form-label small fw-bold text-muted mb-1">Nome</label>
            <input type="text" name="nome" class="form-control rounded-3"
              value="<?= safe_string($user['nome']) ?>" required>
          </div>

          <div class="col-sm-6">
            <label class="form-label small fw-bold text-muted mb-1">Cognome</label>
            <input type="text" name="cognome" class="form-control rounded-3"
              value="<?= safe_string($user['cognome']) ?>" required>
          </div>

          <div class="col-sm-6">
            <label class="form-label small fw-bold text-muted mb-1">Data di nascita</label>
            <input type="date" name="data_nascita" class="form-control rounded-3"
              value="<?= safe_string($user['data_nascita']) ?>" required>
          </div>

          <div class="col-sm-6">
            <label class="form-label small fw-bold text-muted mb-1">Sesso</label>
            <select name="sesso" class="form-select rounded-3" required>
              <option value="m" <?= $user['sesso'] === 'm' ? 'selected' : '' ?>>Maschio</option>
              <option value="f" <?= $user['sesso'] === 'f' ? 'selected' : '' ?>>Femmina</option>
            </select>
          </div>

          <div class="col-12">
            <label class="form-label small fw-bold text-muted mb-1">Classe</label>
            <select name="id_classe" class="form-select rounded-3" required>
              <?php foreach ($classi as $c): ?>
                <option value="<?= (int)$c['id_classe'] ?>"
                  <?= (int)$user['id_classe'] === (int)$c['id_classe'] ? 'selected' : '' ?>>
                  <?= safe_string($c['anno']) ?>°
                  <?= safe_string($c['sezione']) ?> —
                  <?= safe_string($c['indirizzo']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-12">
            <label class="form-label small fw-bold text-muted mb-1">Email</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-envelope text-muted"></i>
              </span>
              <input required type="email" name="email" class="form-control border-start-0 rounded-end-3"
                value="<?= safe_string($user['email']) ?>">
            </div>
          </div>

        </div>
      </div>

      <!-- ================================================
           SEZIONE 2 — Cambio password (opzionale)
           ================================================ -->
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h6 class="fw-bold text-muted text-uppercase small mb-1" style="letter-spacing:.8px;">
          <i class="bi bi-lock me-1"></i> Cambio password
        </h6>
        <p class="text-muted small mb-3">
          Lascia i campi vuoti se non vuoi cambiare la password.
        </p>

        <!-- Password attuale -->
        <div class="mb-3">
          <label class="form-label small fw-bold text-muted mb-1">Password attuale</label>
          <div class="input-group shadow-sm" style="border:1px solid #dee2e6;border-radius:.375rem;overflow:hidden;">
            <span class="input-group-text bg-white border-0 pe-1">
              <i id="icon-oldPassword" class="bi bi-lock text-muted"></i>
            </span>
            <input id="oldPassword" type="password" name="oldPassword"
              class="form-control border-0 ps-1" placeholder="••••••••"
              style="box-shadow:none;">
            <span class="input-group-text bg-white border-0">
              <div class="form-check mb-0 d-flex align-items-center">
                <input class="form-check-input" type="checkbox" style="cursor:pointer;"
                  onclick="showPassword('oldPassword','icon-oldPassword')">
                <label class="form-check-label small text-muted fw-bold mb-0 ms-2" style="cursor:pointer;">
                  Mostra
                </label>
              </div>
            </span>
          </div>
        </div>

        <!-- Nuova password -->
        <div class="mb-3">
          <label class="form-label small fw-bold text-muted mb-1">Nuova password</label>
          <div class="input-group shadow-sm" style="border:1px solid #dee2e6;border-radius:.375rem;overflow:hidden;">
            <span class="input-group-text bg-white border-0 pe-1">
              <i id="icon-newPassword" class="bi bi-lock text-muted"></i>
            </span>
            <input id="newPassword" type="password" name="newPassword"
              class="form-control border-0 ps-1" placeholder="••••••••"
              style="box-shadow:none;">
            <span class="input-group-text bg-white border-0">
              <div class="form-check mb-0 d-flex align-items-center">
                <input class="form-check-input" type="checkbox" style="cursor:pointer;"
                  onclick="showPassword('newPassword','icon-newPassword')">
                <label class="form-check-label small text-muted fw-bold mb-0 ms-2" style="cursor:pointer;">
                  Mostra
                </label>
              </div>
            </span>
          </div>
        </div>

        <!-- Conferma nuova password -->
        <div class="mb-0">
          <label class="form-label small fw-bold text-muted mb-1">Conferma nuova password</label>
          <div class="input-group shadow-sm" style="border:1px solid #dee2e6;border-radius:.375rem;overflow:hidden;">
            <span class="input-group-text bg-white border-0 pe-1">
              <i id="icon-confNewPassword" class="bi bi-lock text-muted"></i>
            </span>
            <input id="confNewPassword" type="password" name="confNewPassword"
              class="form-control border-0 ps-1" placeholder="••••••••"
              style="box-shadow:none;">
            <span class="input-group-text bg-white border-0">
              <div class="form-check mb-0 d-flex align-items-center">
                <input class="form-check-input" type="checkbox" style="cursor:pointer;"
                  onclick="showPassword('confNewPassword','icon-confNewPassword')">
                <label class="form-check-label small text-muted fw-bold mb-0 ms-2" style="cursor:pointer;">
                  Mostra
                </label>
              </div>
            </span>
          </div>
        </div>

      </div>

      <!-- Bottoni -->
      <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 fw-semibold shadow-sm">
          <i class="bi bi-check-lg me-1"></i> Salva modifiche
        </button>
        <a href="index.php?page=home&action=index" class="btn btn-outline-secondary rounded-3 px-4 py-2">
          Annulla
        </a>
      </div>

    </form>
  </div>
</div>

<br><br><br><br><br>