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

    // fa scadere gli annunci con data scaduta
    $this->model->scadiAnnunci();

    // recupero tutti gli annunci del venditore e li separo per stato
    $tuttiAnnunci = $this->model->getAnnunciByVenditore($idStudente);

    // separiamo gli annunci per stato
    $inVendita    = [];
    $libriVenduti = [];


    $n_scaduti = 0;
    $n_disponibili = 0;

    foreach ($tuttiAnnunci as $a) {
      if ($a['stato'] === 'disponibile' || $a['stato'] === 'scaduto') {
        $inVendita[] = $a;
        if ($a['stato'] === 'scaduto') $n_scaduti++;
        if ($a['stato'] === 'disponibile') $n_disponibili++;
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
