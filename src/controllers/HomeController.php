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
    //    $annunci = $this->model->selectAllLibri();
    $view = 'views/home/home_page.php';
    include 'views/layout.php';
  }

  public function lista()
  {
    include 'views/layout.php';
  }
}
