<?php

/**
 * File principale che si occupa del page routing
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 */

define("APP", true);
require_once 'utils/helpers.php';

$msg = "<a href=\"\">link</a>";

echo "Messaggio con echo: {$msg} <br>";
echo "Messaggio con safe string: " . safe_string($msg);
