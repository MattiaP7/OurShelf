<?php


/**
 * sprint - Safe Print, stampa una stringa in modo sicuro, converte caratteri speciali in valori HTML.
 * Per esempio se il nostro valore da stampare è "<a href=\"\">link</a>" con echo sarà un link ma con sprint sarà il valore in sè.
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
