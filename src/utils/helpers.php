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


/**
 * permette di creare l'url del nostro sito e salvarlo in una costante chiamata BASE_URL, in modo tale che
 * quando andiamo a creare dei link nelle pagina per associare per esempio, pagine.css, img ecc.. al posto 
 * di scrivere tutto il percorso e cambiarlo ogni volta in base a dove spostiamo i file...scriviamo questa 
 * costante e gli aggiungiamo solo la parte di percorso che ci interessa per raggiungere il file o la cartella
 * desiderati...questo metodo è molto più dinamico e sicuro 
 * 
 * @author Matteo Portacci <portacci.7780@isit100.fe.it>
 * @return void
 */
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$project_root = str_replace('/src', '', $script_dir);
define('BASE_URL', rtrim($protocol . $host . $project_root, '/'));