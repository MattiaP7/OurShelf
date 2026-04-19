<div class="container-fluid py-5"> <div class="row g-4 justify-content-center"> <div class="col-lg-8 col-md-7">
      <div class="p-4 border-start border-4 border-primary bg-white shadow-sm rounded-3" style="min-height: 600px;">
        <h2 class="fw-bold mb-4">Annunci Recenti</h2>
        <p class="text-muted">Esplora gli ultimi libri inseriti dalla community.</p>
        <hr>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-5">
      <div class="form-container shadow-sm p-4 bg-white rounded-3">
        <h3 class="h4 fw-bold text-primary mb-4">Cerca il libro giusto</h3>
        <form action="index.php?page=Annunci&action=Annunci" method="post">
          <div class="mb-3">
            <label class="form-label fw-semibold">ISBN</label>
            <input type="text" name="ISBN" class="form-control" placeholder="es. 97888..." required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Titolo</label>
            <input type="text" name="titolo" class="form-control" placeholder="Matematica a colori" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Materia</label>
              <input type="text" name="materia" class="form-control" placeholder="Matematica" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Editore</label>
              <input type="text" name="editore" class="form-control" placeholder="Zanichelli" required>
            </div>
          </div>
          <button class="btn btn-primary w-100 py-3 rounded-pill fw-bold mt-3 shadow-sm" type="submit">
            <i class="bi bi-search me-2"></i> Avvia Ricerca
          </button>
        </form>
      </div>
    </div>

  </div>
</div>
