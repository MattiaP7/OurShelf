<?php
defined("APP") or die("Accesso negato");

class HomeController
{
  public function index()
  {
    $view = 'views/home/index.php';
    include 'views/layout.php';
  }
}
