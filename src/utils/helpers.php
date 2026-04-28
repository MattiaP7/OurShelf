<?php

/**
 * Converte caratteri speciali in entità HTML per una stampa sicura nelle View.
 * 
 * Esempio: trasformando `"<a>"` in `"&lt;a&gt;"`, il browser visualizzerà 
 * il testo letterale invece di interpretarlo come un link o script.
 *
 * @param string $value La stringa grezza da convertire.
 * @return string La stringa convertita in entità HTML sicure.
 * 
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 20/03/2026
 */
function safe_string(string $value): string
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Funzione che stampa tutti gli errori
 *
 * @return void
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 26/04/2026
 */
function flash_error()
{
  if (!empty($_SESSION['errors'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong class="me-2">Attenzione!</strong>
                </div>
                <ul class="mb-0 mt-2">';
    foreach ($_SESSION['errors'] as $error) {
      echo "<li>" . safe_string($error) . "</li>";
    }
    echo '  </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    unset($_SESSION['errors']);
  }
}

/**
 * Funzione che stampa il messaggio di successo
 *
 * @return void
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 26/04/2026
 */
function flash_success()
{
  if (!empty($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
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
    // explode divide la stringa dato il separatore e fa un array 
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
 * Ritorna l'array delle condizioni dei libri
 *
 * @return array
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 26/04/2026
 */
function get_condizioni(): array
{
  return ['Ottime condizioni', 'Buone condizioni', 'Condizioni accettabili', 'Danneggiato'];
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
