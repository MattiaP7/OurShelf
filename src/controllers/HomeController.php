<?php
defined("APP") or die("Accesso negato");

class HomeController
{
  public function index()
  {
    $page_title = 'Home Page';

    include __DIR__ . "/../views/layout/header.php";
    include __DIR__ . "/../views/home/index.php";
    include __DIR__ . "/../views/layout/footer.php";
  }
}
