<?php

defined("APP") or die("Accesso negato");

require_once 'config/dbconnect.php';

/**
 * Classe AnnunciModels
 * Gestisce le operazioni CRUD relative alla tabella `Annunci`,
 * incluso pubblicazione, ricerca, l'acquisto e la conclusione alle vendite
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 17/04/2026
 */
class AnnunciModels
{
  /** @var PDO Istanza della connessione al DB */
  private $pdo;

  /**
   * Inizializza la connessione al database.
   *
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function __construct()
  {
    $this->pdo = DB::connect();
  }

  /**
   * Recupera tutti gli annunci disponibili con i dati del libro e del venditore.
   * Supporta il filtraggio opzionale per materia, condizione e fascia di prezzo.
   *
   * @param string $materia   Filtra per materia del libro (default '').
   * @param string $condizione Filtra per condizione del libro (default '').
   * @param float  $prezzoMin  Prezzo minimo (default 0).
   * @param float  $prezzoMax  Prezzo massimo, 0 = nessun limite (default 0).
   * @return array Array associativo degli annunci trovati.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function getAnnunci(
    string $materia    = '',
    string $condizione = '',
    float  $prezzoMin  = 0,
    float  $prezzoMax  = 0
  ): array {
    $sql = "
			SELECT
				a.id_annuncio,
				a.prezzo,
				a.data_pubblicazione,
				a.descrizione,
				a.data_ora_scambio,
				a.stato,
				a.condizione,
				l.titolo,
				l.autore,
				l.isbn,
				l.materia,
				l.editore,
				l.volume,
				ls.nome AS luogo_scambio,
				CONCAT(s.nome, ' ', s.cognome) AS venditore
			FROM Annunci a
			JOIN Libri         l  USING(id_libro)
			JOIN Luoghi_Scambi ls USING(id_luogo)
			JOIN Studenti      s  ON a.id_venditore = s.id_studente
			WHERE a.stato = 'disponibile'
		";

    //NOTA: utilizziamo i parametri nella funzione al posto di un array perchè ci esce 
    // meglio la query, ci bastera' infatti aggiungere il pezzo al $sql e aggiungere il parametro nel $params

    //CONCAT unisce stringhe, c'e' l'ha fatta vedere dessolis

    $params = [];

    if (!empty($materia)) {
      $sql     .= " AND l.materia = ?";
      $params[] = $materia;
    }
    if (!empty($condizione)) {
      $sql     .= " AND a.condizione = ?";
      $params[] = $condizione;
    }
    if ($prezzoMin > 0) {
      $sql     .= " AND a.prezzo >= ?";
      $params[] = $prezzoMin;
    }
    if ($prezzoMax > 0) {
      $sql     .= " AND a.prezzo <= ?";
      $params[] = $prezzoMax;
    }

    $sql .= " ORDER BY a.data_pubblicazione DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera i dettagli di un singolo annuncio tramite il suo ID.
   *
   * @param int $id L'identificativo univoco dell'annuncio.
   * @return array|false Array associativo con i dati dell'annuncio, false se non trovato.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function getAnnuncioById(int $id): array|false
  {
    $sql = "
			SELECT
				a.*,
				l.titolo,
				l.autore,
				l.isbn,
				l.materia,
				l.editore,
				l.volume,
				l.anno_scolastico,
				ls.nome AS luogo_scambio,
				CONCAT(sv.nome, ' ', sv.cognome) AS venditore,
				sv.email AS email_venditore
			FROM Annunci a
			JOIN Libri         l  ON a.id_libro    = l.id_libro
			JOIN Luoghi_Scambi ls ON a.id_luogo     = ls.id_luogo
			JOIN Studenti      sv ON a.id_venditore = sv.id_studente
			WHERE a.id_annuncio = ?
			LIMIT 1
		";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Inserisce un nuovo annuncio nella bacheca.
   *
   * @param array $params [prezzo, descrizione, data_ora_scambio, id_venditore, id_luogo, id_libro, condizione]
   * @return int L'ID dell'annuncio appena inserito, 0 in caso di errore.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function insertAnnuncio(array $params): int
  {
    $sql = "
			INSERT INTO Annunci
				(prezzo, descrizione, data_ora_scambio, id_venditore, id_luogo, id_libro, condizione)
			VALUES (?, ?, ?, ?, ?, ?, ?)
		";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return (int) $this->pdo->lastInsertId();
  }

  /**
   * Registra l'acquisto di un libro: imposta compratore, data acquisto e stato venduto.
   * Usato per concludere una transazione tra venditore e acquirente.
   *
   * @param int $id_annuncio   L'ID dell'annuncio da aggiornare.
   * @param int $id_compratore L'ID dello studente acquirente.
   * @return bool True se l'aggiornamento è avvenuto con successo, false altrimenti.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function concludiAcquisto(int $id_compratore, int $id_annuncio): bool
  {
    $sql = "
			UPDATE Annunci
			SET
				id_compratore = ?,
				data_acquisto = NOW(),
				stato         = 'venduto'
			WHERE id_annuncio = ?
			  AND stato       = 'disponibile'
		";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id_compratore, $id_annuncio]);
    return $stmt->rowCount() > 0;
  }

  /**
   * Elimina un annuncio. Operazione consentita solo al venditore proprietario.
   *
   * @param int $id_annuncio   L'ID dell'annuncio da eliminare.
   * @param int $id_venditore  L'ID del venditore, usato come guardia di sicurezza.
   * @return bool True se la cancellazione è avvenuta con successo, false altrimenti.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function deleteAnnuncio(int $id_annuncio, int $id_venditore): bool
  {
    $sql  = "DELETE FROM Annunci WHERE id_annuncio = ? AND id_venditore = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id_annuncio, $id_venditore]);
    return $stmt->rowCount() > 0;
  }

  /**
   * Recupera tutti gli annunci pubblicati da un determinato studente (libri in vendita).
   *
   * @param int $id_studente L'ID dello studente venditore.
   * @return array Array degli annunci del venditore.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function getAnnunciByVenditore(int $id_studente): array
  {
    $sql = "
			SELECT
				a.id_annuncio as id_annuncio,
				a.prezzo as prezzo,
				a.data_pubblicazione as data_pubblicazione,
				a.stato as stato,
				a.condizione as condizione,
				l.titolo as titolo,
				l.autore as autore,
				l.isbn as isbn
			FROM Annunci a
			JOIN Libri l ON a.id_libro = l.id_libro
			WHERE a.id_venditore = ?
			ORDER BY a.data_pubblicazione DESC
		";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id_studente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera tutti i libri acquistati da un determinato studente (storico acquisti).
   *
   * @param int $id_studente L'ID dello studente acquirente.
   * @return array Array degli annunci conclusi in cui lo studente è l'acquirente.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function getLibriAcquistati(int $id_studente): array
  {
    $sql = "
			SELECT
				a.id_annuncio,
				a.prezzo,
				a.data_acquisto,
				a.condizione,
				l.titolo,
				l.autore,
				l.isbn,
				CONCAT(s.nome, ' ', s.cognome) AS venditore
			FROM Annunci a
			JOIN Libri    l ON a.id_libro    = l.id_libro
			JOIN Studenti s ON a.id_venditore = s.id_studente
			WHERE a.id_compratore = ?
			  AND a.stato         = 'venduto'
			ORDER BY a.data_acquisto DESC
		";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id_studente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera tutti i luoghi disponibili per lo scambio.
   * Usato per popolare il select nel form di pubblicazione annuncio.
   *
   * @return array Array dei luoghi con id e nome.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function getLuoghi(): array
  {
    $sql  = "SELECT id_luogo, nome FROM Luoghi_Scambi ORDER BY nome";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
};
