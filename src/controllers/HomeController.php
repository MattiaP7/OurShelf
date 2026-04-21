<?php
defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../models/AnnunciModels.php';
require_once __DIR__ . '/../models/LibriModels.php';

/**
 * Classe HomeController
 * Gestisce la homepage dell'applicazione e le pagine statiche.
 * Recupera e passa alla main_view tutti gli annunci disponibili,
 * applicando gli eventuali filtri di ricerca presenti in $_GET.
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 17/04/2026
 */
class HomeController
{
  /** @var AnnunciModels Istanza del modello annunci */
  private $model;

  /** @var LibriModels Istanza del modello libri (per la lista materie) */
  private $libriModel;

  /**
   * Inizializza il controller e i modelli necessari.
   *
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function __construct()
  {
    $this->model      = new AnnunciModels();
    $this->libriModel = new LibriModels();
  }

  /**
   * Carica la homepage con tutti gli annunci disponibili.
   * Legge i filtri opzionali da $_GET (isbn, titolo, materia, editore, condizione,
   * prezzo_min, prezzo_max) e li passa al modello per filtrare i risultati.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function index(): void
  {
    $materia    = trim($_GET['materia']    ?? '');
    $condizione = trim($_GET['condizione'] ?? '');
    $isbn       = trim($_GET['isbn']       ?? '');
    $titolo     = trim($_GET['titolo']     ?? '');
    $editore    = trim($_GET['editore']    ?? '');
    $prezzoMin  = (float) ($_GET['prezzo_min'] ?? 0);
    $prezzoMax  = (float) ($_GET['prezzo_max'] ?? 0);

    $annunci = $this->model->getAnnunci(
      $materia,
      $condizione,
      $prezzoMin,
      $prezzoMax,
      $isbn,
      $titolo,
      $editore
    );

    $materie = $this->libriModel->getMaterie();
    $condizioni = ['Ottime condizioni', 'Buone condizioni', 'Condizioni accettabili', 'Danneggiato'];


    $view = __DIR__ . '/../views/layout/main_view.php';
    include __DIR__ . '/../views/layout.php';
  }

  /**
   * Carica la pagina "Chi siamo".
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function about(): void
  {
    $view = __DIR__ . '/../views/link/chi_siamo.php';
    include __DIR__ . '/../views/layout.php';
  }
}
