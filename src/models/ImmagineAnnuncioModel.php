<?php

defined("APP") or die("Accesso negato");

require_once 'config/dbconnect.php';

/**
 * Classe che si occupa delle funzioni CRUD per la tabelle Immagini_Annunci
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 02/05/2026
 */
class ImmagineAnnuncioModel
{
  /** @var PDO istanza al collegamento del DB */
  private PDO $pdo;

  public function __construct()
  {
    $this->pdo = DB::connect();
  }

  /**
   * Conta quante immagini esistono per un dato annuncio.
   *
   * @param integer $id_annuncio
   * @return integer
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 02/05/2026
   */
  public function countByAnnuncio(int $id_annuncio): int
  {
    $sql = "SELECT COUNT(*) FROM Immagini_Annunci WHERE id_annuncio = ?";
    $stm = $this->pdo->prepare($sql);
    $stm->execute([$id_annuncio]);
    return (int) $stm->fetchColumn();
  }


  /**
   *  Inserisce un record nella tabella Immagini_Annunci.
   *
   * @param integer $id_annuncio
   * @param string $nome_file
   * @return boolean
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 02/05/2026
   */
  public function insert(int $id_annuncio, string $nome_file): bool
  {
    $sql = "INSERT INTO Immagini_Annunci (id_annuncio, nome_file) VALUES (?, ?)";
    $stm = $this->pdo->prepare($sql);
    return $stm->execute([$id_annuncio, $nome_file]);
  }

  /**
   * Restituisce tutte le immagini di un annuncio
   *
   * @param integer $id_annuncio
   * @return array
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 02/05/2026
   */
  public function getAllByAnnuncio(int $id_annuncio): array
  {
    $sql = "SELECT id_immagine, nome_file
              FROM Immagini_Annunci
             WHERE id_annuncio = ?
             ORDER BY id_immagine ASC";
    $stm = $this->pdo->prepare($sql);
    $stm->execute([$id_annuncio]);
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Restituisce solo i nomi file di un annuncio 
   *
   * @param integer $id_annuncio
   * @return array ['ann_7_aaa.jpg', 'ann_7_bbb.png', ...]
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 02/05/2026
   */
  public function getNomeFileByAnnuncio(int $id_annuncio): array
  {
    $sql = "SELECT nome_file FROM Immagini_Annunci WHERE id_annuncio = ?";
    $stm = $this->pdo->prepare($sql);
    $stm->execute([$id_annuncio]);
    return $stm->fetchAll(PDO::FETCH_COLUMN);
  }

  /**
   * Recupera una singola immagine per id_immagine.
   *
   * @param integer $id_immagine
   * @return array|false
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 02/05/2026
   */
  public function getById(int $id_immagine): array|false
  {
    $sql = "SELECT id_immagine, id_annuncio, nome_file
              FROM Immagini_Annunci
             WHERE id_immagine = ?";
    $stm = $this->pdo->prepare($sql);
    $stm->execute([$id_immagine]);
    return $stm->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Elimina il record di una singola immagine dal DB.
   *
   * @param integer $id_immagine
   * @return boolean
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 02/05/2026
   */
  public function delete(int $id_immagine): bool
  {
    $sql = "DELETE FROM Immagini_Annunci WHERE id_immagine = ?";
    $stm = $this->pdo->prepare($sql);
    return $stm->execute([$id_immagine]);
  }
};
