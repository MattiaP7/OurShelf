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
				<a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/src/index.php">
					<img src="<?= BASE_URL ?>/assets/img/logo_progetto.png" alt="OurShelf Logo" class="me-2" style="height: 40px;">
					<span class="fw-bold">OurShelf</span>
				</a>

				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarNav">
					<div class="ms-auto d-flex align-items-center gap-2">

						<?php if (!empty($_SESSION['id_studente'])): ?>
							<div class="dropdown">
								<button class="btn btn-outline-dark dropdown-toggle d-flex align-items-center gap-2 border-0"
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
							<a class="btn btn-outline-primary btn-sm rounded-pill px-4" href="index.php?page=login&action=index">
								Accedi
							</a>
							<a class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm" href="index.php?page=login&action=register">
								Registrati
							</a>
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

	<script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
	</script>
	<script src="<?= BASE_URL ?>/src/utils/showPassword.js"></script>

</body>

</html>