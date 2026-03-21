<?php

/**
 * Classe Validator per la validazione automatizzata dei dati.
 * 
 * Questa classe fornisce un motore di validazione leggero per controllare
 * la conformità dei dati (tipicamente provenienti da $_POST) rispetto
 * a un set di regole predefinite.
 *
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 20/03/2026
 */
class Validator
{
  /** @var array Contenitore degli errori riscontrati (campo => messaggio) */
  private $errors = [];

  /**
   * Esegue la validazione dei dati in base alle regole fornite.
   * 
   * La funzione itera su ogni campo definito nelle regole. Per ogni campo,
   * la stringa delle regole (es. "required|min:8") viene esplosa in un array.
   * Il motore controlla la presenza di parametri (es. dopo i due punti ':')
   * e applica i test logici corrispondenti, accumulando eventuali errori.
   *
   * @param array $data Insieme dei dati da validare (es: $_POST).
   * @param array $rules Array associativo dove la chiave è il campo e il valore è la stringa delle regole separate da pipe.
   * @return bool Ritorna true se tutti i dati sono validi, false se sono presenti errori.
   * 
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 20/03/2026
   */
  public function validate(array $data, array $rules): bool
  {
    foreach ($rules as $field => $rule_list) {
      // Trasforma la stringa "rule1|rule2" in un array ['rule1', 'rule2']
      $rules_array = explode('|', $rule_list);

      foreach ($rules_array as $rule) {
        // Recupera il valore dal dataset originale usando la chiave del campo
        $value = $data[$field] ?? null;

        // Validazione: Campo Obbligatorio
        if ($rule === 'required' && empty($value)) {
          $this->addError($field, "Il campo {$field} è obbligatorio.");
        }

        // Validazione: Formato Email
        if ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $this->addError($field, "Inserisci un indirizzo email valido.");
        }

        // Validazione: Complessità Password (Regex per maiuscole, minuscole, numeri, simboli)
        if ($rule === 'secure_password' && !empty($value)) {
          // i regex fanno una ricerca nella stringa per cercare serie di valori, come lettere maiuscole, minuscole, caratteri spieciali, ...
          $uppercase = preg_match('@[A-Z]@', $value);
          $lowercase = preg_match('@[a-z]@', $value);
          $number    = preg_match('@[0-9]@', $value);
          $special   = preg_match('@[^\w]@', $value);

          if (!$uppercase || !$lowercase || !$number || !$special) {
            $this->addError($field, "La password deve contenere una maiuscola, un numero e un carattere speciale.");
          }
        }

        // Validazione: Lunghezza Minima (Estrae il parametro numerico dopo 'min:')
        // cerchiamo nella regola 'min:' e prendiamo il valore successivo (il numero di char).
        if (strpos($rule, 'min:') === 0) {
          $min = (int) str_replace('min:', '', $rule);
          if (strlen($value ?? '') < $min) {
            $this->addError($field, "Il campo {$field} deve avere almeno {$min} caratteri.");
          }
        }
      }
    }
    // Ritorna true solo se l'array degli errori è rimasto vuoto
    return empty($this->errors);
  }

  /**
   * Aggiunge un messaggio di errore all'array interno.
   *
   * @param string $field Il nome del campo che ha fallito la validazione.
   * @param string $message Il messaggio descrittivo dell'errore.
   * @return void
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 20/03/2026
   */
  private function addError(string $field, string $message)
  {
    $this->errors[$field] = $message;
  }

  /**
   * Restituisce l'elenco degli errori accumulati durante la validazione.
   *
   * @return array Array associativo del tipo ['nome_campo' => 'messaggio_errore'].
   * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
   * @date 20/03/2026
   */
  public function getErrors(): array
  {
    return $this->errors;
  }
}
