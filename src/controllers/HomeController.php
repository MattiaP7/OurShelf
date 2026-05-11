<?php
defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../models/AnnunciModels.php';
require_once __DIR__ . '/../models/LibriModels.php';
require_once __DIR__ . '/../models/AnnunciModels.php';
require_once __DIR__ . '/../models/ImmagineAnnuncioModel.php';

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
  private AnnunciModels $annunciModel;

  /** @var LibriModels Istanza del modello libri (per la lista materie) */
  private LibriModels $libriModel;

  /** @var ImmagineAnnuncioModel Istanza del modello ImmagineAnnuncio  */
  private ImmagineAnnuncioModel $immagineModels;


  /**
   * Inizializza il controller e i modelli necessari.
   *
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function __construct()
  {
    $this->annunciModel   = new AnnunciModels();
    $this->libriModel     = new LibriModels();
    $this->immagineModels = new ImmagineAnnuncioModel();
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

    $this->annunciModel->scadiAnnunci();

    $annunci = $this->annunciModel->getAnnunci(
      $materia,
      $condizione,
      $prezzoMin,
      $prezzoMax,
      $isbn,
      $titolo,
      $editore
    );

    $materie = $this->libriModel->getMaterie();
    $condizioni = get_condizioni();


    $title = 'Home Page';
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
    $title = 'About Page';
    $view = __DIR__ . '/../views/users/chi_siamo.php';
    include __DIR__ . '/../views/layout.php';
  }

  public function notFound(): void
  {
    $title = 'Pagina non trovata';
    $view = __DIR__ . '/../views/404.php';
    include __DIR__ . '/../views/layout.php';
  }
}
