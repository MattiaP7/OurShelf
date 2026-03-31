<?php
defined("APP") or die("Accesso negato");

class HomeController
{
  public function index()
  {
    include __DIR__ . "/../views/layout/header.php";
    include __DIR__ . "/../views/home/main_page.php";
    include __DIR__ . "/../views/layout/footer.php";
  }
}
