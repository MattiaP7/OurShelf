<?php defined("APP") or die("Non puoi accedere direttamente") ?>

<div class="auth-card">
  <div class="text-center mb-4">
    <h3 class="fw-bold">Bentornato!</h3>
    <p class="text-muted small">Inserisci le tue credenziali per accedere. Pronto a ricominciare?</p>
  </div>

  <form action="index.php?page=login&action=check" method="post" id="login-form" novalidate>

    <!-- EMAIL -->
    <div class="mb-3">
      <label class="form-label small fw-bold text-muted mb-1">Email</label>
      <div class="input-group shadow-sm" style="border: 1px solid #dee2e6; border-radius: 0.375rem; overflow: hidden;">
        <span class="input-group-text bg-white border-0 pe-1">
          <i id="icon-email" class="bi bi-envelope text-muted"></i>
        </span>
        <input
          id="email"
          type="email"
          name="email"
          class="form-control border-0 ps-1"
          placeholder="nome.cognome@isit100.fe.it"
          style="box-shadow: none;"
          required>
      </div>
      <!-- Hint sempre visibile -->
      <div class="form-text">
        <i class="bi bi-info-circle me-1"></i>
        Usa la tua email istituzionale <strong>@isit100.fe.it</strong>
      </div>
      <!-- Feedback errore email, nascosto di default -->
      <div id="email-error" class="form-text text-danger d-none">
        <i class="bi bi-exclamation-circle me-1"></i>
        L'email deve terminare con <strong>@isit100.fe.it</strong>
      </div>
    </div>

    <!-- PASSWORD -->
    <div class="mb-3">
      <label class="form-label small fw-bold text-muted mb-1">Password</label>
      <div class="input-group shadow-sm" style="border: 1px solid #dee2e6; border-radius: 0.375rem; overflow: hidden;">
        <span class="input-group-text bg-white border-0 pe-1">
          <i id="icon-password" class="bi bi-lock text-muted"></i>
        </span>
        <input
          id="password"
          type="password"
          name="password"
          class="form-control border-0 ps-1"
          placeholder="••••••••"
          style="box-shadow: none;"
          required>
        <span class="input-group-text bg-white border-0">
          <div class="form-check mb-0 d-flex align-items-center">
            <input class="form-check-input" type="checkbox" style="cursor: pointer;"
              onclick="showPassword('password', 'icon-password')">
            <label class="form-check-label small text-muted fw-bold mb-0 ms-2" style="cursor: pointer;">
              Mostra
            </label>
          </div>
        </span>
      </div>
      <div class="form-text">
        <i class="bi bi-info-circle me-1"></i>
        Minimo 8 caratteri
      </div>
    </div>

    <div class="text-end mb-4">
      <a href="index.php?page=login&action=changePassword" class="text-decoration-none small">
        Password dimenticata?
      </a>
    </div>

    <button type="submit" class="btn btn-primary w-100 btn-modern shadow-sm">
      Entra nel profilo
      <i class="bi bi-arrow-right-short ms-2"></i>
    </button>

    <div class="divider"><span>oppure</span></div>

    <div class="text-center">
      <a href="index.php?page=login&action=register" class="btn btn-outline-success w-100 btn-modern">
        <i class="bi bi-person-plus me-2"></i> Crea un nuovo account
      </a>
    </div>

  </form>
</div>