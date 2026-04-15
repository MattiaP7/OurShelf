<?php defined("APP") or die("Accesso negato"); ?>
<div class="login-container">
  <h4 class="fw-bold mb-3 text-center">Cambia Password</h4>

  <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <?php flash_error(); ?>
  </div>

  <form action="index.php?page=login&action=updatePassword" method="post">

    <div class="mb-3">
      <label class="form-label small fw-bold text-muted mb-1">Email</label>
      <div class="input-group shadow-sm" style="border: 1px solid #dee2e6; border-radius: 0.375rem; overflow: hidden;">

        <span class="input-group-text bg-white border-0 pe-1">
          <i class="bi bi-envelope text-muted"></i>
        </span>

        <input
          type="email"
          name="email"
          /* Rimosso 'form-control-sm' per matchare la password,
          sostituito 'border-start-0' con 'border-0'
          */
          class="form-control border-0 ps-1"
          placeholder="mario@esempio.it"
          style="box-shadow: none;"
          value="<?= safe_string($_SESSION['email'] ?? '') ?>"
          required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-bold text-muted mb-1">Password attuale</label>
      <div class="input-group shadow-sm" style="border: 1px solid #dee2e6; border-radius: 0.375rem; overflow: hidden;">

        <span class="input-group-text bg-white border-0 pe-1">
          <i id="icon-oldPassword" class="bi bi-lock text-muted"></i>
        </span>

        <input
          id="oldPassword"
          type="password"
          name="oldPassword"
          class="form-control border-0 ps-1"
          placeholder="••••••••"
          style="box-shadow: none;"
          required>

        <span class="input-group-text bg-white border-0">
          <div class="form-check mb-0 d-flex align-items-center">
            <input class="form-check-input"
              type="checkbox"
              style="cursor: pointer;"
              onclick="showPassword('oldPassword', 'icon-oldPassword')">
            <label class="form-check-label small text-muted fw-bold mb-0 ms-2" style="cursor: pointer;">
              Mostra
            </label>
          </div>
        </span>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-bold text-muted mb-1">Nuova Password</label>
      <div class="input-group shadow-sm" style="border: 1px solid #dee2e6; border-radius: 0.375rem; overflow: hidden;">

        <span class="input-group-text bg-white border-0 pe-1">
          <i id="icon-newPassword" class="bi bi-lock text-muted"></i>
        </span>

        <input
          id="newPassword"
          type="password"
          name="newPassword"
          class="form-control border-0 ps-1"
          placeholder="••••••••"
          style="box-shadow: none;"
          required>

        <span class="input-group-text bg-white border-0">
          <div class="form-check mb-0 d-flex align-items-center">
            <input class="form-check-input"
              type="checkbox"
              style="cursor: pointer;"
              onclick="showPassword('newPassword', 'icon-newPassword')">
            <label class="form-check-label small text-muted fw-bold mb-0 ms-2" style="cursor: pointer;">
              Mostra
            </label>
          </div>
        </span>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-bold text-muted mb-1">Conferma Nuova Password</label>
      <div class="input-group shadow-sm" style="border: 1px solid #dee2e6; border-radius: 0.375rem; overflow: hidden;">

        <span class="input-group-text bg-white border-0 pe-1">
          <i id="icon-confNewPassword" class="bi bi-lock text-muted"></i>
        </span>

        <input
          id="confNewPassword"
          type="password"
          name="confNewPassword"
          class="form-control border-0 ps-1"
          placeholder="••••••••"
          style="box-shadow: none;"
          required>

        <span class="input-group-text bg-white border-0">
          <div class="form-check mb-0 d-flex align-items-center">
            <input class="form-check-input"
              type="checkbox"
              style="cursor: pointer;"
              onclick="showPassword('confNewPassword', 'icon-confNewPassword')">
            <label class="form-check-label small text-muted fw-bold mb-0 ms-2" style="cursor: pointer;">
              Mostra
            </label>
          </div>
        </span>
      </div>
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