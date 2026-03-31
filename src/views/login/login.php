<?php defined("APP") or die("Accesso negato"); ?>
<div class="login-container">
    <h4 class="fw-bold mb-3 text-center">Accedi</h4>

    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger py-2 mb-3 border-0 shadow-sm" role="alert" style="font-size: 0.85rem;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <div class="d-block"><?= $error ?></div>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success py-2 mb-3 border-0 shadow-sm" role="alert" style="font-size: 0.85rem;">
            <i class="bi bi-check-all me-2"></i>
            <span><?= $_SESSION['success'] ?></span>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form action="index.php?page=login&action=check" method="post">
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted mb-1">Email</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-envelope text-muted"></i>
                </span>
                <input id="email" type="email" name="email"
                    class="form-control form-control-sm border-start-0 ps-0"
                    placeholder="mario@esempio.it" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold text-muted mb-1">Password</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-shield-lock text-muted"></i>
                </span>
                <input id="password" type="password" name="password"
                    class="form-control border-start-0 ps-0"
                    placeholder="••••••••" required>
                <div class="input-group-text">
                    <div class="form-check d-flex align-items-center mb-0">
                        <input class="form-check-input mt-0 me-2" type="checkbox" id="togglePass" onclick="showPassword('password')">
                        <label class="form-check-label small text-muted fw-bold" for="togglePass">Mostra</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mt-1 mb-3">
            <a href="index.php?page=login&action=changePassword" class="text-decoration-none small text-muted">
                Cambia password
            </a>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-login mb-3 shadow-sm">
            Entra <i class="bi bi-box-arrow-right ms-1"></i>
        </button>

        <div class="d-flex align-items-center my-3">
            <hr class="flex-grow-1 text-muted opacity-25">
            <span class="mx-2 small text-muted">oppure</span>
            <hr class="flex-grow-1 text-muted opacity-25">
        </div>

        <div class="text-center">
            <p class="small text-muted mb-2">Non hai ancora un account?</p>
            <a href="index.php?page=login&action=register" class="btn btn-outline-success btn-sm w-100 rounded-pill fw-bold">
                <i class="bi bi-person-plus me-1"></i> Registrati Ora
            </a>
        </div>
    </form>
</div>

<script src="/pirazzi/OurShelf/src/utils/showPassword.js"></script>