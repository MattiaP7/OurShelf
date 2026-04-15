<?php
// Configurazione base e caricamento utility
require_once __DIR__ . '/../utils/helpers.php';
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OurShelf - Scambio Libri</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style_layout.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/img/logo_progetto.png">

</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/src/index.php">
                    <img src="<?= BASE_URL ?>/assets/img/logo_progetto.png" alt="OurShelf Logo" class="me-2">
                    <span class="fw-bold">OurShelf</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <?php if (!empty($_SESSION['id_studente'])): ?>

                            <div class="dropdown">
                                <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center gap-2 border-0"
                                    type="button"
                                    id="userMenu"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bi bi-person-circle fs-5"></i>
                                    <span class="small fw-bold"><?= safe_string($_SESSION['email']) ?></span>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userMenu">
                                    <li>
                                        <h6 class="dropdown-header">Area Utente</h6>
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="index.php?page=user&action=dashboard">
                                            <i class="bi bi-speedometer2 text-primary"></i> Dashboard
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="index.php?page=libri&action=miei">
                                            <i class="bi bi-book text-primary"></i> I miei Libri
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="index.php?page=login&action=changePassword">
                                            <i class="bi bi-key text-primary"></i> Cambia Password
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="index.php?page=login&action=logout">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a class="btn btn-outline-light btn-sm" href="index.php?page=login&action=index">Accedi</a>
                            <a class="btn btn-light btn-sm text-primary fw-bold shadow-sm" href="index.php?page=login&action=register">Registrati</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <?php if (!empty($_SESSION['id_studente'])): ?>
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <?php flash_success(); ?>
        </div>
    <?php endif; ?>

    <main class="container">
        <div class="view-container">
            <?php
            if (!empty($view) && file_exists($view)) {
                include $view;
            } else
                die("Pagina non ricaricata riprova tran po' ... ");
            ?>
        </div>
    </main>

    <footer class="text-center mt-auto">
        <div class="footer-container">
            <p class="mb-1 fw-bold">&copy; 2026 OurShelf - Team 2</p>
            <p class="small text-muted mb-0">Scambia, Vendi, Leggi.</p>
        </div>
        <div class="link">
            <p class="prova">qua ci dovrebbero essere tutti i link</p>
        </div>
    </footer>

    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
    <script src="<?= BASE_URL ?>/src/utils/showPassword.js"></script>

</body>

</html>