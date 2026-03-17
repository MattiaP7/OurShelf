<?php
defined("APP") or die("Accesso negato");
require_once "dbconfig.php";

/**
 * Classe per la gestione della connessione al Database.
 * Fornisce un'interfaccia statica per stabilire una connessione sicura tramite PDO, garantendo che l'applicazione utilizzi standard moderni per le query SQL.
 *
 * @author **Mattia Pirazzi** <PIRAZZI.8076@isit100.fe.it>
 */
class DB
{
  /**
   * Crea e restituisce un'istanza di connessione PDO.
   * Utilizza le costanti definite in dbconfig.php per stabilire la connessione.
   * In caso di errore, solleva un'eccezione PDOException per permettere il debugging.
   *
   * @return PDO|void L'oggetto della connessione se riuscita, altrimenti termina con errore.
   * @author **Mattia Pirazzi** <PIRAZZI.8076@isit100.fe.it>
   */
  public static function connect()
  {
    try {
      $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USERNAME,
        DB_PASSWORD,
        // quando c'è un errore solleva eccezzione
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
      );
      return $pdo;
    } catch (PDOException $e) {
      echo $e->getMessage(); // stampare il messaggio non e' il top ma amen.
      die("Errore di connessione al database.");
    }
  }
}
