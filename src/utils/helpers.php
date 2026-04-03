<?php

/**
 * Converte caratteri speciali in entità HTML per una stampa sicura nelle View.
 * 
 * Protegge l'applicazione da attacchi XSS (Cross-Site Scripting). 
 * Esempio: trasformando `"<a>"` in `"&lt;a&gt;"`, il browser visualizzerà 
 * il testo letterale invece di interpretarlo come un link o script.
 *
 * @param string $value La stringa grezza da convertire.
 * @return string La stringa convertita in entità HTML sicure.
 * 
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 20/03/2026
 */
function safe_string(?string $value): string
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$project_root = str_replace('/src', '', $script_dir);
define('BASE_URL', rtrim($protocol . $host . $project_root, '/'));