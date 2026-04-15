<?php

/**
 * File principale che si occupa del page routing
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 */
define("APP", true);

session_start();

require_once __DIR__ . '/utils/helpers.php';

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';

$filename = ucfirst($page) . 'Controller';
$path = __DIR__ . "/controllers/{$filename}.php";

if (file_exists($path)) {
  require_once $path;
  if (class_exists($filename)) {
    $controller = new $filename();

    if (method_exists($controller, $action)) {
      $controller->$action();
    } else {
      die("Errore: action '$action' non esiste nel controller '$filename'.");
    }
  }
} else {
  header("Location: index.php?page=home");
  exit;
}