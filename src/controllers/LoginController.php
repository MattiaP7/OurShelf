<?php
defined("APP") or die("Accesso negato");

require_once 'config/dbconnect.php';
require_once __DIR__ . '/../models/LoginModels.php';
require_once __DIR__ . '/../models/UsersModels.php';
require_once __DIR__ . '/../utils/helpers.php';
require_once __DIR__ . '/../utils/secureUploader.php';

/**
 * Classe LoginController
 * Gestisce il flusso di autenticazione, la registrazione dei nuovi utenti
 * e la gestione delle sessioni attive.
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 30/03/2026
 */
class LoginController
{
  /** @var LoginModels Istanza del modello per le query */
  private LoginModels $model;

  /** @var secureUploader Istanza della classe secureUploader per il caricamento sicuro delle immagini */
  private secureUploader $uploader;

  /** @var PDO Istanza della connessione al database */
  private PDO $pdo;

  /**
   * Inizializza il controller e il suo modello di riferimento.
   *
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 31/03/2026
   */
  public function __construct()
  {
    $this->model    = new LoginModels();
    $this->uploader = new secureUploader();
    $this->pdo      = DB::connect();
  }

  /**
   * Carica e mostra il form di login.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 31/03/2026
   */
  public function index(): void
  {
    $title = "Login Page";
    $view = __DIR__ . '/../views/login/login.php';
    include __DIR__ . '/../views/layout.php';
  }

  /**
   * Esegue il controllo delle credenziali fornite dall'utente.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 31/03/2026
   */
  public function check(): void
  {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!isEmailDomainValid($email)) {
      $_SESSION['errors'][] = "Devi autenticarti usando la email istituzionale";
      header("Location: index.php?page=login");
      exit;
    }

    $user = $this->model->authUser($email);

    if (empty($user) || !password_verify($password, $user['password'])) {
      $_SESSION['errors'][] = "Credenziali non valide";
      header("Location: index.php?page=login");
      exit;
    }

    $_SESSION['id_studente']   = $user['id_studente'];
    $_SESSION['nome_completo'] = $user['nome'] . ' ' . $user['cognome'];
    $_SESSION['nome']          = $user['nome'];
    $_SESSION['cognome']       = $user['cognome'];
    $_SESSION['email']         = $user['email'];
    $_SESSION['id_classe']     = $user['id_classe'];
    $_SESSION['foto']          = $user['foto'];

    $_SESSION['success'] = "Bentornato, {$_SESSION['nome_completo']}!";
    header("Location: index.php");
    exit;
  }

  /**
   * Carica e mostra il form di registrazione per nuovi studenti.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 31/03/2026
   */
  public function register(): void
  {
    $classi = $this->model->getClassi();
    $title  = "Register Page";
    $view   = __DIR__ . '/../views/login/register.php';
    include __DIR__ . '/../views/layout.php';
  }

  /**
   * Gestisce la logica di creazione di un nuovo studente.
   * Dopo l'INSERT, se l'utente ha allegato un avatar lo carica subito.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 31/03/2026
   */
  public function store(): void
  {
    $nome             = trim($_POST['nome']        ?? '');
    $cognome          = trim($_POST['cognome']      ?? '');
    $data_nascita     = trim($_POST['data_nascita'] ?? '');
    $sesso            = trim($_POST['sesso']        ?? '');
    $email            = trim($_POST['email']        ?? '');
    $password         = trim($_POST['password']     ?? '');
    $confirm_password = trim($_POST['confPassword'] ?? '');
    $id_classe        = (int) ($_POST['id_classe']  ?? 0);
    $_SESSION['foto'] = '';

    if (empty($password) || empty($confirm_password)) {
      $_SESSION['errors'][] = "Password obbligatoria";
      header("Location: index.php?page=login");
      exit;
    }
    if ($password !== $confirm_password) {
      $_SESSION['errors'][] = "Le password non coincidono";
      header("Location: index.php?page=login");
      exit;
    }
    if (strlen($password) < 8) {
      $_SESSION['errors'][] = "La password deve essere almeno 8 caratteri";
      header("Location: index.php?page=login");
      exit;
    }
    if (!isEmailDomainValid($email)) {
      $_SESSION['errors'][] = "Devi registrarti usando la email istituzionale";
      header("Location: index.php?page=login");
      exit;
    }
    if (empty($email)) {
      $_SESSION['errors'][] = "La email non puo' essere vuota";
      header("Location: index.php?page=login");
      exit;
    }
    if (in_array($email, $this->model->emailList())) {
      $_SESSION['errors'][] = "Email già registrata";
      header("Location: index.php?page=login");
      exit;
    }
    if (empty($nome) || empty($cognome)) {
      $_SESSION['errors'][] = "Nome e cognome obbligatori";
      header("Location: index.php?page=login");
      exit;
    }
    if ($id_classe === 0) {
      $_SESSION['errors'][] = "Seleziona una classe";
      header("Location: index.php?page=login");
      exit;
    }
    if (!empty($_SESSION['errors'])) {
      header("Location: index.php?page=login&action=register");
      exit;
    }

    $hash   = password_hash($password, PASSWORD_DEFAULT);
    $params = [$nome, $cognome, $data_nascita, $sesso, $email, $hash, $id_classe];

    $user_id = $this->model->insertUser($params);
    if ($user_id === 0) {
      $_SESSION['errors'][] = 'Errore durante la creazione dell\'utente';
      header("Location: index.php?page=login&action=register");
      exit;
    }

    // Avatar profilo, opzionale ma se presente lo salviamo
    // vecchia_foto = '' perche' l'utente appena creato non aveva ancora una foto

    if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
      $this->uploader->salvaAvatar($this->pdo, (int) $user_id, $_FILES['avatar'], '');
    }

    $_SESSION['success'] = "Registrazione completata! Accedi ora";
    header("Location: index.php?page=login");
    exit;
  }

  /**
   * Carica e mostra il form per il cambio password.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 31/03/2026
   */
  public function changePassword(): void
  {
    $title = 'Cambia Password';
    $view = __DIR__ . '/../views/login/change_password.php';
    include __DIR__ . '/../views/layout.php';
  }

  /**
   * Valida la vecchia password e aggiorna con la nuova nel database.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 31/03/2026
   */
  public function updatePassword(): void
  {
    $email         = trim($_POST['email']         ?? '');
    $oldPassword   = trim($_POST['oldPassword']   ?? '');
    $newPassword   = trim($_POST['newPassword']   ?? '');
    $reNewPassword = trim($_POST['reNewPassword'] ?? '');

    if (empty($email) || !isEmailDomainValid($email)) {
      $_SESSION['errors'][] = "Devi usare un'email istituzionale";
      header("Location: index.php?page=login");
      exit;
    }

    $user = $this->model->authUser($email);

    if (!$user) {
      header("Location: index.php?page=login&action=changePassword");
      exit;
    }
    if (!password_verify($oldPassword, $user['password'])) {
      header("Location: index.php?page=login&action=changePassword");
      exit;
    }
    if ($newPassword !== $reNewPassword || strlen($newPassword) < 8) {
      header("Location: index.php?page=login&action=changePassword");
      exit;
    }

    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    if ($this->model->updatePassword($hash, $user['id_studente'])) {
      $_SESSION['success'] = "Password aggiornata con successo!";
      header("Location: index.php?page=login");
    } else {
      header("Location: index.php?page=login&action=changePassword");
    }
    exit;
  }

  /**
   * Esegue il logout distruggendo la sessione.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 31/03/2026
   */
  public function logout(): void
  {
    requireLogin();
    $_SESSION = [];
    session_destroy();
    header("Location: index.php?page=login");
    exit;
  }
}
