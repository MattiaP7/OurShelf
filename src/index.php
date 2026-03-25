<?php

/**
 * File principale che si occupa del page routing
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 */
define("APP", true);

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';

$filename = ucfirst($page) . 'Controller';
$path = __DIR__ . "/controllers/{$filename}.php";

if (file_exists($path)) {
  require_once $path;
  $controller = new $filename();
  if (method_exists($controller, $action)) {
    $controller->$action();
  } else {
    die("Il metodo '{$action}' non esiste nel controller '{$filename}'");
  }
} else {
  die("Il controller non esiste: {$path}");
}
