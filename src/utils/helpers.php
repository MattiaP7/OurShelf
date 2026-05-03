<?php

/**
 * Converte caratteri speciali in entità HTML per una stampa sicura nelle View.
 * 
 * Protegge l'applicazione da attacchi XSS (Cross-Site Scripting). 
 * Esempio: trasformando `"<a>"` in `"&lt;a&gt;"`, il browser visualizzerà 
 * il testo letterale invece di interpretarlo come un link o script.
 *
 * @param string $value La stringa grezza da convertire.
 * @return string La stringa convertita in entità HTML sicure.
 * 
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 20/03/2026
 */
function safe_string(?string $value): string
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function flash_error()
{
  if (!empty($_SESSION['errors'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show small shadow-sm" role="alert">
            <ul class="mb-0 ps-3">';
    foreach ($_SESSION['errors'] as $error) {
      echo "<li>" . safe_string($error) . "</li>";
    }
    echo '  </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['errors']);
  }
}

function flash_success()
{
  if (!empty($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show small shadow-sm d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>' . safe_string($_SESSION['success']) . '</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['success']);
  }
}

/**
 * Estra dalla email il dominio e verifica che quello passato dall'utente sia isit100.fe.it (o in maiuscolo), si possono registrare/autenticare solo account con email scolastica
 *
 * @param string $email
 * @return boolean
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 18/04/2026
 */
function isEmailDomainValid(string $email): bool
{
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $domain = explode('@', $email)[1];
  }

  $domain = strtoupper($domain);
  if ($domain === 'ISIT100.FE.IT') {
    return true;
  } else {
    return false;
  }
}

/**
 * Verifica che l'utente sia autenticato.
 * In caso contrario reindirizza al login e interrompe l'esecuzione.
 *
 * @return void
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 18/04/2026
 */
function requireLogin(): void
{
  if (empty($_SESSION['id_studente'])) {
    $_SESSION['errors'][] = "Devi essere registrato per accedere a questa pagina.";
    header("Location: index.php?page=login");
    exit;
  }
}


/**
 * il codice di sotto ci permette di creare una costante chiamata BASE_URL che contiene l'url del nostro
 * sito in modo tale che quando dobbiamo andare a collegare un link esterno o delle pagine tra di loro
 * andiamo ad aprire l'ambiente php nel link per potere richiamare questa costante e aggiungere solamente
 * la parte di percorso mancante... questo risolve tutti i problemi legati ai percorsi per i collegamenti
 * dei file tra di loro e per link esterni, così che anche se dovesse cambiare qualcosa all'interno delle cartelle
 * quindi magari si spostano dei file, la base del percorso rimane sempre quella 
 *
 * @author Matteo Portacci <portacci.7780@isit100.fe.it>
 * @param mixed BASE_URL
 * @return void
 */
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$project_root = str_replace('/src', '', $script_dir);
define('BASE_URL', rtrim($protocol . $host . $project_root, '/'));