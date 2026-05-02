<?php
defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../config/dbconnect.php';

/**
 * Classe UsersModels
 * Gestisce le operazioni CRUD relative al profilo dello studente:
 * lettura dei dati personali e aggiornamento (dati + password opzionale).
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 21/04/2026
 */
class UsersModels
{
  /** @var PDO Istanza della connessione al database */
  private $pdo;

  /**
   * Inizializza la connessione al database.
   *
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function __construct()
  {
    $this->pdo = DB::connect();
  }

  /**
   * Recupera tutti i dati di uno studente tramite il suo ID.
   *
   * @param int $userId L'ID dello studente.
   * @return array|false Array associativo con i dati, false se non trovato.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function getUser(int $userId): array|false
  {
    $sql  = "SELECT id_studente, nome, cognome, data_nascita, sesso, email, id_classe, foto
             FROM Studenti 
             WHERE id_studente = ? 
             LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera tutte le classi disponibili per il select nel form profilo.
   *
   * @return array Array delle classi con id, anno, sezione, indirizzo.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function getClassi(): array
  {
    $sql  = "SELECT id_classe, anno, sezione, indirizzo FROM Classi ORDER BY anno, sezione";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Verifica se un'email è già usata da un altro studente.
   * Usato per evitare duplicati quando l'utente cambia la propria email.
   *
   * @param string $email     L'email da verificare.
   * @param int $id_studente  Utente da escludere
   * @return bool True se l'email è già occupata da qualcun altro.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function emailEsiste(string $email, int $id_studente): bool
  {
    $sql  = "SELECT id_studente FROM Studenti WHERE email = ? and id_studente != ? LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$email, $id_studente]);
    return (bool) $stmt->fetch();
  }

  /**
   * Aggiorna i dati del profilo di uno studente.
   * Se $nuovaPassword non è vuota, aggiorna anche la password.
   *
   * @param int    $userId        L'ID dello studente da aggiornare.
   * @param string $nome          Nuovo nome.
   * @param string $cognome       Nuovo cognome.
   * @param string $dataNascita   Nuova data di nascita (formato Y-m-d).
   * @param string $sesso         Sesso ('m' o 'f').
   * @param string $email         Nuova email.
   * @param int    $idClasse      ID della nuova classe.
   * @param string $nuovaPassword Hash della nuova password, stringa vuota se non cambia.
   * @return bool True se l'aggiornamento è avvenuto con successo.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function updateUser(
    int    $userId,
    string $nome,
    string $cognome,
    string $dataNascita,
    string $sesso,
    string $email,
    int    $idClasse,
    string $nuovaPassword = ''
  ): bool {
    if ($nuovaPassword !== '') {
      // aggiorna anche la password
      $sql = "
				UPDATE Studenti
				SET nome         = ?,
				    cognome      = ?,
				    data_nascita = ?,
				    sesso        = ?,
				    email        = ?,
				    id_classe    = ?,
				    password     = ?
				WHERE id_studente = ?
			";
      $params = [$nome, $cognome, $dataNascita, $sesso, $email, $idClasse, $nuovaPassword, $userId];
    } else {
      // aggiorna solo i dati personali
      $sql = "
				UPDATE Studenti
				SET nome         = ?,
				    cognome      = ?,
				    data_nascita = ?,
				    sesso        = ?,
				    email        = ?,
				    id_classe    = ?
				WHERE id_studente = ?
			";
      $params = [$nome, $cognome, $dataNascita, $sesso, $email, $idClasse, $userId];
    }

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($params);
    //return $stmt->rowCount() > 0;
  }

  /**
   * Aggiorna la foto profilo di uno studente
   *
   * @param string $nome_file
   * @param integer $id_studente
   * @return boolean
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 02/05/2026
   */
  public function updateAvatar(string $nome_file, int $id_studente): bool
  {
    $sql = "UPDATE Studenti SET foto = ? WHERE id_studente = ?";
    $stm = $this->pdo->prepare($sql);
    return $stm->execute([$nome_file, $id_studente]);
  }
}
