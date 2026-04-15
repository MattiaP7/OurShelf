<?php

defined("APP") or die("Non si ha il permesso di accedere");

require_once 'config/dbconnect.php';

/**
 * Classe AnnunciModels
 * 
 * gestisce le operazioni CRUD per la tabella degli annunci.
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 14/04/2026
 */
class AnnunciModels
{
	/** @var PDO Istanza della connessione al database */
	private $pdo;

	/**
	 * Inizializza la connessione al db
	 *
	 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
	 * @date 14/04/2026
	 */
	public function __construct()
	{
		$this->pdo = DB::connect();
	}

	/**
	 * Funzione per ottenere l'array di tutti gli annunci
	 *
	 * @param array $param - deve essere vuoto.
	 * @return array
	 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
	 * @date 14/04/2026
	 */
	public function getAllAnnunci(array $param = []): array
	{
		$dql = "SELECT * from Annunci";
		$stm = $this->pdo->prepare($dql);
		$stm->execute($param);
		return $stm->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Funzione per creare un annuncio per un libro
	 *
	 * @param array $params []
	 * @return boolean
	 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
	 * @date 14/04/2026
	 */
	public function createAnnuncio(array $params): bool
	{
		$dql = "INSERT INTO `Annunci` (prezzo, data_acquisto, descrizione, data_ora_scambio, id_venditore, id_compratore, id_stato, id_condizione) VALUES ()";
		$stmt = $this->pdo->prepare($dql);
		$stmt->execute($params);
		return $stmt->rowCount() !== 0;
	}
};
