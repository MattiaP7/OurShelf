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
                <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/index.php">
                    <img src="<?= BASE_URL ?>/assets/img/logo_progetto.png" alt="OurShelf Logo" class="me-2">
                    <span class="fw-bold">OurShelf</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <?php if (!empty($_SESSION['id_studente'])): ?>
                        <span class="text-white me-2">
                            <i class="bi bi-person-circle"></i> <?= safe_string($_SESSION['email']) ?>
                        </span>
                        <a class="btn btn-outline-light btn-sm" href="index.php?page=login&action=logout">Logout</a>
                        <?php else: ?>
                        <a class="btn btn-outline-light btn-sm" href="index.php?page=login&action=index">Accedi</a>
                        <a class="btn btn-light btn-sm text-primary fw-bold"
                            href="index.php?page=login&action=register">Registrati</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="view-container">
            <?php
            if (!empty($view) && file_exists($view)) {
                include $view;
            } else {
                die("Pagina non ricaricata riprova tran po' ... ");
            ?>

            <?php
            }
            ?>
        </div>
    </main>

    <footer class="container">
        <div class="row">
            <div class="col-md-3 footer-container">
                <p class="mb-1 fw-bold">&copy; 2026 OurShelf - Team 2</p>
                <p class="small text-muted mb-0">Scambia, Vendi, Leggi.</p>
            </div>
            <div class="col-md-3 link">
               <label for="Navigazione e Utility">Navigazione e Utility</label>
               <ul>
                <li><a href="<?php BASE_URL?>/../../link/chi_siamo.php">Chi siamo</a></li>
                <li><a href="https://www.isit100.fe.it/">Isit Bassi Burgatti</a></li>
                <li></li>
               </ul>
            </div>
            <div class="col-md-3 link">
               <label for="Contatti">Contatti</label>
               <ul>
                <label for="">Email group leader</label>
                <li><a href="">pirazzi.8076@isit100.fe.it</a></li>
                <label for="">Email front-developer</label>
                <li><a href="">portacci.7780@isit100.fe.it</a></li>
                <label for="">Email back-ender</label>
                <li><a href="">Email back-ender</a></li>
                <label for="">Email data analist</label>
                <li><a href="">Email data analist</a></li>
               </ul>
            </div>
        </div>
    </footer>

    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>