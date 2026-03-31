<?php defined("APP") or die("Accesso negato"); ?>
<div class="login-container">
  <h4 class="fw-bold mb-3 text-center">Cambia Password</h4>

  <?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger py-2 mb-3 border-0 shadow-sm" style="font-size: 0.85rem;">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>
      <?php foreach ($_SESSION['errors'] as $error): ?>
        <div class="d-block"><?= $error ?></div>
      <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['errors']); ?>
  <?php endif; ?>

  <form action="index.php?page=login&action=updatePassword" method="post">

    <div class="mb-3">
      <label class="form-label small fw-bold text-muted mb-1">Email</label>
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0">
          <i class="bi bi-envelope text-muted"></i>
        </span>
        <input type="email" name="email" class="form-control form-control-sm border-start-0 ps-0"
          placeholder="mario@esempio.it"
          value="<?= safe_string($_SESSION['email'] ?? '') ?>"
          required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-bold text-muted mb-1">Password attuale</label>
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-white border-end-0">
          <i class="bi bi-lock text-muted"></i>
        </span>
        <input id="oldPassword" type="password" name="oldPassword"
          class="form-control border-start-0 ps-0"
          placeholder="••••••••" required>
        <div class="input-group-text">
          <div class="form-check d-flex align-items-center mb-0">
            <input class="form-check-input mt-0 me-2" type="checkbox" onclick="showPassword('oldPassword')">
            <label class="form-check-label small text-muted fw-bold">Mostra</label>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-bold text-muted mb-1">Nuova Password</label>
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-white border-end-0">
          <i class="bi bi-shield-lock text-muted"></i>
        </span>
        <input id="newPassword" type="password" name="newPassword"
          class="form-control border-start-0 ps-0"
          placeholder="••••••••" required>
        <div class="input-group-text">
          <div class="form-check d-flex align-items-center mb-0">
            <input class="form-check-input mt-0 me-2" type="checkbox" onclick="showPassword('newPassword')">
            <label class="form-check-label small text-muted fw-bold">Mostra</label>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label small fw-bold text-muted mb-1">Conferma Nuova Password</label>
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-white border-end-0">
          <i class="bi bi-shield-lock text-muted"></i>
        </span>
        <input id="reNewPassword" type="password" name="reNewPassword"
          class="form-control border-start-0 ps-0"
          placeholder="••••••••" required>
        <div class="input-group-text">
          <div class="form-check d-flex align-items-center mb-0">
            <input class="form-check-input mt-0 me-2" type="checkbox" onclick="showPassword('reNewPassword')">
            <label class="form-check-label small text-muted fw-bold">Mostra</label>
          </div>
        </div>
      </div>
      <div id="passwordError" class="small mt-1 text-danger text-center"></div>
    </div>

    <button type="submit" class="btn btn-primary w-100 btn-login mb-3 shadow-sm">
      Aggiorna Password <i class="bi bi-check-lg ms-1"></i>
    </button>

    <div class="text-center">
      <a href="index.php?page=login" class="small text-decoration-none fw-bold">
        <i class="bi bi-arrow-left me-1"></i> Torna al Login
      </a>
    </div>

  </form>
</div>
<script src="/pirazzi/OurShelf/src/utils/showPassword.js"></script>