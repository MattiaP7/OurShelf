<?php

/**
 * Script per l'importazione massiva di libri da file JSON a database MySQL.
 * * Il processo gestisce automaticamente le relazioni tramite chiavi esterne,
 * effettua la pulizia dei dati monetari e garantisce l'integrità dei dati
 * tramite transazioni SQL.
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 27/03/2026
 */

require_once __DIR__ . '../../src/config/dbconfig.php';

/**
 * Funzione Helper per gestire le Chiavi Esterne (Foreign Keys).
 * * Cerca un valore in una tabella specifica; se esiste restituisce l'ID,
 * altrimenti inserisce una nuova riga e restituisce l'ID appena generato.
 *
 * @param PDO    $pdo    Istanza della connessione al database.
 * @param string $table  Nome della tabella in cui cercare/inserire.
 * @param string $idCol  Nome della colonna ID (chiave primaria).
 * @param string $valCol Nome della colonna contenente il valore da confrontare.
 * @param mixed  $value  Il valore da cercare o inserire.
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @return int|string|null Restituisce l'ID trovato/generato o null se il valore è vuoto.
 */
function getOrInsertId($pdo, $table, $idCol, $valCol, $value)
{
  if (empty(trim($value))) {
    return null;
  }

  $stmt = $pdo->prepare("SELECT $idCol FROM $table WHERE $valCol = :val LIMIT 1");
  $stmt->execute([':val' => $value]);
  $row = $stmt->fetch();

  if ($row) {
    return $row[$idCol];
  } else {
    $insertStmt = $pdo->prepare("INSERT INTO $table ($valCol) VALUES (:val)");
    $insertStmt->execute([':val' => $value]);
    return $pdo->lastInsertId();
  }
}

try {
  /** @var array $options Opzioni di configurazione per la connessione PDO */
  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
  $pdo = new PDO($dsn, $user, $pass, $options);

  $jsonFile = './libri_2025.json';
  if (!file_exists($jsonFile)) {
    die("Il file {$jsonFile} non trovato.");
  }

  $jsonData = file_get_contents($jsonFile);
  $data = json_decode($jsonData, true);

  if (json_last_error() !== JSON_ERROR_NONE) {
    die("Errore di sicurezza: File JSON malformato.");
  }

  // Inizio della transazione per garantire l'atomicità dell'importazione
  $pdo->beginTransaction();

  /** @var string $sqlBook Query di inserimento per la tabella books */
  $sqlBook = "INSERT INTO books (titolo, isbn, vol, author, school_year, price, id_class, id_subject, id_publish_house, id_faculty) 
                VALUES (:titolo, :isbn, :vol, :author, :school_year, :price, :id_class, :id_subject, :id_publish_house, :id_faculty)";

  $stmtBook = $pdo->prepare($sqlBook);

  /** @var int $libriInseriti Contatore dei record inseriti con successo */
  $libriInseriti = 0;

  if (isset($data['classi'])) {
    foreach ($data['classi'] as $datiClasse) {

      $nomeClasse = $datiClasse['classe'];
      $indirizzo = $datiClasse['indirizzo'];
      $annoScolastico = $datiClasse['anno_scolastico'];

      // Recupero o inserimento ID per le relazioni principali della classe
      $idClass = getOrInsertId($pdo, 'class', 'id_class', 'class', $nomeClasse);
      $idFaculty = getOrInsertId($pdo, 'faculty', 'id_faculty', 'name', $indirizzo);

      if (isset($datiClasse['libri']) && is_array($datiClasse['libri'])) {
        foreach ($datiClasse['libri'] as $libro) {

          // Recupero o inserimento ID per materia ed editore
          $idSubject = getOrInsertId($pdo, 'subjects', 'id_subject', 'name', $libro['materia']);
          $idPublishHouse = getOrInsertId($pdo, 'publishing_house', 'id_publish_house', 'name', $libro['editore']);

          // Formattazione del prezzo: normalizzazione virgola e cast a float
          $prezzoPulito = str_replace(',', '.', $libro['prezzo']);
          $prezzoFinale = is_numeric($prezzoPulito) ? floatval($prezzoPulito) : 0.00;

          // Esecuzione dell'inserimento del record libro
          $stmtBook->execute([
            ':titolo'           => $libro['titolo'],
            ':isbn'             => $libro['isbn'],
            ':vol'              => '', // Campo volume (attualmente vuoto come da specifica)
            ':author'           => $libro['autore'],
            ':school_year'      => $annoScolastico,
            ':price'            => $prezzoFinale,
            ':id_class'         => $idClass,
            ':id_subject'       => $idSubject,
            ':id_publish_house' => $idPublishHouse,
            ':id_faculty'       => $idFaculty
          ]);

          $libriInseriti++;
        }
      }
    }
  }

  $pdo->commit();
  echo "<h1>Importazione Completata!</h1><p>Sono stati importati <strong>$libriInseriti</strong> libri (inclusi i prezzi) nel database.</p>";
} catch (\PDOException $e) {
  // Rollback in caso di errore per evitare dati parziali o inconsistenti
  if (isset($pdo) && $pdo->inTransaction()) {
    $pdo->rollBack();
  }

  error_log("DB Error: " . $e->getMessage());
  echo "<h1>Errore Critico</h1><p>Operazione annullata. Controlla i log del server per i dettagli tecnici.</p>";
}
