<?php
defined("APP") or die("Impossibile accedere");

class HomeController
{
  private $page;

  public function __construct()
  {
    $this->page = 'home';
  }

  public function index()
  {
    include __DIR__ . "/../views/home/index.php";
  }
}
