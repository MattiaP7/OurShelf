<?php
defined("APP") or die("Accesso negato");

class LoginController
{
    public function index()
    {
        $view = 'views/login/a.php';
        include 'views/layout.php';
    }
}
