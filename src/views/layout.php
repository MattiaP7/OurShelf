<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>OurShelf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">OurShelf</a>
            <a class="nav-link" href="index.php?page=login&action=index">Registrati</a>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (!empty($view)): ?>
            <div class="mt-4 p-4 bg-white rounded-4 shadow-sm border">
                <?php include $view; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="text-center mt-5 py-3 border-top">
        <p>&copy; 2026 OurShelf - Team 2</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>