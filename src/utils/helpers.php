<?php


/**
 * safe_string - Ritorna una stringa sicura, converte caratteri speciali in valori HTML, da usare nelle view html.
 * Per esempio se il nostro valore da stampare è "<a href=\"\">link</a>" con echo sarà un link ma con sprint sarà il valore in sè, faremo `echo safe_string(stringa)`.
 * 
 * 
 * @param string $value
 * @return string
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 20/03/2026
 */
function safe_string(string $value): string
{
  return htmlspecialchars($value, ENT_QUOTES, "utf-8");
}
