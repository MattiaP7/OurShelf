<?php

defined("APP") or die("Non si ha il permesso di accedere");

require_once 'config/dbconnect.php';

class AnnuncuModels
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DB::connect();
    }

    public function selectAnnunci(array $param = []): array
    {
        $dql = "SELECT * from Annunci WHERE ";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
};