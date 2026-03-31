<form action="index.php?page=login&action=store" method="post">
    <h4 class="fw-bold mb-3 text-center">Registrazione</h4>

    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger small">
            <ul class="mb-0">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= safe_string($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success small">
            <?= safe_string($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row g-2 mb-2">
        <div class="col-6">
            <label class="form-label small fw-bold text-muted mb-1">Nome</label>
            <input id="name" type="text" name="nome" class="form-control form-control-sm" placeholder="Mario" required>
        </div>
        <div class="col-6">
            <label class="form-label small fw-bold text-muted mb-1">Cognome</label>
            <input id="surname" type="text" name="cognome" class="form-control form-control-sm" placeholder="Rossi" required>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-7">
            <div class="form-floating">
                <input
                    id="dob"
                    type="date"
                    name="data_nascita"
                    class="form-control form-control-sm"
                    placeholder="Data di Nascita"
                    required>
                <label for="dob" class="text-muted fw-bold small text-uppercase">Data di Nascita</label>
            </div>
        </div>

        <div class="col-md-5">
            <div class="form-floating">
                <select id="gender" name="sesso" class="form-select form-select-sm" required>
                    <option value="" selected disabled hidden>Scegli...</option>
                    <option value="m">Maschio</option>
                    <option value="f">Femmina</option>
                </select>
                <label for="gender" class="text-muted fw-bold small text-uppercase">Sesso</label>
            </div>
        </div>
    </div>

    <div class="mb-2">
        <label class="form-label small fw-bold text-muted mb-1">Email</label>
        <input id="email" type="email" name="email" class="form-control form-control-sm" placeholder="mario@esempio.it" required>
        <div id="emailError" class="small mt-1"></div>
    </div>

    <div class="mb-2">
        <label class="form-label small fw-bold text-muted mb-1">Password</label>
        <input id="password" type="password" name="password" class="form-control form-control-sm" placeholder="••••••••" required>
        <div class="form-check mt-1">
            <input class="form-check-input" type="checkbox" id="togglePass" onclick="showPassword('password')">
            <label class="form-check-label small text-muted" for="togglePass">Mostra Password</label>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label small fw-bold text-muted mb-1">Conferma Password</label>
        <input id="confirmPassword" type="password" name="password_confirm" class="form-control form-control-sm" placeholder="••••••••" required>
        <div id="passwordError" class="small mt-1 text-center"></div>
        <div class="form-check mt-1">
            <input class="form-check-input" type="checkbox" id="toggleConfirm" onclick="showPassword('confirmPassword')">
            <label class="form-check-label small text-muted" for="toggleConfirm">Mostra Password</label>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label small fw-bold text-muted mb-1">Classe</label>
        <select name="id_classe" class="form-select" required>
            <option value="0">-- Seleziona la tua classe --</option>
            <?php if (!empty($classi)): ?>
                <?php foreach ($classi as $classe): ?>
                    <option value="<?= $classe['id_classe'] ?>">
                        <?= $classe['anno'] . $classe['sezione'] . " - " . $classe['indirizzo'] ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option disabled>Nessuna classe disponibile</option>
            <?php endif; ?>
        </select>
    </div>

    <button type="submit" id="submitBtn" class="btn btn-primary w-100 btn-login mb-3">
        Crea Account <i class="bi bi-person-plus ms-1"></i>
    </button>

    <div class="text-center">
        <a href="index.php?page=login" class="small text-decoration-none fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Torna al Login
        </a>
    </div>
</form>

<script src="/pirazzi/OurShelf/src/utils/showPassword.js"></script>