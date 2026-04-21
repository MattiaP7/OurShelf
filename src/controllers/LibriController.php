<?php
defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../models/LibriModels.php';

/**
 * Classe LibriController
 * Gestisce la ricerca e la verifica dei libri di testo nel catalogo scolastico.
 * Espone anche un endpoint AJAX per la verifica dell'ISBN durante la scansione
 * tramite fotocamera o l'inserimento manuale nel form di pubblicazione annuncio.
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 17/04/2026
 */
class LibriController
{
  /** @var LibriModels Istanza del modello libri */
  private $model;

  /**
   * Inizializza il controller e il suo modello di riferimento.
   *
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function __construct()
  {
    $this->model = new LibriModels();
  }

  /**
   * Mostra il catalogo dei libri adottati dalla classe dello studente loggato.
   *
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 17/04/2026
   */
  public function index(): void
  {
    requireLogin();

    $libri = $this->model->getLibriByClasse($_SESSION['id_classe']);

    $view = __DIR__ . '/../views/libri/catalogo.php';
    include __DIR__ . '/../views/layout.php';
  }
}
