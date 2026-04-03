<?php
// Configurazione base e caricamento utility
$base_url = "http://lab.isit100.fe.it:8092/portacci/OurShelf";
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


    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/style_layout.css">
    <link rel="icon" type="image/png" href="<?= $base_url ?>/assets/img/logo_progetto.png">

</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="<?= $base_url ?>/index.php">
                    <img src="<?= $base_url ?>/assets/img/logo_progetto.png" alt="OurShelf Logo" class="me-2">
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
                        <a class="btn btn-light btn-sm text-primary fw-bold" href="#">Registrati</a>
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
                // Contenuto di default se la view è vuota (Home Page integrata)
            ?>
            <section class="text-center py-5">
                <h1 class="display-4 fw-bold">Benvenuti su OurShelf</h1>
                <p class="lead">La piattaforma per scambiare libri scolastici all'ISI Levi di Ferrara.</p>
                <a href="index.php?page=libri&action=lista" class="btn btn-primary btn-lg mt-3">Sfoglia il catalogo</a>
            </section>
            <?php
            }
            ?>
        </div>
    </main>

    <footer class="text-center mt-auto">
        <div class="container">
            <p class="mb-1 fw-bold">&copy; 2026 OurShelf - Team 2</p>
            <p class="small text-muted mb-0">Scambia, Vendi, Leggi.</p>
        </div>
        <div class="link">
            <p class="prova">CIAO</p>
        </div>
    </footer>

    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>