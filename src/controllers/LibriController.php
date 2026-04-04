<?php
defined("APP") or die("Accesso negato");

require_once __DIR__ . '/../models/LibriModels.php';

class LibriController
{
    private $model;

    public function __construct()
    {
        $this->model = new LibriModels();
    }

    public function typeLibri(): void
    {
        $ISBN    = $_POST['ISBN'];
        $titolo  = $_POST['titolo'];
        $materia = $_POST['materia'];
        $editore = $_POST['editore'];

        $param = [$ISBN,  $titolo, $materia, $editore];
   
        $annunci = $this->model->selectLibri($param);
        include __DIR__ . '/../views/layout.php';

    }
}