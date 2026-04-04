<?php
include_once __DIR__ . "/../utils/helpers.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>main-view</title>
    <meta name="description" content="Generated with Layoutit" />
    <link rel="stylesheet" href="./bootstrap.min.css" />
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <!-- qua è dove ci devono essere tutte le copertine dei libri-->
            </div>
            <div class="form">
                <h3 class="h3 text-info text-start">
                    Cerca il libro giusto per te
                </h3>
                <form action="index.php?page=Annunci&action=Annunci" method="post">
                    <div class="mb-3">
                        <label class=isbn>
                            ISBN
                        </label>
                        <input type="text" name="ISBN" id="ISBN" aria-roledescription="ISBN es.1788808699862" required>
                    </div>
                    <div class="mb-3">
                        <label class="titolo">
                            Titolo
                        </label>
                        <input class="form-control" type="text" name="titolo" id="titolo"
                            placeholder="Matematica a colori" required />
                    </div>
                    <div class="mb-3">
                        <label class="materia">
                            Materia
                        </label>
                        <input class="form-control" type="text" name="materia" id="materia" placeholder=" Matematica"
                            required />
                    </div>
                    <div class="mb-3">
                        <label class="editore">
                            Editore
                        </label>
                        <input class="form-control" type="text" name="editore" id="editore" placeholder="Zanichelli"
                            required />
                    </div>
                    <div>
                        <button class="btn btn-primary" type="submit">
                            Cerca
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="./bootstrap.bundle.min.js"></script>
</body>

</html>