<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OurShelf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <header>
        <nav class="navbar navbar-expand-lg shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php">
                    <i class="bi bi-book-half me-2"></i>OurShelf
                </a>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <?php if (!empty($_SESSION['id_studente'])): ?>
                    <div class="user-badge px-3 py-1 rounded-pill  bg-opacity-10 small d-none d-sm-block">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= safe_string($_SESSION['email']) ?>
                    </div>
                    <a class="btn btn-light btn-sm rounded-pill px-3" href="index.php?page=login&action=logout">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                    <?php else: ?>
                    <a class="btn btn-outline-light btn-sm rounded-pill px-4"
                        href="index.php?page=login&action=index">Accedi</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main class="flex-grow-1">
        <div class="container my-5">
            <?php if (!empty($view)): ?>
            <div class="content-card p-4 p-md-5 bg-white rounded-4 shadow-sm">
                <?php include $view; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer mt-auto">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4 col-md-12 text-center text-lg-start">
                    <h5 class="footer-brand mb-3">OurShelf</h5>
                    <p class="footer-description mb-4">La piattaforma ideale per il Team 2, dedicata alla condivisione
                        fluida e organizzata della conoscenza.</p>
                    <div class="copyright small">
                        &copy; 2026 OurShelf &bull; Made with <i class="bi bi-heart-fill text-danger"></i>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 text-center text-lg-start">
                    <h6 class="footer-title">Navigazione</h6>
                    <ul class="list-unstyled footer-list">
                        <li><a href="https://www.isit100.fe.it/" target="_blank"><i
                                    class="bi bi-chevron-right me-1"></i>Isit Bassi Burgatti</a></li>
                        <li><a href="index.php?page=home&action=about"><i class="bi bi-chevron-right me-1"></i>Chi
                                siamo</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 text-center text-lg-start">
                    <h6 class="footer-title">Contatti Supporto</h6>
                    <ul class="list-unstyled footer-list">
                        <li><a href="mailto:pirazzi.8076@isit100.fe.it"><i class="bi bi-envelope-at me-2"></i>Email
                                Pirazzi</a></li>
                        <li><a href="mailto:portacci.7780@isit100.fe.it"><i class="bi bi-envelope-at me-2"></i>Email
                                Portacci</a></li>
                        <li><a href="mailto:landi.7998@isit100.fe.it"><i class="bi bi-envelope-at me-2"></i>Email
                                Landi</a></li>
                        <li><a href="mailto:anusca.7806@isit100.fe.it"><i class="bi bi-envelope-at me-2"></i>Email
                                Anusca</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>