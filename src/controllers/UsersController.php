<?php
defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../models/UsersModels.php';
require_once __DIR__ . '/../utils/secureUploader.php';
require_once 'config/dbconnect.php';

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

  /** @var secureUploader Istanza della classe secureUploader per il caricamento sicuro delle immagini */
  private secureUploader $uploader;

  /** @var PDO Istanza della connessione al database */
  private PDO $pdo;

  /**
   * Inizializza il controller e il suo modello.
   *
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function __construct()
  {
    $this->model    = new UsersModels();
    $this->uploader = new secureUploader();
    $this->pdo      = DB::connect();
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
    requireLogin();

    $user   = $this->model->getUser((int) $_SESSION['id_studente']);
    $classi = $this->model->getClassi();

    $title = "Area utente";
    $view = __DIR__ . '/../views/users/index.php';
    include __DIR__ . '/../views/layout.php';
  }

  /**
   * Processa il form di aggiornamento profilo.
   * Aggiorna sempre i dati personali anche avatar profilo
   * aggiorna la password solo se l'utente ha compilato i campi appositi.
   *
   * Validazioni eseguite:
   * - Campi obbligatori non vuoti
   * - Formato email valido
   * - Email non già usata da un altro account
   * - Se cambio password: verifica password attuale, lunghezza minima 8 caratteri,
   *   corrispondenza nuova password / conferma
   * 
   * Flusso foto:
   *  1. Legge il nome del vecchio file da Studenti.foto
   *  2. Chiama salvaAvatar() che:
   *     - valida il nuovo file
   *     - fa unlink() del vecchio
   *     - salva il nuovo fisicamente
   *     - aggiorna Studenti.foto nel DB
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function update(): void
  {
    requireLogin();

    // $_SESSION['errors']  = [];
    // $_SESSION['success'] = '';

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
    // con il terzo parametro controlliamo che il tipo che cerchiamo sia uguale al tipo degli elementi da dove cerchiamo
    if (!in_array($sesso, ['m', 'f'], true)) {
      $_SESSION['errors'][] = "Seleziona un sesso valido";
    }

    // validazione email, controlliamo se gia' usata da un utente che NON sia noi stessi
    if (empty($email) || !isEmailDomainValid($email)) {
      $_SESSION['errors'][] = "Inserisci un'email valida";
    } elseif (!empty($this->model->emailEsiste($email, $_SESSION['id_studente']))) {
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
      // aggiorno i valori nella session
      $_SESSION['nome_completo'] = $nome . ' ' . $cognome;
      $_SESSION['email']     = $email;
      $_SESSION['id_classe'] = $idClasse;

      $_SESSION['success'] = "Profilo aggiornato con successo!";
    } else {
      $_SESSION['errors'][] = "Nessuna modifica rilevata o errore durante il salvataggio";
    }

    // cambio la foto profilo (opzionale)
    if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
      $userDB = $this->model->getUser($userId);
      $vecchia_foto = $userDB['foto'] ?? '';

      // salva la nuova foto
      $this->uploader->salvaAvatar($userId, $_FILES['avatar'], $vecchia_foto);
      $user_aggiornato = $this->model->getUser($userId);
      $_SESSION['foto'] = $user_aggiornato['foto'] ?? '';
    }

    header("Location: index.php?page=users&action=index");
    exit;
  }
}
