<?php
defined('APP') or die('Accesso negato');
require_once 'dbconfig.php';

/**
 * Classe per la gestione della connessione al Database.
 * Fornisce un'interfaccia statica per stabilire una connessione sicura tramite PDO, garantendo che l'applicazione utilizzi standard moderni per le query SQL.
 *
 * @author Nome Cognome <email@isit100.fe.it>
 * @date 19/03/2026
 */
class DB
{
  /**
   * Crea e restituisce un'istanza di connessione PDO.
   * Utilizza le costanti definite in dbconfig.php per stabilire la connessione.
   * In caso di errore, solleva un'eccezione PDOException per permettere il debugging.
   *
   * @return PDO|void L'oggetto della connessione se riuscita, altrimenti termina con errore.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 19/03/2026
   */
  public static function connect(): PDO
  {
    try {
      $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USERNAME,
        DB_PASSWORD,
        // quando c'è un errore solleva eccezzione
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
      );
      return $pdo;
    } catch (PDOException $e) {
      throw new Exception('Errore di connessione al database.');
    }
  }
}
