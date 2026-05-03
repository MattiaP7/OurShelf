<?php
// OurShelf/src/views/layout.php

/** @var string $title - titolo dinamico della pagina */

// ── BASE URL ──────────────────────────────────────────────────────────────────
// Il <base href> punta a .../src/, quindi qualsiasi URL che inizia con /
// viene ignorato dal browser e risolto dalla base.
// Usiamo questa costante per costruire URL assoluti agli upload che funzionano
// indipendentemente dal <base href>.
// Adatta il prefisso se cambia il percorso del progetto sul server.
define('APP_BASE_URL', 'http://lab.isit100.fe.it:8092/pirazzi/OurShelf');

// Avatar navbar: lo leggiamo qui una volta sola per tutto il layout
$_navAvatarUrl = '';
if (!empty($_SESSION['id_studente']) && !empty($_SESSION['foto'])) {
  $_navAvatarUrl = APP_BASE_URL . '/public/uploads/users/' . $_SESSION['foto'];
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OurShelf - <?= $title ?></title>
  <base href="http://lab.isit100.fe.it:8092/pirazzi/OurShelf/src/">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../assets/css/style_layout.css">
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg shadow-sm">
      <div class="container d-flex align-items-center justify-content-between">

        <a class="navbar-brand d-flex align-items-center gap-3" href="index.php">
          <div class="logo-wrapper">
            <img src="../assets/img/logo_progetto.png" alt="OurShelf Logo">
          </div>
          <span class="brand-name fw-bold">OurShelf</span>
        </a>

        <div class="header-center-content d-none d-lg-flex">
          <div class="icone-google">
            <span class="material-symbols-outlined">menu_book</span>
            <span class="material-symbols-outlined search-icon">search</span>
          </div>
          <div class="frase">
            <h3 class="typing-text">Ogni libro è un viaggio. Dove vuoi andare oggi?</h3>
          </div>
        </div>

        <div class="d-flex align-items-center gap-2">
          <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto d-flex align-items-center gap-2">

              <?php if (!empty($_SESSION['id_studente'])): ?>
                <div class="dropdown">
                  <button class="btn btn-outline-dark dropdown-toggle d-flex align-items-center gap-2 border-0"
                    type="button" id="userMenu"
                    data-bs-toggle="dropdown" aria-expanded="false">

                    <?php if ($_navAvatarUrl): ?>
                      <!-- Foto profilo reale -->
                      <img src="<?= $_navAvatarUrl ?>"
                        alt="Avatar"
                        class="rounded-circle border"
                        style="width:32px;height:32px;object-fit:cover;"
                        onerror="this.outerHTML='<i class=\'bi bi-person-circle fs-5\'></i>'">

                    <?php else: ?>
                      <!-- Iniziale colorata come fallback -->
                      <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                        style="width:32px;height:32px;font-size:.85rem;flex-shrink:0;">
                        <?= strtoupper($_SESSION['name'][0]) ?>
                      </div>
                    <?php endif; ?>

                    <span class="small fw-bold d-none d-md-inline">
                      <?= safe_string($_SESSION['email']) ?>
                    </span>
                  </button>

                  <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2"
                    aria-labelledby="userMenu">
                    <li>
                      <h6 class="dropdown-header">Area Utente</h6>
                    </li>
                    <li>
                      <a class="dropdown-item d-flex align-items-center gap-2"
                        href="index.php?page=dashboard&action=index">
                        <i class="bi bi-speedometer2 text-primary"></i> Dashboard
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item d-flex align-items-center gap-2"
                        href="index.php?page=users&action=index">
                        <i class="bi bi-person-circle text-primary"></i> Area Utente
                      </a>
                    </li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>
                    <li>
                      <a class="dropdown-item d-flex align-items-center gap-2 text-danger"
                        href="index.php?page=login&action=logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                      </a>
                    </li>
                  </ul>
                </div>

              <?php else: ?>
                <a class="btn btn-outline-primary btn-sm rounded-pill px-4"
                  href="index.php?page=login&action=index">
                  <i class="fa-solid fa-user"></i> Accedi
                </a>
                <a class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm"
                  href="index.php?page=login&action=register">
                  <i class="fa-solid fa-user-plus"></i> Registrati
                </a>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <div class="container mt-3">
    <?php
    flash_error();
    flash_success();
    ?>
  </div>

  <main class="container my-5">
    <div class="view-container">
      <?php
      if (!empty($view) && file_exists($view)) {
        include $view;
      } else {
        include __DIR__ . '/404.php';
      }
      ?>
    </div>
  </main>

  <footer class="footer">
    <div class="container">
      <div class="row gy-5">

        <div class="col-lg-4 col-md-12 text-center text-lg-start">
          <h5 class="footer-brand mb-3">OurShelf</h5>
          <p class="footer-description mb-4">
            Il mercatino dei libri dell'ISIT Bassi Burgatti.
            Aiutiamo gli studenti a risparmiare e a dare nuova vita ai testi scolastici
            all'interno della nostra community.
          </p>
          <div class="copyright small text-muted">
            &copy; <span id="year"></span> OurShelf &bull; Sviluppato dal Team 2<br>
            Made with <i class="bi bi-heart-fill text-danger"></i> for Cento
          </div>
        </div>

        <div class="col-lg-4 col-md-6 text-center text-lg-start">
          <h6 class="footer-title">Link Utili</h6>
          <ul class="list-unstyled footer-list">
            <li class="mb-3">
              <a href="https://www.isit100.fe.it/" target="_blank"
                class="d-flex align-items-center justify-content-center justify-content-lg-start text-decoration-none footer-link">
                <span class="bottone-btm me-3"><i class="fa-solid fa-school"></i></span>
                <span class="d-flex flex-column text-start">
                  <span class="fw-semibold small">Sito Istituzionale</span>
                  <span class="small text-muted">ISIT Bassi Burgatti</span>
                </span>
              </a>
            </li>
            <li class="mb-3">
              <a href="index.php?page=home&action=about"
                class="d-flex align-items-center justify-content-center justify-content-lg-start text-decoration-none footer-link">
                <span class="bottone-btm me-3"><i class="fa-solid fa-user"></i></span>
                <span class="d-flex flex-column text-start">
                  <span class="fw-semibold small">Chi siamo</span>
                  <span class="small text-muted">Il team di sviluppo</span>
                </span>
              </a>
            </li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-6 text-center text-lg-start">
          <h6 class="footer-title">Supporto e Segnalazioni</h6>
          <p class="text-muted small mb-3">Hai riscontrato un problema? Contatta uno dei nostri sviluppatori:</p>
          <ul class="list-unstyled footer-list">
            <li class="mb-2">
              <a href="mailto:pirazzi.8076@isit100.fe.it" class="text-decoration-none">
                <i class="bi bi-envelope-at me-2 text-primary"></i>Mattia Pirazzi
              </a>
            </li>
            <li class="mb-2">
              <a href="mailto:portacci.7780@isit100.fe.it" class="text-decoration-none">
                <i class="bi bi-envelope-at me-2 text-primary"></i>Matteo Portacci
              </a>
            </li>
            <li class="mb-2">
              <a href="mailto:landi.7998@isit100.fe.it" class="text-decoration-none">
                <i class="bi bi-envelope-at me-2 text-primary"></i>Alessandro Landi
              </a>
            </li>
            <li class="mb-2">
              <a href="mailto:anusca.7806@isit100.fe.it" class="text-decoration-none">
                <i class="bi bi-envelope-at me-2 text-primary"></i>Ionut Anusca
              </a>
            </li>
          </ul>
        </div>

      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="utils/showPassword.js"></script>
  <script>
    document.getElementById("year").textContent = `2026 - ${new Date().getFullYear()}`;
  </script>
</body>

</html>