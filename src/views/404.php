<?php
// OurShelf/src/views/errors/404.php
// Inclusa dal router quando la pagina/action non esiste.
// Non ha bisogno di variabili esterne — è autonoma.
// Il layout la include come qualsiasi altra view (con $title e $view).


?>

<div style="min-height:60vh;display:flex;align-items:center;justify-content:center;padding:3rem 1rem;">
  <div style="text-align:center;max-width:480px;width:100%;">

    <!-- Numero 404 stilizzato -->
    <div style="position:relative;display:inline-block;margin-bottom:2rem;">
      <span style="font-size:clamp(96px,20vw,160px);font-weight:700;line-height:1;
                   color:var(--color-background-secondary);
                   -webkit-text-stroke:2px var(--color-border-secondary);
                   letter-spacing:-4px;user-select:none;">
        404
      </span>
      <!-- Icona libro sovrapposta -->
      <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
        <div style="width:64px;height:64px;border-radius:var(--border-radius-lg);
                    background:var(--color-background-primary);
                    border:0.5px solid var(--color-border-tertiary);
                    display:flex;align-items:center;justify-content:center;">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
            stroke="var(--color-text-secondary)" stroke-width="1.5"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Titolo -->
    <h1 style="font-size:1.5rem;font-weight:500;color:var(--color-text-primary);margin:0 0 .75rem;">
      Pagina non trovata
    </h1>

    <!-- Sottotitolo -->
    <p style="font-size:1rem;color:var(--color-text-secondary);line-height:1.6;margin:0 0 2rem;">
      La pagina che cerchi non esiste o è stata rimossa.<br>
      Prova a tornare alla bacheca degli annunci.
    </p>

    <!-- Azioni -->
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
      <a href="index.php"
        style="display:inline-flex;align-items:center;gap:8px;
                padding:.625rem 1.5rem;border-radius:50px;
                background:var(--color-text-primary);color:var(--color-background-primary);
                font-size:.9rem;font-weight:500;text-decoration:none;
                border:0.5px solid transparent;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
          <polyline points="9 22 9 12 15 12 15 22" />
        </svg>
        Torna alla Home
      </a>

      <a href="javascript:history.back()"
        style="display:inline-flex;align-items:center;gap:8px;
                padding:.625rem 1.5rem;border-radius:50px;
                background:transparent;color:var(--color-text-primary);
                font-size:.9rem;font-weight:500;text-decoration:none;
                border:0.5px solid var(--color-border-secondary);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6" />
        </svg>
        Torna indietro
      </a>
    </div>

    <!-- Link rapidi -->
    <div style="margin-top:3rem;padding-top:2rem;border-top:0.5px solid var(--color-border-tertiary);">
      <p style="font-size:.8rem;color:var(--color-text-tertiary);margin:0 0 1rem;">
        Oppure vai direttamente a:
      </p>
      <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;">

        <a href="index.php?page=home&action=index"
          style="display:inline-flex;align-items:center;gap:6px;
                  padding:.375rem .875rem;border-radius:50px;
                  background:var(--color-background-secondary);
                  color:var(--color-text-secondary);
                  font-size:.8rem;text-decoration:none;
                  border:0.5px solid var(--color-border-tertiary);">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
          Annunci
        </a>

        <?php if (!empty($_SESSION['id_studente'])): ?>
          <a href="index.php?page=dashboard&action=index"
            style="display:inline-flex;align-items:center;gap:6px;
                    padding:.375rem .875rem;border-radius:50px;
                    background:var(--color-background-secondary);
                    color:var(--color-text-secondary);
                    font-size:.8rem;text-decoration:none;
                    border:0.5px solid var(--color-border-tertiary);">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7" />
              <rect x="14" y="3" width="7" height="7" />
              <rect x="14" y="14" width="7" height="7" />
              <rect x="3" y="14" width="7" height="7" />
            </svg>
            Dashboard
          </a>

          <a href="index.php?page=annunci&action=crea"
            style="display:inline-flex;align-items:center;gap:6px;
                    padding:.375rem .875rem;border-radius:50px;
                    background:var(--color-background-secondary);
                    color:var(--color-text-secondary);
                    font-size:.8rem;text-decoration:none;
                    border:0.5px solid var(--color-border-tertiary);">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19" />
              <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Pubblica annuncio
          </a>

        <?php else: ?>
          <a href="index.php?page=login&action=index"
            style="display:inline-flex;align-items:center;gap:6px;
                    padding:.375rem .875rem;border-radius:50px;
                    background:var(--color-background-secondary);
                    color:var(--color-text-secondary);
                    font-size:.8rem;text-decoration:none;
                    border:0.5px solid var(--color-border-tertiary);">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
              <polyline points="10 17 15 12 10 7" />
              <line x1="15" y1="12" x2="3" y2="12" />
            </svg>
            Accedi
          </a>

          <a href="index.php?page=login&action=register"
            style="display:inline-flex;align-items:center;gap:6px;
                    padding:.375rem .875rem;border-radius:50px;
                    background:var(--color-background-secondary);
                    color:var(--color-text-secondary);
                    font-size:.8rem;text-decoration:none;
                    border:0.5px solid var(--color-border-tertiary);">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="8.5" cy="7" r="4" />
              <line x1="20" y1="8" x2="20" y2="14" />
              <line x1="23" y1="11" x2="17" y2="11" />
            </svg>
            Registrati
          </a>
        <?php endif; ?>

      </div>
    </div>

  </div>
</div>