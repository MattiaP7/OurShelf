<?php
defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../models/AnnunciModels.php';

/**
 * Classe DashboardController
 * Gestisce l'area personale dello studente autenticato.
 * Mostra lo storico dei libri acquistati, venduti e gli annunci attualmente attivi.
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 17/04/2026
 */
class DashboardController
{
  /** @var AnnunciModels Istanza del modello annunci */
  private $model;

  /**
   * Inizializza il controller e il modello necessario.
   *
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function __construct()
  {
    $this->model = new AnnunciModels();
  }

  /**
   * Carica e mostra la dashboard personale dello studente.
   * Recupera in parallelo: libri in vendita, libri venduti e libri acquistati.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function index(): void
  {
    requireLogin();

    $idStudente = (int) $_SESSION['id_studente'];

    // recupero tutti gli annunci del venditore e li separo per stato
    $annuncio   = $this->model->getAnnunciByVenditore($idStudente);
    $inVendita = [];
    $libriVenduti = [];

    foreach ($annuncio as $a) {
      if ($a['stato'] === 'disponibile') {
        $inVendita[] = $a;
      } elseif ($a['stato'] === 'venduto') {
        $libriVenduti[] = $a;
      }
    }
    $libriAcquistati = $this->model->getLibriAcquistati($idStudente);

    $title = "{$_SESSION['nome_completo']}";
    $view = __DIR__ . '/../views/dashboard/index.php';
    include __DIR__ . '/../views/layout.php';
  }
}
