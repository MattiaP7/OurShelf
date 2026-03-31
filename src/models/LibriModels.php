<?php

defined("APP") or die("Non si ha il permesso di accedere");

require_once 'config/dbconnect.php';

class LibriModels
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = DB::connect();
  }


  public function selectAllLibri(array $param = []): array
  {
    $dql = "SELECT * from Libri";
    $stm = $this->pdo->prepare($dql);
    $stm->execute($param);
    return $stm->fetchAll(PDO::FETCH_ASSOC);
  }
};
