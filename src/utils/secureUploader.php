<?php

defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../models/UsersModels.php';
require_once __DIR__ . '/../models/ImmagineAnnuncioModel.php';

/**
 * Classe per la gestione del caricamento delle immagini sul server.
 * Si occupa di salvarle fisicamente, nel database, operazioni CRUD.
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 02/05/2026
 */
class secureUploader
{
  /** @var string $dir_annuncio path dove salviamo le immagini degli annunci */
  private string $dir_annuncio;

  /** @var string $dir_users path dove salviamo le immagini degli utenti */
  private string $dir_users;

  /** @var int $max_image numero massimo di immagini caricabili */
  private int   $max_image;

  /** @var int  $max_mb numero massimo di MB */
  private int   $max_mb;

  /** @var int $max_size dimensione massima per immagine (in MB) */
  private int   $max_size;

  /** @var array $allowed_mime tipi MIME usabili, presi dal form */
  private array $allowed_mime = ['image/jpeg', 'image/png', 'image/webp'];

  /** @var array $allowed_ext estensioni immagini consentite */
  private array $allowed_ext  = ['jpg', 'jpeg', 'png', 'webp'];

  /** @var ImmagineAnnuncioModel model dedicato alla tabella Immagini_Annunci */
  private ImmagineAnnuncioModel $immagineModel;

  /** @var UsersModels model dedicato alla tabella Studente */
  private UsersModels $userModel;

  public function __construct()
  {
    $this->dir_annuncio   = __DIR__ . '/../../public/uploads/annunci/';
    $this->dir_users      = __DIR__ . '/../../public/uploads/users/';
    $this->max_image      = 3;
    $this->max_mb         = 2;
    $this->max_size       = $this->max_mb * 1024 * 1024; // 2MB
    $this->immagineModel  = new ImmagineAnnuncioModel();
    $this->userModel      = new UsersModels();
  }

  /**
   * Salva fino a `secureUploader::max_image` immagini per un annuncio e le registra nel DB
   * Scrive errori e success nella $_SESSION
   *
   * @param integer $id_annuncio
   * @param array $files  $_FILES['immagini']
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 30/04/2026
   */
  public function salvaImmagineAnnuncio(int $id_annuncio, array $files): void
  {
    $trovate = $this->immagineModel->countByAnnuncio($id_annuncio);

    if ($trovate >= $this->max_image) {
      $_SESSION['errors'][] = 'Hai già caricato il massimo di 3 immagini!';
      return;
    }


    if (empty($files)) {
      $_SESSION['errors'][] = 'Nessun file ricevuto';
      return;
    }

    $salvati = 0;
    $immagini_rimaste = $this->max_image - $trovate;
    // se files['name'] e' un array prendiamo la dimensione, altrimenti ha valore 1
    $count = is_array($files['name']) ? count($files['name']) : 1;

    for ($i = 0; $i < $count; $i++) {
      if ($salvati >= $immagini_rimaste) {
        $_SESSION['errors'][] = "Limite di 3 immagini raggiunto. File extra ignorati.";
        break;
      }

      $error = is_array($files['error']) ? $files['error'][$i] : $files['error'];
      if ($error === UPLOAD_ERR_NO_FILE) continue;

      /**
       * Dobbiamo fare questo perche' la struttura del array files e' fatta cosi:
       * $_FILES['immagini'] ha questa forma:
          ['name' => ['a.jpg', 'b.png'], 'tmp_name' => ['/tmp/1', '/tmp/2'], 'error' => [0, 0], ...]
       */

      $file = [
        'name'     => is_array($files['name'])     ? $files['name'][$i]     : $files['name'],
        'type'     => is_array($files['type'])     ? $files['type'][$i]     : $files['type'],
        'tmp_name' => is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'],
        'error'    => $error,
        'size'     => is_array($files['size'])     ? $files['size'][$i]     : $files['size'],
      ];

      $errore = $this->valida($file);
      if ($errore !== "") {
        $_SESSION['errors'][] = $errore;
        continue;
      }

      $nome_file = $this->genera_nome('ann', $id_annuncio, $file['name']);
      $dest      = $this->dir_annuncio . $nome_file;

      if (move_uploaded_file($file['tmp_name'], $dest)) {
        chmod($dest, 0644);
        $this->immagineModel->insert($id_annuncio, $nome_file);
        $salvati++;
      } else {
        $_SESSION['errors'][] = "Errore nel salvataggio di \"{$file['name']}\".";
      }
    }

    if ($salvati > 0) {
      $_SESSION['success'] = $salvati === 1
        ? "1 foto caricata con successo!"
        : "{$salvati} foto caricate con successo!";
    }
  }

  /**
   * Elimina tutti i file fisici di un annuncio dal disco.
   * I nomi file vengono letti tramite ImmagineAnnuncioModel.
   * Le righe DB vengono cancellate dal CASCADE quando si elimina l'annuncio.
   * Da chiamare PRIMA del DELETE sull'annuncio.
   *
   * @param integer $id_annuncio
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 02/05/2026
   */
  public function eliminaImmaginiAnnuncio(int $id_annuncio): void
  {
    $files = $this->immagineModel->getNomeFileByAnnuncio($id_annuncio);

    foreach ($files as $f) {
      $path = $this->dir_annuncio . $f;
      if (file_exists($path)) {
        unlink($path);
      }
    }
  }

  /**
   * Salva l'avatar di uno studente e aggiorna  la foto 
   * Elimina il vecchio file fisico prima di salvare il nuovo.
   *
   * @param integer $id_studente
   * @param array $file
   * @param string $vecchia_foto
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 02/05/2026
   */
  public function salvaAvatar(int $id_studente, array $file, string $vecchia_foto = ''): void
  {
    // Campo non compilato: l'utente ha saltato l'upload, non è un errore
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
      return;
    }

    $errore = $this->valida($file);
    if ($errore !== "") {
      $_SESSION['errors'][] = $errore;
      return;
    }

    // Elimina il vecchio file fisico (se esiste)
    if (!empty($vecchia_foto)) {
      $vecchioPath = $this->dir_users . $vecchia_foto;
      if (file_exists($vecchioPath)) {
        unlink($vecchioPath);
      }
    }

    $nomeFile = $this->genera_nome('user', $id_studente, $file['name']);
    $dest     = $this->dir_users . $nomeFile;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
      $_SESSION['errors'][] = "Errore nel salvataggio della foto profilo.";
      return;
    }

    chmod($dest, 0644);

    $this->userModel->updateAvatar($nomeFile, $id_studente);
    $_SESSION['success'] = "Foto profilo aggiornata!";
  }

  /**
   * Normalizza la struttura dell'array $_FILES per la gestione di upload singoli o multipli.
   *
   * PHP organizza i dati dei file multipli (es. immagini[]) raggruppandoli per proprietà 
   * (tutti i nomi, tutti i tipi, ecc.) anziché per singolo file. Questa funzione "ruota" 
   * tale struttura restituendo un array numerico di file, dove ogni elemento è un 
   * array associativo pronto per essere ciclato con foreach.
   *
   * Esempio di trasformazione:
   * Input:  ['name' => ['f1.jpg', 'f2.png'], 'tmp_name' => ['/tmp/1', '/tmp/2'], ...]
   * Output: [0 => ['name' => 'f1.jpg', ...], 1 => ['name' => 'f2.png', ...]]
   *
   * @param  array $files L'estratto dell'array superglobale $_FILES (es. $_FILES['immagini']).
   * @return array Un array di file "normalizzati". Se un file non è stato caricato (slot vuoto),
   *               viene escluso dal risultato finale.
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 30/04/2026
   */
  private function normalizza(array $files): array
  {
    $out = [];
    if (!is_array($files['name'])) {
      if ($files['error'] !== UPLOAD_ERR_NO_FILE) {
        $out[] = $files;
      }
    }

    // ripristino il ciclo per upload multiplici
    foreach ($files['name'] as $i => $name) {
      if ($files['error'][$i] !== UPLOAD_ERR_NO_FILE) {
        $out[] = [
          'name'     => $files['name'][$i],
          'type'     => $files['type'][$i],
          'tmp_name' => $files['tmp_name'][$i],
          'error'    => $files['error'][$i],
          'size'     => $files['size'][$i]
        ];
      }
    }

    return $out;
  }

  /**
   * Genera un nome file univoco e sicuro.
   * Formato: {prefisso}_{id}_{uniqid}.{ext}
   * Esempio: ann_7_6824f1a3c0e15.jpg  oppure  user_3_6824f1a3c0e22.png
   *
   * @param string $prefisso
   * @param integer $id
   * @param string $nomeOriginale
   * @return string
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 30/04/2026
   */
  private function genera_nome(string $prefisso, int $id, string $nomeOriginale): string
  {
    $ext = strtolower(pathinfo($nomeOriginale, PATHINFO_EXTENSION));
    if ($ext === 'jpeg') $ext = 'jpg'; // normalizza
    return "{$prefisso}_{$id}_" . uniqid() . ".{$ext}";
  }

  /**
   * Valida un singolo file caricato.
   * 
   * Esegue controlli sequenziali su:
   * 1. Errori nativi di caricamento PHP.
   * 2. Dimensione massima consentita.
   * 3. Tipo MIME reale (tramite analisi binaria del contenuto).
   * 4. Estensione del nome file.
   * 5. Integrità della struttura dell'immagine.
   *
   * @param  array  $file L'array del singolo file (estratto da normalizza()).
   * @return string Restituisce il messaggio di errore se la validazione fallisce, 
   *                altrimenti restituisce una stringa vuota ('').
   */
  private function valida(array $file): string
  {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
      return "Errore nel caricamento di \"{$file['name']}\".";
    }

    if (($file['size'] ?? 0) > $this->max_size) {
      return "Il file \"{$file['name']}\" supera i {$this->max_mb}MB.";
    }

    $tmp = $file['tmp_name'] ?? null;
    if (!$tmp || !is_file($tmp)) {
      return "File non disponibile.";
    }

    // MIME reale
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp);

    if (!in_array($mime, $this->allowed_mime, true)) {
      return "Formato non consentito per \"{$file['name']}\".";
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $this->allowed_ext, true)) {
      return "Estensione non consentita.";
    }


    $check = getimagesize($tmp);
    if ($check === false) {
      return "Il file \"{$file['name']}\" non è un'immagine valida o il formato non è supportato dal server.";
    }

    return ""; // successo
  }
};
