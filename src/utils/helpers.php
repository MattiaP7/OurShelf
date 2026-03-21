<?php

/**
/**
 * safe_string - Ritorna una stringa sicura, converte caratteri speciali in valori HTML, da usare nelle view html.
 * Per esempio se il nostro valore da stampare è "<a href=\"\">link</a>" con echo sarà un link ma con sprint sarà il valore in sè, faremo `echo safe_string(stringa)`.
 * 
 *
 * @param string $value valore da stampare in modo sicuro
 * @return string stringa di caratteri sicuri
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 20/03/2026
 */
<<<<<<<< HEAD:src/utils/helpers.php
function safe_string(string $value): string
========
function sprint(string|array $value): void
>>>>>>>> e375c6c (Update generale):src/core/helpers.php
{
  echo htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
