<?php
defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../models/LibriModels.php';

class HomeController
{
  private $model;

  public function __construct()
  {
    $this->model = new LibriModels();
  }

  public function index(): void
  {
    $view = "views/layout/main_view.php";
    include 'views/layout.php';
  }
  public function about(): void
  {
    $view = "views/link/chi_siamo.php";
    include 'views/layout.php';
  }
}
