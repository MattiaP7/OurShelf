<?php
defined("APP") or die("Accesso negato");

require_once 'config/dbconnect.php';

/**
 * Classe LoginModels
 * Gestisce tutte le operazioni CRUD relative alla tabella Studenti,
 * incluse l'autenticazione, la registrazione e il recupero dati
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 30/03/2026
 */
class LoginModels
{
  /** @var PDO Istanza della connessione al database */
  private $pdo;

  /**
   * Inizializza la connessione al database.
   */
  public function __construct()
  {
    $this->pdo = DB::connect();
  }

  /**
   * Recupera la lista di tutti gli indirizzi email registrati.
   * @param array $params Parametri opzionali per la query (default vuoto).
   * @return array Array contenente le email.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 30/03/2026
   */
  public function emailList(array $params = []): array
  {
    $dql = "SELECT email FROM Studenti";
    $stm = $this->pdo->prepare($dql);
    $stm->execute($params);
    return array_column($stm->fetchAll(PDO::FETCH_ASSOC), 'email');
  }

  /**
   * Recupera tutte le classi
   *
   * @param array $params
   * @return array
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 31/03/2026
   */
  public function getClassi(array $params = []): array
  {
    $dql  = "SELECT id_classe, anno, sezione, indirizzo FROM Classi ORDER BY anno, sezione";
    $stmt = $this->pdo->prepare($dql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Cerca uno studente nel database tramite il suo indirizzo email.
   * @param string $email L'email dello studente da cercare.
   * @return array Restituisce l'array dei dati utente, se vuoto nessuno e' stato trovato.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 30/03/2026
   */
  public function authUser(string $email): array
  {
    $dql = "SELECT * FROM Studenti WHERE email = ? LIMIT 1";
    $stmt = $this->pdo->prepare($dql);
    $stmt->execute([$email]);
    $result =  $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result === false)
      return [];
    return $result;
  }

  /**
   * Inserisce un nuovo record studente nella tabella e restituisce l'ID generato.
   *
   * @param array $params [nome, cognome, data_nascita, sesso, email, password, id_classe]
   * @return int L'ID dello studente appena creato, oppure 0 in caso di fallimento.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 30/03/2026
   */
  public function insertUser(array $params): int
  {
    $dql = "
            INSERT INTO Studenti (nome, cognome, data_nascita, sesso, email, password, id_classe)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
    $stmt = $this->pdo->prepare($dql);
    $stmt->execute($params);

    // Verifichiamo se l'inserimento ha effettivamente prodotto una riga.
    if ($stmt->rowCount() > 0) {
      // Recupera l'ultimo ID autoincrementale generato dalla connessione.
      return (int) $this->pdo->lastInsertId();
    }

    return 0;
  }

  /**
   * Aggiorna la password hashata di uno studente specifico.
   * @param string $hash Il nuovo hash della password.
   * @param int $id L'identificativo univoco dello studente.
   * @return bool Esito dell'operazione di update.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 30/03/2026
   */
  public function updatePassword(string $hash, int $id): bool
  {
    $dql = "UPDATE Studenti SET password = ? WHERE id_studente = ?";
    $stmt = $this->pdo->prepare($dql);
    return $stmt->execute([$hash, $id]);
  }
}
