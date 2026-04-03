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

     /**
     * funzione per selezionare parametri dei libri 
     *
     * @author Matteo Portacci <portacci.7780@isit100.fe.it>
     * @param mixed $variabile Descrizione parametro
     * @return void
     */
    public function selectLibri(array $param = []): array
    {
        /**
         * WHERE 1 = 1 -> seleziono solo i libri disponibili
         *
         * @author Matteo Portacci <portacci.7780@isit100.fe.it>
         * @param mixed $variabile Descrizione parametro
         * @return void
         */
        $dql = "SELECT isbn, titolo, materia, editore from Annunci WHERE 1 = 1";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }


};