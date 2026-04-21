<?php
defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../models/AnnunciModels.php';
require_once __DIR__ . '/../models/LibriModels.php';

/**
 * Classe AnnunciController
 * Gestisce il flusso degli annunci: visualizzazione bacheca, pubblicazione,
 * dettaglio singolo annuncio, acquisto e rimozione.
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 17/04/2026
 */
class AnnunciController
{
  /** @var AnnunciModels Istanza del modello annunci */
  private $model;

  /** @var LibriModels Istanza del modello libri, usata per la validazione ISBN */
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
   * Mostra la bacheca degli annunci disponibili.
   * Applica i filtri di ricerca se presenti in $_GET.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function index(): void
  {
    requireLogin();

    $materia    = trim($_GET['materia']    ?? '');
    $condizione = trim($_GET['condizione'] ?? '');
    $prezzoMin  = (float) ($_GET['prezzo_min'] ?? 0);
    $prezzoMax  = (float) ($_GET['prezzo_max'] ?? 0);

    $annunci  = $this->model->getAnnunci($materia, $condizione, $prezzoMin, $prezzoMax);
    $materie  = $this->libriModel->getMaterie();

    $view = __DIR__ . '/../views/annunci/bacheca.php';
    include __DIR__ . '/../views/layout.php';
  }

  /**
   * Mostra il dettaglio di un singolo annuncio.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function dettaglio(): void
  {
    requireLogin();

    $id      = (int) ($_GET['id'] ?? 0);
    $annuncio = $this->model->getAnnuncioById($id);

    if (!$annuncio) {
      $_SESSION['errors'][] = "Annuncio non trovato";
      header("Location: index.php?page=annunci");
      exit;
    }

    $view = __DIR__ . '/../views/annunci/dettaglio.php';
    include __DIR__ . '/../views/layout.php';
  }

  /**
   * Carica il form per la pubblicazione di un nuovo annuncio.
   * Precarica i luoghi disponibili e i libri della classe dello studente.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function crea(): void
  {
    requireLogin();

    $luoghi  = $this->model->getLuoghi();

    // per avere i libri solo di una classe specifica
    //$libri   = $this->libriModel->getLibriByClasse($_SESSION['id_classe']);

    // tutti i libri
    $libri = $this->libriModel->getAllLibri();

    $condizioni = ['Ottime condizioni', 'Buone condizioni', 'Condizioni accettabili', 'Danneggiato'];

    $view = __DIR__ . '/../views/annunci/crea.php';
    include __DIR__ . '/../views/layout.php';
  }

  /**
   * Gestisce il salvataggio di un nuovo annuncio.
   * Esegue la validazione dell'ISBN, dei campi obbligatori e del prezzo,
   * poi inserisce il record nel database.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function store(): void
  {
    requireLogin();

    $_SESSION['errors']  = [];
    $_SESSION['success'] = '';

    $isbn           = trim($_POST['isbn']          ?? '');
    $prezzo         = trim($_POST['prezzo']        ?? '');
    $descrizione    = trim($_POST['descrizione']   ?? '');
    $dataOraScambio = trim($_POST['data_ora_scambio'] ?? '');
    $idLuogo        = (int) ($_POST['id_luogo']   ?? 0);
    $condizione     = trim($_POST['condizione']    ?? '');

    // verifica che il libro sia nel catalogo scolastico
    $libro = $this->libriModel->getLibroByIsbn($isbn);
    if (!$libro) {
      $_SESSION['errors'][] = "ISBN non trovato nel catalogo scolastico";
    }

    if (empty($condizione)) {
      $_SESSION['errors'][] = "Seleziona la condizione del libro";
    }
    if (!is_numeric($prezzo) || (float) $prezzo <= 0) {
      $_SESSION['errors'][] = "Inserisci un prezzo valido";
    }
    if (empty($dataOraScambio)) {
      $_SESSION['errors'][] = "Inserisci la data e l'ora dello scambio";
    }
    if ($idLuogo === 0) {
      $_SESSION['errors'][] = "Seleziona un luogo per lo scambio";
    }

    if (!empty($_SESSION['errors'])) {
      header("Location: index.php?page=annunci&action=crea");
      exit;
    }

    $params = [
      (float) $prezzo,
      $descrizione,
      $dataOraScambio,
      $_SESSION['id_studente'],
      $idLuogo,
      $libro['id_libro'],
      $condizione,
    ];

    $new_id = $this->model->insertAnnuncio($params);

    if ($new_id > 0) {
      $_SESSION['success'] = "Annuncio pubblicato con successo!";
      header("Location: index.php?page=annunci&action=dettaglio&id={$new_id}");
    } else {
      $_SESSION['errors'][] = "Errore durante la pubblicazione dell'annuncio";
      header("Location: index.php?page=annunci&action=crea");
    }
    exit;
  }

  /**
   * Gestisce l'acquisto di un libro da parte dello studente loggato.
   * Verifica che lo studente non stia acquistando un proprio annuncio.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function acquista(): void
  {
    requireLogin();

    $idAnnuncio = (int) ($_POST['id_annuncio'] ?? 0);
    $annuncio   = $this->model->getAnnuncioById($idAnnuncio);

    if (!$annuncio) {
      $_SESSION['errors'][] = "Annuncio non trovato";
      header("Location: index.php?page=annunci");
      exit;
    }

    // uno studente non può acquistare un proprio annuncio
    if ((int) $annuncio['id_venditore'] === (int) $_SESSION['id_studente']) {
      $_SESSION['errors'][] = "Non puoi acquistare un tuo annuncio";
      header("Location: index.php?page=annunci&action=dettaglio&id={$idAnnuncio}");
      exit;
    }

    if ($this->model->concludiAcquisto($_SESSION['id_studente'], $idAnnuncio)) {
      $_SESSION['success'] = "Acquisto registrato! Presentati all'orario indicato per il ritiro.";
      header("Location: index.php?page=dashboard");
    } else {
      $_SESSION['errors'][] = "Errore durante l'acquisto. L'annuncio potrebbe non essere più disponibile.";
      header("Location: index.php?page=annunci&action=dettaglio&id={$idAnnuncio}");
    }
    exit;
  }

  /**
   * Elimina un annuncio pubblicato dallo studente loggato.
   * Operazione consentita solo al venditore proprietario dell'annuncio.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function elimina(): void
  {
    requireLogin();

    $idAnnuncio = (int) ($_POST['id_annuncio'] ?? 0);

    if ($this->model->deleteAnnuncio($idAnnuncio, $_SESSION['id_studente'])) {
      $_SESSION['success'] = "Annuncio rimosso con successo";
    } else {
      $_SESSION['errors'][] = "Impossibile rimuovere l'annuncio";
    }

    header("Location: index.php?page=dashboard");
    exit;
  }
}
