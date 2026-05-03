<?php
// OurShelf/src/controllers/AnnunciController.php
defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../models/AnnunciModels.php';
require_once __DIR__ . '/../models/LibriModels.php';
require_once __DIR__ . '/../utils/secureUploader.php';
require_once __DIR__ . '/../models/ImmagineAnnuncioModel.php';


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
  /** @var AnnunciModels */
  private AnnunciModels $model;

  /** @var LibriModels */
  private LibriModels $libriModel;

  /** @var SecureUploader */
  private SecureUploader $uploader;

  /** @var ImmagineAnnuncioModel */
  private ImmagineAnnuncioModel $immagineModels;

  /** @var PDO  — la tua istanza PDO (adatta al tuo bootstrap) */
  private PDO $pdo;

  public function __construct()
  {
    $this->model          = new AnnunciModels();
    $this->libriModel     = new LibriModels();
    $this->uploader       = new SecureUploader();
    $this->immagineModels = new ImmagineAnnuncioModel();


    $this->pdo = DB::connect();
  }

  public function uploadImmagini(): void
  {
    requireLogin();

    $id_annuncio = (int)($_POST['id_annuncio'] ?? 0);

    if ($id_annuncio <= 0) {
      $_SESSION['errors'][] = "Annuncio non valido";
      header("Location: index.php?page=annunci&action=index");
      exit;
    }

    $annuncio = $this->model->getAnnuncioById($id_annuncio);

    $proprietario_id = $annuncio['proprietario'] ?? 0;

    if (!$annuncio || (int)$proprietario_id !== (int)$_SESSION['id_studente']) {
      $_SESSION['errors'][] = "Accesso negato";
      header("Location: index.php?page=annunci&action=index");
      exit;
    }

    if (!isset($_FILES['immagini'])) {
      $_SESSION['errors'][] = "Nessuna immagine inviata.";
      header("Location: index.php?page=annunci&action=uploadForm&id=$id_annuncio");
      exit;
    }

    $hasValidFile = false;

    foreach ($_FILES['immagini']['error'] as $error) {
      if ($error !== UPLOAD_ERR_NO_FILE) {
        $hasValidFile = true;
        break;
      }
    }

    if (!$hasValidFile) {
      $_SESSION['errors'][] = "Nessuna immagine selezionata dal browser.";
      header("Location: index.php?page=annunci&action=uploadForm&id=$id_annuncio");
      exit;
    }

    $this->uploader->salvaImmagineAnnuncio($id_annuncio, $_FILES['immagini']);

    header("Location: index.php?page=annunci&action=dettaglio&id=$id_annuncio");
    exit;
  }

  /**
   * Mostra il dettaglio di un singolo annuncio con il carousel immagini.
   * Le immagini vengono lette dalla tabella Immagini_Annunci tramite il model.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 01/05/2026
   */
  public function dettaglio(): void
  {
    requireLogin();

    $id       = (int) ($_GET['id'] ?? 0);
    $annuncio = $this->model->getAnnuncioById($id);

    if (!$annuncio) {
      $_SESSION['errors'][] = "Annuncio non trovato";
      header("Location: index.php");
      exit;
    }

    $immagini         = $this->immagineModels->getAllByAnnuncio($id);
    $avatar_venditore = $this->model->getImmagineVenditoreById($annuncio['id_venditore']);

    $title = $annuncio['titolo'];
    $view  = __DIR__ . '/../views/annunci/dettaglio.php';
    include __DIR__ . '/../views/layout.php';
  }


  /**
   * Mostra il form per la pubblicazione di un nuovo annuncio.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 01/05/2026
   */
  public function crea(): void
  {
    requireLogin();

    $libri      = $this->libriModel->getAllLibri();
    $luoghi     = $this->model->getLuoghi();
    $condizioni = get_condizioni();

    $title = "Crea annuncio";
    $view  = __DIR__ . '/../views/annunci/crea.php';
    include __DIR__ . '/../views/layout.php';
  }


  /**
   * Mostra il form per la modifica di un annuncio esistente.
   * Pre-carica i dati attuali, le immagini e le liste per le select.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 02/05/2026
   */
  public function modifica(): void
  {
    requireLogin();

    $id_annuncio  = (int) ($_GET['id'] ?? 0);
    $annuncio     = $this->model->getAnnuncioById($id_annuncio);

    $proprietario_id = $annuncio['proprietario'] ?? 0;

    if (!$annuncio || (int) $proprietario_id !== (int) $_SESSION['id_studente']) {
      $_SESSION['errors'][] = "Accesso negato o annuncio non trovato.";
      header("Location: index.php?page=annunci&action=index");
      exit;
    }

    $libri      = $this->libriModel->getAllLibri();
    $luoghi     = $this->model->getLuoghi();
    $condizioni = get_condizioni();
    $immagini   = $this->immagineModels->getAllByAnnuncio($id_annuncio);

    $title = "Modifica annuncio";
    $view = __DIR__ . '/../views/annunci/modifica.php';
    include __DIR__ . '/../views/layout.php';
  }

  public function update(): void
  {
    requireLogin();

    $idAnnuncio = (int) ($_POST['id_annuncio'] ?? 0);
    $annuncio   = $this->model->getAnnuncioById($idAnnuncio);

    $proprietario_id = $annuncio['proprietario'] ?? 0;
    if (!$annuncio || (int)$proprietario_id !== (int)$_SESSION['id_studente']) {
      $_SESSION['errors'][] = "Operazione non autorizzata.";
      header("Location: index.php");
      exit;
    }

    $isbn           = trim($_POST['isbn'] ?? '');
    $prezzo         = (float) ($_POST['prezzo_vendita'] ?? 0);
    $descrizione    = trim($_POST['descrizione'] ?? '');
    $dataOraScambio = trim($_POST['data_ora_scambio'] ?? '');
    $idLuogo        = (int) ($_POST['id_luogo'] ?? 0);
    $condizione     = trim($_POST['condizione'] ?? '');

    $libro = $this->libriModel->getLibroByIsbn($isbn);
    if (!$libro)
      $_SESSION['errors'][] = "ISBN non trovato nel catalogo";
    if ($prezzo <= 0)
      $_SESSION['errors'][] = "Inserisci un prezzo valido";
    if (empty($dataOraScambio))
      $_SESSION['errors'][] = "Inserisci data e ora dello scambio";

    if (!empty($_SESSION['errors'])) {
      header("Location: index.php?page=annunci&action=modifica&id=$idAnnuncio");
      exit;
    }

    $stato = (new DateTime($dataOraScambio) > new DateTime()) ? 'disponibile' : 'scaduto';

    $params = [
      $prezzo,
      $descrizione,
      $dataOraScambio,
      $idLuogo,
      $libro['id_libro'],
      $condizione,
      $stato,
      $idAnnuncio
    ];

    if ($this->model->updateAnnuncio($params)) {
      $_SESSION['success'] = "Annuncio aggiornato con successo!";
    } else {
      $_SESSION['errors'][] = "Errore durante il salvataggio delle modifiche.";
    }

    header("Location: index.php?page=annunci&action=dettaglio&id=$idAnnuncio");
    exit;
  }

  /**
   * Salva il nuovo annuncio nel DB, poi gestisce le immagini allegate.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 01/05/2026
   */
  public function store(): void
  {
    requireLogin();
    $isbn           = trim($_POST['isbn']             ?? '');
    $prezzo         = (float) ($_POST['prezzo']       ?? 0);
    $descrizione    = trim($_POST['descrizione']      ?? '');
    $dataOraScambio = trim($_POST['data_ora_scambio'] ?? '');
    $idLuogo        = (int) ($_POST['id_luogo']       ?? 0);
    $condizione     = trim($_POST['condizione']       ?? '');

    // Validazioni
    $libro = $this->libriModel->getLibroByIsbn($isbn);
    if (!$libro)
      $_SESSION['errors'][] = "ISBN non trovato nel catalogo scolastico";
    if (empty($condizione))
      $_SESSION['errors'][] = "Seleziona la condizione del libro";
    if (!is_numeric($prezzo) || $prezzo <= 0)
      $_SESSION['errors'][] = "Inserisci un prezzo valido";
    if (empty($dataOraScambio))
      $_SESSION['errors'][] = "Inserisci data e ora dello scambio";
    if ($idLuogo === 0)
      $_SESSION['errors'][] = "Seleziona un luogo per lo scambio";

    if (!empty($_SESSION['errors'])) {
      header("Location: index.php?page=annunci&action=crea");
      exit;
    }

    $params = [
      $prezzo,
      $descrizione,
      $dataOraScambio,
      $_SESSION['id_studente'],
      $idLuogo,
      $libro['id_libro'],
      $condizione,
    ];

    $newId = $this->model->insertAnnuncio($params);

    if ($newId <= 0) {
      $_SESSION['errors'][] = "Errore durante la pubblicazione dell'annuncio";
      header("Location: index.php?page=annunci&action=crea");
      exit;
    }

    /**
     * Il form deve avere enctype="multipart/form-data" e un input
     * name="immagini[]" multiple per le foto (opzionale al momento della creazione).
     * TODO: al momento non funziona...
     */
    $files_sent = false;

    if (isset($_FILES['immagini'])) {
      foreach ($_FILES['immagini']['error'] as $error) {
        if ($error !== UPLOAD_ERR_NO_FILE) {
          $files_sent = true;
          break;
        }
      }
    }

    if ($files_sent) {
      $this->uploader->salvaImmagineAnnuncio($newId, $_FILES['immagini']);
      $_SESSION['success'] = "Annuncio e foto pubblicati!";
      header("Location: index.php?page=annunci&action=dettaglio&id={$newId}");
    } else {
      $_SESSION['success'] = "Annuncio creato! Ora aggiungi delle foto.";
      header("Location: index.php?page=annunci&action=uploadForm&id={$newId}");
    }
    exit;
  }


  /**
   * Mostra la pagina per il caricamento delle immagini per l'annuncio
   * Cosi un utente puo' cambiare le immagini o caricarle se non era stato fatto in precedenza
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 01/05/2026
   */
  public function uploadForm(): void
  {
    requireLogin();

    $idAnnuncio = (int) ($_GET['id'] ?? 0);
    $annuncio   = $this->model->getAnnuncioById($idAnnuncio);

    $proprietario_id = $annuncio['proprietario'] ?? 0;

    if (!$annuncio || (int)$proprietario_id !== (int)$_SESSION['id_studente']) {
      $_SESSION['errors'][] = "Accesso negato o annuncio non trovato.";
      header("Location: index.php?page=annunci&action=index");
      exit;
    }

    $immagini = $this->immagineModels->getAllByAnnuncio($idAnnuncio);

    $title = "Aggiungi foto";
    $view  = __DIR__ . '/../views/annunci/upload_immagini.php';
    include __DIR__ . '/../views/layout.php';
  }


  /**
   * Gestisce l'acquisto di un libro.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 01/05/2026
   */
  public function acquista(): void
  {
    requireLogin();

    $idAnnuncio = (int) ($_POST['id_annuncio'] ?? 0);
    $annuncio   = $this->model->getAnnuncioById($idAnnuncio);

    if (!$annuncio) {
      $_SESSION['errors'][] = "Annuncio non trovato";
      header("Location: index.php");
      exit;
    }

    if ((int) $annuncio['id_venditore'] === (int) $_SESSION['id_studente']) {
      $_SESSION['errors'][] = "Non puoi acquistare un tuo annuncio";
      header("Location: index.php?page=annunci&action=dettaglio&id={$idAnnuncio}");
      exit;
    }

    if ($this->model->concludiAcquisto($idAnnuncio, $_SESSION['id_studente'])) {
      $_SESSION['success'] = "Acquisto registrato! Presentati all'orario indicato per il ritiro.";
      header("Location: index.php?page=dashboard");
    } else {
      $_SESSION['errors'][] = "Errore durante l'acquisto. L'annuncio potrebbe non essere più disponibile.";
      header("Location: index.php?page=annunci&action=dettaglio&id={$idAnnuncio}");
    }
    exit;
  }


  /**
   * Annulla un acquisto entro 24 ore.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 01/05/2026
   */
  public function annullaAcquisto(): void
  {
    requireLogin();

    $idAnnuncio    = $_POST['id_annuncio']  ?? null;
    $dataAcquistoR = $_POST['data_acquisto'] ?? null;
    $idCompratore  = $_SESSION['id_studente'];

    if (!$idAnnuncio || !$dataAcquistoR) {
      $_SESSION['errors'][] = "Dati insufficienti per annullare l'operazione.";
      header("Location: index.php?page=dashboard&action=index");
      exit;
    }

    $dataAcquisto = new DateTime($dataAcquistoR);
    $differenza   = (new DateTime())->diff($dataAcquisto);

    if ($differenza->days >= 1) {
      $_SESSION['errors'][] = "Tempo scaduto: puoi annullare l'acquisto solo entro 24 ore.";
      header("Location: index.php?page=dashboard&action=index");
      exit;
    }

    if ($this->model->annullaAcquisto((int) $idAnnuncio, (int) $idCompratore)) {
      $_SESSION['success'] = "Acquisto annullato con successo.";
    } else {
      $_SESSION['errors'][] = "Errore durante l'annullamento.";
    }

    header("Location: index.php?page=dashboard&action=index");
    exit;
  }

  /**
   * Elimina una singola immagine
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 01/05/2026
   */
  public function eliminaImmagine(): void
  {
    requireLogin();
    $idImg = (int)($_GET['id_img'] ?? 0);
    $idAnnuncio = (int)($_GET['id_annuncio'] ?? 0);

    $img = $this->immagineModels->getById($idImg);
    $annuncio = $this->model->getAnnuncioById($idAnnuncio);

    if ($img && $annuncio && (int)$annuncio['proprietario'] === (int)$_SESSION['id_studente']) {
      // 2. Elimina file fisico
      $percorsoFisico = __DIR__ . '/../../public/uploads/annunci/' . $img['nome_file'];
      if (file_exists($percorsoFisico)) {
        unlink($percorsoFisico);
      }

      $this->immagineModels->delete($idImg);
      $_SESSION['success'] = "Immagine rimossa con successo.";
    } else {
      $_SESSION['errors'][] = "Impossibile eliminare l'immagine.";
    }

    header("Location: index.php?page=annunci&action=uploadForm&id=" . $idAnnuncio);
    exit;
  }

  /**
   * Elimina l'annuncio: prima i file fisici, poi il record DB.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 01/05/2026
   */
  public function elimina(): void
  {
    requireLogin();

    $idAnnuncio = (int) ($_POST['id_annuncio'] ?? 0);

    // 1. Elimina i file fisici (legge i nomi dal DB prima del CASCADE)
    $this->uploader->eliminaImmaginiAnnuncio($idAnnuncio);

    // 2. Elimina l'annuncio — il CASCADE rimuove le righe in Immagini_Annunci
    if ($this->model->deleteAnnuncio($idAnnuncio, $_SESSION['id_studente'])) {
      $_SESSION['success'] = "Annuncio rimosso con successo";
    } else {
      $_SESSION['errors'][] = "Impossibile rimuovere l'annuncio";
    }

    header("Location: index.php?page=dashboard");
    exit;
  }
}
