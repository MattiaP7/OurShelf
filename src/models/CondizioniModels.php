<?php

defined("APP") or die("Non si ha il permesso di accedere");

require_once 'config/dbconnect.php';

class CondizioniModels
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DB::connect();
    }


    /**
     * Undocumented function
     *
     * @param array $param
     * @return array
     * @author Ionut Anusca <anusca.7806@isit100.fe.it>
     * @date 31/03/2026
     */
    public function selectAll(array $param = []): array
    {
        $dql = "SELECT * from Condizioni";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    public function find($value): array
    {
        $dql = "SELECT * FROM Condizioni WHERE nome = ?";
        $stm = $this->pdo->prepare($dql);
        $stm->execute([$value]);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
};
