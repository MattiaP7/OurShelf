<?php
defined("APP") or die("Accesso negato");

require_once 'config/dbconnect.php';

/**
 * Classe LibriModels
 * Gestisce le operazioni di ricerca e recupero dati relativi alla tabella Libri
 * e alla tabella di associazione Classi_Libri.
 * Usata principalmente per verificare che un libro sia adottato dalla scuola
 * prima di permetterne la pubblicazione come annuncio.
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 21/04/2026
 */
class LibriModels
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
   * Cerca un libro tramite il suo codice ISBN.
   * Utilizzato per verificare che il libro sia nel catalogo scolastico
   * prima di permettere la pubblicazione di un annuncio.
   *
   * @param string $isbn Il codice ISBN del libro da cercare.
   * @return array|false Array con i dati del libro, false se non trovato.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function getLibroByIsbn(string $isbn): array
  {
    $sql  = "SELECT * FROM Libri WHERE isbn = ? LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$isbn]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera un libro tramite il suo ID.
   *
   * @param int $id L'identificativo univoco del libro.
   * @return array|false Array con i dati del libro, false se non trovato.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function getLibroById(int $id): array|false
  {
    $sql  = "SELECT * FROM Libri WHERE id_libro = ? LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera tutti i libri adottati in tutta la scuola
   *
   * @return array
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 18/04/2026
   */
  public function getAllLibri(): array
  {
    $sql = "
      SELECT DISTINCT
        isbn, 
        titolo, 
        autore, 
        materia, 
        editore, 
        volume, 
        anno_scolastico,
        prezzo
      FROM Libri 
      ORDER BY materia, titolo
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  /**
   * Recupera tutti i libri adottati dalla classe specificata.
   * Il GROUP BY su l.id_libro evita duplicati quando lo stesso libro
   * è associato alla stessa classe più volte in Classi_Libri.
   *
   * @param int $idClasse L'ID della classe di cui recuperare i libri adottati.
   * @return array Array dei libri adottati dalla classe.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function getLibriByClasse(int $idClasse): array
  {
    $sql = "
			SELECT
				l.id_libro,
				l.isbn,
				l.titolo,
				l.autore,
				l.materia,
				l.editore,
				l.volume,
				l.anno_scolastico,
				l.prezzo
			FROM Libri l
			JOIN Classi_Libri cl USING(id_libro)
			WHERE cl.id_classe = ?
			GROUP BY
				l.id_libro,
				l.isbn,
				l.titolo,
				l.autore,
				l.materia,
				l.editore,
				l.volume,
				l.anno_scolastico,
				l.prezzo
			ORDER BY l.materia, l.titolo
		";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$idClasse]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Recupera l'elenco di tutte le materie presenti nel catalogo.
   * Usato per popolare i filtri di ricerca nella bacheca degli annunci.
   *
   * @return array Array delle materie distinte, ordinate alfabeticamente.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 21/04/2026
   */
  public function getMaterie(): array
  {
    $sql  = "SELECT DISTINCT materia FROM Libri ORDER BY materia";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'materia');
  }
}
