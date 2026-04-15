<?php

defined("APP") or die("Non si ha il permesso di accedere");

require_once 'config/dbconnect.php';
/**
 * Undocumented class
 *
 * @author Nome Cognome Ionut Anusca <anusca.7806@isit100.fe.it>
 * @date 01/04/2026
 */
class AnnunciModels
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = DB::connect();
  }
  //funzione per inserire un annuncio,prende in input un array di cui seguenza dei valore deve essere :
  //prezzo,id_venditore,id_libro,id_condizione e in fine la descrizione che è opzionale
  //lo stato è automaticamente settato a disponibile
  public function insert(array $param = []): void
  {
    if (count($param) == 5) {
      $dml = "INSERT INTO Annunci(`prezzo`,`id_venditore`,`id_libro`,`id_condizione`,`descrizione`,`id_stato`)
      VALUES (?,?,?,?,?,?)";
    }
    //-----------------------------------------
    elseif (count($param) == 4) {
      $dml = "INSERT INTO Annunci(`prezzo`,`id_venditore`,`id_libro`,`id_condizione`,`id_stato`)
      VALUES (?,?,?,?,?)";
    }
    $param[] = 1;
    $stm = $this->pdo->prepare($dml);
    $stm->execute($param);
    //------------------------------------------
  }
  public function selectAll(array $param = []): array
  {
    $dql = "SELECT * from Annunci";
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }
  public function find($key, $value): array
  {
    $dql = "SELECT * FROM Annuncui WHERE $key = ?";
    $stm = $this->pdo->prepare($dql);
    $stm->execute([$value]);

    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }
  public function delete($key, $value): bool
  {
    $dml = "DELETE FROM Annuncui WHERE $key=?";
    //---------------------------------------------
    $stm = $this->pdo->prepare($dml);
    $stm->execute([$value]);
    //---------------------------------------------
    return $stm->rowCount() !== 0;
  }
  public function update($id, $key, $value): void
  {
    $sql = "UPDATE Annunci SET $key = ? WHERE id = ?";
    $stm = $this->pdo->prepare($sql);
    $stm->execute([$value, $id]);
  }
};
