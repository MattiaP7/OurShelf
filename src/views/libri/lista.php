<?php defined("APP") or die("Accesso negato"); ?>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
  <span class="text-muted"><?= count($libri) ?> libri trovati</span>
  <div class="btn-group btn-group-sm">
    <button class="btn btn-outline-secondary active" id="btn-card" onclick="setView('card')">&#9646;&#9646; Card</button>
    <button class="btn btn-outline-secondary" id="btn-table" onclick="setView('table')">&#9776; Tabella</button>
  </div>
</div>

<!-- VISTA CARD -->
<div id="view-card" class="row row-cols-1 row-cols-md-3 g-3">
  <?php foreach ($libri as $libro): ?>
    <div class="col">
      <div class="card h-100 border shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <span class="badge bg-info text-dark"><?= safe_string($libro['materia']) ?></span>
            <span class="badge bg-success">€ <?= number_format($libro['prezzo'], 2) ?></span>
          </div>
          <h6 class="card-title mb-1"><?= safe_string($libro['titolo']) ?></h6>
          <p class="card-text text-muted small mb-1"><?= safe_string($libro['autore']) ?> · <?= safe_string($libro['editore']) ?></p>
          <p class="card-text text-muted" style="font-size:11px"><?= safe_string($libro['anno_scolastico']) ?></p>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- VISTA TABELLA -->
<div id="view-table" style="display:none">
  <table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
      <tr>
        <th>Titolo</th>
        <th>Autore</th>
        <th>Materia</th>
        <th>Editore</th>
        <th>Anno</th>
        <th>Prezzo</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($libri as $libro): ?>
        <tr>
          <td><?= safe_string($libro['titolo']) ?></td>
          <td><?= safe_string($libro['autore']) ?></td>
          <td><span class="badge bg-info text-dark"><?= safe_string($libro['materia']) ?></span></td>
          <td><?= safe_string($libro['editore']) ?></td>
          <td><?= safe_string($libro['anno_scolastico']) ?></td>
          <td><span class="badge bg-success">€ <?= number_format($libro['prezzo'], 2) ?></span></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
  function setView(v) {
    document.getElementById('view-card').style.display = v === 'card' ? 'flex' : 'none';
    document.getElementById('view-table').style.display = v === 'table' ? 'block' : 'none';
    document.getElementById('btn-card').classList.toggle('active', v === 'card');
    document.getElementById('btn-table').classList.toggle('active', v === 'table');
  }
</script>