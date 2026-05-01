<?php

defined("APP") or die("Accesso negato");

class secureUploader
{
  /** @var string $dir_annuncio path dove salviamo le immagini degli annunci */
  private string $dir_annuncio;
  /** @var string $dir_users path dove salviamo le immagini degli utenti */
  private string $dir_users;

  /** @var int $max_image numero massimo di immagini caricabili */
  private int   $max_image;
  /** @var int $max_size dimensione massima per immagine (in MB) */
  private int   $max_size;

  /** @var array $allowed_mime tipi mime usabili, presi dal form */
  private array $allowed_mime = ['image/jpeg', 'image/png', 'image/webp'];
  /** @var array $allowed_ext estensioni immagini consentite */
  private array $allowed_ext  = ['jpg', 'jpeg', 'png', 'webp'];

  public function __construct()
  {
    // prendiamo il nome della radice e saliamo di 2 livelli per arrivare alla radice del progetto
    $root = dirname(__DIR__, 2);
    $this->dir_annuncio = $root . '/public/uploads/annunci/';
    $this->dir_users    = $root . '/public/uploads/users/';
    $this->max_image    = 3;
    $this->max_size     = 2 * 1024 * 1024; // 2MB
  }

  /**
   * Salva fino a 3 immagini per un annuncio e le registra nel DB
   * Scrive errori e success nella $_SESSION
   *
   * @param PDO $pdo
   * @param integer $id_annuncio
   * @param array $files  $_FILES['immagini']
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 30/04/2026
   */
  public function salvaImmagineAnnuncio(PDO $pdo, int $id_annuncio, array $files): void
  {
    $sql = "SELECT COUNT(*) FROM Immagini_Annunci WHERE id_annuncio = ?";
    $stm = $pdo->prepare($sql);
    $stm->execute([$id_annuncio]);
    $trovate = (int) $stm->fetchColumn();

    if ($trovate >= 3) {
      $_SESSION['errors'][] = 'Hai già caricato il massimo di 3 immagini!';
      return;
    }

    // sistemiamo l'array dei file
    $files = $this->normalizza($files);

    if (empty($files)) {
      $_SESSION['errors'][] = 'Nessun file ricevuto';
      return;
    }

    $salvati = 0;
    $immagini_rimaste = $this->max_image - $trovate;

    foreach ($files as $file) {
      if ($salvati >= $immagini_rimaste) {
        $_SESSION['errors'][] = "Limite di 3 immagini raggiunto. File extra ignorati.";
        break;
      }

      $errore = $this->valida($file);
      if ($errore !== "") {
        $_SESSION['errors'][] = $errore;
        continue;
      }

      $nome_file  = $this->genera_nome('ann', $id_annuncio, $file['name']);
      $dest       = $this->dir_annuncio . $nome_file;

      if (move_uploaded_file($file['tmp_name'], $dest)) {
        chmod($dest, 0644);
        $sql = "INSERT INTO Immagini_Annunci (id_annuncio, nome_file) VALUES (?, ?)";
        $stm = $pdo->prepare($sql);
        $stm->execute([$id_annuncio, $nome_file]);
        $salvati++;
      } else {
        $_SESSION['error'][] = "Errore nel salvataggio di \"{$file['name']}\".";
      }
    }

    if ($salvati > 0) {
      $_SESSION['success'] = $salvati === 1
        ? "1 foto caricata con successo!"
        : "{$salvati} foto caricate con successo!";
    }
  }

  /**
   * Elimina tutti i file fisici di un annuncio.
   * Il DB viene pulito automaticamente dall'ON DELETE CASCADE sull'annuncio.
   * Da chiamare PRIMA del DELETE sull'annuncio se vuoi cancellare i file fisici.
   *
   * @param PDO $pdo
   * @param int $id_annuncio
   */
  public function eliminaImmaginiAnnuncio(PDO $pdo, int $id_annuncio): void
  {
    $stmt = $pdo->prepare("SELECT nome_file FROM Immagini_Annunci WHERE id_annuncio = ?");
    $stmt->execute([$id_annuncio]);
    $files = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($files as $f) {
      $path = $this->dir_annuncio . $f;
      if (file_exists($path)) {
        @unlink($path);
      }
    }
  }

  /**
   * Salva l'avatar di uno studente e aggiorna la colonna `foto` in Studenti.
   * Se lo studente aveva già un avatar, il vecchio file fisico viene eliminato.
   * Input: $_FILES['avatar'] (file singolo, name="avatar")
   *
   * @param  PDO    $pdo
   * @param  int    $id_studente
   * @param  array  $file        $_FILES['avatar']
   * @param  string $vecchia_foto Nome del file attuale (da Studenti.foto), per unlink
   * @return void  — usa $_SESSION['errors'] e $_SESSION['success']
   */
  public function salvaAvatar(PDO $pdo, int $id_studente, array $file, string $vecchia_foto = ''): void
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
        @unlink($vecchioPath);
      }
    }

    $nomeFile = $this->genera_nome('user', $id_studente, $file['name']);
    $dest     = $this->dir_users . $nomeFile;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
      $_SESSION['errors'][] = "Errore nel salvataggio della foto profilo.";
      return;
    }

    chmod($dest, 0644);

    // Aggiorna la colonna foto in Studenti
    $stmt = $pdo->prepare("UPDATE Studenti SET foto = ? WHERE id_studente = ?");
    $stmt->execute([$nomeFile, $id_studente]);

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
   * @param  array  $file L'array del singolo file (estratto da normalizza())[cite: 1].
   * @return string|bool Restituisce il messaggio di errore se la validazione fallisce, 
   *                altrimenti restituisce una stringa vuota ('')[cite: 1].
   */
  private function valida(array $file): string
  {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
      return "Errore nel caricamento di \"{$file['name']}\".";
    }

    if (($file['size'] ?? 0) > $this->max_size) {
      return "Il file \"{$file['name']}\" supera i 2MB.";
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
