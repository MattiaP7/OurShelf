<?php
defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../models/UsersModels.php';

/**
 * Classe UsersController
 * Gestisce la visualizzazione e l'aggiornamento del profilo utente.
 * Il form è unico: aggiorna dati personali e, opzionalmente, la password.
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 21/04/2026
 */
class UsersController
{
  /** @var UsersModels Istanza del modello utente */
  private $model;

  /**
   * Inizializza il controller e il suo modello.
   *
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function __construct()
  {
    $this->model = new UsersModels();
  }

  /**
   * Mostra la pagina profilo con i dati attuali dell'utente e il select classi.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function index(): void
  {
    $this->requireLogin();

    $user   = $this->model->getUser((int) $_SESSION['id_studente']);
    $classi = $this->model->getClassi();

    $view = __DIR__ . '/../views/users/index.php';
    include __DIR__ . '/../views/layout.php';
  }

  /**
   * Processa il form di aggiornamento profilo.
   * Aggiorna sempre i dati personali; aggiorna la password solo se
   * l'utente ha compilato i campi appositi.
   *
   * Validazioni eseguite:
   * - Campi obbligatori non vuoti
   * - Formato email valido
   * - Email non già usata da un altro account
   * - Se cambio password: verifica password attuale, lunghezza minima 8 caratteri,
   *   corrispondenza nuova password / conferma
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function update(): void
  {
    $this->requireLogin();

    $_SESSION['errors']  = [];
    $_SESSION['success'] = '';

    $userId      = (int) $_SESSION['id_studente'];
    $nome        = trim($_POST['nome']        ?? '');
    $cognome     = trim($_POST['cognome']      ?? '');
    $dataNascita = trim($_POST['data_nascita'] ?? '');
    $sesso       = trim($_POST['sesso']        ?? '');
    $email       = trim($_POST['email']        ?? '');
    $idClasse    = (int)  $_SESSION['id_classe'];

    // campi password (opzionali — solo se l'utente vuole cambiarla)
    $oldPassword     = trim($_POST['oldPassword']    ?? '');
    $newPassword     = trim($_POST['newPassword']    ?? '');
    $confNewPassword = trim($_POST['confNewPassword'] ?? '');

    // --- Validazione dati personali ---
    if (empty($nome) || empty($cognome)) {
      $_SESSION['errors'][] = "Nome e cognome sono obbligatori";
    }
    if (empty($dataNascita)) {
      $_SESSION['errors'][] = "La data di nascita è obbligatoria";
    }
    if (!in_array($sesso, ['m', 'f'], true)) {
      $_SESSION['errors'][] = "Seleziona un sesso valido";
    }
    if (empty($email) || !isEmailDomainValid($email)) {
      $_SESSION['errors'][] = "Inserisci un'email valida";
    } elseif ($this->model->emailEsiste($email, $userId)) {
      $_SESSION['errors'][] = "L'email è già utilizzata da un altro account";
    }
    if ($idClasse === 0) {
      $_SESSION['errors'][] = "Seleziona una classe";
    }

    // --- Validazione password (solo se almeno un campo è compilato) ---
    $cambiaPassword = ($oldPassword !== '' || $newPassword !== '' || $confNewPassword !== '');
    $hashNuovaPassword = '';

    if ($cambiaPassword) {
      $userDB = $this->model->getUser($userId);

      if (!password_verify($oldPassword, $userDB['password'])) {
        $_SESSION['errors'][] = "La password attuale non è corretta";
      }
      if (strlen($newPassword) < 8) {
        $_SESSION['errors'][] = "La nuova password deve essere di almeno 8 caratteri";
      }
      if ($newPassword !== $confNewPassword) {
        $_SESSION['errors'][] = "La nuova password e la conferma non coincidono";
      }

      // genera l'hash solo se non ci sono errori sulla password
      if (empty($_SESSION['errors'])) {
        $hashNuovaPassword = password_hash($newPassword, PASSWORD_DEFAULT);
      }
    }

    if (!empty($_SESSION['errors'])) {
      header("Location: index.php?page=users&action=index");
      exit;
    }

    // --- Aggiornamento DB ---
    $ok = $this->model->updateUser(
      $userId,
      $nome,
      $cognome,
      $dataNascita,
      $sesso,
      $email,
      $idClasse,
      $hashNuovaPassword
    );

    if ($ok) {
      // aggiorna i dati in sessione per riflettere i cambiamenti nella navbar
      $_SESSION['nome']      = $nome;
      $_SESSION['cognome']   = $cognome;
      $_SESSION['email']     = $email;
      $_SESSION['id_classe'] = $idClasse;

      $_SESSION['success'] = "Profilo aggiornato con successo!";
    } else {
      $_SESSION['errors'][] = "Nessuna modifica rilevata o errore durante il salvataggio";
    }

    header("Location: index.php?page=users&action=index");
    exit;
  }

  /**
   * Verifica che l'utente sia autenticato.
   * In caso contrario reindirizza al login e interrompe l'esecuzione.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  private function requireLogin(): void
  {
    if (empty($_SESSION['id_studente'])) {
      header("Location: index.php?page=login");
      exit;
    }
  }
}
