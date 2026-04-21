<?php
defined("APP") or die("Accesso negato");

require_once 'config/dbconnect.php';

/**
 * Classe AnnunciModels
 * Gestisce tutte le operazioni CRUD relative alla tabella Annunci,
 * incluse la pubblicazione, la ricerca, l'acquisto e la conclusione delle vendite.
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 21/04/2026
 */
class AnnunciModels
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
   * Recupera tutti gli annunci disponibili con i dati del libro e del venditore.
   * Supporta il filtraggio opzionale per materia, condizione, fascia di prezzo,
   * ISBN, titolo ed editore.
   *
   * @param string $materia    Filtra per materia esatta del libro.
   * @param string $condizione Filtra per condizione del libro.
   * @param float  $prezzoMin  Prezzo minimo annuncio.
   * @param float  $prezzoMax  Prezzo massimo annuncio.
   * @param string $isbn       Filtra per ISBN esatto.
   * @param string $titolo     Filtra per titolo (LIKE).
   * @param string $editore    Filtra per editore (LIKE).
   * @return array Array associativo degli annunci trovati con alias per i prezzi.
   */
  public function getAnnunci(
    string $materia    = '',
    string $condizione = '',
    float  $prezzoMin  = 0,
    float  $prezzoMax  = 0,
    string $isbn       = '',
    string $titolo     = '',
    string $editore    = ''
  ): array {
    $sql = "
    SELECT
      a.id_annuncio,
      a.prezzo AS prezzo_vendita,  -- Alias per distinguere il prezzo usato
      a.id_venditore,
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
      l.prezzo AS prezzo_listino, -- Alias per il prezzo originale
      ls.nome AS luogo_scambio,
      CONCAT(s.nome, ' ', s.cognome) AS venditore
    FROM Annunci a
    JOIN Libri         l  ON a.id_libro    = l.id_libro
    JOIN Luoghi_Scambi ls ON a.id_luogo    = ls.id_luogo
    JOIN Studenti      s  ON a.id_venditore = s.id_studente
    WHERE a.stato = 'disponibile'
  ";

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
    if (!empty($isbn)) {
      $sql     .= " AND l.isbn = ?";
      $params[] = $isbn;
    }
    if (!empty($titolo)) {
      $sql     .= " AND l.titolo LIKE ?";
      $params[] = '%' . $titolo . '%';
    }
    if (!empty($editore)) {
      $sql     .= " AND l.editore LIKE ?";
      $params[] = '%' . $editore . '%';
    }

    // GROUP BY aggiornato con gli alias corretti
    $sql .= "
    GROUP BY
      a.id_annuncio,
      prezzo_vendita,
      a.id_venditore,
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
      prezzo_listino,
      ls.nome,
      venditore
    ORDER BY a.data_pubblicazione DESC
  ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera i dettagli di un singolo annuncio tramite il suo ID.
   * Include prezzo del libro per mostrare il risparmio nella view dettaglio.
   *
   * @param int $id L'identificativo univoco dell'annuncio.
   * @return array|false Array associativo con i dati dell'annuncio, false se non trovato.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function getAnnuncioById(int $id): array|false
  {
    $sql = "
      SELECT
        a.id_annuncio,
        a.prezzo AS prezzo_vendita,  -- Alias per il prezzo dell'annuncio
        a.id_venditore,
        a.data_pubblicazione,
        a.data_acquisto,
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
        l.anno_scolastico,
        l.prezzo AS prezzo_listino, -- Alias per il prezzo originale del libro
        ls.nome AS luogo_scambio,
        CONCAT(sv.nome, ' ', sv.cognome) AS venditore,
        sv.email AS email_venditore
      FROM Annunci a
      JOIN Libri         l  ON a.id_libro    = l.id_libro
      JOIN Luoghi_Scambi ls ON a.id_luogo    = ls.id_luogo
      JOIN Studenti      sv ON a.id_venditore = sv.id_studente
      WHERE a.id_annuncio = ?
      GROUP BY
        a.id_annuncio,
        prezzo_vendita,
        a.id_venditore,
        a.data_pubblicazione,
        a.data_acquisto,
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
        l.anno_scolastico,
        prezzo_listino,
        ls.nome,
        venditore,
        sv.email
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
   * @date 21/04/2026
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
   *
   * @param int $idAnnuncio   L'ID dell'annuncio da aggiornare.
   * @param int $idCompratore L'ID dello studente acquirente.
   * @return bool True se l'aggiornamento è avvenuto con successo, false altrimenti.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function concludiAcquisto(int $idAnnuncio, int $idCompratore): bool
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
    $stmt->execute([$idCompratore, $idAnnuncio]);
    return $stmt->rowCount() > 0;
  }

  /**
   * Elimina un annuncio. Operazione consentita solo al venditore proprietario.
   *
   * @param int $idAnnuncio  L'ID dell'annuncio da eliminare.
   * @param int $idVenditore L'ID del venditore, usato come guardia di sicurezza.
   * @return bool True se la cancellazione è avvenuta con successo, false altrimenti.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function deleteAnnuncio(int $idAnnuncio, int $idVenditore): bool
  {
    $sql  = "DELETE FROM Annunci WHERE id_annuncio = ? AND id_venditore = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$idAnnuncio, $idVenditore]);
    return $stmt->rowCount() > 0;
  }

  /**
   * Recupera tutti gli annunci pubblicati da un determinato studente.
   * GROUP BY su a.id_annuncio per evitare duplicati.
   *
   * @param int $idStudente L'ID dello studente venditore.
   * @return array Array degli annunci del venditore.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function getAnnunciByVenditore(int $idStudente): array
  {
    $sql = "
        SELECT 
            a.id_annuncio,
            a.prezzo AS prezzo_vendita,
            a.data_pubblicazione,
            a.stato,
            a.condizione,
            l.titolo,
            l.autore,
            l.isbn,
            l.prezzo AS prezzo_listino
        FROM Annunci a
        JOIN Libri l ON a.id_libro = l.id_libro
        WHERE a.id_venditore = ?
        ORDER BY a.data_pubblicazione DESC
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$idStudente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera tutti i libri acquistati da un determinato studente.
   * GROUP BY su a.id_annuncio per evitare duplicati.
   *
   * @param int $idStudente L'ID dello studente acquirente.
   * @return array Array degli annunci conclusi in cui lo studente è l'acquirente.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function getLibriAcquistati(int $idStudente): array
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
				l.prezzo,
				CONCAT(s.nome, ' ', s.cognome) AS venditore
			FROM Annunci a
			JOIN Libri    l ON a.id_libro    = l.id_libro
			JOIN Studenti s ON a.id_venditore = s.id_studente
			WHERE a.id_compratore = ?
			  AND a.stato         = 'venduto'
			GROUP BY
				a.id_annuncio,
				a.prezzo,
				a.data_acquisto,
				a.condizione,
				l.titolo,
				l.autore,
				l.isbn,
				l.prezzo,
				venditore
			ORDER BY a.data_acquisto DESC
		";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$idStudente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera tutti i luoghi disponibili per lo scambio.
   *
   * @return array Array dei luoghi con id e nome.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function getLuoghi(): array
  {
    $sql  = "SELECT id_luogo, nome FROM Luoghi_Scambi ORDER BY nome";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
