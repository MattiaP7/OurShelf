<?php
defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../models/LoginModels.php';

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
	private $model;

	/**
	 * Inizializza il controller e il suo modello di riferimento.
	 *
	 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
	 * @date 31/03/2026
	 */
	public function __construct()
	{
		$this->model = new LoginModels();
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
		$view = __DIR__ . '/../views/login/login.php';
		include __DIR__ . '/../views/layout.php';
	}

	/**
	 * Esegue il controllo delle credenziali fornite dall'utente.
	 * Avvia la sessione se l'autenticazione ha successo.
	 *
	 * @return void
	 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
	 * @date 31/03/2026
	 */
	public function check(): void
	{
		// recupero email e password dal POST e rimuovo spazi bianchi
		$email    = trim($_POST['email'] ?? '');
		$password = trim($_POST['password'] ?? '');

		// cerco l'utente nel db 
		$user = $this->model->authUser($email);

		if (!$user) {
			$_SESSION['errors'][] = "Email non trovata";
			header("Location: index.php?page=login");
			exit;
		}
		if (!password_verify($password, $user['password'])) {
			$_SESSION['errors'][] = "Password errata";
			header("Location: index.php?page=login");
			exit;
		}

		// se l'utente è stato trovato aggiungo alla sessione i relativi valori.
		$_SESSION['id_studente'] = $user['id_studente'];
		$_SESSION['nome']        = $user['nome'];
		$_SESSION['email']       = $user['email'];
		$_SESSION['id_classe']   = $user['id_classe'];

		$_SESSION['success'] = "Bentornato, {$user['nome']}!";
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
		// recupero la lista di classi : indirizzi e relativo valore id
		// utile per la registrazione di un utente
		$classi = $this->model->getClassi();
		$view   = __DIR__ . '/../views/login/register.php';
		include __DIR__ . '/../views/layout.php';
	}

	/**
	 *  Gestisce la logica di creazione di un nuovo studente.
	 *
	 * @return void
	 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
	 * @date 31/03/2026
	 */
	public function store(): void
	{
		$_SESSION['errors']  = [];
		$_SESSION['success'] = '';

		// recupero dal POST i valori inviati dal form
		$nome             = trim($_POST['nome'] ?? '');
		$cognome          = trim($_POST['cognome'] ?? '');
		$data_nascita     = trim($_POST['data_nascita'] ?? '');
		$sesso            = trim($_POST['sesso'] ?? '');
		$email            = trim($_POST['email'] ?? '');
		$password         = trim($_POST['password'] ?? '');
		$confirm_password = trim($_POST['password_confirm'] ?? '');
		$id_classe        = (int)($_POST['id_classe'] ?? 0);

		// controllo errori vari per i campi


		if (empty($password) || empty($confirm_password)) {
			$_SESSION['errors'][] = "Password obbligatoria";
		}
		if ($password !== $confirm_password) {
			$_SESSION['errors'][] = "Le password non coincidono";
		}
		if (strlen($password) < 8) {
			$_SESSION['errors'][] = "La password deve essere almeno 8 caratteri";
		}

		if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['errors'][] = "Email non valida";
		}
		if (in_array($email, $this->model->emailList())) {
			$_SESSION['errors'][] = "Email già registrata";
		}

		if (empty($nome) || empty($cognome)) {
			$_SESSION['errors'][] = "Nome e cognome obbligatori";
		}
		if ($id_classe === 0) {
			$_SESSION['errors'][] = "Seleziona una classe";
		}

		if (!empty($_SESSION['errors'])) {
			header("Location: index.php?page=login&action=register");
			exit;
		}

		// hash della password, MAI salvare in chiaro
		$hash   = password_hash($password, PASSWORD_DEFAULT);
		$params = [$nome, $cognome, $data_nascita, $sesso, $email, $hash, $id_classe];

		if ($this->model->insertUser($params)) {
			$_SESSION['success'] = "Registrazione completata!";
			header("Location: index.php?page=login");
		} else {
			$_SESSION['errors'][] = "Errore durante il salvataggio";
			header("Location: index.php?page=login&action=register");
		}
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
		// recupero i valori dal POST inviati dal form
		$email          = trim($_POST['email'] ?? '');
		$oldPassword    = trim($_POST['oldPassword'] ?? '');
		$newPassword    = trim($_POST['newPassword'] ?? '');
		$reNewPassword  = trim($_POST['reNewPassword'] ?? '');

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
	 * Esegue il logout distruggendo la sessione e reindirizzando al login.
	 *
	 * @return void
	 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
	 * @date 31/03/2026
	 */
	public function logout(): void
	{
		$_SESSION = [];
		session_destroy();
		header("Location: index.php?page=login");
		exit;
	}
}
